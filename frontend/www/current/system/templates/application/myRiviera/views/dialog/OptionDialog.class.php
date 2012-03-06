<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class OptionDialog extends AbstractTemplate {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	* Get the HEADER for jQuery Mobile
	*/
	public /*String*/ function getHeader() { ?>
		<!-- HEADER -->
		<div data-theme="b" data-role="header">
			<h1>Options</h1>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>
		<div data-theme="b" data-role="content">
			<div style="text-align: left;">
				<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm1" id="<?= APPLICATION_NAME ?>FindForm1">
						<fieldset data-role="controlgroup">
							<input type="checkbox" name="checkbox-1" id="checkbox-1" class="custom" />
							<label for="checkbox-1">Lieux Public</label>
						</fieldset>
						<fieldset data-role="controlgroup">
							<input type="checkbox" name="checkbox-1" id="checkbox-1" class="custom" />
							<label for="checkbox-1">Restaurants</label>
						</fieldset>
						<fieldset data-role="controlgroup">	
							<input type="checkbox" name="checkbox-1" id="checkbox-1" class="custom" />
							<label for="checkbox-1">Musées</label>
						</fieldset>
						<fieldset data-role="controlgroup">	
							<input type="checkbox" name="checkbox-1" id="checkbox-1" class="custom" />
							<label for="checkbox-1">Evenements</label>
						</fieldset>
				</form>
			</div>
		</div>
	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="Option" data-role="page">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
	
}
?>
