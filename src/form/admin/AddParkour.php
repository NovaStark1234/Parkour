<?php

namespace deveworld\parkour\form\admin;

use deveworld\parkour\Parkour;
use deveworld\parkour\form\UIPage;
use deveworld\parkour\utils\Color;
use deveworld\parkour\utils\Text;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\player\Player;

class AddParkour extends UIPage
{
    public const FORM_ID = 18321004;

    public function handle(Player $player, $response, $id): void
    {
        $id = $id - 18321004;
        switch ($id) {
            case 0:
                if (is_string($response[0]) && ($response[0] != "")) {
                    $overLap = false;
                    foreach (Parkour::getParkour() as $value) {
                        if ($value["name"] == $response[0]) {
                            $overLap = true;
                            break;
                        }
                    }
                    if ($overLap) {
                        $player->sendMessage((string) new Text("alreadyName", Color::$error, Text::EXPLAIN));
                        $this->sendTo($player);
                        return;
                    }
                    $playerData = Parkour::getData($player);
                    if (!isset($playerData["addParkour"])) {
                        $playerData["addParkour"] = [];
                    }
                    $playerData["addParkour"]["name"] = $response[0];
                    $playerData["addParkour"]["select"] = 0;
                    $playerData["addParkour"]["now"] = 0;
                    Parkour::setData($player, $playerData);
                    $this->sendTo($player, 1);
                } else {
                    $player->sendMessage((string) new Text("addParkour.notValid", Color::$warning, Text::EXPLAIN));
                    $this->sendTo($player);
                }
                break;
            case 1:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 1;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 2);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour");
                        $this->sendTo($player);
                        break;
                }
                break;
            case 2:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 2;
                        $playerData["addParkour"]["select"] = "start";
                        $playerData["addParkour"]["start"] = [];
                        Parkour::setData($player, $playerData);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour");
                        $this->sendTo($player);
                }
                break;
            case 3:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 3;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 4);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "start");
                        $this->sendTo($player, 2);
                        break;
                }
                break;
            case 4:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["select"] = "checkPoint";
                        if (!isset($playerData["addParkour"]["checkPoint"])) {
                            $playerData["addParkour"]["checkPoint"] = [];
                            $playerData["addParkour"]["checkPoint"]["select"] = 0;
                        }
                        $playerData["addParkour"]["now"] = 4;
                        Parkour::setData($player, $playerData);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "start");
                        $this->sendTo($player, 2);
                        break;
                }
                break;
            case 5:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 5;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 6);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "checkPoint");
                        $this->sendTo($player, 4);
                        break;
                }
                break;
            case 6:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 6;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 4);
                        break;

                    case 1:
                        $this->sendTo($player, 7);
                        break;
                }
                break;
            case 7:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["select"] = "end";
                        $playerData["addParkour"]["end"] = [];
                        $playerData["addParkour"]["now"] = 7;
                        Parkour::setData($player, $playerData);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "checkPoint");
                        $this->sendTo($player, 4);
                        break;
                }
                break;
            case 8:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 8;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 9);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "end");
                        $this->sendTo($player, 7);
                        break;
                }
                break;
            case 9:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["select"] = "floor";
                        $playerData["addParkour"]["floor"] = [];
                        $playerData["addParkour"]["now"] = 9;
                        Parkour::setData($player, $playerData);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "end");
                        $this->sendTo($player, 7);
                        break;
                }
                break;
            case 10:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 10;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 11);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "floor");
                        $this->sendTo($player, 9);
                        break;
                }
                break;
            case 11:
                if (is_string($response[0]) && ($response[0] != "")) {
                    $playerData = Parkour::getData($player);
                    $playerData["addParkour"]["money"] = $response[0];
                    $playerData["addParkour"]["now"] = 11;
                    Parkour::setData($player, $playerData);
                    $this->sendTo($player, 12);
                } else {
                    $player->sendMessage((string) new Text("addParkour.notValid", Color::$warning, Text::EXPLAIN));
                    $this->sendTo($player, 11);
                }
                break;
            case 12:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 12;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 13);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "money");
                        $this->sendTo($player, 11);
                        break;
                }
                break;
            case 13:
                $playerData = Parkour::getData($player);
                $options = [0, 6, 12, 24, 48, 72];
                $playerData["addParkour"]["time"] = $options[$response[0]];
                $playerData["addParkour"]["now"] = 13;
                Parkour::setData($player, $playerData);
                $this->sendTo($player, 14);
                break;
            case 14:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 14;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 15);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "time");
                        $this->sendTo($player, 13);
                        break;
                }
                break;
            case 15:
                switch ($response) {
                    case 0:
                        $playerData = Parkour::getData($player);
                        $playerData["addParkour"]["now"] = 15;
                        Parkour::setData($player, $playerData);
                        $this->sendTo($player, 16);
                        break;

                    case 1:
                        Parkour::delData($player, "addParkour", "time");
                        $this->sendTo($player, 13);
                        break;
                }
                break;
            case 16:
                $playerData = Parkour::getData($player);
                $playerData["addParkour"]["now"] = 16;
                $playerData["addParkour"]["world"] = $player->getWorld()->getFolderName();
                Parkour::setData($player, $playerData);
                Parkour::addParkour($player);
                break;

        }
    }

    public function sendTo(Player $player, $id = 0): void
    {
        $uiData = [];
        $uiData = $this->setTitle($uiData, (string) new Text("name", Color::$explain, Text::EXPLAIN));
        $uiData["buttons"] = [];
        switch ($id) {
            case 0: // Parkour Name Question
                $uiData["type"] = "custom_form";
                $uiData["content"][0]["type"] = "input";
                $uiData["content"][0]["text"] = (string) new Text("addParkour.name", Color::$explain, Text::EXPLAIN);
                $uiData["content"][0]["default"] = "";
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                break;
            case 1: // Check Name
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $parkourName = $playerData["addParkour"]["name"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.check", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addContent($uiData, "\n" . new Text("addParkour.checkName", Color::$explain, Text::NONE, " = $parkourName"));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 2: // Parkour Set Start Position
                $uiData["type"] = "form";
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.start", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 3: // Check Start Position
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $x = $playerData["addParkour"]["start"]["x"];
                $y = $playerData["addParkour"]["start"]["y"];
                $z = $playerData["addParkour"]["start"]["z"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.check", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addContent($uiData, "\n" . new Text("addParkour.checkStart", Color::$explain, Text::EXPLAIN, " = $x, $y, $z"));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 4: // Parkour Set CheckPoint Position
                $uiData["type"] = "form";
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.checkPoint", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 5: // Check CheckPoint Position
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $n = count($playerData["addParkour"]["checkPoint"]);
                $x = $playerData["addParkour"]["checkPoint"][$n - 2]["x"];
                $y = $playerData["addParkour"]["checkPoint"][$n - 2]["y"];
                $z = $playerData["addParkour"]["checkPoint"][$n - 2]["z"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.check", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addContent($uiData, "\n" . new Text("addParkour.checkCheckPoint", Color::$explain, Text::EXPLAIN, " = $x, $y, $z", "{n}", $n - 1));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 6: // Ask More Set CheckPoint
                $uiData["type"] = "form";
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.addMore", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 7: // Parkour Set End Position
                $uiData["type"] = "form";
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.end", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 8: // Check End Position
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $x = $playerData["addParkour"]["end"]["x"];
                $y = $playerData["addParkour"]["end"]["y"];
                $z = $playerData["addParkour"]["end"]["z"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.check", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addContent($uiData, "\n" . new Text("addParkour.checkEnd", Color::$explain, Text::EXPLAIN, " = $x, $y, $z"));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 9: // Parkour Set Floor Position
                $uiData["type"] = "form";
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.floor", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 10: // Check Floor Position
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $y = $playerData["addParkour"]["floor"]["y"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.check", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addContent($uiData, "\n" . new Text("addParkour.checkFloor", Color::$explain, Text::EXPLAIN, " = $y"));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 11: // Parkour Set Reward Money
                $uiData["type"] = "custom_form";
                $uiData["content"][0]["type"] = "input";
                $uiData["content"][0]["text"] = (string) new Text("addParkour.rewardMoney", Color::$explain, Text::EXPLAIN);
                $uiData["content"][0]["default"] = "";
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                break;
            case 12: // Check Reward
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $money = $playerData["addParkour"]["money"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.check", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addContent($uiData, "\n" . new Text("addParkour.checkMoney", Color::$explain, Text::NONE, " = $money"));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 13: // Parkour Set Again Time
                $uiData["type"] = "custom_form";
                $uiData["content"][0]["type"] = "dropdown";
                $uiData["content"][0]["text"] = (string) new Text("addParkour.againTime", Color::$explain, Text::EXPLAIN);
                $options = [];
                foreach ([0, 6, 12, 24, 48, 72] as $k => $i) {
                    $options[$k] = (string) new Text("time.h", Color::$explain, Text::EXPLAIN, "", "{h}", $i);
                }
                $uiData["content"][0]["options"] = $options;
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 14: // check Again Time
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $time = $playerData["addParkour"]["time"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.check", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addContent($uiData, "\n" . new Text("addParkour.checkTime", Color::$explain, Text::NONE, " = $time"));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 15: // Last check
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $parkourName = $playerData["addParkour"]["name"];
                $x = $playerData["addParkour"]["start"]["x"];
                $y = $playerData["addParkour"]["start"]["y"];
                $z = $playerData["addParkour"]["start"]["z"];
                $uiData = $this->addContent($uiData, "\n" . new Text("addParkour.check", Color::$explain, Text::EXPLAIN));
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.checkName", Color::$explain, Text::NONE, " = $parkourName"));
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.checkStart", Color::$explain, Text::EXPLAIN, " = $x, $y, $z"));
                $i = 1;
                foreach ($playerData["addParkour"]['checkPoint'] as $key => $value) {
                    if ($key == "select") {
                        continue;
                    }
                    $x = $value['x'];
                    $y = $value["y"];
                    $z = $value['z'];
                    $uiData = $this->addContent($uiData, (string) new Text("addParkour.checkCheckPoint", Color::$explain, Text::EXPLAIN, " = $x, $y, $z", "{n}", $i));
                    $i++;
                }
                $x = $playerData["addParkour"]["end"]["x"];
                $y = $playerData["addParkour"]["end"]["y"];
                $z = $playerData["addParkour"]["end"]["z"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.checkEnd", Color::$explain, Text::EXPLAIN, " = $x, $y, $z"));
                $y = $playerData["addParkour"]["floor"]["y"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.checkFloor", Color::$explain, Text::EXPLAIN, " = $y"));
                $money = $playerData["addParkour"]["money"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.checkMoney", Color::$explain, Text::NONE, " = $money"));
                $time = $playerData["addParkour"]["time"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.checkTime", Color::$explain, Text::NONE, " = $time"));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
                break;
            case 16:
                $uiData["type"] = "form";
                $playerData = Parkour::getData($player);
                $parkourName = $playerData["addParkour"]["name"];
                $uiData = $this->addContent($uiData, (string) new Text("addParkour.done", Color::$explain, Text::EXPLAIN, "", "{name}", $parkourName));
                $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
                break;
        }
        $ui = new ModalFormRequestPacket();
        $ui->formId = self::FORM_ID + $id;
        $ui->formData = json_encode($uiData);
        $player->getNetworkSession()->sendDataPacket($ui);
    }

    public function getFolderName(): string
    {
        return "admin";
    }

    public function getName(): string
    {
        return "AddParkour";
    }
}