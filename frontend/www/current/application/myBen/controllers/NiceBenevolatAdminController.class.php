<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<? 

/** 
 * Conctroller with actions that should be called duiretly 
 * (by entering URL manually in the navigator),
 * To init NiceBenevolat account :
 * http://<host>:<port>/application/myBen/index.php?action=NiceBenevolatAdmin&method=init
 */
class NiceBenevolatAdminController extends ProfileNiceBenevolatRequired {
	
	public function init() {
		
		// Subscribe to creation of new associations
		$req = new ProfileAssociation();
		$req->valid = "false";
		$req->subscribe();
		
		// Subscribe to creation of new benevoles
		// XXX Hack : Subscribe to both males and females to subscribe to all
		$req = new ProfileBenevole();
		$req->missions = array_keys(CategoriesMissions::$values);
		
		
		// Subscribe to all annonces
		//$req = new Annonce();
		//$req->promue = "false";
		//$req->subscribe();
		
		// Success message
		$this->success = _("Compte NiceBenevolat correctement activé");
		$this->forwardTo("main");
		
	}	
}

?>