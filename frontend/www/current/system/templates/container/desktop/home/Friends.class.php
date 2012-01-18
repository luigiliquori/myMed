<?php

require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Friends extends AbstractTemplate {
	/* --------------------------------------------------------- */
	/* Attribute */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("notification", "notification");
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div style="background-color: #1d1d1d; color: white; width: 184px; font-size: 15px; font-weight: bold;">
			Contacts
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { }
		
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() {?>
		<!-- CONTENT -->
		<div id="buddyList" style="position:relative; width: 200px; height:100px; overflow:hidden; top:2px;">
			<?php $i=0; ?>
				<?php foreach ($_SESSION['friends'] as $friend ) { ?>
					<a href="<?= $friend["link"] ?>"><img src="http://graph.facebook.com/<?= $friend["id"] ?>/picture" width="20px" alt="<?= $friend["name"] ?>" /></a>
				<?php $i++; ?>
			<?php } ?>
			<br /><br />
			<a id="buddyListHideButton" href="#" onClick="hideOverflow('buddyList')" style="display: none;">Masquer ›</a>
		</div>
		<br />
		<a id="buddyListShowButton" href="#" onClick="displayOverflow('buddyList')">Tout afficher ›</a>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div style="position: absolute; left: 71%; top:210px;">
			<?php 
			$this->getHeader();
			$this->getContent();
			$this->getFooter();
			?>
		</div>
	<?php }
}
?>


