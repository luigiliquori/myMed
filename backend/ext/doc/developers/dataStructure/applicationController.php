<!-- ApplicationController -->
<div id="applicationController" class="componenent">
	<span class="columnFamilyName">ApplicationController:</span>
	<table class="columnFamily">
		<tbody>
			<tr>
				<th>Key</th>
				<th>Value</th>
			</tr>
			<tr>
				<td class="applciationControllerID">APPLICATION_ID + PREDICATE_ID</td>
				<td>
					<div>
						<span class="columnFamilyName">Predicate:</span>
						<table class="columnFamily">
							<tbody>
								<tr>
									<th>Key</th>
									<th>Value</th>
								</tr>
								<tr>
									<td>memberList</td>
									<td>
										<table>
											<tbody>
												<tr>
													<th>Key</th>
													<th>Value</th>
												</tr>
												<tr>
													<td>publisherList</td>
													<td class="userListID">USER_LIST_ID</td>
												</tr>
												<tr>
													<td>subscriberlist</td>
													<td class="userListID">USER_LIST_ID</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>SUB_PREDICATE_ID+USER_ID</td>
									<td>
										<table>
											<tbody>
												<tr>
													<th>Key</th>
													<th>Value</th>
												</tr>
												<tr>
													<td>predicate</td>
													<td>SUB_PREDICATE_ID</td>
												</tr>
												<tr>
													<td>begin</td>
													<td class="type">Date</td>
												</tr>
												<tr>
													<td>end</td>
													<td class="type">Date</td>
												</tr>
												<tr>
													<td>publisherID</td>
													<td class="userID">USER_ID</td>
												</tr>
												<tr>
													<td>publisherName</td>
													<td class="type">String</td>
												</tr>
												<tr>
													<td>publisherProfilePicture</td>
													<td class="type">url</td>
												</tr>
												<tr>
													<td>publisherReputation</td>
													<td class="type">REPUTATION_ID</td>
												</tr>
												<tr>
													<td>data</td>
													<td class="type">Text</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
