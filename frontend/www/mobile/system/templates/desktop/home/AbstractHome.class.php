<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class AbstractHome extends AbstractTemplate {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $activeHeader;
	private /*String*/ $activeFooter;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*String*/ $id) {
		parent::__construct($id, $id);
		$this->activeHeader = $id;
		$this->activeFooter = ($id == "favorite" || $id == "category" || $id == "top10") ? "home" : $id;
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
				<a href="#favorite" data-transition="fade" data-role="button" <?= $this->activeHeader == "favorite" ? 'class="ui-btn-active"' : ''; ?> data-inline="true">Favoris</a>
				<a href="#top10" data-transition="fade" data-role="button" <?= $this->activeHeader == "top10" ? 'class="ui-btn-active"' : ''; ?> data-inline="true">Top 10</a>
				<a href="#category" data-transition="fade" data-role="button" <?= $this->activeHeader == "category" ? 'class="ui-btn-active"' : ''; ?> data-inline="true">Toutes les applications</a>
			</div>
		</div>
	<?php }
	
	/**
	 * Get the FOOTER for jQuery Mobile
	 */
	public /*String*/ function getFooter() { }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="<?= $this->id ?>" data-role="page" data-theme="d" style="left:25%; width: 50%; top:120px; border: thin #d2d2d2 solid; padding: 5px;">
			<?php $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div> 
	<?php }
}
?>