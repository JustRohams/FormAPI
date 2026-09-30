<?php

declare(strict_types=1);

namespace RohaM\Binder\forms;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use RohaM\Binder\Loader;

class ListForm{

    public static function send(Player $player, Loader $plugin) : void{
        $entries = $plugin->getDataManager()->getEntries($player->getName());

        $form = new SimpleForm(function(Player $player, ?int $data) use ($plugin, $entries) : void{
            if($data === null || !isset($entries[$data])){
                return;
            }

            $text = $entries[$data];

            if($plugin->getConfig()->get("broadcast", true) === true){
                $plugin->getServer()->broadcastMessage(TF::AQUA . $player->getName() . TF::GRAY . " » " . TF::WHITE . $text);
            }else{
                $player->sendMessage(TF::WHITE . $text);
            }
        });

        $form->setTitle(TF::GOLD . "" . TF::BOLD . "Binder");

        if(empty($entries)){
            $form->setContent(
                TF::YELLOW . "You don't have any saved texts yet.\n" .
                TF::GRAY . "Use " . TF::WHITE . "/binder add" . TF::GRAY . " to create one!"
            );
        }else{
            $form->setContent(TF::GRAY . "Click a button to send that text in chat.");

            foreach($entries as $text){ 25 ? mb_substr($text, 0, 25) . "..." : $text;
                $form->addButton(TF::WHITE . $label);
            }
        }

        $player->sendForm($form);
    }
}
