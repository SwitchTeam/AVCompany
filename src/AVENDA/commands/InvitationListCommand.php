<?php

namespace AVENDA\commands;

use pocketmine\command\CommandSender;
use AVENDA\AVCompany;

class InvitationListCommand {
	public $owner;
	public function __construct(AVCompany $owner) {
		$this->owner = $owner;
	}
	public function onCommand(CommandSender $player, string $label, array $args): bool {
		if (! isset ( $args [1] ) or ! is_numeric ( $args [1] )) {
			$player->sendMessage ( "§f§l======[초대 리스트]======" );
			$player->sendMessage ( $this->owner->getAllInvitation ( $player ) );
			return true;
		}
		$player->sendMessage ( "§f§l======[초대 리스트]======" );
		$player->sendMessage ( $this->owner->getAllInvitation ( $player ) );
	}
}