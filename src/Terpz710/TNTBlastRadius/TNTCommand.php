<?php

namespace Terpz710;

namespace Terpz710\TNTBlastRadius;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\player\Player;

class TNTCommand extends Command {

    private $plugin;

    public function __construct(Plugin $plugin) {
        parent::__construct("tntradius", "Change TNT blast radius");
        $this->plugin = $plugin;
        $this->setPermission("tntradius.command"); 
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cYou must be in-game to run this command");
            return true;
        }

        $this->plugin->openRadiusSelectorUI($sender);

        return true;
    }
}
