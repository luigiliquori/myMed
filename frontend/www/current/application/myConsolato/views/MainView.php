
<? include("header.php") ?>
<div data-role="page" >

	<? header_bar() ?>

	<div data-role="content" >

		<ul data-role="listview" data-inset="true">
			<li><a href="<?= url("institutionCategory") ?>">
				<img src="img/institution.png">
				<?= _("Institution")?>
			</a></li>
			<li><a href="<?= url() ?>">
			
			</a></li>
		</ul>
		
	</div>

</div>
<? include("footer.php") ?>