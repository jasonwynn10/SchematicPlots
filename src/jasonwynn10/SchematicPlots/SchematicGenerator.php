<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 1/3/2019
 * Time: 1:55 PM
 */
namespace jasonwynn10\SchematicPlots;
use pocketmine\level\generator\Generator;
use pocketmine\math\Vector3;

class SchematicGenerator extends Generator {
	public function __construct(array $settings = []) {
		parent::__construct($settings);
	}

	public function generateChunk(int $chunkX, int $chunkZ) : void {
		// TODO: Implement generateChunk() method.
	}

	public function populateChunk(int $chunkX, int $chunkZ) : void {
		// TODO: Implement populateChunk() method.
	}

	public function getSettings() : array {
		// TODO: Implement getSettings() method.
	}

	public function getName() : string {
		// TODO: Implement getName() method.
	}

	public function getSpawn() : Vector3 {
		// TODO: Implement getSpawn() method.
	}
}