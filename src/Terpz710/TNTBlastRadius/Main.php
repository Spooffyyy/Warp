<?php

namespace Terpz710\TNTBlastRadius;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onExplosionPrime(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $event->setCancelled();
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
        
            case "setradius":
                if (count($args) !== 1) {
                    $sender->sendMessage("Usage: /setradius <radius>");
                    return false;
                }

                $radius = (int)$args[0];
                if ($radius >= 1 && $radius <= 25) {
                    $this->setTNTBlastRadius($sender, $radius);
                    $sender->sendMessage("TNT blast radius set to $radius");
                } else {
                    $sender->sendMessage("Invalid radius value. Please select a number between 1 and 25.");
                }
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
        $form->addSlider("Select the TNT blast radius:", 1, 25, 4);

        $player->sendForm($form);
    }

    public function setTNTBlastRadius(Player $player, int $radius) {
        $level = $player->getLevel();

        foreach ($level->getEntities() as $entity) {
            if ($entity instanceof PrimedTNT) {
                // Update the TNT blast radius.
                $entity->setBlastRadius($radius);
            }
        }

        $player->sendMessage("TNT blast radius changed to $radius");
    }
}
