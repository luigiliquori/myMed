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

/**
 * Display a notification pop up for errors or success
 */
function print_notification($message) {

	if($message != "") {
		echo '<div data-role="popup" id="notificationPopup" data-transition="flip" data-theme="e" class="ui-content"><p>' . $message . ' </p></div>';
		echo '
		<script type="text/javascript">
			var first_pageshow = true;
			$(document).on("pageshow", function() {
				if(first_pageshow) {
				  	// Go back in the history has problem in Chrome. 
				  	// Do not return back in the history when the pop up disappear.
				  	$( "#notificationPopup" ).popup({ history: false });
		    		$( "#notificationPopup" ).popup("open");
		    		first_pageshow = false;
	    		}
	     	});
		
		 </script>
		 ';
	}  
}

?>