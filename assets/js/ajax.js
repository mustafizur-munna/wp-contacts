jQuery(document).ready(function($){
    $.ajax({
        url: contactData.ajax_url,
        method: 'POST',
        data: {
			action: 'save_contact_data',
            person_name: contactData.person_name,
            person_phone: contactData.person_phone,
            person_email: contactData.person_email,
			_ajax_nonce: contactData.ajax_nonce,
		},
		success: function(response) {
			
		}
    });

});