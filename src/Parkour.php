<?php
declare(strict_types=1);

namespace deveworld\parkour;

use JsonException;
use deveworld\parkour\command\ParkourCommand;
use deveworld\parkour\event\EventListener;
use deveworld\parkour\task\ParkourTask;
use deveworld\parkour\form\UIPage;
use deveworld\parkour\utils\Color;
use deveworld\parkour\utils\Text;
use pocketmine\event\Listener;
use pocketmine\lang\Language;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\Position;

class Parkour extends PluginBase implements Listener
{
    private Language $baseLang;

    private static $instance = null;

    public Config $config;
    public static bool $checkPos;
    /**
     * @var array will save with Data.yml.
     */
    public static array $db;
    /**
     * @var array not be saved. removed at disabled
     */
    public static array $data = [];
    /**
     * @var array now playing players name
     */
    public static array $plays = [];

    protected function onLoad(): void
    {
        if (self::$instance == null) {
            self::$instance = $this;
        }
    }

    public static function getInstance(): Parkour
    {
        return static::$instance;
    }

    /**
     * Get Player Data
     *
     * @brief get player data from self::$data by player name.
     *
     * @param Player $player
     *
     * @return array|null
     */
    public static function getData(Player $player): ?array
    {
        return self::$data[strtolower($player->getName())] ?? null;
    }

    /**
     * Set Player Data
     *
     * @brief set player data into param $data.
     *
     * @param Player $player
     * @param array  $data
     */
    public static function setData(Player $player, array $data)
    {
        self::$data[strtolower($player->getName())] = $data;
    }

    /**
     * delete player data
     *
     * @brief delete player data. if param $arrayName is blank, delete All.
     *
     * @param Player $player
     * @param string $arrayName
     * @param string $arrayName2
     *
     * @return bool
     */
    public static function delData(Player $player, string $arrayName = "", string $arrayName2 = ""): bool
    {
        $name = strtolower($player->getName());
        if ($arrayName == "") {
            unset(self::$data[$name]);
            return !isset(self::$data[$name]);
        } else {
            if ($arrayName2 != "") {
                unset(self::$data[$name][$arrayName][$arrayName2]);
                return !isset(self::$data[$name][$arrayName][$arrayName2]);
            } else {
                unset(self::$data[$name][$arrayName]);
                return !isset(self::$data[$name][$arrayName]);
            }
        }
    }

    public static function addPlay(Player $player): bool
    {
        self::$plays[strtolower($player->getName())] = 0;
        return isset(self::$plays[strtolower($player->getName())]);
    }

    public static function delPlay(Player $player): bool
    {
        if (isset(self::$plays[strtolower($player->getName())])) {
            unset(self::$plays[strtolower($player->getName())]);
            return !isset(self::$plays[strtolower($player->getName())]);
        } else {
            return false;
        }
    }

    /**
     * @return array parkour list
     */
    public static function getParkour(): array
    {
        return self::$db["data"];
    }

    /**
     * Add Parkour to file from player data.
     *
     * @param Player $player
     *
     * @return bool
     */
    public static function addParkour(Player $player): bool
    {
        $playerData = self::getData($player);
        if (isset($playerData["addParkour"])) {
            unset($playerData["addParkour"]["now"]);
            unset($playerData["addParkour"]["select"]);
            unset($playerData["addParkour"]["checkPoint"]["select"]);
            self::$db["data"][] = $parkour = $playerData["addParkour"];
            self::sendFloatingText([$parkour]); // idk why but this isn't working
            $parkourName = $playerData["addParkour"]["name"];
            self::delData($player, "addParkour");
            $player->sendMessage((string) new Text("addParkour.done", Color::$explain, Text::EXPLAIN, "", "{name}", $parkourName));
            return true;
        } else {
            $player->sendMessage((string) new Text("unknownError", Color::$error, Text::EXPLAIN));
            return false;
        }
    }

    public static function delParkour(int $parkour): bool
    {
        $orig = self::$db["data"][$parkour]["name"];
        array_splice(self::$db["data"], $parkour, 1);
        if (isset(self::$db["data"][$parkour])) {
            return (self::$db["data"][$parkour]["name"] != $orig);
        } else {
            return true;
        }
    }

    public static function sendFloatingText(array $parkours = [], array $players = []): void
    {
        if($parkours !== []) {
            foreach($parkours as $parkour) {
                $world = self::getInstance()->getServer()->getWorldManager()->getWorldByName($parkour["world"]);
                $checkPoint = $parkour["checkPoint"];
                $start = $parkour["start"];
                $end = $parkour["end"];
                $world->addParticle(new Position($start["x"] + 0.5, $start["y"] + 2, $start["z"] + 0.5, $world), new FloatingTextParticle("Start point", "Parkour: " . $parkour["name"]), $players);
                $world->addParticle(new Position($end["x"] + 0.5, $end["y"] + 2, $end["z"] + 0.5, $world), new FloatingTextParticle("End point", "Parkour: " . $parkour["name"]), $players);
                foreach($checkPoint as $num => $pos) {
                    $world->addParticle(new Position($pos["x"] + 0.5, $pos["y"] + 2, $pos["z"] + 0.5, $world), new FloatingTextParticle("Checkpoint #" . $num + 1, "Parkour: " . $parkour["name"]), $players);
                }
            }
        } else {
            foreach(self::getParkour() as $parkour) {
                $world = self::getInstance()->getServer()->getWorldManager()->getWorldByName($parkour["world"]);
                $checkPoint = $parkour["checkPoint"];
                $start = $parkour["start"];
                $end = $parkour["end"];
                $world->addParticle(new Position($start["x"] + 0.5, $start["y"] + 2, $start["z"] + 0.5, $world), new FloatingTextParticle("Start point", "Parkour: " . $parkour["name"]), $players);
                $world->addParticle(new Position($end["x"] + 0.5, $end["y"] + 2, $end["z"] + 0.5, $world), new FloatingTextParticle("End point", "Parkour: " . $parkour["name"]), $players);
                foreach($checkPoint as $num => $pos) {
                    $world->addParticle(new Position($pos["x"] + 0.5, $pos["y"] + 2, $pos["z"] + 0.5, $world), new FloatingTextParticle("Checkpoint #" . $num + 1, "Parkour: " . $parkour["name"]), $players);
                }
            }
        }
    }

    public function onEnable(): void
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        // data is Parkour data, save is player data
        $this->config = new Config($this->getDataFolder() . "data.yml", Config::YAML, ["data" => [], "save" => []]);
        self::$db = $this->config->getAll();
        // data is self::$db["data"], save is self::$db["save"]

        date_default_timezone_set($this->getConfig()->get("timezone", "Asia/Ho_Chi_Minh"));
        $lang = $this->getConfig()->get("language", "eng");
        $this->baseLang = new Language((string) $lang, $this->getFile() . "resources" . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR);

        if ($this->getConfig()->get("checkpos", "false") == "false") {
            self::$checkPos = false;
        } else {
            self::$checkPos = true;
        }

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getCommand("parkour")->setExecutor(new ParkourCommand($this));
        Color::init();
        UIPage::init();
        $task = new ParkourTask($this);
        $this->getScheduler()->scheduleRepeatingTask($task, 1);
    }

    /**
     * save ALL data to File at plugin disabled.
     *
     * @throws JsonException
     */
    protected function onDisable(): void
    {
        $this->config->setAll(self::$db);
        $this->config->save();
    }

    /**
     * get BaseLang
     *
     * @brief get translated ini file object.
     *
     * @return Language
     */
    public function getLang(): Language
    {
        return $this->baseLang;
    }
}