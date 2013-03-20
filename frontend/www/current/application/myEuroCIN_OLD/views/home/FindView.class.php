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
				<br />
				<!-- Nazione -->
				<select name="Nazione">
					<option value=""><?= $_SESSION['dictionary'][LG]["ontology1"] ?></option>
					<option value="Alessandria"><?= $_SESSION['dictionary'][LG]["Nazione"][0] ?></option>
					<option value="Asti"><?= $_SESSION['dictionary'][LG]["Nazione"][1] ?></option>
					<option value="Cuneo"><?= $_SESSION['dictionary'][LG]["Nazione"][2] ?></option>
					<option value="Francia"><?= $_SESSION['dictionary'][LG]["Nazione"][3] ?></option>
					<option value="Genova"><?= $_SESSION['dictionary'][LG]["Nazione"][4] ?></option>
					<option value="Imperia"><?= $_SESSION['dictionary'][LG]["Nazione"][5] ?></option>
					<option value="Savona"><?= $_SESSION['dictionary'][LG]["Nazione"][6] ?></option>
				</select>
				<?php $dataBean = new MDataBean("Nazione", null, KEYWORD); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
				<br />
				<!-- Lingua -->
				<select name="Lingua">
					<option value=""><?= $_SESSION['dictionary'][LG]["ontology2"] ?></option>
					<option value="italiano"><?= $_SESSION['dictionary'][LG]["Ligua"][0] ?></option>
					<option value="francese"><?= $_SESSION['dictionary'][LG]["Ligua"][1] ?></option>
				</select>
				<?php $dataBean = new MDataBean("Lingua", null, KEYWORD); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
				<br />
				<!-- Categorie -->
				    <fieldset data-role="controlgroup">
					   
					   <!-- Arte/Cultura: -->
					   <input type="checkbox" name="Arte_Cultura" id="Arte_Cultura" class="custom" />
					   <label for="Arte_Cultura"><?= $_SESSION['dictionary'][LG]["Categorie"][0] ?></label>
					   <?php $dataBean = new MDataBean("Arte_Cultura", null, KEYWORD); ?>
					   <input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
					   
					    <!-- Natura: -->
					   <input type="checkbox" name="Natura" id="Natura" class="custom" />
					   <label for="Natura"><?= $_SESSION['dictionary'][LG]["Categorie"][1] ?></label>
					   <?php $dataBean = new MDataBean("Natura", null, KEYWORD); ?>
					   <input type="hidden" name="ontology4" value="<?= urlencode(json_encode($dataBean)); ?>">
					   
					   	<!-- Tradizioni: -->
					   <input type="checkbox" name="Tradizioni" id="Tradizioni" class="custom" />
					   <label for="Tradizioni"><?= $_SESSION['dictionary'][LG]["Categorie"][2] ?></label>
					   <?php $dataBean = new MDataBean("Tradizioni", null, KEYWORD); ?>
					   <input type="hidden" name="ontology5" value="<?= urlencode(json_encode($dataBean)); ?>">
					   
					   <!-- Enogastronomia: -->
					   <input type="checkbox" name="Enogastronomia" id="Enogastronomia" class="custom" />
					   <label for="Enogastronomia"><?= $_SESSION['dictionary'][LG]["Categorie"][3] ?></label>
					   <?php $dataBean = new MDataBean("Enogastronomia", null, KEYWORD); ?>
					   <input type="hidden" name="ontology6" value="<?= urlencode(json_encode($dataBean)); ?>">
					   
					   <!-- Benessere: -->
					   <input type="checkbox" name="Benessere" id="Benessere" class="custom" />
					   <label for="Benessere"><?= $_SESSION['dictionary'][LG]["Categorie"][4] ?></label>
					   <?php $dataBean = new MDataBean("Benessere", null, KEYWORD); ?>
					   <input type="hidden" name="ontology7" value="<?= urlencode(json_encode($dataBean)); ?>">
					   
					   <!-- Storia: -->
					   <input type="checkbox" name="Storia" id="Storia" class="custom" />
					   <label for="Storia"><?= $_SESSION['dictionary'][LG]["Categorie"][5] ?></label>
					   <?php $dataBean = new MDataBean("Storia", null, KEYWORD); ?>
					   <input type="hidden" name="ontology8" value="<?= urlencode(json_encode($dataBean)); ?>">
					   
					   <!-- Religione: -->
					   <input type="checkbox" name="Religione" id="Religione" class="custom" />
					   <label for="Religione"><?= $_SESSION['dictionary'][LG]["Categorie"][6] ?></label>
					   <?php $dataBean = new MDataBean("Religione", null, KEYWORD); ?>
					   <input type="hidden" name="ontology9" value="<?= urlencode(json_encode($dataBean)); ?>">
					   
					   <!-- Escursioni/Sport: -->
					   <input type="checkbox" name="Escursioni_Sport" id="Escursioni_Sport" class="custom" />
					   <label for="Escursioni_Sport"><?= $_SESSION['dictionary'][LG]["Categorie"][7] ?></label>
					   <?php $dataBean = new MDataBean("Escursioni_Sport", null, KEYWORD); ?>
					   <input type="hidden" name="ontology10" value="<?= urlencode(json_encode($dataBean)); ?>">
					   
				    </fieldset>
				<div data-role="collapsible">
					 <h3><?= $_SESSION['dictionary'][LG]["ontology0"] ?></h3>
					<!-- Titolo -->
					<input type="search" name="data" value="" />
					<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
				</div>
				
				<center><a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm.submit()" data-inline="true" data-icon="search"><?= $_SESSION['dictionary'][LG]["view2"] ?></a></center>
			</form>
		</div>
	<?php }
}
?>
