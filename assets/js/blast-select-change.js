$( document ).ready(function () {
    var $tool = $('#blast_tool');

    // When genus gets selected ...
    $tool.change(function () {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected genus value.
        var data = {};
        data[$tool.attr('name')] = $tool.val();

        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            success: function (html) {
                // Replace current position field ...
                $('select#blast_database').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('select#blast_database')
                );
                $('select#blast_matrix').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('select#blast_matrix')
                );
            }
        });
    });
});
