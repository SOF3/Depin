<?php

declare(strict_types=1);

namespace SOFe\Depin\Example\Plugin1;

use SOFe\Depin\ConstructorArgs;
use SOFe\Depin\Example\BanList;
use SOFe\Depin\Example\Command;
use SOFe\Depin\Example\Logger;
use SOFe\Depin\Example\PluginDescription;
use SOFe\Depin\Example\PluginName;

final class LoginCommand implements Command {
    use ConstructorArgs;

    public function __construct(
        private BanList $banList,
        private Logger $logger,
        private PluginName $pluginName,
        private PluginDescription $pluginDescription,
    ) {
    }

    public function getName() : string {
        return "login";
    }

    public function handle(array $args) : void {
        if ($this->banList->isBanned($args[0])) {
            $this->logger->log("cannot login with banned name");
            return;
        }

        $this->logger->log("logged in as $args[0] through {$this->pluginName->name} v{$this->pluginDescription->version}");
    }
}
