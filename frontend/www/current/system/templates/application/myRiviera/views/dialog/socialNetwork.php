<div id="socialNetwork" data-role="page" data-theme="c">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b">
		<a href="#loginView" data-role="button" class="ui-btn-left" data-icon="arrow-l" data-back="true">Retour</a>
		<h3> </h3>
	</div>

	<!-- CONTENT -->
	<div data-role="content" style="text-align: center;">
		<h3>Connectez-vous avec votre compte:</h3>
		
		<!-- CONNECTION FACEBOOK -->
 	    <div id="fb-root"></div>
	    <script>
	        window.fbAsyncInit = function() {
	          FB.init({
	            appId      : '<?= Facebook_APP_ID ?>',
	            status     : true, 
	            cookie     : true,
	            xfbml      : true,
	            oauth      : true,
	          });
	          FB.Event.subscribe('auth.login', function(response) {
	              window.location.reload();
	            });
	        };
	        (function(d){
	           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	           js = d.createElement('script'); js.id = id; js.async = true;
	           js.src = "//connect.facebook.net/en_US/all.js";
	           d.getElementsByTagName('head')[0].appendChild(js);
	         }(document));
	    </script>
	    <div class="fb-login-button" scope="email,read_stream">Login with Facebook</div>
	    <!-- END CONNECTION FACEBOOK -->
	    
	</div>
</div>