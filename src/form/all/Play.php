<?php
namespace deveworld\parkour\form\all;

use deveworld\parkour\Parkour;
use deveworld\parkour\utils\Color;
use deveworld\parkour\utils\Text;
use pocketmine\entity\Location;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use deveworld\parkour\form\UIPage;

class Play extends UIPage
{
    public const FORM_ID = 18321003;

    public function handle(Player $player, $response, $id): void
    {
        $parkour = Parkour::getParkour();
        if (!empty($parkour) && isset($parkour[$response])) {
            $time = false;
            if (isset(Parkour::$db["save"][strtolower($player->getName())][$response])) {
                if (Parkour::$db["save"][strtolower($player->getName())][$response] < time()) {
                    $time = true;
                }
            } else {
                $time = true;
            }
            if (!$time) {
                $format = "s";
                $remain = Parkour::$db["save"][strtolower($player->getName())][$response] - time();
                if (round($remain / 3600) != 0) {
                    $format = "h";
                    $time = round($remain / 3600);
                } elseif (round($remain / 60) != 0) {
                    $format = "i";
                    $time = round($remain / 60);
                } elseif (round($remain) != 0) {
                    $time = round($remain);
                }
                $player->sendMessage(
                    (string) new Text(
                        "timeLater",
                        Color::$warning,
                        Text::EXPLAIN,
                        "",
                        "{time}",
                        (
                            (string) new Text(
                                "time." . $format,
                                "",
                                Text::NONE,
                                "",
                                "{" . $format . "}",
                                $time
                            )
                        )
                    )
                );
            } else {
                $levelName = $parkour[$response]["world"];
                if (!Parkour::getInstance()->getServer()->getWorldManager()->isWorldLoaded($levelName)) {
                    if (!Parkour::getInstance()->getServer()->getWorldManager()->loadWorld($levelName)) {
                        $player->sendMessage((string) new Text("noWorld", Color::$error, Text::EXPLAIN));
                        return;
                    }
                }
                $playerData = Parkour::getData($player);
                $playerData["parkour"] = $response;
                $playerData["location"] = $player->getLocation();
                $playerData["checkPoint"] = 0;
                $playerData["noCheckPoint"] = false;
                $playerData["gameMode"] = $player->getGamemode();
                $playerData["time"] = 0;
                Parkour::setData($player, $playerData);
                $level = Parkour::getInstance()->getServer()->getWorldManager()->getWorldByName($parkour[$response]["world"]);
                $start = new Location(
                    $parkour[$response]["start"]["x"] + 0.5,
                    $parkour[$response]["start"]["y"],
                    $parkour[$response]["start"]["z"] + 0.5,
                    $level,
                    $player->getLocation()->getYaw(),
                    $player->getLocation()->getPitch()
                );
                Parkour::addPlay($player);
                $player->setGamemode(GameMode::ADVENTURE());
                $player->teleport($start);
                $player->sendMessage((string) new Text("entrance", Color::$explain, Text::EXPLAIN, "", "{name}", $parkour[$response]["name"]));
                $player->sendMessage((string) new Text("out", Color::$explain, Text::EXPLAIN));
            }
        }
    }

    public function sendTo(Player $player, $id = self::FORM_ID): void
    {
        $uiData = [];
        $uiData["type"] = "form";
        $uiData = $this->setTitle($uiData, (string) new Text("name", Color::$explain, Text::EXPLAIN));
        $parkour = Parkour::getParkour();
        $uiData["buttons"] = [];
        if (empty($parkour)) {
            $uiData = $this->addContent($uiData, (string) new Text("empty", Color::$explain, Text::EXPLAIN));
            $uiData = $this->addButton($uiData, (string) new Text("close", Color::$warning, Text::BUTTON));
        } else {
            $uiData = $this->addContent($uiData, (string) new Text("selectParkourPlay", Color::$explain, Text::EXPLAIN));
            foreach ($parkour as $value) {
                $name = $value["name"];
                $uiData = $this->addButton($uiData, (string) new Text($name, Color::$button, Text::BUTTON, "", "", "", false));
            }
        }
        $ui = new ModalFormRequestPacket();
        $ui->formId = self::FORM_ID;
        $ui->formData = json_encode($uiData);
        $player->getNetworkSession()->sendDataPacket($ui);
    }

    public function getFolderName(): string
    {
        return "all";
    }

    public function getName(): string
    {
        return "Play";
    }
}