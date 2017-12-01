// Handling the modal confirmation message.

// Secure submit button, for form that require a confirmation modal
$('form[data-confirmation]').submit(function (e) {
    var form = $(this),
        confirm = $('#confirmationModal');

    if (confirm.data('result') !== 'yes') {
        //cancel submit event
        event.preventDefault();

        confirm
            .off('click', '#btnYes')
            .on('click', '#btnYes', function () {
                confirm.data('result', 'yes');
                form.find('input[type="submit"]').attr('disabled', 'disabled');
                form.submit();
            })
            .modal('show');
    }
});

// Secure links that require a confirmation modal
$('a[data-confirmation]').click(function(e) {
    var a = $(this),
        confirm = $('#confirmationModal');

    if (confirm.data('result') !== 'yes') {
        //cancel submit event
        e.preventDefault();

        confirm
            .off('click', '#btnYes')
            .on('click', '#btnYes', function () {
                confirm.data('result', 'yes');
                window.location = a.attr('href');
            })
            .modal('show');
    }
});
