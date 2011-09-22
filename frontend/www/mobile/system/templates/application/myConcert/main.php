<!DOCTYPE html> 
<html> 
	<head>
	<title>myMed | Mobile UI</title>
	
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	
	<link rel="stylesheet" href="css/style.css" />
	<link href="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.css" rel="stylesheet" />
	
	<script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
	
	<!-- CSS -->
	<link rel="stylesheet" href="system/templates/application/myConcert/css/style.css" />
	
	<?php require_once dirname(__FILE__).'/MyConcert.class.php'; ?>
	
	</head> 

	<body> 

		<?php 
		$myConcert = new MyConcert();
		$myConcert->printTemplate();
		?>
		
		<script type="text/javascript">
			function scroller(){
				window.scrollTo(0, 40);
			}
			setTimeout("scroller()", 500);
		</script>
	</body>
	
</html>