<?php

declare(strict_types=1);

namespace RohaM\Binder\utils;

use pocketmine\utils\TextFormat as TF;
use RohaM\Binder\Loader;

class Banner{

    public static function show(Loader $plugin) : void{
        $logger = $plugin->getLogger();

        $art = [
            " ____    _    ____  _   _ _  _______ ",
            "/ ___|  / \\  / ___|| | | | |/ / ____|",
            "\\___ \\ / _ \\ \\___ \\| | | | ' /|  _|  ",
            " ___) / ___ \\ ___) | |_| | . \\| |___ ",
            "|____/_/   \\_\\____/ \\___/|_|\\_\\_____|",
        ];

        $colors = [TF::AQUA, TF::LIGHT_PURPLE, TF::YELLOW, TF::GOLD, TF::RED];

        $logger->info(TF::GRAY . "==========================================");

        foreach($art as $i => $line){
            $logger->info($colors[$i % count($colors)] . $line);
        }

        $logger->info(TF::GRAY . "==========================================");
        $logger->info(TF::WHITE . "Plugin  : " . TF::GREEN . "Binder " . TF::WHITE . "v" . $plugin->getDescription()->getVersion());
        $logger->info(TF::WHITE . "Author  : " . TF::AQUA . "RohaM");
        $logger->info(TF::WHITE . "API     : " . TF::AQUA . implode(", ", $plugin->getDescription()->getCompatibleApis()));
        $logger->info(TF::WHITE . "Status  : " . TF::GREEN . "Loaded successfully!");
        $logger->info(TF::GRAY . "==========================================");
    }
}
