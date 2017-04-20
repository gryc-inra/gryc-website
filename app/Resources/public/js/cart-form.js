function showHideCartField() {
    var $type = $('#cart_type');
    var $feature = $('#cart_feature');
    var $intronSplicing = $('#cart_intronSplicing');
    var $upstream = $('#cart_upstream');
    var $downstream = $('#cart_downstream');
    var $setup = $feature.closest('fieldset');

    if ('prot' === $type.val()) {
        $setup.hide();
    } else {
        $setup.show();
    }

    if ('locus' === $feature.val()) {
        $intronSplicing.val(0);
        $intronSplicing.prop('disabled', true);
    } else {
        $intronSplicing.prop('disabled', false);
    }

    if ('1' === $intronSplicing.val()) {
        $upstream.closest('div.form-group').hide();
        $downstream.closest('div.form-group').hide();
    } else {
        $upstream.closest('div.form-group').show();
        $downstream.closest('div.form-group').show();
    }

    $type.change(function() {
        showHideCartField();
    });

    $feature.change(function() {
        showHideCartField();
    });

    $intronSplicing.change(function() {
        showHideCartField();
    });
}
