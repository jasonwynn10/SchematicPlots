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
	private $settings = [];
	private $blocks = [];
	private $length = 0;
	private $width = 0;

	public function __construct(array $settings = []) {
		if(isset($settings["preset"])) {
			$settings = json_decode($settings["preset"], true);
		}
		$this->settings = $settings;
		$schematic = new Schematic($settings["file"] ?? "");
		$schematic->decode();
		$schematic->fixBlockIds();
		$this->blocks = $schematic->getBlocks();
		$this->length = $schematic->getLength();
		$this->width = $schematic->getWidth();
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
		return new Vector3(0, $this->settings["height"] + 1, 0);
	}
}