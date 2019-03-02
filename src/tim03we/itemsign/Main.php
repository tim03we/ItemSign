<?php

namespace tim03we\itemsign;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public function configUpdater(): void {
        $settings = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
		if($settings->get("version") !== "1.0.4"){
			rename($this->getDataFolder() . "settings.yml", $this->getDataFolder() . "settings_old.yml");
			$this->saveResource("settings.yml");
            $this->getLogger()->notice("We create a new settings.yml file for you.");
            $this->getLogger()->notice("Because the config version has changed. Your old configuration has been saved as settings_old.yml.");
		}
	}

	public function onEnable() {
		$this->configUpdater();
		$this->getServer()->getCommandMap()->register("itemsign", new ItemSignCommand($this));
		$this->saveResource("settings.yml");
		$settings = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
	}

	public function onDisable() {
	}
}