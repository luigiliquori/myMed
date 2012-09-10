<? include("header.php"); ?>
<div data-role="page" id="PublishView" data-theme="a">
<div class="wrapper">
<div data-role="header" data-theme="a">
<a data-theme="a" data-rel="back" data-role="button"  data-icon="back" >Back</a>
<h3>myFSA</h3>
<a href="?action=logout" rel="external" data-role="button" data-theme="a">Quit</a>
</div>

<div data-role="content" data-theme="a">
	
	<ul data-role="listview" class="ui-listview">
		<li class="ui-li ui-li-static ui-body-a ui-li-has-thumb">
			<img src="<?=$_SESSION['user']->profilePicture?>" alt="Your photo here" class="ui-li-thumb"/>
			<h3 class="ui-li-heading"><?=$_SESSION['user']->name?></h3>
			<p class="ui-li-desc"><?=$_SESSION['user']->login?></p>
		</li>

		<?php if ($_SESSION["profileFilled"] == "company") {?>
		<li class="ui-li ui-li-static ui-body-a">
			<h3 class="ui-li-heading"> Full profile information</h3>
			<div>
						
				<!-- 	displaying array:
				
						$object = array( 
						"type" => $_POST["ctype"],
						"name" => $_POST["cname"],
						"address" => $_POST["caddress"],
						"number" => $_POST["cnumber"]); -->
			
					<br> Company type : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['type']?></a>
					
					<br> Company name :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['name']?></a>
					
					<br> Company address : <br/>
					<a data-role="label" ><?= $_SESSION['ExtendedProfile']->object['address']?></a>
					
					<br> Company number :<br/>
					<a data-role="label"><?= $_SESSION['ExtendedProfile']->object['number']?></a>
			</div>
		</li>		
		<?php }?>
	</ul>	
</div>
<? include("footer.php"); ?>