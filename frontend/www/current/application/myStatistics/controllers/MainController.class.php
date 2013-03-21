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
<?

/**
 * This controller is the main controller of the statistics application.
 * it shows the statistics about publish and subscribe in myMed applications
 *
 */ 
require_once MYMED_ROOT. '/lib/dasp/request/Request.class.php';

class MainController extends AuthenticatedController {
	
	public static function getBootstrapApplication(){
	 	return MainController::$bootstrapApplication;
	}

	/**
	* Request handler method is calling when we use
	* ?action=main in the address
	*/
	public function handleRequest() {
		parent::handleRequest();
		if(isset($_POST['first-select-curve'])){
			$response=$this->sendRequestToBackend($_POST['select-method'], $_POST['select-year'], $_POST['select-month'], $_POST['select-application']);
			$this->tabrep = $this->analyzeBackendResponse($response);
		}
		$this->renderView("main");
	}

	function generateGraph($response){
		$this->array_resp_return = array();
		$this->max_array_value=0;
		if((isset($_POST['select-month'])&& $_POST['select-month']!= "all") || (isset($_POST['select-year']) && $_POST['select-year']!="all")){
			if($_POST['select-month']!=all){
				$this->nbdaymonth= $this->getNumberDaysForMonth($_POST['select-month'],$_POST['select-year']);
				$max = $this->nbdaymonth;
			}
			else{
				$max=12;
			}
			for($i=1;$i<=$max;$i++){
				$str = (string) $i;
				$count=0;
				if(isset($response->$str)){
					$count = $response->$str;
				}
				if($count > $this->max_array_value){
					$this->max_array_value = $count;
				}
				$this->array_resp_return[$i]=$count;
			}
		}
		if(isset($_POST['select-year']) && $_POST['select-year']=="all" && $_POST['select-month']== "all"){
			
		}
		$this->generateTitle();
	}
	
	function generateTitle(){
		$this->title = "Graph of ".$_POST['select-method']." method for ".$_POST['select-application']." application ";
		if(isset($_POST['select-month'])&& $_POST['select-month']!= "all"){
			$this->title= $this->title."from 1/".$_POST['select-month']." to ". $this->nbdaymonth."/".$_POST['select-month'];
		}
		if(isset($_POST['select-year']) && $_POST['select-year']!="all"){
			$this->title = $this->title." ".$_POST['select-year'];
		}
	}
	
	function getNumberDaysForMonth($month,$year){
		return date('t', mktime(0,0,0,$month,1,$year));
	}
	
	function sendRequestToBackend($method,$year,$month,$application){
		$request = new Request("StatisticsRequestHandler", READ);
		$request->addArgument("accessToken",$_SESSION['accessToken']);
		$request->addArgument("code",1);
		$request->addArgument("application",$application);
		$request->addArgument("method",$method);
		if($year != "all"){
			$request->addArgument("year",$year);
		}
		if($month != "all"){
			$request->addArgument("month",$month);
		}
		
		return $request->send();
		
	}
	
	function analyzeBackendResponse($response){
		echo $response;
		$resp_obj = json_decode($response);
		$str = (string)4;
		if($resp_obj->status != 500){
			$this->generateGraph($resp_obj->dataObject->statistics);
		}
	}
}
?>