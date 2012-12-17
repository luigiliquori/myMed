<?php

require_once('Entry.php');

class Profile extends Entry{

	public $users; //members
	public $partnerships; //partnerships owned or joined
	
	public $details; // all profile data
	
	public function __construct(
			$id = null,
			$data = null,
			$metadata = null,
			$index = array()) {
		parent::__construct("profiles", $id, $data, $metadata, $index);
	}
	
	public function parseProfile(){
		$this->users = array();
		$this->partnerships = array();
		foreach ($this->details as $k => $v){
			if (strpos($k, "user_") === 0){
				unset($this->details[$k]); // some cleaning for debug
				$this->users[] = $v;
			} else if(strpos($k, "part_") === 0){
				unset($this->details[$k]); // some cleaning for debug
				$this->partnerships[] = $v;
			}
		}
		sort($this->users);
	}
	
	
	//shouldn't be there, I'll put that in utils later
	public function renderProfile($user = false){
		?>
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d" style="margin-top: 2px;">
			<li>
				<h2>
					<?= $this->details['name'] ?>
				</h2>
				<p>
					<?= _("Role") ?>: <strong style="color:#444;"><?= $this->details['role'] ?></strong>
				</p>
				<p>
					<strong style="color:#444;"><?= (empty($this->details['activity'])?" ":$this->details['activity']) ?></strong>
				</p>
				<br />
				<p>
					<img src="./img/mail-send.png" style="height: 22px;vertical-align: bottom;"/>
					<?=
					(empty($this->details['email'])?" ": _("email").": <a href='mailto:".$this->details['email']."'>".$this->details['email']."</a>")." - ".
					(empty($this->details['phone'])?" ":_("phone").": <a href='tel:".$this->details['phone']."'>".$this->details['phone']."</a>")." - ".
					(empty($this->details['address'])?" ":_("address").": ".$this->details['address'])
					?>
				</p>
				<br />
				<p>
					<?= empty($this->details['desc'])?" ":$this->details['desc'] ?>
				</p>
				<p class="ui-li-aside">
					réputation: <a href="#reppopup" style="font-size: 16px;" title="<?= $this->reputation['up'] ?> votes +, <?= $this->reputation['down'] ?> votes -"><?= $this->reputation['up'] - $this->reputation['down'] ?></a>
				</p>
				<br />
					
			</li>
			<? if( count($this->users)>0) :?>
				<li data-role="list-divider"><?= _("Members list") ?></li>
			<? endif ?>
			<? foreach($this->users as $item) :?>
				<li><p>
					<? if($user->id == $item): ?>
						<? if(!empty($user->profilePicture )): ?>
							<a title="<?= $user->name ?>"><img src="<?= $user->profilePicture ?>" style="width: 50px;vertical-align: middle;padding-right: 10px;"></a>
						<? else: ?>
							<a href="#updatePicPopup" data-rel="popup"><img src="http://graph.facebook.com//picture?type=large" style="width: 50px;vertical-align: middle;padding-right: 10px;"></a>
						<? endif; ?>
					<? else: ?>
						<img src="http://www.gravatar.com/avatar/<?= hash("crc32b",$item) ?>?s=128&d=identicon&r=PG" style="width: 30px;vertical-align: middle;padding-right: 10px;"/>
					<? endif; ?>
					<a href="mailto:<?= prettyprintId($item) ?>"><?= prettyprintId($item) ?></a> <?= $item==$_SESSION['user']->id?_("(You)"):"" ?>
				</p></li>
			<? endforeach ?>
		</ul>
		<?
	}
	
}




?>