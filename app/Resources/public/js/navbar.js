function setNavbarAffix() {
    var $header = $('header');
    var $offset = $header.is(":visible") ? $header.height() : 0;

    $('nav.navbar').affix({
        offset: {
            top: $offset
        }
    });
}

setNavbarAffix();
$(window).resize(function () {
    setNavbarAffix();
});
