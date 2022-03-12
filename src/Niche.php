<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Attribute;

/**
 * Marks that an interface should be implemented by exactly one singleton object.
 *
 * Use the `Local` attribute if the niche is only unique for a specific scope.
 */
#[Attribute(flags: Attribute::TARGET_CLASS)]
final class Niche {
    /**
     * @param class-string<FromRequest> $default The default implementation to use if none is provided.
     */
    public function __construct(
        public ?string $default = null,
    ) {
    }
}
