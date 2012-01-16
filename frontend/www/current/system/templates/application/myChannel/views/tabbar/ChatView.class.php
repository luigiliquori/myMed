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
			$this->refreshQuote();
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
	
	/**
	 * Refresh the chat
	 */
	public function refreshQuote() {
		$request = new Request("FindRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME);
		$request->addArgument("predicate", "Category(" . $this->category . ")Channel(" . $this->channel . ")");
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		
		// get results
		if($responseObject->status == 200) {
			
			foreach(json_decode($responseObject->data->results) as $result) {
					
				$request = new Request("FindRequestHandler", READ);
				$request->addArgument("application", APPLICATION_NAME);
				$request->addArgument("predicate", $result->predicate);
				$request->addArgument("user", $result->user);
				$responsejSon = $request->send();
				$responseObject = json_decode($responsejSon);
					
				if($responseObject->status == 200) {
					$time = 0;
					$userName = "unknown";
					$date = "unknown";
					foreach(json_decode($responseObject->data->details) as $details) {
						if($details->key == "Quote") {
							$quote = $details->value;
						} else if ($details->key == "Time") {
							$time = $details->value;
						} else if ($details->key == "UserName") {
							$userName = $details->value;
						} else if ($details->key == "Date") {
							$date = $details->value;
						}
					} 
					$this->quotes[$time]['user'] = $userName;
					$this->quotes[$time]['date'] = $date;
					$this->quotes[$time]['value'] = $quote;
				} 
			}
			
			ksort($this->quotes);
		}
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
			<?php if($this->category != null && $this->channel != null) { ?>
				<h3><?= $this->channel ?></h3>
				<div id="chatTextArea" Style="position: relative; width: 100%; border: thin white solid; background-color: black; font-size:19pt;">
					<div Style="position: relative; width: 90%; padding: 10px;">
						<?php foreach ($this->quotes as $quote) { ?>
							<span Style="font-weight: bold; color: red; "><?= $quote['date'] ?></span> ::
							<span Style="font-weight: bold; color: green; "><?= $quote['user'] ?></span> :
							<?= $quote['value'] ?><br />
						<?php } ?>
					</div>
				</div>
				<br />
				<form  action="#" method="post" name="<?= APPLICATION_NAME ?>PublishForm" id="<?= APPLICATION_NAME ?>PublishForm" enctype="multipart/form-data">
					<!-- Define the method to call -->
					<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
					<input type="hidden" name="method" value="publish" />
					<input type="hidden" name="numberOfOntology" value="6" />
					
					<!-- KEYWORD -->
					<input type="text" name="Quote" value="" />
					<?php $keyword = new MDataBean("Quote", null, KEYWORD); ?>
					<input type="hidden" name="ontology0" value="<?= urlencode(json_encode($keyword)); ?>">
					
					<!-- CATEGORY -->
					<input type="hidden" name="Category" value="<?= $this->category ?>" />
					<?php $keyword2 = new MDataBean("Category", null, KEYWORD); ?>
					<input type="hidden" name="ontology1" value="<?= urlencode(json_encode($keyword2)); ?>">
					
					<!-- CHANNEL  -->
					<input type="hidden" name="Channel" value="<?= $this->channel ?>" />
					<?php $keyword3 = new MDataBean("Channel", null, KEYWORD); ?>
					<input type="hidden" name="ontology2" value="<?= urlencode(json_encode($keyword3)); ?>">
					
					<!-- TIMESTAMP  -->
					<input type="hidden" name="Time" value="<?= microtime(true) ?>" />
					<?php $time = new MDataBean("Time", null, DATE); ?>
					<input type="hidden" name="ontology3" value="<?= urlencode(json_encode($time)); ?>">
					
					<!-- USERNAME  -->
					<input type="hidden" name="UserName" value="<?= $_SESSION['user']->name ?>" />
					<?php $keyword4 = new MDataBean("UserName", null, KEYWORD); ?>
					<input type="hidden" name="ontology4" value="<?= urlencode(json_encode($keyword4)); ?>">
					
					<!-- DATE  -->
					<input type="hidden" name="Date" value="<?= date("F j, Y, g:i a"); ?>" />
					<?php $date = new MDataBean("Date", null, DATE); ?>
					<input type="hidden" name="ontology5" value="<?= urlencode(json_encode($date)); ?>">
					
					<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>PublishForm.submit()" >Publish</a>
				</form>
			<?php } else { ?>
				<h3>No channel selected: <a href="#Find">Please choose one</a></h3>
				<div Style="position: relative; width: 100%; height: 400px; border: thin white solid; background-color: black;"></div>
				<br />
				<input type="text" name=Quote value="" />
				<a href="#" onclick="alert('select a channel first!')" data-role="button">Publish</a>
			<?php } ?>
		
			
		</div>
	<?php }
	
}
?>