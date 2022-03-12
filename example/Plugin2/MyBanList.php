<?php

declare(strict_types=1);

namespace SOFe\Depin\Example;

final class MyBanList implements BanList {
    /** @var array<string, true> */
    private array $banned = [];

    public function ban(string $name) : void {
        $this->banned[$name] = true;
    }

    public function isBanned(string $name) : bool {
        return isset($this->banned[$name]);
    }
}
