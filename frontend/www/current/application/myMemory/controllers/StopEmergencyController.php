<?php

class StopEmergencyController extends AuthenticatedController {


	/**
	 * @see IRequestHandler::handleRequest()
	 */
	public /*void*/ function handleRequest(){
		
		parent::handleRequest();
		
		/*
		 * Determine the dehaviour :
		 * POST data ->  Store the profile
		 * No Post but ExtendedProfile in session -> show profile
		 * Nothing -> show the form to fill the profile
		 */
		if (isset($_GET["confirm"]))
		{
			if($_GET["confirm"] == "ok")
			{
				//TODO : stop emergency
				$this->redirectTo("main");
			}
		}
		else
			$this->renderView("StopEmergency");
		
	}


}
?>