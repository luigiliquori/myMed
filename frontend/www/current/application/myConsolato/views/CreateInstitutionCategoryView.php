<? include("header.php") ?>
<div data-role="page" >

	<? header_bar(category_crumb($this->category->getParent())) ?>

	<div data-role="content">

		<? title_bar(_("Nouvelle catégorie")) ?>
	
		<form method="post" action="<?= url("institutionCategory:doCreate") ?>" data-ajax="false" >
		
			<? include("InstitutionCategoryForm.php") ?>
		
			<input type="submit" value="<?= _('Ajouter') ?>" data-theme="g">
			
		</form>

	</div><!-- End of "content" -->

</div><!-- End of "page" -->
<? include("footer.php") ?>
