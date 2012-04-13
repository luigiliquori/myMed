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
	public /*String*/ function getContent() { ?>
		<div data-role="content" id="content" style="padding: 10px;" data-theme="c">
			<form action="#ResultView" method="post" name="<?= APPLICATION_NAME ?>FindForm" id="<?= APPLICATION_NAME ?>FindForm" enctype="multipart/form-data">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="numberOfOntology" value="12" />
				
				<!-- Nazione -->
				<select name="Nazione">
					<option value="">Nazione</option>
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
				
				<!-- Lingua -->
				<select name="Lingua">
					<option value="">Lingua</option>
					<option value="italiano">italiano</option>
					<option value="francese">francese</option>
				</select>
				<?php $dataBean = new MDataBean("Lingua", null, KEYWORD); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
				
				<!-- Categorie -->
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
				<div data-role="collapsible">
					 <h3>Titolo</h3>
					<!-- Titolo -->
					<input type="search" name="data" value="" />
					<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
				</div>
				
				<center><a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()" data-inline="true" data-icon="search">Cercare</a></center>
			</form>
		</div>
	<?php }
}
?>
