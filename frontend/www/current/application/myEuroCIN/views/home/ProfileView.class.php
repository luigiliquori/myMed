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
class ProfileView extends MainView {
	
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
			<?php if(USER_CONNECTED && $_SESSION['user']->id != VISITOR_ID) {?>
				<h3>Profile</h3>
				<?php if($_SESSION['user']->profilePicture != "") { ?>
					<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="100">
				<?php } else { ?>
					<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="100">
				<?php } ?>
				<br />
				Prenom: <?= $_SESSION['user']->firstName ?><br />
				Nom: <?= $_SESSION['user']->lastName ?><br />
				Date de naissance: <?= $_SESSION['user']->birthday ?><br />
				eMail: <?= $_SESSION['user']->email ?><br />
				<div data-role="controlgroup" data-type="horizontal">
					<a href="#inscription" data-role="button" data-inline="true" data-theme="c" data-icon="refresh">mise à jour</a>
					<a href="#login" onclick="document.disconnectForm.submit()" rel="external" data-role="button" data-theme="r">Deconnexion</a>
				</div>
			<?php } else { ?>
				<form action="#ProfileView" method="post" name="singinForm" id="singinForm" Style="text-align: center;">
					<h3><?= APPLICATION_NAME ?></h3>
					<!-- NOTIFICATION -->
					<?php if($this->handler->getError()) { ?>
						<div id="loginError" style="color: red;">
							<?= $this->handler->getError(); ?>
						</div>
					<?php } else if(isset($_SESSION['error'])) { ?>
						<div id="loginError" style="color: red;">
							<?= $_SESSION['error']; ?>
							<?php $_SESSION['error'] = null; ?>
						</div>
					<?php } else if($this->handler->getSuccess()) { ?>
						<div style="color: #12ff00;">
							<?= $this->handler->getSuccess(); ?>
						</div>
					<?php } ?>
					<input type="hidden" name="singin" value="1" />
					<input type="text" name="login" id="login" value="email"  data-theme="c"/><br />
					<input type="password" name="password" id="password" value="Mot de passe"  data-theme="c"/><br />
					<a href="#InscriptionView" data-role="button" data-inline="true" data-theme="c">Inscription</a>
					<a href="#" onclick="document.singinForm.submit()" data-role="button" data-inline="true" data-theme="c">Connexion</a>
				</form>
			<?php } ?>
		</div>
	<?php }
}
?>
