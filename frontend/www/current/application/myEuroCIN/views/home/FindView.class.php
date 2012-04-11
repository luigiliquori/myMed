<?php

require_once '../../lib/dasp/beans/MDataBean.class.php';
require_once '../../lib/dasp/request/Request.class.php';
require_once '../../lib/dasp/request/Reputation.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class FindView extends MainView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($handler) {
		$this->handler = $handler;
		parent::__construct("FindView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getPublishContent() { ?>
		<form  action="#FindView" method="post" name="<?= APPLICATION_NAME ?>FindForm" id="<?= APPLICATION_NAME ?>FindForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
			<input type="hidden" name="method" value="find" />
			<input type="hidden" name="numberOfOntology" value="12" />
			
			<!-- Titolo -->
			<span>Titolo :</span>
			<input type="text" name="data" value="" />
			<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
			<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Nazione -->
			<span>Nazione :</span>
			<select name="Nazione">
				<option value=""></option>
				<option value="Alessandria">Alessandria</option>
				<option value="Asti">Asti</option>
				<option value="Cuneo">Cuneo</option>
				<option value="Francia">Francia</option>
				<option value="Genova">Genova</option>
				<option value="Imperia">Imperia</option>
				<option value="Savona">Savona</option>
			</select>
			<?php $dataBean = new MDataBean("Nazione", null, KEYWORD); ?>
			<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Lingua -->
			<span>Lingua :</span>
			<select name="Lingua">
				<option value="italiano">italiano</option>
				<option value="francese">francese</option>
			</select>
			<?php $dataBean = new MDataBean("Lingua", null, KEYWORD); ?>
			<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Categorie -->
			<div data-role="fieldcontain">
				<span>Categorie :</span>
			    <fieldset data-role="controlgroup">
				   
				   <!-- Arte/Cultura: -->
				   <input type="checkbox" name="Arte_Cultura" id="Arte_Cultura" class="custom" />
				   <label for="Arte_Cultura">Arte/Cultura</label>
				   <?php $dataBean = new MDataBean("Arte_Cultura", null, KEYWORD); ?>
				   <input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				    <!-- Natura: -->
				   <input type="checkbox" name="Natura" id="Natura" class="custom" />
				   <label for="Natura">Natura</label>
				   <?php $dataBean = new MDataBean("Natura", null, KEYWORD); ?>
				   <input type="hidden" name="ontology4" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   	<!-- Tradizioni: -->
				   <input type="checkbox" name="Tradizioni" id="Tradizioni" class="custom" />
				   <label for="Tradizioni">Tradizioni</label>
				   <?php $dataBean = new MDataBean("Tradizioni", null, KEYWORD); ?>
				   <input type="hidden" name="ontology5" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Enogastronomia: -->
				   <input type="checkbox" name="Enogastronomia" id="Enogastronomia" class="custom" />
				   <label for="Enogastronomia">Enogastronomia</label>
				   <?php $dataBean = new MDataBean("Enogastronomia", null, KEYWORD); ?>
				   <input type="hidden" name="ontology6" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Benessere: -->
				   <input type="checkbox" name="Benessere" id="Benessere" class="custom" />
				   <label for="Benessere">Benessere</label>
				   <?php $dataBean = new MDataBean("Benessere", null, KEYWORD); ?>
				   <input type="hidden" name="ontology7" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Storia: -->
				   <input type="checkbox" name="Storia" id="Storia" class="custom" />
				   <label for="Storia">Storia</label>
				   <?php $dataBean = new MDataBean("Storia", null, KEYWORD); ?>
				   <input type="hidden" name="ontology8" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Religione: -->
				   <input type="checkbox" name="Religione" id="Religione" class="custom" />
				   <label for="Religione">Religione</label>
				   <?php $dataBean = new MDataBean("Religione", null, KEYWORD); ?>
				   <input type="hidden" name="ontology9" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Escursioni/Sport: -->
				   <input type="checkbox" name="Escursioni_Sport" id="Escursioni_Sport" class="custom" />
				   <label for="Escursioni_Sport">Escursioni/Sport</label>
				   <?php $dataBean = new MDataBean("Escursioni_Sport", null, KEYWORD); ?>
				   <input type="hidden" name="ontology10" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
			    </fieldset>
			</div>
			<br />
			
			<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()" >Cercare</a>
		</form>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getResultContent() {
		if($succes = $this->handler->getSuccess()) {
			if($_POST['method'] == "find") {
				$this->getResult();
			} else {
				$this->getDetail();
			}
		} else {
			echo $this->handler->getError();
		}
	}
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getResult() { ?>
		<ul data-role="listview" data-filter="true" data-theme="c" data-dividertheme="a" >
				<?php $i=0 ?>
				<?php foreach(json_decode($this->handler->getSuccess()) as $controller) { ?>
					<li>
						<!-- RESULT DETAILS -->
						<form action="#FindView" method="post" name="getDetailForm<?= $i ?>">
							<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
							<input type="hidden" name="method" value="getDetail" />
							<input type="hidden" name="user" value="<?= $controller->publisherID ?>" />
							<input type="hidden" name="predicate" value="<?= $controller->predicate ?>" />
						</form>
						<a href="#" onclick="document.getDetailForm<?= $i ?>.submit()">
							<?= $controller->data ?>
						</a>
					</li>
					<?php $i++ ?>
				<?php } ?>
			</ul>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getDetail() { ?>
		<!-- PROFILE -->
		<?php
		$request = new Request("ProfileRequestHandler", READ);
		$request->addArgument("id",  $_POST['user']);
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
			
		if($responseObject->status != 200) { ?>
			<h2 style="color:red;"><?= $profile->error ?></h2>
		<?php } else { 
			$profile = json_decode($responseObject->data->user);
			if($profile->profilePicture != "") { ?>
				<img alt="thumbnail" src="<?= $profile->profilePicture ?>" width="180">
			<?php } else { ?>
				<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="180">
			<?php } ?>
			<br><br>
			Prenom: <?= $profile->firstName ?><br />
			Nom: <?= $profile->lastName ?><br />
			Date de naissance: <?= $profile->birthday ?><br />
			eMail: <?= $profile->email ?><br />
			Reputation:
			<?php 
				$reputation = new Reputation();
				$value = $reputation->getReputation(APPLICATION_NAME, $profile->id, $_SESSION['user']->id);
				$percent = ($value * 100);
				echo $percent . "%"; 
			?><br />
			<?php 
	    	$j=0;
	    	while($j<=$percent){ ?>
	    		<img alt="star" src="system/img/star.png" width="20" />
	    		<?php 
	    		$j+=25;
	    	}
	    	while($j<=100){ ?>
	    		<img alt="star" src="system/img/starGray.png" width="20" />		
	    		<?php 
	    		$j+=25;
	    	} ?>
	    	<br />
		<?php } ?>
		
		<hr />
		
		<!-- VALUES -->
		<?php foreach(json_decode($this->handler->getSuccess()) as $details) { ?>
			<?= $details->key; ?> : <?= urldecode($details->value) ?>
			<br />
		<?php } ?>
		
		<?php if($_SESSION['user']->id != $profile->id) { ?>
			<!-- REPUTATION -->
			<form action="#" method="post" name="increaseRepForm" id="increaseRepForm">
	    		<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="startInteraction" />
	    		<input type="hidden" name="producer" value="<?= $profile->id ?>">
	    		<input type="hidden" name="consumer" value="<?= $_SESSION['user']->id ?>" />
	    		<input type="hidden" name="start" value="<?= time(); ?>" />
	    		<input type="hidden" name="end" value="<?= time(); ?>" />
	    		<input type="hidden" name="feedback" value="1" />
	    		<input type="hidden" name="predicate" value="<?= $_POST['predicate'] ?>" />
	    	</form>
	    	<form action="#" method="post" name="decreaseRepForm" id="decreaseRepForm">
	    		<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="startInteraction" />
	    		<input type="hidden" name="producer" value="<?= $profile->id ?>">
	    		<input type="hidden" name="consumer" value="<?= $_SESSION['user']->id ?>" />
	    		<input type="hidden" name="start" value="<?= time(); ?>" />
	    		<input type="hidden" name="end" value="<?= time(); ?>" />
	    		<input type="hidden" name="feedback" value="0" />
	    		<input type="hidden" name="predicate" value="<?= $_POST['predicate'] ?>" />
	    	</form>
	    	<a data-role="button" data-inline="true" data-theme="a" onclick="document.decreaseRepForm.submit()">-1</a>
	    	<a data-role="button" data-inline="true" data-theme="a" onclick="document.increaseRepForm.submit()">+1</a>
    	<?php } ?>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
		if(!$this->handler->getError() && !$this->handler->getSuccess()) {
			$this->getPublishContent();
		} else {
			$this->getResultContent();
		}
		echo '</div>';
	}
}
?>
