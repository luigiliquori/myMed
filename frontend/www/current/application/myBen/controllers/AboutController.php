<?php
class AboutController extends  GuestOrUserController{
	
	public function defaultMethod() {
		$this->renderView("About");
	}
}
?>