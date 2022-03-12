<?php

declare(strict_types=1);

namespace SOFe\Depin\Example;

use SOFe\Depin\Registry;

#[Registry\Target]
interface Command {
    public function getName() : string;

    /**
     * @param list<string> $args
     */
    public function handle(array $args) : void;
}
