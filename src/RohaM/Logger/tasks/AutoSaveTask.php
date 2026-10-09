<?php

declare(strict_types=1);

namespace RohaM\Logger\tasks;

use pocketmine\scheduler\Task;
use RohaM\Logger\managers\LoggerManager;

class AutoSaveTask extends Task {

    private LoggerManager $manager;

    public function __construct(LoggerManager $manager) {
        $this->manager = $manager;
    }

    public function onRun(): void {
        $this->manager->saveAll();
    }
}