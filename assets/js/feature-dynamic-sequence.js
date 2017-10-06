function ajaxFeatureDynamicSequenceForm(container) {
    var form = $(container).find('form');
    var containerId = container.attr('id');

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
}

$(document).ready(function(){
    $('div.locus-feature').each(function() {
        var container = $( this ).find('div.row');

        ajaxFeatureDynamicSequenceForm(container);
    });
});
