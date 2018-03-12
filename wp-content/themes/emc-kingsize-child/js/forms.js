var EMC_Forms = (function($) {
	return function initForms() {
		//set variables
		var nameVal = $("#form_name").val();
		var emailVal = $("#form_email").val();
		var websiteVal = $("#form_website").val();
		var messageVal = $("#form_message").val();
				
		//if name field is empty, show label in it
		if(nameVal == '') {
		$("#form_name").parent().find('label').css('display', 'block');	
		}
		
		//if email field is empty, show label in it
		if(emailVal == '') {
		$("#form_email").parent().find('label').css('display', 'block');	
		}
		
		//if website field is empty, show label in it
		if(websiteVal == '') {
		$("#form_website").parent().find('label').css('display', 'block');	
		}
					
		
		//if message field is empty, show label in it
		if(messageVal == '') {
		$("#form_message").parent().find('label').css('display', 'block');	
		}

		$('#contact_form input, #contact_form textarea').parent().find('label').hide();		
		//hide labels on focus		
		$('#contact_form input, #contact_form textarea, #comment_form input, #comment_form textarea').focus(function(){
			//$(this).parent().find('label').fadeOut('fast');		
			if($(this).val() == $(this).parent().find('label').text()){
				$(this).val('');
			}
			//if($(this).is('input')){}
		});		

		$('#subscribe-label, #subscribe-blog-label').css('display', 'block');
		
		//show labels when field is not focused - only if there are no text
		$('#contact_form input, #contact_form textarea, #comment_form input, #comment_form textarea').blur(function(){
			var currentInput = 	$(this);	
			if (currentInput.val() == ""){
				 //$(this).parent().find('label').fadeIn('fast');
			 currentInput.val($(this).parent().find('label').text());
			 }
		});		

		// CONTACT FORM HANDLING SCRIPT - WHEN USER CLICKS "SUBMIT"
		$("#contact_form #form_submit").on('submit', function(){		
												
			// hide all error messages
			$(".error").hide();
			
			// remove "error" class from text fields
			$("#contact_form input, #contact_form textarea, #comment_form input, #comment_form textarea").focus(function() {
				$(this).removeClass('error_input');
				$(this).css('border-color','#333');
				});
			
			// remove error messages when user starts typing		
			$("#contact_form input, #contact_form textarea, #comment_form input, #comment_form textarea").keypress(function() {
				$(this).parent().find('span').fadeOut();	
			});
			
			$("#form_message").keypress(function() {	
				$(this).stop().animate({ 
						width: "380px"
				 }, 100); 
			});
			
			// set variables
			var hasError = false;
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			
			
			// validate "name" field
			var nameVal = $("#form_name").val();
			if(nameVal == '' || nameVal == $("#form_name").parent().find('label').text()) {
				$("#form_name")
				.after('<span class="error">'+contact_form_name+'</span>')
				.addClass('error_input');
				$('#form_name').css('border-color','#ff0000');
				hasError = true;
			}

		
			// validate "e-mail" field - andd error message and animate border to red color on error
			var emailVal = $("#form_email").val();
			if(emailVal == '') {
				$("#form_email")
				.after('<span class="error">'+contact_form_email+'</span>')
				.addClass('error_input');
				$('#form_email').css('border-color','#ff0000');
				hasError = true; 
					
			} else if(!emailReg.test(emailVal)) {	
				$("#form_email")
				.after('<span class="error">'+contact_form_valid_email+'</span>')
				.addClass('error_input');
				$('#form_email').css('border-color','#ff0000');
				hasError = true;
			}
			
					
			// validate "message" field
			var messageVal = $("#form_message").val();
			if(messageVal == ''|| messageVal == $("#form_message").parent().find('label').text()) {
				
				$("#form_message").stop().animate({ 
							width: "250px"
				 }, 100 )
				.after('<span class="error comment_error">'+contact_form_message+'</span>')
				.addClass('error_input');
				$('#form_message').css('border-color','#ff0000');
				hasError = true;
			}
			
				 // if the are errors - return false
				 if(hasError == true) { return false; }
							
			// if no errors are found - submit the form with AJAX
			if(hasError == false) {
				
			var dataString = $('#contact_form').serialize();

				//hide the submit button and show the loader image	
				$("#form_submit").fadeOut('fast', function () {
				$('#contact_form').append('<span id="loader"></span>'); 
				});
							 
				
			// make an Ajax request
					$.ajax({
							type: "POST",
							url: template_directory+"/php/contact-send.php",
							data: dataString,
							success: function(){ 
						 
						// on success fade out the form and show the success message
						$('#loader').remove();
						$('#contact_form').children().fadeOut('fast');
						$('.contact_info').fadeOut('fast');
						 $('.success').fadeIn();    	
							}
					}); // end ajax

			 return false;  
			} 	
			
		});		
	};
})(jQuery);
