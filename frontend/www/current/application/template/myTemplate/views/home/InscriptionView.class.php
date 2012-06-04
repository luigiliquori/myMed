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
class InscriptionView extends MainView {
	
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
		parent::__construct("InscriptionView");
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
			<form action="#ProfileView" method="post" name="inscriptionForm" id="inscriptionForm">
				<input type="hidden" name="inscription" value="1" />
				<span><?= $_SESSION['dictionary'][LG]["firstName"] ?> : </span><input type="text" name="prenom" value="" /><br />
				<span><?= $_SESSION['dictionary'][LG]["name"] ?> : </span><input type="text" name="nom" value="" /><br />
				<span>eMail : </span><input type="text" name="email" value="" /><br />
				<span><?= $_SESSION['dictionary'][LG]["password"] ?> : </span><input type="password" name="password" /><br />
				<span><?= $_SESSION['dictionary'][LG]["confirm"] ?> : </span><input type="password" name="confirm" /><br />
				<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;"/>
				<span style="position: relative; left: 50px;">
					<?= $_SESSION['dictionary'][LG]["accept"] ?>
					<a href="doc/CONDITIONS_GENERALES_MyMed_version1_FR.pdf" rel="external"><?= $_SESSION['dictionary'][LG]["condition"] ?></a>
				</span><br />
				<center>
				<a data-role="button" onclick="document.inscriptionForm.submit()" data-theme="g" data-inline="true"><?= $_SESSION['dictionary'][LG]["validate"] ?></a>
				</center>
			</form>
		</div>
	<?php }
}
?>
