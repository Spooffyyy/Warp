<?php

namespace Terpz710;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class TNTBlastRadius extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onExplosionPrime(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $event->setCancelled(); // Prevent the explosion for now.
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cYou must be in-game to run this command");
            return true;
        }

        switch ($command->getName()) {
            case "tntradius":
                $this->openRadiusSelectorUI($sender);
                break;
        }

        return true;
    }

    public function openRadiusSelectorUI(Player $player) {
        $form = new CustomForm(function (Player $player, ?array $data) {
            if ($data !== null) {
                $radius = (int)$data[0];
                if ($radius >= 1 && $radius <= 25) {
                    $this->setTNTBlastRadius($player, $radius);
                } else {
                    $player->sendMessage("Invalid radius value. Please select a number between 1 and 25.");
                }
            }
        });

        $form->setTitle("TNT Blast Radius Selector");
        $form->addSlider("Select the TNT blast radius:", 1, 25, 1, 4);

        $player->sendForm($form);
    }

    public function setTNTBlastRadius(Player $player, int $radius) {
        $level = $player->getLevel();

        foreach ($level->getEntities() as $entity) {
            if ($entity instanceof PrimedTNT) {
                $entity->setBlastRadius($radius);
            }
        }

        $player->sendMessage("TNT blast radius changed to $radius");
    }
}
