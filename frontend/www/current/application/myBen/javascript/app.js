

function message(msg) {
	$('<div>').simpledialog2({
	    mode: 'blank',
	    headerText: 'Message',
	    headerClose: true,
	    blankContent : 
	      "<img src='img/mail_send.png' style='margin:20px' /><br/>" +
	      "<p style='margin:1em'>" + msg + "</p>" +
	      "<a rel='close' data-role='button' href='#'>Close</a>"
	  })
} 

$(document).ready(function() {
		
	// Handle "close" buttons
	$('[data-action="close"]').click(function () {
	    var bars = $($(this).attr("href"));
	    bars.hide();
	    return false;
	});
	
	// Handle all/none checkboxes
	$('[data-check-all]').change(function() {
			
		// Get the selector
		selector = $(this).attr("data-check-all");
		var checked = $(this).is(':checked');
		
		// Loop on selected elements
		$(selector).each(function() {
			
			// Check/uncheck all
			if (checked) {
				$(this).attr('checked', 'checked');
			} else {
				$(this).removeAttr('checked');
			}
			$(this).checkboxradio("refresh");
		})
		
	}) 
	
});

// Hook on page load => Show some messages
$(document).bind("pagechange", function(event, data) {
	
    var target = data.toPage;
    
    switch(target.attr("id")) {
    	case "offres" :
    		message("Un email est envoyé à Nice-Bénévolat avec les coordonnées / profil du nouveau bénévole.");
    		break;
    	case "association" :
    		message("Un email est envoyé à Nice Bénévolat avec les coordonnées de la nouvelle association");
    		break;
    		
    }
   
    return true;
});

