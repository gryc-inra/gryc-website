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
