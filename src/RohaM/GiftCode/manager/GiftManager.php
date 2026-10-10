<?php

declare(strict_types=1);

namespace RohaM\GiftCode\manager;

use pocketmine\utils\Config;
use RohaM\GiftCode\Loader;

class GiftManager {

    private Loader $plugin;
    private Config $gifts;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        $this->gifts = new Config($plugin->getDataFolder() . "gifts.yml", Config::YAML, []);
    }

    public function createGift(string $code, int $amount): bool {
        $code = strtoupper($code);
        if ($this->gifts->exists($code)) {
            return false;
        }
        $this->gifts->set($code, $amount);
        $this->gifts->save();
        return true;
    }

    public function getGift(string $code): ?int {
        $code = strtoupper($code);
        if (!$this->gifts->exists($code)) {
            return null;
        }
        return (int)$this->gifts->get($code);
    }

    public function removeGift(string $code): void {
        $this->gifts->remove(strtoupper($code));
        $this->gifts->save();
    }

    public function save(): void {
        $this->gifts->save();
    }
}