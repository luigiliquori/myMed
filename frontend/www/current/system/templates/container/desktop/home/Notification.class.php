<?php

require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Notification extends AbstractTemplate {
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
		<div style="background-color: #1d1d1d; color: white; width: 200px; font-size: 15px; font-weight: bold;">
			Notifications
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
		<div style="position:relative; width: 200px; background-color: #f0f0f0; top:0px;">
			<?php if ($handle = opendir('system/templates/application')) {
				    while (false !== ($file = readdir($handle))) {
				    	if($file != "." && $file != ".." && $file != ".DS_Store"){ ?>
				    		<span style="position: relative;"><img alt="<?= $file ?>" src="system/templates/application/<?= $file ?>/img/icon.png" height="30" ></span>
				    		<span style="position: relative; left: 5px; top:-10px;"><a href="?application=<?= $file ?>" class="myIcon" rel="external"><?= $file ?></a></span>
				    		<br/>
				    	<?php } ?>
				    <?php } ?>
			<?php } ?>
			<br/>
		</div>
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


