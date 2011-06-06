<div class="appBig">
	<div class="appTitle">myProfile</div>
	
	<div class="appContentBig">
		<!-- USER PICTURE PROFILE -->
		<div style="width: 200px; height: 245px; background-color: #edf2f4;">
			<img width="200px" alt="profile picture" src="<?= $_SESSION['user']->profile_picture ?>" /><br />
			<br />
			<!-- USER INFOMATION -->
			<table style="position: absolute; left:200px; top:0px; text-align: left;">
			<tr>
			  <th>Name:</th>
			  <td> <?= $_SESSION['user']->name ?></td>
			</tr>
			<tr>
			  <th>Gender:</th>
			  <td> <?= $_SESSION['user']->gender ?></td>
			</tr>
			<tr>
			  <th>Langage:</th>
			  <td> <?= $_SESSION['user']->locale ?></td>
			 </tr>
			<tr>
			   <th>Profile from:</th>
			   <td> <?= $_SESSION['user']->social_network ?></td>
			  </tr>
			<tr>
			   <td colspan="2"><input type="button" value="modifier le profile"></td>
			</tr>
			</table>
		</div>
	</div>
</div>