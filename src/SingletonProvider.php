<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Generator;
use ReflectionClass;
use RuntimeException;
use function count;
use function spl_object_hash;

/**
 * Provides an instance of a requested type in a given scope.
 */
final class SingletonProvider implements Provider {
    /** @var array<class-string, array<string, object>> */
    private array $pool = [];

    public function comparePriority(Provider $other) : ProviderPriority {
        return ProviderPriority::unspecified();
    }

    public function supports(string $class) : bool {
        $reflect = new ReflectionClass($class);
        if (count($reflect->getAttributes(Singleton::class)) > 0) {
            if ($reflect->implementsInterface(FromContext::class)) {
                throw new RuntimeException("Singleton class $class must implement " . FromContext::class);
            }

            return true;
        }

        return false;
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return Generator<mixed, mixed, mixed, T>
     */
    public function provide(Context $context, Scope $scope, string $class) : Generator {
        /** @var class-string<FromContext> $class */

        $reflect = new ReflectionClass($class);

        $localAttrs = $reflect->getAttributes(Local::class);

        if (count($localAttrs) > 0) {
            /** @var Local $local */
            $local = $localAttrs[0]->newInstance();

            $scopeObject = $scope->getMetadata($local->to);
            if ($scopeObject === null) {
                throw new RuntimeException("An instance of $class was requested outside of a $local->to scope.");
            }

            $scopeId = spl_object_hash($scopeObject);
        } else {
            $scopeId = "";
        }

        if (!isset($this->pool[$class])) {
            $this->pool[$class] = [];
        }

        if (!isset($this->pool[$class][$scopeId])) {
            $this->pool[$class][$scopeId] = yield from $class::fromContext($context, $scope);
        }

        /** @var T $value */
        $value = $this->pool[$class][$scopeId];
        return $value;
    }
}
