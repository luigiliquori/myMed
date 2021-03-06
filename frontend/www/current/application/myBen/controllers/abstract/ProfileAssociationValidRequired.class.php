<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<?php 

/** Extended profile required, with type == ASSOCIATION and Validated */
class ProfileAssociationValidRequired extends ProfileAssociationRequired {

	public function __construct() {
		parent::__construct(ASSOCIATION);
	}

	public function handleRequest() {
		parent::handleRequest();
		if (!(($this->extendedProfile instanceof ProfileNiceBenevolat)  || is_true($this->extendedProfile->valid))) {
			throw new UserError(
					"Votre association n'a pas encore été validée par Nice Bénévolat.
					Pour celà, merci de leur communiquer une copie de votre parution au Journal Officiel.");
		}
	}

	/** Static request handler */
	static public function check() {
		$instance = new ProfileAssociationValidRequired();
		$instance->handleRequest();
	}
}

?>