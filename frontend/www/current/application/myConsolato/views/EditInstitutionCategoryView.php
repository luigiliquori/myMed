<? include("header.php") ?>
<div data-role="page" >

	<? header_bar(category_crumb($this->category)) ?>

	<div data-role="content">

		<? title_bar(_("Edition de la catégorie ") . $this->item->title) ?>
	
		<form method="post" action="<?= url("institutionCategory:doEdit") ?>" data-ajax="false" >
		
			<? include("InstitutionCategoryForm.php") ?>
		
			<input type="submit" value="<?= _('Confirmer') ?>" data-theme="g">
			
		</form>

	</div><!-- End of "content" -->

</div><!-- End of "page" -->
<? include("footer.php") ?>