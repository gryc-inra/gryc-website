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

var delay = require('./delay');

$(document).ready(function(){
    var form = $('#user-search-form');
    var userIndexScheme = form.data('user-index');
    var userAjaxScheme = form.data('user-ajax');
    var searchField = form.find('#user-search-field');

    var processing = false;

    searchField.keyup(function() {
        var query = searchField.val();
        var displayedUrl = userIndexScheme.replace(/__QUERY__/g, query);
        var ajaxUrl = userAjaxScheme.replace(/__QUERY__/g, query);

        history.replaceState('', '', displayedUrl);

        delay(function(){
            $.ajax({
                type: 'GET',
                url: ajaxUrl,
                dataType: 'html',
                delay: 400,
                beforeSend: function() {
                    if (processing) {
                        return false;
                    } else {
                        processing = true;
                    }
                },
                success: function (html) {
                    $('#user-list').replaceWith(html);
                    processing = false;
                }
            });
        }, 400 );
    });
});
