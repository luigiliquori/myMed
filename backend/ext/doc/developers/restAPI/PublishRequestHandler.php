<div class="requestHandlerName">
	<h3>PublishRequestHandler</h3>
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
		<td class="handler">CREATE</td>
		<td class="method">POST</td>
		<td class="arguments">
			<ul>
				<li>code</li>
				<li>application</li>
				<li>predicate</li>
				<li>data</li>
				<li>userID</li>
				<li>level*</li>
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
				<li class="json">predicate : [<br />
					<div>
						{
						<div>key : "",</div>
						<div>value : "",</div>
						<div>ontologyID : ""</div>
						},<br /> ...
					</div> ]
				</li>
			</ul>
			<ul>
				<li class="json">data : [<br />
					<div>
						{
						<div>key : "",</div>
						<div>value : "",</div>
						<div>ontologyID : ""</div>
						},<br /> ...
					</div> ]
				</li>
				<li>level: (*optional) sets the level of broadcasting in the Index Table (default: predicate length)</li>
			</ul>
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
				<li class="json">predicate : [<br />
					<div>
						{
						<div>key : "",</div>
						<div>value : "",</div>
						<div>ontologyID : ""</div>
						},<br /> ...
					</div> ]
				</li>
			</ul>
		</td>
	</tr>

</table>
