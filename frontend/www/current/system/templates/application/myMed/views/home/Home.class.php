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
	protected /*String[]*/ $hiddenApplication = array("myNCE", "myBEN");
	
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
		<div data-role="header"  data-theme="b">
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
		<div class="content" data-role="content"> 
			<div class="ui-grid-b" Style="padding: 10px;">
				<?php if ($handle = opendir('application')) {
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
		</div>
	<?php }
	
	/**
	* Get the FOOTER for jQuery Mobile
	*/
	public /*String*/ function getFooter() { ?>
			<!-- HEADER -->
			<div>
				<center>
					<h4>myMed - INTERREG IV - Alcotra</h4>
					<img alt="Alcotra" src="/system/img/logos/alcotra"
						style="width: 100px;" /> <img alt="Europe"
						src="/system/img/logos/europe" style="width: 50px;" /> <img
						alt="Conseil Général 06" src="/system/img/logos/cg06"
						style="width: 100px; height: 30px;" /> <img alt="Regine Piemonte"
						src="/system/img/logos/regione" style="width: 100px;" /> <img
						alt="Région PACA" src="/system/img/logos/PACA" style="width: 100px;" />
					<img alt="Prefecture 06" src="/system/img/logos/pref"
						style="width: 70px;" /> <img alt="Inria"
						src="/system/img/logos/inria.jpg" style="width: 100px;" />
					<p>"Ensemble par-delà les frontières"</p>
				</center>
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


