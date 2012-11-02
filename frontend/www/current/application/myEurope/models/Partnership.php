<?php

require_once('Entry.php');

class Partnership extends Entry {
	
	public /* array of ExtendedProfile*/ $partnersProfiles;  // first is the owner, others who joined, might sort them by rep, at the end unshift partner at the top of list highlighted
	
	//construct index from the request url values, post or get
	
	public function __construct(
			$id = null,
			$data = null,
			$metadata = null,
			$index = array()) {
		parent::__construct("part", $id, $data, $metadata, $index);
	}
	 
	
	public function setIndex($request) {
		$this->index = array();
		foreach (array('t', 'pf', 'pi', 'po', 'r', 'k') as $el){
			if (!isset($request[$el])){
				$request[$el] = array();
			}
		}
		if (!isset($request['c']) || $request['c'] == "libre"){
			$request['c'] = ""; //equivalent to all choices
		}
		
		$this->untranslate($request['k']);
		
		//the order matters because I was too lazy to sort it backend-side
		$this->index[] = new DataBeanv2("t", ENUM, join("|", $request['t'])); //themes
		$this->index[] = new DataBeanv2("p", ENUM, join("|", array_merge($request['pf'], $request['pi'], $request['po']))); //places
		$this->index[] = new DataBeanv2("r", ENUM, join("|", $request['r'])); //roles of profiles
		$this->index[] = new DataBeanv2("c", ENUM, $request['c']); // call
		$this->index[] = new DataBeanv2("k", ENUM, join("|", $request['k'])); // keywords
	}
	
	public function setBroadcastIndex($request) {
		$this->index = array();
		foreach (array('t', 'pf', 'pi', 'po', 'k') as $el){
			if (!isset($request[$el])){
				$request[$el] = array();
			}
		}
		if (!isset($request['c']) || $request['c'] == "libre"){
			$request['c'] = ""; //equivalent to all choices
		}
		
		$this->untranslate($request['k']);
		
		//the order matters because I was too lazy to sort it backend-side
		$this->index[] = new DataBeanv2("t", ENUM, '|'.join("|",  $request['t'])); //themes
		$this->index[] = new DataBeanv2("p", ENUM, '|'.join("|", array_merge($request['pf'], $request['pi'], $request['po']))); //places
		$this->index[] = new DataBeanv2("r", ENUM, '|'.$request['r']); //roles of profiles
		$this->index[] = new DataBeanv2("c", ENUM, '|'.$request['c']); // call
		$this->index[] = new DataBeanv2("k", ENUM, '|'.join("|", $request['k'])); // keywords
	}
	
	
	public function parseProfile() {
		$this->users = array();
		foreach ($this->details as $k => $v){
			if (strpos($k, "user_") === 0){
				unset($this->details[$k]); // some cleaning for debug
				$this->users[] = $v;
			}
		}
	}
	
	/*public function makeKeywords($k){
		
		$k = preg_split('/[ ,+:-]/', $k, NULL, PREG_SPLIT_NO_EMPTY);
		$k = array_map('strtolower', $k);
		//$k = array_filter($k, function ($w){return strlen($w) > 2;});
		return array_unique($k);

	}*/
	
	public function untranslate(& $keywords){
		// translate back the keywords sent in the user's locale
		
		debug_r($keywords);
		$all = array_map("gettext", Categories::$keywords);
		foreach ($keywords as $k=>$v){
			$i = array_search($v, $all);
			var_dump($i);
			if ( $i !== false ){
				$keywords[$k] = Categories::$keywords[$i];
			}
		}
		debug_r($keywords);	
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
				$t .= ''.$v->value.' ';
			}
		}
		return $t;
	}
	
}









?>