<?php
/** Main view */
class MainController extends GuestOrUserController {	
	/** Unique view */
	function defaultMethod() {
		$this->renderView("main");
	}
}