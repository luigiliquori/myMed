<?php
/*
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
 */
?>
<?php
/**
 *	This class extends the default myMed user profile, common to every applications, with a profile specific
 * for this application. Store anything you need.
 * Choices have been made that this extended profile will be stored as a Publication in the database.
 * Although, you can change it the way you want in your own application, as long as you use the column families
 * available in cassandra. 
 * 
 * @see ExtendedProfileController
 * @author David Da Silva
 * 
 */
class ExtendedProfile
{

	/**
	 * The ID of the myMed user to whom this ExtendedProfile is linked.
	 */
	public /*String*/ $user_id;
	
	/**
	 * List of the default application for the user
	 */
	public /*Array*/ $applicationList;
	
	public $handler;
	
	public function __construct(IRequestHandler $handler, /*String*/ $user_id,/*array*/ $applicationList){
		
		$this->handler  = $handler;
		$this->user_id = $user_id;
		$this->applicationList = $applicationList;
	}
	
	public function storeFavoriteApps(){
		debug_r($this->applicationList);
		$req = new Requestv2Wrapper(
				$this->handler,
				array("user"=>$this->user_id, 
						"key"=>"applicationList", 
						"value"=>json_encode(array_keys($this->getFavorites($this->applicationList)))
				),
				UPDATE, "v2/ProfileRequestHandler" );
		
		$req->send();
	}
	
	private function getFavorites($list){
		return array_filter($list, function ($status){
			return $status == 'on';
		});
	}
	
	
	
	/**
	 * The extended Profile is stored as a publication in the database.
	 * The predicate is composed of the name of the application, the user_ID + "extendedProfile"
	 */
	public /*void*/ function storeProfile(IRequestHandler $handler){
		
		/*
		 * Build the predicates Array
		 */
		$predicates[] = new OntologyBean("role", "ExtendedProfile", KEYWORD);
		
		/*
		 * Buid the datas Array
		 */
		$datas[] = new OntologyBean("applicationList", json_encode($this->applicationList));
		
		// Build the DataBean
		$dataBean = new DataBean($predicates, $datas);
		
		// Build the request
		$publish = new PublishRequest($handler, $dataBean);
		$publish->send();
		
	}
	
	
	/**
	 * Retreive the ExtendedProfile of a user in the database
	 * @param String $user_id
	 * @return ExtendedProfile
	 */
	public static /* ExtendedProfile */ function getExtendedProfile(IRequestHandler $handler, $user_id){
		
		$predicate = "roleExtendedProfile";
		
		$find = new FindRequest($handler, $predicate, $user_id);
		
		try{
			$result = $find->send();
		}
		catch(Exception $e){
			// Typical Exception raised : No publication found
			// which means the user don't have a ExtendedProfile yet
			return null;
		}
		
		foreach ($result as $line){
			if($line->key == "applicationList"){
				$applicationList = json_decode($line->value, TRUE);
				break;
			}
		}
		
		try {
			return new ExtendedProfile($user_id, $applicationList);
		}
		catch(Exception $e){
			return false;
		}			
	}
}