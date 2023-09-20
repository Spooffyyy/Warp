<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\event\Listener;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\event\entity\EntityPreExplodeEvent;

class TNTForm implements Listener {

    private static $blastRadius = 4;

    public static function execute(Player $player): void {
        $form = new CustomForm(function (Player $player, ?array $data) {
            if ($data !== null) {
                $pickedRadius = max(1, min(25, (int)$data[0])); // Get the value picked from the slider

                $confirmation = new SimpleForm(function (Player $player, int $data) use ($pickedRadius) {
                    if ($data === 0) {
                        self::$blastRadius = $pickedRadius; // Set the new blast radius
                        $player->sendMessage("Successfully changed Blast Radius to: " . self::$blastRadius);
                    } else {
                        $player->sendMessage("Blast radius change canceled.");
                    }
                });

                $confirmation->setTitle("Confirm Radius");
                $confirmation->setContent("Are you sure you want to set the TNT blast radius to " . $pickedRadius . "?");
                $confirmation->addButton("Yes");
                $confirmation->addButton("No");

                $player->sendForm($confirmation);
            }
        });

        $form->setTitle("TNT Blast Radius");
        $form->addSlider("Radius:", 1, 25, 1, self::$blastRadius); // Use the stored radius as the default
        $player->sendForm($form);
    }

    public function onExplosionPrime(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $scaledRadius = max(1, min(25, self::$blastRadius));
            $event->setRadius($scaledRadius);
        }
    }
}
