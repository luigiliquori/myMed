<?php

require('Document.php');

class Partnership extends Document {
	
	public /* array of ExtendedProfile*/ $partnersProfiles;  // first is the owner, others who joined, might sort them by rep, at the end unshift partner at the top of list highlighted
	
	//construct index from the request url values, post or get
	public function initSearch($request) {
		$this->index = array();
		//the order matters because I was too lazy to sort it backend-side
		$this->index[] = new DataBeanv2("t", ENUM, $request['t']); //themes
		$this->index[] = new DataBeanv2("p", ENUM, $request['p']); //places
		$this->index[] = new DataBeanv2("r", ENUM, $request['r']); //roles of profiles
		$this->index[] = new DataBeanv2("c", ENUM, $request['c']); // call
		$this->index[] = new DataBeanv2("k", ENUM, $request['k']); // keywords
	}
	
	public function initCreate($request) {
		$this->index = array();
		//the order matters because I was too lazy to sort it backend-side
		$this->index[] = new DataBeanv2("t", ENUM, '|'.$request['t']); //themes
		$this->index[] = new DataBeanv2("p", ENUM, '|'.$request['p']); //places
		$this->index[] = new DataBeanv2("r", ENUM, '|'.$request['r']); //roles of profiles
		$this->index[] = new DataBeanv2("c", ENUM, '|'.$request['c']); // call
		$this->index[] = new DataBeanv2("k", ENUM, '|'.$request['k']); // keywords
	}
	
	
	public function fetchPartnersProfiles($res) {
		
		$this->partnersProfiles = array();
		
		foreach ($res as $k => $v){
			if (strpos($k, "user_") === 0){
				$p = new ExtendedProfile($v);
				$this->partnersProfiles[$p->id]= $p->readFromUser();
			}
		}
		//sort them by rep
		
		//author
		$p = new ExtendedProfile($this->user);
		$authorProfile = $p->readFromUser();
		
		$this->partnersProfiles = array_merge(array($this->user=>$authorProfile, $this->partnersProfiles));
		
	}
	
	public function makeKeywords($k){
		
		$k = preg_split('/[ ,+:-]/', $k, NULL, PREG_SPLIT_NO_EMPTY);
		$k = array_map('strtolower', $k);
		$k = array_filter($k, array($this, "smallWords"));
		$k = array_unique($k);
		return join("|",$k);
	}
	
	private function smallWords($w){
		return strlen($w) > 2;
	}
	
}









?>