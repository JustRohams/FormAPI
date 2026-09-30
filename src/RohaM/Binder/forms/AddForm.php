<?php

declare(strict_types=1);

namespace RohaM\Binder\forms;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use RohaM\Binder\Loader;

class AddForm{

    public static function send(Player $player, Loader $plugin) : void{
        $form = new CustomForm(function(Player $player, ?array $data) use ($plugin) : void{
            if($data === null){
                return;
            }

            $text = trim((string) $data[0]);

            if($text === ""){
                $player->sendMessage(TF::RED . "You can't add an empty text!");
                return;
            }

            $added = $plugin->getDataManager()->addEntry($player->getName(), $text);

            if($added){
                $player->sendMessage(TF::GREEN . "Text added to your binder! Open it with " . TF::YELLOW . "/binder");
            }else{
                $max = (int) $plugin->getConfig()->get("max-entries", 20);
                $player->sendMessage(TF::RED . "Your binder is full! (max {$max} entries, use /binder clear)");
            }
        });

        $form->setTitle(TF::GOLD . "" . TF::BOLD . "Binder - Add Text");
        $form->addInput(TF::WHITE . "Write your text below:", "Type something...", "");

        $player->sendForm($form);
    }
}
