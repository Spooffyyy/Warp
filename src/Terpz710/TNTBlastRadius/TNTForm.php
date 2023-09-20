<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\event\Listener;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;

class TNTForm implements Listener {

    public static function execute(Player $player): void {
        $form = new CustomForm(function (Player $player, ?array $data) {
            if ($data !== null) {
                $radius = max(1, min(25, (int)$data[0]));

                $confirmation = new SimpleForm(function (Player $player, int $data) use ($radius) {
                    if ($data === 0) {
                        
                        $player->sendMessage("Successfully changed Blast Radius to: " . $radius);
                    } else {
                        
                        $player->sendMessage("Blast radius change canceled.");
                    }
                });

                $confirmation->setTitle("Confirm Radius");
                $confirmation->setContent("Are you sure you want to set the TNT blast radius to $radius?");
                $confirmation->addButton("Yes");
                $confirmation->addButton("No");

                $player->sendForm($confirmation);
            }
        });

        $form->setTitle("TNT Blast Radius");
        $form->addSlider("Radius:", 1, 25, 1, 1);
        $player->sendForm($form);
    }

    public function onExplosionPrime(ExplosionPrimeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $player = $tnt->getOwningEntity();
            if ($player instanceof Player) {
                $scaledRadius = max(1, min(25, $tnt->getRadius()));
                $event->setRadius($scaledRadius);
            }
        }
    }
}
