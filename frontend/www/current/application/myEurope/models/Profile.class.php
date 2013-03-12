<?php

require_once('Entry.class.php');

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
	public function renderProfile($user = false, $fromDetailView=false){
		$lang="";
		if($_SESSION['user']->lang=="en") $lang=_("English");
		else if($_SESSION['user']->lang=="it") $lang=_("Italian");
		else $lang=_("French");
		?>
		<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d" style="margin-top: 2px;">
			<? if($fromDetailView==false){ ?>
				<li data-role="list-divider"><?= _("User details") ?></li>	
				<li>
					<div class="ui-grid-a" style="margin-top: 7px;margin-bottom:7px">	
						<div class="ui-block-a" style="width: 110px;">
							<a title="<?= $_SESSION['user']->name ?>"><img src="<?= $_SESSION['user']->profilePicture ?>"style="width: 80px;vertical-align: middle;padding-right: 10px;"></a>
						</div>
						<div class="ui-block-b">
							<p><strong><?= $_SESSION['user']->firstName." ".$_SESSION['user']->lastName ?></strong></p>
							<p><?= $_SESSION['user']->birthday ?> </p>
							<p><?= $lang?></p>
							<p><a href="mailto:<?= prettyprintId($_SESSION['user']->id) ?>"><?= prettyprintId($_SESSION['user']->id) ?></a></p>
						</div>
					</div>
				</li>
			<? } ?>
			<li data-role="list-divider"><?= _("Organization details") ?></li>	
			<li>
				<h2>
					<?= $this->details['name'] ?>
				</h2>
				<p>
					<?= _("Role") ?>: <strong style="color:#444;"><?= Categories::$roles[$this->details['role']] ?></strong>
				</p>
				<p>
					<?= (empty($this->details['activity'])?" ": _("Action area").": <strong>".$this->details['activity'])."</strong>"?>
				</p>
				<p>
					<? echo _("Territory type").": <strong>"._($this->details['area'])."</strong>" ?>
				</p>
				<? echo "<p>". _("Action territory").":<br/><p style='margin-left:20px'>";
					$tokens = explode("|", $this->details['territoryType']);
					
					foreach($tokens as $token) {
						echo "<strong>".Categories::$territorytype[$token]."</strong><br/>";
					}
				?>
				<br />
				<p>
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
				<br />
					
			</li>
			<!-- 
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
			-->
		</ul>
		<?
	}
	
}




?>