<div class="requestHandlerName">
	<h3>FindRequestHandler</h3>
</div>
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
				<li>namespace*</li>
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
				<li>
					<ul>
						<li class="json">results : {<br />
							<div>predicate : "",</div>
							<div>predicateList : "",</div>
							<div>begin : "",</div>
							<div>end : ""</div>
							<div>publisherID : ""</div>
							<div>publisherName : ""</div>
							<div>publisherProfilePicture : ""</div>
							<div>publisherReputation : ""</div>
							<div>data : ""</div> }
						</li>
					</ul>
				</li>
				<li>
					start: (*optional) sets the starting point of your search (default: "")<br /> pass it the last results.predicate of the previous search to
					continue searching from this point.
				</li>
				<li>count: (*optional) size of the results returned (default: 100)</li>
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
				<li>namespace*</li>
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
				<li>
					<ul>
						<li class="json">details : [<br />
							<div>
								{
								<div>key : "",</div>
								<div>value : "",</div>
								<div>ontology : ""</div>
								},<br /> ...
							</div> ]
						</li>
					</ul>
				</li>
				<li>namespace: (*optional) set a prefix to the keys stored into the database</li>
			</ul>
		</td>
	</tr>
</table>
