<?php

declare(strict_types=1);

namespace skymin\ParticleShape;

use pocketmine\plugin\PluginBase;
use skymin\ImageParticle\particle\CustomParticle;
use skymin\ImageParticle\particle\EulerAngle;
use function class_exists;

final class Loader extends PluginBase{

	protected function onEnable() : void{
		if(!class_exists(EulerAngle::class) || !class_exists(CustomParticle::class)){
			$this->getLogger()->critical('This plugin is required ImageToParticle.');
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}
}
