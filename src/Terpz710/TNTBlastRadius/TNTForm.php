<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\event\Listener;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use jojoe77777\FormAPI\CustomForm;

class TNTForm implements Listener {

    public static function execute(Player $player): void {
        $form = new CustomForm(function (Player $player, ?array $data) {
            if ($data !== null) {
                $radius = max(1, min(25, (int)$data[0]));

                $player->setTNTExplosionSize($radius);

                $player->sendMessage("Blast radius set to: " . $radius);
            }
        });

        $form->setTitle("TNT Blast Radius");
        $form->addSlider("Radius:", 1, 25, 1, 1);
        $player->sendForm($form);
    }

    public function onExplosionPrime(ExplosionPrimeEvent $event) {
        $entity = $event->getEntity();
        if ($entity instanceof PrimedTNT) {
            $player = $entity->getOwningEntity();
            if ($player instanceof Player) {
                $radius = $player->getTNTExplosionSize();

                $entity->setRadius($radius);
            }
        }
    }
}
