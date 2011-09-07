<?php

require_once dirname(__FILE__).'/Admin.class.php';
require_once dirname(__FILE__).'/handler/AdvancedHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Advanced extends Admin {
	
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
		parent::__construct("Advanced");
		$this->advancedHandler = new AdvancedHandler();
		$this->advancedHandler->handleRequest();
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
			<form action="#" method="post" name="advancedForm" id="applicationReadForm">
				<input type="hidden" name="advanced" value="1" />
				Handler: 
				<select id="selectAdvancedAdmin" onchange="cleanView(); fadeIn('#'+document.getElementById('selectAdvancedAdmin').value);">
					<option value="ProfileRequestHandler">ProfileRequestHandler</option>
					<option value="AuthenticationRequestHandler">AuthenticationRequestHandler</option>
					<option value="SessionRequestHandler">SessionRequestHandler</option>
					<option value="DHTRequestHandler">DHTRequestHandler</option>
					<option value="DHTRequestHandler">ApplicationRequestHandler</option>
				</select>
				<br />
				Action: 
				<select id="selectAdvancedAdmin" onchange="cleanView(); fadeIn('#'+document.getElementById('selectAdvancedAdmin').value);">
					<option value="adminAdvancedRead">CREATE</option>
					<option value="adminAdvancedRead">READ</option>
					<option value="adminAdvancedUpdate">UPDATE</option>
					<option value="adminAdvancedDelete">DELETE</option>
				</select>
				<br /><br />
				<div>
					Argument 1: 
					<br />
					<input type="text" data-inline="true"/>, <input type="text" data-inline="true"/>
					<br />
					<a href="#" data-role="button" onclick="" data-inline="true">Add</a>
					<br /><br />
					<a href="#" data-role="button" onclick="document.inscriptionForm.submit()">Send</a>
				</div>
			</form>
		</div>
	<?php }
	
}
?>