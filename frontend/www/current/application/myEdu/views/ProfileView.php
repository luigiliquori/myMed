<div id="profile" data-role="page">

	<? print_header_bar(false, true); ?>

	<div data-role="content">

		<? include_once 'notifications.php'; ?>
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
			
			<div data-role="collapsible" data-collapsed="false">
	
				<h3><?= _("About you") ?></h3>
	
				<ul data-role="listview" data-mini="true">
					<li data-icon="picture"><a href="#updateProfile"><?php if($_SESSION['user']->profilePicture != "") { ?>
							<img class="ui-li-mymed" alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" width="60" height="60">
						<?php } else { ?>
							<img class="ui-li-mymed" alt="thumbnail" src="http://graph.facebook.com//picture?type=large" width="60" height="60">
						<?php } ?>
						<?= $_SESSION['user']->name ?></a>
					</li>
		
					<li data-icon="refresh"><a href="#updateProfile"><img class="ui-li-mymed" alt="eMail: " src="img/email_icon.png" width="50"
						Style="margin-left: 5px; top: 5px;" /> Email
						<p>
							<?= $_SESSION['user']->email ?>
						</p>
						</a>
					</li>
					<li data-icon="refresh"><a href="#updateProfile"><img class="ui-li-mymed" alt="Date de naissance: " src="img/birthday_icon.png" width="50"
						Style="margin-left: 5px; top: 5px;" /> Date de naissance
						<p>
							<?= $_SESSION['user']->birthday ?>
						</p>
						</a>
					</li>
					<li data-icon="refresh"><a href="#updateProfile"><img class="ui-li-mymed" alt="Langue: " src="img/<?= $_SESSION['user']->lang ?>_flag.png" width="50"
						Style="margin-left: 5px; top: 5px;" /> Langue
						<p>
							<?= $_SESSION['user']->lang ?>
						</p>
						</a>
					</li>
		
			    </ul>
	    
	   		</div>
	    
	    </div>
	    
	    <center><a href="#updateProfile" data-role="button" data-inline="true" data-theme="e" data-icon="pencil"><?= _("Edit") ?></a></center>
		
	</div>
	
	<form action="index.php?action=subscribe" method="POST" data-ajax="false">
			<input type="submit" name="method" value="Get Subscription" data-theme="d" data-inline="true" data-icon="star" data-iconpos="right"/>
	</form>
	
	<?= $this->subscription ?>
	
	<? print_footer_bar_main("#profile"); ?>
	
</div>

<? include_once 'MainView.php'; ?>
<? include_once 'FindView.php'; ?>
<? include_once 'UpdateProfileView.php'; ?>
