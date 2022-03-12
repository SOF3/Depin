<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Generator;
use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;
use SOFe\AwaitGenerator\Await;

use function class_exists;
use function get_class;
use function interface_exists;
use function sprintf;

/**
 * Implements `FromContext` by calling the constructor
 * with arguments provided from the context.
 */
trait ConstructorArgs {
    public static function fromContext(Context $context, Scope $scope) : Generator {
        $class = new ReflectionClass(static::class);
        $constructor = $class->getConstructor();

        if ($constructor === null) {
            return $class->newInstance();
        }

        $args = [];
        foreach ($constructor->getParameters() as $param) {
            $paramType = $param->getType();
            if ($paramType === null) {
                throw new RuntimeException(sprintf('Error resolving parameter $%s for %s::__construct() because it has no type hint', $param->getName(), $class->getName()));
            }

            if (!$paramType instanceof ReflectionNamedType) {
                throw new RuntimeException(sprintf('Error resolving parameter $%s for %s::__construct(): union types are not supported', $param->getName(), $class->getName()));
            }

            $paramClass = $paramType->getName();
            if (!class_exists($paramClass) && !interface_exists($paramClass)) {
                throw new RuntimeException(sprintf('Error resolving parameter $%s for %s::__construct(): %s is not a class or interface', $param->getName(), $class->getName(), $paramClass));
            }

            $subscope = $scope->createChild([
                Metadata\MethodRequester::class => new Metadata\MethodRequester(
                    class: $class->getName(),
                    method: "__construct",
                    paramName: $param->getName()),
            ]);
            foreach ($param->getAttributes() as $attribute) {
                $subscope->setMetadatum(get_class($attribute), $attribute);
            }

            $args[] = $context->request($subscope, $paramClass);
        }

        try {
            return $class->newInstance(yield from Await::all($args));
        } catch (NoProviderException $e) {
            throw new RuntimeException(sprintf('Error resolving parameter $%s for %s::__construct(): %s', $param->getName(), $class->getName(), $e->getMessage()));
        }
    }
}
