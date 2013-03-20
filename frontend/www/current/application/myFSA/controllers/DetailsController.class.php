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
class DetailsController extends AuthenticatedController {
	
	//public /*String*/ $user; //not needed because is taken from session
	public /*String*/ $author;//consumer
	public /*String*/ $predicate;
	public /*String*/ $feedback;
	public /*String*/ $comments;
	public /*String*/ $users;
	public /*String*/ $pictures;
	public /*String*/ $result_comment;
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		//keyword is set in the jquery file when user set rank on jquery plugin
		if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] == "Reputation") {
			$this->feedback = $_REQUEST['rate']/5;
			$this->storeReputation();
			//$this->getMark();
			$this->renderView("main");
		
		}
		else {
			//$_SESSION['test']=(isset($_SESSION['disabled']))?true:false;
						
			// Get arguments of the query
			$this->predicate = $_GET['predicate'];
			$_SESSION['predicate'] = $_GET['predicate'];
			$this->author = $_GET['author'];
			
			// Create an object
			$obj = new PublishObject($this->predicate);
			$obj->publisherID = $this->author;
			
			// Fetches the details
			$obj->getDetails();
				
			// Give this to the view
			$this->result = $obj;
			
			//during the way predicate is lost so here it is extracting
			$string = $_GET['predicate'];
			$_SESSION['predicate'] = $string;
			preg_match_all('/pred2([a-zA-Z0-9-_\s\#]+)pred3/', $string, $matches);
			preg_match_all('/pred3([a-zA-Z0-9-_\s\#]+)/', $string, $matches2);
				
			$_SESSION['author'] = $this->author;
			$_SESSION['pred2'] = $matches[1][0];
			$_SESSION['pred3'] = $matches2[1][0];
					
			$_SESSION['begin'] = $this->result->begin;
			$_SESSION['end'] = $this->result->end;
			$_SESSION['data1'] = $this->result->data1;
			$_SESSION['data2']= $this->result->data2;
		
			$this->search_comment();
			$this->getMark();
			
			$debugtxt  =  "<pre>AUTHOOOOOOOOOOOOOOOOOOR";
			$debugtxt  .= var_export($_SESSION['author'], TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			
			$debugtxt  =  "<pre>USEEEEEEEEEEEEEEEEEEEEEER";
			$debugtxt  .= var_export($_SESSION['user']->id, TRUE);
			$debugtxt .= "</pre>";
			debug($debugtxt);
			
			$this->renderView("details");
			
		}
	}
	public /*void*/ function storeReputation(){
			
		$rep = new Ranking($_SESSION['user']->id,$_SESSION['predicate'], $this->feedback);
		$rep->storeReputation();
			
	}
	public /*void*/ function getMark(){
			
		$rep2 = new Ranking($_SESSION['user']->id,$_SESSION['predicate'],'');
		//$rep = new Ranking(array('name' => 'John'));
		$rep2->getMark();
			
	}
	
	//searching comments
	public function search_comment() {
	
		// -- Search	
		$searchk = new PublishObject();
		$this->fillObj($searchk);
		$this->result_comment = $searchk->find();
		
	}
	
	// Fill object with POST values
	private function fillObj($obj2) {
	
			$obj2->pred1 = 'comment&'.$_SESSION['pred2'].'&'.$_SESSION['pred3'].'&'.$_SESSION['author'];
	
	}

	
	
}
?>