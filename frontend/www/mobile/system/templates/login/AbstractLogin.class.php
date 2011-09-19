<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
abstract class AbstractLogin extends AbstractTemplate {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*String*/ $activeFooter;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*String*/ $id) {
		parent::__construct($id, $id);
		$this->activeFooter = $id;
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the FOOTER for jQuery Mobile
	 */
	public /*String*/ function getFooter() { ?>
		<!-- FOOTER_PERSITENT-->
		<div data-role="footer" data-position="fixed">
			<div data-role="navbar">
				<ul>
					<li><a href="#login" <?= $this->activeFooter == "login" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Login</a></li>
					<li><a href="#privacy" <?= $this->activeFooter == "privacy" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>Privacy</a></li>
					<li><a href="#aboutus" <?= $this->activeFooter == "aboutus" ? 'class="ui-btn-active ui-state-persist"' : ''; ?>>About us</a></li>
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