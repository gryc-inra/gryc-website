function ajaxFeatureDynamicSequenceForm(container) {
    var form = $(container).find('form');
    var containerId = container.attr('id');

    // Use AJAX to validate the form
    form.submit(function(event) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            url: form.attr('action'),
            dataType: 'html',
            success: function (html) {
                // Select the right part of the code
                var sequenceHTML = $(html).find('#' + containerId).html();

                container.html(sequenceHTML);

                ajaxFeatureDynamicSequenceForm(container);
            }
        });
    });

    // Define Reset button action (default values)
    container.find('button[type="reset"]').click(function(event){
        event.preventDefault();
        var feature = container.data('feature');
        container.find('#feature_dynamic_sequence_' + feature + '_upstream').val(0);
        container.find('#feature_dynamic_sequence_' + feature + '_downstream').val(0);
        container.find('#feature_dynamic_sequence_' + feature + '_showUtr').prop('checked', true);
        container.find('#feature_dynamic_sequence_' + feature + '_showIntron').prop('checked', true);
    });
}

$(document).ready(function(){
    $('div.locus-feature').each(function() {
        ajaxFeatureDynamicSequenceForm($(this));
    });
});
