<?php

class Template {
	
	
	
	const APPLICATION_NAME = 'myEurope';
	
	public static function init(  $redirect = true ) {
		
		if (!defined('APP_ROOT')) {
			define('APP_ROOT', __DIR__);
		}
		if (!defined('APP_URL')) {
			define('APP_URL', $_SERVER['HTTP_HOST'].'/application/'.Template::APPLICATION_NAME);
		}
		require_once '../../lib/dasp/request/Request.v2.php';
		require_once '../../system/config.php';
		require_once '../../lib/dasp/beans/OntologyBean.php';
		
		// Init gettext() locales
		
		$langcode = explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$langcode = explode(",", $langcode['0']);
		$locale = $langcode['0'].'.utf8';
		$filename = 'myEurope';
		putenv("LC_ALL=$locale");
		setlocale(LC_ALL, $locale);
		bindtextdomain($filename, './../../lang');
		textdomain($filename);
				
		if(session_id() == '') {
			session_start();			
		}
		if ($redirect && !isset($_SESSION['user'])) {
			if (!strpos($_SERVER['REQUEST_URI'], "extended") !==false){
				$_SESSION['redirect'] = '.'.substr($_SERVER['REQUEST_URI'], strlen('/application/'.Template::APPLICATION_NAME));
			}
			header("Location: ./authenticate");
		}
		
	}
	
	public static function fetchExtProfile () {
		
		$request = new Request("v2/PublishRequestHandler", READ);
		$request->addArgument("application", Template::APPLICATION_NAME);
		$request->addArgument("predicate", "ext");
		$request->addArgument("userID", $_SESSION['user']->id);
		$responsejSon = $request->send();
		return json_decode($responsejSon); 
	}
	
	
	public static function updateDataReputation ( $detail, $dataRep, $application, $id, $author ) {
		//construct data queryBean
		$dataList=array();
		foreach( $detail as $value ) {
			if ($value->key=="rate"){
				$dataList[$value->key] = array("valueStart"=>$value->value, "valueEnd"=> 100-$dataRep, "ontologyID"=>$value->ontologyID);
			} else {
				$dataList[$value->key] = array("valueStart"=>$value->value, "valueEnd"=>$value->value, "ontologyID"=>$value->ontologyID);
			}
		}
		
		$request = new Request("v2/FindRequestHandler", UPDATE);
		$request->addArgument("application", $application);
		$request->addArgument("predicateList", json_encode($dataList));
		
		$request->addArgument("id", $id);
		$request->addArgument("level", 3); // this will be enough since we insert everything with level <=3
		$request->addArgument("userID", $author );
		
		$responsejSon = $request->send();
		
		
		$dataList=array();
		array_push($dataList, array("key"=>"rate", "value"=> 100-$dataRep, "ontologyID"=>FLOAT));
		$request = new Request("v2/PublishRequestHandler", UPDATE);
		$request->addArgument("application", $application);
		$request->addArgument("data", json_encode($dataList));
		$request->addArgument("id", $id);
		$request->addArgument("userID", $author );
		
		$responsejSon = $request->send();
		return json_decode($responsejSon);
	}


	public static function head(){ ?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>myEurope</title>
<link rel="stylesheet"
	href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
<link rel="stylesheet" href="my.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
        </script>

<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js">
        </script>

<script src="app.js">
        </script>

<!--     	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"> -->
<!--     	</script> -->
<?php }

	public static function credits(){ ?>

<div class="footer" >
	<h4  style="margin: 10px;">myMed - INTERREG IV - Alcotra</h4>
	<img alt="Alcotra" src="/system/img/logos/alcotra" />
	<img alt="Conseil Général 06" src="/system/img/logos/cg06" />
	<img alt="Regine Piemonte" src="/system/img/logos/regione"/>
	<img alt="Europe" src="/system/img/logos/europe" />
	<img alt="Région PACA" src="/system/img/logos/PACA" />
	<img alt="Prefecture 06" src="/system/img/logos/pref" />
	<img alt="Inria" src="/system/img/logos/inria.png" />
	<p style="margin: 8px; font-weight: normal;">"Ensemble par-delà les frontières"</p>
</div>

<?php }


	static function isPredicate($var) {
		return($var->ontologyID < 4);
	}

	static function isCheckbox($var) {
		return($var == "on");
	}

}
?>