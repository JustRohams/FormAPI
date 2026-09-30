<?php

declare(strict_types=1);

namespace RohaM\Binder\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use RohaM\Binder\forms\AddForm;
use RohaM\Binder\forms\ListForm;
use RohaM\Binder\Loader;

class BinderCommand extends Command{

    private Loader $plugin;

    public function __construct(Loader $plugin){
        parent::__construct("binder", "Open your Binder menu", "/binder [add|clear]");
        $this->setPermission("binder.use");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if(!$this->testPermission($sender)){
            return false;
        }

        if(!$sender instanceof Player){
            $sender->sendMessage(TF::RED . "This command can only be used in-game.");
            return false;
        }

        $sub = strtolower($args[0] ?? "");

        switch($sub){
            case "add":
                AddForm::send($sender, $this->plugin);
                break;

            case "clear":
                $this->plugin->getDataManager()->clearEntries($sender->getName());
                $sender->sendMessage(TF::GREEN . "Your binder has been cleared!");
                break;

            case "":
                ListForm::send($sender, $this->plugin);
                break;

            default:
                $sender->sendMessage(TF::RED . "Unknown usage. Try " . TF::YELLOW . "/binder" . TF::RED . " or " . TF::YELLOW . "/binder add");
                break;
        }

        return true;
    }
}
