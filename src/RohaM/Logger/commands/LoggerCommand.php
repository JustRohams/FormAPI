<?php

declare(strict_types=1);

namespace RohaM\Logger\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;
use RohaM\Logger\Loader;

class LoggerCommand extends Command {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        parent::__construct(
            "logger",
            "Manage the Logger plugin.",
            "/logger <view|reload|clear> [player]",
            ["logs", "log"]
        );
        $this->plugin = $plugin;
        $this->setPermission("logger.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$this->testPermission($sender)) {
            return false;
        }

        if (empty($args)) {
            $this->sendUsage($sender);
            return true;
        }

        $sub = strtolower($args[0]);
        switch ($sub) {
            case "view":
                if (!isset($args[1])) {
                    $sender->sendMessage(TF::RED . "Usage: /logger view <player>");
                    return true;
                }
                $name = $args[1];
                $logger = $this->plugin->getLoggerManager()->getLogger($name);
                $sender->sendMessage(Loader::PREFIX . TF::YELLOW . "Logs for " . TF::WHITE . $name
                    . TF::YELLOW . ": " . TF::WHITE . $logger->getCount() . TF::YELLOW . " entries");
                $sender->sendMessage(TF::GRAY . "File: " . $logger->getFilePath());
                return true;

            case "reload":
                $this->plugin->reloadPluginConfig();
                $sender->sendMessage(Loader::PREFIX . TF::GREEN . "Configuration reloaded.");
                return true;

            case "clear":
                if (!isset($args[1])) {
                    $sender->sendMessage(TF::RED . "Usage: /logger clear <player>");
                    return true;
                }
                $name = $args[1];
                $this->plugin->getLoggerManager()->unload($name);
                if ($this->plugin->getLoggerManager()->clearLogs($name)) {
                    $sender->sendMessage(Loader::PREFIX . TF::GREEN . "Logs cleared for " . TF::WHITE . $name);
                } else {
                    $sender->sendMessage(Loader::PREFIX . TF::RED . "No log file found for " . TF::WHITE . $name);
                }
                return true;

            default:
                $this->sendUsage($sender);
                return true;
        }
    }

    private function sendUsage(CommandSender $sender): void {
        $sender->sendMessage(Loader::PREFIX . TF::YELLOW . "Usage: " . TF::WHITE . "/logger <view|reload|clear> [player]");
    }
}