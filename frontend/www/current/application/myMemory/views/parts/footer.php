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
	//$debug		= true;
	$debug			= false;
	$show_session	= true;
	$show_server	= false;
	$show_post		= false;
	
	
	
	if($debug){
?>
<div data-role="collapsible-set" data-theme="b" data-content-theme="c">
	<div data-role="collapsible">
		<h3>Debug</h3>
		<?php if($show_session) {?>
			<div data-role="collapsible">
				<h3>$_SESSION</h3>
				<pre>
				<?php print_r($_SESSION)?>
				</pre>
			</div>
			<?php } if($show_server) { ?>
			<div data-role="collapsible">
				<h3>$_SERVER</h3>
				<pre>
				<?php print_r($_SERVER)?>
				</pre>
			</div>
			<?php } if($show_post) { ?>
			<div data-role="collapsible">
				<h3>$_POST</h3>
				<pre>
				<?php print_r($_POST)?>
				</pre>
			</div>
			<?php }?>
	</div>					
</div>		

<?php 
	}
?>
	</body>
</html>