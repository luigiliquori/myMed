<?php
/*
 * Copyright 2012 INRIA
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
require_once dirname(__FILE__).'/Requestv2.php';
//require_once dirname(dirname(__FILE__)) . '/beans/DataBeanv2.php';
require_once dirname(__FILE__).'/IRequestHandler.php';

/**
 *
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class PublishRequestv2 extends Requestv2 {

	private /* IRequestHandler */ 	$handler;
	private /* string  */	    	$id;
	private /* string */			$namespace;
	private /* array    */			$data;
	private /* array    */			$metadata;
	private /* array  */	    	$index;
	public /* int  */	         	$min;
	private /* int  */	        	$max;
	
	public function __construct(
		$handler, $namespace=null, $id, $data=null, $index=null, $metadata=null, $min=null, $max=null )
	{
		parent::__construct("v2/PublishRequestHandler", CREATE);
		$this->handler	 = $handler;
		$this->id        = $id;
		$this->namespace = $namespace;
		$this->data      = $data;
		$this->metadata  = $metadata;
		$this->index     = $index;
		$this->min       = $min;
		$this->max       = $max;

		//$this->setMultipart(true);
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function send() {		
		// Build the request
		
		parent::addArgument("application", APPLICATION_NAME);
		parent::addArgument("id", $this->id);
		if (!empty($this->namespace))
			parent::addArgument("namespace", $this->namespace);
		if (!empty($this->index))
			parent::addArgument("index", json_encode($this->index));
		if (!empty($this->data))
			parent::addArgument("data", json_encode($this->data));
		if (!empty($this->metadata))
			parent::addArgument("metadata", json_encode($this->metadata));
		if (!empty($this->min))
			parent::addArgument("min", $this->min);
		if (!empty($this->max))
			parent::addArgument("max", $this->max);
		
		$responsejSon = parent::send();
		$responseObject = json_decode($responsejSon);
			
		if ($responseObject->status != 200) {
			if (is_null($this->handler)) {
				throw new Exception("Error in PublishRequest: " . $responseObject->description);
			} else {
				$this->handler->setError($responseObject->description);
			}
		} 
			
	}
}
?>
