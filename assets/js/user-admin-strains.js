// Check all checkboxes no disabled
function checkAll(groupName) {
    $(":checkbox[data-name=" + groupName + "]:not(:disabled)").prop('checked', true);
}

// Uncheck all checkboxes disabled too
function uncheckAll(groupName) {
    $("input:checkbox[data-name=" + groupName + "]").prop('checked', false);
}

// Uncheck all disabled checkbox
$(document).ready(function() {
    $(":checkbox:disabled").prop('checked', false);

    // On checkAll click
    $('.checkAllStrains').click(function(e) {
        e.preventDefault();
        var species = $( this ).data('species');
        checkAll(species);
    });

    // On uncheckAllClick
    $('.uncheckAllStrains').click(function(e) {
        e.preventDefault();
        var species = $( this ).data('species');
        uncheckAll(species);
    });
});
