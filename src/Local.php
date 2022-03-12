<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Attribute;

/**
 * Marks a class to have scope-unique characteristics in the context of `to`.
 */
#[Attribute(flags: Attribute::TARGET_CLASS)]
final class Local {
    /**
     * @param class-string<object> $to The scope that the singleton is unique for.
     * Use `null` if the singleton is globally unique for the context.
     */
    public function __construct(
        public string $to,
    ) {
    }
}
