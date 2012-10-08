<?php

class InfosCandidaturesController extends ProfileBenevoleRequired{
	
	function defaultMethod() {
		$this->renderView("InfosCandidatures");
	}
}