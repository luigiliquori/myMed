<? include("header.php"); ?>

<? if(empty($_SESSION['user']->lang)):?>
	<? include("LangView.php"); ?>
<? endif ?>

<div id="home" data-role="page">

	<? include("header-bar.php"); ?>
	
	<div data-role="content">
		<div data-role="fieldcontain" style="padding-top: 5%;">
			<a href="#search" type="button" class="mymed-huge-button">Rechercher un partenariat</a>
		</div>
		
		<div data-role="fieldcontain">
			<a href="#post" type="button" class="mymed-huge-button">Déposer une offre de partenariat</a>
		</div>
	</div>
	
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#home" data-transition="none" data-icon="search"  class="ui-btn-active ui-state-persist">Partenariats</a></li>
				<li><a href="#info" data-transition="none" data-back="true" data-icon="info">Informations</a></li>
				<li><a href="#store" data-transition="none" data-icon="grid">Journal</a></li>
				<li><a href="#profile" data-transition="none" data-icon="user">Profil</a></li>
			</ul>
		</div>
	</div>

</div>

<? include("infos.php"); ?>
<? include("InfoView.php"); ?>
<? include("ProfileView.php"); ?>
<? include("UpdateProfileView.php"); ?>
<? include("StoreView.php"); ?>

<? include("footer.php"); ?>