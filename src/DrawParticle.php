<?php

declare(strict_types=1);

namespace skymin\ParticleShape;

use Generator;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\World;
use skymin\ImageParticle\particle\CustomParticle;
use skymin\ImageParticle\particle\EulerAngle;
use skymin\ParticleShape\shapes\Shape;
use skymin\ParticleShape\shapes\Spiral;
use skymin\ParticleShape\shapes\Straight;
use function cos;
use function deg2rad;
use function json_encode;
use function sin;

final class DrawParticle{

	public static function draw(
		EulerAngle $euler,
		CustomParticle $particle,
		Shape $shape
	) : void{
		$pks = [];
		foreach(self::encode($euler, $particle, $shape) as $pk){
			$pks[] = $pk;
		}
		self::sendPackets($euler, $pks);
	}

	public static function drawStraight(World $world, CustomParticle $particle, Straight $shape) : void{
		$pks = [];
		foreach(self::encodeForStraight($particle, $shape) as $pk){
			$pks[] = $pk;
		}
		$targets = $world->getViewersForPosition($shape->getStart());

		foreach($world->getViewersForPosition($shape->getStop()) as $id => $target){
			if(!isset($targets[$id])){
				$targets[$id] = $target;
			}
		}
		Server::getInstance()->broadcastPackets($targets, $pks);
	}

	public static function drawSpiral(
		EulerAngle $eulerAngle,
		Spiral $spiral,
		CustomParticle ...$particles
	) : void{
		$pks = [];
		$a = 360 / count($particles);
		$c = 1;
		foreach($particles as $particle){
			foreach(self::encode(
				EulerAngle::fromObject($eulerAngle, null, roll: $eulerAngle->getRoll() + $c * $a),
				$particle, $spiral) as $pk
			){
				$pks[] = $pk;
			}
			$c++;
		}
		self::sendPackets($eulerAngle, $pks);
	}

	/**
	 * @return Generator<SpawnParticleEffectPacket>
	 */
	public static function encode(
		EulerAngle $euler,
		CustomParticle $particle,
		Shape $shape
	) : Generator{
		if($shape instanceof Straight){
			throw new \LogicException("Please use 'encodeForStraight' method");
		}
		$center = $euler->asVector3();
		//yaw
		$yaw = deg2rad($euler->getYaw());
		$ysin = sin($yaw);
		$ycos = cos($yaw);
		//pitch
		$pitch = deg2rad($euler->getPitch());
		$psin = sin($pitch);
		$pcos = cos($pitch);
		//roll
		$roll = deg2rad($euler->getRoll());
		$rsin = sin($roll);
		$rcos = cos($roll);
		foreach($shape->getPositions() as $shapePos){
			$x = $shapePos->getX();
			$y = $shapePos->getY();
			$dx = $y * $rsin + $x * $rcos;
			$dy = $y * $rcos - $x * $rsin;
			$dz = $dy * $psin;
			yield self::pk($center->add(
				$dz * $ysin + $dx * $ycos,
				$dy * -$pcos,
				$dz * -$ycos + $dx * $ysin
			), $particle);
		}
	}

	/**
	 * @return Generator<SpawnParticleEffectPacket>
	 */
	public static function encodeForStraight(CustomParticle $particle, Straight $shape) : Generator{
		foreach($shape->getPositions() as $shapePos){
			yield self::pk($shapePos, $particle);
		}
	}

	private static function pk(Vector3 $pos, CustomParticle $customParticle) : SpawnParticleEffectPacket{
		return SpawnParticleEffectPacket::create(
			dimensionId: DimensionIds::OVERWORLD,
			actorUniqueId: -1,
			position: $pos,
			particleName: 'skymin:custom_dust',
			molangVariablesJson: json_encode($customParticle)
		);
	}

	/** @param SpawnParticleEffectPacket[] $pks */
	private static function sendPackets(Position $position, array $pks) : void{
		Server::getInstance()->broadcastPackets($position->getWorld()->getViewersForPosition($position), $pks);
	}
}