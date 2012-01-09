<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Container extends AbstractTemplate {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($id) {
		parent::__construct($id, "container");
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
			<div class="ui-grid-b">
				<?php if ($handle = opendir('system/templates/application')) {
						$column = "a";
					    while (false !== ($file = readdir($handle))) {
					    	if($file != "." && $file != ".." && $file != ".DS_Store"){ ?>
					    		<?php if(TARGET == "desktop") { ?>
					   				<iframe src="#" id="<?= $file ?>_iframe" name="<?= $file ?>_iframe" style="position: absolute; width:70%; height:70%; top:20px; left:15%; display: none;"></iframe> 
						    	<?php } ?>
						    	<div class="ui-block-<?= $column ?>">
							    	<a
							    	<?php if(TARGET == "desktop") { ?> 
							    		href="#" 
							    		onClick="$('#<?= $file ?>_iframe').attr('src', 'index.php?application=<?= $file ?>'); displayFrame('<?= $file ?>_iframe')" target="<?= $file ?>_iframe"
							    	<?php } else { ?>
							    		href="?application=<?= $file ?>"
							    		rel="external"
							    	<?php } ?>
							    	class="myIcon"><img alt="<?= $file ?>" src="system/templates/application/<?= $file ?>/img/icon.png" width="50px" >
							    	</a>
							    	<br>
							    	<span style="font-size: 9pt; font-weight: bold;">
							    		<?= $file ?>
							    	</span>
						    	</div>
						    	<?php 
						    	if($column == "a") {
						    		$column = "b";
						    	} else if($column == "b") {
						    		$column = "c";
						    	} else if($column == "c") {
						    		$column = "a";
						    		echo '</div><br /><div class="ui-grid-b">';
						    	}
					    	} 
					    } 
				} ?>
			</div>
		</div>
	<?php }
}
?>