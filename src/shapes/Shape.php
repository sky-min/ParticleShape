<?php

declare(strict_types=1);

namespace skymin\ParticleShape\shapes;

use Generator;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;

interface Shape{

	/** @return Generator<Vector2|Vector3> */
	public function getPositions() : Generator;
}