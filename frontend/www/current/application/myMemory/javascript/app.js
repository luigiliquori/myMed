
// Handle javascript navbar
$(document).delegate('[data-role="navbar"] a', 'click', function () {
    $(this).addClass('ui-btn-active');
    $('.tab').hide();
    $($(this).attr('href')).show();
    return true;
});

// Click on the default tab
$(document).ready(function() {
	// Click on all "default" button to click on load
	$("[data-click-on-load]").each(function() {
		$(this).click();
	});
})

// Handle close buttons
$(document).delegate('[data-action="close"]', 'click', function () {
    $($(this).attr('href')).hide();
});
