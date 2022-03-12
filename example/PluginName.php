<?php

declare(strict_types=1);

namespace SOFe\Depin\Example;

use SOFe\Depin;

#[Depin\Singleton]
#[Depin\Local(to: PluginName::class)]
final class PluginName {
    public function __construct(public string $name) {
    }
}
