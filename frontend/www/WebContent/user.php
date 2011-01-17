<!-- USER PICTURE PROFILE -->
<div style="width: 200px; height: 300px; background-color: #edf2f4;">
	<img alt="" src="http://graph.facebook.com/<?= $user->id ?>/picture?type=large"><br />
</div>

<!-- USER INFOMATION -->
<table>
  <!-- 
  <tr>
     <th>Id:</th>
 	 <td> <span id="userId"><?= $user->id ?></span></td>
  </tr>
  -->
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
   <th>up-time:</th>
   <td> <?= $user->updated_time ?></td>
  </tr>
</table>

<hr style="position: relative; width: 200px; margin-left:0px;"/>

<!-- DEBUG
<form action="http://mymed2.sophia.inria.fr:8080/services/DebugConsole" >
	      	<input type="hidden" name="act" value="4" />
	      	<input type="hidden" name="key1" value="<?= $user->id ?>" />
	      	<input type="hidden" name="value1" value="<?= $user->name ?><br><?= $user->gender ?><br><?= $user->locale ?><br><?= $user->updated_time ?><br>" />
	      	<input type='submit' value='Debut Tool' />
</form>

<hr style="position: relative; width: 200px; margin-left:0px; margin-top:10px;"/>
 -->
