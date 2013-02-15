<?php 

/**
 * Admin Controller
 */
class AdminController extends ExtendedProfileRequired {
	
	/* Default method */
	function defaultMethod() {
		
		// Search all myBenevolatUser
		$find = new RequestJson( $this, 
					array("application"=>APPLICATION_NAME.":users",
						  "predicates"=>array()));
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
		
		// Filter associations
		array_walk($this->users, array($this,"userFilter"));
		
		$this->renderView("admin");
	}
	
	
	/* Update Permission */
	function updatePermission() {
		
		$publish =  new RequestJson($this,
						array("application"=>APPLICATION_NAME.":users", 
						"id"=>$_GET['id'], 
						"data"=>array("permission" => $_GET['perm']), 
						"metadata"=>array("permission" => $_GET['perm'])),
						UPDATE);
		$publish->send();
		
		$this->forwardTo("admin");
	}
	
	/* Filter assocation basing on permissions */
	public function userFilter($var) {
		
		//$var->id = filter_var($var->id, FILTER_SANITIZE_EMAIL);
		if ( isset($var->id) && isset($var->profiletype) && #
			 ($var->profiletype=='association')) {
			
			// Filter association basing on permissions
			if ($var->permission < 1){
				$this->blocked[] = $var;
			} else if ($var->permission == 1){
				$this->normals[] = $var;
			} else {
				$this->admins[] = $var;
			}
		}
	}
	
	/** Delete an association extended profile and its publication */
	public function delete() {
	
		$this->deletePublications($_GET['id']);
		$this->deleteUser($_GET['id']);
	
		$this->forwardTo("admin");
	}
	
	
	/** Delete a user and its profile */
	function deleteUser($id) {
	
		// Delete the user if exists
		$find = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users",
						"id"=>$id));
		try{
			$user = $find->send();
		} catch(Exception $e) {
		}
		if (isset($user)){
			$publish = new RequestJson(
					$this,
					array("application"=>APPLICATION_NAME.":profiles",
							"id"=>$user->details->profile,
							"field"=>"user_".$id),
					DELETE);
			$publish->send();
		}
	
		$publish = new RequestJson($this,
				array("application"=>APPLICATION_NAME.":users",
						"id"=>$id),
				DELETE);
		$publish->send();
	
		// Session myBenevolat is not still valid
		unset($_SESSION['myBenevolat']);
	
		$this->success = "done";
	
	}
	
	
	/** Delete a user profile */
	function deleteProfile($id){
	
		$publish = new RequestJson(
				$this,
				array("application"=>APPLICATION_NAME.":profiles","id"=>$id),
				DELETE);
		$publish->send();
	
		$this->success = "done";
		$this->renderView("main");
	}
	
	
	/** Delete user's publications */
	function deletePublications($id){
	
		$search_by_userid = new myBenevolatPublication();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find();
	
		foreach($result as $item) :
		$item->delete();
		endforeach;
	
		//$this->forwardTo('extendedProfile', array("user"=>$_SESSION['user']->id));
	}
	
}
?>