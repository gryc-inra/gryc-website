webpackJsonp([0],{

/***/ "./assets/js/auto-dismiss-alert.js":
/*!*****************************************!*\
  !*** ./assets/js/auto-dismiss-alert.js ***!
  \*****************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {
    $('[data-toggle="auto-dismiss"]').hide();
    $('[data-toggle="auto-dismiss"]').fadeIn("low");
    $('[data-toggle="auto-dismiss"]').delay('5000').fadeOut("low");
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/blast-scrollspy.js":
/*!**************************************!*\
  !*** ./assets/js/blast-scrollspy.js ***!
  \**************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(window).on('activate.bs.scrollspy', function () {
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
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/blast-select-change.js":
/*!******************************************!*\
  !*** ./assets/js/blast-select-change.js ***!
  \******************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {
    var $tool = $('#blast_tool');

    $tool = $('input[name="blast[tool]"]');

    // When the user change of tool...
    $tool.change(function () {
        // Retrieve the form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected tool value.
        var data = {};
        data[$tool.attr('name')] = $('input[name="blast[tool]"]:checked').val();

        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            success: function success(html) {
                // Replace current position field ...
                $('#blast_database').replaceWith(
                // ... with the returned one from the AJAX response.
                $(html).find('#blast_database'));
            }
        });
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/cart-btn.js":
/*!*******************************!*\
  !*** ./assets/js/cart-btn.js ***!
  \*******************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {
    var $cartBadge = $('a#cart span.badge');

    $('a.cart-add-btn').click(function (e) {
        e.preventDefault();
        var $url = $(this).attr('href');

        $.get($url, function (data) {
            // Count objects in data
            var $nbItems = data.items.length;
            $cartBadge.text($nbItems);

            // if reached limit
            if (true === data.reached_limit) {
                location.reload();
            }
        });
    });

    $('a.cart-remove-btn').click(function (e) {
        e.preventDefault();
        var $url = $(this).attr('href');
        var $tableRow = $(this).closest('tr');

        $.get($url, function (data) {
            // Count objects in data
            var $nbItems = data.items.length;
            $cartBadge.text($nbItems);

            // Remove the line in the page
            $tableRow.remove();
        });
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/cart-fasta.js":
/*!*********************************!*\
  !*** ./assets/js/cart-fasta.js ***!
  \*********************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {function generateCartFasta(textareaId, modalId) {
    var $modal = $(modalId);
    var $form = $modal.find('form');

    var $values = {};

    $.each($form[0].elements, function (i, field) {
        $values[field.name] = field.value;
    });

    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        dataType: 'text',
        data: $values,
        success: function success(data) {
            $(modalId).modal('hide');
            $(textareaId).val(data);
        }
    });
}

$(function () {
    $('#generate-fasta-from-cart-button').click(function (e) {
        generateCartFasta('#blast_query', '#cartFormModal');
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/cart-form.js":
/*!********************************!*\
  !*** ./assets/js/cart-form.js ***!
  \********************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {function showHideCartSetup() {
    var $type = $('select[id$=\'cart_type\']');
    var $feature = $('select[id$=\'cart_feature\']');
    var $intronSplicing = $('select[id$=\'cart_intronSplicing\']');
    var $upstream = $('input[id$=\'cart_upstream\']');
    var $downstream = $('input[id$=\'cart_downstream\']');
    var $setup = $feature.closest('#cart-setup');

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

    $type.change(function () {
        showHideCartSetup();
    });

    $feature.change(function () {
        showHideCartSetup();
    });

    $intronSplicing.change(function () {
        showHideCartSetup();
    });
}

showHideCartSetup();
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/collection-type.js":
/*!**************************************!*\
  !*** ./assets/js/collection-type.js ***!
  \**************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {function collectionType(container, buttonText, buttonId, fieldStart, functions) {
    if (buttonId === undefined) {
        buttonId = null;
    }

    if (fieldStart === undefined) {
        fieldStart = false;
    }

    if (functions === undefined) {
        functions = [];
    }

    // Delete the first label (the number of the field), and the required class
    container.children('div').find('label:first').text('');
    container.children('div').find('label:first').removeClass('required');
    container.children('div').find('label:first').removeClass('required');

    // Create and add a button to add new field
    if (buttonId) {
        var id = "id='" + buttonId + "'";
        var $addButton = $('<a href="#" ' + id + 'class="btn btn-default btn-xs"><span class="fa fa-plus aria-hidden="true""></span> ' + buttonText + '</a>');
    } else {
        var $addButton = $('<a href="#" class="btn btn-default btn-xs"><span class="fa fa-plus aria-hidden="true""></span> ' + buttonText + '</a>');
    }

    container.append($addButton);

    // Add a click event on the add button
    $addButton.click(function (e) {
        e.preventDefault();
        // Call the addField method
        addField(container);
        return false;
    });

    // Define an index to count the number of added field (used to give name to fields)
    var index = container.children('div').length;

    // If the index is > 0, fields already exists, then, add a deleteButton to this fields
    if (index > 0) {
        container.children('div').each(function () {
            addDeleteButton($(this));
            addFunctions($(this));
        });
    }

    // If we want to have a field at start
    if (true == fieldStart && 0 == index) {
        addField(container);
    }

    // The addField function
    function addField(container) {
        // Replace some value in the « data-prototype »
        // - "__name__label__" by the name we want to use, here nothing
        // - "__name__" by the name of the field, here the index number
        var $prototype = $(container.attr('data-prototype').replace(/class="col-sm-2 control-label required"/, 'class="col-sm-2 control-label"').replace(/__name__label__/g, '').replace(/__name__/g, index));

        // Add a delete button to the new field
        addDeleteButton($prototype);

        // If there are supplementary functions
        addFunctions($prototype);

        // Add the field in the form
        $addButton.before($prototype);

        // Increment the counter
        index++;
    }

    // A function called to add deleteButton
    function addDeleteButton(prototype) {
        // First, create the button
        var $deleteButton = $('<div class="col-sm-1"><a href="#" class="btn btn-danger btn-sm"><span class="fa fa-trash" aria-hidden="true"></span></a></div>');

        // Add the button on the field
        $('.col-sm-10', prototype).removeClass('col-sm-10').addClass('col-sm-9');
        prototype.append($deleteButton);

        // Create a listener on the click event
        $deleteButton.click(function (e) {
            e.preventDefault();
            // Remove the field
            prototype.remove();
            return false;
        });
    }

    function addFunctions(prototype) {
        // If there are supplementary functions
        if (functions.length > 0) {
            // Do a while on functions, and apply them to the prototype
            for (var i = 0; functions.length > i; i++) {
                functions[i](prototype);
            }
        }
    }
}
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/copy2clipboard.js":
/*!*************************************!*\
  !*** ./assets/js/copy2clipboard.js ***!
  \*************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {function copy2clipboard(dataSelector) {
    dataSelector.select();
    document.execCommand('copy');
}

function copy2clipboardOnClick(clickTrigger, dataSelector) {
    clickTrigger.click(function () {
        copy2clipboard(dataSelector);
    });
}

$(function () {
    copy2clipboardOnClick($("#reverse-complement-copy-button"), $("#reverse-complement-result"));
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/delay.js":
/*!****************************!*\
  !*** ./assets/js/delay.js ***!
  \****************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports) {

// var delay = (function(){
//     var timer = 0;
//     return function(callback, ms){
//         clearTimeout (timer);
//         timer = setTimeout(callback, ms);
//     };
// })();

module.exports = function () {
    return function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    }();
}();

/***/ }),

/***/ "./assets/js/live-sequence-display.js":
/*!********************************************!*\
  !*** ./assets/js/live-sequence-display.js ***!
  \********************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {
    $('div.locus-feature').each(function (index) {
        var locus = $(this).data("locus");
        var feature = $(this).data("feature");
        var sequenceContainer = $(this).find('div.fasta');
        var form = $(this).find('form');

        form.removeClass('hidden');

        form.submit(function (event) {
            event.preventDefault();
            var upstream = $(this).parent().find("input[name='upstream']").val();
            var downstream = $(this).parent().find("input[name='downstream']").val();
            var showUtr = $(this).parent().find("input[name='showUtr']").is(":checked");
            var showIntron = $(this).parent().find("input[name='showIntron']").is(":checked");

            $.ajax({
                type: 'GET',
                url: Routing.generate('feature_sequence', { locus_name: locus, feature_name: feature, upstream: upstream, downstream: downstream, showUtr: showUtr, showIntron: showIntron }),
                dataType: 'html',
                success: function success(html) {
                    sequenceContainer.first().html(html);
                }
            });
        });
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/locus-tooltip.js":
/*!************************************!*\
  !*** ./assets/js/locus-tooltip.js ***!
  \************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/password-control.js":
/*!***************************************!*\
  !*** ./assets/js/password-control.js ***!
  \***************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$("input[type=password][id*='_plainPassword_']").keyup(function () {
    // Set regex control
    var ucase = new RegExp("[A-Z]+");
    var lcase = new RegExp("[a-z]+");
    var num = new RegExp("[0-9]+");

    // Set password fields
    var password1 = $("[id$='_plainPassword_first']");
    var password2 = $("[id$='_plainPassword_second']");

    // Set display result
    var numberChars = $("#number-chars");
    var upperCase = $("#upper-case");
    var lowerCase = $("#lower-case");
    var number = $("#number");
    var passwordMatch = $("#password-match");

    // Do the test
    if (password1.val().length >= 8) {
        numberChars.removeClass("fa-times");
        numberChars.addClass("fa-check");
        numberChars.css("color", "#00A41E");
    } else {
        numberChars.removeClass("fa-check");
        numberChars.addClass("fa-times");
        numberChars.css("color", "#FF0004");
    }

    if (ucase.test(password1.val())) {
        upperCase.removeClass("fa-times");
        upperCase.addClass("fa-check");
        upperCase.css("color", "#00A41E");
    } else {
        upperCase.removeClass("fa-check");
        upperCase.addClass("fa-times");
        upperCase.css("color", "#FF0004");
    }

    if (lcase.test(password1.val())) {
        lowerCase.removeClass("fa-times");
        lowerCase.addClass("fa-check");
        lowerCase.css("color", "#00A41E");
    } else {
        lowerCase.removeClass("fa-check");
        lowerCase.addClass("fa-times");
        lowerCase.css("color", "#FF0004");
    }

    if (num.test(password1.val())) {
        number.removeClass("fa-times");
        number.addClass("fa-check");
        number.css("color", "#00A41E");
    } else {
        number.removeClass("fa-check");
        number.addClass("fa-times");
        number.css("color", "#FF0004");
    }

    if (password1.val() === password2.val() && password1.val() !== '') {
        passwordMatch.removeClass("fa-times");
        passwordMatch.addClass("fa-check");
        passwordMatch.css("color", "#00A41E");
    } else {
        passwordMatch.removeClass("fa-check");
        passwordMatch.addClass("fa-times");
        passwordMatch.css("color", "#FF0004");
    }
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/search-keyword-highlight.js":
/*!***********************************************!*\
  !*** ./assets/js/search-keyword-highlight.js ***!
  \***********************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {
    var result = $('#search-results');

    if (result.length > 0) {
        var keyword = result.data('search-keyword');
        keyword = '(' + keyword + ')';
        var regex = new RegExp(keyword, "gi");
        var resultHtml = result.html();

        resultHtml = resultHtml.replace(regex, "<b>$1</b>");
        result.html(resultHtml);
    }
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/strains-filter.js":
/*!*************************************!*\
  !*** ./assets/js/strains-filter.js ***!
  \*************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {function strainsFilter(strainsFilterSelect, strainsCheckBoxesContainer) {

    // Define var that contains fields
    var strainsCheckboxes = strainsCheckBoxesContainer.find('label');

    //********************************//
    //  Add the links (check/uncheck) //
    //********************************//

    // Define checkAll/uncheckAll links
    var checkAllLink = $('<a href="#" class="check_all_strains" > Check all</a>');
    var uncheckAllLink = $('<a href="#" class="uncheck_all_strains" > Uncheck all</a>');

    // Insert the check/uncheck links
    strainsCheckBoxesContainer.before(uncheckAllLink);
    strainsCheckBoxesContainer.before(' / ');
    strainsCheckBoxesContainer.before(checkAllLink);

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
                var strainClade = $(this).find(":checkbox").data('clade');

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
            var strainClade = $(this).find("input:checkbox").data('clade');

            if (strainClade === cladeFiltered) {
                $(this).find("input:checkbox").prop('checked', true);
            }
        });
    }

    function uncheckAllClade(cladeFiltered) {
        strainsCheckboxes.each(function () {
            var strainClade = $(this).find("input:checkbox").data('clade');

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

$(function () {
    strainsFilter($("#blast_strainsFilter_filter"), $("#blast_strainsFilter_strains"));
    strainsFilter($("#advanced_search_strainsFilter_filter"), $("#advanced_search_strainsFilter_strains"));
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/user-admin-strains.js":
/*!*****************************************!*\
  !*** ./assets/js/user-admin-strains.js ***!
  \*****************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {// Check all checkboxes no disabled
function checkAll(groupName) {
    $(":checkbox[data-name=" + groupName + "]:not(:disabled)").prop('checked', true);
}

// Uncheck all checkboxes disabled too
function uncheckAll(groupName) {
    $("input:checkbox[data-name=" + groupName + "]").prop('checked', false);
}

// Uncheck all disabled checkbox
$(document).ready(function () {
    $(":checkbox:disabled").prop('checked', false);

    // On checkAll click
    $('.checkAllStrains').click(function (e) {
        e.preventDefault();
        var species = $(this).data('species');
        checkAll(species);
    });

    // On uncheckAllClick
    $('.uncheckAllStrains').click(function (e) {
        e.preventDefault();
        var species = $(this).data('species');
        uncheckAll(species);
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ "./assets/js/user-instant-search.js":
/*!******************************************!*\
  !*** ./assets/js/user-instant-search.js ***!
  \******************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {var delay = __webpack_require__(/*! ./delay */ "./assets/js/delay.js");

$(document).ready(function () {
    var processing = false;
    var search = $('#user-search-field');
    var team = $('#user-team-field');

    search.keyup(function () {
        history.replaceState('', '', Routing.generate('user_index', { q: search.val(), p: 1 }));

        delay(function () {
            $.ajax({
                type: 'GET',
                url: Routing.generate('user_index_ajax', { q: search.val(), p: 1 }),
                dataType: 'html',
                delay: 400,
                beforeSend: function beforeSend() {
                    if (processing) {
                        return false;
                    } else {
                        processing = true;
                    }
                },
                success: function success(html) {
                    $('#user-list').replaceWith(html);
                    processing = false;
                }
            });
        }, 400);
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

/***/ 1:
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** multi ./assets/js/auto-dismiss-alert.js ./assets/js/blast-scrollspy.js ./assets/js/blast-select-change.js ./assets/js/cart-btn.js ./assets/js/cart-fasta.js ./assets/js/cart-form.js ./assets/js/collection-type.js ./assets/js/copy2clipboard.js ./assets/js/delay.js ./assets/js/live-sequence-display.js ./assets/js/locus-tooltip.js ./assets/js/password-control.js ./assets/js/search-keyword-highlight.js ./assets/js/strains-filter.js ./assets/js/user-admin-strains.js ./assets/js/user-instant-search.js ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! ./assets/js/auto-dismiss-alert.js */"./assets/js/auto-dismiss-alert.js");
__webpack_require__(/*! ./assets/js/blast-scrollspy.js */"./assets/js/blast-scrollspy.js");
__webpack_require__(/*! ./assets/js/blast-select-change.js */"./assets/js/blast-select-change.js");
__webpack_require__(/*! ./assets/js/cart-btn.js */"./assets/js/cart-btn.js");
__webpack_require__(/*! ./assets/js/cart-fasta.js */"./assets/js/cart-fasta.js");
__webpack_require__(/*! ./assets/js/cart-form.js */"./assets/js/cart-form.js");
__webpack_require__(/*! ./assets/js/collection-type.js */"./assets/js/collection-type.js");
__webpack_require__(/*! ./assets/js/copy2clipboard.js */"./assets/js/copy2clipboard.js");
__webpack_require__(/*! ./assets/js/delay.js */"./assets/js/delay.js");
__webpack_require__(/*! ./assets/js/live-sequence-display.js */"./assets/js/live-sequence-display.js");
__webpack_require__(/*! ./assets/js/locus-tooltip.js */"./assets/js/locus-tooltip.js");
__webpack_require__(/*! ./assets/js/password-control.js */"./assets/js/password-control.js");
__webpack_require__(/*! ./assets/js/search-keyword-highlight.js */"./assets/js/search-keyword-highlight.js");
__webpack_require__(/*! ./assets/js/strains-filter.js */"./assets/js/strains-filter.js");
__webpack_require__(/*! ./assets/js/user-admin-strains.js */"./assets/js/user-admin-strains.js");
module.exports = __webpack_require__(/*! ./assets/js/user-instant-search.js */"./assets/js/user-instant-search.js");


/***/ })

},[1]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYXV0by1kaXNtaXNzLWFsZXJ0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9ibGFzdC1zY3JvbGxzcHkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2JsYXN0LXNlbGVjdC1jaGFuZ2UuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NhcnQtYnRuLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZhc3RhLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZvcm0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxlY3Rpb24tdHlwZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvY29weTJjbGlwYm9hcmQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2RlbGF5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9saXZlLXNlcXVlbmNlLWRpc3BsYXkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2xvY3VzLXRvb2x0aXAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Bhc3N3b3JkLWNvbnRyb2wuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlYXJjaC1rZXl3b3JkLWhpZ2hsaWdodC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc3RyYWlucy1maWx0ZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWRtaW4tc3RyYWlucy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1pbnN0YW50LXNlYXJjaC5qcyJdLCJuYW1lcyI6WyIkIiwiZG9jdW1lbnQiLCJyZWFkeSIsImhpZGUiLCJmYWRlSW4iLCJkZWxheSIsImZhZGVPdXQiLCJ3aW5kb3ciLCJvbiIsIiRhbGxMaSIsInJlbW92ZUNsYXNzIiwiJGFjdGl2ZUxpIiwicHJldiIsImFkZENsYXNzIiwibmV4dCIsImVxIiwiJHRvb2wiLCJjaGFuZ2UiLCIkZm9ybSIsImNsb3Nlc3QiLCJkYXRhIiwiYXR0ciIsInZhbCIsImFqYXgiLCJ1cmwiLCJ0eXBlIiwic3VjY2VzcyIsImh0bWwiLCJyZXBsYWNlV2l0aCIsImZpbmQiLCIkY2FydEJhZGdlIiwiY2xpY2siLCJlIiwicHJldmVudERlZmF1bHQiLCIkdXJsIiwiZ2V0IiwiJG5iSXRlbXMiLCJpdGVtcyIsImxlbmd0aCIsInRleHQiLCJyZWFjaGVkX2xpbWl0IiwibG9jYXRpb24iLCJyZWxvYWQiLCIkdGFibGVSb3ciLCJyZW1vdmUiLCJnZW5lcmF0ZUNhcnRGYXN0YSIsInRleHRhcmVhSWQiLCJtb2RhbElkIiwiJG1vZGFsIiwiJHZhbHVlcyIsImVhY2giLCJlbGVtZW50cyIsImkiLCJmaWVsZCIsIm5hbWUiLCJ2YWx1ZSIsImRhdGFUeXBlIiwibW9kYWwiLCJzaG93SGlkZUNhcnRTZXR1cCIsIiR0eXBlIiwiJGZlYXR1cmUiLCIkaW50cm9uU3BsaWNpbmciLCIkdXBzdHJlYW0iLCIkZG93bnN0cmVhbSIsIiRzZXR1cCIsInNob3ciLCJwcm9wIiwiY29sbGVjdGlvblR5cGUiLCJjb250YWluZXIiLCJidXR0b25UZXh0IiwiYnV0dG9uSWQiLCJmaWVsZFN0YXJ0IiwiZnVuY3Rpb25zIiwidW5kZWZpbmVkIiwiY2hpbGRyZW4iLCJpZCIsIiRhZGRCdXR0b24iLCJhcHBlbmQiLCJhZGRGaWVsZCIsImluZGV4IiwiYWRkRGVsZXRlQnV0dG9uIiwiYWRkRnVuY3Rpb25zIiwiJHByb3RvdHlwZSIsInJlcGxhY2UiLCJiZWZvcmUiLCJwcm90b3R5cGUiLCIkZGVsZXRlQnV0dG9uIiwiY29weTJjbGlwYm9hcmQiLCJkYXRhU2VsZWN0b3IiLCJzZWxlY3QiLCJleGVjQ29tbWFuZCIsImNvcHkyY2xpcGJvYXJkT25DbGljayIsImNsaWNrVHJpZ2dlciIsIm1vZHVsZSIsImV4cG9ydHMiLCJ0aW1lciIsImNhbGxiYWNrIiwibXMiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwibG9jdXMiLCJmZWF0dXJlIiwic2VxdWVuY2VDb250YWluZXIiLCJmb3JtIiwic3VibWl0IiwiZXZlbnQiLCJ1cHN0cmVhbSIsInBhcmVudCIsImRvd25zdHJlYW0iLCJzaG93VXRyIiwiaXMiLCJzaG93SW50cm9uIiwiUm91dGluZyIsImdlbmVyYXRlIiwibG9jdXNfbmFtZSIsImZlYXR1cmVfbmFtZSIsImZpcnN0IiwidG9vbHRpcCIsImtleXVwIiwidWNhc2UiLCJSZWdFeHAiLCJsY2FzZSIsIm51bSIsInBhc3N3b3JkMSIsInBhc3N3b3JkMiIsIm51bWJlckNoYXJzIiwidXBwZXJDYXNlIiwibG93ZXJDYXNlIiwibnVtYmVyIiwicGFzc3dvcmRNYXRjaCIsImNzcyIsInRlc3QiLCJyZXN1bHQiLCJrZXl3b3JkIiwicmVnZXgiLCJyZXN1bHRIdG1sIiwic3RyYWluc0ZpbHRlciIsInN0cmFpbnNGaWx0ZXJTZWxlY3QiLCJzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lciIsInN0cmFpbnNDaGVja2JveGVzIiwiY2hlY2tBbGxMaW5rIiwidW5jaGVja0FsbExpbmsiLCJjbGFkZSIsInNob3dIaWRlU3RyYWlucyIsInN0cmFpbkNsYWRlIiwiY2xhZGVGaWx0ZXJlZCIsImNoZWNrQWxsIiwiY2hlY2tBbGxDbGFkZSIsInVuY2hlY2tBbGwiLCJ1bmNoZWNrQWxsQ2xhZGUiLCJncm91cE5hbWUiLCJzcGVjaWVzIiwicmVxdWlyZSIsInByb2Nlc3NpbmciLCJzZWFyY2giLCJ0ZWFtIiwiaGlzdG9yeSIsInJlcGxhY2VTdGF0ZSIsInEiLCJwIiwiYmVmb3JlU2VuZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7OztBQUFBLHlDQUFBQSxFQUFFQyxRQUFGLEVBQVlDLEtBQVosQ0FBa0IsWUFBVztBQUN6QkYsTUFBRSw4QkFBRixFQUFrQ0csSUFBbEM7QUFDQUgsTUFBRSw4QkFBRixFQUFrQ0ksTUFBbEMsQ0FBeUMsS0FBekM7QUFDQUosTUFBRSw4QkFBRixFQUFrQ0ssS0FBbEMsQ0FBd0MsTUFBeEMsRUFBZ0RDLE9BQWhELENBQXdELEtBQXhEO0FBQ0gsQ0FKRCxFOzs7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QUFDSDtBQWZFLGFBQVA7QUFpQkgsU0FsQkQsRUFrQkcsR0FsQkg7QUFtQkgsS0F0QkQ7QUF1QkgsQ0E1QkQsRSIsImZpbGUiOiJhcHAuZGU2MDE5ODMxODc1ZTYxZTk3NjQuanMiLCJzb3VyY2VzQ29udGVudCI6WyIkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcbiAgICAkKCdbZGF0YS10b2dnbGU9XCJhdXRvLWRpc21pc3NcIl0nKS5oaWRlKCk7XG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwiYXV0by1kaXNtaXNzXCJdJykuZmFkZUluKFwibG93XCIpO1xuICAgICQoJ1tkYXRhLXRvZ2dsZT1cImF1dG8tZGlzbWlzc1wiXScpLmRlbGF5KCc1MDAwJykuZmFkZU91dChcImxvd1wiKTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2F1dG8tZGlzbWlzcy1hbGVydC5qcyIsIiQod2luZG93KS5vbignYWN0aXZhdGUuYnMuc2Nyb2xsc3B5JywgZnVuY3Rpb24gKCkge1xuICAgIC8vIFJlbW92ZSBhbGwgZGlzcGxheSBjbGFzc1xuICAgIHZhciAkYWxsTGkgPSAkKCduYXYjYmxhc3Qtc2Nyb2xsc3B5IG5hdiBhLmFjdGl2ZSArIG5hdiBhJyk7XG4gICAgJGFsbExpLnJlbW92ZUNsYXNzKCdkaXNwbGF5Jyk7XG5cbiAgICAvLyBBZGQgZGlzcGxheSBjbGFzcyBvbiAyIGJlZm9yZSBhbmQgMiBhZnRlclxuICAgIHZhciAkYWN0aXZlTGkgPSAkKCduYXYjYmxhc3Qtc2Nyb2xsc3B5IG5hdiBhLmFjdGl2ZSArIG5hdiBhLmFjdGl2ZScpO1xuICAgICRhY3RpdmVMaS5wcmV2KCkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbiAgICAkYWN0aXZlTGkucHJldigpLnByZXYoKS5hZGRDbGFzcygnZGlzcGxheScpO1xuICAgICRhY3RpdmVMaS5uZXh0KCkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbiAgICAkYWN0aXZlTGkubmV4dCgpLm5leHQoKS5hZGRDbGFzcygnZGlzcGxheScpO1xuXG4gICAgLy8gQWRkIGRpc3BsYXkgb24gdGhlIGZpcnN0IGFuZCAybmRcbiAgICAkYWxsTGkuZXEoMCkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbiAgICAkYWxsTGkuZXEoMSkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2JsYXN0LXNjcm9sbHNweS5qcyIsIiQoIGRvY3VtZW50ICkucmVhZHkoZnVuY3Rpb24gKCkge1xuICAgIHZhciAkdG9vbCA9ICQoJyNibGFzdF90b29sJyk7XG5cbiAgICAkdG9vbCA9ICQoJ2lucHV0W25hbWU9XCJibGFzdFt0b29sXVwiXScpO1xuXG4gICAgLy8gV2hlbiB0aGUgdXNlciBjaGFuZ2Ugb2YgdG9vbC4uLlxuICAgICR0b29sLmNoYW5nZShmdW5jdGlvbiAoKSB7XG4gICAgICAgIC8vIFJldHJpZXZlIHRoZSBmb3JtLlxuICAgICAgICB2YXIgJGZvcm0gPSAkKHRoaXMpLmNsb3Nlc3QoJ2Zvcm0nKTtcbiAgICAgICAgLy8gU2ltdWxhdGUgZm9ybSBkYXRhLCBidXQgb25seSBpbmNsdWRlIHRoZSBzZWxlY3RlZCB0b29sIHZhbHVlLlxuICAgICAgICB2YXIgZGF0YSA9IHt9O1xuICAgICAgICBkYXRhWyR0b29sLmF0dHIoJ25hbWUnKV0gPSAkKCdpbnB1dFtuYW1lPVwiYmxhc3RbdG9vbF1cIl06Y2hlY2tlZCcpLnZhbCgpO1xuXG4gICAgICAgIC8vIFN1Ym1pdCBkYXRhIHZpYSBBSkFYIHRvIHRoZSBmb3JtJ3MgYWN0aW9uIHBhdGguXG4gICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICB1cmw6ICRmb3JtLmF0dHIoJ2FjdGlvbicpLFxuICAgICAgICAgICAgdHlwZTogJGZvcm0uYXR0cignbWV0aG9kJyksXG4gICAgICAgICAgICBkYXRhOiBkYXRhLFxuICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGh0bWwpIHtcbiAgICAgICAgICAgICAgICAvLyBSZXBsYWNlIGN1cnJlbnQgcG9zaXRpb24gZmllbGQgLi4uXG4gICAgICAgICAgICAgICAgJCgnI2JsYXN0X2RhdGFiYXNlJykucmVwbGFjZVdpdGgoXG4gICAgICAgICAgICAgICAgICAgIC8vIC4uLiB3aXRoIHRoZSByZXR1cm5lZCBvbmUgZnJvbSB0aGUgQUpBWCByZXNwb25zZS5cbiAgICAgICAgICAgICAgICAgICAgJChodG1sKS5maW5kKCcjYmxhc3RfZGF0YWJhc2UnKVxuICAgICAgICAgICAgICAgICk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvYmxhc3Qtc2VsZWN0LWNoYW5nZS5qcyIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgIHZhciAkY2FydEJhZGdlID0gJCgnYSNjYXJ0IHNwYW4uYmFkZ2UnKTtcblxuICAgICQoJ2EuY2FydC1hZGQtYnRuJykuY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHZhciAkdXJsID0gJCh0aGlzKS5hdHRyKCdocmVmJyk7XG5cbiAgICAgICAgJC5nZXQoICR1cmwsIGZ1bmN0aW9uKCBkYXRhICkge1xuICAgICAgICAgICAgLy8gQ291bnQgb2JqZWN0cyBpbiBkYXRhXG4gICAgICAgICAgICB2YXIgJG5iSXRlbXMgPSBkYXRhLml0ZW1zLmxlbmd0aDtcbiAgICAgICAgICAgICRjYXJ0QmFkZ2UudGV4dCgkbmJJdGVtcyk7XG5cbiAgICAgICAgICAgIC8vIGlmIHJlYWNoZWQgbGltaXRcbiAgICAgICAgICAgIGlmICh0cnVlID09PSBkYXRhLnJlYWNoZWRfbGltaXQpIHtcbiAgICAgICAgICAgICAgICBsb2NhdGlvbi5yZWxvYWQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSk7XG5cbiAgICAkKCdhLmNhcnQtcmVtb3ZlLWJ0bicpLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgJHVybCA9ICQodGhpcykuYXR0cignaHJlZicpO1xuICAgICAgICB2YXIgJHRhYmxlUm93ID0gJCh0aGlzKS5jbG9zZXN0KCd0cicpO1xuXG4gICAgICAgICQuZ2V0KCAkdXJsLCBmdW5jdGlvbiggZGF0YSApIHtcbiAgICAgICAgICAgIC8vIENvdW50IG9iamVjdHMgaW4gZGF0YVxuICAgICAgICAgICAgdmFyICRuYkl0ZW1zID0gZGF0YS5pdGVtcy5sZW5ndGg7XG4gICAgICAgICAgICAkY2FydEJhZGdlLnRleHQoJG5iSXRlbXMpO1xuXG4gICAgICAgICAgICAvLyBSZW1vdmUgdGhlIGxpbmUgaW4gdGhlIHBhZ2VcbiAgICAgICAgICAgICR0YWJsZVJvdy5yZW1vdmUoKTtcbiAgICAgICAgfSk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jYXJ0LWJ0bi5qcyIsImZ1bmN0aW9uIGdlbmVyYXRlQ2FydEZhc3RhKHRleHRhcmVhSWQsIG1vZGFsSWQpIHtcbiAgICB2YXIgJG1vZGFsID0gJChtb2RhbElkKTtcbiAgICB2YXIgJGZvcm0gPSAkbW9kYWwuZmluZCgnZm9ybScpO1xuXG4gICAgdmFyICR2YWx1ZXMgPSB7fTtcblxuICAgICQuZWFjaCggJGZvcm1bMF0uZWxlbWVudHMsIGZ1bmN0aW9uKGksIGZpZWxkKSB7XG4gICAgICAgICR2YWx1ZXNbZmllbGQubmFtZV0gPSBmaWVsZC52YWx1ZTtcbiAgICB9KTtcblxuICAgICQuYWpheCh7XG4gICAgICAgIHR5cGU6ICAgICAgICRmb3JtLmF0dHIoJ21ldGhvZCcpLFxuICAgICAgICB1cmw6ICAgICAgICAkZm9ybS5hdHRyKCdhY3Rpb24nKSxcbiAgICAgICAgZGF0YVR5cGU6ICAgJ3RleHQnLFxuICAgICAgICBkYXRhOiAgICAgICAkdmFsdWVzLFxuICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgJChtb2RhbElkKS5tb2RhbCgnaGlkZScpO1xuICAgICAgICAgICAgJCh0ZXh0YXJlYUlkKS52YWwoZGF0YSk7XG4gICAgICAgIH1cbiAgICB9KTtcbn1cblxuJChmdW5jdGlvbigpIHtcbiAgICAkKCcjZ2VuZXJhdGUtZmFzdGEtZnJvbS1jYXJ0LWJ1dHRvbicpLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZ2VuZXJhdGVDYXJ0RmFzdGEoJyNibGFzdF9xdWVyeScsICcjY2FydEZvcm1Nb2RhbCcpO1xuICAgIH0pO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY2FydC1mYXN0YS5qcyIsImZ1bmN0aW9uIHNob3dIaWRlQ2FydFNldHVwKCkge1xuICAgIHZhciAkdHlwZSA9ICQoJ3NlbGVjdFtpZCQ9XFwnY2FydF90eXBlXFwnXScpO1xuICAgIHZhciAkZmVhdHVyZSA9ICQoJ3NlbGVjdFtpZCQ9XFwnY2FydF9mZWF0dXJlXFwnXScpO1xuICAgIHZhciAkaW50cm9uU3BsaWNpbmcgPSAkKCdzZWxlY3RbaWQkPVxcJ2NhcnRfaW50cm9uU3BsaWNpbmdcXCddJyk7XG4gICAgdmFyICR1cHN0cmVhbSA9ICQoJ2lucHV0W2lkJD1cXCdjYXJ0X3Vwc3RyZWFtXFwnXScpO1xuICAgIHZhciAkZG93bnN0cmVhbSA9ICQoJ2lucHV0W2lkJD1cXCdjYXJ0X2Rvd25zdHJlYW1cXCddJyk7XG4gICAgdmFyICRzZXR1cCA9ICRmZWF0dXJlLmNsb3Nlc3QoJyNjYXJ0LXNldHVwJyk7XG5cbiAgICBpZiAoJ3Byb3QnID09PSAkdHlwZS52YWwoKSkge1xuICAgICAgICAkc2V0dXAuaGlkZSgpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgICRzZXR1cC5zaG93KCk7XG4gICAgfVxuXG4gICAgaWYgKCdsb2N1cycgPT09ICRmZWF0dXJlLnZhbCgpKSB7XG4gICAgICAgICRpbnRyb25TcGxpY2luZy52YWwoMCk7XG4gICAgICAgICRpbnRyb25TcGxpY2luZy5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgICRpbnRyb25TcGxpY2luZy5wcm9wKCdkaXNhYmxlZCcsIGZhbHNlKTtcbiAgICB9XG5cbiAgICBpZiAoJzEnID09PSAkaW50cm9uU3BsaWNpbmcudmFsKCkpIHtcbiAgICAgICAgJHVwc3RyZWFtLmNsb3Nlc3QoJ2Rpdi5mb3JtLWdyb3VwJykuaGlkZSgpO1xuICAgICAgICAkZG93bnN0cmVhbS5jbG9zZXN0KCdkaXYuZm9ybS1ncm91cCcpLmhpZGUoKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICAkdXBzdHJlYW0uY2xvc2VzdCgnZGl2LmZvcm0tZ3JvdXAnKS5zaG93KCk7XG4gICAgICAgICRkb3duc3RyZWFtLmNsb3Nlc3QoJ2Rpdi5mb3JtLWdyb3VwJykuc2hvdygpO1xuICAgIH1cblxuICAgICR0eXBlLmNoYW5nZShmdW5jdGlvbigpIHtcbiAgICAgICAgc2hvd0hpZGVDYXJ0U2V0dXAoKTtcbiAgICB9KTtcblxuICAgICRmZWF0dXJlLmNoYW5nZShmdW5jdGlvbigpIHtcbiAgICAgICAgc2hvd0hpZGVDYXJ0U2V0dXAoKTtcbiAgICB9KTtcblxuICAgICRpbnRyb25TcGxpY2luZy5jaGFuZ2UoZnVuY3Rpb24oKSB7XG4gICAgICAgIHNob3dIaWRlQ2FydFNldHVwKCk7XG4gICAgfSk7XG59XG5cbnNob3dIaWRlQ2FydFNldHVwKCk7XG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NhcnQtZm9ybS5qcyIsImZ1bmN0aW9uIGNvbGxlY3Rpb25UeXBlKGNvbnRhaW5lciwgYnV0dG9uVGV4dCwgYnV0dG9uSWQsIGZpZWxkU3RhcnQsIGZ1bmN0aW9ucykge1xuICAgIGlmIChidXR0b25JZCA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGJ1dHRvbklkID0gbnVsbDtcbiAgICB9XG5cbiAgICBpZiAoZmllbGRTdGFydCA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGZpZWxkU3RhcnQgPSBmYWxzZTtcbiAgICB9XG5cbiAgICBpZiAoZnVuY3Rpb25zID09PSB1bmRlZmluZWQpIHtcbiAgICAgICAgZnVuY3Rpb25zID0gW107XG4gICAgfVxuXG4gICAgLy8gRGVsZXRlIHRoZSBmaXJzdCBsYWJlbCAodGhlIG51bWJlciBvZiB0aGUgZmllbGQpLCBhbmQgdGhlIHJlcXVpcmVkIGNsYXNzXG4gICAgY29udGFpbmVyLmNoaWxkcmVuKCdkaXYnKS5maW5kKCdsYWJlbDpmaXJzdCcpLnRleHQoJycpO1xuICAgIGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykuZmluZCgnbGFiZWw6Zmlyc3QnKS5yZW1vdmVDbGFzcygncmVxdWlyZWQnKTtcbiAgICBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmZpbmQoJ2xhYmVsOmZpcnN0JykucmVtb3ZlQ2xhc3MoJ3JlcXVpcmVkJyk7XG5cbiAgICAvLyBDcmVhdGUgYW5kIGFkZCBhIGJ1dHRvbiB0byBhZGQgbmV3IGZpZWxkXG4gICAgaWYgKGJ1dHRvbklkKSB7XG4gICAgICAgIHZhciBpZCA9IFwiaWQ9J1wiICsgYnV0dG9uSWQgKyBcIidcIjtcbiAgICAgICAgdmFyICRhZGRCdXR0b24gPSAkKCc8YSBocmVmPVwiI1wiICcgKyBpZCArICdjbGFzcz1cImJ0biBidG4tZGVmYXVsdCBidG4teHNcIj48c3BhbiBjbGFzcz1cImZhIGZhLXBsdXMgYXJpYS1oaWRkZW49XCJ0cnVlXCJcIj48L3NwYW4+ICcrYnV0dG9uVGV4dCsnPC9hPicpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgIHZhciAkYWRkQnV0dG9uID0gJCgnPGEgaHJlZj1cIiNcIiBjbGFzcz1cImJ0biBidG4tZGVmYXVsdCBidG4teHNcIj48c3BhbiBjbGFzcz1cImZhIGZhLXBsdXMgYXJpYS1oaWRkZW49XCJ0cnVlXCJcIj48L3NwYW4+ICcrYnV0dG9uVGV4dCsnPC9hPicpO1xuICAgIH1cblxuICAgIGNvbnRhaW5lci5hcHBlbmQoJGFkZEJ1dHRvbik7XG5cbiAgICAvLyBBZGQgYSBjbGljayBldmVudCBvbiB0aGUgYWRkIGJ1dHRvblxuICAgICRhZGRCdXR0b24uY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIC8vIENhbGwgdGhlIGFkZEZpZWxkIG1ldGhvZFxuICAgICAgICBhZGRGaWVsZChjb250YWluZXIpO1xuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfSk7XG5cbiAgICAvLyBEZWZpbmUgYW4gaW5kZXggdG8gY291bnQgdGhlIG51bWJlciBvZiBhZGRlZCBmaWVsZCAodXNlZCB0byBnaXZlIG5hbWUgdG8gZmllbGRzKVxuICAgIHZhciBpbmRleCA9IGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykubGVuZ3RoO1xuXG4gICAgLy8gSWYgdGhlIGluZGV4IGlzID4gMCwgZmllbGRzIGFscmVhZHkgZXhpc3RzLCB0aGVuLCBhZGQgYSBkZWxldGVCdXR0b24gdG8gdGhpcyBmaWVsZHNcbiAgICBpZiAoaW5kZXggPiAwKSB7XG4gICAgICAgIGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykuZWFjaChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIGFkZERlbGV0ZUJ1dHRvbigkKHRoaXMpKTtcbiAgICAgICAgICAgIGFkZEZ1bmN0aW9ucygkKHRoaXMpKTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgLy8gSWYgd2Ugd2FudCB0byBoYXZlIGEgZmllbGQgYXQgc3RhcnRcbiAgICBpZiAodHJ1ZSA9PSBmaWVsZFN0YXJ0ICYmIDAgPT0gaW5kZXgpIHtcbiAgICAgICAgYWRkRmllbGQoY29udGFpbmVyKTtcbiAgICB9XG5cbiAgICAvLyBUaGUgYWRkRmllbGQgZnVuY3Rpb25cbiAgICBmdW5jdGlvbiBhZGRGaWVsZChjb250YWluZXIpIHtcbiAgICAgICAgLy8gUmVwbGFjZSBzb21lIHZhbHVlIGluIHRoZSDCqyBkYXRhLXByb3RvdHlwZSDCu1xuICAgICAgICAvLyAtIFwiX19uYW1lX19sYWJlbF9fXCIgYnkgdGhlIG5hbWUgd2Ugd2FudCB0byB1c2UsIGhlcmUgbm90aGluZ1xuICAgICAgICAvLyAtIFwiX19uYW1lX19cIiBieSB0aGUgbmFtZSBvZiB0aGUgZmllbGQsIGhlcmUgdGhlIGluZGV4IG51bWJlclxuICAgICAgICB2YXIgJHByb3RvdHlwZSA9ICQoY29udGFpbmVyLmF0dHIoJ2RhdGEtcHJvdG90eXBlJylcbiAgICAgICAgICAgIC5yZXBsYWNlKC9jbGFzcz1cImNvbC1zbS0yIGNvbnRyb2wtbGFiZWwgcmVxdWlyZWRcIi8sICdjbGFzcz1cImNvbC1zbS0yIGNvbnRyb2wtbGFiZWxcIicpXG4gICAgICAgICAgICAucmVwbGFjZSgvX19uYW1lX19sYWJlbF9fL2csICcnKVxuICAgICAgICAgICAgLnJlcGxhY2UoL19fbmFtZV9fL2csIGluZGV4KSk7XG5cbiAgICAgICAgLy8gQWRkIGEgZGVsZXRlIGJ1dHRvbiB0byB0aGUgbmV3IGZpZWxkXG4gICAgICAgIGFkZERlbGV0ZUJ1dHRvbigkcHJvdG90eXBlKTtcblxuICAgICAgICAvLyBJZiB0aGVyZSBhcmUgc3VwcGxlbWVudGFyeSBmdW5jdGlvbnNcbiAgICAgICAgYWRkRnVuY3Rpb25zKCRwcm90b3R5cGUpO1xuXG4gICAgICAgIC8vIEFkZCB0aGUgZmllbGQgaW4gdGhlIGZvcm1cbiAgICAgICAgJGFkZEJ1dHRvbi5iZWZvcmUoJHByb3RvdHlwZSk7XG5cbiAgICAgICAgLy8gSW5jcmVtZW50IHRoZSBjb3VudGVyXG4gICAgICAgIGluZGV4Kys7XG4gICAgfVxuXG4gICAgLy8gQSBmdW5jdGlvbiBjYWxsZWQgdG8gYWRkIGRlbGV0ZUJ1dHRvblxuICAgIGZ1bmN0aW9uIGFkZERlbGV0ZUJ1dHRvbihwcm90b3R5cGUpIHtcbiAgICAgICAgLy8gRmlyc3QsIGNyZWF0ZSB0aGUgYnV0dG9uXG4gICAgICAgIHZhciAkZGVsZXRlQnV0dG9uID0gJCgnPGRpdiBjbGFzcz1cImNvbC1zbS0xXCI+PGEgaHJlZj1cIiNcIiBjbGFzcz1cImJ0biBidG4tZGFuZ2VyIGJ0bi1zbVwiPjxzcGFuIGNsYXNzPVwiZmEgZmEtdHJhc2hcIiBhcmlhLWhpZGRlbj1cInRydWVcIj48L3NwYW4+PC9hPjwvZGl2PicpO1xuXG4gICAgICAgIC8vIEFkZCB0aGUgYnV0dG9uIG9uIHRoZSBmaWVsZFxuICAgICAgICAkKCcuY29sLXNtLTEwJywgcHJvdG90eXBlKS5yZW1vdmVDbGFzcygnY29sLXNtLTEwJykuYWRkQ2xhc3MoJ2NvbC1zbS05Jyk7XG4gICAgICAgIHByb3RvdHlwZS5hcHBlbmQoJGRlbGV0ZUJ1dHRvbik7XG5cbiAgICAgICAgLy8gQ3JlYXRlIGEgbGlzdGVuZXIgb24gdGhlIGNsaWNrIGV2ZW50XG4gICAgICAgICRkZWxldGVCdXR0b24uY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgLy8gUmVtb3ZlIHRoZSBmaWVsZFxuICAgICAgICAgICAgcHJvdG90eXBlLnJlbW92ZSgpO1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBhZGRGdW5jdGlvbnMocHJvdG90eXBlKSB7XG4gICAgICAgIC8vIElmIHRoZXJlIGFyZSBzdXBwbGVtZW50YXJ5IGZ1bmN0aW9uc1xuICAgICAgICBpZiAoZnVuY3Rpb25zLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIC8vIERvIGEgd2hpbGUgb24gZnVuY3Rpb25zLCBhbmQgYXBwbHkgdGhlbSB0byB0aGUgcHJvdG90eXBlXG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgZnVuY3Rpb25zLmxlbmd0aCA+IGk7IGkrKykge1xuICAgICAgICAgICAgICAgIGZ1bmN0aW9uc1tpXShwcm90b3R5cGUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxufVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NvbGxlY3Rpb24tdHlwZS5qcyIsImZ1bmN0aW9uIGNvcHkyY2xpcGJvYXJkKGRhdGFTZWxlY3Rvcikge1xuICAgIGRhdGFTZWxlY3Rvci5zZWxlY3QoKTtcbiAgICBkb2N1bWVudC5leGVjQ29tbWFuZCgnY29weScpO1xufVxuXG5mdW5jdGlvbiBjb3B5MmNsaXBib2FyZE9uQ2xpY2soY2xpY2tUcmlnZ2VyLCBkYXRhU2VsZWN0b3IpIHtcbiAgICBjbGlja1RyaWdnZXIuY2xpY2soZnVuY3Rpb24oKXtcbiAgICAgICAgY29weTJjbGlwYm9hcmQoZGF0YVNlbGVjdG9yKTtcbiAgICB9KTtcbn1cblxuJChmdW5jdGlvbigpIHtcbiAgIGNvcHkyY2xpcGJvYXJkT25DbGljaygkKFwiI3JldmVyc2UtY29tcGxlbWVudC1jb3B5LWJ1dHRvblwiKSwgJChcIiNyZXZlcnNlLWNvbXBsZW1lbnQtcmVzdWx0XCIpKTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NvcHkyY2xpcGJvYXJkLmpzIiwiLy8gdmFyIGRlbGF5ID0gKGZ1bmN0aW9uKCl7XG4vLyAgICAgdmFyIHRpbWVyID0gMDtcbi8vICAgICByZXR1cm4gZnVuY3Rpb24oY2FsbGJhY2ssIG1zKXtcbi8vICAgICAgICAgY2xlYXJUaW1lb3V0ICh0aW1lcik7XG4vLyAgICAgICAgIHRpbWVyID0gc2V0VGltZW91dChjYWxsYmFjaywgbXMpO1xuLy8gICAgIH07XG4vLyB9KSgpO1xuXG5tb2R1bGUuZXhwb3J0cyA9IChmdW5jdGlvbigpIHtcbiAgICByZXR1cm4gKGZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciB0aW1lciA9IDA7XG4gICAgICAgIHJldHVybiBmdW5jdGlvbihjYWxsYmFjaywgbXMpe1xuICAgICAgICAgICAgY2xlYXJUaW1lb3V0ICh0aW1lcik7XG4gICAgICAgICAgICB0aW1lciA9IHNldFRpbWVvdXQoY2FsbGJhY2ssIG1zKTtcbiAgICAgICAgfTtcbiAgICB9KSgpO1xufSkoKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9kZWxheS5qcyIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCl7XG4gICAgJCgnZGl2LmxvY3VzLWZlYXR1cmUnKS5lYWNoKGZ1bmN0aW9uKGluZGV4KSB7XG4gICAgICAgIHZhciBsb2N1cyA9ICQoIHRoaXMgKS5kYXRhKFwibG9jdXNcIik7XG4gICAgICAgIHZhciBmZWF0dXJlID0gJCggdGhpcyApLmRhdGEoXCJmZWF0dXJlXCIpO1xuICAgICAgICB2YXIgc2VxdWVuY2VDb250YWluZXIgPSAkKCB0aGlzICkuZmluZCgnZGl2LmZhc3RhJyk7XG4gICAgICAgIHZhciBmb3JtID0gJCggdGhpcyApLmZpbmQoJ2Zvcm0nKTtcblxuICAgICAgICBmb3JtLnJlbW92ZUNsYXNzKCdoaWRkZW4nKTtcblxuICAgICAgICBmb3JtLnN1Ym1pdChmdW5jdGlvbihldmVudCkge1xuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIHZhciB1cHN0cmVhbSA9ICQoIHRoaXMgKS5wYXJlbnQoKS5maW5kKFwiaW5wdXRbbmFtZT0ndXBzdHJlYW0nXVwiKS52YWwoKTtcbiAgICAgICAgICAgIHZhciBkb3duc3RyZWFtID0gJCggdGhpcyApLnBhcmVudCgpLmZpbmQoXCJpbnB1dFtuYW1lPSdkb3duc3RyZWFtJ11cIikudmFsKCk7XG4gICAgICAgICAgICB2YXIgc2hvd1V0ciA9ICQoIHRoaXMgKS5wYXJlbnQoKS5maW5kKFwiaW5wdXRbbmFtZT0nc2hvd1V0ciddXCIpLmlzKFwiOmNoZWNrZWRcIik7XG4gICAgICAgICAgICB2YXIgc2hvd0ludHJvbiA9ICQoIHRoaXMgKS5wYXJlbnQoKS5maW5kKFwiaW5wdXRbbmFtZT0nc2hvd0ludHJvbiddXCIpLmlzKFwiOmNoZWNrZWRcIik7XG5cbiAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdHlwZTogJ0dFVCcsXG4gICAgICAgICAgICAgICAgdXJsOiBSb3V0aW5nLmdlbmVyYXRlKCdmZWF0dXJlX3NlcXVlbmNlJywgeyBsb2N1c19uYW1lOiBsb2N1cywgZmVhdHVyZV9uYW1lOiBmZWF0dXJlLCB1cHN0cmVhbTogdXBzdHJlYW0sIGRvd25zdHJlYW06IGRvd25zdHJlYW0sIHNob3dVdHI6IHNob3dVdHIsIHNob3dJbnRyb246IHNob3dJbnRyb24gfSksXG4gICAgICAgICAgICAgICAgZGF0YVR5cGU6ICdodG1sJyxcbiAgICAgICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICAgICAgICAgICAgICBzZXF1ZW5jZUNvbnRhaW5lci5maXJzdCgpLmh0bWwoaHRtbCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbGl2ZS1zZXF1ZW5jZS1kaXNwbGF5LmpzIiwiJChmdW5jdGlvbiAoKSB7XG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwidG9vbHRpcFwiXScpLnRvb2x0aXAoKVxufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbG9jdXMtdG9vbHRpcC5qcyIsIiQoXCJpbnB1dFt0eXBlPXBhc3N3b3JkXVtpZCo9J19wbGFpblBhc3N3b3JkXyddXCIpLmtleXVwKGZ1bmN0aW9uKCl7XG4gICAgLy8gU2V0IHJlZ2V4IGNvbnRyb2xcbiAgICB2YXIgdWNhc2UgPSBuZXcgUmVnRXhwKFwiW0EtWl0rXCIpO1xuICAgIHZhciBsY2FzZSA9IG5ldyBSZWdFeHAoXCJbYS16XStcIik7XG4gICAgdmFyIG51bSA9IG5ldyBSZWdFeHAoXCJbMC05XStcIik7XG5cbiAgICAvLyBTZXQgcGFzc3dvcmQgZmllbGRzXG4gICAgdmFyIHBhc3N3b3JkMSA9ICQoXCJbaWQkPSdfcGxhaW5QYXNzd29yZF9maXJzdCddXCIpO1xuICAgIHZhciBwYXNzd29yZDIgPSAkKFwiW2lkJD0nX3BsYWluUGFzc3dvcmRfc2Vjb25kJ11cIik7XG4gICAgXG4gICAgLy8gU2V0IGRpc3BsYXkgcmVzdWx0XG4gICAgdmFyIG51bWJlckNoYXJzID0gJChcIiNudW1iZXItY2hhcnNcIik7XG4gICAgdmFyIHVwcGVyQ2FzZSA9ICQoXCIjdXBwZXItY2FzZVwiKTtcbiAgICB2YXIgbG93ZXJDYXNlID0gJChcIiNsb3dlci1jYXNlXCIpO1xuICAgIHZhciBudW1iZXIgPSAkKFwiI251bWJlclwiKTtcbiAgICB2YXIgcGFzc3dvcmRNYXRjaCA9ICQoXCIjcGFzc3dvcmQtbWF0Y2hcIik7XG5cbiAgICAvLyBEbyB0aGUgdGVzdFxuICAgIGlmKHBhc3N3b3JkMS52YWwoKS5sZW5ndGggPj0gOCl7XG4gICAgICAgIG51bWJlckNoYXJzLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIG51bWJlckNoYXJzLmFkZENsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIG51bWJlckNoYXJzLmNzcyhcImNvbG9yXCIsXCIjMDBBNDFFXCIpO1xuICAgIH1lbHNle1xuICAgICAgICBudW1iZXJDaGFycy5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBudW1iZXJDaGFycy5hZGRDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBudW1iZXJDaGFycy5jc3MoXCJjb2xvclwiLFwiI0ZGMDAwNFwiKTtcbiAgICB9XG5cbiAgICBpZih1Y2FzZS50ZXN0KHBhc3N3b3JkMS52YWwoKSkpe1xuICAgICAgICB1cHBlckNhc2UucmVtb3ZlQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgdXBwZXJDYXNlLmFkZENsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIHVwcGVyQ2FzZS5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgdXBwZXJDYXNlLnJlbW92ZUNsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIHVwcGVyQ2FzZS5hZGRDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICB1cHBlckNhc2UuY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxuXG4gICAgaWYobGNhc2UudGVzdChwYXNzd29yZDEudmFsKCkpKXtcbiAgICAgICAgbG93ZXJDYXNlLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIGxvd2VyQ2FzZS5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBsb3dlckNhc2UuY3NzKFwiY29sb3JcIixcIiMwMEE0MUVcIik7XG4gICAgfWVsc2V7XG4gICAgICAgIGxvd2VyQ2FzZS5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBsb3dlckNhc2UuYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbG93ZXJDYXNlLmNzcyhcImNvbG9yXCIsXCIjRkYwMDA0XCIpO1xuICAgIH1cblxuICAgIGlmKG51bS50ZXN0KHBhc3N3b3JkMS52YWwoKSkpe1xuICAgICAgICBudW1iZXIucmVtb3ZlQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbnVtYmVyLmFkZENsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIG51bWJlci5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgbnVtYmVyLnJlbW92ZUNsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIG51bWJlci5hZGRDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBudW1iZXIuY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxuXG4gICAgaWYocGFzc3dvcmQxLnZhbCgpID09PSBwYXNzd29yZDIudmFsKCkgJiYgcGFzc3dvcmQxLnZhbCgpICE9PSAnJyl7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2gucmVtb3ZlQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBwYXNzd29yZE1hdGNoLmNzcyhcImNvbG9yXCIsXCIjMDBBNDFFXCIpO1xuICAgIH1lbHNle1xuICAgICAgICBwYXNzd29yZE1hdGNoLnJlbW92ZUNsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2guYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5jc3MoXCJjb2xvclwiLFwiI0ZGMDAwNFwiKTtcbiAgICB9XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYXNzd29yZC1jb250cm9sLmpzIiwiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgdmFyIHJlc3VsdCA9ICQoJyNzZWFyY2gtcmVzdWx0cycpO1xuXG4gICAgaWYgKHJlc3VsdC5sZW5ndGggPiAwKSB7XG4gICAgICAgIHZhciBrZXl3b3JkID0gcmVzdWx0LmRhdGEoJ3NlYXJjaC1rZXl3b3JkJyk7XG4gICAgICAgIGtleXdvcmQgPSAnKCcgKyBrZXl3b3JkICsgJyknO1xuICAgICAgICB2YXIgcmVnZXggPSBuZXcgUmVnRXhwKGtleXdvcmQsXCJnaVwiKTtcbiAgICAgICAgdmFyIHJlc3VsdEh0bWwgPSByZXN1bHQuaHRtbCgpO1xuXG4gICAgICAgIHJlc3VsdEh0bWwgPSByZXN1bHRIdG1sLnJlcGxhY2UocmVnZXgsIFwiPGI+JDE8L2I+XCIpO1xuICAgICAgICByZXN1bHQuaHRtbChyZXN1bHRIdG1sKTtcbiAgICB9XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZWFyY2gta2V5d29yZC1oaWdobGlnaHQuanMiLCJmdW5jdGlvbiBzdHJhaW5zRmlsdGVyKHN0cmFpbnNGaWx0ZXJTZWxlY3QsIHN0cmFpbnNDaGVja0JveGVzQ29udGFpbmVyKSB7XG5cbiAgICAvLyBEZWZpbmUgdmFyIHRoYXQgY29udGFpbnMgZmllbGRzXG4gICAgdmFyIHN0cmFpbnNDaGVja2JveGVzID0gc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIuZmluZCggJ2xhYmVsJyApO1xuXG4gICAgLy8qKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi8vXG4gICAgLy8gIEFkZCB0aGUgbGlua3MgKGNoZWNrL3VuY2hlY2spIC8vXG4gICAgLy8qKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi8vXG5cbiAgICAvLyBEZWZpbmUgY2hlY2tBbGwvdW5jaGVja0FsbCBsaW5rc1xuICAgIHZhciBjaGVja0FsbExpbmsgPSAkKCc8YSBocmVmPVwiI1wiIGNsYXNzPVwiY2hlY2tfYWxsX3N0cmFpbnNcIiA+IENoZWNrIGFsbDwvYT4nKTtcbiAgICB2YXIgdW5jaGVja0FsbExpbmsgPSAkKCc8YSBocmVmPVwiI1wiIGNsYXNzPVwidW5jaGVja19hbGxfc3RyYWluc1wiID4gVW5jaGVjayBhbGw8L2E+Jyk7XG5cbiAgICAvLyBJbnNlcnQgdGhlIGNoZWNrL3VuY2hlY2sgbGlua3NcbiAgICBzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lci5iZWZvcmUodW5jaGVja0FsbExpbmspO1xuICAgIHN0cmFpbnNDaGVja0JveGVzQ29udGFpbmVyLmJlZm9yZSgnIC8gJyk7XG4gICAgc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIuYmVmb3JlKGNoZWNrQWxsTGluayk7XG5cbiAgICAvLyoqKioqKioqKioqKioqKioqKioqKioqKioqKi8vXG4gICAgLy8gQ3JlYXRlIGFsbCBvbkNMaWNrIGV2ZW50cyAvL1xuICAgIC8vKioqKioqKioqKioqKioqKioqKioqKioqKioqLy9cblxuICAgIC8vIENyZWF0ZSBvbkNsaWNrIGV2ZW50IG9uIFRlYW0gZmlsdGVyXG4gICAgc3RyYWluc0ZpbHRlclNlbGVjdC5jaGFuZ2UoZnVuY3Rpb24gKCkge1xuICAgICAgICAvLyBHZXQgdGhlIGNsYWRlXG4gICAgICAgIHZhciBjbGFkZSA9ICQodGhpcykudmFsKCk7XG5cbiAgICAgICAgLy8gQ2FsbCB0aGUgZnVuY3Rpb24gYW5kIGdpdmUgdGhlIGNsYWRlXG4gICAgICAgIHNob3dIaWRlU3RyYWlucyhjbGFkZSk7XG4gICAgfSk7XG5cbiAgICBmdW5jdGlvbiBzaG93SGlkZVN0cmFpbnMoY2xhZGUpIHtcbiAgICAgICAgaWYgKCcnID09PSBjbGFkZSkge1xuICAgICAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuc2hvdygpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgLy8gSGlkZSBhbGwgU3RyYWluc1xuICAgICAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuaGlkZSgpO1xuXG4gICAgICAgICAgICAvLyBTaG93IGNsYWRlIHN0cmFpbnNcbiAgICAgICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciBzdHJhaW5DbGFkZSA9ICQoIHRoaXMgKS5maW5kKCBcIjpjaGVja2JveFwiICkuZGF0YSgnY2xhZGUnKTtcblxuICAgICAgICAgICAgICAgIGlmIChzdHJhaW5DbGFkZSA9PT0gY2xhZGUpIHtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5zaG93KCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICAvLyBDcmVhdGUgb25DbGljayBldmVudCBvbiBjaGVja0FsbExpbmtcbiAgICBjaGVja0FsbExpbmsuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgY2xhZGVGaWx0ZXJlZCA9IHN0cmFpbnNGaWx0ZXJTZWxlY3QudmFsKCk7XG5cbiAgICAgICAgaWYgKCcnID09PSBjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgICAgICBjaGVja0FsbCgpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgY2hlY2tBbGxDbGFkZShjbGFkZUZpbHRlcmVkKTtcbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgLy8gQ3JlYXRlIG9uQ2xpY2sgZXZlbnQgb24gdW5jaGVja0FsbExpbmtcbiAgICB1bmNoZWNrQWxsTGluay5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHZhciBjbGFkZUZpbHRlcmVkID0gc3RyYWluc0ZpbHRlclNlbGVjdC52YWwoKTtcblxuICAgICAgICBpZiAoJycgPT09IGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgICAgIHVuY2hlY2tBbGwoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHVuY2hlY2tBbGxDbGFkZShjbGFkZUZpbHRlcmVkKTtcbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgLy9cbiAgICAvLyBCYXNlIGZ1bmN0aW9uczogY2hlY2svdW5jaGVjayBhbGwgY2hlY2tib3hlcyBhbmQgY2hlY2svdW5jaGVjayBzcGVjaWZpYyBzdHJhaW5zIChwZXIgY2xhZGUpXG4gICAgLy9cblxuICAgIGZ1bmN0aW9uIGNoZWNrQWxsQ2xhZGUoY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBzdHJhaW5DbGFkZSA9ICQodGhpcykuZmluZCggXCJpbnB1dDpjaGVja2JveFwiICkuZGF0YSgnY2xhZGUnKTtcblxuICAgICAgICAgICAgaWYgKHN0cmFpbkNsYWRlID09PSBjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5maW5kKFwiaW5wdXQ6Y2hlY2tib3hcIikucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiB1bmNoZWNrQWxsQ2xhZGUoY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBzdHJhaW5DbGFkZSA9ICQodGhpcykuZmluZCggXCJpbnB1dDpjaGVja2JveFwiICkuZGF0YSgnY2xhZGUnKTtcblxuICAgICAgICAgICAgaWYgKHN0cmFpbkNsYWRlID09PSBjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5maW5kKFwiaW5wdXQ6Y2hlY2tib3hcIikucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gY2hlY2tBbGwoKSB7XG4gICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJCh0aGlzKS5maW5kKFwiaW5wdXQ6Y2hlY2tib3hcIikucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiB1bmNoZWNrQWxsKCkge1xuICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICQodGhpcykuZmluZChcImlucHV0OmNoZWNrYm94XCIpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG4gICAgICAgIH0pO1xuICAgIH1cbn1cblxuJChmdW5jdGlvbigpIHtcbiAgICBzdHJhaW5zRmlsdGVyKCQoIFwiI2JsYXN0X3N0cmFpbnNGaWx0ZXJfZmlsdGVyXCIgKSwgJCggXCIjYmxhc3Rfc3RyYWluc0ZpbHRlcl9zdHJhaW5zXCIgKSk7XG4gICAgc3RyYWluc0ZpbHRlcigkKCBcIiNhZHZhbmNlZF9zZWFyY2hfc3RyYWluc0ZpbHRlcl9maWx0ZXJcIiApLCAkKCBcIiNhZHZhbmNlZF9zZWFyY2hfc3RyYWluc0ZpbHRlcl9zdHJhaW5zXCIgKSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zdHJhaW5zLWZpbHRlci5qcyIsIi8vIENoZWNrIGFsbCBjaGVja2JveGVzIG5vIGRpc2FibGVkXG5mdW5jdGlvbiBjaGVja0FsbChncm91cE5hbWUpIHtcbiAgICAkKFwiOmNoZWNrYm94W2RhdGEtbmFtZT1cIiArIGdyb3VwTmFtZSArIFwiXTpub3QoOmRpc2FibGVkKVwiKS5wcm9wKCdjaGVja2VkJywgdHJ1ZSk7XG59XG5cbi8vIFVuY2hlY2sgYWxsIGNoZWNrYm94ZXMgZGlzYWJsZWQgdG9vXG5mdW5jdGlvbiB1bmNoZWNrQWxsKGdyb3VwTmFtZSkge1xuICAgICQoXCJpbnB1dDpjaGVja2JveFtkYXRhLW5hbWU9XCIgKyBncm91cE5hbWUgKyBcIl1cIikucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcbn1cblxuLy8gVW5jaGVjayBhbGwgZGlzYWJsZWQgY2hlY2tib3hcbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgICQoXCI6Y2hlY2tib3g6ZGlzYWJsZWRcIikucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcblxuICAgIC8vIE9uIGNoZWNrQWxsIGNsaWNrXG4gICAgJCgnLmNoZWNrQWxsU3RyYWlucycpLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgc3BlY2llcyA9ICQoIHRoaXMgKS5kYXRhKCdzcGVjaWVzJyk7XG4gICAgICAgIGNoZWNrQWxsKHNwZWNpZXMpO1xuICAgIH0pO1xuXG4gICAgLy8gT24gdW5jaGVja0FsbENsaWNrXG4gICAgJCgnLnVuY2hlY2tBbGxTdHJhaW5zJykuY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHZhciBzcGVjaWVzID0gJCggdGhpcyApLmRhdGEoJ3NwZWNpZXMnKTtcbiAgICAgICAgdW5jaGVja0FsbChzcGVjaWVzKTtcbiAgICB9KTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItYWRtaW4tc3RyYWlucy5qcyIsInZhciBkZWxheSA9IHJlcXVpcmUoJy4vZGVsYXknKTtcblxuJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKXtcbiAgICB2YXIgcHJvY2Vzc2luZyA9IGZhbHNlO1xuICAgIHZhciBzZWFyY2ggPSAkKCcjdXNlci1zZWFyY2gtZmllbGQnKTtcbiAgICB2YXIgdGVhbSA9ICQoJyN1c2VyLXRlYW0tZmllbGQnKTtcblxuICAgIHNlYXJjaC5rZXl1cChmdW5jdGlvbigpIHtcbiAgICAgICAgaGlzdG9yeS5yZXBsYWNlU3RhdGUoJycsICcnLCBSb3V0aW5nLmdlbmVyYXRlKCd1c2VyX2luZGV4JywgeyBxOiBzZWFyY2gudmFsKCksIHA6IDEgfSkpO1xuXG4gICAgICAgIGRlbGF5KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgICAgIHR5cGU6ICdHRVQnLFxuICAgICAgICAgICAgICAgIHVybDogUm91dGluZy5nZW5lcmF0ZSgndXNlcl9pbmRleF9hamF4JywgeyBxOiBzZWFyY2gudmFsKCksIHA6IDEgfSksXG4gICAgICAgICAgICAgICAgZGF0YVR5cGU6ICdodG1sJyxcbiAgICAgICAgICAgICAgICBkZWxheTogNDAwLFxuICAgICAgICAgICAgICAgIGJlZm9yZVNlbmQ6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICBpZiAocHJvY2Vzc2luZykge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgcHJvY2Vzc2luZyA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChodG1sKSB7XG4gICAgICAgICAgICAgICAgICAgICQoJyN1c2VyLWxpc3QnKS5yZXBsYWNlV2l0aChodG1sKTtcbiAgICAgICAgICAgICAgICAgICAgcHJvY2Vzc2luZyA9IGZhbHNlO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9LCA0MDAgKTtcbiAgICB9KTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3VzZXItaW5zdGFudC1zZWFyY2guanMiXSwic291cmNlUm9vdCI6IiJ9