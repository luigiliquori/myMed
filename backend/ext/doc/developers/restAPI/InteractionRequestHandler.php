<div class="requestHandlerName"><h3>InteractionRequestHandler</h3></div>
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
    <td class="method"></td>
    <td class="arguments"></td>
	<td class="response"></td>
	<td class="doc"></td>
  </tr>
  <tr>
    <td class="handler">UPDATE</td>
    <td class="method">POST</td>
    <td class="arguments">
    	<ul>
		  <li>code</li>
		  <li>application</li>
		  <li>producer</li>
		  <li>consumer</li>
		  <li>start</li>
		  <li>end</li>
		  <li>predicate</li>
		  <li>feedback</li>
		  <li>accessToken</li>
		</ul>
	</td>
	<td class="response"></td>
	<td class="doc">
		<p>Permet de créer une interaction entre un producer et un consumer pour un predicat donné dans une application donnée.
		La valeur du feedback est optionnel et permet de mettre à jour la reputation du producer.</p>
		<p>predicate: "key1value1key2value2"</p>
		<p>feedback : valeur comprise entre [0;1]</p>
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