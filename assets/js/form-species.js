var collectionType = require('./collection-type');

$(document).ready(function() {
    collectionType($('div#species_synonyms'), 'Add a synonym', 'add_synonym');
    collectionType($('div#species_lineages'), 'Add a lineage', 'add_lineage');

    // Set the taxid div
    var taxidDiv = $('#species_taxId').closest(".form-group");

    // Resize the input
    $('.col-sm-10', taxidDiv).removeClass('col-sm-10').addClass('col-sm-9');

    // Add a button and a hidden loader
    var button = $ ('<div class="col-sm-1"><a href="#" id="taxid-send" class="btn btn-info btn-sm"><span class="fas fa-sync"></span></a><span style="display:none;" id="taxid-send-loader" class="btn btn-info btn-sm" disabled="true"><span class="fas fa-sync fa-spin fa-fw" title="Ajax loader"></span></span></div>');
    taxidDiv.append(button);

    // Retrieve the URL scheme
    var urlScheme = taxidDiv.closest('form').data('url');

    // When someone click on the button
    button.click(function (e) {
        // To prevent a # in the URL
        e.preventDefault();

        // Take the taxid value
        var taxid = $("#species_taxId").val();

        // If the visitor click on the button with something written in the input field
        if (taxid.length > 0) {
            var url = urlScheme.replace(/__taxid__/g, taxid);

            $.ajax({
                url: url,
                dataType: 'json',
                beforeSend: function () {
                    // Replace the button by a loader
                    $('#taxid-send').hide();
                    $('#taxid-send-loader').show();

                    // Remove previous error messages
                    taxidDiv.find('input').removeClass("is-invalid");
                    $(".col-sm-9  > .invalid-feedback", taxidDiv).remove();
                },
                success: function (data) {
                    // If the result is an error, display it
                    if ("error" in data) {
                        taxidDiv.find('input').addClass("is-invalid");
                        $(".col-sm-9", taxidDiv).append("<div class='invalid-feedback'><ul class='list-unstyled mb-0'><li><span class='fas fa-exclamation'></span> " + data.error + "</li> </ul></div>");
                    // Else call populate function
                    } else {
                        populate(data);
                    }
                },
                complete: function () {
                    // Replace the loader by a button
                    $('#taxid-send').show();
                    $('#taxid-send-loader').hide();
                }
            });
        }
    });

    function populate(data) {
        // A while on each value of the json
        $.each(data, function(key, value){
            if (key === 'synonyms') { // If this is a synonyms array, create fields before if needed
                populateCollection($('#species_synonyms'), $('#add_synonym'), "input[name^='species[synonyms]']", value);
            } else if(key === 'lineages') { // Else if this is a lineage array
                populateCollection($('#species_lineages'), $('#add_lineage'), "input[name^='species[lineages]']", value);
            } else { // Else just fill the input
                $(' #species_'+key).val(value);
            }
        });
    }

    function populateCollection(collection, addButton, inputSelector, value) {

        // Count how many existant field there are
        var existingFields = collection.children('.form-group');
        var nbExistingFields = existingFields.length;
        var nbNeededFields = value.length;

        // If the needed number of field is > to the existing, create fields
        if (nbNeededFields > nbExistingFields) {
            // Create fields
            for(i = 0; i < (nbNeededFields - nbExistingFields); i++) {
                addButton.trigger('click');
            }
        }
        // Else if, the needed number of field is < to the existing, remove fields
        else if(nbNeededFields < nbExistingFields) {
            // Remove fields
            for(i = 0; i < (nbExistingFields - nbNeededFields); i++) {
                collection.children('.form-group').last().remove();
            }
        }

        // Hydrate the fields
        $.each(value, function(subkey, subvalue){
            // Fill elements
            $(inputSelector).eq(subkey).val(subvalue);
        });
    }
});
