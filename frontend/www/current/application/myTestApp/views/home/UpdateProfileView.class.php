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
		
		/*
		 * If the myMem profile doesn't exist yet, create a blank one. (Easier to handle)
		 */
		if (!isset($_SESSION['myMem_profile'])){
			$_SESSION['myMem_profile'] = array();
			$_SESSION['myMem_profile']['angelName'] = "";
			$_SESSION['myMem_profile']['angelEmail'] = "";
			$_SESSION['myMem_profile']['diseaseLevel'] = "";
			$_SESSION['myMem_profile']['meds'] = "";
			$_SESSION['myMem_profile']['callOrder'] = array();
			$_SESSION['myMem_profile']['acceptedGeoLoc'] = false;
		}
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { 
		?>
		<div data-role="content" style="padding: 10px;" data-theme="c">
			<a href="#ProfileView" data-role="button" data-direction="reverse" data-inline="true"><?= $_SESSION['dictionary'][LG]["back"] ?></a><br /><br />
			
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
				<!-- <input type="hidden" name="application" value="myTestApp" /> -->
				<!-- MyALZHEIMER SPECIFIC PROFILE FORM -->

				<h1><?= $_SESSION['dictionary'][LG]["myMemProfile"] ?></h1>
				<span><?= $_SESSION['dictionary'][LG]["angelName"] ?> : </span><input type="text" name="angelName" value="<?= $_SESSION['myMem_profile']['angelName'] ?>" />
				<span><?= $_SESSION['dictionary'][LG]["angelEmail"] ?> : </span><input type="text" name="angelEmail" value="<?= $_SESSION['myMem_profile']['angelEmail'] ?>" />
				
				<span><?= $_SESSION['dictionary'][LG]["diseaseLevel"] ?> : </span>
				<select name="diseaseLevel">
					<option value="low" <? if($_SESSION['myMem_profile']['diseaseLevel'] == 'low') echo 'selected=selected' ?> ><?= $_SESSION['dictionary'][LG]["diseaseLevel_low"] ?></option>
					<option value="moderate" <? if($_SESSION['myMem_profile']['diseaseLevel'] == 'moderate') echo 'selected=selected' ?> ><?= $_SESSION['dictionary'][LG]["diseaseLevel_med"] ?></option>
					<option value="high" <? if($_SESSION['myMem_profile']['diseaseLevel'] == 'high') echo 'selected=selected' ?> ><?= $_SESSION['dictionary'][LG]["diseaseLevel_high"] ?></option>
				</select>
				<span><?= $_SESSION['dictionary'][LG]["meds"] ?> : </span><textarea name="meds"><?= $_SESSION['myMem_profile']['meds'] ?></textarea><br />
				<span><?= $_SESSION['dictionary'][LG]["callOrder"] ?> : </span><textarea name="callOrder" ></textarea><br />
					<input id="service-term" type="checkbox" name="checkCondition" style="position: absolute; top: 13px;" <?php if ($_SESSION['myMem_profile']['acceptedGeoLoc']) echo 'checked=checked';?>/>
				<span style="position: relative; left: 50px;"><?= $_SESSION['dictionary'][LG]["acceptGeoLoc"] ?></span><br />

				<br />
				<br />
				<!-- MYMED GENERIC PROFILE FORM-->
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
