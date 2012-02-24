<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class OptionView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	private /*String[]*/ $filterList;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*MyTemplateHandler*/ $handler) {
		parent::__construct("Option");
		$this->handler = $handler;
		$this->filterList = array("mymed", "carf");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<div data-role="header" data-theme="b">
			<h1>Options</h1>
			<a href="#Find" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a>
		</div>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div data-role="content" Style="font-size: 10pt;">

			<!-- UPDATE POIs -->
			<div data-role="collapsible-set">
			
				<!-- POIs - Filter -->
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c" style="text-align: left;">
				 	<h3>Points d'interêts</h3>
				 	<div id="<?= APPLICATION ?>" data-role="controlgroup">
				    	<?php foreach ($this->filterList as $filter) { ?>
					    	<input type="checkbox" name="<?= $filter ?>" id="<?= $filter ?>" class="custom" checked="checked" />
					    	<label for="<?= $filter ?>"><?= $filter ?></label>
				    	<?php } ?>
				    </div>
				</div>
			
				<!-- POIs - radius -->
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c" style="text-align: left;">
				 	<h3>Rayon de recherche</h3>
		  			Mètre:<br>
				   	<input type="range" name="slider" id="slider" value="500" min="100" max="1000"  data-theme="b"/>
				</div>
				
				<!-- Search Option -->
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c">
					<h3>Type de Trajet</h3>
					
					<div data-role="fieldcontain">
					    
						<fieldset data-role="controlgroup">   
						   <input type="checkbox" name="checkbox-1" id="checkbox-1" class="custom" checked="checked" 
						   onchange="$('#TrajetTypeRadio').css('display', this.checked ? 'block' : 'none');"/>
						   <label for="checkbox-1">En Transport Public</label>
					    </fieldset>
					    
					    	
						<fieldset id="TrajetTypeRadio" data-role="controlgroup">
						     	<input type="radio" name="radio-choice-1" id="radio-choice-1" value="choice-1" checked="checked" />
						     	<label for="radio-choice-1">Le moins de changements</label>
						
						     	<input type="radio" name="radio-choice-1" id="radio-choice-2" value="choice-2"  />
						     	<label for="radio-choice-2">Le plus rapide</label>
						</fieldset>
					    
					    
						<fieldset data-role="controlgroup">
						   <input type="checkbox" name="checkbox-1" id="checkbox-1" class="custom" checked="checked"/>
						   <label for="checkbox-1">En Voiture</label>
						</fieldset>
					</div>
					
				</div>
				
				<!-- Profile -->
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c">
					<h3>Profile</h3>
					<?php if($_SESSION['user']->profilePicture != "") { ?>
						<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="100">
					<?php } else { ?>
						<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="100">
					<?php } ?>
					<br />
				    Prenom: <?= $_SESSION['user']->firstName ?><br />
					Nom: <?= $_SESSION['user']->lastName ?><br />
					Date de naissance: <?= $_SESSION['user']->birthday ?><br />
					eMail: <?= $_SESSION['user']->email ?><br />
					<div data-role="controlgroup" data-type="horizontal">
						<a href="#inscription" data-role="button" data-rel="dialog" data-inline="true" data-theme="b" data-icon="refresh">mise à jour</a>
						<?php if(TARGET == "desktop") { ?>
							<a href="#login" onclick="document.disconnectForm.submit()" rel="external" data-role="button" data-theme="r">Deconnexion</a>
						<?php } else { ?>
							<a href="mobile_binary<?= MOBILE_PARAMETER_SEPARATOR ?>logout" data-role="button" data-theme="r">Deconnexion</a>
						<?php } ?>
					</div>
				</div>
				
				<!-- FRIENDS -->
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c">
				 	<h3>Mes amis</h3>
				 	<?php $i=0; ?>
						<?php foreach ($_SESSION['friends'] as $friend ) { ?>
							<a href="<?= $friend["link"] ?>"><img src="http://graph.facebook.com/<?= $friend["id"] ?>/picture" width="20px" alt="<?= $friend["name"] ?>" /></a>
						<?php $i++; ?>
					<?php } 
					if($i == 0) {
						$socialNetworkConnection =  new SocialNetworkConnection();
					 	foreach($socialNetworkConnection->getWrappers() as $wrapper) {
					 		$url = TARGET == "mobile" ? str_replace("www", "m", $wrapper->getLoginUrl()) . "&display=touch" :  $wrapper->getLoginUrl();
					 		echo "<a href='" . $url . "'>" . $wrapper->getSocialNetworkButton() . "</a>";
					 	}
					} else { ?>
						<br /><br />
						<script>(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
							fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
						<div class="fb-like" data-href="http://www.mymed.fr" data-send="true" data-width="450" data-show-faces="true"></div>
					<?php } ?>
				 </div>
				 
				<!-- HELP -->
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c" style="text-align: left;">
				 	<h3>Aide</h3>
				 	<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
				 </div>
				 
				<!-- ABOUT -->
				<div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="c">
				 	<h3>A propos</h3>
				 	<h2>myRiviera v1.0 beta</h2>
				 	<h3>myMed - INTERREG IV - Alcotra</h3>
				 	<div class="innerContent">
						<img alt="Alcotra"				src="system/img/logos/alcotra"	style="width: 100px;" />
						<img alt="Europe"				src="system/img/logos/europe"	style="width: 50px;" />
						<img alt="Conseil Général 06"	src="system/img/logos/cg06"		style="width: 100px;" />
						<img alt="Regine Piemonte"		src="system/img/logos/regione"	style="width: 100px;" />
						<img alt="Région PACA"			src="system/img/logos/PACA"		style="width: 100px;" />
						<img alt="Prefecture 06"		src="system/img/logos/pref"		style="width: 70px;" />
						<img alt="Inria"				src="system/img/logos/inria"	style="width: 100px;" />
						<p>"Ensemble par-delà les frontières"</p>
					</div>
				 </div>
				
			</div>
			
		</div>
	<?php }
	
}
?>

