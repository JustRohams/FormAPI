<?php

declare(strict_types=1);

namespace RohaM\PlayerStatus;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase {

    private const DEVICE_OS = [
        0 => "Unknown", 1 => "Android", 2 => "iOS", 3 => "macOS",
        4 => "FireOS", 5 => "GearVR", 6 => "HoloLens", 7 => "Windows 10",
        8 => "Windows", 9 => "Dedicated", 10 => "Orbis", 11 => "Playstation 4",
        12 => "Nintendo Switch", 13 => "Xbox One"
    ];

    private const CONTROLS = [
        0 => "Unknown", 1 => "Mouse & Keyboard", 2 => "Touch", 3 => "Controller"
    ];

    public function onEnable(): void {
        $this->showAsciiArt();

        if($this->getServer()->getPluginManager()->getPlugin("ScoreHud") !== null) {
            $this->getServer()->getPluginManager()->registerEvents(new ScorehudEvent(), $this);
            $this->getLogger()->info("ScoreHud integration enabled!");
        }
        
        $this->getLogger()->info("PlayerStatus plugin enabled successfully!");
    }

    private function showAsciiArt(): void {
    $art = [
        TextFormat::RED . "____       _           __  __",
        TextFormat::RED . "|  _ \ ___ | |__   __ _|  \/  |",
        TextFormat::WHITE . "| |_) / _ \| '_ \ / _` | |\/| |",
        TextFormat::WHITE . "|  _ < (_) | | | | (_| | |  | |",
        TextFormat::GREEN . "|_| \_\___/|_| |_|\__,_|_|  |_|",
        TextFormat::GREEN . "    Plugin by RohaM | Version 1.0.0"
    ];
    
    foreach($art as $line) {
        $this->getLogger()->info($line);
    }
}

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if($command->getName() !== "player") {
            return false;
        }

        if(empty($args)) {
            $sender->sendMessage(TextFormat::RED . "Usage: /player <name>");
            return false;
        }

        $name = implode(" ", $args);
        $target = Server::getInstance()->getPlayerByPrefix($name);

        if(!$target instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Player not found!");
            return false;
        }

        $deviceId = $target->getPlayerInfo()->getExtraData()["DeviceOS"] ?? 0;
        $controlId = $target->getPlayerInfo()->getExtraData()["CurrentInputMode"] ?? 0;
        $deviceModel = $target->getPlayerInfo()->getExtraData()["DeviceModel"] ?? "Unknown";
        $ping = $target->getNetworkSession()->getPing();

        $message = TextFormat::GRAY . "═══ Player Status ═══" . PHP_EOL .
                   TextFormat::AQUA . "Name: " . TextFormat::WHITE . $target->getName() . PHP_EOL .
                   TextFormat::AQUA . "Device: " . TextFormat::WHITE . (self::DEVICE_OS[$deviceId] ?? "Unknown") . PHP_EOL .
                   TextFormat::AQUA . "Device Model: " . TextFormat::WHITE . $deviceModel . PHP_EOL .
                   TextFormat::AQUA . "Control: " . TextFormat::WHITE . (self::CONTROLS[$controlId] ?? "Unknown") . PHP_EOL .
                   TextFormat::AQUA . "Ping: " . TextFormat::WHITE . $ping . "ms" . PHP_EOL .
                   TextFormat::GRAY . "══════════════════════";

        $sender->sendMessage($message);
        return true;
    }
}