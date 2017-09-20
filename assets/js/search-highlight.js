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
