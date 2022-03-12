<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Generator;

/**
 * Classes that can be constructed from a `Context`.
 *
 * Use the `ConstructorArgs` trait to implement this class
 * by calling the constructor with args from the context.
 */
interface FromContext {
    /**
     * Constructs this class from a context.
     *
     * @return Generator<mixed, mixed, mixed, static>
     */
    public static function fromContext(Context $context, Scope $scope) : Generator;
}
