<?php

require_once 'system/templates/ITemplate.php';
require_once 'system/templates/AbstractTemplate.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class EditDialog extends AbstractTemplate {
	
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
			<h1>Edit</h1>
		</div>
	<?php }
	
	/**
	* Get the CONTENT for jQuery Mobile
	*/
	public /*String*/ function getContent() { ?>

	<div id="Itin" data-theme="c">
		<form action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm"
			id="<?= APPLICATION_NAME ?>FindForm">
			<input type="hidden" name="application"
				value="<?= APPLICATION_NAME ?>" /> <input type="hidden" name="method"
				value="find" /> <input type="hidden" name="numberOfOntology"
				value="4" /> <input type="hidden" id="departGeo" name="DepartGeo" />
	
			<!-- FROM -->
	
			<input data-theme="d" type="text" id="depart" name="Depart"
				placeholder="Départ" />
	
			<!-- TO -->
	
			<div id="divarrivee">
				<input data-theme="d" type="text" id="arrivee" name="Arrivee"
					placeholder="Arrivée"
					onkeyup="$(this).css('background-image', 'none');" /> <select
					id="selectarrivee" data-iconpos="notext" data-icon="plus"
					name="enum-1" onclick="changeDestination()">
	
					<!-- USER -->				
			<?php if (isset($_SESSION['position'])) { ?>
									<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
			<?php } ?>
						
							<!-- FRIENDS -->
			<?php if(isset($_SESSION['friends'])) {
							foreach ($_SESSION['friends'] as $friend ) { ?>
			<?php if ($friend["position"]->formattedAddress != "") {?>
									<option value="<?= $friend["profilePicture"] ?>&&<?= $friend["position"]->formattedAddress ?>">
								<?= $friend["name"] ?>
									</option>
			<?php }
					}
				} ?>
							  </select>
			</div>
	
			<!-- DATE -->
			<div id="date">
	
			<?php
			$now = getdate();
			$months = array('janvier', 'février', 'mars', 'avril','mai',
					'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
			?>
				<fieldset data-role="controlgroup" data-type="horizontal" style="display: inline; margin: 5px;">
	
					<select name="select-day" id="select-day">
					<?php for ($i = 1; $i <= 31; $i++) {
						?>
						<option value=<?=$i?> 
						<?php if ($i==$now['mday']){?>
							selected="selected"           <?php } ?>>
							&nbsp;
							<?= $i?>
						</option>
						<?php } ?>
					</select> 
					<select name="select-month" id="select-month">
						<?php for ($i = 0; $i <= 11; $i++) {?>
						<option value=<?=$i+1?>           
						<?php if ($i+1==$now['mon']){?>
							selected="selected" <?php } ?>>
							<?=$months[$i]?>
						</option>
	
							<?php } ?>
						</select>
						<select name="select-year" id="select-year">
							<?php for ($i = 2012; $i <= 2016; $i++) {
										?>
							<option value=<?=$i?>
							<?php if ($i==$now['year']){?>
								selected="selected" <?php } ?>>
								<?=$i?>
							</option>
						
						<?php } ?>
						</select>
				</fieldset>
	
				<fieldset data-role="controlgroup" data-type="horizontal" style="display: inline; margin: 5px;">
					<select name="select-hour" id="select-hour">
					<?php for ($i = 0; $i <= 23; $i++) {
						?>
						<option value=<?=$i?> <?php if ($i==$now['hours']){?>
							selected="selected"   <?php } ?>>
							<?=sprintf('%02d',$i)?>h
						</option>
											
										<?php } ?>
					</select>
					<select name="select-minute" id="select-minute">
									<?php for ($i = 0; $i <= 59; $i++) {
										?>
						<option value=<?=$i?> <?php if ($i==$now['minutes']){?>
							selected="selected" <?php } ?>>
							<?=sprintf('%02d',$i)?>
						</option>
							<?php } ?>
					</select>
				</fieldset>
			</div>
	
			<!-- SUBMIT - ToDO validate before submit-->
			<a href="#" id="trouver" data-role="button" rel="external"
				data-icon="right" data-iconpos="right" data-theme="b"
				onclick="validateIt();">Rechercher</a>
	
		</form>
	
	</div>

	<?php }
	
	/**
	* Print the Template
	*/
	public /*String*/ function printTemplate() { ?>
		<div id="Edit" data-role="page">
			<?php  $this->getHeader(); ?>
			<?php $this->getContent(); ?>
			<?php $this->getFooter(); ?>
		</div>
	<?php }
	
}
?>