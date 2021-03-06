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
class SubscribeView extends MainView {
	
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
		parent::__construct("SubscribeView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	
	/**
	* 
	*/
	private /*String*/ function getSubscribeContent() { 
	
		
		$subFr = false;
		$subIt = false;
		$subCom = false;
		
		$request = new Request("SubscribeRequestHandler", READ);
		$request->addArgument("application", APPLICATION_NAME."_ADMIN");
		$request->addArgument("userID", $_SESSION['user']->id);
		
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			$res = (array) $responseObject->dataObject->subscriptions;
			foreach( $res as $i => $value ){
				if (strrpos($i, "Linguafrancese") !== false){
					$subFr = true;
				} else if (strrpos($i, "Linguaitaliano") !== false){
					$subIt = true;
				} else if (strrpos($i, "commentGroup") !== false){
					$subCom = true;
				}
			}
		}
		
		?>

		<form action="" method="post" name="getSubscribeForm1">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Lingua", "italiano", KEYWORD))); ?>" type="hidden" >
		</form>
		<form action="" method="post" name="getSubscribeForm2">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("Lingua", "francese", KEYWORD))); ?>" type="hidden" >
		</form>
		

		<form action="" method="post" name="getSubscribeForm3">
			<input name="application" value="<?= APPLICATION_NAME."_ADMIN" ?>" type="hidden" />
			<input name="method" value="subscribe" type="hidden" />
			<input name="numberOfOntology" value="1" type="hidden"/>
			<input name="ontology0" value="<?= urlencode(json_encode(new MDataBean("commentGroup", APPLICATION_NAME, KEYWORD))); ?>" type="hidden" >
		</form>
		<div style="width:700px;margin-left:auto; margin-right:auto;">
			<a <?= $subIt?"class='ui-disabled'":"" ?> type="button" data-inline="true" data-theme="e" href="#" onclick="document.getSubscribeForm1.submit();">Sottoscrivere testi (it)</a>
			<a <?= $subFr?"class='ui-disabled'":"" ?> type="button" data-inline="true" data-theme="e" href="#" onclick="document.getSubscribeForm2.submit();">Sottoscrivere testi (fr)</a>
			<a <?= $subCom?"class='ui-disabled'":"" ?> type="button" data-inline="true" data-theme="e" href="#" onclick="document.getSubscribeForm3.submit();" >Sottoscrivere commenti</a>
		</div>
		
	<?php }
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		echo '<div data-role="content" style="padding: 10px;" data-theme="c">';
			$this->getSubscribeContent();
		echo '</div>';
	}
}
?>
