
<!-- MYSTORE -->
<div id="mystore" style="position: absolute; left: 227px;">
	<table>
	  <tr>
	    <td>
		    <a href="#" onclick="displayWindow('#warning')"><b>New applications</b></a> | 
			<a href="#" onclick="displayWindow('#warning')">Top 10 applications</a> | 
			<a href="#" onclick="displayWindow('#warning')">all applications</a>
	    </td>
	    <td>
		    <form action="get">
				<input type="text" style="width: 300px;" />
				<input type="submit" value="rechercher" disabled="disabled" />
			</form>
	    </td>
	  </tr>
	</table>
</div>

<!-- MYSTORE CONTENT -->
<div id="store" class="application" style="position:absolute; top:30px; left:230px; text-align: center; color: white; display: none;">
	<img alt="" src="img/myStoreCS.png" width="700px;" height="500px;" />
	<input type="button" value="Close" onclick="fadeOut('#store')" style="position: relative; top:-50px;" />
</div>
