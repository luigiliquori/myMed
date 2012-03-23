<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class MainView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct( $id = "mainView") {
		parent::__construct($id);
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header" data-theme="a">
			<a href="#buildView1" data-role="button" data-theme="g" data-transition="slidedown" data-icon="gear">Nouvelle Application</a>
			<h1>myMed - Fonctionnalités</h1>
			<a href="?application=0" rel="external" data-role="button" data-theme="r" class="ui-btn-right" data-icon="delete">Fermer</a>
		</div>
	<?php }
	
	protected /*void*/ function getFirendAddress($id) { ?>
		<select	id="select<?= $id ?>" data-iconpos="notext" data-icon="plus" name="enum"
		onchange="changeAddress('<?= $id ?>')">
		
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
		<div data-role="collapsible" data-collapsed="false">
			<h3>Géolocalisation</h3>
			<div id="<?= APPLICATION_NAME ?>Map" style="position: relative; width: 100%; height: 200px;"></div>
			<br />
			<span>Adresse :</span>
			<input id="formatedAddress0" type="text" value="" />
			<?php $this->getFirendAddress("0");	?>
			<a href="#" data-role="button" onclick="refreshMap($('#formatedAddress0').val());" >Géolocaliser</a>
		</div>
	<?php }
	
	protected /*void*/ function getPublishFeature() { ?>
		<!-- PUBLISH FEATURE -->
		<div data-role="collapsible">
			<h3>Publication</h3>
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="publish" />
				<input type="hidden" name="numberOfOntology" value="4" />
				
				<!-- KEYWORD -->
				<span>Mot Cléf :</span>
				<input type="text" name="keyword" value=""  data-inline="true"/><br />
				<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
				
				<!-- GPS -->
				<span>Adresse (position GPS) :</span>
				<input id="formatedAddress1" type="text" name="gps" value=""  data-inline="true"/>
				<?php $this->getFirendAddress("1");	?>
				<?php $gps = new MDataBean("gps", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- DATE -->
				<span>Date :</span>
				<input type="date" name="date" data-role="datebox" data-options='{"mode": "calbox"}' readonly="readonly" />
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">
				<br /><br />
				
				<!-- TEXT -->
				<span>Text :</span>
				<textarea name="text" rows="" cols=""></textarea>
				<?php $text = new MDataBean("text", null, TEXT); ?>
				<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($text)); ?>">
				<br />
				
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" >Publier</a>
			</form>
		</div>
	<?php }
	
	protected /*void*/ function getSubscribeFeature() { ?>
		<!-- SUBSCRIBE FEATURE -->
		<div data-role="collapsible">
			<h3>Souscription</h3>
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>SubscribeForm" id="<?= APPLICATION_NAME ?>SubscribeForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="subscribe" />
				<input type="hidden" name="numberOfOntology" value="3" />
				
				<!-- KEYWORD -->
				<span>Mot Cléf :</span>
				<input type="text" name="keyword" value=""  data-inline="true"/><br />
				<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
				
				<!-- GPS -->
				<span>Adresse (position GPS) :</span>
				<input id="formatedAddress2" type="text" name="gps" value=""  data-inline="true"/>
				<?php $this->getFirendAddress("2");	?>
				<?php $gps = new MDataBean("gps", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				<!-- DATE -->
				<span>Date :</span>
				<input type="date" name="date" data-role="datebox" data-options='{"mode": "calbox"}' readonly="readonly" />
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">

				<br /><br />
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>SubscribeForm.submit()" >Souscrire</a>	
			</form>
		</div>
	<?php }
	
	protected /*void*/ function getFindFeature() { ?>
		<!-- FIND FEATURE -->
		<div data-role="collapsible">
		<h3>Recherche</h3>
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm" id="<?= APPLICATION_NAME ?>FindForm">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="numberOfOntology" value="3" />
				
				<!-- KEYWORD -->
				<span>Mot Cléf :</span>
				<input type="text" name="keyword" value=""  data-inline="true"/><br />
				<?php $keyword = new MDataBean("keyword", null, KEYWORD); ?>
				<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
				
				<!-- GPS -->
				<span>Adresse (position GPS) :</span>
				<input id="formatedAddress3" type="text" name="gps" value=""  data-inline="true"/>
				<?php $this->getFirendAddress("3");	?>
				<?php $gps = new MDataBean("gps", null, GPS); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($gps)); ?>">
				<br />
				
				<!-- DATE -->
				<span>Date :</span>
				<input type="date" name="date" data-role="datebox" data-options='{"mode": "calbox"}' readonly="readonly" />
				<?php $date = new MDataBean("date", null, DATE); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($date)); ?>">

				<br /><br />
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()" >Rechercher</a>	
			</form>
		</div>
	<?php }
	
	protected /*void*/ function getProfileFeature() { ?>
		<div data-role="collapsible" data-collapsed="true">
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
	<?php }
	
	protected /*void*/ function getSocialNetworkFeature() { ?>
		<!-- FRIENDS -->
		<div data-role="collapsible" data-collapsed="true">
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
		 </div>
	<?php }

	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*void*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
				<center>
					<div data-role="collapsible-set" data-theme="a" data-content-theme="d" style="width: 70%; text-align: left;"> 
						<?php $this->getMapFeature() ?>
						<?php $this->getPublishFeature() ?>
						<?php $this->getFindFeature() ?>	
						<?php $this->getSubscribeFeature() ?>
						<?php $this->getProfileFeature() ?>
						<?php $this->getSocialNetworkFeature() ?>
					</div>
				</center>
			
		</div>
	<?php }
	
}
?>