<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="new" >

	<? print_header_bar(true, "defaultHelpPopup"); ?>

	<div data-role="content">
		<br>
		<form action="?action=ExtendedProfile&method=create" method="post" id="ExtendedProfileForm" data-ajax="false">
			<input type="hidden" name="form" value="create" />
			<div data-role="fieldcontain">
				<label for="textinputu1"><?= _('Organization Name') ?>: </label>
				<input id="textinputu1" name="name" placeholder="" value='' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="role" class="select"><?= _("Your category") ?>:</label>
				<select name="role" id="role">
				<? foreach ($this->cats as $k=>$v) :?>
					<option value="<?= $k ?>"><?= Categories::$roles[$k] ?></option>
				<? endforeach ?>
				</select>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu2">Domaine d'action: </label>
				<input id="textinputu2" name="activity" placeholder="" value='' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu4"><?= _('Address') ?>: </label>
				<input id="textinputu4" name="address" placeholder="" value='' type="text" />
			</div>
			<div data-role="fieldcontain">
				<label for="area" class="select"><?= _("Territoire d'action") ?>:</label>
				<select name="area" id="area">
					<option value="local">local</option>
					<option value="départemental">départemental</option>
					<option value="régional">régional</option>
					<option value="national">national</option>
					<option value="international">international</option>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset id="type" data-role="controlgroup">
					<legend>Type de territoire:</legend>
					<input type="checkbox" name="type-urbain" id="check-view-a" value="urbain" checked="checked"/> <label for="check-view-a">urbain</label>
					<input type="checkbox" name="type-rural" id="check-view-b" value="rural" /> <label for="check-view-b">rural</label>
					<input type="checkbox" name="type-montagnard" id="check-view-c" value="montagnard" /> <label for="check-view-c">montagnard</label>
					<input type="checkbox" name="type-maritime" id="check-view-d" value="maritime" /> <label for="check-view-d">maritime</label>
				</fieldset>
			</div>
			<div data-role="fieldcontain">
				<label for="textinputu5"><?= _('Email') ?>: </label>
				<input id="textinputu5" name="email" placeholder="" value='<?= $_SESSION['user']->email ?>' type="email" />
			</div>		
			<div data-role="fieldcontain">
				<label for="textinputu6"><?= _('Phone') ?>: </label>
				<input id="textinputu6" name="phone" placeholder="" value='' type="tel" />
			</div>
			<div data-role="fieldcontain">
				<label for="desc"><?= _('Description') ?>: </label>
				<textarea id="desc" name="desc" placeholder="description, commentaires"></textarea>
			</div>
			<br/>
			<input id="service-term" type="checkbox" name="checkCondition" style="display: inline-block; top: 8px;"/>
			<span style="display:inline-block;margin-left: 40px;">
				J'accepte les 
				<a href="<?= APP_ROOT ?>/conds" rel="external">conditions d'utilisation</a>
			</span>
			<div style="text-align: center;">
				<input type="submit" data-inline="true" data-theme="e" data-role="button" data-icon="gear" value="<?= _('Create this profile') ?>"/>
			</div>
		</form>
	</div>
</div>


<? include("footer.php"); ?>

