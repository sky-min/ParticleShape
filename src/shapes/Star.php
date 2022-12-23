<?php

declare(strict_types=1);

namespace skymin\ParticleShape\shapes;

use Generator;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;

final class Star implements Shape{

	public function __construct(
		private readonly int $angleCount,
		private readonly float $nearRadius,
		private readonly float $longRadius,
		private readonly int $oneSideParticleCount
	){}

	public function getPositions() : Generator{
		$side = $this->angleCount * 2;
		$ang = 180 * ($side - 2);
		$round = 180 - ($ang / $side);
		$pos1 = new Vector3(0, 0, 0);
		$pos2 = new Vector3(0, 0, 0);
		$n = 0;
		for($i = 0; $i <= 360; $i += $round){
			$rad = deg2rad($i);
			if($n % 2 === 0){
				$pos1 = new Vector3(
					$this->longRadius * -sin($rad),
					0,
					$this->longRadius * cos($rad)
				);
			}else{
				$pos2 = new Vector3(
					$this->nearRadius * -sin($rad),
					0,
					$this->nearRadius * cos($rad)
				);
			}
			$n++;
			if($i === 0) continue;
			foreach((new Straight($pos1, $pos2, $this->oneSideParticleCount))->getPositions() as $p){
				yield new Vector2($p->getX(), $p->getZ());
			}
		}
	}
}