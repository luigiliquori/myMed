<?php
class AboutController extends  GuestOrUserController{
	
	public function defaultMethod() {
		$this->error = "test message d'erreur";
		$this->renderView("About");
	}
}
?>