<?php

declare(strict_types=1);

namespace RohaM\Binder;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use RohaM\Binder\commands\BinderCommand;
use RohaM\Binder\manager\DataManager;
use RohaM\Binder\utils\Banner;

class Loader extends PluginBase{

    private DataManager $dataManager;

    public function onEnable() : void{
        $this->saveDefaultConfig();
        $this->getConfig();

        $this->dataManager = new DataManager($this);

        $this->getServer()->getCommandMap()->register("binder", new BinderCommand($this));

        Banner::show($this);
    }

    public function onDisable() : void{
        $this->getLogger()->info(TF::YELLOW . "Binder has been disabled. See you next time!");
    }

    public function getDataManager() : DataManager{
        return $this->dataManager;
    }
}
