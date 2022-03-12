<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Attribute;

/**
 * Marks a class as a singleton (specific to the `Local` scope).
 */
#[Attribute(flags: Attribute::TARGET_CLASS)]
final class Singleton {
    public function __construct(
    ) {
    }
}
