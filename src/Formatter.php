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
     * Formats a text according to user-inputted options
     *
     * @param string $text Text
     * @param string|array $styles Text styles
     * @param string $fore Text color
     * @param string $back Background color
     * @param bool $wrap Specify if text is to be wrapped in line breaks (experimental!)
     * @return string
     */
    public static function format(string $text = '', $styles = [], $fore = '', $back = '', bool $wrap = false): string
    {
        $styles = is_array($styles) ? $styles : [$styles];
        $format = self::translate($styles, $fore, $back);
        $text = $wrap ? "\n\n$text\n" : $text;

        return "\e[{$format}m{$text}\e[0m\n";
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
    public static function colorize(string $text = '', string $mode = 'info'): string
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
                break;
        }

        return self::format($text, [], $fore, $back);
    }

    /**
     * Assembles the format hints from the passed params
     *
     * @param array $styles
     * @param string $fore
     * @param string $back
     * @return string
     */
    private static function translate(array $styles, string $fore, string $back): string
    {
        $format = [];
        foreach ($styles as $style) {
            $format[] = self::$styleMap[$style] ?? null;
        }
        $format[] = self::$foregroundMap[$fore] ?? null;
        $format[] = self::$backgroundMap[$back] ?? null;

        return implode(';', array_filter($format));
    }
}
