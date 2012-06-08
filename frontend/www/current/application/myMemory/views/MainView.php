<? include("header.php"); ?>
<div>
<p>
Hello <?= $_SESSION['user']->name ?> Welcome to the main page !
</p>

<a href="?action=logout" rel="external" data-role="button" data-theme="r">Déconnexion</a>
</div>
<? include("footer.php"); ?>