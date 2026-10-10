<?php

declare(strict_types=1);

namespace RohaM\GiftCode;

use pocketmine\plugin\PluginBase;
use RohaM\GiftCode\commands\GiftCommand;
use RohaM\GiftCode\commands\GiftCreateCommand;
use RohaM\GiftCode\manager\GiftManager;

class Loader extends PluginBase {

    private GiftManager $giftManager;

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->giftManager = new GiftManager($this);

        $this->getServer()->getCommandMap()->registerAll("giftcode", [
            new GiftCreateCommand($this),
            new GiftCommand($this)
        ]);
    }

    protected function onDisable(): void {
        $this->giftManager->save();
    }

    public function getGiftManager(): GiftManager {
        return $this->giftManager;
    }

    public function getMessage(string $key, array $replace = []): string {
        $prefix = (string)$this->getConfig()->getNested("messages.prefix", "");
        $msg = (string)$this->getConfig()->getNested("messages." . $key, $key);

        foreach ($replace as $k => $v) {
            $msg = str_replace("{" . $k . "}", (string)$v, $msg);
        }

        return $prefix . $msg;
    }
}