<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use Terpz710\TNTBlastRadius\TNTCommand;
use Terpz710\TNTBlastRadius\TNTForm;
use Terpz710\TNTBlastRadius\TNTEventListener;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new TNTEventListener(), $this);
        $this->getServer()->getCommandMap()->register("tntradius", new TNTCommand()); // Create instance without parameters
    }
}
