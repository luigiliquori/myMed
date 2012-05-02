<div class="requestHandlerName"><h3>SessionRequestHandler</h3></div>
<table class="restAPITable">
  <tr>
    <th class="handlerHead">Request</th>
    <th class="methodHead">Method</th>
    <th class="argumentsHead">Arguments</th>
    <th class="responseHead">Response.data</th>
    <th class="docHead">Doc</th>
  </tr>
  <tr>
    <td class="handler">CREATE</td>
    <td class="method">POST</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>userID</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
		<ul>
		  <li>accessToken</li>
		  <li>url</li>
		</ul>
	</td>
	<td class="doc">
		<p>Creation d'une session pour un utilisateur donné.
		Utilisé pour les login avec des réseaux sociaux existant (facebook, twitter, ...)
		permet de recuperer un accessToken sans avoir besoin de se logger</p>
	</td>
  </tr>
  <tr>
    <td class="handler">READ</td>
    <td class="method">GET</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>socialNetwork</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
	    <ul>
		  <li>user</li>
		</ul>
	</td>
	<td class="doc">
		<p>Verification si la session existe ou pas.
		l'accessToken est l'ID de la session</p>
	</td>
  </tr>
  <tr>
    <td class="handler">UPDATE</td>
    <td class="method">POST</td>
    <td class="arguments"></td>
	<td class="response"></td>
	<td class="doc"></td>
  </tr>
  <tr>
    <td class="handler">DELETE</td>
    <td class="method">GET</td>
    <td class="arguments">
    	<ul>
		  <li>code</li>
		  <li>socialNetwork</li>
		  <li>accessToken</li>
		</ul>
    </td>
	<td class="response"></td>
	<td class="doc"><p>Suppression de la session (logout)</p></td>
  </tr>
</table>