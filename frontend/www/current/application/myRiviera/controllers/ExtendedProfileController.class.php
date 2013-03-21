<?php

class ExtendedProfileController extends AbstractController{

	/**
	 * HandleRequest
	 */
	public function handleRequest() {}
	
	public function defaultMethod() {
		$this->renderView('option');
	}
}
?>