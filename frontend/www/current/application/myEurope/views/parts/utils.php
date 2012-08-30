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
   
   function tabs_3click(
   		$title,
   		$button,
   		$url = "?action=extendedProfile",
   		$icon = "profile") {
   
   	?>
      	<div data-role="navbar" data-theme="d" data-iconpos="left"> 
      	<ul>
      		<li><a data-rel="back" data-icon="back" data-theme="d"><?= _("Back") ?></a></li>
      		<li><a class="ui-btn-active"><?= $title ?></a></li>
      		<li><a onclick="<?= $url ?>" data-theme="d" data-icon="<?= $icon ?>"><?= $button ?></a></li>
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
        
        function profileForm($shortRole, $role) {
        ?>
<div data-role="page" id="<?= $shortRole ?>" >
	<div data-role="header" data-theme="c" data-position="fixed">
		<? tabs_3click(
				APPLICATION_NAME,
				_('Validate'),
				"$('#ExtendedProfileForm').submit();",
				"check") ?>
	</div>

	<div data-role="content">
		<form action="?action=ExtendedProfile" method="post" name="ExtendedProfileForm" id="ExtendedProfileForm">
			<input type="hidden" name="form" value="create" />
			<input type="hidden" name="role" value="<?= $role ?>" />
			
			<h3 class="ui-link"><?= $role ?></h3>
			
			<label for="textinputu1"><?= _('Organization or Company Name') ?>: </label>
			<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			
			<label for="textinputu2"> Domaine d'action: </label>
			<input id="textinputu2" name="activity" placeholder="" value='' type="text" />
			
			<label for="textinputu4"> <?= _('Address') ?>: </label>
			<input id="textinputu4" name="address" placeholder="" value='' type="text" />
			
			<div data-role="fieldcontain">
			<label for="area">Territoire d'action:</label>
			<fieldset id="area" data-role="controlgroup">
				<input type="radio" name="area" id="radio-view-a" value="local" checked="checked"/> <label for="radio-view-a">local</label>
				<input type="radio" name="area" id="radio-view-b" value="départemental" <?= $shortRole=="Département"?'checked="checked"':'' ?>/> <label for="radio-view-b">départemental</label>
				<input type="radio" name="area" id="radio-view-c" value="régional" <?= $shortRole=="Région"?'checked="checked"':'' ?>/> <label for="radio-view-c">régional</label>
				<input type="radio" name="area" id="radio-view-d" value="national" /> <label for="radio-view-d">national</label>
				<input type="radio" name="area" id="radio-view-e" value="international" /><label for="radio-view-e">international</label>
			</fieldset>
			</div>
			
			<div data-role="fieldcontain">
			<label for="type">Type de territoire:</label>
			<fieldset id="type" data-role="controlgroup">
				<input type="checkbox" name="type-urbain" id="check-view-a" value="urbain" checked="checked"/> <label for="check-view-a">urbain</label>
				<input type="checkbox" name="type-rural" id="check-view-b" value="rural" /> <label for="check-view-b">rural</label>
				<input type="checkbox" name="type-montagnard" id="check-view-c" value="montagnard" /> <label for="check-view-c">montagnard</label>
				<input type="checkbox" name="type-maritime" id="check-view-d" value="maritime" /> <label for="check-view-d">maritime</label>
			</fieldset>
			</div>
						
			<label for="textinputu6"> <?= _('Phone') ?>: </label>
			<input id="textinputu6" name="phone" placeholder="" value='' type="tel" />
			
			<label for="desc"> <?= _('Description') ?>: </label>
			<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			<br/>
				
			<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 8px;"/>
			<span style="position: relative; left: 50px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span><br />
			
			<div style="text-align: center;" >
				<input type="submit" data-inline="true" data-role="button" data-icon="check" value="Valider"/>
			</div>
		</form>
	</div>
</div>
        <?
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

				<ul data-role="listview" data-inset="true" data-theme="d" style="margin-top: 2px;">
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

						<div class="ui-li-aside" data-role="controlgroup" style="width:auto;" data-type="horizontal" data-mini="true">
							<a data-role="button" data-theme="d" style="color:gray;" data-icon="minus" onclick="rate(0, '<?= $profile->user ?>');"><?= $profile->reputation['down'] ?></a>
							<a data-role="button" data-theme="d" style="color:blue;" data-icon="plus" onclick="rate(1, '<?= $profile->user ?>');"><?= $profile->reputation['up'] ?></a>
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
							<? if (prettyprintId($profile->user) != $profile->email): ?>
							<p>
								<?= _("Id") ?>: <strong style="color:#444;"><?= prettyprintId($profile->user) ?></strong>
							</p>
							<? endif ?>
							<p>
								<?= _("Role") ?>: <strong style="color:#444;"><?= $profile->role ?></strong>
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