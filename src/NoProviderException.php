<?php

declare(strict_types=1);

namespace SOFe\Depin;

use RuntimeException;

final class NoProviderException extends RuntimeException {
    public function __construct(string $class) {
        parent::__construct("None of the registered Depin providers supports $class");
    }
}
