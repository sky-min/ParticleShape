<?php

declare(strict_types=1);

namespace skymin\ParticleShape\shapes;

use Generator;
use pocketmine\math\Vector2;
use skymin\ParticleShape\utils\Utils;
use function cos;
use function deg2rad;
use function sin;

class Ellipse implements Shape{

	public function __construct(
		private readonly float $x_radius,
		private readonly float $y_radius,
		private readonly float $startTheta = 0,
		private readonly float $stopTheta = 360,
		private readonly int $particleCount = 360
	){}

	/** @return  Generator<Vector2> */
	public final function getPositions() : Generator{
		foreach(Utils::linspaceForGenerator($this->startTheta, $this->stopTheta, $this->particleCount) as $t){
			$rad = deg2rad($t);
			yield new Vector2(
				$this->x_radius * cos($rad),
				$this->y_radius * sin($rad)
			);
		}
	}
}