<?php

declare(strict_types = 1);

namespace RohaM\FormAPITest;

use pocketmine\plugin\PluginBase;
use RohaM\FormAPITest\command\FormCommand;

final class FormAPITest extends PluginBase{

    protected function onEnable() : void{
        $this->getServer()->getCommandMap()->register(
            "formapitest",
            new FormCommand($this)
        );

        $this->getLogger()->info("§aFormAPITest enabled — use §e/formtest §ato try Persian forms.");
        $this->getLogger()->info("§7Debug: §f/formtest debug §7| Toggle: §f/formtest toggle");
    }
}