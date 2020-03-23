<?php

namespace Motif;

class Formatter
{
    private static $styleMap = [
        'bold'      => 1, 'dark'        => 2,
        'italic'    => 3, 'underline'   => 4,
        'blink'     => 5, 'invert'      => 7,
        'hidden'    => 8, 'strike'      => 9,
    ];

    private static $foregroundMap = [
        'black'     => 30, 'red'        => 31,
        'green'     => 32, 'yellow'     => 33,
        'blue'      => 34, 'magenta'    => 35,
        'cyan'      => 36, 'white'      => 37,
    ];

    private static $backgroundMap = [
        'black'     => 40, 'red'        => 41,
        'green'     => 42, 'yellow'     => 43,
        'blue'      => 44, 'magenta'    => 45,
        'cyan'      => 46, 'white'      => 47,
    ];

    /**
     * Formats a string according to user-inputted options
     *
     * @param string $text Text
     * @param string $styles Text styles
     */
    public static function format(string $text, string $styles = ''): string
    {
        $format = self::parse($styles);
        return "\e[{$format}m{$text}\e[0m";
    }

    /**
     * Expands on `self::format()`; appends a line break to string
     *
     * @param string $text Text
     * @param string $styles Text styles
     */
    public static function formatLine(string $text, string $styles = ''): string
    {
        return self::format($text, $styles) . "\n";
    }

    /**
     * Formats a text in one of four modes
     *  - `success`: black text, green bg
     *  - `warning`: black text, yellow bg
     *  - `error`: white text, red bg
     *  - `info|default`: white text, blue bg
     *
     * @param string $text
     * @param string $mode
     * @return string
     */
    public static function display(string $text, string $mode = 'info'): string
    {
        $fore = 'white';
        switch (strtolower($mode)) {
            case 'success':
                $back = 'green';
                $fore = 'black';
                break;
            case 'warning':
                $back = 'yellow';
                $fore = 'black';
                break;
            case 'error':
                $back = 'red';
                break;
            default:
                $back = 'blue';
                $fore = 'black';
                break;
        }

        return self::formatLine($text, "color:$fore|bg:$back");
    }

    /**
     * Interprets a user-given string
     *
     * @param string $options An options string
     * @return string
     */
    private static function parse(string $options): string
    {
        $styles = [];

        foreach (explode('|', $options) as $option) {
            $option = trim($option);

            if (strpos($option, 'bg:') !== false) {
                $bg = str_replace('bg:', '', $option);
                $num = self::$backgroundMap[trim($bg)] ?? null;
            } else if (strpos($option, 'color:') !== false) {
                $color = str_replace('color:', '', $option);
                $num = self::$foregroundMap[trim($color)] ?? null;
            } else {
                $misc = self::mapFormats($option);
                $num = strlen($misc) ? $misc : null;
            }

            if (!is_null($num)) {
                $styles[] = $num;
            }
        }

        return implode(';', $styles);
    }

    private static function mapFormats(string $formats): string
    {
        $styles = [];

        foreach (explode(',', $formats) as $part) {
            $part = trim($part);
            if (key_exists($part, self::$styleMap)) {
                $styles[] = self::$styleMap[$part];
            }
        }

        return implode(';', $styles);
    }
}
