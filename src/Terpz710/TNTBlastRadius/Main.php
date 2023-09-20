<?php

namespace Terpz710\TNTBlastRadius;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    private $blastRadius = 4; // Default blast radius

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onEntityPreExplode(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $event->setRadius($this->blastRadius);
        }
    }

    public function openRadiusSelectorUI(Player $player) {
        $form = new CustomForm(function (Player $player, ?array $data) {
            if ($data !== null) {
                $radius = (int)$data[0];
                if ($radius >= 1 && $radius <= 25) {
                    $this->setTNTBlastRadius($player, $radius);
                    $player->sendMessage("TNT blast radius changed to $radius for all players");
                } else {
                    $player->sendMessage("Invalid radius value. Please select a number between 1 and 25.");
                }
            }
        });

        $form->setTitle("TNT Blast Radius Selector");
        $form->addSlider("Select the TNT blast radius for all players:", 1, 25, $this->blastRadius);

        $player->sendForm($form);
    }

    public function setTNTBlastRadius(Player $player, int $radius) {
        // Update the TNT blast radius for all TNT entities on the server.
        $this->blastRadius = $radius;
        $player->sendMessage("TNT blast radius changed to $radius for all players");
    }
}
