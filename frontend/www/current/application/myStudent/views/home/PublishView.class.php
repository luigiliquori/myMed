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
			<span><?= $_SESSION['dictionary'][LG]["pleaseLogin"] ?></span>
			<?php return ?>
		<?php } ?>
	
		<form  action="#PublishView" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
			<!-- Define the method to call -->
			<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>_ADMIN" />
			<input type="hidden" name="method" value="publish" />
			<input type="hidden" name="numberOfOntology" value="5" />
			
			<!-- Titolo -->
			<span><?= $_SESSION['dictionary'][LG]["ontology0"] ?> :</span>
			<input type="text" name="data" value="" />
			<?php $dataBean = new MDataBean("data", null, KEYWORD); ?>
			<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
				<!-- Area -->
				<span><?= $_SESSION['dictionary'][LG]["ontology1"] ?> :</span>
				<select name="Area">
					<option value=""><?= $_SESSION['dictionary'][LG]["ontology1"] ?></option>
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
				<span><?= $_SESSION['dictionary'][LG]["ontology2"] ?> :</span>
				<select name="Categoria">
					<option value=""><?= $_SESSION['dictionary'][LG]["ontology2"] ?></option>
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
			<span><?= $_SESSION['dictionary'][LG]["ontology4"] ?> :</span>
			<textarea id="CLEeditor" name="text"></textarea>
			<?php $dataBean = new MDataBean("text", null, TEXT); ?>
			<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($dataBean)); ?>">
			<br />
			
			<!-- DATE  -->
			<input type="hidden" name="begin" value="<?= date("d/m/Y") . " - " . date("H:i:s") ?>" />
			<?php $date = new MDataBean("begin", null, DATE); ?>
			<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($date)); ?>">
			
			<center>
			<a href="#" data-role="button" data-inline="true" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" ><?= $_SESSION['dictionary'][LG]["view3"] ?></a>
			</center>
		</form>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	private /*String*/ function getResultContent() {
		if($succes = $this->handler->getSuccess()) { 
			echo $_SESSION['dictionary'][LG]["requestSent"];
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
