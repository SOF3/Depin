<?php

declare(strict_types=1);

namespace SOFe\Depin\Registry;

use Attribute;

/**
 * Marks that an interface is to be implemented by multiple objects.
 * Users can list all implementors by requesting the `Registry` class with the `RegistryTarget` attribute.
 *
 * Use the `Local` attribute if the listing should be collected for a specific scope.
 */
#[Attribute(flags: Attribute::TARGET_CLASS)]
final class Target {
    public function __construct(
    ) {
    }
}
