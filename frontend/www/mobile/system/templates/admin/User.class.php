<?php

require_once dirname(__FILE__).'/Admin.class.php';
require_once dirname(__FILE__).'/handler/UserHandler.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class User extends Admin {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*UserHandler*/ $userHandler;
	private /*ApplicationHandler*/ $applicationHandler;
	private /*AdvancedHandler*/ $advancedHandler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("User");
		$this->userHandler = new UserHandler();
		$this->userHandler->handleRequest();
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
			<select id="selectUserAdmin" onchange="cleanView(); fadeIn('#'+document.getElementById('selectUserAdmin').value);">
				<option value="adminUserRead">READ</option>
				<option value="adminUserUpdate">UPDATE</option>
				<option value="adminUserDelete">DELETE</option>
			</select>
			<br />
			<div id="adminUserRead">
				<form action="#" method="post" name="userReadForm" id="userReadForm">
					<input type="hidden" name="userRead" value="1" />
					id: <input type="text" name="id" />
					<br />
					<a href="#" data-role="button" onclick="document.userReadForm.submit()">Send</a>
				</form>
			</div>
			<div id="adminUserUpdate" style="display: none;">
				<form action="#" method="post" name="userUpdateForm" id="userUpdateForm">
					<input type="hidden" name="userUpdate" value="1" />
					jSon User: <textarea name="user"></textarea>
					<br />
					<a href="#" data-role="button" onclick="document.userUpdateForm.submit()">Send</a>
				</form>
			</div>
			<div id="adminUserDelete" style="display: none;">
				<form action="#" method="post" name="userDeleteForm" id="userDeleteForm">
					<input type="hidden" name="userDelete" value="1" />
					id: <input type="text" name="id" />
					<br />
					<a href="#" data-role="button" onclick="document.userDeleteForm.submit()">Send</a>
				</form>
			</div>
			<br />
			<!-- RESPONSE -->
			<?php if($this->userHandler->getError()) { ?>
				<div style="color: red;">
					<?= $this->userHandler->getError(); ?>
				</div>
			<?php } else if($this->userHandler->getSuccess()) { ?>
				<div style="color: #12ff00;">
					<?= $this->userHandler->getSuccess(); ?>
				</div>
			<?php } ?>
			
		</div>
	<?php }
	
}
?>