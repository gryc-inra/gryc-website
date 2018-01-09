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

$(function() {
    var searchResults = $('#search-results');
    var keyword = searchResults.data('search-keyword');
    var pattern = '('+keyword+')';
    var regex = new RegExp(pattern, 'gi');

    // Replace in each h5 a content
    searchResults.find('h5 > a, p').each(function() {
          var html = $(this).html();
        html = html.replace(regex, '<b>$1</b>');
        $(this).html(html);
    });
});
