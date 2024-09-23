jQuery(document).ready(function($) {
    // Add new row to the table when clicking the "Add New" button
    $('.add-new-button').on('click', function() {
        var table = $('#payment-table');
        var templateRow = table.find('.template-row').clone();
        templateRow.removeClass('template-row').show();

        templateRow.find('select').each(function() {
            $(this).val(''); // Reset all select inputs to empty
        });
        // Append the new row to the table body
        table.find('tbody').append(templateRow);
    });
    // Delete a row from the table
    jQuery(document).ready(function($) {
        // Handle the delete button click
        $('#payment-table').on('click', '.delete-button', function() {
            var row = $(this).closest('tr');

            // Check if the row is not the only one remaining
            if ($('#payment-table tbody tr').length > 1) {
                if (confirm('Are you sure you want to delete this row?')) {
                    row.remove(); // Remove the row if confirmed
                }
            } else {
                alert('Cannot delete the last row.'); // Alert if trying to delete the last row
            }
        });
    });

    // Before form submission, remove the template row to avoid it being submitted
    $('form.product-catalog-mode-form').on('submit', function() {
        $('#payment-table').find('.template-row').remove(); // Remove the template row before submitting the form
    });
});