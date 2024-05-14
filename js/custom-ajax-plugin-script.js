jQuery(document).ready(function($) {
    $('#store-name-form').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
        formData += '&action=save_store_name'; // Add AJAX action
        $.post(ajaxurl, formData, function(response) {
            $('#store-name-result').html(response); // Display response
        });
    });
});


/*jQuery(document).ready(function($) {
    $('#portfolio-submission-form').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Serialize form data
        formData += '&action=handle_portfolio_submission'; // Add AJAX action
        $.post(ajaxurl, formData, function(response) {
            $('#portfolio-form-result').html(response); // Display response
        });
    });
});*/