<div class="requestHandlerName"><h3>AuthenticationRequestHandler</h3></div>
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
		  <li>authentication</li>
		  <li>user</li>
		  <li>application</li>
		</ul>
	</td>
	<td class="response"></td>
	<td class="doc">
		<p>Creation d'un profil temporaire (en attente de vaidation par email):</p>
		<ul>
		  <li>user : Profil de l'utilisateur</li>
		  <li class="json">authentication : {<br />
		  <div>login : "",</div>
		  <div>user : "",</div>
		  <div>password : ""</div>
		  }
		  </li>
		  <li>application : l'application lié à l'inscription</li>
		</ul>
	</td>
  </tr>
  <tr>
    <td class="handler">CREATE</td>
    <td class="method">POST</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response"></td>
	<td class="doc">
		<p>Validation d'un profile (apres avoir cliqué sur le lien dans l' email de confirmation)</p>
	</td>
  </tr>
  <tr>
    <td class="handler">READ</td>
    <td class="method">POST</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>login</li>
		  <li>password</li>
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
    <td class="handler">UPDATE</td>
    <td class="method">POST</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>authentication</li>
		  <li>oldLogin</li>
		  <li>oldPassword</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response"></td>
	<td class="doc">
		<p>Mise à jour du login et du password d'un utilisateur</p>
	</td>
  </tr>
  <tr>
    <td class="handler">DELETE</td>
    <td class="method">GET</td>
    <td class="arguments"></td>
	<td class="response"></td>
	<td class="doc"></td>
  </tr>
</table>