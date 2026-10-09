<?php

declare(strict_types=1);

namespace RohaM\Logger\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerQuitEvent;
use RohaM\Logger\managers\LoggerManager;

class PlayerEventListener implements Listener {

    private LoggerManager $manager;

    public function __construct(LoggerManager $manager) {
        $this->manager = $manager;
    }

    public function onChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        if (!$player->isConnected()) {
            return;
        }
        $this->manager->logChat($player->getName(), $event->getMessage());
    }

    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event): void {
        $player = $event->getPlayer();
        if (!$player->isConnected()) {
            return;
        }
        $this->manager->logCommand($player->getName(), $event->getMessage());
    }

    public function onQuit(PlayerQuitEvent $event): void {
        $this->manager->unload($event->getPlayer()->getName());
    }
}