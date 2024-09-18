jQuery(document).ready(function($){
    $('#mr-contact-submit form').on('submit', function(e){
        e.preventDefault();

        let data = $(this).serialize();
        let whereToShowNotice = $('.mr-notification p');

        $.post(contactData.ajax_url, data)
        .done(function( data ) {
            whereToShowNotice.html(data);
        });        
    })
});