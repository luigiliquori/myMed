<!-- ApplicationModel -->
<div id="applicationModel" class="componenent">
	<span class="columnFamilyName">ApplicationModel:</span>
	<table class="columnFamily">
		<tbody>
			<tr>
				<th>Key</th>
				<th>Value</th>
			</tr>
			<tr>
				<td class="applicationID">APPLICATION_ID</td>
				<td>
					<div>
						<span class="columnFamilyName">Model:</span>
						<table class="columnFamily">
							<tbody>
								<tr>
									<th>Key</th>
									<th>Value</th>
								</tr>
								<tr>
									<td>PREDICATE_ID</td>
									<td>
										<table>
											<tbody>
												<tr>
													<th>Key</th>
													<th>Value</th>
												</tr>
												<tr>
													<td>applicationControllerID</td>
													<td class="type">APPLICATION_ID + PREDICATE_ID</td>
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
