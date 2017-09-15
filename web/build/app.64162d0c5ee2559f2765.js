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

    // When genus gets selected ...
    $tool.change(function () {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected genus value.
        var data = {};
        data[$tool.attr('name')] = $tool.val();

        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            success: function success(html) {
                // Replace current position field ...
                $('select#blast_database').replaceWith(
                // ... with the returned one from the AJAX response.
                $(html).find('select#blast_database'));
                $('select#blast_matrix').replaceWith(
                // ... with the returned one from the AJAX response.
                $(html).find('select#blast_matrix'));
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
    var strainsCheckboxes = strainsCheckBoxesContainer.find('.form-check');

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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYXV0by1kaXNtaXNzLWFsZXJ0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9ibGFzdC1zY3JvbGxzcHkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2JsYXN0LXNlbGVjdC1jaGFuZ2UuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NhcnQtYnRuLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZhc3RhLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZvcm0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxlY3Rpb24tdHlwZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvY29weTJjbGlwYm9hcmQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2RlbGF5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9saXZlLXNlcXVlbmNlLWRpc3BsYXkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2xvY3VzLXRvb2x0aXAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Bhc3N3b3JkLWNvbnRyb2wuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlYXJjaC1rZXl3b3JkLWhpZ2hsaWdodC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc3RyYWlucy1maWx0ZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWRtaW4tc3RyYWlucy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1pbnN0YW50LXNlYXJjaC5qcyJdLCJuYW1lcyI6WyIkIiwiZG9jdW1lbnQiLCJyZWFkeSIsImhpZGUiLCJmYWRlSW4iLCJkZWxheSIsImZhZGVPdXQiLCJ3aW5kb3ciLCJvbiIsIiRhbGxMaSIsInJlbW92ZUNsYXNzIiwiJGFjdGl2ZUxpIiwicHJldiIsImFkZENsYXNzIiwibmV4dCIsImVxIiwiJHRvb2wiLCJjaGFuZ2UiLCIkZm9ybSIsImNsb3Nlc3QiLCJkYXRhIiwiYXR0ciIsInZhbCIsImFqYXgiLCJ1cmwiLCJ0eXBlIiwic3VjY2VzcyIsImh0bWwiLCJyZXBsYWNlV2l0aCIsImZpbmQiLCIkY2FydEJhZGdlIiwiY2xpY2siLCJlIiwicHJldmVudERlZmF1bHQiLCIkdXJsIiwiZ2V0IiwiJG5iSXRlbXMiLCJpdGVtcyIsImxlbmd0aCIsInRleHQiLCJyZWFjaGVkX2xpbWl0IiwibG9jYXRpb24iLCJyZWxvYWQiLCIkdGFibGVSb3ciLCJyZW1vdmUiLCJnZW5lcmF0ZUNhcnRGYXN0YSIsInRleHRhcmVhSWQiLCJtb2RhbElkIiwiJG1vZGFsIiwiJHZhbHVlcyIsImVhY2giLCJlbGVtZW50cyIsImkiLCJmaWVsZCIsIm5hbWUiLCJ2YWx1ZSIsImRhdGFUeXBlIiwibW9kYWwiLCJzaG93SGlkZUNhcnRTZXR1cCIsIiR0eXBlIiwiJGZlYXR1cmUiLCIkaW50cm9uU3BsaWNpbmciLCIkdXBzdHJlYW0iLCIkZG93bnN0cmVhbSIsIiRzZXR1cCIsInNob3ciLCJwcm9wIiwiY29sbGVjdGlvblR5cGUiLCJjb250YWluZXIiLCJidXR0b25UZXh0IiwiYnV0dG9uSWQiLCJmaWVsZFN0YXJ0IiwiZnVuY3Rpb25zIiwidW5kZWZpbmVkIiwiY2hpbGRyZW4iLCJpZCIsIiRhZGRCdXR0b24iLCJhcHBlbmQiLCJhZGRGaWVsZCIsImluZGV4IiwiYWRkRGVsZXRlQnV0dG9uIiwiYWRkRnVuY3Rpb25zIiwiJHByb3RvdHlwZSIsInJlcGxhY2UiLCJiZWZvcmUiLCJwcm90b3R5cGUiLCIkZGVsZXRlQnV0dG9uIiwiY29weTJjbGlwYm9hcmQiLCJkYXRhU2VsZWN0b3IiLCJzZWxlY3QiLCJleGVjQ29tbWFuZCIsImNvcHkyY2xpcGJvYXJkT25DbGljayIsImNsaWNrVHJpZ2dlciIsIm1vZHVsZSIsImV4cG9ydHMiLCJ0aW1lciIsImNhbGxiYWNrIiwibXMiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwibG9jdXMiLCJmZWF0dXJlIiwic2VxdWVuY2VDb250YWluZXIiLCJmb3JtIiwic3VibWl0IiwiZXZlbnQiLCJ1cHN0cmVhbSIsInBhcmVudCIsImRvd25zdHJlYW0iLCJzaG93VXRyIiwiaXMiLCJzaG93SW50cm9uIiwiUm91dGluZyIsImdlbmVyYXRlIiwibG9jdXNfbmFtZSIsImZlYXR1cmVfbmFtZSIsImZpcnN0IiwidG9vbHRpcCIsImtleXVwIiwidWNhc2UiLCJSZWdFeHAiLCJsY2FzZSIsIm51bSIsInBhc3N3b3JkMSIsInBhc3N3b3JkMiIsIm51bWJlckNoYXJzIiwidXBwZXJDYXNlIiwibG93ZXJDYXNlIiwibnVtYmVyIiwicGFzc3dvcmRNYXRjaCIsImNzcyIsInRlc3QiLCJyZXN1bHQiLCJrZXl3b3JkIiwicmVnZXgiLCJyZXN1bHRIdG1sIiwic3RyYWluc0ZpbHRlciIsInN0cmFpbnNGaWx0ZXJTZWxlY3QiLCJzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lciIsInN0cmFpbnNDaGVja2JveGVzIiwiY2hlY2tBbGxMaW5rIiwidW5jaGVja0FsbExpbmsiLCJwcmVwZW5kIiwiY2xhZGUiLCJzaG93SGlkZVN0cmFpbnMiLCJzdHJhaW5DbGFkZSIsImNsYWRlRmlsdGVyZWQiLCJjaGVja0FsbCIsImNoZWNrQWxsQ2xhZGUiLCJ1bmNoZWNrQWxsIiwidW5jaGVja0FsbENsYWRlIiwiZ3JvdXBOYW1lIiwic3BlY2llcyIsInJlcXVpcmUiLCJwcm9jZXNzaW5nIiwic2VhcmNoIiwidGVhbSIsImhpc3RvcnkiLCJyZXBsYWNlU3RhdGUiLCJxIiwicCIsImJlZm9yZVNlbmQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7QUFBQSx5Q0FBQUEsRUFBRUMsUUFBRixFQUFZQyxLQUFaLENBQWtCLFlBQVc7QUFDekJGLE1BQUUsOEJBQUYsRUFBa0NHLElBQWxDO0FBQ0FILE1BQUUsOEJBQUYsRUFBa0NJLE1BQWxDLENBQXlDLEtBQXpDO0FBQ0FKLE1BQUUsOEJBQUYsRUFBa0NLLEtBQWxDLENBQXdDLE1BQXhDLEVBQWdEQyxPQUFoRCxDQUF3RCxLQUF4RDtBQUNI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NDE2MmQwYzVlZTI1NTlmMjc2NS5qcyIsInNvdXJjZXNDb250ZW50IjpbIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgICQoJ1tkYXRhLXRvZ2dsZT1cImF1dG8tZGlzbWlzc1wiXScpLmhpZGUoKTtcbiAgICAkKCdbZGF0YS10b2dnbGU9XCJhdXRvLWRpc21pc3NcIl0nKS5mYWRlSW4oXCJsb3dcIik7XG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwiYXV0by1kaXNtaXNzXCJdJykuZGVsYXkoJzUwMDAnKS5mYWRlT3V0KFwibG93XCIpO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvYXV0by1kaXNtaXNzLWFsZXJ0LmpzIiwiJCh3aW5kb3cpLm9uKCdhY3RpdmF0ZS5icy5zY3JvbGxzcHknLCBmdW5jdGlvbiAoKSB7XG4gICAgLy8gUmVtb3ZlIGFsbCBkaXNwbGF5IGNsYXNzXG4gICAgdmFyICRhbGxMaSA9ICQoJ25hdiNibGFzdC1zY3JvbGxzcHkgbmF2IGEuYWN0aXZlICsgbmF2IGEnKTtcbiAgICAkYWxsTGkucmVtb3ZlQ2xhc3MoJ2Rpc3BsYXknKTtcblxuICAgIC8vIEFkZCBkaXNwbGF5IGNsYXNzIG9uIDIgYmVmb3JlIGFuZCAyIGFmdGVyXG4gICAgdmFyICRhY3RpdmVMaSA9ICQoJ25hdiNibGFzdC1zY3JvbGxzcHkgbmF2IGEuYWN0aXZlICsgbmF2IGEuYWN0aXZlJyk7XG4gICAgJGFjdGl2ZUxpLnByZXYoKS5hZGRDbGFzcygnZGlzcGxheScpO1xuICAgICRhY3RpdmVMaS5wcmV2KCkucHJldigpLmFkZENsYXNzKCdkaXNwbGF5Jyk7XG4gICAgJGFjdGl2ZUxpLm5leHQoKS5hZGRDbGFzcygnZGlzcGxheScpO1xuICAgICRhY3RpdmVMaS5uZXh0KCkubmV4dCgpLmFkZENsYXNzKCdkaXNwbGF5Jyk7XG5cbiAgICAvLyBBZGQgZGlzcGxheSBvbiB0aGUgZmlyc3QgYW5kIDJuZFxuICAgICRhbGxMaS5lcSgwKS5hZGRDbGFzcygnZGlzcGxheScpO1xuICAgICRhbGxMaS5lcSgxKS5hZGRDbGFzcygnZGlzcGxheScpO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvYmxhc3Qtc2Nyb2xsc3B5LmpzIiwiJCggZG9jdW1lbnQgKS5yZWFkeShmdW5jdGlvbiAoKSB7XG4gICAgdmFyICR0b29sID0gJCgnI2JsYXN0X3Rvb2wnKTtcblxuICAgIC8vIFdoZW4gZ2VudXMgZ2V0cyBzZWxlY3RlZCAuLi5cbiAgICAkdG9vbC5jaGFuZ2UoZnVuY3Rpb24gKCkge1xuICAgICAgICAvLyAuLi4gcmV0cmlldmUgdGhlIGNvcnJlc3BvbmRpbmcgZm9ybS5cbiAgICAgICAgdmFyICRmb3JtID0gJCh0aGlzKS5jbG9zZXN0KCdmb3JtJyk7XG4gICAgICAgIC8vIFNpbXVsYXRlIGZvcm0gZGF0YSwgYnV0IG9ubHkgaW5jbHVkZSB0aGUgc2VsZWN0ZWQgZ2VudXMgdmFsdWUuXG4gICAgICAgIHZhciBkYXRhID0ge307XG4gICAgICAgIGRhdGFbJHRvb2wuYXR0cignbmFtZScpXSA9ICR0b29sLnZhbCgpO1xuXG4gICAgICAgIC8vIFN1Ym1pdCBkYXRhIHZpYSBBSkFYIHRvIHRoZSBmb3JtJ3MgYWN0aW9uIHBhdGguXG4gICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICB1cmw6ICRmb3JtLmF0dHIoJ2FjdGlvbicpLFxuICAgICAgICAgICAgdHlwZTogJGZvcm0uYXR0cignbWV0aG9kJyksXG4gICAgICAgICAgICBkYXRhOiBkYXRhLFxuICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGh0bWwpIHtcbiAgICAgICAgICAgICAgICAvLyBSZXBsYWNlIGN1cnJlbnQgcG9zaXRpb24gZmllbGQgLi4uXG4gICAgICAgICAgICAgICAgJCgnc2VsZWN0I2JsYXN0X2RhdGFiYXNlJykucmVwbGFjZVdpdGgoXG4gICAgICAgICAgICAgICAgICAgIC8vIC4uLiB3aXRoIHRoZSByZXR1cm5lZCBvbmUgZnJvbSB0aGUgQUpBWCByZXNwb25zZS5cbiAgICAgICAgICAgICAgICAgICAgJChodG1sKS5maW5kKCdzZWxlY3QjYmxhc3RfZGF0YWJhc2UnKVxuICAgICAgICAgICAgICAgICk7XG4gICAgICAgICAgICAgICAgJCgnc2VsZWN0I2JsYXN0X21hdHJpeCcpLnJlcGxhY2VXaXRoKFxuICAgICAgICAgICAgICAgICAgICAvLyAuLi4gd2l0aCB0aGUgcmV0dXJuZWQgb25lIGZyb20gdGhlIEFKQVggcmVzcG9uc2UuXG4gICAgICAgICAgICAgICAgICAgICQoaHRtbCkuZmluZCgnc2VsZWN0I2JsYXN0X21hdHJpeCcpXG4gICAgICAgICAgICAgICAgKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9ibGFzdC1zZWxlY3QtY2hhbmdlLmpzIiwiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgdmFyICRjYXJ0QmFkZ2UgPSAkKCdhI2NhcnQgc3Bhbi5iYWRnZScpO1xuXG4gICAgJCgnYS5jYXJ0LWFkZC1idG4nKS5jbGljayhmdW5jdGlvbihlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgdmFyICR1cmwgPSAkKHRoaXMpLmF0dHIoJ2hyZWYnKTtcblxuICAgICAgICAkLmdldCggJHVybCwgZnVuY3Rpb24oIGRhdGEgKSB7XG4gICAgICAgICAgICAvLyBDb3VudCBvYmplY3RzIGluIGRhdGFcbiAgICAgICAgICAgIHZhciAkbmJJdGVtcyA9IGRhdGEuaXRlbXMubGVuZ3RoO1xuICAgICAgICAgICAgJGNhcnRCYWRnZS50ZXh0KCRuYkl0ZW1zKTtcblxuICAgICAgICAgICAgLy8gaWYgcmVhY2hlZCBsaW1pdFxuICAgICAgICAgICAgaWYgKHRydWUgPT09IGRhdGEucmVhY2hlZF9saW1pdCkge1xuICAgICAgICAgICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9KTtcblxuICAgICQoJ2EuY2FydC1yZW1vdmUtYnRuJykuY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHZhciAkdXJsID0gJCh0aGlzKS5hdHRyKCdocmVmJyk7XG4gICAgICAgIHZhciAkdGFibGVSb3cgPSAkKHRoaXMpLmNsb3Nlc3QoJ3RyJyk7XG5cbiAgICAgICAgJC5nZXQoICR1cmwsIGZ1bmN0aW9uKCBkYXRhICkge1xuICAgICAgICAgICAgLy8gQ291bnQgb2JqZWN0cyBpbiBkYXRhXG4gICAgICAgICAgICB2YXIgJG5iSXRlbXMgPSBkYXRhLml0ZW1zLmxlbmd0aDtcbiAgICAgICAgICAgICRjYXJ0QmFkZ2UudGV4dCgkbmJJdGVtcyk7XG5cbiAgICAgICAgICAgIC8vIFJlbW92ZSB0aGUgbGluZSBpbiB0aGUgcGFnZVxuICAgICAgICAgICAgJHRhYmxlUm93LnJlbW92ZSgpO1xuICAgICAgICB9KTtcbiAgICB9KTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NhcnQtYnRuLmpzIiwiZnVuY3Rpb24gZ2VuZXJhdGVDYXJ0RmFzdGEodGV4dGFyZWFJZCwgbW9kYWxJZCkge1xuICAgIHZhciAkbW9kYWwgPSAkKG1vZGFsSWQpO1xuICAgIHZhciAkZm9ybSA9ICRtb2RhbC5maW5kKCdmb3JtJyk7XG5cbiAgICB2YXIgJHZhbHVlcyA9IHt9O1xuXG4gICAgJC5lYWNoKCAkZm9ybVswXS5lbGVtZW50cywgZnVuY3Rpb24oaSwgZmllbGQpIHtcbiAgICAgICAgJHZhbHVlc1tmaWVsZC5uYW1lXSA9IGZpZWxkLnZhbHVlO1xuICAgIH0pO1xuXG4gICAgJC5hamF4KHtcbiAgICAgICAgdHlwZTogICAgICAgJGZvcm0uYXR0cignbWV0aG9kJyksXG4gICAgICAgIHVybDogICAgICAgICRmb3JtLmF0dHIoJ2FjdGlvbicpLFxuICAgICAgICBkYXRhVHlwZTogICAndGV4dCcsXG4gICAgICAgIGRhdGE6ICAgICAgICR2YWx1ZXMsXG4gICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAkKG1vZGFsSWQpLm1vZGFsKCdoaWRlJyk7XG4gICAgICAgICAgICAkKHRleHRhcmVhSWQpLnZhbChkYXRhKTtcbiAgICAgICAgfVxuICAgIH0pO1xufVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NhcnQtZmFzdGEuanMiLCJmdW5jdGlvbiBzaG93SGlkZUNhcnRTZXR1cCgpIHtcbiAgICB2YXIgJHR5cGUgPSAkKCdzZWxlY3RbaWQkPVxcJ2NhcnRfdHlwZVxcJ10nKTtcbiAgICB2YXIgJGZlYXR1cmUgPSAkKCdzZWxlY3RbaWQkPVxcJ2NhcnRfZmVhdHVyZVxcJ10nKTtcbiAgICB2YXIgJGludHJvblNwbGljaW5nID0gJCgnc2VsZWN0W2lkJD1cXCdjYXJ0X2ludHJvblNwbGljaW5nXFwnXScpO1xuICAgIHZhciAkdXBzdHJlYW0gPSAkKCdpbnB1dFtpZCQ9XFwnY2FydF91cHN0cmVhbVxcJ10nKTtcbiAgICB2YXIgJGRvd25zdHJlYW0gPSAkKCdpbnB1dFtpZCQ9XFwnY2FydF9kb3duc3RyZWFtXFwnXScpO1xuICAgIHZhciAkc2V0dXAgPSAkZmVhdHVyZS5jbG9zZXN0KCcjY2FydC1zZXR1cCcpO1xuXG4gICAgaWYgKCdwcm90JyA9PT0gJHR5cGUudmFsKCkpIHtcbiAgICAgICAgJHNldHVwLmhpZGUoKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICAkc2V0dXAuc2hvdygpO1xuICAgIH1cblxuICAgIGlmICgnbG9jdXMnID09PSAkZmVhdHVyZS52YWwoKSkge1xuICAgICAgICAkaW50cm9uU3BsaWNpbmcudmFsKDApO1xuICAgICAgICAkaW50cm9uU3BsaWNpbmcucHJvcCgnZGlzYWJsZWQnLCB0cnVlKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICAkaW50cm9uU3BsaWNpbmcucHJvcCgnZGlzYWJsZWQnLCBmYWxzZSk7XG4gICAgfVxuXG4gICAgaWYgKCcxJyA9PT0gJGludHJvblNwbGljaW5nLnZhbCgpKSB7XG4gICAgICAgICR1cHN0cmVhbS5jbG9zZXN0KCdkaXYuZm9ybS1ncm91cCcpLmhpZGUoKTtcbiAgICAgICAgJGRvd25zdHJlYW0uY2xvc2VzdCgnZGl2LmZvcm0tZ3JvdXAnKS5oaWRlKCk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgJHVwc3RyZWFtLmNsb3Nlc3QoJ2Rpdi5mb3JtLWdyb3VwJykuc2hvdygpO1xuICAgICAgICAkZG93bnN0cmVhbS5jbG9zZXN0KCdkaXYuZm9ybS1ncm91cCcpLnNob3coKTtcbiAgICB9XG5cbiAgICAkdHlwZS5jaGFuZ2UoZnVuY3Rpb24oKSB7XG4gICAgICAgIHNob3dIaWRlQ2FydFNldHVwKCk7XG4gICAgfSk7XG5cbiAgICAkZmVhdHVyZS5jaGFuZ2UoZnVuY3Rpb24oKSB7XG4gICAgICAgIHNob3dIaWRlQ2FydFNldHVwKCk7XG4gICAgfSk7XG5cbiAgICAkaW50cm9uU3BsaWNpbmcuY2hhbmdlKGZ1bmN0aW9uKCkge1xuICAgICAgICBzaG93SGlkZUNhcnRTZXR1cCgpO1xuICAgIH0pO1xufVxuXG5zaG93SGlkZUNhcnRTZXR1cCgpO1xuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jYXJ0LWZvcm0uanMiLCJmdW5jdGlvbiBjb2xsZWN0aW9uVHlwZShjb250YWluZXIsIGJ1dHRvblRleHQsIGJ1dHRvbklkLCBmaWVsZFN0YXJ0LCBmdW5jdGlvbnMpIHtcbiAgICBpZiAoYnV0dG9uSWQgPT09IHVuZGVmaW5lZCkge1xuICAgICAgICBidXR0b25JZCA9IG51bGw7XG4gICAgfVxuXG4gICAgaWYgKGZpZWxkU3RhcnQgPT09IHVuZGVmaW5lZCkge1xuICAgICAgICBmaWVsZFN0YXJ0ID0gZmFsc2U7XG4gICAgfVxuXG4gICAgaWYgKGZ1bmN0aW9ucyA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGZ1bmN0aW9ucyA9IFtdO1xuICAgIH1cblxuICAgIC8vIERlbGV0ZSB0aGUgZmlyc3QgbGFiZWwgKHRoZSBudW1iZXIgb2YgdGhlIGZpZWxkKSwgYW5kIHRoZSByZXF1aXJlZCBjbGFzc1xuICAgIGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykuZmluZCgnbGFiZWw6Zmlyc3QnKS50ZXh0KCcnKTtcbiAgICBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmZpbmQoJ2xhYmVsOmZpcnN0JykucmVtb3ZlQ2xhc3MoJ3JlcXVpcmVkJyk7XG4gICAgY29udGFpbmVyLmNoaWxkcmVuKCdkaXYnKS5maW5kKCdsYWJlbDpmaXJzdCcpLnJlbW92ZUNsYXNzKCdyZXF1aXJlZCcpO1xuXG4gICAgLy8gQ3JlYXRlIGFuZCBhZGQgYSBidXR0b24gdG8gYWRkIG5ldyBmaWVsZFxuICAgIGlmIChidXR0b25JZCkge1xuICAgICAgICB2YXIgaWQgPSBcImlkPSdcIiArIGJ1dHRvbklkICsgXCInXCI7XG4gICAgICAgIHZhciAkYWRkQnV0dG9uID0gJCgnPGEgaHJlZj1cIiNcIiAnICsgaWQgKyAnY2xhc3M9XCJidG4gYnRuLWRlZmF1bHQgYnRuLXhzXCI+PHNwYW4gY2xhc3M9XCJmYSBmYS1wbHVzIGFyaWEtaGlkZGVuPVwidHJ1ZVwiXCI+PC9zcGFuPiAnK2J1dHRvblRleHQrJzwvYT4nKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICB2YXIgJGFkZEJ1dHRvbiA9ICQoJzxhIGhyZWY9XCIjXCIgY2xhc3M9XCJidG4gYnRuLWRlZmF1bHQgYnRuLXhzXCI+PHNwYW4gY2xhc3M9XCJmYSBmYS1wbHVzIGFyaWEtaGlkZGVuPVwidHJ1ZVwiXCI+PC9zcGFuPiAnK2J1dHRvblRleHQrJzwvYT4nKTtcbiAgICB9XG5cbiAgICBjb250YWluZXIuYXBwZW5kKCRhZGRCdXR0b24pO1xuXG4gICAgLy8gQWRkIGEgY2xpY2sgZXZlbnQgb24gdGhlIGFkZCBidXR0b25cbiAgICAkYWRkQnV0dG9uLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAvLyBDYWxsIHRoZSBhZGRGaWVsZCBtZXRob2RcbiAgICAgICAgYWRkRmllbGQoY29udGFpbmVyKTtcbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0pO1xuXG4gICAgLy8gRGVmaW5lIGFuIGluZGV4IHRvIGNvdW50IHRoZSBudW1iZXIgb2YgYWRkZWQgZmllbGQgKHVzZWQgdG8gZ2l2ZSBuYW1lIHRvIGZpZWxkcylcbiAgICB2YXIgaW5kZXggPSBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmxlbmd0aDtcblxuICAgIC8vIElmIHRoZSBpbmRleCBpcyA+IDAsIGZpZWxkcyBhbHJlYWR5IGV4aXN0cywgdGhlbiwgYWRkIGEgZGVsZXRlQnV0dG9uIHRvIHRoaXMgZmllbGRzXG4gICAgaWYgKGluZGV4ID4gMCkge1xuICAgICAgICBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmVhY2goZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBhZGREZWxldGVCdXR0b24oJCh0aGlzKSk7XG4gICAgICAgICAgICBhZGRGdW5jdGlvbnMoJCh0aGlzKSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIC8vIElmIHdlIHdhbnQgdG8gaGF2ZSBhIGZpZWxkIGF0IHN0YXJ0XG4gICAgaWYgKHRydWUgPT0gZmllbGRTdGFydCAmJiAwID09IGluZGV4KSB7XG4gICAgICAgIGFkZEZpZWxkKGNvbnRhaW5lcik7XG4gICAgfVxuXG4gICAgLy8gVGhlIGFkZEZpZWxkIGZ1bmN0aW9uXG4gICAgZnVuY3Rpb24gYWRkRmllbGQoY29udGFpbmVyKSB7XG4gICAgICAgIC8vIFJlcGxhY2Ugc29tZSB2YWx1ZSBpbiB0aGUgwqsgZGF0YS1wcm90b3R5cGUgwrtcbiAgICAgICAgLy8gLSBcIl9fbmFtZV9fbGFiZWxfX1wiIGJ5IHRoZSBuYW1lIHdlIHdhbnQgdG8gdXNlLCBoZXJlIG5vdGhpbmdcbiAgICAgICAgLy8gLSBcIl9fbmFtZV9fXCIgYnkgdGhlIG5hbWUgb2YgdGhlIGZpZWxkLCBoZXJlIHRoZSBpbmRleCBudW1iZXJcbiAgICAgICAgdmFyICRwcm90b3R5cGUgPSAkKGNvbnRhaW5lci5hdHRyKCdkYXRhLXByb3RvdHlwZScpXG4gICAgICAgICAgICAucmVwbGFjZSgvY2xhc3M9XCJjb2wtc20tMiBjb250cm9sLWxhYmVsIHJlcXVpcmVkXCIvLCAnY2xhc3M9XCJjb2wtc20tMiBjb250cm9sLWxhYmVsXCInKVxuICAgICAgICAgICAgLnJlcGxhY2UoL19fbmFtZV9fbGFiZWxfXy9nLCAnJylcbiAgICAgICAgICAgIC5yZXBsYWNlKC9fX25hbWVfXy9nLCBpbmRleCkpO1xuXG4gICAgICAgIC8vIEFkZCBhIGRlbGV0ZSBidXR0b24gdG8gdGhlIG5ldyBmaWVsZFxuICAgICAgICBhZGREZWxldGVCdXR0b24oJHByb3RvdHlwZSk7XG5cbiAgICAgICAgLy8gSWYgdGhlcmUgYXJlIHN1cHBsZW1lbnRhcnkgZnVuY3Rpb25zXG4gICAgICAgIGFkZEZ1bmN0aW9ucygkcHJvdG90eXBlKTtcblxuICAgICAgICAvLyBBZGQgdGhlIGZpZWxkIGluIHRoZSBmb3JtXG4gICAgICAgICRhZGRCdXR0b24uYmVmb3JlKCRwcm90b3R5cGUpO1xuXG4gICAgICAgIC8vIEluY3JlbWVudCB0aGUgY291bnRlclxuICAgICAgICBpbmRleCsrO1xuICAgIH1cblxuICAgIC8vIEEgZnVuY3Rpb24gY2FsbGVkIHRvIGFkZCBkZWxldGVCdXR0b25cbiAgICBmdW5jdGlvbiBhZGREZWxldGVCdXR0b24ocHJvdG90eXBlKSB7XG4gICAgICAgIC8vIEZpcnN0LCBjcmVhdGUgdGhlIGJ1dHRvblxuICAgICAgICB2YXIgJGRlbGV0ZUJ1dHRvbiA9ICQoJzxkaXYgY2xhc3M9XCJjb2wtc20tMVwiPjxhIGhyZWY9XCIjXCIgY2xhc3M9XCJidG4gYnRuLWRhbmdlciBidG4tc21cIj48c3BhbiBjbGFzcz1cImZhIGZhLXRyYXNoXCIgYXJpYS1oaWRkZW49XCJ0cnVlXCI+PC9zcGFuPjwvYT48L2Rpdj4nKTtcblxuICAgICAgICAvLyBBZGQgdGhlIGJ1dHRvbiBvbiB0aGUgZmllbGRcbiAgICAgICAgJCgnLmNvbC1zbS0xMCcsIHByb3RvdHlwZSkucmVtb3ZlQ2xhc3MoJ2NvbC1zbS0xMCcpLmFkZENsYXNzKCdjb2wtc20tOScpO1xuICAgICAgICBwcm90b3R5cGUuYXBwZW5kKCRkZWxldGVCdXR0b24pO1xuXG4gICAgICAgIC8vIENyZWF0ZSBhIGxpc3RlbmVyIG9uIHRoZSBjbGljayBldmVudFxuICAgICAgICAkZGVsZXRlQnV0dG9uLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIC8vIFJlbW92ZSB0aGUgZmllbGRcbiAgICAgICAgICAgIHByb3RvdHlwZS5yZW1vdmUoKTtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gYWRkRnVuY3Rpb25zKHByb3RvdHlwZSkge1xuICAgICAgICAvLyBJZiB0aGVyZSBhcmUgc3VwcGxlbWVudGFyeSBmdW5jdGlvbnNcbiAgICAgICAgaWYgKGZ1bmN0aW9ucy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAvLyBEbyBhIHdoaWxlIG9uIGZ1bmN0aW9ucywgYW5kIGFwcGx5IHRoZW0gdG8gdGhlIHByb3RvdHlwZVxuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGZ1bmN0aW9ucy5sZW5ndGggPiBpOyBpKyspIHtcbiAgICAgICAgICAgICAgICBmdW5jdGlvbnNbaV0ocHJvdG90eXBlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH1cbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jb2xsZWN0aW9uLXR5cGUuanMiLCJmdW5jdGlvbiBjb3B5MmNsaXBib2FyZChkYXRhU2VsZWN0b3IpIHtcbiAgICBkYXRhU2VsZWN0b3Iuc2VsZWN0KCk7XG4gICAgZG9jdW1lbnQuZXhlY0NvbW1hbmQoJ2NvcHknKTtcbn1cblxuZnVuY3Rpb24gY29weTJjbGlwYm9hcmRPbkNsaWNrKGNsaWNrVHJpZ2dlciwgZGF0YVNlbGVjdG9yKSB7XG4gICAgY2xpY2tUcmlnZ2VyLmNsaWNrKGZ1bmN0aW9uKCl7XG4gICAgICAgIGNvcHkyY2xpcGJvYXJkKGRhdGFTZWxlY3Rvcik7XG4gICAgfSk7XG59XG5cbiQoZnVuY3Rpb24oKSB7XG4gICBjb3B5MmNsaXBib2FyZE9uQ2xpY2soJChcIiNyZXZlcnNlLWNvbXBsZW1lbnQtY29weS1idXR0b25cIiksICQoXCIjcmV2ZXJzZS1jb21wbGVtZW50LXJlc3VsdFwiKSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jb3B5MmNsaXBib2FyZC5qcyIsIi8vIHZhciBkZWxheSA9IChmdW5jdGlvbigpe1xuLy8gICAgIHZhciB0aW1lciA9IDA7XG4vLyAgICAgcmV0dXJuIGZ1bmN0aW9uKGNhbGxiYWNrLCBtcyl7XG4vLyAgICAgICAgIGNsZWFyVGltZW91dCAodGltZXIpO1xuLy8gICAgICAgICB0aW1lciA9IHNldFRpbWVvdXQoY2FsbGJhY2ssIG1zKTtcbi8vICAgICB9O1xuLy8gfSkoKTtcblxubW9kdWxlLmV4cG9ydHMgPSAoZnVuY3Rpb24oKSB7XG4gICAgcmV0dXJuIChmdW5jdGlvbigpe1xuICAgICAgICB2YXIgdGltZXIgPSAwO1xuICAgICAgICByZXR1cm4gZnVuY3Rpb24oY2FsbGJhY2ssIG1zKXtcbiAgICAgICAgICAgIGNsZWFyVGltZW91dCAodGltZXIpO1xuICAgICAgICAgICAgdGltZXIgPSBzZXRUaW1lb3V0KGNhbGxiYWNrLCBtcyk7XG4gICAgICAgIH07XG4gICAgfSkoKTtcbn0pKCk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvZGVsYXkuanMiLCIkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpe1xuICAgICQoJ2Rpdi5sb2N1cy1mZWF0dXJlJykuZWFjaChmdW5jdGlvbihpbmRleCkge1xuICAgICAgICB2YXIgbG9jdXMgPSAkKCB0aGlzICkuZGF0YShcImxvY3VzXCIpO1xuICAgICAgICB2YXIgZmVhdHVyZSA9ICQoIHRoaXMgKS5kYXRhKFwiZmVhdHVyZVwiKTtcbiAgICAgICAgdmFyIHNlcXVlbmNlQ29udGFpbmVyID0gJCggdGhpcyApLmZpbmQoJ2Rpdi5mYXN0YScpO1xuICAgICAgICB2YXIgZm9ybSA9ICQoIHRoaXMgKS5maW5kKCdmb3JtJyk7XG5cbiAgICAgICAgZm9ybS5yZW1vdmVDbGFzcygnaGlkZGVuJyk7XG5cbiAgICAgICAgZm9ybS5zdWJtaXQoZnVuY3Rpb24oZXZlbnQpIHtcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICB2YXIgdXBzdHJlYW0gPSAkKCB0aGlzICkucGFyZW50KCkuZmluZChcImlucHV0W25hbWU9J3Vwc3RyZWFtJ11cIikudmFsKCk7XG4gICAgICAgICAgICB2YXIgZG93bnN0cmVhbSA9ICQoIHRoaXMgKS5wYXJlbnQoKS5maW5kKFwiaW5wdXRbbmFtZT0nZG93bnN0cmVhbSddXCIpLnZhbCgpO1xuICAgICAgICAgICAgdmFyIHNob3dVdHIgPSAkKCB0aGlzICkucGFyZW50KCkuZmluZChcImlucHV0W25hbWU9J3Nob3dVdHInXVwiKS5pcyhcIjpjaGVja2VkXCIpO1xuICAgICAgICAgICAgdmFyIHNob3dJbnRyb24gPSAkKCB0aGlzICkucGFyZW50KCkuZmluZChcImlucHV0W25hbWU9J3Nob3dJbnRyb24nXVwiKS5pcyhcIjpjaGVja2VkXCIpO1xuXG4gICAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgICAgIHR5cGU6ICdHRVQnLFxuICAgICAgICAgICAgICAgIHVybDogUm91dGluZy5nZW5lcmF0ZSgnZmVhdHVyZV9zZXF1ZW5jZScsIHsgbG9jdXNfbmFtZTogbG9jdXMsIGZlYXR1cmVfbmFtZTogZmVhdHVyZSwgdXBzdHJlYW06IHVwc3RyZWFtLCBkb3duc3RyZWFtOiBkb3duc3RyZWFtLCBzaG93VXRyOiBzaG93VXRyLCBzaG93SW50cm9uOiBzaG93SW50cm9uIH0pLFxuICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnaHRtbCcsXG4gICAgICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGh0bWwpIHtcbiAgICAgICAgICAgICAgICAgICAgc2VxdWVuY2VDb250YWluZXIuZmlyc3QoKS5odG1sKGh0bWwpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICB9KTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2xpdmUtc2VxdWVuY2UtZGlzcGxheS5qcyIsIiQoZnVuY3Rpb24gKCkge1xuICAgICQoJ1tkYXRhLXRvZ2dsZT1cInRvb2x0aXBcIl0nKS50b29sdGlwKClcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2xvY3VzLXRvb2x0aXAuanMiLCIkKFwiaW5wdXRbdHlwZT1wYXNzd29yZF1baWQqPSdfcGxhaW5QYXNzd29yZF8nXVwiKS5rZXl1cChmdW5jdGlvbigpe1xuICAgIC8vIFNldCByZWdleCBjb250cm9sXG4gICAgdmFyIHVjYXNlID0gbmV3IFJlZ0V4cChcIltBLVpdK1wiKTtcbiAgICB2YXIgbGNhc2UgPSBuZXcgUmVnRXhwKFwiW2Etel0rXCIpO1xuICAgIHZhciBudW0gPSBuZXcgUmVnRXhwKFwiWzAtOV0rXCIpO1xuXG4gICAgLy8gU2V0IHBhc3N3b3JkIGZpZWxkc1xuICAgIHZhciBwYXNzd29yZDEgPSAkKFwiW2lkJD0nX3BsYWluUGFzc3dvcmRfZmlyc3QnXVwiKTtcbiAgICB2YXIgcGFzc3dvcmQyID0gJChcIltpZCQ9J19wbGFpblBhc3N3b3JkX3NlY29uZCddXCIpO1xuICAgIFxuICAgIC8vIFNldCBkaXNwbGF5IHJlc3VsdFxuICAgIHZhciBudW1iZXJDaGFycyA9ICQoXCIjbnVtYmVyLWNoYXJzXCIpO1xuICAgIHZhciB1cHBlckNhc2UgPSAkKFwiI3VwcGVyLWNhc2VcIik7XG4gICAgdmFyIGxvd2VyQ2FzZSA9ICQoXCIjbG93ZXItY2FzZVwiKTtcbiAgICB2YXIgbnVtYmVyID0gJChcIiNudW1iZXJcIik7XG4gICAgdmFyIHBhc3N3b3JkTWF0Y2ggPSAkKFwiI3Bhc3N3b3JkLW1hdGNoXCIpO1xuXG4gICAgLy8gRG8gdGhlIHRlc3RcbiAgICBpZihwYXNzd29yZDEudmFsKCkubGVuZ3RoID49IDgpe1xuICAgICAgICBudW1iZXJDaGFycy5yZW1vdmVDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBudW1iZXJDaGFycy5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBudW1iZXJDaGFycy5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgbnVtYmVyQ2hhcnMucmVtb3ZlQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbnVtYmVyQ2hhcnMuYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbnVtYmVyQ2hhcnMuY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxuXG4gICAgaWYodWNhc2UudGVzdChwYXNzd29yZDEudmFsKCkpKXtcbiAgICAgICAgdXBwZXJDYXNlLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIHVwcGVyQ2FzZS5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICB1cHBlckNhc2UuY3NzKFwiY29sb3JcIixcIiMwMEE0MUVcIik7XG4gICAgfWVsc2V7XG4gICAgICAgIHVwcGVyQ2FzZS5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICB1cHBlckNhc2UuYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgdXBwZXJDYXNlLmNzcyhcImNvbG9yXCIsXCIjRkYwMDA0XCIpO1xuICAgIH1cblxuICAgIGlmKGxjYXNlLnRlc3QocGFzc3dvcmQxLnZhbCgpKSl7XG4gICAgICAgIGxvd2VyQ2FzZS5yZW1vdmVDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBsb3dlckNhc2UuYWRkQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbG93ZXJDYXNlLmNzcyhcImNvbG9yXCIsXCIjMDBBNDFFXCIpO1xuICAgIH1lbHNle1xuICAgICAgICBsb3dlckNhc2UucmVtb3ZlQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbG93ZXJDYXNlLmFkZENsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIGxvd2VyQ2FzZS5jc3MoXCJjb2xvclwiLFwiI0ZGMDAwNFwiKTtcbiAgICB9XG5cbiAgICBpZihudW0udGVzdChwYXNzd29yZDEudmFsKCkpKXtcbiAgICAgICAgbnVtYmVyLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIG51bWJlci5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBudW1iZXIuY3NzKFwiY29sb3JcIixcIiMwMEE0MUVcIik7XG4gICAgfWVsc2V7XG4gICAgICAgIG51bWJlci5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBudW1iZXIuYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbnVtYmVyLmNzcyhcImNvbG9yXCIsXCIjRkYwMDA0XCIpO1xuICAgIH1cblxuICAgIGlmKHBhc3N3b3JkMS52YWwoKSA9PT0gcGFzc3dvcmQyLnZhbCgpICYmIHBhc3N3b3JkMS52YWwoKSAhPT0gJycpe1xuICAgICAgICBwYXNzd29yZE1hdGNoLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2guYWRkQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBwYXNzd29yZE1hdGNoLmFkZENsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2guY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcGFzc3dvcmQtY29udHJvbC5qcyIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgIHZhciByZXN1bHQgPSAkKCcjc2VhcmNoLXJlc3VsdHMnKTtcblxuICAgIGlmIChyZXN1bHQubGVuZ3RoID4gMCkge1xuICAgICAgICB2YXIga2V5d29yZCA9IHJlc3VsdC5kYXRhKCdzZWFyY2gta2V5d29yZCcpO1xuICAgICAgICBrZXl3b3JkID0gJygnICsga2V5d29yZCArICcpJztcbiAgICAgICAgdmFyIHJlZ2V4ID0gbmV3IFJlZ0V4cChrZXl3b3JkLFwiZ2lcIik7XG4gICAgICAgIHZhciByZXN1bHRIdG1sID0gcmVzdWx0Lmh0bWwoKTtcblxuICAgICAgICByZXN1bHRIdG1sID0gcmVzdWx0SHRtbC5yZXBsYWNlKHJlZ2V4LCBcIjxiPiQxPC9iPlwiKTtcbiAgICAgICAgcmVzdWx0Lmh0bWwocmVzdWx0SHRtbCk7XG4gICAgfVxufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VhcmNoLWtleXdvcmQtaGlnaGxpZ2h0LmpzIiwiZnVuY3Rpb24gc3RyYWluc0ZpbHRlcihzdHJhaW5zRmlsdGVyU2VsZWN0LCBzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lcikge1xuXG4gICAgLy8gRGVmaW5lIHZhciB0aGF0IGNvbnRhaW5zIGZpZWxkc1xuICAgIHZhciBzdHJhaW5zQ2hlY2tib3hlcyA9IHN0cmFpbnNDaGVja0JveGVzQ29udGFpbmVyLmZpbmQoICcuZm9ybS1jaGVjaycgKTtcblxuICAgIC8vKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiovL1xuICAgIC8vICBBZGQgdGhlIGxpbmtzIChjaGVjay91bmNoZWNrKSAvL1xuICAgIC8vKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiovL1xuXG4gICAgLy8gRGVmaW5lIGNoZWNrQWxsL3VuY2hlY2tBbGwgbGlua3NcbiAgICB2YXIgY2hlY2tBbGxMaW5rID0gJCgnPGEgaHJlZj1cIiNcIiBjbGFzcz1cImNoZWNrX2FsbF9zdHJhaW5zXCIgPiBDaGVjayBhbGw8L2E+Jyk7XG4gICAgdmFyIHVuY2hlY2tBbGxMaW5rID0gJCgnPGEgaHJlZj1cIiNcIiBjbGFzcz1cInVuY2hlY2tfYWxsX3N0cmFpbnNcIiA+IFVuY2hlY2sgYWxsPC9hPicpO1xuXG4gICAgLy8gSW5zZXJ0IHRoZSBjaGVjay91bmNoZWNrIGxpbmtzXG4gICAgc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIucHJlcGVuZCh1bmNoZWNrQWxsTGluayk7XG4gICAgc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIucHJlcGVuZCgnIC8gJyk7XG4gICAgc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIucHJlcGVuZChjaGVja0FsbExpbmspO1xuXG4gICAgLy8qKioqKioqKioqKioqKioqKioqKioqKioqKiovL1xuICAgIC8vIENyZWF0ZSBhbGwgb25DTGljayBldmVudHMgLy9cbiAgICAvLyoqKioqKioqKioqKioqKioqKioqKioqKioqKi8vXG5cbiAgICAvLyBDcmVhdGUgb25DbGljayBldmVudCBvbiBUZWFtIGZpbHRlclxuICAgIHN0cmFpbnNGaWx0ZXJTZWxlY3QuY2hhbmdlKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgLy8gR2V0IHRoZSBjbGFkZVxuICAgICAgICB2YXIgY2xhZGUgPSAkKHRoaXMpLnZhbCgpO1xuXG4gICAgICAgIC8vIENhbGwgdGhlIGZ1bmN0aW9uIGFuZCBnaXZlIHRoZSBjbGFkZVxuICAgICAgICBzaG93SGlkZVN0cmFpbnMoY2xhZGUpO1xuICAgIH0pO1xuXG4gICAgZnVuY3Rpb24gc2hvd0hpZGVTdHJhaW5zKGNsYWRlKSB7XG4gICAgICAgIGlmICgnJyA9PT0gY2xhZGUpIHtcbiAgICAgICAgICAgIHN0cmFpbnNDaGVja2JveGVzLnNob3coKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIC8vIEhpZGUgYWxsIFN0cmFpbnNcbiAgICAgICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmhpZGUoKTtcblxuICAgICAgICAgICAgLy8gU2hvdyBjbGFkZSBzdHJhaW5zXG4gICAgICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgc3RyYWluQ2xhZGUgPSAkKCB0aGlzICkuZmluZCggXCI6Y2hlY2tib3hcIiApLmRhdGEoJ2NsYWRlJyk7XG5cbiAgICAgICAgICAgICAgICBpZiAoc3RyYWluQ2xhZGUgPT09IGNsYWRlKSB7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuc2hvdygpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgLy8gQ3JlYXRlIG9uQ2xpY2sgZXZlbnQgb24gY2hlY2tBbGxMaW5rXG4gICAgY2hlY2tBbGxMaW5rLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgdmFyIGNsYWRlRmlsdGVyZWQgPSBzdHJhaW5zRmlsdGVyU2VsZWN0LnZhbCgpO1xuXG4gICAgICAgIGlmICgnJyA9PT0gY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICAgICAgY2hlY2tBbGwoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGNoZWNrQWxsQ2xhZGUoY2xhZGVGaWx0ZXJlZCk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIC8vIENyZWF0ZSBvbkNsaWNrIGV2ZW50IG9uIHVuY2hlY2tBbGxMaW5rXG4gICAgdW5jaGVja0FsbExpbmsuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgY2xhZGVGaWx0ZXJlZCA9IHN0cmFpbnNGaWx0ZXJTZWxlY3QudmFsKCk7XG5cbiAgICAgICAgaWYgKCcnID09PSBjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgICAgICB1bmNoZWNrQWxsKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB1bmNoZWNrQWxsQ2xhZGUoY2xhZGVGaWx0ZXJlZCk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIC8vXG4gICAgLy8gQmFzZSBmdW5jdGlvbnM6IGNoZWNrL3VuY2hlY2sgYWxsIGNoZWNrYm94ZXMgYW5kIGNoZWNrL3VuY2hlY2sgc3BlY2lmaWMgc3RyYWlucyAocGVyIGNsYWRlKVxuICAgIC8vXG5cbiAgICBmdW5jdGlvbiBjaGVja0FsbENsYWRlKGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgc3RyYWluQ2xhZGUgPSAkKHRoaXMpLmZpbmQoIFwiaW5wdXQ6Y2hlY2tib3hcIiApLmRhdGEoJ2NsYWRlJyk7XG5cbiAgICAgICAgICAgIGlmIChzdHJhaW5DbGFkZSA9PT0gY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICAgICAgICAgICQodGhpcykuZmluZChcImlucHV0OmNoZWNrYm94XCIpLnByb3AoJ2NoZWNrZWQnLCB0cnVlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gdW5jaGVja0FsbENsYWRlKGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgc3RyYWluQ2xhZGUgPSAkKHRoaXMpLmZpbmQoIFwiaW5wdXQ6Y2hlY2tib3hcIiApLmRhdGEoJ2NsYWRlJyk7XG5cbiAgICAgICAgICAgIGlmIChzdHJhaW5DbGFkZSA9PT0gY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICAgICAgICAgICQodGhpcykuZmluZChcImlucHV0OmNoZWNrYm94XCIpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIGNoZWNrQWxsKCkge1xuICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICQodGhpcykuZmluZChcImlucHV0OmNoZWNrYm94XCIpLnByb3AoJ2NoZWNrZWQnLCB0cnVlKTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gdW5jaGVja0FsbCgpIHtcbiAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAkKHRoaXMpLmZpbmQoXCJpbnB1dDpjaGVja2JveFwiKS5wcm9wKCdjaGVja2VkJywgZmFsc2UpO1xuICAgICAgICB9KTtcbiAgICB9XG59XG5cbiQoZnVuY3Rpb24oKSB7XG4gICAgc3RyYWluc0ZpbHRlcigkKCBcIiNibGFzdF9zdHJhaW5zRmlsdGVyX2ZpbHRlclwiICksICQoIFwiI2JsYXN0X3N0cmFpbnNGaWx0ZXJfc3RyYWluc1wiICkpO1xuICAgIHN0cmFpbnNGaWx0ZXIoJCggXCIjYWR2YW5jZWRfc2VhcmNoX3N0cmFpbnNGaWx0ZXJfZmlsdGVyXCIgKSwgJCggXCIjYWR2YW5jZWRfc2VhcmNoX3N0cmFpbnNGaWx0ZXJfc3RyYWluc1wiICkpO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc3RyYWlucy1maWx0ZXIuanMiLCIvLyBDaGVjayBhbGwgY2hlY2tib3hlcyBubyBkaXNhYmxlZFxuZnVuY3Rpb24gY2hlY2tBbGwoZ3JvdXBOYW1lKSB7XG4gICAgJChcIjpjaGVja2JveFtkYXRhLW5hbWU9XCIgKyBncm91cE5hbWUgKyBcIl06bm90KDpkaXNhYmxlZClcIikucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xufVxuXG4vLyBVbmNoZWNrIGFsbCBjaGVja2JveGVzIGRpc2FibGVkIHRvb1xuZnVuY3Rpb24gdW5jaGVja0FsbChncm91cE5hbWUpIHtcbiAgICAkKFwiaW5wdXQ6Y2hlY2tib3hbZGF0YS1uYW1lPVwiICsgZ3JvdXBOYW1lICsgXCJdXCIpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG59XG5cbi8vIFVuY2hlY2sgYWxsIGRpc2FibGVkIGNoZWNrYm94XG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcbiAgICAkKFwiOmNoZWNrYm94OmRpc2FibGVkXCIpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG5cbiAgICAvLyBPbiBjaGVja0FsbCBjbGlja1xuICAgICQoJy5jaGVja0FsbFN0cmFpbnMnKS5jbGljayhmdW5jdGlvbihlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgdmFyIHNwZWNpZXMgPSAkKCB0aGlzICkuZGF0YSgnc3BlY2llcycpO1xuICAgICAgICBjaGVja0FsbChzcGVjaWVzKTtcbiAgICB9KTtcblxuICAgIC8vIE9uIHVuY2hlY2tBbGxDbGlja1xuICAgICQoJy51bmNoZWNrQWxsU3RyYWlucycpLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgc3BlY2llcyA9ICQoIHRoaXMgKS5kYXRhKCdzcGVjaWVzJyk7XG4gICAgICAgIHVuY2hlY2tBbGwoc3BlY2llcyk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFkbWluLXN0cmFpbnMuanMiLCJ2YXIgZGVsYXkgPSByZXF1aXJlKCcuL2RlbGF5Jyk7XG5cbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCl7XG4gICAgdmFyIHByb2Nlc3NpbmcgPSBmYWxzZTtcbiAgICB2YXIgc2VhcmNoID0gJCgnI3VzZXItc2VhcmNoLWZpZWxkJyk7XG4gICAgdmFyIHRlYW0gPSAkKCcjdXNlci10ZWFtLWZpZWxkJyk7XG5cbiAgICBzZWFyY2gua2V5dXAoZnVuY3Rpb24oKSB7XG4gICAgICAgIGhpc3RvcnkucmVwbGFjZVN0YXRlKCcnLCAnJywgUm91dGluZy5nZW5lcmF0ZSgndXNlcl9pbmRleCcsIHsgcTogc2VhcmNoLnZhbCgpLCBwOiAxIH0pKTtcblxuICAgICAgICBkZWxheShmdW5jdGlvbigpe1xuICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICB0eXBlOiAnR0VUJyxcbiAgICAgICAgICAgICAgICB1cmw6IFJvdXRpbmcuZ2VuZXJhdGUoJ3VzZXJfaW5kZXhfYWpheCcsIHsgcTogc2VhcmNoLnZhbCgpLCBwOiAxIH0pLFxuICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnaHRtbCcsXG4gICAgICAgICAgICAgICAgZGVsYXk6IDQwMCxcbiAgICAgICAgICAgICAgICBiZWZvcmVTZW5kOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHByb2Nlc3NpbmcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHByb2Nlc3NpbmcgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICAgICAgICAgICAgICAkKCcjdXNlci1saXN0JykucmVwbGFjZVdpdGgoaHRtbCk7XG4gICAgICAgICAgICAgICAgICAgIHByb2Nlc3NpbmcgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSwgNDAwICk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWluc3RhbnQtc2VhcmNoLmpzIl0sInNvdXJjZVJvb3QiOiIifQ==