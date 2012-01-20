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
			Applications
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
		<div style="position:relative; top:0px;">
			<ul data-role="listview" data-theme="d">
			<?php if ($handle = opendir('system/templates/application')) {
				    while (false !== ($file = readdir($handle))) {
				    	if($file != "." && $file != ".." && $file != ".DS_Store"){ ?>
				    		<li>
				    			<img alt="<?= $file ?>" src="system/templates/application/<?= $file ?>/img/icon.png" height="30" >
						    	<a href="?application=<?= $file ?>" class="myIcon" Style="position: relative; left:30px; height:16px;" target="blank">
							    	<?= $file ?>
						    	</a>
				    		</li>				    	
				    	<?php } ?>
				    <?php } ?>
			<?php } ?>
			</ul>
		</div>
	<?php }
	
   /**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div style="position: absolute; margin-left: 30%; left:-202px; top: 660px; width: 200px; background-color: white;">
			<?php 
			$this->getHeader();
			$this->getContent();
			$this->getFooter();
			?>
		</div>
	<?php }
}
?>

