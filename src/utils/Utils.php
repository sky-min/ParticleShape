<?php

declare(strict_types=1);

namespace skymin\ParticleShape\utils;

use Generator;

final class Utils{

	/** @return float[] */
	public static function linspace(float $start, float $stop, int $num) : array{
		$result = [];
		$step = ($stop - $start) / ($num - 1);
		if($start === $stop){
			throw new \InvalidArgumentException('start and stop values cannot be the same.');
		}elseif($start < $stop){
			for(; $stop > $start; $start += $step){
				$result[] = $start;
			}
		}else{
			for(; $stop < $start; $start += $step){
				$result[] = $start;
			}
		}
		return $result;
	}

	/** @return Generator<float> */
	public static function linspaceForGenerator(float $start, float $stop, int $num) : Generator{
		$step = ($stop - $start) / ($num - 1);
		if($start === $stop){
			throw new \InvalidArgumentException('start and stop values cannot be the same.');
		}elseif($start <= $stop){
			for(; $stop > $start; $start += $step){
				yield $start;
			}
		}else{
			for(; $stop <= $start; $start += $step){
				yield $start;
			}
		}
	}
}