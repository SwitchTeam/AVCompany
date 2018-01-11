<?php

namespace AVENDA\commands;

use pocketmine\command\CommandSender;
use AVENDA\AVCompany;

class RefuseCommand {
	public $owner;
	public function __construct(AVCompany $owner) {
		$this->owner = $owner;
	}
	public function onCommand(CommandSender $player, string $label, array $args): bool {
		if (! isset ( $this->owner->dDB [$player] ["invatation"] [$args [1]] )) {
			$this->owner->msg ( $player, "그번호의 회사는 존재하지 않습니다." );
			return true;
		}
		unset ( $this->owner->dDB [$player] ["invatation"] [$args [1]] );
		$this->owner->save ();
		$this->owner->msg ( $player, "해당번호의 회사의 초대를 거절 하였습니다." );
	}
}