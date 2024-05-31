function validateAndSendForm() {
	var error = false;

	var name = $('input#name').val();
	if(name == "" || name == " ") {
		$('#err-name').fadeIn('slow');
		error = true;
	}

	if (error == true)
	{
		$('.page-loader').fadeOut('slow');
		return;
	}

	var email_compare = /^([a-z0-9_.-]+)@([da-z.-]+).([a-z.]{2,6})$/;
	var email = $('input#email').val();
	if (email == "" || email == " " || !email_compare.test(email)) {
		$('#err-email').fadeIn('slow');
		error = true;
	}

	if (error == true)
	{
		$('.page-loader').fadeOut('slow');
		return;
	}

	var data_string = $('#contact-form').serialize();

	$.ajax({
		type: "POST",
		url: $('#contact-form').attr('action'),
		data: data_string,
		timeout: 6000,
		error: function(request,error) {
			if (error == "timeout") {
				$('#err-timeout').slideDown('slow');
			}
			else {
				$('#err-state').slideDown('slow');
			}
		},
		success: function(response) {
			if(response.status == true) {
				$('#contact-form').hide();
				$('#success-state').slideToggle(1000);
				$('#contact-form').trigger("reset");
			} else if(response.status == false) {
				$('#error-state').slideToggle(1000);
			}
		},
		complete: function() {
			$('.page-loader').fadeOut('slow');
        }
	});

	
	return false;
}

jQuery(document).ready(function ($) { // wait until the document is ready
	$('#contact-send-button').click(function(e){
		e.preventDefault();
		$('#err-state').fadeOut('slow');
		$('.page-loader').fadeIn('slow', validateAndSendForm);
	});
});
