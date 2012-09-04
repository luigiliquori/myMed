<?


function getProfilefromUser($handler, $id){

	$find = new RequestJson($handler, array("application"=>APPLICATION_NAME.":users", "id"=>$id));
	try{ $user = $find->send();
	} catch(Exception $e){
	}
	if (isset($user)){
		return getProfile($handler,$user->details->profile);
	}
	return null;
}

function getProfile($handler,$id){

	$find = new RequestJson($handler, array("application"=>APPLICATION_NAME.":profiles", "id"=>$id));

	try{ $res = $find->send();
	} catch(Exception $e){
	}
	if (isset($res)){
		$profile = $res->details;
		$handler->success = "";
		$users = array();
		$partnerships = array();
		foreach ($res->details as $k => $v){
			if (strpos($k, "user_") === 0){
				array_push($users, $v);
			} else if(strpos($k, "part_") === 0){
				array_push($partnerships, $v);
			}
		}
		sort($users);
		$profile->users = $users;
		$profile->partnerships = $partnerships;
		$rep =  new Reputationv2($handler, null, $id);
		$profile->reputation = $rep->send();
		return $profile;
	}
	return null;
}






?>