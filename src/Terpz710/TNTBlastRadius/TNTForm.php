<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\event\Listener;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\event\entity\EntityPreExplodeEvent;

class TNTForm implements Listener {

    public function execute(Player $player, int $scaledRadius): void {
        $form = new CustomForm(function (Player $player, ?array $data) use ($scaledRadius) {
            if ($data !== null) {
                $confirmation = new SimpleForm(function (Player $player, int $data) use ($scaledRadius) {
                    if ($data === 0) {
                        $player->sendMessage("Successfully changed Blast Radius to: " . $scaledRadius);
                    } else {
                        $player->sendMessage("Blast radius change canceled.");
                    }
                });

                $confirmation->setTitle("Confirm Radius");
                $confirmation->setContent("Are you sure you want to set the TNT blast radius to " . $scaledRadius . "?");
                $confirmation->addButton("Yes");
                $confirmation->addButton("No");

                $player->sendForm($confirmation);
            }
        });

        $form->setTitle("TNT Blast Radius");
        $form->addSlider("Radius:", 1, 25, 1, $scaledRadius); // Use the $scaledRadius as the default
        $player->sendForm($form);
    }

    public function onExplosionPrime(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $scaledRadius = max(1, min(25, $scaledRadius)); // Adjust $scaledRadius as needed
            $event->setRadius($scaledRadius);
        }
    }
}
