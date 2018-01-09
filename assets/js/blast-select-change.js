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
