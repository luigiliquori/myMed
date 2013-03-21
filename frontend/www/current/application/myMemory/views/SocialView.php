<? include("header.php"); ?>
<? include("notifications.php")?>

<div style="width : 20%; margin:auto;">

<span class='st_facebook_large' displayText='Facebook'></span><br />
<span class='st_twitter_large' displayText='Tweet'></span><br />
<span class='st_linkedin_large' displayText='LinkedIn'></span><br />
<span class='st_email_large' displayText='Email'></span>
</div>

<div data-role="footer" data-id="myFooter" data-position="fixed">
	<div data-role="navbar" data-iconpos="top" >
		<ul>
			<li><a href="?action=main" data-icon="home" ><?= _('Homescreen') ?></a></li>
			<li><a href="?action=ExtendedProfile" data-icon="profile" ><?= _('Profile'); ?></a></li>
			<li><a href="?action=Social" data-icon="star" class="ui-btn-active"><?= _('Social'); ?></a></li>

			
		</ul>
	</div>
</div>
<? include("footer.php"); ?>