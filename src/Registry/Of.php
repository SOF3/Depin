<?php

declare(strict_types=1);

namespace SOFe\Depin\Registry;

use Attribute;

/**
 * Indicates the item type of a `Registry` parameter.
 */
#[Attribute(flags: Attribute::TARGET_PARAMETER)]
final class Of {
    public function __construct(
        public string $class,
    ) {
    }
}
