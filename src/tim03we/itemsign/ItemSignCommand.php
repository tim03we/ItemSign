<?php

declare(strict_types=1);

namespace tim03we\itemsign;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use tim03we\itemsign\Main;

class ItemSignCommand extends Command {
	
	public function __construct(Main $plugin) {
		parent::__construct("itemsign", "Sign your Item", "/itemsign <text>", ["isign"]);
		$this->setPermission("itemsign.use");
		$this->plugin = $plugin;
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(!$sender instanceof Player) {
			$sender->sendMessage("Run this Command InGame!");
			return true;
		}
		if(empty($args)) {
			$sender->sendMessage($this->getUsage());
			return true;
		}
		$item = $sender->getInventory()->getItemInHand();
        $settings = new Config($this->plugin->getDataFolder() . "settings.yml", Config::YAML);
        $date = date($settings->get("date-format"));
        $time = date($settings->get("time-format"));
        $name = $sender->getName();
        $fullargs = implode(" ", $args);
        $item->clearCustomName();
        $item->setLore([$this->convert($settings->get("lore-line1"), $date, $time, $name)."\n".$this->convert($settings->get("lore-line2"), $date, $time, $name)]);
		$item->setCustomName(str_replace("&", TF::ESCAPE, $fullargs));
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage($settings->get("finished-signed"));
        return false;
    }

    public function convert(string $string, $date, $time, $name): string{
        $string = str_replace("{date}", $date, $string);
        $string = str_replace("{time}", $time, $string);
        $string = str_replace("{name}", $name, $string);
        return $string;
	}
}