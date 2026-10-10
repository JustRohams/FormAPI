<?php

declare(strict_types=1);

namespace jojoe77777\FormAPI\lib;

final class Persian{

    private const CACHE_LIMIT = 2048;

    private static bool $enabled = true;

    /** @var array<string, string> */
    private static array $cache = [];

    public static function setEnabled(bool $enabled) : void{
        self::$enabled = $enabled;
        self::$cache = [];
    }

    public static function isEnabled() : bool{
        return self::$enabled;
    }

    public static function fix(string $text, int $maxLineLength = 0) : string{
        if(!self::$enabled || $text === ""){
            return $text;
        }

        if(preg_match('/\p{Arabic}/u', $text) !== 1){
            return $text;
        }

        $cacheKey = $maxLineLength . "\0" . $text;
        if(isset(self::$cache[$cacheKey])){
            return self::$cache[$cacheKey];
        }

        if($maxLineLength > 0){
            $text = self::wrap($text, $maxLineLength);
        }

        $lines = explode("\n", self::normalize($text));
        foreach($lines as $i => $line){
            $lines[$i] = str_replace("\u{200C}", "", PersianTextEngine::process($line));
        }
        $result = implode("\n", $lines);

        if(count(self::$cache) >= self::CACHE_LIMIT){
            self::$cache = [];
        }
        return self::$cache[$cacheKey] = $result;
    }

    public static function fixAll(array $lines, int $maxLineLength = 0) : array{
        return array_map(static fn(string $line) => self::fix($line, $maxLineLength), $lines);
    }

    public static function wrap(string $text, int $maxLength) : string{
        if($maxLength <= 0) return $text;

        $out = [];
        foreach(explode("\n", $text) as $line){
            if(self::visibleLength($line) <= $maxLength){
                $out[] = $line;
                continue;
            }

            $current = "";
            $color = "";
            foreach(self::unbreakableWords($line) as $word){
                $candidate = $current === "" ? $word : "{$current} {$word}";
                if($current !== "" && self::visibleLength($candidate) > $maxLength){
                    $out[] = $current;
                    $candidate = $color . $word;
                }
                $current = $candidate;
                if(preg_match_all('/\u{00A7}[0-9a-z]/u', $word, $m) > 0){
                    $color = end($m[0]);
                }
            }
            if($current !== ""){
                $out[] = $current;
            }
        }
        return implode("\n", $out);
    }

    private static function normalize(string $text) : string{
        $text = (string) preg_replace("/((?:\u{00A7}.)+)([ \t]+)/u", "$2$1", $text);
        $text = (string) preg_replace("/\[[ \t]+/u", "[", $text);
        return (string) preg_replace("/[ \t]+\]/u", "]", $text);
    }

    private static function unbreakableWords(string $line) : array{
        $words = [];
        $buffer = "";
        foreach(explode(" ", $line) as $word){
            $buffer = $buffer === "" ? $word : "{$buffer} {$word}";
            $open = preg_match_all('/[(\[{]/u', $buffer);
            $close = preg_match_all('/[)\]}]/u', $buffer);
            if($open <= $close){
                $last = count($words) - 1;
                if($last >= 0 && !self::hasPersian($words[$last]) && !self::hasPersian($buffer)){
                    $words[$last] .= " {$buffer}";
                }else{
                    $words[] = $buffer;
                }
                $buffer = "";
            }
        }
        if($buffer !== ""){
            $words[] = $buffer;
        }
        return $words;
    }

    private static function hasPersian(string $text) : bool{
        return preg_match('/\p{Arabic}/u', $text) === 1;
    }

    private static function visibleLength(string $text) : int{
        return mb_strlen((string) preg_replace('/\u{00A7}./u', "", $text));
    }
}