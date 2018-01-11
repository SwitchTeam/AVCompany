<?php

namespace AVENDA\tasks;

use pocketmine\scheduler\PluginTask;
use AVENDA\AVCompany;

class ChangeStockPrice extends PluginTask {
	protected $owner;
	public function __construct(AVCompany $owner) {
		$this->owner = $owner;
	}
	public function onRun($currentTick) {
		foreach ( $this->owner->getServer ()->getOnlinePlayers () as $player ) {
			$id = $this->owner->getCompanyId ( $player );
			$this->owner->changeStockPrice ( $id );
		}
	}
}