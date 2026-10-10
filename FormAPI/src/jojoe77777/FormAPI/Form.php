<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use InvalidArgumentException;
use jojoe77777\FormAPI\lib\Persian;
use pocketmine\form\Form as IForm;
use pocketmine\player\Player;

abstract class Form implements IForm{

    /** @var array */
    protected array $data = [];
    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @throws InvalidArgumentException
     * @deprecated
     * @see Player::sendForm()
     */
    public function sendToPlayer(Player $player) : void {
        $player->sendForm($this);
    }

    public function getCallable() : ?callable {
        return $this->callable;
    }

    public function setCallable(?callable $callable) {
        $this->callable = $callable;
    }

    public function handleResponse(Player $player, $data) : void {
        $this->processData($data);
        $callable = $this->getCallable();
        if($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(&$data) : void {
    }

    public function jsonSerialize() : array {
        return $this->data;
    }

    protected static function fixText(?string $text) : ?string {
        if($text === null || $text === ""){
            return $text;
        }
        return Persian::fix($text);
    }
}