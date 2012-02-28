<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 *
 * Represent the template
 * @author lvanni
 *
 */
class FindView extends MyApplication {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private /*IRequestHandler*/ $handler;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct(/*MyTemplateHandler*/ $handler) {
		parent::__construct("Find");
		$this->handler = $handler;
		$this->filterList = array("mymed", "carf");
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the HEADER for jQuery Mobile
	 */
	public /*String*/ function getHeader() { ?>
<div data-role="header" data-theme="b">
	<h1>
	<?= TARGET == "mobile" ? " " : APPLICATION_NAME ?>
	</h1>
	<a id="Iti" data-role="actionsheet" data-sheet="Itin"
		data-icon="search" data-iconpos="right" class="ui-btn-left">Rechercher</a>
	<a href="#Option" data-role="actionsheet" class="ui-btn-right" data-icon="gear">Options</a>
</div>


<?php }

/**
 * Get the CONTENT for jQuery Mobile
 */
public /*String*/ function getContent() { ?>
<!-- CONTENT -->
<div data-role="content" style="padding: 0px;">

	<!-- MAP -->
	<div id="myRivieraMap"></div>

	<script type="text/javascript">
				var mobile = '<?php echo TARGET ?>';
			</script>

	<div id="itineraire" data-role="collapsible" data-theme="b" data-content-theme="b" style="width: <?= TARGET == "mobile" ? "85" : "35" ?>%;">
		<h3>Feuille de route</h3>
		<div id="itineraireContent" data-role="collapsible-set" data-theme="b"
			data-content-theme="d" data-mini="true"></div>
	</div>

	<a id="prev-step" data-role="button" data-theme="b"
		data-iconpos="notext" data-icon="arrow-l" data-inline="true"
		onclick=""></a> <a id="next-step" data-role="button" data-theme="b"
		data-iconpos="notext" data-icon="arrow-r" data-inline="true"
		onclick=""></a>

</div>




<!-- ITINERAIRE POPUP -->

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
				name="enum" onclick="changeDestination()">

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
			<fieldset data-role="controlgroup" data-type="horizontal">

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

			<fieldset data-role="controlgroup" data-type="horizontal">
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
			data-icon="arrow-r" data-iconpos="right" data-theme="b"
			onclick="validateIt();">Trouver</a>

	</form>

</div>


<?php }

}
?>