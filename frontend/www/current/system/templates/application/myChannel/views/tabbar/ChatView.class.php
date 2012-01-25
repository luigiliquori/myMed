<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class ChatView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $category = null;
	private /*String*/ $channel = null;
	private /*Array*/ $quotes = array();
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Chat");
		if(isset($_GET['category']) && isset($_GET['channel'])) {
			$this->category = $_GET['category'];
			$this->channel = $_GET['channel'];
			echo '<script type="text/javascript">startChat("' . 
			BACKEND_URL . '", "' .
			APPLICATION_NAME . '", "' .
			$_GET['category'] . '", "' . 
			$_GET['channel'] . '", "' .
			$_SESSION['accessToken'] . 
			'");</script>';
		}
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function setCategory($category){
		$this->category = $category;
	}
	
	public /*void*/ function setChannel($channel){
		$this->channel = $channel;
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" Style="text-align: left;">
		
			<!-- TEXT -->
			<?php if(CONNECTED) { ?>
				
				<h3><?= $this->channel ?></h3>
				<div id="chatTextArea" Style="position: relative; width: 100%; border: thin white solid; background-color: black; font-size:10pt;">
					<div id="chatTextAreaContent" Style="position: relative; width: 90%; padding: 10px;"></div>
				</div>
 				
 				<br />
				
				<form name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data" target=""> 
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="publish" />
					<input type="hidden" name="numberOfOntology" value="4" />
					
					<!-- QUOTE -->
					<textarea id="chatInsertText"/></textarea> 
					<input id="chatInsertTextFormated" type="hidden" name="data" value="" />
					<?php $keyword = new MDataBean("data", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
					
					<!-- CATEGORY -->
					<input type="hidden" name="Category" value="<?= $this->category ?>" />
					<?php $keyword2 = new MDataBean("Category", null, KEYWORD); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($keyword2)); ?>">
					
					<!-- CHANNEL  -->
					<input type="hidden" name="Channel" value="<?= $this->channel ?>" />
					<?php $keyword3 = new MDataBean("Channel", null, KEYWORD); ?>
					<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($keyword3)); ?>">
					
					<!-- DATE  -->
					<input id="getDate" type="hidden" name="begin" value="" />
					<?php $date = new MDataBean("begin", null, DATE); ?>
					<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($date)); ?>">
					
					<a href="#" data-role="button" onclick="submitNewQuote('<?= APPLICATION_NAME ?>PublishForm')">Publish</a>
				</form>
			<?php } else { ?>
				<h3>No channel selected: <a href="#Find">Please choose one</a></h3>
				<div Style="position: relative; width: 100%; height: 400px; border: thin white solid; background-color: black;"></div>
				<br />
				<input id="quote" type="text" name="Quote" value="please select a channel..." Style="color: gray;" readonly="readonly"/>
				<a href="#" onclick="alert('select a channel first!')" data-role="button">Publish</a>
			<?php } ?>
		
			
		</div>
	<?php }
	
}
?>