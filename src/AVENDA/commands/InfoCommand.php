<?php

namespace AVENDA\commands;

use pocketmine\command\CommandSender;
use AVENDA\AVCompany;

class InfoCommand {
	public $owner;
	public function __construct(AVCompany $owner) {
		$this->owner = $owner;
	}
	public function onCommand(CommandSender $player, string $label, array $args): bool {
		$id = $this->owner->getCompanyId ( $player );
		if (! isset ( $id )) {
			$this->owner->msg ( $player, "당신은 회사가 없습니다." );
			return true;
		}
		$this->owner->getCompanyName ( $id );
		$player->sendMessage ( "=====[{$id}정보]=====" );
		$player->sendMessage ( "회장 : " . $this->owner->getCompanyOwner ( $id ) );
		$player->sendMessage ( "인원 : " . $this->owner->getAllCompanyMember ( $id ) . "/" . $this->owner->getMaxCompanyMember ( $id ) );
		$player->sendMessage ( "자금 : " . $this->owner->getCompanyMoney ( $id ) );
		$player->sendMessage ( "주식 : " . $this->owner->getCompanyStock ( $id ) );
	}
}