$(document).ready(function() {
    var $cartBadge = $('a#cart span.badge');

    $('a.cart-add-btn').click(function(e) {
        e.preventDefault();
        var $url = $(this).attr('href');

        $.get( $url, function( data ) {
            // Count objects in data
            var $nbItems = data.length;
            $cartBadge.text($nbItems);
        });
    });

    $('a.cart-remove-btn').click(function(e) {
        e.preventDefault();
        var $url = $(this).attr('href');
        var $tableRow = $(this).closest('tr');

        $.get( $url, function( data ) {
            // Count objects in data
            var $nbItems = data.length;
            $cartBadge.text($nbItems);

            // Remove the line in the page
            $tableRow.remove();
        });
    });
});
