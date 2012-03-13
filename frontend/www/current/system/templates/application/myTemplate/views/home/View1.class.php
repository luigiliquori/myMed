<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class View1 extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	protected /*String*/ $id;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($id = "View1") {
		parent::__construct($id);
		$this->id = $id;
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="b">
			<a href="?application=0" rel="external" data-role="button" data-theme="r" data-icon="delete">Fermer</a>
			<h1><?= APPLICATION_NAME ?></h1>
			<a href="#View2" data-role="button" class="ui-btn-right">Vue 2</a>
		</div>
	<?php }
	
	private /*void*/ function getPredicateOntology($keyword, $address, $date) { ?>
		<!-- KEYWORD -->
		<?php for( $i=0 ; $i < $keyword ; $i++ ) { ?>
			<div>
				<span>Mot Cléf :</span>
				<input type="text" name="keyword<?= $i ?>" value=""  data-inline="true"/><br />
				<?php $keywordBean = new MDataBean("keyword" . $i, null, KEYWORD); ?>
				<input type="hidden" name="ontology<?= $i ?>" value="<?= urlencode(json_encode($keywordBean)); ?>">
			</div>
		<?php } ?>
		
		<!-- GPS -->
		<?php for( $i=0 ; $i < $address ; $i++ ) { ?>
			<div>
				<span>Adresse (position GPS) :</span>
				<input type="text" name="address<?= $i ?>" value=""  data-inline="true" class="myPositionAutoComplete"/>
				<?php $this->getFirendAddress($this->id . "1");	?>
				<?php $addressBean = new MDataBean("address" . $i, null, GPS); ?>
				<input type="hidden" name="ontology<?= $keyword+$i ?>" value="<?= urlencode(json_encode($addressBean)); ?>">
				<br />
			</div>
		<?php } ?>
		
		<!-- DATE -->
		<?php for( $i=0 ; $i < $date ; $i++ ) { ?>
			<div>
				<span>Date :</span>
				<input type="date" name="date<?= $i ?>" data-role="datebox" data-options='{"mode": "calbox"}' readonly="readonly" />
				<?php $dateBean = new MDataBean("date" . $i, null, DATE); ?>
				<input type="hidden" name="ontology<?= $keyword+$address+$i ?>" value="<?= urlencode(json_encode($dateBean)); ?>">
				<br /><br />
			</div>
		<?php } ?>
	<?php }
	
	protected /*void*/ function getFirendAddress($id) { ?>
		<select data-iconpos="notext" data-icon="plus" name="enum" onchange="changeAddress(this)">
		
			<option value="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png&&Sophia-antipolis, France">Mymed</option>
			
			<!-- USER -->
			<?php if (isset($_SESSION['position'])) { ?>
				<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
			<?php } ?>
	
			<!-- FRIENDS -->
			<?php
			if(isset($_SESSION['friends'])) {
				foreach ($_SESSION['friends'] as $friend ) { ?>
					<?php if ($friend["position"]->formattedAddress != "") {?>
							<option
								value="<?= $friend["profilePicture"] ?>&&<?= $friend["position"]->formattedAddress ?>">
								<?= $friend["name"] ?>
							</option>
					<?php }
				}
			} ?>
        </select>
	<?php }
		
	protected /*void*/ function getMapFeature() { ?>
		<!-- MAP FEATURE -->
		<h3>Géolocalisation</h3>
		<div id="<?= APPLICATION_NAME ?>Map" style="position: relative; width: 100%; height: 200px;"></div>
		<br />
		<span>Adresse :</span>
		<input id="formatedAddress<?= $this->id ?>0" type="text" value="" />
		<?php $this->getFirendAddress($this->id . "0");	?>
		<a href="#" data-role="button" onclick="refreshMap($('#formatedAddress<?= $this->id ?>0').val());" >Géolocaliser</a>
	<?php }
	
	protected /*void*/ function getPublishFeature($keyword, $address, $date) { ?>
		<!-- PUBLISH FEATURE -->
		<h3>Publication</h3>
		<form  action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
			<input type="hidden" name="method" value="publish" />
			<input type="hidden" name="numberOfOntology" value="<?= $keyword + $address + $date + 1?>" />
			
			<?php $this->getPredicateOntology($keyword, $address, $date); ?>
			
			<!-- TEXT -->
			<span>Text :</span>
			<textarea name="text" rows="" cols=""></textarea>
			<?php $text = new MDataBean("text", null, TEXT); ?>
			<input type="hidden" name="ontology<?= $keyword + $address + $date ?>" value="<?= urlencode(json_encode($text)); ?>">
			<br />
			
			<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" >Publier</a>
		</form>
	<?php }
	
	protected /*void*/ function getSubscribeFeature($keyword, $address, $date) { ?>
		<!-- SUBSCRIBE FEATURE -->
		<h3>Souscription</h3>
		<form  action="#" method="post" name="<?= APPLICATION_NAME ?>SubscribeForm" id="<?= APPLICATION_NAME ?>SubscribeForm">
			<!-- Define the method to call -->
			<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
			<input type="hidden" name="method" value="subscribe" />
			<input type="hidden" name="numberOfOntology" value="<?= $keyword + $address + $date ?>" />
			
			<?php $this->getPredicateOntology($keyword, $address, $date); ?>
			
			<br /><br />
			<a href="#" data-role="button"onclick="document.<?= APPLICATION_NAME ?>SubscribeForm.submit()" >Souscrire</a>	
		</form>
	<?php }
	
	protected /*void*/ function getFindFeature($keyword, $address, $date) { ?>
		<!-- FIND FEATURE -->
		<h3>Recherche</h3>
		<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm" id="<?= APPLICATION_NAME ?>FindForm">
			<!-- Define the method to call -->
			<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
			<input type="hidden" name="method" value="find" />
			<input type="hidden" name="numberOfOntology" value="<?= $keyword + $address + $date ?>" />
			
			<?php $this->getPredicateOntology($keyword, $address, $date); ?>

			<br /><br />
			<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()" >Rechercher</a>	
		</form>
	<?php }
	
	protected /*void*/ function getProfileFeature() { ?>
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
	<?php }
	
	protected /*void*/ function getSocialNetworkFeature() { ?>
		<!-- FRIENDS -->
	 	<h3>Reseaux Sociaux</h3>
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
			<br />
		<?php } ?>
	<?php }

	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*void*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<center>
				<div style="width: 50%; text-align: left; color: black; border: 10px #e5e5e5 solid; border-radius: 15px; padding: 10px;">
					<?php 
					if(VIEW_1_MAP)
						$this->getMapFeature();
					if(VIEW_1_PUBLISH)
						$this->getPublishFeature(VIEW_1_PUBLISH_keyword, VIEW_1_PUBLISH_address, VIEW_1_PUBLISH_date);
					if(VIEW_1_SUBSCRIBE)
						$this->getSubscribeFeature(VIEW_1_SUBSCRIBE_keyword, VIEW_1_SUBSCRIBE_address, VIEW_1_SUBSCRIBE_date);
					if(VIEW_1_FIND)
						$this->getFindFeature(VIEW_1_FIND_keyword, VIEW_1_FIND_address, VIEW_1_FIND_date);
					if(VIEW_1_PROFILE)
						$this->getProfileFeature();
					if(VIEW_1_SOCIAL_NETWORK)
						$this->getSocialNetworkFeature();
					?> 
				</div>
			</center>
		</div>
	<?php }
	
}
?>