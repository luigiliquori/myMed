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
<? require_once('notifications.php'); ?>

<div data-role="page">

  <?php $title = _("Details");
  	// TODO: cannot return to ResultsView, but onlyl to search view
	print_header_bar("?action=main#search", false, $title);
  ?>
	
	<div data-role="content" >
	
		<?print_notification($this->success.$this->error);?>
	
		<div data-role="collapsible-set" data-theme="c" data-content-theme="d">

			<div data-role="collapsible" data-collapsed="false">
			
			<h3><?= _("Description") ?></h3>
								
				<div>
					<!-- TITLE -->
					<h3><?= $this->result->title ?> :</h3>
					
					<b><?= _('Deadline') ?></b>: <?= $this->result->end ?><br/>
					<b><?= _("Themes") ?></b>: <?= Categories::$themes[$this->result->theme] ?><br/>
					<b><?= _("Program") ?></b>: <?= Categories::$calls[$this->result->other] ?><br/><br/>
					
					<!-- TEXT -->
					<b><?= _("Description")?></b>: <?= $this->result->text ?>
				
					<!-- CONTACT -->			
					<p>
						<b><?= _("Author")?></b>: 
					 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) {?>
							<a href="<?= '?action=extendedProfile&user='.$this->result->publisherID ?>"><?= str_replace("MYMED_", "", $this->result->publisherID) ?></a><br/>
					 <? }else{ ?>
					 		<?= str_replace("MYMED_", "", $this->result->publisherID) ?> <br>
					 <? } ?>
					</p>
					
					<!-- REPUTATION -->
		    		<div style="position: relative; left:0px">
		    			<p style="display:inline;" ><?= _("Project reputation")?>: </p>
							<?php
								// Disable reputation stars if there are no votes yet 
								if($this->reputation["value_noOfRatings"] == '0') : ?> 
									<?php for($i=1 ; $i <= 5 ; $i++) {?>
											<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
									<?php } ?>
									
							<?php else: ?>
								<?php for($i=1 ; $i <= 5 ; $i++) { ?>
									<?php if($i*20-20 < $this->reputation["value"] ) { ?>
										<img alt="rep" src="img/yellowStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;" />
									<?php } else { ?>
										<img alt="rep" src="img/grayStar.png" width="12" Style="left: <?= $i ?>0px; margin-top:3px;"/>
									<?php } ?>
								<? } ?>
							<?php endif; ?>
						<p style="display:inline; font-size:80%;"> <?php echo $this->reputation["value_noOfRatings"] ?> <?= _("rates")?> </p>
					 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest) :?>
					 	 <? if($this->result->publisherID != $_SESSION['user']->id){ ?>
								<a data-role="button" data-mini="true" data-icon="star" href="#popupReputationProject" data-rel="popup" data-inline="true" style="text-decoration:none;" ><?= _("Rate project") ?></a>	
					 	 <?	} 
					 	endif; ?>
					</div>
					
					<div data-role="popup" id="popupReputationProject" class="ui-content" Style="text-align: center; width: 18em;">
						<?= _("Do you like the project idea ?") ?><br /><br />
						<form id="form1" action="#" method="get" data-ajax="false">
							<input type="hidden" name="action" value="updateReputation" />
							<input type="hidden" name="isData" value="1" />
							<input type="hidden" name="predicate" value="<?= $_GET['predicate'] ?>" />
							<input type="hidden" name="author" value="<?= $_GET['author'] ?>" />
							<label for="reputation"><p style="display:inline; color: #2489CE; font-size:80%;"> <?= _("Assign a value from 1 (Poor idea) to 5 (Great idea!!)") ?></p><br/></label>
							<input type="range" name="reputation" id="reputation" value="3" min="1" max="5" data-mini="true" step="1"/>
							<input type="submit" value=<?= _("Send")?> data-mini="true" data-theme="g"/>
						</form>
					</div>
					<!-- END REPUTATION -->
				</div>
				
			 <? if(isset($_SESSION['user']) && !$_SESSION['user']->is_guest): ?>
				<!-- SHARE THIS -->
				<div style="position: absolute; right: 24px;">
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="my_Europe" data-url="<?= str_replace('@','%40','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])?>">Tweet</a>
    				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>
					
					<div style="position: absolute; right: 95px;">
				<g:plusone size="tall"></g:plusone>
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'en';}</script>
				</div>
				
				<?
				$text = strip_tags($this->result->text, '<div>');
				$text = str_replace('"',"", $text);
				$text = str_replace('\'',"", $text);
				$text = str_replace('’', "", $text);
				$text = htmlspecialchars($text);
				?>
				<!-- SHARE ON Facebook WITH DESC TITLE IMG -->
				<div style="position: absolute; right: 150px; padding-top:40px;">
					<script src='http://connect.facebook.net/en_US/all.js'></script>
					<a href="javascript:postToFeed('<?= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>','<?= $this->result->title ?>', '<?= $text ?>', '<?= str_replace("MYMED_", "", $this->result->publisherID) ?>', '<?= APPLICATION_NAME ?>')"><img src="img/facebookShare.png"/></a>	
				</div>
					
				<!-- Facebook_APP_ID defined in system/config.php -->
				<div id="fb-root"></div>
			    <script>  
					FB.init({appId: <?= Facebook_APP_ID?>, status: true, cookie: true, xfbml: true});

				    function postToFeed(url, title, desc, author, appname) {
				    	FB.login(function(response) {
				            if (response.authResponse) {
				            	fbShare(url, title, desc, author, appname);
				            }
				        }, {scope: 'publish_stream'});
				    }
				      
				    var fbShare = function(url, title, desc, author, appname) {
				    	FB.ui({
					    	method: 'feed',
					        display: "iframe",
					        link: url,
					        picture: 'http://www.mymed.fr/application/myMed/img/logo-mymed-250c.png',
					        name: (title+" Author: "+author),
					        caption: appname,
					        description: desc
				    	});
					};
			    </script>
				<div style="height: 80px;"></div>
	    		<!-- END SHARE THIS -->
				<? endif; ?>
			</div>
		
		</div>
		
	</div>
	
</div>
