<?php

class CreateMyMedProfileController extends GuestOrUserController{
	
	public function defaultMethod() {
		$this->renderView("CreateMyMedProfile");
	}
}
?>