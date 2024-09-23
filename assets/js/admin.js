jQuery(document).ready(function($) {
    // Add new row to the table when clicking the "Add New" button
    $('.add-new-button').on('click', function() {
        var table = $('#payment-table');
        var templateRow = table.find('.template-row').clone(); // Clone the template row
        templateRow.removeClass('template-row').show(); // Remove the template class and show the row
        // Clear out the values for cloned rows (optional)
        templateRow.find('select').each(function() {
            $(this).val(''); // Reset all select inputs to empty
        });
        // Append the new row to the table body
        table.find('tbody').append(templateRow);
    });
    // Delete a row from the table
    $('#payment-table').on('click', '.delete-button', function() {
        var row = $(this).closest('tr');
        if ($('#payment-table tbody tr').length > 1) { // Ensure at least one row remains
            row.remove();
        } else {
            alert('Cannot delete the last row.');
        }
    });
    // Before form submission, remove the template row to avoid it being submitted
    $('form.product-catalog-mode-form').on('submit', function() {
        $('#payment-table').find('.template-row').remove(); // Remove the template row before submitting the form
    });
});