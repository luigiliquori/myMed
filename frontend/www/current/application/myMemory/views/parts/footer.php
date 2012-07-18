<?php
	$debug = true;
	//$debug = false;
	
	
	
	if($debug){
?>
<div data-role="collapsible-set" data-theme="b" data-content-theme="c">
	<div data-role="collapsible">
		<h3>Debug</h3>
			<div data-role="collapsible">
				<h3>$_SESSION</h3>
				<pre>
				<?php print_r($_SESSION)?>
				</pre>
			</div>
			<div data-role="collapsible">
				<h3>$_SERVER</h3>
				<pre>
				<?php print_r($_SERVER)?>
				</pre>
			</div>
			<div data-role="collapsible">
				<h3>$_POST</h3>
				<pre>
				<?php print_r($_POST)?>
				</pre>
			</div>
	</div>					
</div>		

<?php 
	}
?>
	</body>
</html>