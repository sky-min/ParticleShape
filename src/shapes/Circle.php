<?php

declare(strict_types=1);

namespace skymin\ParticleShape\shapes;

final class Circle extends Ellipse{

	/** @param float $theta <= 360 */
	public function __construct(
		float $radius,
		float $theta = 360,
		readonly int $particleCount = 360
	){
		parent::__construct($radius, $radius, 0, $theta, $this->particleCount);
	}
}