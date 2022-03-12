<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Generator;
use RuntimeException;


/**
 * A scope represents the user of a dependency to inject for.
 *
 * A scope consists of a parent (null for the root scope)
 * and a final-typemap of "metadata" which identify the scope.
 * Metadata are inherited from the parent metadata map
 * and can be overridden in the child scope.
 * Changes to the parent scope metadata are reflected in the child scope.
 */
final class Scope {
    /**
     * @param array<class-string<object>, object> $metadata
     */
    private function __construct(
        private ?Scope $parent,
        private array $metadata,
    ) {
        foreach ($metadata as $key => $object) {
            if (!$object instanceof $key) {
                throw new RuntimeException("Metadata must be of type $key");
            }
        }
    }

    public static function root() : self {
        return new self(null, []);
    }

    public function getParent() : ?Scope {
        return $this->parent;
    }

    /**
     * @return Generator<int, Scope>
     */
    public function traverse() : Generator {
        yield $this;
        if ($this->parent !== null) {
            yield from $this->parent->traverse();
        }
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T|null
     */
    public function getMetadata(string $class) : ?object {
        if (isset($this->metadata[$class])) {
            /** @var T $metadata */
            $metadata = $this->metadata[$class];
            return $metadata;
        }

        return null;
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T|null
     */
    public function findMetadata(string $class) : ?object {
        foreach ($this->traverse() as $scope) {
            $object = $scope->getMetadata($class);
            if ($object !== null) {
                return $object;
            }
        }

        return null;
    }

    /**
     * Creates a child scope with the given metadata.
     *
     * @param array<class-string<object>, object> $metadata
     */
    public function createChild(array $metadata) : self {
        return new self($this, $metadata);
    }

    /**
     * Adds or updates the metadata for the current scope.
     * Changes are reflected in descendents if not overridden.
     *
     * @template T of object
     * @param class-string<T> $key
     * @param T $metadatum
     */
    public function setMetadatum(string $key, object $metadatum) : void {
        if (!$metadatum instanceof $key) {
            throw new RuntimeException("Metadata must be of type $key");
        }

        $this->metadata[$key] = $metadatum;
    }
}
