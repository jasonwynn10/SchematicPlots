<?php
declare(strict_types=1);
namespace jasonwynn10\SchematicPlots;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use spoondetector\SpoonDetector;

class Main extends PluginBase {
	private static $instance;

	/**
	 * @return self
	 */
	public static function getInstance() : self {
		return self::$instance;
	}

	public function onLoad() : void {
		$this->saveDefaultConfig();
		self::$instance = $this;
		$this->getLogger()->debug(TextFormat::BOLD . "Loading MyPlot Generator");
		GeneratorManager::addGenerator(SchematicGenerator::class, "SchematicPlots", true);
	}
	public function onEnable() {
		SpoonDetector::printSpoon($this, "spoon.txt");
		if($this->isDisabled()) {
			return;
		}
		$this->getServer()->getCommandMap()->register("SchematicPlots", new class extends Command {
			/**
			 * @param CommandSender $sender
			 * @param string $commandLabel
			 * @param string[] $args
			 *
			 * @return mixed
			 */
			public function execute(CommandSender $sender, string $commandLabel, array $args) {
				// TODO: Implement execute() method.
			}
		});
	}
}