<?php

namespace AVENDA\commands;

use pocketmine\command\CommandSender;
use AVENDA\AVCompany;
use pocketmine\Player;

class InvitationCommand {
	public $owner;
	public function __construct(AVCompany $owner) {
		$this->owner = $owner;
	}
	public function onCommand(CommandSender $player, string $label, array $args): bool {
		$name = $player->getName ();
		if (isset ( $this->owner->cDB [$name] )) {
			$target = $this->owner->getServer ()->getPlayer ( $args [1] );
			if ($target instanceof Player) {
				if (! isset ( $this->owner->dDB [$target] [$name] )) {
					if (! isset ( $this->owner->dDB ( $target ) )) {
						$rank = $this->owner->getCompanyRank ( $player );
						$id = $this->owner->getCompanyId ( $player );
						if ($rank == "Owner") {
							$count = count ( $this->owner->getAllCompanyMember ( $id ) );
							if ($count <= $this->owner->getMaxCompanyMember ( $id )) {
								$this->owner->pushInvitation ( $target, $this->owner->getCompanyName ( $id ) );
								$this->owner->msg ( $player, "{$target}에게 초대 메세지를 보냈습니다." );
								$this->owner->msg ( $target, "회사 초대를 받았습니다. /회사 초대목록 으로 확인 해주세요." );
							} else {
								$this->owner->msg ( $player, "최대 인원을 초과합니다." );
							}
						} else {
							$this->owner->msg ( $player, "당신은 회사의 주인이 아니기 때문에 초대를 할 수 없습니다." );
						}
					} else {
						$this->owner->msg ( $player, "그 플레이어는 이미 회사가 있습니다." );
					}
				} else {
					$this->owner->msg ( $player, "그 플레이어에게 이미 초대를 보낸 기록이 있습니다." );
				}
			} else {
				$this->owner->msg ( $player, "그 플레이어는 접속중이 아닙니다." );
			}
		} else {
			$this->owner->msg ( $player, "당신은 회사가 없습니다." );
		}
	}
}