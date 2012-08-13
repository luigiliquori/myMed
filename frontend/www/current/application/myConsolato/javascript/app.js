
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

// -------------------------------------------------------------------
// Form validation function
// -------------------------------------------------------------------

/** 
 * Also used by the wizard to validate each page 
 * @param elt Parent element to check input values into
 */
function validateForm(elt) {

	// List of errors 
	var errors = [];
	
	//Loop on all "data-validate" elements
	$("[data-validate]", elt).each(function() {
		
		// Hide the validation element
		//$(this).hide();
		
		// Name of the target
		var name = $(this).attr('data-validate');
		var matches = $('[name="'+name+'"]', elt);
		
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
			var input = $('[name="' + name + '"]', elt);
			
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
		errorMessage(msg.FORM_ERROR + list);
		return false;
	} else {
		return true;
	}
	
} // End of form validation function 


//-------------------------------------------------------------------
// Init made before JQuery mobile init the page 
//-------------------------------------------------------------------

// Page switch handler
$('[data-role=page]').live("pagebeforecreate", function() {

// Current page
var page = $(this);
var id = page.attr("id");

// "data-if-small" attributes : Device reponsive design
$('[data-if-small]', page).each(function()  {
	
	// Not for big screen
	if ($("body").width() > 798) return; 
	
	// Attribute contain an expressino like "name:value"
	var expr = $(this).attr("data-if-small");
	var parts = expr.split(":");
	
	// Set the attribute 
	$(this).attr(parts[0], parts[1]);

});

//Init CLE-editor
$(".cle-editor", page).cleditor({useCSS:true});

	
});


// -------------------------------------------------------------------
// Initialization made on each page show
// -------------------------------------------------------------------

//Dictionnary of already initialized pages
gShowInitialized = {};

// Page switch handler
$('[data-role=page]').live("pagebeforeshow", function() {

// Current page
var page = $(this);
var id = page.attr("id");

// Don't initialize twice
if (id in gShowInitialized) return;
gShowInitialized[id] = true;

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

//Init wizard (wizard pages switch)
$("#wizard", page).each(function() {


	function switchToPage(id) {

		// Get list of all wizard pages
		var wizardPages = $("[data-role=wizard-page]");
		
		// Current page
		if (id == null) {
			// First one
			var currPage = $(wizardPages.get(0));
		} else {
			var currPage = $("#" + id);
		}
		
		// Hide them all
		wizardPages.hide();
	
		// Show the current page
		currPage.show();
	
		// Find siblings
		var previousPage = currPage.prev("[data-role=wizard-page]");
		var nextPage = currPage.next("[data-role=wizard-page]");
		
		// First page ?
		if (previousPage.size() == 0) {
			
			// Show 'start' wizard button
			$('#wizard-start').show();
			$('#wizard-previous').hide();
			
		} else {
			
			// Show previous button
			$('#wizard-start').hide();
			$('#wizard-previous').show();
			
			// Set href of "previous" button to the id of the previous page
			$('#wizard-previous').attr("href", previousPage.attr("id"));
		}
		
		// Last page ?
		if (nextPage.size() == 0) {
			
			// Show 'end' wizard button
			$('#wizard-end').show();
			$('#wizard-next').hide();
			
		} else {
			
			// Show 'next' button
			$('#wizard-end').hide();
			$('#wizard-next').show();
			
			// Set href of "next" button to the id of the next page
			$('#wizard-next').attr("href", nextPage.attr("id"));
		}
		
		$('#wizard').trigger("create");
		
	} // End of function "switchToPage"
	
	// Set 'next' and 'previous' button handler
	$("#wizard-next, #wizard-previous").click(function() {	
		
		// Current page
		currPage = $("[data-role=wizard-page]:visible");
		
		// Validate input fields in it and exit if fails
		if (!validateForm(currPage)) return false;
		
		// Get href and switch to it
		switchToPage($(this).attr("href"));
		
		// Do not go to the link 
		return false;
	});
	
	// Switch to first page
	switchToPage(null);

}); // End of init wizard

// Init data-role=submit links
$("[data-role=submit]", page).click(function() {

	// Submit forms
	$("form").trigger("submit");
	
	// Don't go to link 
	return false;

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

	// Call the validation on the entire form
	return validateForm(this);
			
})
	
}); // End of "initialization on each page"


