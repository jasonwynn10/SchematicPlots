<?php
declare(strict_types=1);
namespace jasonwynn10\SchematicPlots;

use pocketmine\level\generator\GeneratorManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use spoondetector\SpoonDetector;

class Main extends PluginBase {
	public function onLoad() : void {
		$this->saveDefaultConfig();
		$this->getLogger()->debug(TextFormat::BOLD . "Loading MyPlot Generator");
		GeneratorManager::addGenerator(SchematicGenerator::class, "SchematicPlots", true);
	}
	public function onEnable() {
		SpoonDetector::printSpoon($this, "spoon.txt");
		if($this->isDisabled()) {
			return;
		}
	}
}