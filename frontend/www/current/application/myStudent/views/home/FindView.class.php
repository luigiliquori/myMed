<?php
/*
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
 */
?>
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
<<<<<<< HEAD
				<input type="hidden" name="numberOfOntology" value="3" />
				<br />
				<br />
				<ul data-role="listview"  data-theme="c" data-divider-theme="b" >
				<!-- Area -->
				<li data-role="list-divider" data-theme='b'><?= $_SESSION['dictionary'][LG]["ontology1"] ?></li>
				<br />
				<select name="Area">
					<option value=></option>
=======
				<input type="hidden" name="numberOfOntology" value="4" />
				
				<!-- Area -->
				<select name="Area">
					<option value=""><?= $_SESSION['dictionary'][LG]["ontology1"] ?></option>
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
					<option value="Aerospaziale"><?= $_SESSION['dictionary'][LG]["Area"][0] ?></option>
					<option value="Ambientale"><?= $_SESSION['dictionary'][LG]["Area"][1] ?></option>
					<option value="Autoveicolo"><?= $_SESSION['dictionary'][LG]["Area"][2] ?></option>
					<option value="Biomeccania"><?= $_SESSION['dictionary'][LG]["Area"][3] ?></option>
					<option value="Cinema"><?= $_SESSION['dictionary'][LG]["Area"][4] ?></option>
					<option value="Civile"><?= $_SESSION['dictionary'][LG]["Area"][5] ?></option>
					<option value="Elettrica"><?= $_SESSION['dictionary'][LG]["Area"][6] ?></option>
					<option value="Elettronica"><?= $_SESSION['dictionary'][LG]["Area"][7] ?></option>
					<option value="Energetica"><?= $_SESSION['dictionary'][LG]["Area"][8] ?></option>
					<option value="Fisica"><?= $_SESSION['dictionary'][LG]["Area"][9] ?></option>
					<option value="Gestionale"><?= $_SESSION['dictionary'][LG]["Area"][10] ?></option>
					<option value="Informatica"><?= $_SESSION['dictionary'][LG]["Area"][11] ?></option>
					<option value="Matematica"><?= $_SESSION['dictionary'][LG]["Area"][12] ?></option>
					<option value="Materiali"><?= $_SESSION['dictionary'][LG]["Area"][13] ?></option>
					<option value="Meccanica"><?= $_SESSION['dictionary'][LG]["Area"][14] ?></option>
					<option value="Telecomunicazioni"><?= $_SESSION['dictionary'][LG]["Area"][15] ?></option>
				</select>
				<?php $dataBean = new MDataBean("Area", null, KEYWORD); ?>
				<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($dataBean)); ?>">
<<<<<<< HEAD
				<br />
				<li data-role="list-divider" data-theme='b'><?= $_SESSION['dictionary'][LG]["ontology2"] ?></li>
				<br />
				<!-- Categoria -->
				<select name="Categoria">
					<option value=></option>
=======
				
				<!-- Categoria -->
				<select name="Categoria">
					<option value=""><?= $_SESSION['dictionary'][LG]["ontology2"] ?></option>
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
					<option value="Stage"><?= $_SESSION['dictionary'][LG]["Categoria"][0] ?></option>
					<option value="Job"><?= $_SESSION['dictionary'][LG]["Categoria"][1] ?></option>
					<option value="Tesi"><?= $_SESSION['dictionary'][LG]["Categoria"][2] ?></option>
					<option value="Appunti"><?= $_SESSION['dictionary'][LG]["Categoria"][3] ?></option>
				</select>
				<?php $dataBean = new MDataBean("Categoria", null, KEYWORD); ?>
				<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($dataBean)); ?>">
<<<<<<< HEAD
				<br />
				</ul>
				<br />
		
				<div data-role="collapsible" data-theme='a'>
					 <h3><?= $_SESSION['dictionary'][LG]["ontology0"] ?></h3>
					<!-- Titolo -->
					<input type="hidden" name="ontology0" value="">
=======
				
				<div data-role="collapsible">
					 <h3><?= $_SESSION['dictionary'][LG]["ontology0"] ?></h3>
					<!-- Titolo -->
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
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
