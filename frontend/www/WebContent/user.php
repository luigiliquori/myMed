<!-- USER PICTURE PROFILE -->
<div style="width: 200px; height: 300px; background-color: #edf2f4;">
	<img width="200px" alt="profile picture" src="<?= $user->profile_picture ?>"><br />
</div>

<!-- USER INFOMATION -->
<table>
<tr>
  <th>Name:</th>
  <td> <?= $user->name ?></td>
</tr>
<tr>
  <th>Gender:</th>
  <td> <?= $user->gender ?></td>
</tr>
<tr>
  <th>Langage:</th>
  <td> <?= $user->locale ?></td>
 </tr>
<tr>
   <th>Profile from:</th>
   <td> <?= $user->social_network ?></td>
  </tr>
</table>

