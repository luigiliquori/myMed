<?php

require_once '../../../lib/dasp/beans/MDataBean.class.php';
require_once '../../../lib/dasp/request/Request.class.php';
require_once '../../../lib/dasp/request/Reputation.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class ProfileView extends MainView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $loginHandler;
	private /*IRequestHandler*/ $inscriptionHandler;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($loginHandler, $inscriptionHandler) {
		$this->loginHandler = $loginHandler;
		$this->inscriptionHandler = $inscriptionHandler;
		parent::__construct("ProfileView");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<div data-role="content" style="padding: 10px;" data-theme="c">
			<?php if(USER_CONNECTED) {?>
				<h3><?= $_SESSION['dictionary'][LG]["view4"] ?></h3>
				<?php if($_SESSION['user']->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="100">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="100">
				<?php } ?>
				<br />
				<?= $_SESSION['dictionary'][LG]["firstName"]?>: <?= $_SESSION['user']->firstName ?><br />
				<?= $_SESSION['dictionary'][LG]["name"]?>: <?= $_SESSION['user']->lastName ?><br />
				<?= $_SESSION['dictionary'][LG]["birthday"]?>: <?= $_SESSION['user']->birthday ?><br />
				eMail: <?= $_SESSION['user']->email ?><br />
				<div data-role="controlgroup" data-type="horizontal">
					<a href="#UpdateProfileView" data-role="button" data-inline="true" data-theme="c" data-icon="refresh"><?= $_SESSION['dictionary'][LG]["update"] ?></a>
					<a href="#login" onclick="document.disconnectForm.submit()" rel="external" data-role="button" data-theme="r"><?= $_SESSION['dictionary'][LG]["disconnect"] ?></a>
				</div>
			<?php } else { ?>
				<form action="#ProfileView" method="post" name="signinForm" id="signinForm" Style="text-align: center;">
					<h3><?= APPLICATION_NAME ?></h3>
					
					<!-- NOTIFICATION -->
					<?php if(($error = $this->loginHandler->getError()) || ($error = $this->inscriptionHandler->getError())) { ?>
						<div id="loginError" style="color: red;">
							<?= $error; ?>
						</div>
					<?php } else if(isset($_SESSION['error'])) { ?>
						<div id="loginError" style="color: red;">
							<?= $_SESSION['error']; ?>
							<?php $_SESSION['error'] = null; ?>
						</div>
					<?php } else if($success = $this->inscriptionHandler->getSuccess()) { ?>
						<div style="color: #12ff00;">
							<?= $success ?>
						</div>
					<?php } ?>
					
					<input type="hidden" name="signin" value="1" />
					<input type="text" name="login" id="login" value="email"  data-theme="c"/><br />
					<input type="password" name="password" id="password" value="Mot de passe"  data-theme="c"/><br />
					<a href="#" onclick="document.signinForm.submit()" data-role="button" data-inline="true" data-theme="c"><?= $_SESSION['dictionary'][LG]["connect"] ?></a><br />
					<a href="#InscriptionView" data-inline="true" data-theme="c"><?= $_SESSION['dictionary'][LG]["inscription"] ?></a>
				</form>
			<?php } ?>
		</div>
	<?php }
}
?>
