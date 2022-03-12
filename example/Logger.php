<?php

declare(strict_types=1);

namespace SOFe\Depin\Example;

use Closure;

final class Logger {
    /**
     * @param Closure(string): void $collector
     */
    public function __construct(private string $context, private Closure $collector) {
    }

    public function log(string $message) {
        ($this->collector)("[$this->context] $message");
    }
}
