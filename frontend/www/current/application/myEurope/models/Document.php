<?php

class Document {
	
	public $id;
	public $title;
	public $time;
	public $user;
	public $partner;  //first is the owner
	
	public $partners;  // others who joined, might sort them by rep, at the end unshift partner at the top of list highlighted
	
	
	// categories
	public $themes;
	public $roles;
	public $places;
	public $calls;
	public $keywords;
	
	public $index; // wrapper of categories
	
	
	
	
}









?>