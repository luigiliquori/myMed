<!--
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
 -->
<!-- 1st Page : Publish -->
<div id="publish" data-role="page">

	<? print_header_bar(false, true); ?>
	
	<div data-role="content">
	
		<? include_once 'notifications.php'; ?>
	
		<form action="index.php?action=main" method="POST" data-ajax="false">
			
			<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
			
				<div data-role="collapsible" data-collapsed="false">
					<h3>Predicates</h3>
					<input type="text" name="pred1" placeholder="pred1"/>
					<input type="text" name="pred2" placeholder="pred2"/>
					<input type="text" name="pred3" placeholder="pred3"/>
				</div>
				
				<div data-role="collapsible" data-collapsed="true">
					<h3>Specific meta data</h3>
					<input type="text" name="begin"placeholder="begin"/>
					<input type="text" name="end" placeholder="end"/>
				</div>
	
				<div data-role="collapsible" data-collapsed="true">
					<h3>Generic meta data</h3>
					<input type="text" name="wrapped1" placeholder="wrapped1"/>
					<input type="text" name="wrapped2" placeholder="wrapped2"/>
				</div>
				
				<div data-role="collapsible" data-collapsed="true">
					<h3>Data</h3>
					<input type="text" name="data1" placeholder="data1"/>
					<input type="text" name="data2" placeholder="data2"/>
					<input type="text" name="data3" placeholder="data3"/>
				</div>
				
				<center><input type="submit" name="method" value="Publish" data-theme="g" data-inline="true" data-icon="edit" /></center>
			</div>
			
		</form>
			
	</div>
	
	<? print_footer_bar_main("#publish"); ?>

</div>

<? include_once 'FindView.php'; ?>
<? include_once 'ProfileView.php'; ?>
<? include_once 'UpdateProfileView.php'; ?>
