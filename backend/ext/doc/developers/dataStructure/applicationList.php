<!-- ApplicationList -->
<div id="applicationGroup" class="componenent">
	<span class="columnFamilyName">ApplicationList: <span class="type">(SuperColumnFamily)</span>
	</span>
	<table class="columnFamily">
		<tbody>
			<tr>
				<th>Key</th>
				<th>Value</th>
			</tr>
			<tr>
				<td class="applicationListID">APPLICATION_LIST_ID</td>
				<td>
					<div>
						<span class="columnFamilyName">List: <span class="type">(ColumnFamily)</span>
						</span>
						<table class="columnFamily">
							<tbody>
								<tr>
									<th>Key</th>
									<th>Value</th>
								</tr>
								<tr>
									<td class="applicationID">APPLICATION_ID</td>
									<td>
										<table>
											<tbody>
												<tr>
													<th>Key</th>
													<th>Value</th>
												</tr>
												<tr>
													<td>name</td>
													<td class="type">String</td>
												</tr>
												<tr>
													<td>id</td>
													<td class="applicationID">APPLICATION_ID</td>
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
