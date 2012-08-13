<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Stress Test</title>
	</head>
	
	<body Style="background-color: #f4f4f4;">
		<div Style="width:1050px; margin-left: auto; margin-right: auto;">
			<form action="." method="get">
				
				<span Style="position: absolute;">Number of iteration :</span>
				<input type="text" name="iter" value="<?= isset($_REQUEST['iter']) ? $_REQUEST['iter'] : "10" ?>" style="position: relative; margin-left:14em;"/>
				<input type="submit"  value="refresh" />
			</form>
			<br />
			<?php 
			$graphURL = "TestGraph.php";
			$graphURL .= isset($_REQUEST['iter']) ? "?iter=" . $_REQUEST['iter'] : "";
			?>
			<img alt="loading..." src="<?= $graphURL ?>" />
		</div>
	</body>
	
</html>