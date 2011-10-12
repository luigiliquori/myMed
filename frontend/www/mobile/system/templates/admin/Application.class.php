<?php

require_once dirname(__FILE__).'/Admin.class.php';
require_once dirname(__FILE__).'/handler/ApplicationHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Application extends Admin {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Application");
		$this->applicationHandler = new ApplicationHandler();
		$this->applicationHandler->handleRequest();
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
			Action: 
			<select id="selectApplicationAdmin" onchange="cleanView(); fadeIn('#'+document.getElementById('selectApplicationAdmin').value);">
				<option value="adminApplicationRead">READ</option>
				<option value="adminApplicationUpdate">UPDATE</option>
				<option value="adminApplicationDelete">DELETE</option>
			</select>
			<br />
			<div id="adminApplicationRead">
				<form action="#" method="post" name="applicationReadForm" id="applicationReadForm">
					<input type="hidden" name="applicationRead" value="1" />
					id: <input type="text" />
					<br />
					<a href="#" data-role="button" onclick="document.inscriptionForm.submit()">Send</a>
				</form>
			</div>
			<div id="adminApplicationUpdate" style="display: none;">
				<form action="#" method="post" name="applicationReadForm" id="applicationReadForm">
					<input type="hidden" name="applicationRead" value="1" />
					jSon Application: <textarea></textarea>
					<br />
					<a href="#" data-role="button" onclick="document.inscriptionForm.submit()">Send</a>
				</form>
			</div>
			<div id="adminApplicationDelete" style="display: none;">
				<form action="#" method="post" name="applicationReadForm" id="applicationReadForm">
					<input type="hidden" name="applicationRead" value="1" />
					id: <input type="text" />
					<br />
					<a href="#" data-role="button" onclick="document.inscriptionForm.submit()">Send</a>
				</form>
			</div>
		</div>
	<?php }
	
}
?>