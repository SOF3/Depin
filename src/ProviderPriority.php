<?php

declare(strict_types=1);

namespace SOFe\Depin;

/**
 * Return value for `$provider->comparePriority($other)`.
 */
final class ProviderPriority {
    public const SPECIFIC = -2;

    /**
     * @var int The signum used for sorting providers.
     * @internal
     */
    public int $signum;

    private function __construct(
        int $signum,
    ) {
        $this->signum = $signum;
    }

    /**
     * This priority should be unconditionally returned for
     * providers that only work on known specific types.
     */
    public static function specific() : self {
        return new self(self::SPECIFIC);
    }

    /**
     * `$provider` wants be loaded before `$other`.
     */
    public static function before() : self {
        return new self(0 <=> 1);
    }

    /**
     * `$provider` wants be loaded after `$other`.
     */
    public static function after() : self {
        return new self(1 <=> 0);
    }

    /**
     * `$provider` wants be loaded after `$other`.
     */
    public static function unspecified() : self {
        return new self(0);
    }
}
