<?php

declare(strict_types=1);

namespace skymin\ParticleShape\shapes;

use Generator;
use pocketmine\math\Vector2;
use skymin\ParticleShape\utils\Utils;
use function cos;
use function deg2rad;
use function sin;

final class Circle implements Shape{

	/** @param float $theta <= 360 */
	public function __construct(
		private readonly float $radius,
		private readonly float $theta = 360,
		private readonly int $count = 360
	){}

	/** @return Generator<Vector2> */
	public function getPositions() : Generator{
		foreach(Utils::linspaceForGenerator(0, $this->theta, $this->count) as $t){
			$rad = deg2rad($t);
			yield new Vector2(
				x: $this->radius * cos($rad),
				y: $this->radius * sin($rad)
			);
		}
	}
}