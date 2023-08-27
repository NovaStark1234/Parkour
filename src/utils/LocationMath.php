<?php

namespace deveworld\parkour\utils;

use deveworld\parkour\Parkour;
use pocketmine\world\World;
use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class LocationMath
{

    /**
     * go to last checkpoint
     *
     * @brief teleport player to last checkpoint. If not reached any checkpoint, go to start point.
     *
     * @param Player $player target player
     * @param bool   $level set location Level with parkour World Level
     */
    public static function goToLastCheckpoint(Player $player, bool $level = false): void
    {
        $player->teleport(self::getLastCheckPoint($player, $level));
    }

    /**
     * get Last checkpoint
     *
     * @param Player $player
     * @param bool   $level
     *
     * @return Location
     */
    public static function getLastCheckPoint(Player $player, bool $level = false): Location
    {
        $playerData = Parkour::getData($player);
        $parkour = Parkour::getParkour()[$playerData["parkour"]];
        if ($level) {
            $level = Parkour::getInstance()->getServer()->getWorldManager()->getWorldByName($parkour["world"]);
        } else {
            $level = $player->getWorld();
        }
        if ($playerData["checkPoint"] == 0) {
            return self::arrayToLocation($player, $parkour["start"], $level);
        } else {
            return self::arrayToLocation($player, $parkour["checkPoint"][$playerData["checkPoint"] - 1], $level);
        }
    }

    /**
     * @param Player     $player
     * @param array      $arrayLoc
     * @param World|null $level
     *
     * @return Location
     */
    public static function arrayToLocation(Player $player, array $arrayLoc, World $level = null): Location
    {
        if ($level == null) {
            $level = $player->getWorld();
        }
        return new Location(
            $arrayLoc["x"] + 0.5,
            $arrayLoc["y"],
            $arrayLoc["z"] + 0.5,
            $level,
            $player->getLocation()->getYaw(),
            $player->getLocation()->getPitch()
        );
    }

    /**
     * check location is equal
     *
     * @brief check location and location2 is equal
     *
     * @param Location $location location
     * @param array    $location2 array location
     *
     * @return bool
     */
    public static function equal(Location $location, array $location2): bool
    {
        $location = new Vector3(
            $location->getFloorX(),
            $location->getFloorY(),
            $location->getFloorZ()
        );
        $check1 = $location->equals(
            new Vector3(
                (int) $location2["x"],
                (int) $location2["y"],
                (int) $location2["z"]
            )
        );
        $check2 = $location->equals(
            new Vector3(
                (int) $location2["x"],
                (int) $location2["y"] + 1,
                (int) $location2["z"]
            )
        );
        $check3 = $location->equals(
            new Vector3(
                (int) $location2["x"],
                (int) $location2["y"] - 1,
                (int) $location2["z"]
            )
        );
        return $check1 || $check2 || $check3;
    }
}