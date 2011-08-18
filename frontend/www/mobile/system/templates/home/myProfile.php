<div id="myProfile" data-role="page" data-theme="a">
	
	<!-- HEADER -->
	<div id="header" data-role="header">
		<h1>myMed profile</h1>
	</div>

	<!-- CONTENT -->
	<div id="content" data-role="content" id="one">
		
		<!-- NOTIFICATION -->
		<?php if($updateProfileHandler->getError()) { ?>
			<div style="color: red;">
				<?= $updateProfileHandler->getError(); ?>
			</div>
		<?php } else if($updateProfileHandler->getSuccess()) { ?>
			<div style="color: #12ff00;">
				<?= $updateProfileHandler->getSuccess(); ?>
			</div>
		<?php } ?>
		
		<!-- Profile -->
		<div style="text-align: left;">
			<?php if($_SESSION['user']->profilePicture != "") { ?>
				<img alt="thumbnail" src="<?= $_SESSION['user']->profilePicture ?>" height="200">
			<?php } else { ?>
				<img alt="thumbnail" src="http://graph.facebook.com//picture?type=large">
			<?php } ?>
			<br><br>
			Prenom: <?= $_SESSION['user']->firstName ?><br />
			Nom: <?= $_SESSION['user']->lastName ?><br />
			Date de naissance: <?= $_SESSION['user']->birthday ?><br />
			eMail: <?= $_SESSION['user']->email ?><br />
			Reputation: 
			 <?php 
		    	$rand = rand(0, 4);
		    	$j=0;
		    	while($j<=$rand){ ?>
		    		<img alt="star" src="img/star.png" width="20" />
		    		<?php 
		    		$j++;
		    	}
		    	while($j<=4){ ?>
		    		<img alt="star" src="img/starGray.png" width="20" />		
		    		<?php 
		    		$j++;
		    	}
		    ?>
		    <br /><br />
		    <a href="#inscription" data-role="button" data-rel="dialog">mise à jour</a>
		</div>
	</div>
	
	<!-- FOOTER_PERSITENT-->
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#home">Home</a></li>
				<li><a href="#myProfile" class="ui-btn-active">myProfile</a></li>
				<li><a href="#login" onclick="document.disconnectForm.submit()">Deconnexion</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->
			
</div>

<!-- UPDATE PROFILE -->
<?php include('updateProfile.php') ?>