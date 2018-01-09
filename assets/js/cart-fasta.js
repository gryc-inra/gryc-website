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

$(function() {
    $('#generate-fasta-from-cart-button').click(function(e) {
        generateCartFasta('#blast_query', '#cartFormModal');
        generateCartFasta('#multiple_alignment_query', '#cartFormModal');
        generateCartFasta('#reverse_complement_query', '#cartFormModal');
    });
});
