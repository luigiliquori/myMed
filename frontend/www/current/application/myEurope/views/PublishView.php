<div data-role="page" id="post">
	
	<? tabs_simple('Insert') ?>
	<div data-role="content">
		<form action="./" method="post" id="publishForm" data-ajax="false">

			<input type="hidden" name="action" value="Publish" />
			<input type="hidden" name="method" value="create" /> <input
				type="hidden" name="r"
				value="<?= $_SESSION['myEurope']->details['role'] ?>" />

			<div data-role="fieldcontain">
				<label for="textinputp3" class="postTitle"><b><?= _('Title') ?> </b>
				</label> <input id="textinputp3" class="postTitle"
					data-inline="true" name="title"
					placeholder="<?= _("partnership or project name") ?>" value=''
					type="text" />
			</div>

			<div data-role="collapsible" data-collapsed="false" data-theme="b"
				data-content-theme="d" data-mini="true">
				<h3>Options</h3>

				<div data-role="collapsible-set" data-theme="c"
					data-content-theme="d">
					<div data-role="collapsible" data-collapsed="false">
						<h3>
						<?= _('Themes') ?>
							:
						</h3>
						<fieldset data-role="controlgroup">
						<? foreach (Categories::$themes as $k=>$v): ?>
							<input type="checkbox" name="t[]" value="<?= $k ?>"
								id="checkbox-<?= $k ?>" /> <label for="checkbox-<?= $k ?>"><?= $v ?>
							</label>
							<? endforeach; ?>
						</fieldset>
					</div>
					<div data-role="collapsible" data-collapsed="true">
						<h3>
						<?= _('Areas') ?>
							:
						</h3>
						<fieldset data-role="controlgroup">
							<div data-role="collapsible-set" data-mini="true">
								<div data-role="collapsible" data-collapsed="false">
									<h3>
									<?= _("France") ?>
									</h3>
									<? foreach (Categories::$places_fr as $k=>$v): ?>
									<input type="checkbox" name="pf[]" value="<?= $v ?>"
										id="checkbox-f<?= $k ?>" /> <label for="checkbox-f<?= $k ?>"><?= $v ?>
									</label>
									<? endforeach; ?>
								</div>
								<div data-role="collapsible" data-collapsed="true">
									<h3>
									<?= _("Italy") ?>
									</h3>
									<? foreach (Categories::$places_it as $k=>$v): ?>
									<input type="checkbox" name="pi[]" value="<?= $v ?>"
										id="checkbox-i<?= $k ?>" /> <label for="checkbox-i<?= $k ?>"><?= $v ?>
									</label>
									<? endforeach; ?>
								</div>
								<div data-role="collapsible" data-collapsed="true">
									<h3>
									<?= _("Other") ?>
									</h3>
									<? foreach (Categories::$places_ot as $k=>$v): ?>
									<input type="checkbox" name="po[]" value="<?= $v ?>"
										id="checkbox-o<?= $k ?>" /> <label for="checkbox-o<?= $k ?>"><?= $v ?>
									</label>
									<? endforeach; ?>
								</div>
							</div>
						</fieldset>
					</div>
					<div data-role="collapsible" data-collapsed="true">
						<h3>
						<?= _('Other options') ?>
							:
						</h3>

						<div data-role="fieldcontain">
							<label for="call" class="select"><?= _("Programme concerné par l'offre") ?>:</label>
							<select name="c" id="call">
							<? foreach (Categories::$calls as $k=>$v): ?>
								<option value="<?= $k ?>">
								<?= $v ?>
								</option>
								<? endforeach; ?>
							</select>
						</div>

						<div data-role="fieldcontain" id="tagsContainer2">
							<label for="textinput1"><?= _('Keywords') ?>: </label>
 							 <input id="textinput1" class="tagInput"
								placeholder="<?= _('separated by a space') ?>"
								name="k[]" list="keywords" style="width: 50%;min-width: 300px;margin-bottom: 5px;"/>
						</div>

						<div data-role="fieldcontain">
							<label for="textinputp2"><?= _('Date of expiration') ?>: </label>
							<input id="textinputp2" name="date"
								placeholder="<?= _('date in format year-month-day') ?>" value=''
								type="date" />
						</div>
					</div>
				</div>
	
			</div>

			<textarea id="CLEeditor" name="text">
				<h1><?= _("Your partnership") ?></h1>  ...</textarea>

			<div style="text-align: center;">
				<input id="submit2" type="submit" class="ui-btn-active ui-state-persist"
					data-inline="true" data-icon="check" value="<?=_('Insert') ?>" />
			</div>
		</form>
	</div>
</div>