// The team filter function call for each checkbox input we want filtered
function strainsFilter(strainsCheckBoxesContainer, strainsFilterSelect) {

    // Define var that contains fields
    var strainsCheckboxes = strainsCheckBoxesContainer.find( '.checkbox' );

    //********************************//
    //  Add the links (check/uncheck) //
    //********************************//

    // Define checkAll/uncheckAll links
    var checkAllLink = $('<a href="#" class="check_all_strains" > Check all</a>');
    var uncheckAllLink = $('<a href="#" class="uncheck_all_strains" > Uncheck all</a>');

    // Insert the check/uncheck links
    strainsCheckBoxesContainer.prepend(uncheckAllLink);
    strainsCheckBoxesContainer.prepend(' / ');
    strainsCheckBoxesContainer.prepend(checkAllLink);

    //***************************//
    // Create all onCLick events //
    //***************************//

    // Create onClick event on Team filter
    strainsFilterSelect.change(function () {
        // Get the genus
        var genus = $(this).val();

        // Call the function and give the genus
        showHideStrains(genus);
    });

    function showHideStrains(genus) {
        if ('' === genus) {
            strainsCheckboxes.show();
        } else {
            // Hide all Strains
            strainsCheckboxes.hide();

            // Show genus strains
            strainsCheckboxes.each(function () {
                var strainGenus = $( this ).find( ":checkbox" ).data('genus');

                if (strainGenus === genus) {
                    $(this).show();
                }
            });
        }
    }

    // Create onClick event on checkAllLink
    checkAllLink.click(function (e) {
        e.preventDefault();
        var genusFiltered = strainsFilterSelect.val();

        if ('' === genusFiltered) {
            checkAll();
        } else {
            checkAllGenus(genusFiltered);
        }
    });

    // Create onClick event on uncheckAllLink
    uncheckAllLink.click(function (e) {
        e.preventDefault();
        var genusFiltered = strainsFilterSelect.val();

        if ('' === genusFiltered) {
            uncheckAll();
        } else {
            uncheckAllGenus(genusFiltered);
        }
    });

    //
    // Base functions: check/uncheck all checkboxes and check/uncheck specific strains (per genus)
    //

    function checkAllGenus(genusFiltered) {
        strainsCheckboxes.each(function () {
            var strainGenus = $(this).find( "input:checkbox" ).data('genus');

            if (strainGenus === genusFiltered) {
                $(this).find("input:checkbox").prop('checked', true);
            }
        });
    }

    function uncheckAllGenus(genusFiltered) {
        strainsCheckboxes.each(function () {
            var strainGenus = $(this).find( "input:checkbox" ).data('genus');

            if (strainGenus === genusFiltered) {
                $(this).find("input:checkbox").prop('checked', false);
            }
        });
    }

    function checkAll() {
        strainsCheckboxes.each(function () {
            $(this).find("input:checkbox").prop('checked', true);
        });
    }

    function uncheckAll() {
        strainsCheckboxes.each(function () {
            $(this).find("input:checkbox").prop('checked', false);
        });
    }
}
