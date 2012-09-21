<?php 

define('SEP', '<span style="opacity: 0.7; font-size: 80%;"> &gt; </span>');

function tab_bar_default($activeTab, $me) {
	tabs($activeTab, array(
			array("#home", "Partenariats", "retweet"),
			array("#infos", "About European programs", "info-sign"),
			array("#blogs", "Bonnes pratiques", "comments"),
			array("#profile", $me, "user")
	), true);
}

function tabs($activeTab, $tabs, $subtitle = APPLICATION_LABEL, $useBackTomyMed = false) {
	
	$reverse = true;
	$tabsStr = "";
	foreach ($tabs as $i){
		$tabsStr .=
		'<li><a href="'. $i[0] .'" data-transition="none" data-icon="'. $i[2].'" '.($reverse? 'data-direction="reverse"' : '')
		.($i[0][0]!='#'?'rel="external"':'')
		.($activeTab == $i[0] ? 'class="ui-btn-down-c ui-state-persist"' : '').'>'. _($i[1])
		.'</a></li>';
		if ($i[0] == $activeTab) {
			$reverse = false;
		}
	}
	?>
	<div data-role="header" data-theme="b" data-position="fixed">
  		<? if ($useBackTomyMed) { ?>
			<a href="/application/myMed" style="position: absolute; margin-top: -3px; left:5px;" data-role="button" rel="external" data-icon="fahome" data-iconpos="notext" data-theme="e">myMed</a>
		<? } else { ?>
  			<a href="?action=logout" style="position: absolute; margin-top: -3px; left:5px;" data-inline="true" rel="external" data-role="button" data-theme="r" data-icon="signout" data-iconpos="notext">Deconnexion</a>
  		<? } ?>
  		<h1>
  			<a href="./" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none; color: white;"><?= APPLICATION_NAME ?> <span style="font-size: 80%;"> Réseau social transfontalier</span></a>
  		</h1>
  		
  		<? include("social.php"); ?>
  		
  		<div data-role="header" data-theme="d" style="display: none;" class="toptab">
			<div data-role="navbar" data-role="footer" data-iconpos="bottom" >
				<ul><?= $tabsStr ?></ul>
			</div>
		</div>
  	</div>
	
	<div data-role="footer" data-theme="d" data-position="fixed" style="display: none;" class="iostab">
		<div data-role="navbar" data-role="footer" data-iconpos="top" >
			<ul><?= $tabsStr ?></ul>
		</div>
	</div>
 	<?
 }
 
 function tabs_simple($title=null, $useback=true, $action=null) {
 	?>
 	<div data-role="header" data-theme="b" data-position="fixed">
 		<? if ($useback): ?>
 			<a data-rel="back" rel="external" data-icon="arrow-left" style="max-width: 15%;"><?= _("Back") ?></a>
 		<? endif; ?>
  		<h1>
  			<a href="./" rel="external" title="<?= APPLICATION_NAME ?>" data-inline="true" style="text-decoration: none; color: white;"><?= APPLICATION_NAME ?></a>
  			<? if (!is_null($title)): ?>
  				<? foreach($title as $path): ?>
					<?= SEP ?><a href="#<?= $path ?>" style="text-decoration: none; color: white;font-size: 80%;"><?= _($path) ?></a>
				<? endforeach; ?>
			<? endif; ?>
  		</h1>
  		<? if (!is_null($action)): ?>
			<a class="ui-btn-right" href="<?= $action[0] ?>" <?= $action[0][0]!='#'?'rel="external"':'' ?> data-icon="<?= $action[2] ?>"><?= _($actionTitle[1]) ?></a>
		<? endif; ?>
  		<? include("social.php"); ?>
  	</div>
  	<?
}

   
   function tabs_2(
   		$title,
   		$url = "",
  		$icon = "") {
   	?>
      	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
      	<ul>
      		<li><a data-rel="back" data-icon="back" data-theme="d"><?= _("Back") ?></a></li>
      		<li><a href="<?= $url ?>" data-icon="<?= $icon ?>" class="ui-btn-active"><?= $title ?></a></li>
      	</ul>
      	</div> <?
      }
      
      function tabs_2click(
      		$title,
      		$url = "",
      		$icon = "") {
      	?>
            	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
            	<ul>
            		<li><a href="./" data-icon="back" data-theme="d"><?= _("Back") ?></a></li>
            		<li><a onclick="<?= $url ?>" data-theme="d" data-icon="<?= $icon ?>"><?= $title ?></a></li>
            	</ul>
            	</div> <?
            }
      
      function tabs_2empty($title, $icon="") {
      	?>
            	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
            	<ul>
            		<li><a data-rel="back" data-icon="back" rel="external"  data-theme="d"><?= _("Back") ?></a></li>
            		<li><a class="ui-btn-active" data-icon="<?= $icon ?>"><?= $title ?></a></li>
            	</ul>
            	</div> <?
        }
            
            
       function about(){
       	?>
<div style="text-align: justify;margin-left:20px;margin-right:20px;">
	<p>
	<?=
		_("<b>MyEurope</b> is a social network which is based on the meta-social network <b><em>myMed</em></b>, available for City Halls, institutions or economic realities (industrial, tourism industry...) of the French South-East areas (PACA, Rhone-Alpes) and the three Italian North-Western Regions (Liguria, Piemonte, Valle d'Aosta), i.e. the areas eligible to the Alcotra Program.")
	?>
	</p>	
	<p>
	<?=
		_("This \"sociapp\" will help the City Hall of the Alps-Mediterranean Euroregion to find partners, among those who joined the social network, in order to create projects together, within European Programs.
	The main targets of <b><em>myMed</em></b> are :
	<ul>
		<li>Help, through the mechanism of myMed's \"matchmaking\", to gather ideas and resources for European project submission or obtain European funds.</li>
		<li>Exchange practices and common cross-border interests in the area of European project creation.</li>
		<li>Inform users about different European calls.</li>
	</ul>")
	?>
	</p>
	
	<p>
	<?=
		_("These information exchanges will be useful to French elected representatives and their Italian counterparts in order to establish a permanent contact between French and Italian people. It will result in a better organization of cross-border activity.")
	?>	
	</p>
</div>

<?
       }
       
       
	function printProfile($profile){
       	switch($profile->role){
       		
       		default:
       			?>

				<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d" style="margin-top: 2px;">
					<li>
						<h2>
							<?= $profile->name ?>
						</h2>
						<p>
							<?= _("Role") ?>: <strong style="color:#444;"><?= $profile->role ?></strong>
						</p>
						<p>
							<strong style="color:#444;"><?= (empty($profile->activity)?" ":$profile->activity) ?></strong>
						</p>
						<p>
							<img src="./img/mail-send.png" style="height: 22px;vertical-align: bottom;"/>
							<?=
							(empty($profile->email)?" ": _("email").": <a href='mailto:".$profile->email."'>".$profile->email."</a>")." - ".
							(empty($profile->phone)?" ":_("phone").": <a href='tel:".$profile->phone."'>".$profile->phone."</a>")." - ".
							(empty($profile->address)?" ":_("address").": ".$profile->address)
							?>
						</p>
						<br />
						<p>
							<?= empty($profile->desc)?" ":$profile->desc ?>
						</p>
						<p class="ui-li-aside">
							réputation: <a href="#reppopup" style="font-size: 16px;" title="<?= $profile->reputation['up'] ?> votes +, <?= $profile->reputation['down'] ?> votes -"><?= $profile->reputation['up'] - $profile->reputation['down'] ?></a>
						</p>
						<br />
							
					</li>
					<? if( count($profile->users)>0) :?>
						<li data-role="list-divider"><?= _("Members list") ?></li>
					<? endif ?>
					<? foreach($profile->users as $item) :?>
						<li><p><img src="http://www.gravatar.com/avatar/<?= hash("crc32b",$item) ?>?s=128&d=identicon&r=PG" style="width: 30px;vertical-align: middle;padding-right: 10px;"/><a href="mailto:<?= prettyprintId($item) ?>"><?= prettyprintId($item) ?></a> <?= $item==$_SESSION['user']->id?_("(You)"):"" ?></p></li>
					<? endforeach ?>
				</ul>
				<?
				break;
		}
       	 
	}
	
	
	function printMyProfile($profile){
		switch($profile->role){
			 
			default:
				?>
				<ul data-role="listview" data-divider-theme="c" data-inset="true" data-theme="d">
					<li>
						<h3>
							<?= $profile->name ?>
						</h3>
						<p>
							<?= _("Role") ?>: <strong style="color:#444;"><?= $profile->role ?></strong>
						</p>
						<p>
							<strong style="color:#444;"><?= (empty($profile->activity)?" ":$profile->activity) ?></strong>
						</p>
						
						<p class="ui-li-aside">
							réputation: <a href="#reppopup" style="font-size: 16px;" title="<?= $profile->reputation['up'] ?> votes +, <?= $profile->reputation['down'] ?> votes -"><?= $profile->reputation['up'] - $profile->reputation['down'] ?></a>
						</p>
						<br />
						<p>
							<img src="./img/mail-send.png" style="height: 22px;vertical-align: bottom;"/>
							<?=
							(empty($profile->email)?" ": _("email").": <a href='mailto:".$profile->email."'>".$profile->email."</a>")." - ".
							(empty($profile->phone)?" ":_("phone").": <a href='tel:".$profile->phone."'>".$profile->phone."</a>")." - ".
							(empty($profile->address)?" ":_("address").": ".$profile->address)
							?>
						</p>
						<br />
						<p>
							<?= empty($profile->desc)?" ":$profile->desc ?>
						</p>
						<br />
					</li>
					<? if( count($profile->users)>0) :?>
						<li data-role="list-divider"><?= _("Members list") ?></li>
					<? endif ?>
					<? foreach($profile->users as $item) :?>
						<li><p><img src="http://www.gravatar.com/avatar/<?= hash("crc32b",$item) ?>?s=128&d=identicon&r=PG" style="width: 30px;vertical-align: middle;padding-right: 10px;"/><a href="mailto:<?= prettyprintId($item) ?>"><?= prettyprintId($item) ?></a> <?= $item==$_SESSION['user']->id?_("(You)"):"" ?></p></li>
					<? endforeach ?>
				</ul>
					
					<?
					break;
			}
	       	 
		}

       ?>