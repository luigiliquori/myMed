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

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
<<<<<<< HEAD

class PublishView extends MainView {


=======
class PublishView extends MainView {
	
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
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
			<span><?= $_SESSION['dictionary'][LG]["pleaseLogin"] ?></span>
			<?php return ?>
		<?php } ?>
<<<<<<< HEAD
=======
	
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
		<form  action="#PublishView" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>_ADMIN" />
			<input type="hidden" name="method" value="publish" />
			<input type="hidden" name="numberOfOntology" value="5" />
<<<<<<< HEAD
			<br />
			<br />
			<ul data-role="listview"  data-theme="c" data-divider-theme="b" >
			<!-- Titolo -->
			<li data-role="list-divider" data-theme='b'><?= $_SESSION['dictionary'][LG]["ontology0"] ?></li>
			<br />
=======
			
			<!-- Titolo -->
			<span><?= $_SESSION['dictionary'][LG]["ontology0"] ?> :</span>
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
			<input type="text" name="data" value="" />
			<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
			<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
<<<<<<< HEAD

				<!-- Area -->
				<li data-role="list-divider" data-theme='b'><?= $_SESSION['dictionary'][LG]["ontology1"] ?></li>
				<br />
				<select name="Area">
=======
			
				<!-- Area -->
				<span><?= $_SESSION['dictionary'][LG]["ontology1"] ?> :</span>
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
				<br />
				
				<!-- Categoria -->
<<<<<<< HEAD
				<li data-role="list-divider" data-theme='b'><?= $_SESSION['dictionary'][LG]["ontology2"] ?></li>
				<br />
				<select name="Categoria">
=======
				<span><?= $_SESSION['dictionary'][LG]["ontology2"] ?> :</span>
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
				<br />
				<br />
			
			<!-- TEXT -->
<<<<<<< HEAD
			<li data-role="list-divider" data-theme='b'><?= $_SESSION['dictionary'][LG]["ontology4"] ?></li>
			<br />

			<textarea id="CLEeditor" name="text"></textarea>

			<?php $dataBean = new MDataBean("text", null, TEXT); ?>
			<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			</ul>

=======
			<span><?= $_SESSION['dictionary'][LG]["ontology4"] ?> :</span>
			<textarea id="CLEeditor" name="text"></textarea>
			<?php $dataBean = new MDataBean("text", null, TEXT); ?>
			<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
			<!-- DATE  -->
			<input type="hidden" name="begin" value="<?= date("d/m/Y") . " - " . date("H:i:s") ?>" />
			<?php $date = new MDataBean("begin", null, DATE); ?>
			<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($date)); ?>">
<<<<<<< HEAD
=======
			
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
			<center>
			<a href="#" data-role="button" data-inline="true" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" ><?= $_SESSION['dictionary'][LG]["view3"] ?></a>
			</center>
		</form>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getResultContent() {
<<<<<<< HEAD
		$text=$_POST['text'];
		$data=$_POST['data'];
		?>
		<br />
		<a href="#" data-role="button" data-direction="reverse" data-inline="true" onclick="window.location.reload();"><?= $_SESSION['dictionary'][LG]["back"] ?></a><br /><br />
		<?php
		if($data=="" || $text==""){
				echo $_SESSION['dictionary'][LG]["IncompleteSend"];
		}else{
			if($succes = $this->handler->getSuccess()) { 
				echo $_SESSION['dictionary'][LG]["requestSent"];
			} else {
				echo $this->handler->getError();
			}
=======
		if($succes = $this->handler->getSuccess()) { 
			echo $_SESSION['dictionary'][LG]["requestSent"];
		} else {
			echo $this->handler->getError();
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
		}
	}
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" id="content" style="padding: 10px;" data-theme="c">';
<<<<<<< HEAD
		if(isset($_POST['method']) && $_POST['method'] == "publish" && $_SESSION['p']=="ok" && ($this->handler->getError() || $this->handler->getSuccess())) {
			$_SESSION['p']="ko";
			$this->getResultContent();
		} else {
			$this->getPublishContent();
			$_SESSION['p']="ok";
=======
		if(isset($_POST['method']) && $_POST['method'] == "publish" && ($this->handler->getError() || $this->handler->getSuccess())) {
			$this->getResultContent();
		} else {
			$this->getPublishContent();
>>>>>>> 47afa5c5725a71eb2e2fbaac0726ec72919c747c
		}
		echo '</div>';
	}
	
}
?>
