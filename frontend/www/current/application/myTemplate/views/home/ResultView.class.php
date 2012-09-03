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
class ResultView extends MainView {
	
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
		parent::__construct("ResultView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div data-role="content" id="content" style="padding: 10px;" data-theme="c">
		
			<a href="#FindView" data-role="button" data-direction="reverse" data-inline="true"><?= $_SESSION['dictionary'][LG]["back"] ?></a><br /><br />
			
			<?php if(isset($_POST['method']) && $_POST['method'] == "find" && $this->handler->getError()) { ?>
				<h3><?= $_SESSION['dictionary'][IT]["noResult"] ?></h3>
			<?php } else { ?>
				<ul data-role="listview" data-filter="true" data-theme="c" data-dividertheme="a" >
					<?php
					if(isset($_POST['method']) && $_POST['method'] == "find" &&  $this->handler->getSuccess()) {
						$results = json_decode($this->handler->getSuccess());
						$_SESSION[APPLICATION_NAME]['results'] = $results;
					} else {
						$results = $_SESSION[APPLICATION_NAME]['results'];
					}
					$i=0;
					foreach($results as $result) { ?>
						<li>
							<!-- RESULT DETAILS -->
							<form action="#DetailView" method="post" name="getDetailForm<?= $i ?>">
								<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
								<input type="hidden" name="method" value="getDetail" />
								<input type="hidden" name="user" value="<?= $result->publisherID ?>" />
								<input type="hidden" name="predicate" value="<?= $result->predicate ?>" />
							</form>
							<a href="#" onclick="document.getDetailForm<?= $i ?>.submit()">
								<?php 
									$pos = strpos($result->predicate, "Linguaitaliano");
									if($pos === false){ ?>
										<img alt="fr" src="img/FR_Flag.png" height="11px;" class="ui-li-icon ui-corner-none" />
									<?php }else{ ?>
										<img alt="it" src="img/IT_Flag.png" height="11px;" class="ui-li-icon ui-corner-none" />
									<?php }
								?>
								<span><?= $result->data ?></span>
								
							</a>
						</li>
						<?php $i++ ?>
					<?php } ?>
				</ul>
			<?php } ?>
			<br /><br />
		</div>
	<?php }
}
?>
