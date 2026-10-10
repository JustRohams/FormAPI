<?php

declare(strict_types=1);

namespace RohaM\GiftCode\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use RohaM\GiftCode\Loader;

class GiftCreateCommand extends Command {

    private Loader $plugin;

    public function __construct(Loader $plugin) {
        parent::__construct(
            "giftcreate",
            "Create a new gift code",
            "/giftcreate <amount> <code>",
            ["gcreate"]
        );
        $this->setPermission("giftcode.create");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$this->testPermission($sender)) {
            return true;
        }

        if (count($args) < 2) {
            $sender->sendMessage($this->plugin->getMessage("usage-create"));
            return true;
        }

        $amountRaw = $args[0];
        $code = $args[1];

        if (!is_numeric($amountRaw) || (int)$amountRaw <= 0) {
            $sender->sendMessage($this->plugin->getMessage("invalid-amount"));
            return true;
        }

        if (!preg_match('/^[A-Za-z0-9_]{1,32}$/', $code)) {
            $sender->sendMessage($this->plugin->getMessage("invalid-code"));
            return true;
        }

        $amount = (int)$amountRaw;
        $code = strtoupper($code);

        if ($this->plugin->getGiftManager()->createGift($code, $amount)) {
            $sender->sendMessage($this->plugin->getMessage("created", [
                "code" => $code,
                "amount" => $amount
            ]));
        } else {
            $sender->sendMessage($this->plugin->getMessage("already-exists", [
                "code" => $code
            ]));
        }

        return true;
    }
}