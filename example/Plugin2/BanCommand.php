<?php

declare(strict_types=1);

namespace SOFe\Depin\Example\Plugin1;

use SOFe\Depin\ConstructorArgs;
use SOFe\Depin\Example\BanList;
use SOFe\Depin\Example\Command;
use SOFe\Depin\Example\Logger;

final class BanCommand implements Command {
    use ConstructorArgs;

    public function __construct(
        private BanList $banList,
        private Logger $logger,
    ) {
    }

    public function getName() : string {
        return "ban";
    }

    public function handle(array $args) : void {
        $this->banList->ban($args[0]);
    }
}
