
<input type="hidden" name="parentID" value="<?= $this->item->parentID ?>" >
<input type="hidden" name="id" value="<?= $this->item->id ?>" >

<? input("text", "title", _("Titre"), $this->item->title, "", true) ?>

<? input("textarea", "desc", _("Description"), $this->item->desc, _("Description courte"), false) ?>
