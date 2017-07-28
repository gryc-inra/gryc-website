function generateCartFasta(textareaId, modalId) {
    var $modal = $(modalId);
    var $form = $modal.find('form');

    var $values = {};

    $.each( $form[0].elements, function(i, field) {
        $values[field.name] = field.value;
    });

    $.ajax({
        type:       $form.attr('method'),
        url:        $form.attr('action'),
        dataType:   'text',
        data:       $values,
        success: function (data) {
            $(modalId).modal('hide');
            $(textareaId).val(data);
        }
    });
}
