<?php

namespace ClickGuard;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class Main extends PluginBase implements Listener {

    private $clicks = [];
    private $violations;
    private $config;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("ClickGuard enabled.");

        // Load configurations
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->violations = new Config($this->getDataFolder() . "violations.yml", Config::YAML);
    }

    public function onDisable() : void {
        $this->violations->save();
    }

    public function onPlayerJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        $this->clicks[$player->getName()] = [];
    }

    public function onPlayerQuit(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
        unset($this->clicks[$player->getName()]);
    }

    public function onPlayerInteract(PlayerInteractEvent $event) : void {
        $player = $event->getPlayer();
        $name = $player->getName();

        $currentTime = microtime(true);
        $this->clicks[$name][] = $currentTime;

        // Remove clicks older than 1 second
        $this->clicks[$name] = array_filter($this->clicks[$name], function($time) use ($currentTime) {
            return ($currentTime - $time) <= 1;
        });

        // Count clicks in the last second
        $clickCount = count($this->clicks[$name]);

        $maxClicksPerSecond = $this->config->get("clicks-per-second", 20);

        if ($clickCount > $maxClicksPerSecond) {
            $this->handleViolation($player);
        }
    }

    private function handleViolation(Player $player) : void {
        $name = $player->getName();
        $ip = $player->getNetworkSession()->getIp();
        $violations = $this->violations->get($name, 0) + 1;

        $this->violations->set($name, $violations);
        $this->violations->save();

        $maxViolations = $this->config->get("max-violations", 3);

        if ($violations >= $maxViolations) {
            $player->kick("Banned for cheating.", false);
            $this->getServer()->getIPBans()->addBan($ip, "Cheating", null, $this->getName());
            $this->getLogger()->info("Player $name has been banned for cheating.");
        } else {
            $player->kick("Kicked for cheating. Violations: $violations/$maxViolations", false);
            $this->getLogger()->info("Player $name has been kicked for cheating. Violations: $violations/$maxViolations");
        }
    }
}

