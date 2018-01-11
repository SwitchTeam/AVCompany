<?php

namespace AVENDA\commands;

use pocketmine\command\CommandSender;
use AVENDA\AVCompany;

class CreateCompanyCommand {
	public $owner;
	public function __construct(AVCompany $owner) {
		$this->owner = $owner;
	}
	public function onCommand(CommandSender $player, string $label, array $args): bool {
		$name = $player->getName ();
		if (! isset ( $args [0] )) {
			$this->msg ( $player, "/회사 생성 [회사이름] - 회사를 생성합니다." );
			return true;
		}
		if (VultrM::getInstance ()->mymoney ( $player ) > $this->owner->sDB ["create-company-price"]) {
			if (! isset ( $this->owner->dDB [$name] )) {
				$cname = $args [1];
				for($i = 1; $i <= 9999; $i ++) {
					if (! isset ( $this->owner->cDB ["companys"] [$i] )) {
						$id = $i;
						$this->owner->cDB [$name] = $id . ":Owner";
						$this->owner->cDB ["companys"] [$id] = [ 
								"Company" => $cname,
								"Owner" => $name,
								"Worker" => [ ],
								"Max-Worker" => 5,
								"Stock" => 0,
								"Stock-price" => 10000 
						];
					}
				}
				$this->owner->msg ( $player, "회사를 설립하셨습니다. 회사이름 : " . $cname . "\n더 자세한 정보는 /회사 내정보 를 입력해주세요." );
			} else {
				$this->owner->msg ( $player, "당신은 이미 회사가 있습니다." );
			}
		} else {
			$this->owner->msg ( $player, "회사를 설립할 비용이 부족합니다. 필요비용 : " . $this->owner->sDB ["create-company-price"] );
		}
	}
}