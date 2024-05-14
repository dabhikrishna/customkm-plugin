jQuery(document).ready(function($) {
    $('#portfolio-submission-form').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        var formData = $(this).serialize(); // Serialize form data
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'save_portfolio_data',
                formData: formData
            },
            success: function(response) {
                $('#submission-message').html(response); // Display the submission message
                $('#portfolio-submission-form')[0].reset(); // Reset the form
            }
        });
    });
});
