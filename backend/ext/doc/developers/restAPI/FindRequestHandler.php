<div class="requestHandlerName"><h3>FindRequestHandler</h3></div>
<table class="restAPITable">
  <tr>
    <th class="handlerHead">Request</th>
    <th class="methodHead">Method</th>
    <th class="argumentsHead">Arguments</th>
    <th class="responseHead">Response.data</th>
    <th class="docHead">Doc</th>
  </tr>
  <tr>
    <td class="handler">READ</td>
    <td class="method">GET</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>application</li>
		  <li>predicate</li>
		  <li>accessToken</li>
		  <li>start*</li>
		  <li>count*</li>
		</ul>
	</td>
	<td class="response">
		<ul>
		  <li>results</li>
		</ul>
	</td>
	<td class="doc">
		<p>predicate: "key1value1key2value2"</p>
		<ul>
		  <li class="json">results : {<br />
		  <div>predicate : "",</div>
		  <div>begin : "",</div>
		  <div>end : ""</div>
		  <div>publisherID : ""</div>
		  <div>publisherName : ""</div>
		  <div>publisherProfilePicture : ""</div>
		  <div>publisherReputation : ""</div>
		  <div>data : ""</div>
		  }
		  </li>
		</ul>
		<p>
		start: indicates the starting point of your search, defaults to ""
		indicate the last results.predicate of the previous search to continue searching from this point.
		(*Optional parameter)
		</p><p>
		count: size of the results returned, defaults to 100
		(*Optional parameter)
		</p>
	</td>
  </tr>
  <tr>
    <td class="handler">READ</td>
    <td class="method">GET</td>
    <td class="arguments">
	    <ul>
		  <li>code</li>
		  <li>application</li>
		  <li>predicate</li>
		  <li>userID</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response">
		<ul>
		  <li>details</li>
		</ul>
	</td>
	<td class="doc">
		<p>predicate: "key1value1key2value2"</p>
		<ul>
		  <li class="json">details : [<br />
		  <div>{
			  <div>key : "",</div>
			  <div>value : "",</div>
			  <div>ontology : ""</div>
			  },<br />
			  ...
		  </div>
		  ]
		  </li>
		</ul>
	</td>
  </tr>
</table>