<?php

declare(strict_types=1);

namespace SOFe\Depin;

use Iterator;

/**
 * A registry is the API for getting a list of tracked objects implementing the same interface.
 *
 * When using `Registry` as a dependency parameter,
 * you must specify `#[Registry\Of]` to specify which interface to list.
 *
 * @template T
 */
final class Registry {
    /**
     * @return Iterator<T>
     */
    public function list() : Iterator {
        // TODO implement
    }

    public static function provider() : Provider {
        return new class implements Provider {
            public function comparePriority(Provider $other) : ProviderPriority {
                return ProviderPriority::specific();
            }
        };
    }
}
