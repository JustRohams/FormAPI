<?php

declare(strict_types=1);

namespace RohaM\Logger\loggers;

use RohaM\Logger\Loader;
use RohaM\Logger\utils\FileUtils;

class PlayerLogger {

    private Loader $plugin;
    private string $filePath;

    /** @var array<int, array<string, mixed>> */
    private array $entries = [];

    private bool $dirty = false;

    public function __construct(Loader $plugin, string $filePath) {
        $this->plugin = $plugin;
        $this->filePath = $filePath;
        $this->load();
    }

    private function load(): void {
        if (!is_file($this->filePath)) {
            return;
        }

        $content = @file_get_contents($this->filePath);
        if ($content === false || $content === "") {
            return;
        }

        $data = json_decode($content, true);
        if (is_array($data)) {
            $this->entries = $data;
        }
    }

    public function addEntry(string $type, string $content): void {
        $config = $this->plugin->getPluginConfig();

        $this->entries[] = [
            "type"      => $type,
            "content"   => $content,
            "time"      => date((string) $config->get("date-format", "Y-m-d H:i:s")),
            "timestamp" => time()
        ];

        $max = (int) $config->get("max-entries", 0);
        if ($max > 0 && count($this->entries) > $max) {
            $this->entries = array_slice($this->entries, -$max);
        }

        $this->dirty = true;
    }

    public function save(): void {
        if (!$this->dirty) {
            return;
        }

        $json = json_encode(
            $this->entries,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            return;
        }

        if (FileUtils::atomicWrite($this->filePath, $json)) {
            $this->dirty = false;
        }
    }

    /** @return array<int, array<string, mixed>> */
    public function getEntries(): array {
        return $this->entries;
    }

    public function getCount(): int {
        return count($this->entries);
    }

    public function getFilePath(): string {
        return $this->filePath;
    }

    public function isDirty(): bool {
        return $this->dirty;
    }
}