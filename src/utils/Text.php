<?php

namespace deveworld\parkour\utils;

use deveworld\parkour\Parkour;

class Text
{
    public const TRANSLATE_PREFIX = "parkour.";

    public const NONE = 0;
    public const BUTTON = 1;
    public const EXPLAIN = 2;

    protected string $text_str, $code, $prefix, $midText;

    /**
     * @param string $text_str text
     * @param string $code color code
     * @param int    $prefix prefix (NONE, BUTTON, EXPLAIN)
     * @param string $midText middle text
     * @param string $search search
     * @param string $change search and to change
     */
    public function __construct(string $text_str, string $code = "", int $prefix = self::NONE, string $midText = "", array|string $search = "", array|string $change = "", bool $translate = true)
    {
        if ($text_str != "") {
            if ($translate) {
                $this->text_str = $this->getTranslatedString($text_str);
            } else {
                $this->text_str = $text_str;
            }
        } else {
            $this->text_str = $text_str;
        }
        if ((($search != "") && ($change != "")) || (($search != []) && ($change != []))) {
            $this->text_str = str_replace($search, $change, $this->text_str);
        }
        $this->code = $code;
        $this->prefix = $prefix;
        $this->midText = $midText;
    }

    /**
     * @param string $text var name in ini file
     *
     * @return string translated content
     */
    public static function getTranslatedString(string $text): string
    {
        return Parkour::getInstance()->getLang()->translateString(self::TRANSLATE_PREFIX . $text);
    }

    /**
     * @return string get text
     */
    public function __toString(): string
    {
        if ($this->code != "") {
            $text = new Color($this->code) . $this->text_str . $this->midText; // get color with text
        } else {
            $text = $this->text_str . $this->midText; // get only text
        }
        $prefix = new Color($this->getTranslatedString("prefix")); // get "prefix color code"
        if ($this->prefix != self::NONE) {
            $text = $prefix . "[ " . $text . " " . $prefix . "]";
        }
        if ($this->prefix == self::EXPLAIN) {
            $name = new Text("name", "b"); // get name with color code "b"
            $text = $prefix . "[ " . $name . " " . $prefix . "] " . $text;
        }
        return $text;
    }

    /**
     * @param string $text text to change
     */
    public function setText(string $text): void
    {
        $this->text_str = $text;
    }

    /**
     * @return int get prefix type
     */
    public function getPrefix(): int
    {
        return $this->prefix;
    }

    /**
     * @param int $type prefix type to change
     */
    public function setPrefix(int $type): void
    {
        $this->prefix = $type;
    }

    /**
     * @return string get color code
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code color code to change
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string get midText
     */
    public function getMidText(): string
    {
        return $this->midText;
    }

    /**
     * @param string $midText midText to change
     */
    public function setMidText(string $midText): void
    {
        $this->midText = $midText;
    }
}