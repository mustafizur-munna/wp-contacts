jQuery(document).ready(function($){
    $('#mr-contact-submit form').on('submit', function(e){
        e.preventDefault();

        let data = $(this).serialize();

        $.post(contactData.ajax_url, data, function(){});

        // $.ajax({
        //     url: contactData.ajax_url,
        //     method: 'POST',
        //     data: {
        //         action: 'save_contact_data',
        //         person_name: contactData.person_name,
        //         person_phone: contactData.person_phone,
        //         person_email: contactData.person_email,
        //         _ajax_nonce: contactData.ajax_nonce,
        //     },
        //     success: function(response) {
                
        //     }
        // });
    })
});


// jQuery(document).ready(function ($) {
//     $("#rcf-contact-form form").on("submit", function (e) {
//       e.preventDefault();
  
//       let data = $(this).serialize();
  
//       $.post(ReactContactForm.ajax_url, data, function () {})
//         .success(function (res) {
//           if (res?.status) {
//             $("#rcf_success_message").show();
//             setTimeout(function () {
//               $("#rcf_success_message").hide();
//             }, 3000);
//             $("#rcf-contact-form form").trigger("reset");
//           }
//         })
//         .fail(function (err) {
//           $("#rcf_error_message").show();
//           setTimeout(function () {
//             $("#rcf_error_message").hide();
//           }, 3000);
//           $("#rcf-contact-form form").trigger("reset");
//         });
//     });
//   });