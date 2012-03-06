<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class FindView extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*List<String>*/ $channelList;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Find");
		
		// GET THE CURRENT LIST OF CHANNEL (for each category)
		foreach($this->channelCategory as $category) {
			
			$request = new Request("FindRequestHandler", READ);
			$request->addArgument("application", APPLICATION_NAME);
			$request->addArgument("predicate", "Category(".$category.")");
			$responsejSon = $request->send();
			$responseObject = json_decode($responsejSon);
				
			// get results
			$i = 0;
			if($responseObject->status == 200) {
				foreach(json_decode($responseObject->data->results) as $result) {
					
					$request = new Request("FindRequestHandler", READ);
					$request->addArgument("application", APPLICATION_NAME);
					$request->addArgument("predicate", $result->predicate);
					$request->addArgument("user", $result->publisherID);
					$responsejSon = $request->send();
					$responseObject = json_decode($responsejSon);
					
					if($responseObject->status == 200) {
						foreach(json_decode($responseObject->data->details) as $details) {
							if($details->key == "Name") { 
								$this->channelList[$category][$i]["title"] = $details->value;
							}
							if($details->key == "Description") {
								$this->channelList[$category][$i]["description"] = $details->value;
							}
						}
					} else {
// 						echo '<script type="text/javascript">alert(\'Details fail: ' . $responsejSon . '\');</script>';
					}
					$i++;
				}
			} else {
// 				echo '<script type="text/javascript">alert(\'Normal Fail: ' . $responsejSon . '\');</script>';
			}
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
		<div class="content">
			<ul data-role="listview" data-filter="true" data-theme="b">
			  <?php foreach($this->channelCategory as $category) { ?>
				  <li data-role="list-divider" style="position: relative; width: 100%; left:-10px; top:-15px;"><?= $category ?></li>
				  <?php if(isset($this->channelList[$category])) { ?>
					  <?php foreach($this->channelList[$category] as $channel) { ?>
					  		<li data-theme="c" style="position: relative; width: 103%; left:-10px; top:-15px;">
					  			<a href="?application=<?= APPLICATION_NAME ?>&channel=<?= $channel["title"] ?>&category=<?= $category ?>" style="position: relative; left: 0px;" rel="external"><?= $channel["title"] ?></a>
					  			<span Style="position: relative; top:-20px; left: 10px; font-size: 9pt; font-weight: lighter;"><?= $channel["description"] ?></span>
					  		</li>
					  <?php } ?>
				  <?php } ?>
			  <?php } ?>
			</ul>

		</div>
	<?php }
	
}
?>