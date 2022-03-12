<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Generator;

/**
 * Provides an instance of a requested type in a given scope.
 */
interface Provider {
    /**
     * Indicates the preferred loading order of this provider.
     * The priority only needs to be specified in one of the two classes.
     *
     * Behaviour is unspecified if there is cyclic dependency.
     */
    public function comparePriority(Provider $other) : ProviderPriority;

    /**
     * Reports whether this provider can provide the requested type.
     *
     * @param class-string<object> $class
     */
    public function supports(string $class) : bool;

    /**
     * Provides an instance of the requested type.
     * This method is only called when `supports($class)` returns true,
     * so it should be infallible.
     *
     * @template T of object
     * @param class-string<T> $class
     * @return Generator<mixed, mixed, mixed, T>
     */
    public function provide(Context $context, Scope $scope, string $class) : Generator;
}
