$(window).on('activate.bs.scrollspy', function () {
    // Remove all display class
    var $allLi = $('nav#blast-scrollspy nav a.active + nav a');
    $allLi.removeClass('display');

    // Add display class on 2 before and 2 after
    var $activeLi = $('nav#blast-scrollspy nav a.active + nav a.active');
    $activeLi.prev().addClass('display');
    $activeLi.prev().prev().addClass('display');
    $activeLi.next().addClass('display');
    $activeLi.next().next().addClass('display');

    // Add display on the first and 2nd
    $allLi.eq(0).addClass('display');
    $allLi.eq(1).addClass('display');
});
