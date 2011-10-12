<?php

require_once dirname(__FILE__).'/AbstractHome.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Top10 extends AbstractHome {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("top10");
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
			<ol data-role="listview" data-theme="c" data-dividertheme="a">
			<?php if ($handle = opendir('system/templates/application')) {
				    while (false !== ($file = readdir($handle))) {
				    	if($file != "." && $file != ".." && $file != ".DS_Store"){ ?>
				    		<li><img alt="<?= $file ?>" src="system/templates/application/<?= $file ?>/img/icon.png" ><a href="?application=<?= $file ?>" class="myIcon" rel="external"><?= $file ?></a></li>
				    	<?php } ?>
				    <?php } ?>
			<?php } ?>
			</ol>
		</div>
	<?php }
}
?>
