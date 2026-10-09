<?php

declare(strict_types=1);

namespace RohaM\Logger\utils;

final class FileUtils {

    private function __construct() {
    }

    public static function atomicWrite(string $path, string $data): bool {
        $dir = dirname($path);
        if (!is_dir($dir) && !@mkdir($dir, 0777, true) && !is_dir($dir)) {
            return false;
        }

        $tmp = $path . ".tmp." . bin2hex(random_bytes(4));
        if (@file_put_contents($tmp, $data, LOCK_EX) === false) {
            return false;
        }

        if (!@rename($tmp, $path)) {
            @unlink($tmp);
            return false;
        }

        return true;
    }
}