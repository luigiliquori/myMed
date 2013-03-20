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
class AdminController extends ExtendedProfileRequired {
	

	function defaultMethod() {
		$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":users", "predicates"=>array()));
		
		try{
			$res = $find->send();
		}
		catch(Exception $e){
		}
			
		$this->success = "";
			
		$this->users = $res->results;
			
		$this->blocked = array();
		$this->normals = array();
		$this->admins = array();
		array_walk($this->users, array($this,"userFilter"));
		
		$this->renderView("admin");
		
	}
	
	function updatePermission(){
	
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users", "id"=>$_GET['id'], "data"=>array("permission" => $_GET['perm']), "metadata"=>array("permission" => $_GET['perm'])),
				UPDATE);
		
		$publish->send();
		
		$this->forwardTo("admin");
	}
	
	public function userFilter($var){
		//$var->id = filter_var($var->id, FILTER_SANITIZE_EMAIL);
		if (isset($var->id)){
			if ($var->permission < 1){
				$this->blocked[] = $var;
			} else if ($var->permission == 1){
				$this->normals[] = $var;
			} else {
				$this->admins[] = $var;
			}
		}
	}
	
}
?>