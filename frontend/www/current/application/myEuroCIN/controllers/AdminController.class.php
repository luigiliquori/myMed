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
<?php 

/**
 * Admin Controller
 */
class AdminController extends ExtendedProfileRequired {
	
	/* Default method */
	function defaultMethod() {
		
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
		$this->normals = array();
		$this->admins = array();
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
		
		$this->redirectTo("?action=admin");
	}
	
	/* Filter assocation basing on permissions */
	public function userFilter($var) {
		
		//$var->id = filter_var($var->id, FILTER_SANITIZE_EMAIL);
		if (isset($var->id)) {
			
			// Filter association basing on permissions
			if ($var->permission == 1) {
				$this->normals[] = $var;
			} elseif ($var->permission == 2) {
				$this->admins[] = $var;
			}
		}
	}
	
	/** Delete an association extended profile and its announcements */
	public function delete() {
		$this->delete_Comments($_GET['id']);
		$this->delete_Publications($_GET['id']);
		$this->deleteUser($_GET['id']);
	
		$email = $_GET['email'];
		$mailman = new EmailNotification(
					$email,
					_("Your myEuroCIN extended profile has been removed"),
					_("Your association has been removed."));
		$mailman->send();
		
		$this->redirectTo("?action=admin");
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
	
	
	/** Delete user's announcements and comments on them */
	function delete_Publications($id){
		$search_by_userid = new myEuroCINPublication();
		$search_by_userid->publisher = $id;
		$result = $search_by_userid->find();
	
		foreach($result as $publication) :
			$search_comments_publi = new Comment();
			$search_comments_publi->pred1 = 'comment&'.$publication->getPredicateStr().'&'.$id;
			$comments = $search_comments_publi->find();
			foreach($comments as $comment){
				$comment->delete();
			}
			$publication->delete();
		endforeach;
	}
	
	function delete_Comments($id){
		$search_by_userid = new Comment();
		$search_by_userid->publisher = $id;
		$search_by_userid->publisherID = $id;
		$result = $search_by_userid->find();
	
		foreach($result as $item) :
			$item->delete();
		endforeach;
	}
}
?>