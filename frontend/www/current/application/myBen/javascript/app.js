
// Add hasAttr() method to jQuery
$.fn.hasAttr = function(name) {  
   return (this.attr(name) !== undefined) && (this.attr(name) !== false);
};


// Display a message in a dialog box
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

// Display an error message in a dialog box
function errorMessage(msg) {
	$('<div>').simpledialog2({
	    mode: 'blank',
	    headerText: 'Erreur',
	    headerClose: true,
	    blankContent : 
	      "<p style='margin:1em'>" + msg + "</p>" +
	      "<a rel='close' data-role='button' href='#'>Close</a>"
	  })
} 


function debug(msg) {
	console.log(msg);
}

// Dictionnary of already initialized pages
gInitialized = {}

// Initialization made on each page 
$('[data-role=page]').live("pagebeforeshow", function() {
	
	var page = $(this);
	var id = page.attr("id");
		
	// Don't initialize twice
	if (id in gInitialized) return;
	gInitialized[id] = true;
	
	debug("init-page : " + page.attr("id"));
	
	// Handle "close" buttons
	$('[data-action="close"]', page).click(function () {
	    var bars = $($(this).attr("href"));
	    bars.hide();
	    return false;
	});
	
	// Init the all/none checkboxes
	$('[data-check-all]', page).each(function() {
		
		// Get the selector
		var name = $(this).attr("data-check-all");
		var allChecked = true;
		
		// Select all the checkboxes controlled by this one
		// All a re checked ?
		$('[name="'+name+'"]', page).each(function() {
			if (! $(this).is(':checked')) {
				allChecked = false;
			}
		});
				
		// Set the status of the checkbox
		if (allChecked) $(this).attr('checked', 'checked').checkboxradio("refresh");
		
	});
	
	// Handle all/none checkboxes
	$('[data-check-all]', page).change(function() {
		
		// Get the selector
		var name = $(this).attr("data-check-all");
		var checked = $(this).is(':checked');
		
		// Loop on selected elements
		$('[name="'+name+'"]', page).each(function() {
			
			// Check/uncheck all
			if (checked) {
				$(this).attr('checked', 'checked');
			} else {
				$(this).removeAttr('checked');
			}
			$(this).checkboxradio("refresh");
		})
		
	}) 
	
	// Hide validation divs
	$("[data-validate]", page).hide();
	
	// Handle form validation
	$("form", page).submit(function() {

		// Init to "valid"
		var errors = [];
		var form = this;
				
		// Loop on all "data-validate" elements
		$("[data-validate]", form).each(function() {
			
			// Hide the validation element
			//$(this).hide();
			
			// Name of the target
			var name = $(this).attr('data-validate');
			var matches = $('[name="'+name+'"]', form);
			
			var val_element = $(this);
			
			// Show the div and set an error
			function addError() {
				//val_element.show();
				errors.push(val_element.text());
			}
			
			// Count checked options
			function nbChecks() {
				// Count checks
				var nbChecks = 0;
				matches.each(function() {
					nbChecks += $(this).is(':checked');
				})
				return nbChecks;
			}
			
			// Check min ?
			if ($(this).hasAttr('data-validate-min')) {
				if (nbChecks() < parseInt($(this).attr('data-validate-min'))) addError();
			}
			
			// Check max ?
			if ($(this).hasAttr('data-validate-max')) {
				if (nbChecks() > parseInt($(this).attr('data-validate-max'))) addError();
			}
					
			// Check not empty ?
			if ($(this).hasAttr('data-validate-non-empty')) {
				
				debug("toto");
				
				// Get the corresponding input
				var input = $('[name="' + name + '"]', form);
				
				// Empty value ?
				if (!input.val()) addError();
			}
			
		}) // End of loop on "data-validate" elements
		
		// Verdict ?
		if (errors.length > 0) {
			
			list="<ul>";
			for(var i in errors) {
				list += "<li>" + errors[i] + "</li>";
			}
			list += "</ul>";
			errorMessage("Il y a des erreurs dans le formulaire, merci de les corriger." + list);
			return false;
		} else {
			return true;
		}
		
	}) // End of "handle form validation"
	
});

// -- For demo only
if (window.location.search.indexOf("action=demo") != -1) {

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

} // End of "for demo only"

