$(document).ready(function() {
    $( "#quick-search-form" ).submit(function( event ) {
        if ( $( "input:first" ).val() !== "" &&  $( "input:first" ).val() !== null) {
            $(location).attr('href','http://gryc.dev/quick-search/'+$( "input:first").val());
        }
        event.preventDefault();
    });
});