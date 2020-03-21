<?php

namespace Tests;

use Motif\Formatter;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    /** @var array */
    private $formatData = [
        [ 
            'Hello world',
            ['bold', 'black', 'green', true],
            "\e[1;30;42m\n\nHello world\n\e[0m\n"
        ],
        [
            "Some random text with a\ttab",
            [['underline', 'italic'], 'yellow', 'black'],
            "\e[4;3;33;40mSome random text with a\ttab\e[0m\n"
        ],
        [ 
            'stop',
            [[], '', 'magenta', true],
            "\e[45m\n\nstop\n\e[0m\n"
        ],
        [
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr',
            [['bold', 'underline', 'strike', 'italic', 'blink']],
            "\e[1;4;9;3;5mLorem ipsum dolor sit amet, consetetur sadipscing elitr\e[0m\n"
        ],
    ];

    /** @var array */
    private $colorizeData = [
        [ 'It failed.', 'error', "\e[37;41mIt failed.\e[0m\n" ],
        [ 'Viruses downloaded successfully!', 'success', "\e[30;42mViruses downloaded successfully!\e[0m\n" ],
        [ 'This is your third warning.', 'warning', "\e[30;43mThis is your third warning.\e[0m\n" ],
        [ 'Random info 4 u', 'info', "\e[37;44mRandom info 4 u\e[0m\n" ],
    ];

    public function getFormatData(): array
    {
        return $this->formatData;
    }

    public function getColorizeData(): array
    {
        return $this->colorizeData;
    }

    /**
     * @dataProvider getFormatData
     * @param string $text
     * @param array $opts
     * @param string $expected
     */
    public function testReturnsCorrectlyFormattedText(string $text, array $opts, string $result)
    {
        $str = Formatter::format($text, $opts[0], $opts[1] ?? '', $opts[2] ?? '', $opts[3] ?? false);
        $this->assertSame($result, $str);
    }

    /**
     * @dataProvider getColorizeData
     * @param string $text
     * @param string $mode
     * @param string $result
     */
    public function testReturnsCorrectlyColorizedText(string $text, string $mode, string $result)
    {
        $str = Formatter::colorize($text, $mode);
        $this->assertSame($result, $str);
    }
}
