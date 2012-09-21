<?php

require('Document.php');

class Partnership extends Document {
	
	public /* array of ExtendedProfile*/ $partnersProfiles;  // first is the owner, others who joined, might sort them by rep, at the end unshift partner at the top of list highlighted
	
	//construct index from the request url values, post or get
	
	public function __construct(
			IRequestHandler $handler = null,
			$id = null,
			$data = null,
			$metadata = null,
			$index = null) {
		parent::__construct($handler, "part", $id, $data, $metadata, $index);
	}
	 
	
	public function initSearch($request) {
		$this->index = array();
		foreach (array('t', 'pf', 'pi', 'po', 'r') as $el){
			if (!isset($request[$el])){
				$request[$el] = array();
			}
		}
		if ($request['c'] == "libre"){
			$request['c'] = ""; //equivalent to all choices
		}
		
		//the order matters because I was too lazy to sort it backend-side
		$this->index[] = new DataBeanv2("t", ENUM, join("|", $request['t'])); //themes
		$this->index[] = new DataBeanv2("p", ENUM, join("|", array_merge($request['pf'], $request['pi'], $request['po']))); //places
		$this->index[] = new DataBeanv2("r", ENUM, join("|", $request['r'])); //roles of profiles
		$this->index[] = new DataBeanv2("c", ENUM, $request['c']); // call
		$this->index[] = new DataBeanv2("k", ENUM, join("|", $this->makeKeywords($request['k']))); // keywords
	}
	
	public function initCreate($request) {
		$this->index = array();
		foreach (array('t', 'pf', 'pi', 'po') as $el){
			if (!isset($request[$el])){
				$request[$el] = array();
			}
		}
		if ($request['c'] == "libre"){
			$request['c'] = ""; //equivalent to all choices
		}
		
		//the order matters because I was too lazy to sort it backend-side
		$this->index[] = new DataBeanv2("t", ENUM, '|'.join("|",  $request['t'])); //themes
		$this->index[] = new DataBeanv2("p", ENUM, '|'.join("|", array_merge($request['pf'], $request['pi'], $request['po']))); //places
		$this->index[] = new DataBeanv2("r", ENUM, '|'.$request['r']); //roles of profiles
		$this->index[] = new DataBeanv2("c", ENUM, '|'.$request['c']); // call
		$this->index[] = new DataBeanv2("k", ENUM, '|'.join("|", $this->makeKeywords($request['k']))); // keywords
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
		//$k = array_filter($k, function ($w){return strlen($w) > 2;});
		return array_unique($k);

	}
	
	public function isIndexNotEmpty(){

		return	strlen($this->index[0]->value)
			 || strlen($this->index[1]->value)
			 || strlen($this->index[2]->value)
			 || strlen($this->index[3]->value)
			 || strlen($this->index[4]->value);
	}
	
	public function renderSearchIndex(){
		$t = "";
		foreach ($this->index as $i => $v){
			if (!empty($this->index[$i]->value)){
				$t .= '<span style="font-size: 14px;background-color: white;padding: 3px;border-radius: 10px;-moz-border-radius:10px;">'.$v->value.'</span> ';
			}
		}
		return $t;
	}
	
}









?>