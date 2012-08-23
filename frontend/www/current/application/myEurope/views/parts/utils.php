<?php 





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
 		<li><a href="./" data-icon="back" rel="external"><?= APPLICATION_NAME ?></a></li>
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
   		<li><a data-rel="back" data-icon="back" data-theme="d"><?= _("Back") ?></a></li>
   		<li><a class="ui-btn-active"><?= $title ?></a></li>
   		<li><a href="<?= $url ?>" data-theme="d" data-icon="<?= $icon ?>"><?= $button ?></a></li>
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
            	<li><a data-rel="back" data-icon="back" rel="external" data-theme="d"><?= _("Back") ?></a></li>
            	<li><a href="<?= $url ?>" data-theme="d" rel="external"  data-icon="<?= $icon ?>"><?= $button ?></a></li>
            	<li><a class="ui-btn-active" ><?= $title ?></a></li>
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
      
      function tabs_2empty($title) {
      	?>
            	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
            	<ul>
            		<li><a data-rel="back" data-icon="back" rel="external"  data-theme="d"><?= _("Back") ?></a></li>
            		<li><a class="ui-btn-active"><?= $title ?></a></li>
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
       
       
	function printProfile($profile, $id=""){
       	switch($profile->role){
       		
       		default:
       			?>

				<ul data-role="listview" data-inset="true" data-theme="d" style="margin-top: 2px;">
					<li>
						<h2>
							<?= $profile->name ?> 
						</h2>
						<p>
							<?= _("role") ?>: <strong style="color:#444;"><?= $profile->role ?></strong>
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
						
						<div class="ui-li-aside" data-role="controlgroup" style="width:auto;" data-type="horizontal" data-mini="true">
							<a data-role="button" data-theme="d" style="color:gray;" data-icon="minus" onclick="rate(0, '<?= $id ?>', '<?= $profile->user ?>');"><?= $profile->reputation['down'] ?></a>
							<a data-role="button" data-theme="d" style="color:blue;" data-icon="plus" onclick="rate(1, '<?= $id ?>', '<?= $profile->user ?>');"><?= $profile->reputation['up'] ?></a>
						</div>
						
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
								<?= $profile->name ?>
							</h3>
							<p>
								<?= _("role") ?>: <strong style="color:#444;"><?= $profile->role ?></strong>
							</p>
							<? if (!empty($profile->siret)): ?>
							<p>
								<?= _("SIRET") ?>: <strong style="color:#444;"><?= $profile->siret ?></strong>
							</p>
							<? endif ?>
							<p>
								<strong style="color:#444;"><?= (empty($profile->activity)?" ":$profile->activity) ?></strong>
							</p>
							<div class="ui-li-aside" data-role="controlgroup" style="width:auto;" data-type="horizontal" data-mini="true">
								<a class="ui-disabled" data-role="button" style="color:gray;" data-icon="minus" ><?= $profile->reputation['down'] ?></a>
								<a class="ui-disabled" data-role="button" style="color:blue;" data-icon="plus" ><?= $profile->reputation['up'] ?></a>
							</div>
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
							<? foreach($profile->partnerships as $item) :?>
								<li><p><?= _('Active partnership') ?>: #<a href="?action=details&id=<?= $item ?>&namespace=part"><?= substr($item, 0, 3) ?></a></p></li>
							<? endforeach ?>
						</li>
					</ul>
					
					<?
					break;
			}
	       	 
		}

       ?>