<div class="requestHandlerName"><h3>SubscribeRequestHandler</h3></div>
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
		  <li>predicate</li>
		  <li>user</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
		<ul>
		  <li>results</li>
		</ul>
	</td>
	<td class="doc">
		<p>predicate: "key1value1key2value2"</p>
		<p>user: userBean object (json)</p>
	</td>
  </tr>
  <tr>
    <td class="handler">READ</td>
    <td class="method">GET</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>application</li>
		  <li>userID</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
		<ul>
		  <li>subscriptions</li>
		</ul>
	</td>
	<td class="doc">
		<p>subscriptions : liste des prédicats auquel userID a souscrit</p>
	</td>
  </tr>
  <tr>
    <td class="handler">DELETE</td>
    <td class="method">GET</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>application</li>
		  <li>predicate</li>
		  <li>user</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response"></td>
	<td class="doc">
		<p>predicate: "key1value1key2value2"</p>
		<p>user: ID de l'utilisateur</p>
	</td>
  </tr>
</table>