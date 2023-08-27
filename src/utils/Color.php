<?php

namespace deveworld\parkour\utils;

class Color
{
    public const COLOR_CODE = "\xc2\xa7";

    protected string $code;
    public static string $prefix, $explain, $button, $warning, $error;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function init(): void
    {
        self::$prefix = Text::getTranslatedString("prefix");
        self::$explain = Text::getTranslatedString("explain");
        self::$button = Text::getTranslatedString("button");
        self::$warning = Text::getTranslatedString("warning");
        self::$error = Text::getTranslatedString("error");
    }

    public function __toString(): string
    {
        return self::COLOR_CODE . $this->code;
    }
}