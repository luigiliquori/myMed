<?php 

/**
 * Admin Controller
 */
class AdminController extends ExtendedProfileRequired {
	
	/* Default method */
	function defaultMethod() {
		
		// Search for associations and admin 
		$associations = $this->getProfiles('association');
		$admins = $this->getProfiles('admin');
		
		if(isset($associations->results) && isset($admins->results))
			$results = array_merge($associations->results, $admins->results);
		elseif(isset($associations->results))
			$results = $associations->results;
		elseif(isset($admins->results))
			$results = $admins->results;
		
		
		/*$find = new RequestJson( $this, 
					array("application"=>APPLICATION_NAME.":profiles",
						  "predicates"=>array()));
		try{
			$res = $find->send();
		}
		catch(Exception $e){
		}*/
			
		$this->success = "";		
		$this->users = $results;
		$this->blocked = array();
		$this->normals = array();
		$this->admins = array();
		
		// Filter associations
		array_walk($this->users, array($this,"userFilter"));
		
		$this->renderView("admin");
	}
	
	
	/* Search for a specific profile type */
	function getProfiles($type) {
		
		$find = new RequestJson( $this,
				array("application"=>APPLICATION_NAME.":profiles:".$type,
					  "predicates"=>array()));
		try{
			$res = $find->send();
			return $res;
		}
		catch(Exception $e){
			// TODO handle errors here
		}
		return null;
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
				
		$title="";$content="";
		if($_GET['perm']==1 && $_GET['promoted']=="true"){ // has been validated
			
			// Update permissions
			$publish =  new RequestJson($this,
							array("application"=>APPLICATION_NAME.":profiles:".$_GET['profiletype'],
							"id"=>$_GET['id'],
							"data"=>array("permission" => $_GET['perm'], "profiletype" => $_GET['profiletype']),
							"metadata"=>array("permission" => $_GET['perm'], "profiletype" => $_GET['profiletype'])),
							UPDATE);
			$publish->send();
			
			$title = _("Your association has been validated");
			$content = _("Your association has been validated. You can now publish announcement.");
		}
		else if($_GET['perm']==1 && $_GET['promoted']=="false"){ // has been passed to validated from admin
			
			// Remove from admin 
			$publish =  new RequestJson($this,
							array("application"=>APPLICATION_NAME.":profiles:admin",
							"id"=>$_GET['id']),
							DELETE);
			$publish->send();
			
			// Add to associations
			$user = array(
					'permission'=> $_GET['perm'],
					'name'=> $_GET['name'],
					'email'=> $_GET['email'],
					"profiletype"=> "association",
			);
			$publish =  new RequestJson($this,
							array("application"=>APPLICATION_NAME.":profiles:association",
							"id"=>$_GET['id'],
							"data"=>$user,
							"metadata"=>$user),
							CREATE);
			$publish->send();
			
			$title = _("Your association is no longer administrator");
			$content = _("Your association is no longer administrator. Your admin rights have been removed.");
		}else if($_GET['perm']==2) {
			
			// Remove from associations
			$publish =  new RequestJson($this,
							array("application"=>APPLICATION_NAME.":profiles:association",
							"id"=>$_GET['id']),
							DELETE);
			$publish->send();
			// Add to admins
			$user = array(
					'permission'=> $_GET['perm'],
					'name'=> $_GET['name'],
					'email'=> $_GET['email'],
					"profiletype"=> "admin",
			);
			$publish =  new RequestJson($this,
							array("application"=>APPLICATION_NAME.":profiles:admin",
							"id"=>$_GET['id'],
							"data"=>$user,
							"metadata"=>$user),
							CREATE);
			$publish->send();
			
			$title = _("Your association has been promoted administrator");
			$content = _("Your association has been promoted administrator. You are now fully-powered!");
		}
		
		$email = $_GET['email'];
		$mailman = new EmailNotification($email,$title,$content);
		$mailman->send();
		
		
		$this->forwardTo("admin");
	}
	
	/* Filter assocation basing on permissions */
	public function userFilter($var) {
		
		//$var->id = filter_var($var->id, FILTER_SANITIZE_EMAIL);
		if (isset($var->id) && isset($var->profiletype) && #
			($var->profiletype=='association' || 
			 $var->profiletype=='admin')) {
			
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
	
	/** Delete an association extended profile and its announcements */
	public function delete() {
	
		$this->deleteAnnouncements($_GET['id']);
		$this->deleteUser($_GET['id']);
	
		$email = $_GET['email'];
		$mailman = new EmailNotification($email,_("Your association has been removed"),_("Your association has been removed."));
		$mailman->send();
		
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
	
		// Delete profiles:type application
		// Remove from admin
		$publish =  new RequestJson($this,
				array("application"=>APPLICATION_NAME.":profiles:".$_GET['profiletype'],
						"id"=>$_GET['id']),
				DELETE);
		$publish->send();
		
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
	
	
	/** Delete user's announcements and applies on them */
	function deleteAnnouncements($id){
		
		$search_by_userid = new Annonce();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find();
	
		foreach($result as $annonce) :
			$search_applies_annonce = new Apply();
			$search_applies_annonce->pred1 = 'apply&'.$annonce->getPredicateStr().'&'.$id;
			$applies = $search_applies_annonce->find();
			foreach($applies as $apply){
				$apply->delete();
			}
			$annonce->delete();
		endforeach;
	}
}
?>