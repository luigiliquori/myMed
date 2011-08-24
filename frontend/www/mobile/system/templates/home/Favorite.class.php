<?php

require_once 'system/templates/home/AbstractHome.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Favorite extends AbstractHome {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("favorite");
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content"> 
		<?php if ($handle = opendir('system/templates/application')) {
			    while (false !== ($file = readdir($handle))) {
			    	if($file != "." && $file != ".." && $file != ".DS_Store"){ ?>
			    		<a href="?application=<?= $file ?>" class="myIcon" rel="external"><img alt="<?= $file ?>" src="system/templates/application/<?= $file ?>/icon.png" ></a>
			    	<?php } ?>
			    <?php } ?>
		<?php } ?>
		</div>
	<?php }
}
?>