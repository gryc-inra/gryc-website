$( document ).ready(function () {
    var $tool = $('#blast_tool');

    $tool = $('input[name="blast[tool]"]');

    // When the user change of tool...
    $tool.change(function () {
        // Retrieve the form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected tool value.
        var data = {};
        data[$tool.attr('name')] = $('input[name="blast[tool]"]:checked').val();

        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            success: function (html) {
                // Replace current position field ...
                $('#blast_database').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('#blast_database')
                );
            }
        });
    });
});
