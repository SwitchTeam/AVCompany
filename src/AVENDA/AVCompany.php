<?php

namespace AVENDA;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\Player;
use AVENDA\tasks\ChangeStockPrice;
use AVENDA\commands\AccountCommand;
use AVENDA\commands\AddStockCommand;
use AVENDA\commands\AddWorkerCommand;
use AVENDA\commands\ChangeMoneyCommand;
use AVENDA\commands\CompanyMoneyCommand;
use AVENDA\commands\CreateCompanyCommand;
use AVENDA\commands\InfoCommand;
use AVENDA\commands\InvitationCommand;
use AVENDA\commands\InvitationListCommand;
use AVENDA\commands\KickCommand;
use AVENDA\commands\OutCommand;
use AVENDA\commands\RefuseCommand;
use AVENDA\commands\TopCompanyCommand;

class AVCompany extends PluginBase implements Listener {
	public $company, $cDB;
	public $setting, $sDB;
	public $data, $dDB;
	public $tag = "§c§l[§fAVCompany§c]§f";
	public function onEnable() {
		@mkdir ( $this->getDataFolder () );
		$this->setting = new Config ( $this->getDataFolder () . "setting.yml", Config::YAML, [ 
				"create-company-price" => 200000,
				"add-player-price" => 100000,
				"add-player-count" => 3 
		] );
		$this->sDB = $this->setting->getAll ();
		$this->company = new Config ( $this->getDataFolder () . "companys.yml", Config::YAML );
		$this->cDB = $this->company->getAll ();
		$this->data = new Config ( $this->getDataFolder () . "data.yml", Config::YAML );
		$this->dDB = $this->data->getAll ();
		$this->getServer ()->getScheduler ()->scheduleRepeatingTask ( new ChangeStockPrice ( $this ), 20 * 60 );
		$this->commands ();
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
	}
	public function commands() {
		$this->AccountCommand = new AccountCommand ( $this );
		$this->AddStockCommand = new AddStockCommand ( $this );
		$this->AddWorkerCommand = new AddWorkerCommand ( $this );
		$this->ChangeMoneyCommand = new ChangeMoneyCommand ( $this );
		$this->CompanyMoneyCommand = new CompanyMoneyCommand ( $this );
		$this->CreateCompanyCommand = new CreateCompanyCommand ( $this );
		$this->InfoCommand = new InfoCommand ( $this );
		$this->InvitationCommand = new InvitationCommand ( $this );
		$this->InvitationListCommand = new InvitationListCommand ( $this );
		$this->KickCommand = new KickCommand ( $this );
		$this->OutCommand = new OutCommand ( $this );
		$this->RefuseCommand = new RefuseCommand ( $this );
		$this->TopCompanyCommand = new TopCompanyCommand ( $this );
	}
	public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool {
		$cmd = $command->getName ();
		if ($cmd == "회사") {
		}
		return true;
	}
	public function Join(PlayerJoinEvent $event) {
		$player = $event->getPlayer ();
		$name = $player->getName ();
		$this->dDB [$name] ["invitation"] = [ ];
		$this->save ();
	}
	public function help(Player $player) {
		$this->msg ( $player, "/회사 생성 [회사이름] - 회사를 생성합니다." );
		$this->msg ( $player, "/회사 초대 [닉네임] - [닉네임]에게 초대 메세지를 보냅니다." );
		$this->msg ( $player, "/회사 초대목록 [페이지] - 나에게 회사 초대를 보낸 회사 들을 봅니다." );
		$this->msg ( $player, "/회사 초대수락 [번호] - 해당번호의 회사 초대를 수락 합니다." );
		$this->msg ( $player, "/회사 초대거절 [번호] - 해당번호의 회사 초대를 거절 합니다." );
		$this->msg ( $player, "/회사 내정보 - 나의 대한 회사 정보를 봅니다." );
		$this->msg ( $player, "/회사 퇴출 [닉네임] - [닉네임]의 플레이어를 퇴출 시킵니다." );
		$this->msg ( $player, "/회사 나가기 - 회사에서 나갑니다." );
	}
	public function help2(Player $player) {
		$this->msg ( $player, "/회사 순위 [페이지] - 회사 순위를 봅니다." );
		$this->msg ( $player, "/회사 주식발행 [갯수] - 회사의 주식을 발행합니다." );
		$this->msg ( $player, "/회사 주식구매 [번호] [갯수]" );
		$this->msg ( $player, "/회사 주식판매 [번호] [갯수]" );
		$this->msg ( $player, "/회사 자금넣기 [금액] - 회사의 자금을 [금액]만큼 넣습니다." );
		$this->msg ( $player, "/회사 자금 - 회사의 자금을 확인합니다." );
		$this->msg ( $player, "/회사 인원추가 - {$this->sDB ["add-player-price"]}원을 소비하고 회사에 영입할 수 있는 사람수 {$this->sDB ["add-player-count"]}명을 늘립니다." );
	}
	public function getCompanyId(Player $player) {
		$name = strtolower ( $player->getName () );
		$ci = explode ( ":", $this->cDB [$name] );
		return $ci [0];
	}
	public function getCompanyRank(Player $player) {
		$name = strtolower ( $player->getName () );
		$ci = explode ( ":", $this->cDB [$name] );
		return $ci [1];
	}
	public function getCompanyName($id) {
		return $this->cDB ["Companys"] [$id] ["Company"];
	}
	public function getCompanyOwner($id) {
		return $this->cDB ["Companys"] [$id] ["Owner"];
	}
	public function isCompanyWorker($id, $player) {
		if (isset ( $this->cDB ["Companys"] [$id] ["Worker"] [$player] ))
			return true;
		else
			return false;
	}
	public function isHaveCompany($player) {
		if (isset ( $this->cDB [$player] ))
			return true;
		else
			return false;
	}
	public function getCompanyMoney($id) {
		return $this->cDB ["Companys"] [$id] ["money"];
	}
	public function getMaxCompanyMember($id) {
		return $this->cDB ["Companys"] [$id] ["Max-Worker"];
	}
	public function getAllCompanyMember($id) {
		$worker = count ( $this->cDB ["Companys"] [$id] ["Worker"] );
		$owner = count ( $this->cDB ["Companys"] [$id] ["Owner"] );
		return $worker + $owner;
	}
	public function getCompanyStock($id) {
		return $this->cDB ["Companys"] [$id] ["Stock"];
	}
	public function pushInvitation($player, $cname) {
		array_push ( $this->dDB [$player] ["invatation"], $cname );
		$this->save ();
	}
	public function getAllInvitation($player) {
		foreach ( $this->dDB [$player] ["invatation"] as $v => $cname )
			return "[{$v}번] 회사이름 : " . $cname;
	}
	public function changeStockPrice($id) {
		$this->cDB ["Companys"] [$id] ["Stock-price"] += mt_rand ( - 9999, 10000 );
		$this->save ();
	}
	public function msg(Player $player, $msg) {
		return $player->sendMessage ( $this->tag . " " . $msg );
	}
	public function save() {
		$this->setting->setAll ( $this->sDB );
		$this->setting->save ();
		$this->company->setAll ( $this->cDB );
		$this->company->save ();
		$this->data->setAll ( $this->dDB );
		$this->data->save ();
	}
}