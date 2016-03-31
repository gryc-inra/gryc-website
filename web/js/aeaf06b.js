$(document).ready(function() {
    $('[data-toggle="auto-dismiss"]').hide();
    $('[data-toggle="auto-dismiss"]').fadeIn("low");
    $('[data-toggle="auto-dismiss"]').delay('5000').fadeOut("low");
});
$(document).ready(function() {
    $( "#quick-search-form" ).submit(function( event ) {
        if ( $( "input:first" ).val() !== "" &&  $( "input:first" ).val() !== null) {
            $(location).attr('href','http://gryc.dev/quick-search/'+$( "input:first").val());
        }
        event.preventDefault();
    });
});