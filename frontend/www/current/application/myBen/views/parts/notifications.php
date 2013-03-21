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
<? if (!empty($this->error)): ?>
	<div  
		data-role="navbar" 
		data-theme="e"
		class="ui-bar ui-bar-e error-box" >
	
		<p>
		<img alt="Warning: " src="<?= APP_ROOT ?>/img/warning-icon.png" class="ui-li-icon" />
		<span Style="position: relative; top: -10px;"><?= $this->error ?></span>
		<!-- <a href="." data-action="close" data-role="button" data-ajax="false">ok</a>-->
		</p>
		
	</div>
<? endif ?>

<? if (!empty($this->success)): ?>
	<div 
		data-role="navbar"
		data-theme="e"
		class="ui-bar ui-bar-e error-box" >
		
		<p>
		<span Style="position: relative; top: -10px;"><?= $this->success ?></span>
		</p>
			
	</div>
<? endif ?>