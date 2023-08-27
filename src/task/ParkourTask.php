<?php

namespace deveworld\parkour\task;

use deveworld\parkour\Parkour;
use deveworld\parkour\utils\Color;
use deveworld\parkour\utils\LocationMath;
use deveworld\parkour\utils\Text;
use JavierLeon9966\VanillaElytra\item\Elytra;
use onebone\economyapi\EconomyAPI;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ParkourTask extends Task
{

    private Parkour $plugin;

    public function __construct(Parkour $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(): void
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if (isset(Parkour::$plays[strtolower($player->getName())])) {
                $playerData = Parkour::getData($player);
                $parkour = Parkour::getParkour()[$playerData["parkour"]];
                $location = $player->getLocation();

                // If parkour world and player world isn't same
                if ($parkour['world'] != $player->getWorld()->getFolderName()) {
                    LocationMath::goToLastCheckpoint($player, true);
                    $player->sendMessage((string) new Text("noWarp", Color::$warning, Text::EXPLAIN));
                }

                // If player use elytra in parkour
                // Depend on plugin VanillaElytra by JavierLeon9966
                if ($player->getArmorInventory()->getChestplate() instanceof Elytra) {
                    if ($player->isGliding()) {
                        $player->toggleGlide(false);
                        $player->teleport(LocationMath::arrayToLocation($player, $parkour["start"], $player->getWorld()));
                        $playerData["checkPoint"] = 0;
                        $playerData["noCheckPoint"] = false;
                        $player->sendMessage((string) new Text("noElytra", Color::$warning, Text::EXPLAIN));
                    }
                }

                // If remain more checkpoint
                if (!$playerData["noCheckPoint"]) {
                    if (LocationMath::equal($location, $parkour["checkPoint"][$playerData["checkPoint"]])) {
                        $playerData["checkPoint"] += 1;
                        if (!isset($parkour["checkPoint"][$playerData["checkPoint"]])) {
                            $playerData["noCheckPoint"] = true;
                        }
                        $player->sendMessage((string) new Text("reachCheckPoint", Color::$explain, Text::EXPLAIN, "", "{n}", (string) $playerData["checkPoint"]));
                    }
                }

                // Reach floor
                if ($location->getFloorY() <= $parkour["floor"]["y"] - 2) {
                    LocationMath::goToLastCheckpoint($player);
                }

                $time = gmdate("H:i:s", floor($playerData["time"] / 20));
                $player->sendTip("Timer: " . $time);
                // Reach END
                if (LocationMath::equal($location, $parkour["end"])) {
                    if (!$playerData["noCheckPoint"]) {
                        $player->sendMessage((string) new Text("reachAll", Color::$warning, Text::EXPLAIN));
                        continue;
                    }
                    $parkourName = $parkour["name"];
                    $player->sendMessage((string) new Text("clear", Color::$explain, Text::EXPLAIN, "", ["{name}", "{time}"], [$parkourName, $time]));
                    $player->setGamemode($playerData["gameMode"]);
                    $player->teleport($playerData["location"]);
                    Parkour::$db["save"][strtolower($player->getName())][$playerData["parkour"]] = mktime(
                        (
                            (int) date("H") + $parkour["time"]
                        ),
                        date("i"),
                        date("s"),
                        date("m"),
                        date("d"),
                        date("Y")
                    );
                    Parkour::delData($player);
                    Parkour::delPlay($player);
                    if (isset($parkour["commands"])) {
                        foreach ($parkour["commands"] as $command) {
                            Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), str_replace("{player}", $player->getName(), $command));
                        }
                    }
                    if(class_exists(EconomyAPI::class)) {
                        EconomyAPI::getInstance()->addMoney($player, $parkour['money']);
                    }
                }
                ++$playerData["time"];
                Parkour::setData($player, $playerData);
            }
        }
    }
}