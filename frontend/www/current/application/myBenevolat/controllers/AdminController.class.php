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
	
}
?>