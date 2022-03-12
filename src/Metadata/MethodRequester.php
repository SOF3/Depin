<?php

declare(strict_types=1);

namespace SOFe\Depin\Metadata;

/**
 * Metadatum that stores the requesting scope of a dependency if requested from a method parameter.
 */
final class MethodRequester {
    /**
     * @param string $class The class in which the dependency is requested.
     * @param string $method The method name (without class name) in which the dependency is requested.
     * @param string $paramName The name of the parameter that requested the dependency.
     */
    public function __construct(public string $class, public string $method, public string $paramName) {
    }
}
