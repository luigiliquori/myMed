<?php
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Home extends AbstractTemplate {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	protected /*String*/ $activeHeader;
	protected /*String[]*/ $hiddenFolder = array(".", "..", ".DS_Store", "myTemplate", "myMed", "myRivieraAdmin", "myNCE");
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct($id = "home") {
		parent::__construct($id, $id);
		$this->activeHeader = $id;
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-role="header">
			<div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
				<a href="#home" data-transition="slide"  data-role="button" data-back="true" <?= $this->activeHeader == "home" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Applications</a>
				<a href="#profile" data-transition="slide"  data-role="button" <?= $this->activeHeader == "profile" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Profile</a>
			</div>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" > 
			<div class="ui-grid-b" Style="padding: 10px;">
				<?php if ($handle = opendir('application')) {
						$column = "a";
					    while (false !== ($file = readdir($handle))) {
					    	if(preg_match("/my/", $file) && !preg_match("/Admin/", $file)) { ?>
						    	<div class="ui-block-<?= $column ?>">
							    	<a
							    	href="/application/<?= $file ?>"
							    	rel="external"
							    	class="myIcon"><img alt="<?= $file ?>" src="application/<?= $file ?>/img/icon.png" width="50px" >
							    	</a>
							    	<br>
							    	<span style="font-size: 9pt; font-weight: bold;"><?= $file ?></span>
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
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="a">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
		</div>
	<?php }
}
?>


