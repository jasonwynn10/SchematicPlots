<?php
declare(strict_types=1);
namespace jasonwynn10\SchematicPlots;

use BlockHorizons\libschematic\Schematic;
use pocketmine\block\Block;
use pocketmine\level\biome\Biome;
use pocketmine\level\generator\Generator;
use pocketmine\level\Level;
use pocketmine\math\Vector3;

class SchematicGenerator extends Generator {
	private $settings = [];
	private $blocks = [];
	private $length = 0;
	const PLOT = 0;
	const ROAD = 1;
	const WALL = 2;

	public function __construct(array $settings = []) {
		if(isset($settings["preset"])) {
			$settings = json_decode($settings["preset"], true);
		}
		$this->settings = $settings;
		if(file_exists($settings["file"]))
			$schematic = new Schematic($settings["file"]);
		else
			$schematic = new Schematic($settings["blank"]);
		$schematic->decode();
		$schematic->fixBlockIds();
		$this->blocks = $schematic->getBlocks();
		$this->length = $schematic->getLength();
	}

	public function generateChunk(int $chunkX, int $chunkZ) : void {
		$shape = $this->getShape($chunkX << 4, $chunkZ << 4);
		$chunk = $this->level->getChunk($chunkX, $chunkZ);
		$groundHeight = Level::Y_MAX;
		for($Z = 0; $Z < 16; ++$Z) {
			for($X = 0; $X < 16; ++$X) {
				$chunk->setBiomeId($X, $Z, Biome::PLAINS);
				for($y = 0; $y <= Level::Y_MAX; ++$y) {
					/** @var Block $block */
					$block = array_shift($this->blocks);
					var_dump($block); // TODO: remove
					$chunk->setBlock($X, $y, $Z, $block->getId(), $block->getDamage());
				}
				$type = $shape[($Z << 4) | $X];
				if($type === self::ROAD) {
					$chunk->setBlock($X, $groundHeight, $Z, Block::AIR, 0);
				}else{
					$chunk->setBlock($X, $groundHeight, $Z, Block::AIR, 0);
				}
			}
		}
		$chunk->setX($chunkX);
		$chunk->setZ($chunkZ);
		$chunk->setGenerated();
		$this->level->setChunk($chunkX, $chunkZ, $chunk);
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return \SplFixedArray
	 */
	public function getShape(int $x, int $z) {
		$totalSize = $this->length;
		if($x >= 0) {
			$X = $x % $totalSize;
		}else{
			$X = $totalSize - abs($x % $totalSize);
		}
		if($z >= 0) {
			$Z = $z % $totalSize;
		}else{
			$Z = $totalSize - abs($z % $totalSize);
		}
		$startX = $X;
		$shape = new \SplFixedArray(256);
		for($z = 0; $z < 16; $z++, $Z++) {
			if($Z === $totalSize) {
				$Z = 0;
			}
			if($Z < $this->length) {
				$typeZ = self::PLOT;
			}elseif($Z === $this->length or $Z === ($totalSize - 1)) {
				$typeZ = self::WALL;
			}else{
				$typeZ = self::ROAD;
			}
			for($x = 0, $X = $startX; $x < 16; $x++, $X++) {
				if($X === $totalSize) {
					$X = 0;
				}
				if($X < $this->length) {
					$typeX = self::PLOT;
				}elseif($X === $this->length or $X === ($totalSize - 1)) {
					$typeX = self::WALL;
				}else{
					$typeX = self::ROAD;
				}
				if($typeX === $typeZ) {
					$type = $typeX;
				}elseif($typeX === self::PLOT) {
					$type = $typeZ;
				}elseif($typeZ === self::PLOT) {
					$type = $typeX;
				}else{
					$type = self::ROAD;
				}
				$shape[($z << 4) | $x] = $type;
			}
		}
		return $shape;
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
		return new Vector3(0, 65, 0); // TODO: adjust according to schematic
	}
}