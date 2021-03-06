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

/**
 *  This controller shows the search/publish form and receives "search" and "publish" queries.
 *  It renders the views "main" or "results".
 */
class PublishController extends AuthenticatedController {
	
	public /*Array*/ $object;
	public function handleRequest() {
	
		parent::handleRequest();
			
		if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Publier") {
			
			// -- Publish
			
			$obj = new PublishObject();
				
			// Fill the object
			if(!empty($_POST['pred2']) && !empty($_POST['pred3']) && !empty($_POST['data1'])){
				$this->fillObj($obj);
			}else{
				$this->error = _("All the fields are mandatory");
				$this->renderView("publish");
			}
			$obj->publish();
			
			$this->result = $obj;
			$_SESSION['author'] = $_SESSION['user']->id;

			header("location: index.php?action=details&predicate=pred1FSApublicationpred2".$_SESSION['pred2']."pred3".$_SESSION['pred3']."&author=".$_SESSION['author']);
			

				
		}	elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Comment") {
	
			// -- Publish
			
			$obj = new CommentObject();
				
			// Fill the object
			$this->fillObj_comments($obj);
			$obj->publish();
			
			$this->result = $obj;

			$debugtxt  =  "<pre>wyyyyyyniiiik";
			$debugtxt  .= var_export($this->result, TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			header("location: index.php?action=details&predicate=pred1FSApublicationpred2".$_SESSION['pred2']."pred3".$_SESSION['pred3']."&author=".$_SESSION['author']);
			
		} elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "Delete") {

			$obj = new PublishObject();				
			// Fill the object
			$this->fillObj2($obj);			
			$obj->delete();			
			$this->result = $obj;	
			$this->redirectTo("Search");	
		}
		else {
				
			// -- Show the form
		}
	
		$this->renderView("publish");
	}
	
	public function search() {
	
			// -- Search
	
			$search = new PublishObject();
			$this->fillObj($search);
			$this->result = $search->find();			
	}
	
	// Fill object with POST values
	private function fillObj($obj) {
		
		if(isset($_POST['pred2']) && isset($_POST['pred3']) && isset($_POST['data1'])){
		
			$obj->begin = "";
			$obj->end = "";
			$obj->pred1 = "FSApublication";
			$obj->pred2 = $_POST['pred2'];
			
			$title = str_replace('"',"", $_POST['pred3']);
			$title = str_replace('\'',"", $title);
			$title = str_replace('’', "", $title);
			$obj->pred3 = $title;
			
			$desc = str_replace('"',"", $_POST['data1']);
			$desc = str_replace('\'',"", $desc);
			$desc = str_replace('’', "", $desc);
			$obj->data1 = $desc;
			
			$obj->data3 = "";

			$obj->wrapped1 ="";
			$obj->wrapped2 ="";
			
			$_SESSION['begin'] = $obj->begin;
			$_SESSION['end'] = $obj->end;
			$_SESSION['pred2'] = $obj->pred2;		
			$_SESSION['pred3'] = $obj->pred3;
			$_SESSION['data1'] = $obj->data1;
			$_SESSION['data2'] = NULL;
			$_SESSION['rank'] = 5;
		}
	
	}
	
	//for deleting
	private function fillObj2($obj) {
			$obj->pred1 = "FSApublication";
			$obj->pred2 = $_SESSION['pred2'];
			$obj->pred3 = $_SESSION['pred3'];
			$obj->publisherID = $_SESSION['user']->id;
	
	}
	private function fillObj_comments($obj) {		
		$time = time();
		$obj->pred1 = 'comment&'.$_SESSION['pred2'].'&'.$_SESSION['pred3'].'&'.$_SESSION['author'];
		$obj->pred2 = $time;
	
		$obj->wrapped1 =$_POST['data2'];
		$obj->wrapped2 =$_SESSION['user']->profilePicture;
	
	}
 	
}
?>