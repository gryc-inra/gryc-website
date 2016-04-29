$(document).ready(function() {
    $('a.btn[disabled]').click(function(e) {
        if(!confirm('This button is disabled because this object have at least a child.\n\n Are you sure you want to proceed ?')) {
            e.preventDefault();
        }
    });
});