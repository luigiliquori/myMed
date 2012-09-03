<div class="requestHandlerName"><h3>POIRequestHandler</h3></div>
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
		  <li>application</li>
		  <li>type</li>
		  <li>userID</li>
		  <li>latitude</li>
		  <li>longitude</li>
		  <li>value</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response"></td>
	<td class="doc"><p>Insertion d'un nouveau point d'interêt</p></td>
  </tr>
  <tr>
    <td class="handler">READ</td>
    <td class="method">GET</td>
    <td class="arguments">
    	<ul>
		  <li>code</li>
		  <li>application</li>
		  <li>type</li>
		  <li>latitude</li>
		  <li>longitude</li>
		  <li>radius</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">pois</td>
	<td class="doc">
		<p>Retourne Tous les POIs de type "type" autour de "latitude", "longitude" avec un radius "radius"</p>
		<div class="json">pois : [<br />
		  <div>{
			  <div>id : "",</div>
			  <div>locationId : "",</div>
			  <div>latitude : ""</div>
			  <div>longitude : ""</div>
			  <div>value : ""</div>
			  <div>distance : ""</div>
			  <div>date : ""</div>
			  <div>expirationDate : ""</div>
			  },<br />
			  ...
		  </div>
		  ]
		  </div>
	</td>
  </tr>
  <tr>
    <td class="handler">UPDATE</td>
    <td class="method"></td>
    <td class="arguments"></td>
	<td class="response"></td>
	<td class="doc"></td>
  </tr>
  <tr>
    <td class="handler">DELETE</td>
    <td class="method"></td>
    <td class="arguments"></td>
	<td class="response"></td>
	<td class="doc"></td>
  </tr>
</table>