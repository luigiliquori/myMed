<?php 
if(isset($_GET["password"])) {
	echo hash('sha512', $_GET["password"]);
} else {
	echo '{
			"error": {
			"type": "InternalBackEndException",
			"status":  "500",
			"message": "password argument is missing!"
			}
		}';
}
?>