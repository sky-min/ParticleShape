<?php

declare(strict_types=1);

namespace skymin\ParticleShape\shapes;

use Generator;
use pocketmine\math\Vector2;
use skymin\ParticleShape\utils\Utils;
use function cos;
use function deg2rad;
use function sin;

final class Spiral implements Shape{

	public function __construct(
		private readonly float $radius,
		private readonly float $theta,
		private readonly float $cutRadius,
		private readonly int $particleCount = 360
	){}

	/**
	 * @return Generator<Vector2>
	 */
	public function getPositions() : Generator{
		$thetas = Utils::linspace(0, $this->theta, $this->particleCount);
		foreach(Utils::linspace($this->cutRadius, $this->radius, $this->particleCount) as $key => $r){
			if(!isset($thetas[$key])) break;
			$rad = deg2rad($thetas[$key]);
			yield new Vector2(
				x: $r * cos($rad),
				y: $r * sin($rad)
			);
		}
	}
}