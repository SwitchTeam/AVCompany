<?php

namespace AVENDA\commands;

use pocketmine\command\CommandSender;
use AVENDA\AVCompany;

class OutCommand {
	public $owner;
	public function __construct(AVCompany $owner) {
		$this->owner = $owner;
	}
	public function onCommand(CommandSender $player, string $label, array $args): bool {
	}
}