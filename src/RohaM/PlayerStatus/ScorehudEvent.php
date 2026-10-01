<?php

declare(strict_types=1);

namespace RohaM\PlayerStatus;

use pocketmine\event\Listener;
use Ifera\ScoreHud\event\TagsResolveEvent;

class ScorehudEvent implements Listener {

    private const DEVICE_OS = [
        0 => "Unknown", 1 => "Android", 2 => "iOS", 3 => "macOS",
        4 => "FireOS", 5 => "GearVR", 6 => "HoloLens", 7 => "Windows 10",
        8 => "Windows", 9 => "Dedicated", 10 => "Orbis", 11 => "Playstation 4",
        12 => "Nintendo Switch", 13 => "Xbox One"
    ];

    private const CONTROLS = [
        0 => "Unknown", 1 => "Mouse & Keyboard", 2 => "Touch", 3 => "Controller"
    ];

    public function onTagResolve(TagsResolveEvent $event): void {
        $player = $event->getPlayer();
        $tag = $event->getTag();

        if($player === null) return;

        $extraData = $player->getPlayerInfo()->getExtraData();

        switch($tag->getName()) {
            case "playerstatus.device":
                $deviceId = $extraData["DeviceOS"] ?? 0;
                $tag->setValue(self::DEVICE_OS[$deviceId] ?? "Unknown");
                break;

            case "playerstatus.control":
                $controlId = $extraData["CurrentInputMode"] ?? 0;
                $tag->setValue(self::CONTROLS[$controlId] ?? "Unknown");
                break;

            case "playerstatus.devicemodel":
                $tag->setValue($extraData["DeviceModel"] ?? "Unknown");
                break;
        }
    }
}