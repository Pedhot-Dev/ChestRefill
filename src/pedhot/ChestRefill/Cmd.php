<?php


namespace pedhot\ChestRefill;


use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Cmd extends PluginCommand
{

    /** @var Main */
    private $plugin;

    public function __construct(string $name, Main $owner)
    {
        $this->plugin = $owner;
        parent::__construct($name, $owner);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player){
            return true;
        }
        if (!$sender->isOp()){
            return false;
        }
        if (count($args) !== 2){
            return false;
        }
        $worldName = $args[0];

        $this->plugin->refillChest($sender, $worldName);
    }

}