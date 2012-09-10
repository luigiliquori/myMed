<!-- Reputation -->
<div id="reputation" class="componenent">
	<span class="columnFamilyName">UserApplicationConsumer:</span>
	<table class="columnFamily">
		<tbody><tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
		<tr>
			<td class="">userApplicationConsumerId</td>
			<td>
			<table>
				<tbody><tr>
					<th>Key</th>
					<th>Value</th>
				</tr>
				<tr>
					<td>userApplicationConsumerId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>userId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>applicationId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>verdictList_userCharging</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>score</td>
					<td class="type">Double</td>
				</tr>
				<tr>
					<td>size</td>
					<td class="type">Integer</td>
				</tr>
			</tbody></table>
			</td>
		</tr>
	</tbody></table>
	
	<br>
	<span class="columnFamilyName">UserApplicationProducer:</span>
	<table class="columnFamily">
		<tbody><tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
		<tr>
			<td class="">userApplicationProducerId</td>
			<td>
			<table>
				<tbody><tr>
					<th>Key</th>
					<th>Value</th>
				</tr>
				<tr>
					<td>userApplicationProducerId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>userId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>applicationId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>verdictList_userCharging</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>score</td>
					<td class="type">Double</td>
				</tr>
				<tr>
					<td>size</td>
					<td class="type">Integer</td>
				</tr>
			</tbody></table>
			</td>
		</tr>
	</tbody></table>
	
	<br>
	<span class="columnFamilyName">Verdict:</span>
	<table class="columnFamily">
		<tbody><tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
		<tr>
			<td class="">verdictId</td>
			<td>
			<table>
				<tbody><tr>
					<th>Key</th>
					<th>Value</th>
				</tr>
				<tr>
					<td>verdictId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>judgeId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>chargedId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>time</td>
					<td class="type">Long</td>
				</tr>
				<tr>
					<td>isJudgeProducer</td>
					<td class="type">Boolean</td>
				</tr>
				<tr>
					<td>verdictAggregationList</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>vote</td>
					<td class="type">Double</td>
				</tr>
			</tbody></table>
			</td>
		</tr>
	</tbody></table>
	
	<br>
	<span class="columnFamilyName">VerdictAggregation:</span>
	<table class="columnFamily">
		<tbody><tr>
			<th>Key</th>
			<th>Value</th>
		</tr>
		<tr>
			<td class="">verdictAggregationId</td>
			<td>
			<table>
				<tbody><tr>
					<th>Key</th>
					<th>Value</th>
				</tr>
				<tr>
					<td>verdictAggregationId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>verdictListId</td>
					<td class="type">String</td>
				</tr>
				<tr>
					<td>score</td>
					<td class="type">Double</td>
				</tr>
				<tr>
					<td>size</td>
					<td class="type">Integer</td>
				</tr>
			</tbody></table>
			</td>
		</tr>
	</tbody></table>
	
	<br>
	<span class="columnFamilyName">TimeOrderVerdictList:</span>
	<table class="columnFamily">
	  <tbody><tr>
	    <th>Key</th>
	    <th>Value</th>
	  </tr>
	  <tr>
	    <td class="type">TimeOrderVerdictListId</td>
	    <td>
		  <div>
			  	<span class="columnFamilyName">VerdictList:</span>
			   	<table class="columnFamily">
				  <tbody><tr>
				    <th>Key</th>
				    <th>Value</th>
				  </tr>
				  <tr>
				    <td class="dataID">verdictId</td>
				    <td>
						<table>
						  <tbody><tr>
						    <th>Key</th>
						    <th>Value</th>
						  </tr>
						  <tr>
						    <td>verdictId</td>
						    <td class="type">String</td>
						  </tr>
						</tbody></table>
				    </td>
				  </tr>
				</tbody></table>
			</div>
	    </td>
	  </tr>
	</tbody></table>
	
	<br>
	<span class="columnFamilyName">AuxOrderVerdictList:</span>
	<table class="columnFamily">
	  <tbody><tr>
	    <th>Key</th>
	    <th>Value</th>
	  </tr>
	  <tr>
	    <td class="type">AuxOrderVerdictListId</td>
	    <td>
		  <div>
			  	<span class="columnFamilyName">VerdictList:</span>
			   	<table class="columnFamily">
				  <tbody><tr>
				    <th>Key</th>
				    <th>Value</th>
				  </tr>
				  <tr>
				    <td class="dataID">verdictId</td>
				    <td>
						<table>
						  <tbody><tr>
						    <th>Key</th>
						    <th>Value</th>
						  </tr>
						  <tr>
						    <td>verdictId</td>
						    <td class="type">String</td>
						  </tr>
						</tbody></table>
				    </td>
				  </tr>
				</tbody></table>
			</div>
	    </td>
	  </tr>
	</tbody></table>
	
	<br>
	<span class="columnFamilyName">VerdictAggregationList:</span>
	<table class="columnFamily">
	  <tbody><tr>
	    <th>Key</th>
	    <th>Value</th>
	  </tr>
	  <tr>
	    <td class="type">VerdictAggregationListId</td>
	    <td>
		  <div>
			  	<span class="columnFamilyName">aggregation:</span>
			   	<table class="columnFamily">
				  <tbody><tr>
				    <th>Key</th>
				    <th>Value</th>
				  </tr>
				  <tr>
				    <td class="dataID">aggregationId</td>
				    <td>
						<table>
						  <tbody><tr>
						    <th>Key</th>
						    <th>Value</th>
						  </tr>
						  <tr>
						    <td>aggregationId</td>
						    <td class="type">String</td>
						  </tr>
						</tbody></table>
				    </td>
				  </tr>
				</tbody></table>
			</div>
	    </td>
	  </tr>
	</tbody></table>
	
</div>