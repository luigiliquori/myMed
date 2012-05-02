<div class="requestHandlerName"><h3>ProfileRequestHandler</h3></div>
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
		  <li>user</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
		<ul>
		  <li>user</li>
		</ul>
	</td>
	<td class="doc">
		<p>Création d'un profil utilisateur (sans authentication)
		Utile pour créer un profil à partir d'info facebook ou twitter (pas besoin de login et password)</p>
	</td>
  </tr>
  <tr>
    <td class="handler">READ</td>
    <td class="method">GET</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>id</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
	    <ul>
		  <li>user</li>
		</ul>
	</td>
	<td class="doc">
		<p>Lecture du profil correspondant à l'id</p>
	</td>
  </tr>
  <tr>
    <td class="handler">UPDATE</td>
    <td class="method">POST</td>
    <td class="arguments">
    	<ul>
		  <li>code</li>
		  <li>user</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
	    <ul>
		  <li>user</li>
		</ul>
	</td>
	<td class="doc"><p>Mise à jour du profil de l'utilisateur</p></td>
  </tr>
  <tr>
    <td class="handler">DELETE</td>
    <td class="method">GET</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>id</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response"></td>
	<td class="doc"><p>Suppression du profil de l'utilisateur</p></td>
  </tr>
</table>