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
 * Generic class of a data saved in our DB
 *
 */
class Entry {
	
	/*
	 * have to put them private later
	 */
	public $id;
	public $namespace;
	public $user;
	
	public function __construct(
			$namespace = null,
			$id = null,
			$data = null,
			$metadata = null,
			$index = array()) {

		$this->namespace = $namespace;
		$this->id = $id;
		$this->index = $index;
		$this->data = $data;
		$this->metadata = $metadata;
	}
	
	public function search() {

		$find = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$this->namespace, "predicates"=>$this->index));
		
		$res = $find->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		} else {// Success
			return (array) $res->dataObject->results;
		}
	}
	
	public function read() {
		
		$req = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id));
		
		$res = $req->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		} else {// Success
			return (array) $res->dataObject->details;
		}
	}
	
	
	public function create() {
		
		$t = time();		
		$this->id = hash("md5", $t.$this->user);
		
		$publish = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$this->id, "user"=>$this->user, "data"=>$this->data, "predicates"=>$this->index, "metadata"=>$this->metadata),
				CREATE);
		
		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
	public function update() {
		
		$publish = new RequestPubSub(				
				array("application"=>APPLICATION_NAME.":".$this->namespace, "id"=>$this->id, "user"=>$this->user, "data"=>$this->data, "predicates"=>$this->index, "metadata"=>$this->metadata),
				UPDATE);
		
		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
	public function delete() {
		$publish = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$this->namespace,"id"=>$this->id),
				DELETE);

		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
	public function setUser($user){
		$this->user = $user;
	}
	
	
}









?>