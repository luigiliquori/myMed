<?php
	//$debug = true;
	$debug = false;
	
	
	
	if($debug){
?>
<div data-role="collapsible-set" data-theme="b" data-content-theme="c">
			<div data-role="collapsible">
				<h3>$_SESSION</h3>
				<pre>
				<?php print_r($_SESSION)?>
				</pre>
			</div>			
		</div>		

<?php 
	}
?>
	</body>
</html>