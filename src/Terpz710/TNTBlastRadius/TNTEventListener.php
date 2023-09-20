<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\event\Listener;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\event\entity\EntityPreExplodeEvent;

class TNTEventListener implements Listener {

    public function onExplosionPrime(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $scaledRadius = max(1, min(25, TNTForm::$blastRadius));
            $event->setRadius($scaledRadius);
        }
    }
}
