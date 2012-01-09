<?php 
		session_start();
		$_SESSION['user'] = json_decode('{
		  "id": "visiteur",
		  "name": "Visiteur",
 		  "gender": "something",
		  "locale": "somewhere",
		  "updated_time": "now",
		  "profile": "http://www.facebook.com/profile.php?id=007",
		  "profile_picture" : "http://graph.facebook.com//picture?type=large",
		  "social_network" : "unknown"
		}');
?>
