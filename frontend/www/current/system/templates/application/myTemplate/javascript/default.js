function initialize() {
	// INIT JAVASCRIPT FOR THE TEMPLATE
	setupDASP($("#userID").val(),
			$("#accessToken").val(), 
			$("#applicationName").val());
	
	// INITIALIZE DASP->MAP
	setupDASPMap($("#applicationName").val() + "Map");
}
