<?php

declare(strict_types=1);

namespace SOFe\Depin\Example;

use SOFe\Depin;

#[Depin\Niche]
interface BanList {
    public function ban(string $name) : void;

    public function isBanned(string $name) : bool;
}
