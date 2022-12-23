<?php

declare(strict_types=1);

namespace skymin\ParticleShape\shapes;

use Generator;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use function cos;
use function deg2rad;
use function sin;

final class Regular implements Shape{

	public function __construct(
		private readonly float $radius,
		private readonly int $side,
		private readonly int $oneSideParticleCount
	){}

	/** @return Generator<Vector2> */
	public function getPositions() : Generator{
		$ang = 180 * ($this->side - 2);
		$round = 180 - ($ang / $this->side);
		$pos1 = new Vector3(
			$this->radius * -sin(0),
			0,
			$this->radius * cos(0)
		);
		for($i = 0; $i < 360; $i += $round){
			if($i === 0) continue;
			$rad = deg2rad($i);
			$pos2 = new Vector3(
				$this->radius * -sin($rad),
				0,
				$this->radius * cos($rad)
			);
			foreach((new Straight($pos1, $pos2, $this->oneSideParticleCount))->getPositions() as $p){
				yield new Vector2($p->getX(), $p->getZ());
			}
			$pos1 = $pos2;
		}
	}
}