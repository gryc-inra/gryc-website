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
        // Get the clade
        var clade = $(this).val();

        // Call the function and give the clade
        showHideStrains(clade);
    });

    function showHideStrains(clade) {
        if ('' === clade) {
            strainsCheckboxes.show();
        } else {
            // Hide all Strains
            strainsCheckboxes.hide();

            // Show clade strains
            strainsCheckboxes.each(function () {
                var strainClade = $( this ).find( ":checkbox" ).data('clade');

                if (strainClade === clade) {
                    $(this).show();
                }
            });
        }
    }

    // Create onClick event on checkAllLink
    checkAllLink.click(function (e) {
        e.preventDefault();
        var cladeFiltered = strainsFilterSelect.val();

        if ('' === cladeFiltered) {
            checkAll();
        } else {
            checkAllClade(cladeFiltered);
        }
    });

    // Create onClick event on uncheckAllLink
    uncheckAllLink.click(function (e) {
        e.preventDefault();
        var cladeFiltered = strainsFilterSelect.val();

        if ('' === cladeFiltered) {
            uncheckAll();
        } else {
            uncheckAllClade(cladeFiltered);
        }
    });

    //
    // Base functions: check/uncheck all checkboxes and check/uncheck specific strains (per clade)
    //

    function checkAllClade(cladeFiltered) {
        strainsCheckboxes.each(function () {
            var strainClade = $(this).find( "input:checkbox" ).data('clade');

            if (strainClade === cladeFiltered) {
                $(this).find("input:checkbox").prop('checked', true);
            }
        });
    }

    function uncheckAllClade(cladeFiltered) {
        strainsCheckboxes.each(function () {
            var strainClade = $(this).find( "input:checkbox" ).data('clade');

            if (strainClade === cladeFiltered) {
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
