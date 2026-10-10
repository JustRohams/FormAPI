<?php

declare(strict_types=1);

namespace jojoe77777\FormAPI\lib;

final class PersianTextEngine{

    private static array $glyphs = [
        "\u{0627}" => ["\u{FE8D}", "\u{FE8D}", "\u{FE8E}", "\u{FE8E}"], // ا
        "\u{0622}" => ["\u{FE81}", "\u{FE81}", "\u{FE82}", "\u{FE82}"], // آ
        "\u{0623}" => ["\u{FE83}", "\u{FE83}", "\u{FE84}", "\u{FE84}"], // أ
        "\u{0625}" => ["\u{FE87}", "\u{FE87}", "\u{FE88}", "\u{FE88}"], // إ
        "\u{0628}" => ["\u{FE8F}", "\u{FE91}", "\u{FE92}", "\u{FE90}"], // ب
        "\u{067E}" => ["\u{FB56}", "\u{FB58}", "\u{FB59}", "\u{FB57}"], // پ
        "\u{062A}" => ["\u{FE95}", "\u{FE97}", "\u{FE98}", "\u{FE96}"], // ت
        "\u{062B}" => ["\u{FE99}", "\u{FE9B}", "\u{FE9C}", "\u{FE9A}"], // ث
        "\u{062C}" => ["\u{FE9D}", "\u{FE9F}", "\u{FEA0}", "\u{FE9E}"], // ج
        "\u{0686}" => ["\u{FB7A}", "\u{FB7C}", "\u{FB7D}", "\u{FB7B}"], // چ
        "\u{062D}" => ["\u{FEA1}", "\u{FEA3}", "\u{FEA4}", "\u{FEA2}"], // ح
        "\u{062E}" => ["\u{FEA5}", "\u{FEA7}", "\u{FEA8}", "\u{FEA6}"], // خ
        "\u{062F}" => ["\u{FEA9}", "\u{FEA9}", "\u{FEAA}", "\u{FEAA}"], // د
        "\u{0630}" => ["\u{FEAB}", "\u{FEAB}", "\u{FEAC}", "\u{FEAC}"], // ذ
        "\u{0631}" => ["\u{FEAD}", "\u{FEAD}", "\u{FEAE}", "\u{FEAE}"], // ر
        "\u{0632}" => ["\u{FEAF}", "\u{FEAF}", "\u{FEB0}", "\u{FEB0}"], // ز
        "\u{0698}" => ["\u{FB8A}", "\u{FB8A}", "\u{FB8B}", "\u{FB8B}"], // ژ
        "\u{0633}" => ["\u{FEB1}", "\u{FEB3}", "\u{FEB4}", "\u{FEB2}"], // س
        "\u{0634}" => ["\u{FEB5}", "\u{FEB7}", "\u{FEB8}", "\u{FEB6}"], // ش
        "\u{0635}" => ["\u{FEB9}", "\u{FEBB}", "\u{FEBC}", "\u{FEBA}"], // ص
        "\u{0636}" => ["\u{FEBD}", "\u{FEBF}", "\u{FEC0}", "\u{FEBE}"], // ض
        "\u{0637}" => ["\u{FEC1}", "\u{FEC3}", "\u{FEC4}", "\u{FEC2}"], // ط
        "\u{0638}" => ["\u{FEC5}", "\u{FEC7}", "\u{FEC8}", "\u{FEC6}"], // ظ
        "\u{0639}" => ["\u{FEC9}", "\u{FECB}", "\u{FECC}", "\u{FECA}"], // ع
        "\u{063A}" => ["\u{FECD}", "\u{FECF}", "\u{FED0}", "\u{FECE}"], // غ
        "\u{0641}" => ["\u{FED1}", "\u{FED3}", "\u{FED4}", "\u{FED2}"], // ف
        "\u{0642}" => ["\u{FED5}", "\u{FED7}", "\u{FED8}", "\u{FED6}"], // ق
        "\u{06A9}" => ["\u{FB8E}", "\u{FB90}", "\u{FB91}", "\u{FB8F}"], // ک (Persian keheh)
        "\u{0643}" => ["\u{FED9}", "\u{FEDB}", "\u{FEDC}", "\u{FEDA}"], // ك (Arabic kaf)
        "\u{06AF}" => ["\u{FB92}", "\u{FB94}", "\u{FB95}", "\u{FB93}"], // گ
        "\u{0644}" => ["\u{FEDD}", "\u{FEDF}", "\u{FEE0}", "\u{FEDE}"], // ل
        "\u{0645}" => ["\u{FEE1}", "\u{FEE3}", "\u{FEE4}", "\u{FEE2}"], // م
        "\u{0646}" => ["\u{FEE5}", "\u{FEE7}", "\u{FEE8}", "\u{FEE6}"], // ن
        "\u{0648}" => ["\u{FEED}", "\u{FEED}", "\u{FEEE}", "\u{FEEE}"], // و
        "\u{0647}" => ["\u{FEE9}", "\u{FEEB}", "\u{FEEC}", "\u{FEEA}"], // ه
        "\u{06CC}" => ["\u{FBFC}", "\u{FBFE}", "\u{FBFF}", "\u{FBFD}"], // ی (Persian yeh)
        "\u{064A}" => ["\u{FEF1}", "\u{FEF3}", "\u{FEF4}", "\u{FEF2}"], // ي (Arabic yeh)
        "\u{0629}" => ["\u{FE93}", "\u{FE93}", "\u{FE94}", "\u{FE94}"], // ة
        "\u{0624}" => ["\u{FE85}", "\u{FE85}", "\u{FE86}", "\u{FE86}"], // ؤ
        "\u{0626}" => ["\u{FE89}", "\u{FE8B}", "\u{FE8C}", "\u{FE8A}"], // ئ
        "\u{0621}" => ["\u{FE80}", "\u{FE80}", "\u{FE80}", "\u{FE80}"], // ء
    ];

    private static array $nonConnectors = [
        "\u{0627}" => 1, // ا
        "\u{0622}" => 1, // آ
        "\u{0623}" => 1, // أ
        "\u{0625}" => 1, // إ
        "\u{062F}" => 1, // د
        "\u{0630}" => 1, // ذ
        "\u{0631}" => 1, // ر
        "\u{0632}" => 1, // ز
        "\u{0698}" => 1, // ژ
        "\u{0648}" => 1, // و
        "\u{0629}" => 1, // ة
        "\u{0624}" => 1, // ؤ
    ];

    private static array $nonJoining = [
        "\u{0621}" => 1, // ء
    ];

    private static array $lamAlef = [
        "\u{0627}" => ["\u{FEFB}", "\u{FEFC}"], // لا
        "\u{0622}" => ["\u{FEF5}", "\u{FEF6}"], // لآ
        "\u{0623}" => ["\u{FEF7}", "\u{FEF8}"], // لأ
        "\u{0625}" => ["\u{FEF9}", "\u{FEFA}"], // لإ
    ];

    public static function process(string $text) : string{
        return self::reversePersianText(self::correctPersianText($text));
    }

    public static function reversePersianText(string $text) : string{
        static $hasArabicRegex = null;
        static $numberRegex = null;
        static $symbolRegex = null;

        if($hasArabicRegex === null){
            $hasArabicRegex = "/\p{Arabic}/u";
            $numberRegex = "/^\p{N}+$/u";
            $symbolRegex = "/^[><\[\]]+$/";
        }

        $leadingColor = "";
        if(preg_match("/^(?:\u{00A7}.)+/u", $text, $lm) === 1){
            $leadingColor = $lm[0];
            $text = mb_substr($text, mb_strlen($leadingColor, "UTF-8"), null, "UTF-8");
        }

        $leadingSymbol = "";
        if(preg_match("/^([><\[\]]+)(\s+)/u", $text, $sm) === 1){
            $leadingSymbol = $sm[1] . ($sm[2] ?? "");
            $text = mb_substr($text, mb_strlen($leadingSymbol, "UTF-8"), null, "UTF-8");
        }

        if(preg_match($hasArabicRegex, $text) !== 1){
            return $leadingColor . $leadingSymbol . $text;
        }

        preg_match_all(
            "/(?:\u{00A7}.)*(?:\([^)]*\)|\[[^]]*]|\{[^}]*}|<[^>]*>|\s+|[\(\)\[\]{}<>]|[^\s(){}\[\]<>]+)/u",
            $text,
            $m
        );
        $tokens = $m[0];

        $splitPrefix = static function(string $tok) : array{
            if(preg_match("/^(?:\u{00A7}.)+/u", $tok, $pm) === 1){
                $pref = $pm[0];
                $core = mb_substr($tok, mb_strlen($pref, "UTF-8"), null, "UTF-8");
                return [$pref, $core];
            }
            return ["", $tok];
        };

        $merged = [];
        $n = count($tokens);
        $i = 0;
        while($i < $n){
            $t = $tokens[$i];

            if(preg_match("/^\s+$/u", $t) === 1){
                $merged[] = $t;
                $i++;
                continue;
            }

            if(preg_match("/^[(\[{<].*[)\]}>]$/us", $t) === 1){
                $merged[] = $t;
                $i++;
                continue;
            }

            $isLatinRun = static fn(string $c) : bool =>
                preg_match("/[A-Za-z0-9]/u", self::stripColors($c)) === 1
                && preg_match($hasArabicRegex, $c) !== 1
                && preg_match("/^[(\[{<]/u", $c) !== 1;

            [, $core] = $splitPrefix($t);
            if($isLatinRun($core)){
                $buf = $t;
                $j = $i + 1;
                while($j + 1 < $n){
                    if(preg_match("/^\s+$/u", $tokens[$j]) !== 1){
                        break;
                    }
                    [, $nextCore] = $splitPrefix($tokens[$j + 1]);
                    if(!$isLatinRun($nextCore)){
                        break;
                    }
                    $buf .= $tokens[$j] . $tokens[$j + 1];
                    $j += 2;
                }
                $merged[] = $buf;
                $i = $j;
                continue;
            }

            $merged[] = $t;
            $i++;
        }

        $lastPrefix = $leadingColor;
        $tokenObjs = [];

        foreach($merged as $t){
            if(preg_match("/^\s+$/u", $t) === 1){
                $tokenObjs[] = ["raw" => $t, "prefix" => "", "core" => $t, "applied" => ""];
                continue;
            }

            [$pref, $core] = $splitPrefix($t);

            if($pref !== ""){
                $lastPrefix = $pref;
                $applied = $pref;
            } else {
                $applied = $lastPrefix;
            }

            $tokenObjs[] = ["raw" => $t, "prefix" => $pref, "core" => $core, "applied" => $applied];
        }

        $tokenObjs = array_reverse($tokenObjs);

        $outParts = [];
        foreach($tokenObjs as $tokObj){
            $raw = $tokObj["raw"];
            $core = $tokObj["core"];
            $colorForToken = $tokObj["applied"];

            if(preg_match("/^\s+$/u", $raw) === 1){
                $outParts[] = $raw;
                continue;
            }

            if(preg_match("/^(?:\u{00A7}.)*([(\[{<])/u", $raw) === 1){
                if(preg_match("/^\((.*)\)$/us", $core, $im) === 1){
                    $inner = self::reversePersianText($im[1]);
                    $outParts[] = $colorForToken . "(" . $inner . ")";
                    continue;
                }
                if(preg_match("/^\[(.*)]$/us", $core, $im) === 1){
                    $inner = self::reversePersianText($im[1]);
                    $outParts[] = $colorForToken . "[" . $inner . "]";
                    continue;
                }
                if(preg_match("/^\{(.*)}$/us", $core, $im) === 1){
                    $inner = self::reversePersianText($im[1]);
                    $outParts[] = $colorForToken . "{" . $inner . "}";
                    continue;
                }
                if(preg_match("/^<(.*)>$/us", $core, $im) === 1){
                    $inner = self::reversePersianText($im[1]);
                    $outParts[] = $colorForToken . "<" . $inner . ">";
                    continue;
                }
            }

            if(preg_match($symbolRegex, $core) === 1){
                $outParts[] = $colorForToken . $core;
                continue;
            }

            if(preg_match($hasArabicRegex, $core) !== 1
                && preg_match("/[A-Za-z0-9]/u", self::stripColors($core)) === 1){
                if(preg_match("/^(.*?)([\p{P}\p{S}]+)$/u", $core, $pm) === 1){
                    [, $word, $pun] = $pm;
                    $core = $pun . $word;
                }
                $outParts[] = $colorForToken . $core;
                continue;
            }

            if(preg_match($hasArabicRegex, $core) === 1 && preg_match($numberRegex, $core) !== 1){
                $units = array_reverse(self::splitUnits($core));
                $outParts[] = $colorForToken . implode("", $units);
                continue;
            }

            $outParts[] = $colorForToken . $core;
        }

        return $leadingSymbol . implode("", $outParts);
    }

    private static function stripColors(string $text) : string{
        return (string) preg_replace("/\u{00A7}./u", "", $text);
    }

    public static function correctPersianText(string $text) : string{
        $glyphs = self::$glyphs;
        $nonConnectors = self::$nonConnectors;
        $nonJoining = self::$nonJoining;

        $chars = self::splitUnits(self::removeDiacritics($text));
        $count = count($chars);
        $result = [];

        $skipNext = false;
        foreach($chars as $i => $curr){
            if($skipNext){
                $skipNext = false;
                continue;
            }

            $prev = $i > 0 ? $chars[$i - 1] : null;
            $next = $i < $count - 1 ? $chars[$i + 1] : null;

            if($curr === "\u{0644}" && $next !== null && isset(self::$lamAlef[$next])){
                $hasPrev = $prev !== null;
                $prevGlyph = $hasPrev && isset($glyphs[$prev]);
                $connectsBefore = $prevGlyph
                    && !isset($nonConnectors[$prev])
                    && !isset($nonJoining[$prev]);

                $result[] = self::$lamAlef[$next][$connectsBefore ? 1 : 0];
                $skipNext = true;
                continue;
            }

            if(!isset($glyphs[$curr])){
                $result[] = $curr;
                continue;
            }

            $hasPrev = $prev !== null;
            $hasNext = $next !== null;
            $prevGlyph = $hasPrev && isset($glyphs[$prev]);
            $nextGlyph = $hasNext && isset($glyphs[$next]);

            $connectsBefore = $prevGlyph
                && !isset($nonConnectors[$prev])
                && !isset($nonJoining[$prev])
                && !isset($nonJoining[$curr]);

            $connectsAfter = !isset($nonConnectors[$curr])
                && !isset($nonJoining[$curr])
                && $nextGlyph
                && !isset($nonJoining[$next]);

            if($connectsBefore){
                $form = $connectsAfter ? 2 : 3;
            } elseif($connectsAfter){
                $form = 1;
            } else {
                $form = 0;
            }

            $result[] = $glyphs[$curr][$form];
        }

        return implode("", $result);
    }

    private static function removeDiacritics(string $text) : string{
        $text = str_replace(
            ["\u{0647}\u{0654}", "\u{06CC}\u{0654}", "\u{064A}\u{0654}"],
            ["\u{06C0}", "\u{0626}", "\u{0626}"],
            $text
        );
        return (string) preg_replace("/[\x{064B}-\x{065F}\x{0670}]/u", "", $text);
    }

    private static function splitUnits(string $text) : array{
        $units = [];
        $length = mb_strlen($text, "UTF-8");
        for($i = 0; $i < $length; $i++){
            $char = mb_substr($text, $i, 1, "UTF-8");
            if($char === "\u{00A7}" && $i + 1 < $length){
                $units[] = $char . mb_substr($text, $i + 1, 1, "UTF-8");
                $i++;
            } else {
                $units[] = $char;
            }
        }
        return $units;
    }
}