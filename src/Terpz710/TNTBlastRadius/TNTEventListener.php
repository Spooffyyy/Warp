<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\entity\object\PrimedTNT;

class TNTEventListener implements Listener {

    public function onPreExplode(EntityPreExplodeEvent $event) {
        $entity = $event->getEntity();

        if ($entity instanceof PrimedTNT) {
            $scaledRadius = max(1, min(25, $scaledRadius));
            $entity->setRadius($scaledRadius);
        }
    }
}
