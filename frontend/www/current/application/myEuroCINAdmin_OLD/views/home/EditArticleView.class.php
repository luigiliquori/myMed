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
class EditArticleView extends MainView {
	
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
		parent::__construct("EditArticleView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Edit the article
	*/
	private /*String*/ function getContentToValidate() { ?>
	
	
		
	    
	
		<!-- getValue -->
		<?php $values = array(); ?>
		<?php foreach(json_decode($this->handler->getSuccess()) as $details) {
			$values[$details->key] = urldecode($details->value);
		} ?>
		
		<!-- AUTHOR -->
		
    	<?php
    	if(isset($_POST['user'])) {
    		$request = new Request("ProfileRequestHandler", READ);
    		$request->addArgument("id",  $_POST['user']);
    		$responsejSon = $request->send();
    		$responseObject = json_decode($responsejSon);
    		$profile = json_decode($responseObject->data->user);
    		if($profile->profilePicture != "") { ?>
    			<img alt="thumbnail" src="<?= $profile->profilePicture ?>" width="30" height="30" style="position: absolute;">
    		<?php } else { ?>
    			<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="30" height="30" style="position: absolute;">
    		<?php }	?>
    		<div style="position: relative; top: 3px; left:35px; height: 30px;">
		    	<a href="mailto:<?= $profile->email ?>" target="_blank">
		    		<?= $profile->firstName ?> &nbsp;
		    		<?= $profile->lastName ?> 
		    	</a> &nbsp;
		    	<?= $values['begin'] ?>
		    </div>
		    <br />
	
    	<?php } ?>
	
		<form  action="#ArticleView" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" id="EditArticleApplication" name="application" value="<?= APPLICATION_NAME ?>" />
			<input type="hidden" id="EditArticleMethod" name="method" value="publish" />
			<input type="hidden" name="numberOfOntology" value="13" />
			
			<!-- Titolo -->
			<span>Titolo :</span>
			<input type="text" name="data" value="<?= $values['data'] ?>" />
			<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
			<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Nazione -->
			<span>Nazione :</span>
			<select name="Nazione">
				<option value=""></option>
				<option value="Alessandria" <?= $values['Nazione'] == 'Alessandria' ? 'selected' : ''?>>Alessandria</option>
				<option value="Asti" 		<?= $values['Nazione'] == 'Asti' ? 'selected' : ''?>>Asti</option>
				<option value="Cuneo" 		<?= $values['Nazione'] == 'Cuneo' ? 'selected' : ''?>>Cuneo</option>
				<option value="Francia" 	<?= $values['Nazione'] == 'Francia' ? 'selected' : ''?>>Francia</option>
				<option value="Genova" 		<?= $values['Nazione'] == 'Genova' ? 'selected' : ''?>>Genova</option>
				<option value="Imperia" 	<?= $values['Nazione'] == 'Imperia' ? 'selected' : ''?>>Imperia</option>
				<option value="Savona" 		<?= $values['Nazione'] == 'Savona' ? 'selected' : ''?>>Savona</option>
			</select>
			<?php $dataBean = new MDataBean("Nazione", null, KEYWORD); ?>
			<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Lingua -->
			<span>Lingua :</span>
			<select name="Lingua">
				<option value="italiano" <?= $values['Lingua'] == 'italiano' ? 'selected' : ''?>>italiano</option>
				<option value="francese" <?= $values['Lingua'] == 'francese' ? 'selected' : ''?>>francese</option>
			</select>
			<?php $dataBean = new MDataBean("Lingua", null, KEYWORD); ?>
			<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Categorie -->
			<div data-role="fieldcontain">
				<span>Categorie :</span>
			    <fieldset data-role="controlgroup">
				   
				   <!-- Arte/Cultura: -->
				   <input type="checkbox" name="Arte_Cultura" id="Arte_Cultura" class="custom" <?= $values['Arte_Cultura'] ? 'checked' : ''?> />
				   <label for="Arte_Cultura">Arte/Cultura</label>
				   <?php $dataBean = new MDataBean("Arte_Cultura", null, KEYWORD); ?>
				   <input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				    <!-- Natura: -->
				   <input type="checkbox" name="Natura" id="Natura" class="custom" <?= $values['Natura'] ? 'checked' : ''?> />
				   <label for="Natura">Natura</label>
				   <?php $dataBean = new MDataBean("Natura", null, KEYWORD); ?>
				   <input type="hidden" name="ontology4" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   	<!-- Tradizioni: -->
				   <input type="checkbox" name="Tradizioni" id="Tradizioni" class="custom" <?= $values['Tradizioni'] ? 'checked' : ''?> />
				   <label for="Tradizioni">Tradizioni</label>
				   <?php $dataBean = new MDataBean("Tradizioni", null, KEYWORD); ?>
				   <input type="hidden" name="ontology5" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Enogastronomia: -->
				   <input type="checkbox" name="Enogastronomia" id="Enogastronomia" class="custom" <?= $values['Enogastronomia'] ? 'checked' : ''?> />
				   <label for="Enogastronomia">Enogastronomia</label>
				   <?php $dataBean = new MDataBean("Enogastronomia", null, KEYWORD); ?>
				   <input type="hidden" name="ontology6" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Benessere: -->
				   <input type="checkbox" name="Benessere" id="Benessere" class="custom" <?= $values['Benessere'] ? 'checked' : ''?> />
				   <label for="Benessere">Benessere</label>
				   <?php $dataBean = new MDataBean("Benessere", null, KEYWORD); ?>
				   <input type="hidden" name="ontology7" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Storia: -->
				   <input type="checkbox" name="Storia" id="Storia" class="custom" <?= $values['Storia'] ? 'checked' : ''?> />
				   <label for="Storia">Storia</label>
				   <?php $dataBean = new MDataBean("Storia", null, KEYWORD); ?>
				   <input type="hidden" name="ontology8" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Religione: -->
				   <input type="checkbox" name="Religione" id="Religione" class="custom" <?= $values['Religione'] ? 'checked' : ''?> />
				   <label for="Religione">Religione</label>
				   <?php $dataBean = new MDataBean("Religione", null, KEYWORD); ?>
				   <input type="hidden" name="ontology9" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
				   <!-- Escursioni/Sport: -->
				   <input type="checkbox" name="Escursioni_Sport" id="Escursioni_Sport" class="custom" <?= $values['Escursioni_Sport'] ? 'checked' : ''?> />
				   <label for="Escursioni_Sport">Escursioni/Sport</label>
				   <?php $dataBean = new MDataBean("Escursioni_Sport", null, KEYWORD); ?>
				   <input type="hidden" name="ontology10" value="<?= urlencode(json_encode($dataBean)); ?>">
				   
			    </fieldset>
			</div>
			<br />
			
			<!-- TEXT -->
			<span>Testo :</span>
			<textarea id="CLEeditor" name="text"><?= $values['text'] ?></textarea>
			<?php $dataBean = new MDataBean("text", null, TEXT); ?>
			<input type="hidden" name="ontology11" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- DATE  -->
			<input type="hidden" name="begin" value="<?= $values['begin'] ?>" />
			<?php $date = new MDataBean("begin", null, DATE); ?>
			<input type="hidden" name="ontology12" value="<?= urlencode(json_encode($date)); ?>">
			
			<!-- PUBLISHER -->
			<input type="hidden" name="publisher" value="<?= $_POST['user'] ?>" />
			
			<center>
			<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" data-theme="g" data-inline="true">Validate</a>
			<a href="#" data-role="button" onclick="$('#EditArticleMethod').val('delete'); document.<?= APPLICATION_NAME ?>PublishForm.submit()" data-theme="r" data-inline="true">Reject</a>
			</center>
		</form>
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
			$this->getContentToValidate();
		echo '</div>';
	}
}
?>
