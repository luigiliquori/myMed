<?php

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class Home extends AbstractView {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	protected /*String*/ $activeHeader;
	protected /*String[]*/ $hiddenApplication = array("myMed", "myNCE", "myBEN", "myTestApp");
	
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
		<div data-role="header" data-theme="b"  data-position="fixed">
			<a href="#profile" data-icon="star">Partagez</a>
			<h1>myMed</h1>
			<a href="#About" data-icon="gear">Option</a>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content" data-role="content"> 
			<div class="ui-grid-b" Style="padding: 10px;">
				<?php if ($handle = opendir(MYMED_ROOT . '/application')) {
						$column = "a";
					    while (false !== ($file = readdir($handle))) {
					    	if(preg_match("/my/", $file) && !preg_match("/Admin/", $file) && !in_array($file, $this->hiddenApplication)) { ?>
						    	<div class="ui-block-<?= $column ?>">
							    	<a
							    	href="/application/<?= $file ?>"
							    	rel="external"
							    	class="myIcon"><img alt="<?= $file ?>" src="application/<?= $file ?>/img/icon.png" width="50px" ></a>
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
			
			<!-- LOGO -->
			<?php include(MYMED_ROOT . 'system/views/logos.php'); ?>
			
		</div>
	<?php }
	
	protected /*void*/ function getFooter() { ?>
		<div data-role="footer" data-position="fixed" data-theme="b">
			<div data-role="navbar">
				<ul>
					<li><a href="#home" data-transition="none" data-back="true" data-icon="grid" <?= $this->activeHeader == "home" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Applications</a></li>
					<li><a href="#profile" data-transition="none" data-icon="profile" <?= $this->activeHeader == "profile" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Profil</a></li>
				</ul>
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="b">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
}
?>


