<?php 
/**
 *  DataMapper Class
 *   that takes a Model Class and use a Request Class 
 *   to perform common database operations
 */

class DataMapper {
	
	public function findByPredicate( $data ){
		debug(APPLICATION_NAME.$data->namespace." ");
		debug_r($data->index);
		$find = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$data->namespace,
						"predicates"=>$data->index));
	
		$res = $find->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		} else {// Success
			return (array) $res->dataObject->results;
		}
	}
	
	public function findById( $data ){
	
		$req = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$data->namespace,
						"id"=>$data->id));
	
		$res = $req->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		} else {// Success
			return (array) $res->dataObject->details;
		}
	}
	
	
	public function save( $data ) {
	
		if (!isset($data->user)){
			throw new Exception("user no set");
		}
		
		$t = time();
		$data->id = hash("md5", $t.$data->user);
	
		$publish = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$data->namespace, 
						"id"=>$data->id, 
						"user"=>$data->user, 
						"data"=>$data->data, 
						"predicates"=>$data->index, 
						"metadata"=>$data->metadata),
				CREATE);
	
		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
	public function update( $data ) { // do alwost same as save
		// will merge that in backend
	//just one save method, if no metadata is given then no reindexation stuff, just pure data update
		$publish = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$data->namespace, 
						"id"=>$data->id, 
						"user"=>$data->user, 
						"data"=>$data->data, 
						"predicates"=>$data->index, 
						"metadata"=>$data->metadata),
				UPDATE);
	
		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
	public function remove( $data ) {
		$publish = new RequestPubSub(
				array("application"=>APPLICATION_NAME.":".$data->namespace,"id"=>$data->id),
				DELETE);
	
		$res = $publish->send();
		if ($res->status != 200 ){
			throw new Exception($res->description);
		}
	}
	
}
?>