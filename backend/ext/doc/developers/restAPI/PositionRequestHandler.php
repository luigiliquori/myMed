<div class="requestHandlerName"><h3>PositionRequestHandler</h3></div>
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
    <td class="method"></td>
    <td class="arguments"></td>
	<td class="response"></td>
	<td class="doc"></td>
  </tr>
  <tr>
    <td class="handler">READ</td>
    <td class="method">GET</td>
    <td class="arguments">
    	<ul>
		  <li>code</li>
		  <li>userID</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">position</td>
	<td class="doc">
		<p>Retourne la dernière position (geoloc) connue d'un utilisateur</p>
	</td>
  </tr>
  <tr>
    <td class="handler">UPDATE</td>
    <td class="method">POST</td>
    <td class="arguments">
        <ul>
		  <li>code</li>
		  <li>userID</li>
		  <li>position</li>
		  <li>accessToken</li>
		</ul>
    </td>
	<td class="response"></td>
	<td class="doc">
		<p>Mise à jour de la position (geoloc) d'un utilisateur.</p>
		 <div class="json">position : {<br />
		  <div>userID : "",</div>
		  <div>latitude : "",</div>
		  <div>longitude : ""</div>
		  <div>city : ""</div>
		  <div>zipCode : ""</div>
		  <div>country : ""</div>
		  <div>formattedAddress : ""</div>
		  }
		  </div>
	</td>
  </tr>
  <tr>
    <td class="handler">DELETE</td>
    <td class="method"></td>
    <td class="arguments"></td>
	<td class="response"></td>
	<td class="doc"></td>
  </tr>
</table>