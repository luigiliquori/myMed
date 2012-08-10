<?php 


function getUser($id){
	if (strpos($id, "MYMED_") === 0)
		return substr($id, 6);
	else
		return "User #".$id;
}


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
  		<li><a href="/application/myMed" data-ajax="false" data-icon="back"><?= _("Exit") ?></a></li>
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
<div style="text-align: justify;margin-left:20px;margin-right:20px;">
	<p>
		<b>MyEurope</b> est un réseau social s'appuyant sur le méta-réseau social myMed, à disposition des mairies, des institutions, des élus ou encore des
		réalités économiques (industrielles, touristiques…) du territoire de la région française du Sud-Est (PACA, Rhône-Alpes) et des trois régions
		italiennes du Nord Ouest de l'Italie, (Ligurie, Piémont, Vallée d'Aoste), c'est-à-dire des territoires éligibles au programme Alcotra (Alpes Latines
		Coopération Transfrontalière France-Italie). L'application permettra aux mairies de l'Eurorégion Alpes-Méditerranée (MedAlp) de trouver des
		partenaires, bien évidemment inscrits au réseau myEurope, au sein de l'interregion afin de pouvoir monter ensemble des projets communs, dans le cadre
		de différents programmes européens : Interreg Alcotra, de Med, FP7 etc … <br /> <br /> Les objectifs principaux de myEurope sont donc les suivants :
	</p>
	<ul>
		<li>Aider, à travers le mécanisme de « Matchmaking » de myMed, à mettre en communs des idées mais aussi des moyens afin de pouvoir soumettre un
			projet Interreg Alcotra, Med ou d'obtenir des financements provenant de l'Europe (crédits FEDER …).</li>
		<li>Echanger des pratiques et des intérêts communs transfrontaliers dans le domaine du montage de projet</li>
		<li>Informer des différents appels d'offres de projets européens les personnes inscrites au réseau social</li>
	</ul>
	</p>
	<p>
	Ces échanges d'information seront utiles aux élus français ainsi qu'à leurs homologues italiens afin de pouvoir instaurer un contact permanent entre
	les habitants des régions françaises et ceux des régions italiennes limitrophes. Ce qui favorisera l'organisation d'activités transfrontalières
	(partenariat de projets européens, coopérations dans différents domaines …).
	</p>
</div>
<?
       }
       
       
	function printProfile($profile, $id=""){
       	switch($profile->role){
       		
       		default:
       			?>

				<ul data-role="listview" data-inset="true" data-theme="d" style="margin-top: 2px;">
					<li>
						<h2>
							<?= $profile->name ?>  <a style="font-style: italic;">+<?= $profile->reputation['up'] ?></a> 
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
							<a href="" data-role="button" data-theme="c" data-mini="true" data-inline="true" style="font-style: italic;color:blue;"
							onclick="rate(1, '<?= $id ?>', '<?= $profile->user ?>');">+1</a>
						</p> 
						<span>
						<a href="#deletePopup" data-role="button" data-rel="popup" class="fooh" data-position-to="origin" 
						onclick="$('#deleteYes').attr('href', '?action=');" data-theme="d" data-icon="delete" data-iconpos="notext" style="position:absolute;right:1px;bottom:-5px;"><?= _('delete') ?></a>
						</span>
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
								<?= $profile->name ?> <a style="font-style: italic;">+<?= $profile->reputation['up'] ?></a>
							</h3>
							<p>
								<strong style="color:#444;"><?= $profile->activity ?></strong>
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
							<p>
								<?= _('Active partnerships') ?>:
								<ul>
								<? foreach($profile->partnerships as $item) : ?>
									<li></ul><a href="?action=details&id=<?= $item ?>&namespace=part"><?= $item ?></a></li>
								<? endforeach ?>
								</ul>
							</p>
							<p style="position:absolute; right: 1px; top: 50px;">
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