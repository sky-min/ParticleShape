<?php

declare(strict_types=1);

namespace skymin\ParticleShape\shapes;

use Generator;
use pocketmine\math\Vector3;
use skymin\ParticleShape\utils\Utils;

final class Straight implements Shape{

	public function __construct(
		private readonly Vector3 $start,
		private readonly Vector3 $stop,
		private readonly int $count
	){}

	/** @return Generator<Vector3> */
	public function getPositions() : Generator{
		$start_x = $this->start->getX();
		$start_y = $this->start->getY();
		$start_z = $this->start->getZ();
		$x = $start_x - $this->stop->getX();
		$y = $start_y - $this->stop->getY();
		$z = $start_z - $this->stop->getZ();
		$xz_sq = $x * $x + $z * $z;
		$xz_modulus = sqrt($xz_sq);
		$yaw = atan2(-$x, $z);
		$pitch = -atan2($y, $xz_modulus);
		$distance = $this->start->distance($this->stop);
		foreach(Utils::linspaceForGenerator(0, $distance, $this->count) as $n){
			yield new Vector3(
				$start_x - $n * (-sin($yaw)),
				$start_y - $n * (-sin($pitch)),
				$start_z - $n * (cos($yaw))
			);
		}
	}

	public function getStart() : Vector3{
		return $this->start;
	}

	public function getStop() : Vector3{
		return $this->stop;
	}
}