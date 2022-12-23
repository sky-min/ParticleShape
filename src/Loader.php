<?php

declare(strict_types=1);

namespace skymin\ParticleShape;

use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\plugin\PluginBase;
use skymin\ImageParticle\particle\CustomParticle;
use skymin\ImageParticle\particle\EulerAngle;
use skymin\ParticleShape\shapes\Ellipse;
use skymin\ParticleShape\shapes\Regular;
use skymin\ParticleShape\shapes\Spiral;
use skymin\ParticleShape\shapes\Star;

final class Loader extends PluginBase{

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvent(PlayerItemUseEvent::class, function(PlayerItemUseEvent $ev) : void{
			$pos = $ev->getPlayer()->getLocation();
			DrawParticle::draw(
				EulerAngle::fromObject($pos, $pos->getWorld()),
				(new CustomParticle())->setColor(mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)),
				new Ellipse(6, 3)
			);
		}, EventPriority::MONITOR, $this);
	}
}
