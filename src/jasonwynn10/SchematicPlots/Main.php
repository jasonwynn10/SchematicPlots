<?php
declare(strict_types=1);
namespace jasonwynn10\SchematicPlots;

use BlockHorizons\libschematic\Schematic;
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
		$this->saveResource("new.schematic");
		self::$instance = $this;
		$this->getLogger()->debug(TextFormat::BOLD . "Loading MyPlot Generator");
		GeneratorManager::addGenerator(SchematicGenerator::class, "SchematicPlots", true);
	}
	public function onEnable() {
		SpoonDetector::printSpoon($this, "spoon.txt");
		if($this->isDisabled()) {
			return;
		}
		$this->getServer()->getCommandMap()->register("SchematicPlots", new class($this) extends Command {
			private $plugin;
			/**
			 *  constructor.
			 *
			 * @param Main $plugin
			 */
			public function __construct(Main $plugin) {
				$this->plugin = $plugin;
				parent::__construct("/genschem", "generates a world based on a square schematic", "/genschem <file: string>", ["gs"]);
				$this->setPermission("genschem");
			}

			/**
			 * @param CommandSender $sender
			 * @param string $commandLabel
			 * @param string[] $args
			 *
			 * @return mixed
			 */
			public function execute(CommandSender $sender, string $commandLabel, array $args) {
				if(empty($args))
					return false;
				if($this->testPermission($sender)) {
					$schem = new Schematic($this->plugin->getDataFolder().$args[0].".schematic");
					$schem->decodeSizes();
					if($schem->getLength() !== $schem->getWidth()) {
						$sender->sendMessage("That schematic is not square!");
						return true;
					}
					$options = [];
					$options["file"] = $this->plugin->getDataFolder().$args[0].".schematic";
					$options["preset"] = json_encode($options);
					$this->plugin->getServer()->generateLevel($args[0], null, "SchematicGenerator", $options);
					$sender->sendMessage("world '" . $args[0] ."' has been generated.");
				}
				return true;
			}
		});
	}
}