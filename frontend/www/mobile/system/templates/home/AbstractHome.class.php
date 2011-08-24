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
				<a href="#favorite" data-role="button" <?= $this->activeHeader == "favorite" ? 'class="ui-btn-active"' : ''; ?> data-inline="true">Favoris</a>
				<a href="#category" data-role="button" <?= $this->activeHeader == "category" ? 'class="ui-btn-active"' : ''; ?> data-inline="true">Catégories</a>
				<a href="#top10" data-role="button" <?= $this->activeHeader == "top10" ? 'class="ui-btn-active"' : ''; ?> data-inline="true">Top 10</a>
			</div>
		</div>
	<?php }
	
	/**
	 * Get the FOOTER for jQuery Mobile
	 */
	public /*String*/ function getFooter() { ?>
		<!-- FOOTER_PERSITENT-->
		<div data-role="footer" data-position="fixed">
			<div data-role="navbar">
				<ul>
				<li><a href="#favorite" <?= $this->activeFooter == "home" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Home</a></li>
				<li><a href="#profile" <?= $this->activeFooter == "profile" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Profile</a></li>
				<li><a href="#login" onclick="document.disconnectForm.submit()">Deconnexion</a></li>
				</ul>
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() {
		parent::printTemplate();
	}
}
?>