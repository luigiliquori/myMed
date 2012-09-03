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
		  <li>namespace*</li>
		  <li>predicate</li>
		  <li>userID</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
		<ul>
		  <li>results</li>
		</ul>
	</td>
	<td class="doc">
		<ul>
			<li>predicate: "key1value1key2value2"</li>
			<li class="json">predicateList : [<br />
				<div>
					{
					<div>key : "",</div>
					<div>value : "",</div>
					<div>ontologyID : ""</div>
					},<br /> ...
				</div> ]
			</li>
			<li>user: userBean object (json)</li>
			<li>namespace: (*optional) set a prefix to the keys stored into the database</li>
		 </ul>
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
		  <li>namespace*</li>
		  <li>predicate</li>
		  <li>userID</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response"></td>
	<td class="doc">
		<ul>
			<li>predicate: "key1value1key2value2"</li>
			<li class="json">predicateList : [<br />
				<div>
					{
					<div>key : "",</div>
					<div>value : "",</div>
					<div>ontologyID : ""</div>
					},<br /> ...
				</div> ]
			</li>
			<li>user: ID de l'utilisateur</li>
			<li>namespace: (*optional) set a prefix to the keys stored into the database</li>
		</ul>
	</td>
  </tr>
</table>