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
<? 
class CommentController extends AuthenticatedController {
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Comment") {
			if(!empty($_POST['wrapped1'])){
				
				$obj = new Comment();	
				$time = time();
				$obj->publisher = $_SESSION['user']->id;    // Student ID
				$obj->pred1 = 'comment&'.$_SESSION['predicate'].'&'.$_SESSION['author'];
				$obj->pred2 = $time;
				$obj->wrapped1 =$_POST['wrapped1'];
				$obj->wrapped2 =$_SESSION['user']->profilePicture;
				
				$obj->publish();
			}
			header("location: index.php?action=details&predicate=".$_SESSION['predicate']."&author=".$_SESSION['author']);
		}
	}
}
?>