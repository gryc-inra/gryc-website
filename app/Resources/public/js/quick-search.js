$(document).ready(function() {
    $( "#quick-search-form" ).submit(function( event ) {
        event.preventDefault();
        var $keyword = $( "input:first" ).val();

        if ( $keyword !== "" &&  $keyword !== null) {
            $(location).attr('href', Routing.generate('quick-search', { keyword: $keyword }));
        }
    });
});
