<?php

namespace deveworld\parkour\form\admin;

use deveworld\parkour\form\UIPage;
use deveworld\parkour\utils\Color;
use deveworld\parkour\utils\Text;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\player\Player;

class SelWork extends UIPage
{
    public const FORM_ID = 18321001;

    public function handle(Player $player, $response, $id): void
    {
        switch ($response) {
            case 0:
                UIPage::getPageByName("admin", "SelManage")->sendTo($player);
                break;
            case 1:
                UIPage::getPageByName("all", "Play")->sendTo($player);
                break;
        }
    }

    public function sendTo(Player $player, $id = self::FORM_ID): void
    {
        $uiData = [];
        $uiData["type"] = "form";
        $uiData = $this->setTitle($uiData, (string) new Text("name", Color::$explain, Text::EXPLAIN));
        $uiData = $this->addContent($uiData, (string) new Text("selectWork", Color::$explain, Text::EXPLAIN));
        $uiData["buttons"] = [];
        $uiData = $this->addButton($uiData, (string) new Text("manage", Color::$button, Text::BUTTON));
        $uiData = $this->addButton($uiData, (string) new Text("play", Color::$button, Text::BUTTON));
        $uiData = $this->addButton($uiData, (string) new Text("close", Color::$warning, Text::BUTTON));
        $ui = new ModalFormRequestPacket();
        $ui->formId = self::FORM_ID;
        $ui->formData = json_encode($uiData);
        $player->getNetworkSession()->sendDataPacket($ui);
    }

    public function getFolderName(): string
    {
        return "admin";
    }

    public function getName(): string
    {
        return "SelWork";
    }
}