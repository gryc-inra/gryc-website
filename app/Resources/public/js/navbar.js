function setNavbarAffix() {
    var $header = $('header');

    $('nav.navbar').affix({
        offset: {
            top: $header.is(":visible") ? $header.height() : 0
        }
    });
}

setNavbarAffix();
$(window).resize(function () {
    setNavbarAffix();
});
