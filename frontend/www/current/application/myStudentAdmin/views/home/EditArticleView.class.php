<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
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
			
		    </div>
		    <br />
	
    	<?php } ?>
	
		
		<form  action="#ArticleView" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" id="EditArticleApplication" name="application" value="<?= APPLICATION_NAME ?>" />
			<input type="hidden" id="EditArticleMethod" name="method" value="publish" />
			<input type="hidden" name="numberOfOntology" value="5" />
			
			<!-- Titolo -->
			<span>Titolo :</span>
			<input type="text" name="data" value="<?= $values['data'] ?>" />
			<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
			<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Area -->
			<span>Area :</span>
			<select name="Area">
				<option value=""></option>
				<option value="Aerospaziale"     <?= $values['Area'] == 'Aerospaziale' ? 'selected' : ''?>>Aerospaziale</option>
				<option value="Ambientale"       <?= $values['Area'] == 'Ambientale' ? 'selected' : ''?>>Ambientale</option>
				<option value="Autoveicolo"      <?= $values['Area'] == 'Autoveicolo' ? 'selected' : ''?>>Autoveicolo</option>
				<option value="Biomeccania"      <?= $values['Area'] == 'Biomeccania' ? 'selected' : ''?>>Biomeccania</option>
				<option value="Cinema" 	         <?= $values['Area'] == 'Cinema' ? 'selected' : ''?>>Cinema</option>
				<option value="Civile"	         <?= $values['Area'] == 'Civile' ? 'selected' : ''?>>Civile</option>
				<option value="Elettrica"        <?= $values['Area'] == 'Elettrica' ? 'selected' : ''?>>Elettrica</option>
				<option value="Elettronica"      <?= $values['Area'] == 'Elettronica' ? 'selected' : ''?>>Elettronica</option>
				<option value="Energetica"       <?= $values['Area'] == 'Energetica' ? 'selected' : ''?>>Energetica</option>
				<option value="Fisica"           <?= $values['Area'] == 'Fisica' ? 'selected' : ''?>>Fisica</option>
				<option value="Gestionale"       <?= $values['Area'] == 'Gestionale' ? 'selected' : ''?>>Gestionale</option>
				<option value="Informatica"      <?= $values['Area'] == 'Informatica' ? 'selected' : ''?>>Informatica</option>
				<option value="Matematica"       <?= $values['Area'] == 'Matematica' ? 'selected' : ''?>>Matematica</option>
				<option value="Materiali"        <?= $values['Area'] == 'Materiali' ? 'selected' : ''?>>Materiali</option>
				<option value="Meccanica"        <?= $values['Area'] == 'Meccanica' ? 'selected' : ''?>>Meccanica</option>
				<option value="Telecomunicazioni"<?= $values['Area'] == 'Telecomunicazioni' ? 'selected' : ''?>>Telecomunicazioni</option>
			</select>
			<?php $dataBean = new MDataBean("Area", null, KEYWORD); ?>
			<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- Categoria -->
			<span>Categoria :</span>
			<select name="Categoria">
				<option value="Stage" <?= $values['Categoria'] == 'Stage' ? 'selected' : ''?>>Stage</option>
				<option value="Job" <?= $values['Categoria'] == 'Job' ? 'selected' : ''?>>Job</option>
				<option value="Tesi" <?= $values['Categoria'] == 'Tesi' ? 'selected' : ''?>>Tesi</option>
				<option value="Appunti" <?= $values['Categoria'] == 'Appunti' ? 'selected' : ''?>>Appunti</option>
			</select>
			<?php $dataBean = new MDataBean("Categoria", null, KEYWORD); ?>
			<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			<br />
			
			<!-- TEXT -->
			<span>Testo :</span>
			<textarea id="CLEeditor" name="text"><?= $values['text'] ?></textarea>
			<?php $dataBean = new MDataBean("text", null, TEXT); ?>
			<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- DATE  -->
			<input type="hidden" name="begin" value="<?= $values['begin'] ?>" />
			<?php $date = new MDataBean("begin", null, DATE); ?>
			<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($date)); ?>">
			
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
