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
	public /*String*/ function getHeader() { 
		if (TARGET == "mobile") { ?>
			<!-- HEADER -->
			<div data-role="header">
				<div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
					<a href="#favorite" data-transition="slide"  data-role="button" data-back="true" <?= $this->activeFooter == "home" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Applications</a>
					<a href="#profile" data-transition="slide"  data-role="button" <?= $this->activeFooter == "profile" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Profile</a>
				</div>
			</div>
		<?php }
	}
	
	/**
	 * Get the FOOTER for jQuery Mobile
	 */
	public /*String*/ function getFooter() { } 
}
?>