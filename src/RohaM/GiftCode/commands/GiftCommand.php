<?php

declare(strict_types=1);

namespace RohaM\GiftCode\commands;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use RohaM\GiftCode\Loader;

class GiftCommand extends Command {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        parent::__construct(
            "gift",
            "Redeem a gift code",
            "/gift <code>"
        );
        $this->setPermission("giftcode.use");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage($this->plugin->getMessage("in-game-only"));
            return true;
        }

        if (count($args) < 1) {
            $sender->sendMessage($this->plugin->getMessage("usage-gift"));
            return true;
        }

        $code = strtoupper($args[0]);
        $amount = $this->plugin->getGiftManager()->getGift($code);

        if ($amount === null) {
            $sender->sendMessage($this->plugin->getMessage("invalid-gift"));
            return true;
        }

        EconomyAPI::getInstance()->addMoney($sender, $amount);
        $this->plugin->getGiftManager()->removeGift($code);

        $sender->sendMessage($this->plugin->getMessage("redeemed", [
            "amount" => $amount
        ]));

        return true;
    }
}