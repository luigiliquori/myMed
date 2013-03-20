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
<!-- 2nd Page : Find/Subscribe -->
<div id="find" data-role="page">

	<? print_header_bar(false, true, "Find view", false); ?>
	
	<div data-role="content">
	
		<form action="index.php?action=main" method="POST" data-ajax="false">
			
			<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
			
				<div data-role="collapsible" data-collapsed="false">
					<h3>Predicates</h3>
					<input type="text" name="pred1" placeholder="pred1"/>
					<input type="text" name="pred2" placeholder="pred2"/>
					<input type="text" name="pred3" placeholder="pred3"/>
				</div>
				
				<center>
					<div data-role="controlgroup" data-type="horizontal">
						<input type="submit" name="method" value="Search" data-theme="g" data-inline="true" data-icon="search" />
						<input type="submit" name="method" value="Subscribe" data-theme="d" data-inline="true" data-icon="star" data-iconpos="right"/>
					</div>
				</center>
			</div>
			
		</form>
			
	</div>
	
	<? print_footer_bar_main("#find"); ?>

</div>

