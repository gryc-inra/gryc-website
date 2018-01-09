/*
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

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
