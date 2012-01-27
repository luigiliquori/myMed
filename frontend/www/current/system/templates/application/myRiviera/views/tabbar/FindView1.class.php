<?php

require_once 'system/templates/application/' . APPLICATION_NAME . '/MyApplication.class.php';
require_once 'lib/dasp/beans/MDataBean.class.php';

/**
 * 
 * Represent the template
 * @author lvanni
 *
 */
class FindView1 extends MyApplication {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct("Find1");
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	private /*MPositionBean*/ function getPosition(/*String*/ $userID){
		$request = new Request("PositionRequestHandler", READ);
		$request->addArgument("userID", $userID);
		$responsejSon = $request->send();
		$responseObject = json_decode($responsejSon);
		if($responseObject->status == 200) {
			return json_decode($responseObject->data->position);
		} else {
			return null;
		}
	}
	
	/**
	 * Get the CONTENT for jQuery Mobile
	 */
	public /*String*/ function getContent() { ?>
		<!-- CONTENT -->
		<div class="content">
			<form  action="#" method="post" name="<?= APPLICATION_NAME ?>FindForm1" id="<?= APPLICATION_NAME ?>FindForm1">
				<!-- Define the method to call -->
				<input type="hidden" name="application" value="<?= APPLICATION_NAME ?>" />
				<input type="hidden" name="method" value="find" />
				<input type="hidden" name="numberOfOntology" value="4" />
				
					
				<!-- FROM -->
				<div>
					<img Style="position: absolute; left: 4%; width:7%; height:7%;" id="departpicture" alt="thumbnail" src="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png" /><br />
					<div Style="position: relative; left: 15%; width:80%; text-align: left;"  >Commune, Adresse, Lieu public, Arrêt :</div>
					<input type="text" id="depart"  name="Départ" Style="position: relative; left: 15%; width:80%;"  >	
					<select id="selectdepart" name="enum" onchange="changeDestination('depart')">
                        <!-- DEFAULT -->                      
						<option value="nullpart&&007">Lieux Disponibles</option>
	
						<!-- USER -->
						<?php if (isset($_SESSION['position'])) {?>
							<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
						<?php } ?>
	
						<!-- FRIENDS -->
						<?php
						if(isset($_SESSION['friends'])) {
							foreach ($_SESSION['friends'] as $friend ) { ?>
								<?php if ($friend["position"]->formattedAddress != "") {?>
										<option
											value="<?= $friend["profilePicture"] ?>&&<?= $friend["position"]->formattedAddress ?>">
											<?= $friend["name"] ?>
										</option>
								<?php }
							}
						} ?>
					</select>
				</div>

				<!-- SEPARATOR -->
				<div >
					<br /><br />
				</div>

				<!-- TO -->
				<div>
					<img Style="position: absolute; left: 4%; width:7%; height:7%;" id="arriveepicture" alt="thumbnail" src="http://www.poledream.com/wp-content/uploads/2009/10/icon_map2.png" width="80" height="80" style="" /><br /> 
					<div Style="position: relative; left: 15%; width:80%; text-align: left;"  >Commune, Adresse, Lieu public, Arrêt :</div>
					<input type="text" id="arrivee"  name="Arrivée" Style="position: relative; left: 15%; width:80%;"  >	
					<select id="selectarrivee" name="enum" onchange="changeDestination('arrivee')">

                        <!-- DEFAULT -->                      
						<option value="nullpart&&007">Lieux Disponibles</option>
	
						<!-- USER -->
						<?php if (isset($_SESSION['position'])) {?>
							<option value="<?= $_SESSION['user']->profilePicture ?>&&<?= $_SESSION['position']->formattedAddress ?>"><?= $_SESSION['user']->name ?></option>
						<?php } ?>
	
						<!-- FRIENDS -->
						<?php
						if(isset($_SESSION['friends'])) {
							foreach ($_SESSION['friends'] as $friend ) { ?>
								<?php if ($friend["position"]->formattedAddress != "") {?>
										<option
											value="<?= $friend["profilePicture"] ?>&&<?= $friend["position"]->formattedAddress ?>">
											<?= $friend["name"] ?>
										</option>
								<?php }
							}
						} ?>
                    </select>
				</div>
				
				<!-- SEPARATOR -->
				<div >
					<br /><br />
				</div>
				
				<!-- DATE -->

				<div data-role="fieldcontain">
					<label for="date" >Partir le :</label>
					<input name="date" id="date" type="date" data-role="datebox" data-options='{"noButtonFocusMode": true, "mode": "calbox", "disableManualInput": true}'>
				</div>
				<div data-role="fieldcontain">
					<label for="time" >à :</label>
					<input name="time" id="time" type="date" data-role="datebox" data-options='{"noButtonFocusMode": true, "mode": "timeflipbox", "disableManualInput": true}'>
				</div>
				
				
				<!-- SEPARATOR -->
				<div >
					<br /><br />
				</div>
				
				<a href="#" data-role="button" onclick="document.<?= APPLICATION_NAME ?>FindForm1.submit()">Calculer l'itinéraire</a>	
			</form>
		</div>
	<?php }
	
}
?>
