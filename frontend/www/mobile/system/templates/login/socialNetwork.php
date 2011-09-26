<div id="socialNetwork" data-role="page">
	<!-- HEADER -->
	<div id="header" data-role="header" data-theme="b" >
		<h2>Selection</h2>
	</div>

	<!-- CONTENT -->
	<div data-role="content" id="one" data-theme="b">
	  <div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js"></script>
      <script>
         FB.init({ 
            appId:'<?= FACEBOOK_APP_ID ?>', cookie:true, 
            status:true, xfbml:true 
         });
      </script>
      <fb:login-button>Login with Facebook</fb:login-button>
	</div>
</div>