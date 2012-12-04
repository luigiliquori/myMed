<!-- STEP1: Select Category -->
<div data-role="page" id="post">
	
	<? print_header_bar(true, "publishHelpPopup"); ?>
	
	<div data-role="content">
	
		<form action="index.php?action=main" method="POST" data-ajax="false">
		
			<input type="hidden" name="method" value="Publish" />
			<input type="hidden" id="publish_theme" name="theme" value="" />
			<input type="hidden" id="publish_other" name="other" value="" />
			<input type="hidden" id="publish_date" name="date" value="" />
	
			<div data-role="collapsible" data-collapsed="false" data-theme="e" data-content-theme="e" data-mini="true">
				<h3>Comment pubiler ?</h3>
				<p>Saisissez un <b>titre</b> pour votre projet, puis une liste de <b>mots-clés</b> séparés par un espace<br />
				Rédigez votre offre a l'aide de l'editeur en ligne de myEurope. N'hesitez pas à compléter votre fiche de projet dans les options (bouton "Option").
				</p>
			</div>
			
			<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d" data-mini="true">
				<h3><?= _('Publier votre projet') ?> :</h3>
				
				<h3><?= _('Title') ?> : </h3>
				<input id="textinputp3" class="postTitle"
					data-inline="true" name="title"
					placeholder="<?= _("partnership or project name") ?>" value=''
					type="text" />
				
				<h3><?= _('Texte libre') ?> :</h3>
				<div data-role="controlgroup" data-type="horizontal"> 
					<a href="#publishOptionPopup" data-rel="popup" data-role="button" data-inline="true" data-icon="gear" data-mini="true"><?= _("Option") ?></a>
					<a href="#publishExamplePopup" data-rel="popup" data-role="button" data-inline="true" data-icon="star" data-mini="true" data-theme="e" data-iconpos="right"><?= _("Example") ?></a>
				</div>
				<textarea id="CLEeditor" name="text"></textarea>
				<br />
			</div>
			
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-icon="check" data-theme="g" value="<?=_('Insert') ?>" onclick="
					$('#publish_theme').val($('#publish_theme_content').val());
					$('#publish_other').val($('#publish_other_content').val());
					$('#publish_date').val($('#publish_day_content').val() + '-' + $('#publish_month_content').val() + '-' +  $('#publish_year_content').val());
				"/>
			</div>
	
			<!-- --------------------- -->
			<!-- PUBLISH OPTION POPUP -->
			<!-- --------------------- -->
			<div data-role="popup" id="publishOptionPopup" data-theme="c"
				data-content-theme="d" data-mini="true" class="ui-content">
				<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
					
					<div data-role="fieldcontain">
		   				<fieldset data-role="controlgroup">
							<select id="publish_theme_content" id="call">
							<option value=""><?= _("Themes") ?></option>
							<? foreach (Categories::$themes as $k=>$v): ?>
								<option value="<?= $k ?>">
									<?= $v ?>
								</option>
								<? endforeach; ?>
							</select>
							
								<select id="publish_other_content" id="call">
								<option value=""><?= _("Programme") ?></option>
								<? foreach (Categories::$calls as $k=>$v): ?>
									<option value="<?= $k ?>">
										<?= $v ?>
									</option>
								<? endforeach; ?>
							</select>
						</fieldset>
					</div>
	
					<h3><?= _('Date of expiration') ?> :</h3>
					<fieldset data-role="controlgroup" data-type="horizontal"> 
						<select id="publish_day_content" data-inline="true">
							<option value="">Day</option>
						<?php for ($i = 1; $i <= 31; $i++) { ?>
							<option value="<?= $i ?>"><?= $i ?></option>
						<?php } ?>
						</select>
						<select id="publish_month_content" data-inline="true">
							<option value="">Month</option>
						<?php for ($i = 1; $i <= 12; $i++) { ?>
							<option value="<?= $i ?>"><?= $i ?></option>
						<?php } ?>
						</select>
						<select id="publish_year_content" data-inline="true">
							<option value="">Year</option>
						<?php for ($i = 2012; $i <= 2042; $i++) { ?>
							<option value="<?= $i ?>"><?= $i ?></option>
						<?php } ?>
						</select>
					</fieldset>
	
			</div>
	
		</form>
	</div>
	
	<!-- --------------------- -->
	<!-- PUBLISH EXAMPLE POPUP -->
	<!-- --------------------- -->
	<div data-role="popup" id="publishExamplePopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _('Title') ?> : </h3>
		<input type="text" value="Création d'un centre d'art dans la commune d'Embrun" readonly="readonly"/>
		<h3><?= _('Texte libre') ?> :</h3>
		<textarea rows="80" cols="80" readonly="readonly">J’aimerais redonner une attractivité à ma commune en y créant un centre d’art. En effet, alors que notre commune nous offre des lieux chargés d’histoire, nous ne disposons pas de lieu où nous pourrions promouvoir l’art contemporain, de façon à faire le lien entre les deux époques.

	- Mon but serait, en dehors de la création d’un centre d’art, de promouvoir la culture auprès des plus jeunes, de stimuler la population.
	- Je recherche un partenaire italien qui soit intéressé par ma proposition pour que nous puissions soumissionner un projet Alcotra et réaliser ainsi de véritables circuits culturels transfrontaliers.
		</textarea>
	</div>
	
	<!-- --------------------- -->
	<!-- PUBLISH HELP POPUP -->
	<!-- --------------------- -->
	<div data-role="popup" id="publishHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;" class="ui-content">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3>Noubliez pas :</h3>
		<p>de renseigner des champs présents dans <a href="#searchOptionPopup" data-rel="popup" data-role="button" data-inline="true" data-icon="gear" data-mini="true" data-theme="e"><?= _("Option") ?></a></p>
		<p>Plus les options sont remplies, plus un projet sera visible .</p>
		<p>Lorsque vous cochez/ remplissez plusieurs champs dans une catégorie, les 
		résultats correspondront à au moins un des critères coché .</p>
	</div>
	
</div>

