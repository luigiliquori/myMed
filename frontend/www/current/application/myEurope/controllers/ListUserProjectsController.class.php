<?php

class ListUserProjectsController extends ExtendedProfileRequired {
	
	
	public function handleRequest() {
		
		parent::handleRequest();
		
		// Retrieve all the user's projects
		$search = new Partnership();
		$search->publisher = $_SESSION['user']->id;
		$this->result = $search->find();
		
		$this->renderView("ListUserProjects");
		
	}
	
}
?>