<?php

declare(strict_types=1);

namespace RohaM\Binder\manager;

use RohaM\Binder\Loader;

class DataManager{

    private Loader $plugin;
    private string $dataPath;

    /** @var array<string, string[]> playerName(lowercase) => texts[] */
    private array $cache = [];

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
        $this->dataPath = $plugin->getDataFolder() . "players/";

        if(!is_dir($this->dataPath)){
            @mkdir($this->dataPath, 0777, true);
        }
    }

    /**
     * @return string[]
     */
    public function getEntries(string $playerName) : array{
        $key = strtolower($playerName);

        if(isset($this->cache[$key])){
            return $this->cache[$key];
        }

        $file = $this->getFilePath($playerName);

        if(!is_file($file)){
            return $this->cache[$key] = [];
        }

        $decoded = json_decode((string) file_get_contents($file), true);

        if(!is_array($decoded)){
            $decoded = [];
        }

        return $this->cache[$key] = array_values($decoded);
    }

    public function addEntry(string $playerName, string $text) : bool{
        $entries = $this->getEntries($playerName);
        $max = (int) $this->plugin->getConfig()->get("max-entries", 20);

        if($max > 0 && count($entries) >= $max){
            return false;
        }

        $entries[] = $text;
        $this->saveEntries($playerName, $entries);
        return true;
    }

    public function removeEntry(string $playerName, int $index) : bool{
        $entries = $this->getEntries($playerName);

        if(!isset($entries[$index])){
            return false;
        }

        unset($entries[$index]);
        $this->saveEntries($playerName, array_values($entries));
        return true;
    }

    public function clearEntries(string $playerName) : void{
        $this->saveEntries($playerName, []);
    }

    /**
     * @param string[] $entries
     */
    private function saveEntries(string $playerName, array $entries) : void{
        $key = strtolower($playerName);
        $this->cache[$key] = $entries;

        file_put_contents(
            $this->getFilePath($playerName),
            json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function getFilePath(string $playerName) : string{
        $safeName = strtolower((string) preg_replace("/[^A-Za-z0-9_]/", "_", $playerName));
        return $this->dataPath . $safeName . ".json";
    }
}
