<?php

declare(strict_types=1);

namespace RohaM\Logger\managers;

use RohaM\Logger\Loader;
use RohaM\Logger\loggers\PlayerLogger;

class LoggerManager {

    private Loader $plugin;
    private string $folder;

    /** @var array<string, PlayerLogger> */
    private array $loggers = [];

    public function __construct(Loader $plugin, string $folder) {
        $this->plugin = $plugin;
        $this->folder = rtrim($folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (!is_dir($this->folder)) {
            @mkdir($this->folder, 0777, true);
        }
    }

    public function getLogger(string $playerName): PlayerLogger {
        $key = strtolower($playerName);
        if (!isset($this->loggers[$key])) {
            $this->loggers[$key] = new PlayerLogger(
                $this->plugin,
                $this->folder . $playerName . ".json"
            );
        }
        return $this->loggers[$key];
    }

    public function logChat(string $playerName, string $message): bool {
        if (!(bool) $this->plugin->getPluginConfig()->get("log-chat", true)) {
            return false;
        }
        if ($this->isIgnoredPlayer($playerName)) {
            return false;
        }
        $this->getLogger($playerName)->addEntry("chat", $message);
        return true;
    }

    public function logCommand(string $playerName, string $rawCommand): bool {
        if (!(bool) $this->plugin->getPluginConfig()->get("log-commands", true)) {
            return false;
        }
        if ($this->isIgnoredPlayer($playerName)) {
            return false;
        }

        $trimmed = ltrim(trim($rawCommand), "/");
        $commandName = strtolower(explode(" ", $trimmed)[0] ?? "");
        if ($commandName === "") {
            return false;
        }

        $ignored = (array) $this->plugin->getPluginConfig()->get("ignored-commands", []);
        foreach ($ignored as $ignoredCmd) {
            if (strtolower((string) $ignoredCmd) === $commandName) {
                return false;
            }
        }

        $this->getLogger($playerName)->addEntry("command", $rawCommand);
        return true;
    }

    private function isIgnoredPlayer(string $playerName): bool {
        $ignored = (array) $this->plugin->getPluginConfig()->get("ignored-players", []);
        foreach ($ignored as $name) {
            if (strtolower((string) $name) === strtolower($playerName)) {
                return true;
            }
        }
        return false;
    }

    public function saveAll(): void {
        foreach ($this->loggers as $logger) {
            $logger->save();
        }
    }

    public function unload(string $playerName): void {
        $key = strtolower($playerName);
        if (isset($this->loggers[$key])) {
            $this->loggers[$key]->save();
            unset($this->loggers[$key]);
        }
    }

    public function clear(): void {
        $this->loggers = [];
    }

    public function clearLogs(string $playerName): bool {
        $path = $this->folder . $playerName . ".json";
        if (is_file($path)) {
            return @unlink($path);
        }
        return false;
    }

    public function getFolder(): string {
        return $this->folder;
    }

    /** @return array<string, PlayerLogger> */
    public function getLoaded(): array {
        return $this->loggers;
    }
}