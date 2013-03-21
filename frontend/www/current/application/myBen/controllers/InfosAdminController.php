<?php

class InfosAdminController extends ProfileNiceBenevolatRequired{
	
	function defaultMethod() {
		$this->renderView("InfosAdmin");
	}
}
?>