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

class BlogController extends ExtendedProfileRequired {
	
	function handleRequest() {
		parent::handleRequest();
		$this->blog = $_GET['id'];
	}
	
	function delete(){
		$request = new RequestJson($this,
			array("application"=>APPLICATION_NAME.":blogs", "id"=>$this->blog, "field"=>$_GET['field']),
			DELETE);
		$request->send();
		
		$this->forwardTo("blog", array("id" => $this->blog));
	}
	
	function create(){
		$t = time();
		$k = hash("crc32b", $t.$_SESSION['user']->id);
		$data = array(
			$k => json_encode(array(
				"time"=>$t,
				"user"=>$_SESSION['user']->id,
				"title"=>$_POST['title'],
				"text"=>$_POST['text']
			))
		);
		$publish = new RequestJson($this,
			array("application"=>APPLICATION_NAME.":blogs", "id"=>$this->blog, "data"=>$data),
			UPDATE);
		$publish->send();
		$subscribe = new RequestJson( $this,
			array("application"=>APPLICATION_NAME.":blogs", "id"=>$this->blog."comments".$k, "user"=> $_SESSION['user']->id, "mailTemplate"=>APPLICATION_NAME.":blogComment"),
			CREATE, "v2/SubscribeRequestHandler");
		$subscribe->send();
		
		$this->redirectTo("blog", array("id" => $this->blog));
	}
	
	function defaultMethod() {
		$find = new RequestJson($this, array("application"=>APPLICATION_NAME.":blogs", "id"=>$this->blog));
			
		try{
			$res = $find->send();
		}
		catch(Exception $e){
			//return null;
		}
		if (isset($res->details)){
			
			$this->messages = array();
			foreach ($res->details as $k=>$v){
				$this->messages[$k] = json_decode($v, true);
			}
			uasort($this->messages, array($this, "timeCmp"));
			
			

			$repArr =  getReputation(array_keys($this->messages));
			
			$this->messages = array_replace_recursive($this->messages, $repArr);
			
			$this->comments = array();
			
			$req = new RequestJson($this, array("application"=>APPLICATION_NAME.":blogs"));

			foreach($res->details as $k => $v){
				$req->addArguments(array("id"=>$this->blog."comments".$k));
				
				$this->comments[$k] = array();
				try{
					$r = $req->send();
				} catch (NoResultException $e) {
					continue;
				}catch(Exception $e){}
				
				if (isset($r->details)){
					
					foreach ($r->details as $ki=>$vi){
						$this->comments[$k][$ki] = json_decode($vi, true);
					}
					$repArr = getReputation(array_keys($this->comments[$k]));
					$this->comments[$k] = array_replace_recursive($repArr, $this->comments[$k]);

					
					uasort($this->comments[$k], array($this, "repCmp"));
					
				}
			}
			
		} else { //it's empty
			$this->messages = array();
		}
		$this->renderView("Blogs");

	}
	
	public function timeCmp($a, $b){
		return  $a['time'] < $b['time'];
	}
	
	public function repCmp($a, $b){
		if ($a['up']-$a['down'] == $b['up']-$b['down'])
			return $this->timeCmp($a, $b);
		return  $a['up']-$a['down'] < $b['up']-$b['down'];
	}

}
?>