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
<? require_once('header-bar.php'); ?>
<? require_once('notifications.php'); ?>

<div data-role="page" id="users">
  
  <? $title = _("Administration");
	 print_header_bar("?action=ExtendedProfile", "defaultHelpPopup", $title); ?>
	
	<div data-role="content">
		<br/>
		<!-- <a type="button" href="?action=ExtendedProfile&list" data-mini="true" data-inline="true" rel="external"  data-icon="list" title="<?= _('list of organizations profiles in myEurope') ?>"><?= _("Profiles list") ?></a>-->
				
		<a type="button" data-inline="true" data-mini="true" data-theme="e" data-icon="warning-sign" style="float: right;"
		onclick='subscribe($(this), "<?= APPLICATION_NAME ?>:users", "<?= APPLICATION_NAME ?>:users", []); $(this).addClass("ui-disabled");'><?= _("Notify me of new users") ?></a>
		
		<input id="user_permission" name="permission" value="" type="hidden" />
		<input id="user_id" name="id" value="" type="hidden" />
		<br /><br />
		<ul data-role="listview" data-filter="true" >
			<li data-role="list-divider"><?= _("New users waiting for validation") ?><span class="ui-li-count"><?= count($this->blocked) ?></span></li>
		<? foreach( $this->blocked as $i => $item ) : ?>
			<li>
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"><span class="<?= $item->id==$_SESSION['user']->id?"ui-link":"" ?>"><?= $item->email ?></span></a>
	        	<a rel="external" data-icon="sort-down" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
			<li data-role="list-divider"><?= _("Normal users") ?><span class="ui-li-count"><?= count($this->normals) ?></span></li>
		<? foreach( $this->normals as $i => $item ) : ?>
			
			<li>
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"><span class="<?= $item->id==$_SESSION['user']->id?"ui-link":"" ?>"><?= $item->email ?></span></a>
				<a rel="external" data-icon="sort-up" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission - 1 ?>" data-iconpos="notext" data-inline="true" data-role="button" style="position:absolute; top:0; right:42px;"><?= $item->permission ?></a>
	        	<a rel="external" data-icon="sort-down" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission + 1 ?>" data-theme="e"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
			<li data-role="list-divider"><?= _("Admins") ?><span class="ui-li-count"><?= count($this->admins) ?></span></li>
			<? foreach( $this->admins as $i => $item ) : ?>
			<li>
				<a href="?action=ExtendedProfile&user=<?= urlencode($item->id) ?>"><span class="<?= $item->id==$_SESSION['user']->id?"ui-link":"" ?>"><?= $item->email ?></span></a>
				<a rel="external" data-icon="sort-up" href="?action=Admin&method=updatePermission&id=<?= urlencode($item->id) ?>&perm=<?= $item->permission - 1 ?>" data-theme="d"><?= $item->permission ?></a>
			</li>
		<? endforeach ?>
		</ul>
		<br /><br />
		<!-- <div data-role="collapsible" data-content-theme="c">
		   <h3><?= _("Add partnerships")?></h3>
		   <form action="index.php?action=DB" method="post" data-ajax="false">
		   	  <input type="hidden" name="method" value="addPartnership">
		 	  <textarea rows="" cols="" name="data"></textarea>
		 	  <div style="text-align: center;">
		 	  <input type="submit" value="send" data-inline="true" data-theme="g"/>
		 	  </div>
		   </form>
		</div>-->
		
	</div>
	
	<!-- ----------------- -->
	<!-- DEFAULT HELP POPUP -->
	<!-- ----------------- -->
	<div data-role="popup" id="defaultHelpPopup" data-transition="flip" data-theme="e" Style="padding: 10px;">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
		<h3><?= _("Manage users") ?></h3>
		<p> <?= _("Here you can change user category. <br /> Categories are: 'Admin', 'Normal user' and 'New User'.") ?></p>
		
	</div>
	
</div>



<? include("footer.php"); ?>