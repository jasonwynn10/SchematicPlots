<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 1/3/2019
 * Time: 1:55 PM
 */
namespace jasonwynn10\SchematicPlots;

use BlockHorizons\libschematic\Schematic;
use pocketmine\level\generator\Generator;
use pocketmine\math\Vector3;

class SchematicGenerator extends Generator {
	private $settings;

	public function __construct(array $settings = []) {
		if(isset($settings["preset"])) {
			$settings = json_decode($settings["preset"], true);
			if($settings === false or is_null($settings)) {
				$settings = [];
			}
		}else{
			$settings = [];
		}
		// TODO: get schematic block data
		$schematic = new Schematic($settings["location"] ?? "");
		$blocks = $schematic->decodeBlocks($settings["blocks"],$settings["meta"],$settings["height"], $settings["width"],$settings["length"]);
	}

	public function generateChunk(int $chunkX, int $chunkZ) : void {
		// TODO: replicate pattern without chunks resetting it
	}

	public function populateChunk(int $chunkX, int $chunkZ) : void {}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "SchematicGenerator";
	}

	/**
	 * @return string[]
	 */
	public function getSettings() : array {
		return $this->settings;
	}

	/**
	 * @return Vector3
	 */
	public function getSpawn() : Vector3 {
		// TODO: 0,0 at 1 above schematic height
		return new Vector3(0, 0, 0);
	}
}