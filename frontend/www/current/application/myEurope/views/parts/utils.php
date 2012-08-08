<?php 



$themeedu = array("education", "jeunesse");
$themeemp = array("emploi", "social");
$themeent = array("entreprise", "innovation");
$themeenv = array("environnement", "énergie", "transport");
$themeagr = array("agriculture", "rural");
$themepec = array("pêche", "nature");
$themecoh = array("social", "transfontalier", "interreg");
$themerec = array("recherche", "science");
$themesan = array("santé", "consommation");


/**
 *  Generates a navbar of tabs with appropriate transitions (left / right).
 *  $tabs : An array of "<tabId>" => "<Label>"
 *          Where tabId is the id of the page = The id of the div with data-role="page"
 *  $ActiveTab : Current active tab id 
 *  These tabs should be repeated in the header of each tabbed page
 */
function tabs($tabs, $activeTab) {
	
	$reverse = true;
	?> 	
	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
	<ul>
		<li><a href="?action=logout" rel="external" data-icon="back"><?= _("Exit") ?></a></li>
		<? foreach ($tabs as $id => $label) { ?>
		<li>
			<a 
				href="#<?= $id ?>"    
				data-transition="slide" 
				<?= ($reverse) ? 'data-direction="reverse"' : '' ?>
				<?= ($activeTab == $id) ? 'class="ui-btn-active ui-state-persist"' : '' ?> >
				<?= $label ?>
			</a>
		</li><? 
		
		if ($id == $activeTab) {
			$reverse = false;
		}
	}

	?> 
	</ul>
	</div> <?
 }
 
 function tabs_white($tabs, $activeTab) {
 
 	$reverse = true;
 	?>
  	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
  	<ul>
  		<li><a href="/application/myMed" rel="external" data-icon="back"><?= _("Exit") ?></a></li>
  		<li><a href="#shareThis" id="shareThisButton" data-icon="thumb" data-inline="true" data-rel="popup" data-position-to="origin"><?= _("Share") ?></a></li>
  		<? foreach ($tabs as $id => $v) { ?>
  		<li>
  			<a 
  				href="#<?= $id ?>"  
  				data-transition="slide" 
  				data-icon="<?= $v[1] ?>" 
  				<?= ($reverse) ? 'data-direction="reverse"' : '' ?>
  				<?= ($activeTab == $id) ? 'class="ui-btn-active ui-state-persist"' : '' ?> >
  				<?= $v[0] ?>
  			</a>
  		</li><? 
  		
  		if ($id == $activeTab) {
  			$reverse = false;
  		}
  	}
  
  	?> 
  	</ul>
  	
  	</div>
  	<div data-role="popup" id="shareThis" class="ui-content" data-overlay-theme="b" data-theme="d" style="padding:5px;">
		<a href="#" data-rel="back" data-role="button" data-theme="d" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<span class='st_googleplus' displayText='Google +'></span>
		<span class='st_sharethis' displayText='ShareThis'></span>
		<span class='st_facebook' displayText='Facebook'></span>
		<span class='st_twitter' displayText='Tweet'></span>
		<span class='st_linkedin' displayText='LinkedIn'></span>
		<span class='st_email' displayText='Email'></span>
	</div> <?
   } 
 
 function tabs_white_back($tabs, $activeTab) {
 
 	$reverse = true;
 	?>
 	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
 	<ul>
 		<li><a href="./" rel="external" data-icon="back"><?= APPLICATION_NAME ?></a></li>
 		<? foreach ($tabs as $id => $v) { ?>
 		<li>
 			<a 
 				href="#<?= $id ?>"    
 				data-transition="slide" 
 				data-icon="<?= $v[1] ?>" 
 				<?= ($reverse) ? 'data-direction="reverse"' : '' ?>
 				<?= ($activeTab == $id) ? 'class="ui-btn-active ui-state-persist"' : '' ?> >
 				<?= $v[0] ?>
 			</a>
 		</li><? 
 		
 		if ($id == $activeTab) {
 			$reverse = false;
 		}
 	}
 
 	?> 
 	</ul>
 	</div> <?
  } 
  
  function tabs_3(
  		$title,
  		$button,
  		$url = "?action=extendedProfile",
  		$icon = "profile") {
  
  	?>
   	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
   	<ul>
   		<li><a data-rel="back" data-icon="back"><?= _("Back") ?></a></li>
   		<li><a class="ui-disabled"><?= $title ?></a></li>
   		<li><a href="<?= $url ?>" rel="external" data-icon="<?= $icon ?>"><?= $button ?></a></li>
   	</ul>
   	</div> <?
   }
   
   function tabs_3empty(
   		$title,
   		$button = APPLICATION_NAME,
   		$url = "./",
   		$icon = APPLICATION_NAME) {
   
   	?>
   		<div data-role="navbar" data-theme="d" data-iconpos="left"> 
        	<ul>
            	<li><a data-rel="back" data-icon="back"><?= _("Back") ?></a></li>
            	<li><a href="<?= $url ?>" rel="external" data-icon="<?= $icon ?>"><?= $button ?></a></li>
            	<li><a class="ui-disabled"><?= $title ?></a></li>
            </ul>
        </div> <?
      }
   
   function tabs_2(
   		$title,
   		$url = "",
  		$icon = "") {
   	?>
      	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
      	<ul>
      		<li><a data-rel="back" data-icon="back"><?= _("Back") ?></a></li>
      		<li><a href="<?= $url ?>" rel="external" data-icon="<?= $icon ?>" class="ui-disabled"><?= $title ?></a></li>
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
            		<li><a href="./" rel="external" data-icon="back"><?= _("Back") ?></a></li>
            		<li><a onclick="<?= $url ?>" rel="external" data-icon="<?= $icon ?>"><?= $title ?></a></li>
            	</ul>
            	</div> <?
            }
      
      function tabs_2empty($title) {
      	?>
            	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
            	<ul>
            		<li><a data-rel="back" data-icon="back"><?= _("Back") ?></a></li>
            		<li><a class="ui-disabled"><?= $title ?></a></li>
            	</ul>
            	</div> <?
        }
            
            
       function about(){
       	?>
<div style="text-align: justify;margin:20px;">
	<b>MyEurope</b> est un réseau social s'appuyant sur le méta-réseau social myMed, à disposition des mairies, des institutions, des élus ou encore des
	réalités économiques (industrielles, touristiques…) du territoire de la région française du Sud-Est (PACA, Rhône-Alpes) et des trois régions
	italiennes du Nord Ouest de l'Italie, (Ligurie, Piémont, Vallée d'Aoste), c'est-à-dire des territoires éligibles au programme Alcotra (Alpes Latines
	Coopération Transfrontalière France-Italie). L'application permettra aux mairies de l'Eurorégion Alpes-Méditerranée (MedAlp) de trouver des
	partenaires, bien évidemment inscrits au réseau myEurope, au sein de l'interregion afin de pouvoir monter ensemble des projets communs, dans le cadre
	de différents programmes européens : Interreg Alcotra, de Med, FP7 etc … <br /> <br /> Les objectifs principaux de myEurope sont donc les suivants :

<ul>
	<li>Aider, à travers le mécanisme de « Matchmaking » de myMed, à mettre en communs des idées mais aussi des moyens afin de pouvoir soumettre un
		projet Interreg Alcotra, Med ou d'obtenir des financements provenant de l'Europe (crédits FEDER …).</li>
	<li>Echanger des pratiques et des intérêts communs transfrontaliers dans le domaine du montage de projet</li>
	<li>Informer des différents appels d'offres de projets européens les personnes inscrites au réseau social</li>
</ul>
<br />
Ces échanges d'information seront utiles aux élus français ainsi qu'à leurs homologues italiens afin de pouvoir instaurer un contact permanent entre
les habitants des régions françaises et ceux des régions italiennes limitrophes. Ce qui favorisera l'organisation d'activités transfrontalières
(partenariat de projets européens, coopérations dans différents domaines …).
</div>
<img src="img/logos" style="max-width:460px;"/>
<?
       }
       
       
	function printProfile($profile, $id="", $user="", $isAuthor=true){
       	switch($profile->role){
       		
       		default:
       			?>

				<ul data-role="listview" data-inset="true" data-theme="d" style="margin-top: 2px;">
					<li>
						<h2>
							<?= $profile->name ?> <?=$isAuthor ? "<span style='font-weight: lighter;'>"._("(partnership creator)")."</span>" : "" ?> <span style="font-style: italic;color: #36C;">+<?= $profile->reputation['up'] ?></span> 
						</h2>
						<p>
							<?= _("role") ?>: <strong style="color:#444;"><?= $profile->role ?></strong>
						</p>
						<p>
							<strong style="color:#444;"><?= $profile->activity ?></strong>
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
							<a href="" data-role="button" data-theme="d" data-mini="true" data-inline="true" style="font-style: italic; color: #36C;"
							onclick="rate(1, '<?= $id ?>', '<?= $user ?>');">+1</a>
						</p> 
					</li>
				</ul>
				
				<?
				break;
		}
       	 
	}
	
	
	function printMyProfile($profile, $rep = 0){
		switch($profile->role){
			 
			default:
				?>
	
					<ul data-role="listview" data-inset="true" data-theme="d">
						<li>
							<h3>
								<?= $profile->name ?> <span style="font-style: italic;color: #36C;">+<?= $profile->reputation['up'] ?></span>
							</h3>
							<p>
								<strong style="color:#444;"><?= $profile->activity ?></strong>
							</p>
							<br />
							<p style="margin-right: 40px;">
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
							<p>
								<?= _('Active partnerships') ?>:
							</p>
							<p style="position:absolute; right: 1px; top: 40px;">
								<a type="button" data-mini="true" href="?action=ExtendedProfile&edit=false" data-inline="true" data-theme="d" data-icon="grid"><?= _('edit') ?></a>
							</p>
							<p class="ui-li-aside">
								<?= _("role") ?>: <strong style="color:#444;"><?= $profile->role ?></strong>
							</p>
						</li>
					</ul>
					
					<?
					break;
			}
	       	 
		}

       ?>