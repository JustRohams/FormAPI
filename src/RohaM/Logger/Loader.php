<?php

declare(strict_types=1);

namespace RohaM\Logger;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use RohaM\Logger\commands\LoggerCommand;
use RohaM\Logger\listeners\PlayerEventListener;
use RohaM\Logger\managers\LoggerManager;
use RohaM\Logger\tasks\AutoSaveTask;

class Loader extends PluginBase {

    public const PREFIX = "§6[§eLogger§6] §r";

    private Config $config;
    private LoggerManager $loggerManager;

    protected function onLoad(): void {
        @mkdir($this->getDataFolder(), 0777, true);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
    }

    protected function onEnable(): void {
        $this->loggerManager = new LoggerManager(
            $this,
            $this->getDataFolder() . "players" . DIRECTORY_SEPARATOR
        );

        $this->getServer()->getPluginManager()->registerEvents(
            new PlayerEventListener($this->loggerManager),
            $this
        );

        $command = $this->getCommand("logger");
        if ($command !== null) {
            $command->setExecutor(new LoggerCommand($this));
        }

        $interval = (int) $this->config->get("save-interval", 300);
        if ($interval > 0) {
            $this->getScheduler()->scheduleRepeatingTask(
                new AutoSaveTask($this->loggerManager),
                $interval * 20
            );
        }

        $this->getLogger()->info("Logger v" . $this->getDescription()->getVersion() . " enabled — by RohaM");
    }

    protected function onDisable(): void {
        if (isset($this->loggerManager)) {
            $this->loggerManager->saveAll();
            $this->loggerManager->clear();
        }
    }

    public function getLoggerManager(): LoggerManager {
        return $this->loggerManager;
    }

    public function getPluginConfig(): Config {
        return $this->config;
    }

    public function reloadPluginConfig(): void {
        $this->reloadConfig();
        $this->config = $this->getConfig();
    }
}