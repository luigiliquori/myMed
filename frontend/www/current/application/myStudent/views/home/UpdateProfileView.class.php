<?php

require_once '../../lib/dasp/beans/MDataBean.class.php';
require_once '../../lib/dasp/request/Request.class.php';
require_once '../../lib/dasp/request/Reputation.class.php';
require_once 'controller/UpdateProfileHandler.class.php';	

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class UpdateProfileView extends MainView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $updateHandler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		$this->updateHandler = new UpdateProfileHandler();
		parent::__construct("UpdateProfileView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div data-role="content" style="padding: 10px;" data-theme="c">
			<a href="#ProfileView" data-role="button" data-direction="reverse" data-inline="true"><?= $_SESSION['dictionary'][IT]["back"] ?></a><br /><br />
			
			<!-- NOTIFICATION -->
			<?php if(($error = $this->updateHandler->getError())) { ?>
				<div id="loginError" style="color: red;">
					<?= $error; ?>
				</div>
			<?php } else if(isset($_SESSION['error'])) { ?>
				<div id="loginError" style="color: red;">
					<?= $_SESSION['error']; ?>
					<?php $_SESSION['error'] = null; ?>
				</div>
			<?php } else if($success = $this->updateHandler->getSuccess()) { ?>
				<div style="color: #12ff00;">
					<?= $success ?>
				</div>
			<?php } ?>
			
			<form action="#UpdateProfileView" method="post" name="updateForm" id="updateForm">
				<input type="hidden" name="update" value="1" />
				<span><?= $_SESSION['dictionary'][LG]["firstName"] ?> : </span><input type="text" name="prenom" value="<?= $_SESSION['user']->firstName ?>" /><br />
				<span><?= $_SESSION['dictionary'][LG]["name"] ?> : </span><input type="text" name="nom" value="<?= $_SESSION['user']->lastName ?>" /><br />
				<span>eMail : </span><input type="text" name="email" value="<?= $_SESSION['user']->email ?>" /><br />
				<span><?= $_SESSION['dictionary'][LG]["oldPassword"] ?> : </span><input type="password" name="oldPassword" /><br />
				<span><?= $_SESSION['dictionary'][LG]["password"] ?> : </span><input type="password" name="password" /><br />
				<span><?= $_SESSION['dictionary'][LG]["confirm"] ?> : </span><input type="password" name="confirm" /><br />
				<span><?= $_SESSION['dictionary'][LG]["birthday"] ?> : </span><input name="birthday" id="birthday" type="date" value="<?= $_SESSION['user']->birthday ?>" data-role="datebox"  data-options='{"mode": "slidebox"}' readonly="readonly"><br />
			    <span><?= $_SESSION['dictionary'][LG]["profilePicture"] ?> : </span><input type="text" name="thumbnail" value="<?= $_SESSION['user']->profilePicture ?>" /><br />
				<center>
				<a data-role="button" onclick="document.updateForm.submit()" data-theme="g" data-inline="true"><?= $_SESSION['dictionary'][LG]["validate"] ?></a>
				</center>
			</form>
		</div>
	<?php }
}
?>
