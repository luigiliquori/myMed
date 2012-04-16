<?php

require_once '../../lib/dasp/beans/MDataBean.class.php';
require_once '../../lib/dasp/request/Request.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class PublishView extends MainView {
	
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
		parent::__construct("PublishView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getPublishContent() { ?>
		<?php if($_SESSION['user']->id == VISITOR_ID) {?>
			<span>Please login before using this feature...</span>
			<?php return ?>
		<?php } ?>
	
		<form  action="#PublishView" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>_ADMIN" />
			<input type="hidden" name="method" value="publish" />
			<input type="hidden" name="numberOfOntology" value="13" />
			
			<!-- Titolo -->
			<span>Titolo :</span>
			<input type="text" name="data" value="" />
			<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
			<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- ADVANCED OPTIONS -->
			<div data-role="collapsible" data-content-theme="c">
				<h3>Optione</h3>
				
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
			</div>
			
			<!-- TEXT -->
			<span>Testo :</span>
			<textarea id="markItUp" name="text"></textarea>
			<?php $dataBean = new MDataBean("text", null, TEXT); ?>
			<input type="hidden" name="ontology11" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- DATE  -->
			<input type="hidden" name="begin" value="<?= date("d/m/Y") . " - " . date("H:i:s") ?>" />
			<?php $date = new MDataBean("begin", null, DATE); ?>
			<input type="hidden" name="ontology12" value="<?= urlencode(json_encode($date)); ?>">
			
			<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" >Publicare</a>
		</form>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getResultContent() {
		if($succes = $this->handler->getSuccess()) {
			echo $succes;
		} else {
			echo $this->handler->getError();
		}
	}
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
		if(isset($_POST['method']) && $_POST['method'] == "publish" && ($this->handler->getError() || $this->handler->getSuccess())) {
			$this->getResultContent();
		} else {
			$this->getPublishContent();
		}
		echo '</div>';
	}
	
}
?>
