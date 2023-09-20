<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TNTCommand extends Command {

    public function __construct() {
        parent::__construct("tntradius", "Adjust the TNT blast radius");
        $this->setPermission("tntradius.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            $form = new TNTForm();
            $form->execute($sender);
        } else {
            $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
    }
}
