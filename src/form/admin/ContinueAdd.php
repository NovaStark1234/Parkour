<?php

namespace deveworld\parkour\form\admin;

use deveworld\parkour\Parkour;
use deveworld\parkour\form\UIPage;
use deveworld\parkour\utils\Color;
use deveworld\parkour\utils\Text;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\player\Player;

class ContinueAdd extends UIPage
{
    public const FORM_ID = 18321025;

    public function handle(Player $player, $response, $id): void
    {
        switch ($response) {
            case 0:
                $playerData = Parkour::getData($player);
                UIPage::getPageByName("admin", "AddParkour")->sendTo($player, $playerData["addParkour"]["now"]);
                break;

            case 1:
                Parkour::delData($player, "addParkour");
                UIPage::getPageByName("admin", "AddParkour")->sendTo($player);
                break;
        }
    }

    public function sendTo(Player $player, $id = 1): void
    {
        $uiData = [];
        $uiData["type"] = "form";
        $uiData = $this->setTitle($uiData, (string) new Text("name", Color::$explain, Text::EXPLAIN));
        $uiData = $this->addContent($uiData, (string) new Text("addParkour.continue", Color::$explain, Text::EXPLAIN));
        $uiData["buttons"] = [];
        $uiData = $this->addButton($uiData, (string) new Text("ok", Color::$button, Text::BUTTON));
        $uiData = $this->addButton($uiData, (string) new Text("cancel", Color::$warning, Text::BUTTON));
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
        return "ContinueAdd";
    }
}