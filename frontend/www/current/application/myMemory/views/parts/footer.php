<?php
	//$debug		= true;
	$debug			= false;
	$show_session	= true;
	$show_server	= false;
	$show_post		= false;
	
	
	
	if($debug){
?>
<div data-role="collapsible-set" data-theme="b" data-content-theme="c">
	<div data-role="collapsible">
		<h3>Debug</h3>
		<?php if($show_session) {?>
			<div data-role="collapsible">
				<h3>$_SESSION</h3>
				<pre>
				<?php print_r($_SESSION)?>
				</pre>
			</div>
			<?php } if($show_server) { ?>
			<div data-role="collapsible">
				<h3>$_SERVER</h3>
				<pre>
				<?php print_r($_SERVER)?>
				</pre>
			</div>
			<?php } if($show_post) { ?>
			<div data-role="collapsible">
				<h3>$_POST</h3>
				<pre>
				<?php print_r($_POST)?>
				</pre>
			</div>
			<?php }?>
	</div>					
</div>		

<?php 
	}
?>
	</body>
</html>