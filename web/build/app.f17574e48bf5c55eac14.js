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
    $('.checkAllStrains').click(function () {
        var species = $(this).data('species');
        console.log(species);
    });

    // On uncheckAllClick
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYXV0by1kaXNtaXNzLWFsZXJ0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9ibGFzdC1zY3JvbGxzcHkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2JsYXN0LXNlbGVjdC1jaGFuZ2UuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NhcnQtYnRuLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZhc3RhLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZvcm0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxlY3Rpb24tdHlwZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvY29weTJjbGlwYm9hcmQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2RlbGF5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9saXZlLXNlcXVlbmNlLWRpc3BsYXkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2xvY3VzLXRvb2x0aXAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Bhc3N3b3JkLWNvbnRyb2wuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlYXJjaC1rZXl3b3JkLWhpZ2hsaWdodC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc3RyYWlucy1maWx0ZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWRtaW4tc3RyYWlucy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1pbnN0YW50LXNlYXJjaC5qcyJdLCJuYW1lcyI6WyIkIiwiZG9jdW1lbnQiLCJyZWFkeSIsImhpZGUiLCJmYWRlSW4iLCJkZWxheSIsImZhZGVPdXQiLCJ3aW5kb3ciLCJvbiIsIiRhbGxMaSIsInJlbW92ZUNsYXNzIiwiJGFjdGl2ZUxpIiwicHJldiIsImFkZENsYXNzIiwibmV4dCIsImVxIiwiJHRvb2wiLCJjaGFuZ2UiLCIkZm9ybSIsImNsb3Nlc3QiLCJkYXRhIiwiYXR0ciIsInZhbCIsImFqYXgiLCJ1cmwiLCJ0eXBlIiwic3VjY2VzcyIsImh0bWwiLCJyZXBsYWNlV2l0aCIsImZpbmQiLCIkY2FydEJhZGdlIiwiY2xpY2siLCJlIiwicHJldmVudERlZmF1bHQiLCIkdXJsIiwiZ2V0IiwiJG5iSXRlbXMiLCJpdGVtcyIsImxlbmd0aCIsInRleHQiLCJyZWFjaGVkX2xpbWl0IiwibG9jYXRpb24iLCJyZWxvYWQiLCIkdGFibGVSb3ciLCJyZW1vdmUiLCJnZW5lcmF0ZUNhcnRGYXN0YSIsInRleHRhcmVhSWQiLCJtb2RhbElkIiwiJG1vZGFsIiwiJHZhbHVlcyIsImVhY2giLCJlbGVtZW50cyIsImkiLCJmaWVsZCIsIm5hbWUiLCJ2YWx1ZSIsImRhdGFUeXBlIiwibW9kYWwiLCJzaG93SGlkZUNhcnRTZXR1cCIsIiR0eXBlIiwiJGZlYXR1cmUiLCIkaW50cm9uU3BsaWNpbmciLCIkdXBzdHJlYW0iLCIkZG93bnN0cmVhbSIsIiRzZXR1cCIsInNob3ciLCJwcm9wIiwiY29sbGVjdGlvblR5cGUiLCJjb250YWluZXIiLCJidXR0b25UZXh0IiwiYnV0dG9uSWQiLCJmaWVsZFN0YXJ0IiwiZnVuY3Rpb25zIiwidW5kZWZpbmVkIiwiY2hpbGRyZW4iLCJpZCIsIiRhZGRCdXR0b24iLCJhcHBlbmQiLCJhZGRGaWVsZCIsImluZGV4IiwiYWRkRGVsZXRlQnV0dG9uIiwiYWRkRnVuY3Rpb25zIiwiJHByb3RvdHlwZSIsInJlcGxhY2UiLCJiZWZvcmUiLCJwcm90b3R5cGUiLCIkZGVsZXRlQnV0dG9uIiwiY29weTJjbGlwYm9hcmQiLCJkYXRhU2VsZWN0b3IiLCJzZWxlY3QiLCJleGVjQ29tbWFuZCIsImNvcHkyY2xpcGJvYXJkT25DbGljayIsImNsaWNrVHJpZ2dlciIsIm1vZHVsZSIsImV4cG9ydHMiLCJ0aW1lciIsImNhbGxiYWNrIiwibXMiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwibG9jdXMiLCJmZWF0dXJlIiwic2VxdWVuY2VDb250YWluZXIiLCJmb3JtIiwic3VibWl0IiwiZXZlbnQiLCJ1cHN0cmVhbSIsInBhcmVudCIsImRvd25zdHJlYW0iLCJzaG93VXRyIiwiaXMiLCJzaG93SW50cm9uIiwiUm91dGluZyIsImdlbmVyYXRlIiwibG9jdXNfbmFtZSIsImZlYXR1cmVfbmFtZSIsImZpcnN0IiwidG9vbHRpcCIsImtleXVwIiwidWNhc2UiLCJSZWdFeHAiLCJsY2FzZSIsIm51bSIsInBhc3N3b3JkMSIsInBhc3N3b3JkMiIsIm51bWJlckNoYXJzIiwidXBwZXJDYXNlIiwibG93ZXJDYXNlIiwibnVtYmVyIiwicGFzc3dvcmRNYXRjaCIsImNzcyIsInRlc3QiLCJyZXN1bHQiLCJrZXl3b3JkIiwicmVnZXgiLCJyZXN1bHRIdG1sIiwic3RyYWluc0ZpbHRlciIsInN0cmFpbnNGaWx0ZXJTZWxlY3QiLCJzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lciIsInN0cmFpbnNDaGVja2JveGVzIiwiY2hlY2tBbGxMaW5rIiwidW5jaGVja0FsbExpbmsiLCJwcmVwZW5kIiwiY2xhZGUiLCJzaG93SGlkZVN0cmFpbnMiLCJzdHJhaW5DbGFkZSIsImNsYWRlRmlsdGVyZWQiLCJjaGVja0FsbCIsImNoZWNrQWxsQ2xhZGUiLCJ1bmNoZWNrQWxsIiwidW5jaGVja0FsbENsYWRlIiwiZ3JvdXBOYW1lIiwic3BlY2llcyIsImNvbnNvbGUiLCJsb2ciLCJyZXF1aXJlIiwicHJvY2Vzc2luZyIsInNlYXJjaCIsInRlYW0iLCJoaXN0b3J5IiwicmVwbGFjZVN0YXRlIiwicSIsInAiLCJiZWZvcmVTZW5kIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7O0FBQUEseUNBQUFBLEVBQUVDLFFBQUYsRUFBWUMsS0FBWixDQUFrQixZQUFXO0FBQ3pCRixNQUFFLDhCQUFGLEVBQWtDRyxJQUFsQztBQUNBSCxNQUFFLDhCQUFGLEVBQWtDSSxNQUFsQyxDQUF5QyxLQUF6QztBQUNBSixNQUFFLDhCQUFGLEVBQWtDSyxLQUFsQyxDQUF3QyxNQUF4QyxFQUFnREMsT0FBaEQs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QixRQUFJMEosYUFBYSxLQUFqQjtBQUNBLFFBQUlDLFNBQVM3SixFQUFFLG9CQUFGLENBQWI7QUFDQSxRQUFJOEosT0FBTzlKLEVBQUUsa0JBQUYsQ0FBWDs7QUFFQTZKLFdBQU92QyxLQUFQLENBQWEsWUFBVztBQUNwQnlDLGdCQUFRQyxZQUFSLENBQXFCLEVBQXJCLEVBQXlCLEVBQXpCLEVBQTZCaEQsUUFBUUMsUUFBUixDQUFpQixZQUFqQixFQUErQixFQUFFZ0QsR0FBR0osT0FBT3ZJLEdBQVAsRUFBTCxFQUFtQjRJLEdBQUcsQ0FBdEIsRUFBL0IsQ0FBN0I7O0FBRUE3SixjQUFNLFlBQVU7QUFDWkwsY0FBRXVCLElBQUYsQ0FBTztBQUNIRSxzQkFBTSxLQURIO0FBRUhELHFCQUFLd0YsUUFBUUMsUUFBUixDQUFpQixpQkFBakIsRUFBb0MsRUFBRWdELEdBQUdKLE9BQU92SSxHQUFQLEVBQUwsRUFBbUI0SSxHQUFHLENBQXRCLEVBQXBDLENBRkY7QUFHSDFHLDBCQUFVLE1BSFA7QUFJSG5ELHVCQUFPLEdBSko7QUFLSDhKLDRCQUFZLHNCQUFXO0FBQ25CLHdCQUFJUCxVQUFKLEVBQWdCO0FBQ1osK0JBQU8sS0FBUDtBQUNILHFCQUZELE1BRU87QUFDSEEscUNBQWEsSUFBYjtBQUNIO0FBQ0osaUJBWEU7QUFZSGxJLHlCQUFTLGlCQUFVQyxJQUFWLEVBQWdCO0FBQ3JCM0Isc0JBQUUsWUFBRixFQUFnQjRCLFdBQWhCLENBQTRCRCxJQUE1QjtBQUNBaUksaUNBQWEsS0FBYjtBQUNIO0FBZkUsYUFBUDtBQWlCSCxTQWxCRCxFQWtCRyxHQWxCSDtBQW1CSCxLQXRCRDtBQXVCSCxDQTVCRCxFIiwiZmlsZSI6ImFwcC5mMTc1NzRlNDhiZjVjNTVlYWMxNC5qcyIsInNvdXJjZXNDb250ZW50IjpbIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgICQoJ1tkYXRhLXRvZ2dsZT1cImF1dG8tZGlzbWlzc1wiXScpLmhpZGUoKTtcbiAgICAkKCdbZGF0YS10b2dnbGU9XCJhdXRvLWRpc21pc3NcIl0nKS5mYWRlSW4oXCJsb3dcIik7XG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwiYXV0by1kaXNtaXNzXCJdJykuZGVsYXkoJzUwMDAnKS5mYWRlT3V0KFwibG93XCIpO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvYXV0by1kaXNtaXNzLWFsZXJ0LmpzIiwiJCh3aW5kb3cpLm9uKCdhY3RpdmF0ZS5icy5zY3JvbGxzcHknLCBmdW5jdGlvbiAoKSB7XG4gICAgLy8gUmVtb3ZlIGFsbCBkaXNwbGF5IGNsYXNzXG4gICAgdmFyICRhbGxMaSA9ICQoJ25hdiNibGFzdC1zY3JvbGxzcHkgbmF2IGEuYWN0aXZlICsgbmF2IGEnKTtcbiAgICAkYWxsTGkucmVtb3ZlQ2xhc3MoJ2Rpc3BsYXknKTtcblxuICAgIC8vIEFkZCBkaXNwbGF5IGNsYXNzIG9uIDIgYmVmb3JlIGFuZCAyIGFmdGVyXG4gICAgdmFyICRhY3RpdmVMaSA9ICQoJ25hdiNibGFzdC1zY3JvbGxzcHkgbmF2IGEuYWN0aXZlICsgbmF2IGEuYWN0aXZlJyk7XG4gICAgJGFjdGl2ZUxpLnByZXYoKS5hZGRDbGFzcygnZGlzcGxheScpO1xuICAgICRhY3RpdmVMaS5wcmV2KCkucHJldigpLmFkZENsYXNzKCdkaXNwbGF5Jyk7XG4gICAgJGFjdGl2ZUxpLm5leHQoKS5hZGRDbGFzcygnZGlzcGxheScpO1xuICAgICRhY3RpdmVMaS5uZXh0KCkubmV4dCgpLmFkZENsYXNzKCdkaXNwbGF5Jyk7XG5cbiAgICAvLyBBZGQgZGlzcGxheSBvbiB0aGUgZmlyc3QgYW5kIDJuZFxuICAgICRhbGxMaS5lcSgwKS5hZGRDbGFzcygnZGlzcGxheScpO1xuICAgICRhbGxMaS5lcSgxKS5hZGRDbGFzcygnZGlzcGxheScpO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvYmxhc3Qtc2Nyb2xsc3B5LmpzIiwiJCggZG9jdW1lbnQgKS5yZWFkeShmdW5jdGlvbiAoKSB7XG4gICAgdmFyICR0b29sID0gJCgnI2JsYXN0X3Rvb2wnKTtcblxuICAgIC8vIFdoZW4gZ2VudXMgZ2V0cyBzZWxlY3RlZCAuLi5cbiAgICAkdG9vbC5jaGFuZ2UoZnVuY3Rpb24gKCkge1xuICAgICAgICAvLyAuLi4gcmV0cmlldmUgdGhlIGNvcnJlc3BvbmRpbmcgZm9ybS5cbiAgICAgICAgdmFyICRmb3JtID0gJCh0aGlzKS5jbG9zZXN0KCdmb3JtJyk7XG4gICAgICAgIC8vIFNpbXVsYXRlIGZvcm0gZGF0YSwgYnV0IG9ubHkgaW5jbHVkZSB0aGUgc2VsZWN0ZWQgZ2VudXMgdmFsdWUuXG4gICAgICAgIHZhciBkYXRhID0ge307XG4gICAgICAgIGRhdGFbJHRvb2wuYXR0cignbmFtZScpXSA9ICR0b29sLnZhbCgpO1xuXG4gICAgICAgIC8vIFN1Ym1pdCBkYXRhIHZpYSBBSkFYIHRvIHRoZSBmb3JtJ3MgYWN0aW9uIHBhdGguXG4gICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICB1cmw6ICRmb3JtLmF0dHIoJ2FjdGlvbicpLFxuICAgICAgICAgICAgdHlwZTogJGZvcm0uYXR0cignbWV0aG9kJyksXG4gICAgICAgICAgICBkYXRhOiBkYXRhLFxuICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGh0bWwpIHtcbiAgICAgICAgICAgICAgICAvLyBSZXBsYWNlIGN1cnJlbnQgcG9zaXRpb24gZmllbGQgLi4uXG4gICAgICAgICAgICAgICAgJCgnc2VsZWN0I2JsYXN0X2RhdGFiYXNlJykucmVwbGFjZVdpdGgoXG4gICAgICAgICAgICAgICAgICAgIC8vIC4uLiB3aXRoIHRoZSByZXR1cm5lZCBvbmUgZnJvbSB0aGUgQUpBWCByZXNwb25zZS5cbiAgICAgICAgICAgICAgICAgICAgJChodG1sKS5maW5kKCdzZWxlY3QjYmxhc3RfZGF0YWJhc2UnKVxuICAgICAgICAgICAgICAgICk7XG4gICAgICAgICAgICAgICAgJCgnc2VsZWN0I2JsYXN0X21hdHJpeCcpLnJlcGxhY2VXaXRoKFxuICAgICAgICAgICAgICAgICAgICAvLyAuLi4gd2l0aCB0aGUgcmV0dXJuZWQgb25lIGZyb20gdGhlIEFKQVggcmVzcG9uc2UuXG4gICAgICAgICAgICAgICAgICAgICQoaHRtbCkuZmluZCgnc2VsZWN0I2JsYXN0X21hdHJpeCcpXG4gICAgICAgICAgICAgICAgKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9ibGFzdC1zZWxlY3QtY2hhbmdlLmpzIiwiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgdmFyICRjYXJ0QmFkZ2UgPSAkKCdhI2NhcnQgc3Bhbi5iYWRnZScpO1xuXG4gICAgJCgnYS5jYXJ0LWFkZC1idG4nKS5jbGljayhmdW5jdGlvbihlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgdmFyICR1cmwgPSAkKHRoaXMpLmF0dHIoJ2hyZWYnKTtcblxuICAgICAgICAkLmdldCggJHVybCwgZnVuY3Rpb24oIGRhdGEgKSB7XG4gICAgICAgICAgICAvLyBDb3VudCBvYmplY3RzIGluIGRhdGFcbiAgICAgICAgICAgIHZhciAkbmJJdGVtcyA9IGRhdGEuaXRlbXMubGVuZ3RoO1xuICAgICAgICAgICAgJGNhcnRCYWRnZS50ZXh0KCRuYkl0ZW1zKTtcblxuICAgICAgICAgICAgLy8gaWYgcmVhY2hlZCBsaW1pdFxuICAgICAgICAgICAgaWYgKHRydWUgPT09IGRhdGEucmVhY2hlZF9saW1pdCkge1xuICAgICAgICAgICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9KTtcblxuICAgICQoJ2EuY2FydC1yZW1vdmUtYnRuJykuY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHZhciAkdXJsID0gJCh0aGlzKS5hdHRyKCdocmVmJyk7XG4gICAgICAgIHZhciAkdGFibGVSb3cgPSAkKHRoaXMpLmNsb3Nlc3QoJ3RyJyk7XG5cbiAgICAgICAgJC5nZXQoICR1cmwsIGZ1bmN0aW9uKCBkYXRhICkge1xuICAgICAgICAgICAgLy8gQ291bnQgb2JqZWN0cyBpbiBkYXRhXG4gICAgICAgICAgICB2YXIgJG5iSXRlbXMgPSBkYXRhLml0ZW1zLmxlbmd0aDtcbiAgICAgICAgICAgICRjYXJ0QmFkZ2UudGV4dCgkbmJJdGVtcyk7XG5cbiAgICAgICAgICAgIC8vIFJlbW92ZSB0aGUgbGluZSBpbiB0aGUgcGFnZVxuICAgICAgICAgICAgJHRhYmxlUm93LnJlbW92ZSgpO1xuICAgICAgICB9KTtcbiAgICB9KTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NhcnQtYnRuLmpzIiwiZnVuY3Rpb24gZ2VuZXJhdGVDYXJ0RmFzdGEodGV4dGFyZWFJZCwgbW9kYWxJZCkge1xuICAgIHZhciAkbW9kYWwgPSAkKG1vZGFsSWQpO1xuICAgIHZhciAkZm9ybSA9ICRtb2RhbC5maW5kKCdmb3JtJyk7XG5cbiAgICB2YXIgJHZhbHVlcyA9IHt9O1xuXG4gICAgJC5lYWNoKCAkZm9ybVswXS5lbGVtZW50cywgZnVuY3Rpb24oaSwgZmllbGQpIHtcbiAgICAgICAgJHZhbHVlc1tmaWVsZC5uYW1lXSA9IGZpZWxkLnZhbHVlO1xuICAgIH0pO1xuXG4gICAgJC5hamF4KHtcbiAgICAgICAgdHlwZTogICAgICAgJGZvcm0uYXR0cignbWV0aG9kJyksXG4gICAgICAgIHVybDogICAgICAgICRmb3JtLmF0dHIoJ2FjdGlvbicpLFxuICAgICAgICBkYXRhVHlwZTogICAndGV4dCcsXG4gICAgICAgIGRhdGE6ICAgICAgICR2YWx1ZXMsXG4gICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAkKG1vZGFsSWQpLm1vZGFsKCdoaWRlJyk7XG4gICAgICAgICAgICAkKHRleHRhcmVhSWQpLnZhbChkYXRhKTtcbiAgICAgICAgfVxuICAgIH0pO1xufVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NhcnQtZmFzdGEuanMiLCJmdW5jdGlvbiBzaG93SGlkZUNhcnRTZXR1cCgpIHtcbiAgICB2YXIgJHR5cGUgPSAkKCdzZWxlY3RbaWQkPVxcJ2NhcnRfdHlwZVxcJ10nKTtcbiAgICB2YXIgJGZlYXR1cmUgPSAkKCdzZWxlY3RbaWQkPVxcJ2NhcnRfZmVhdHVyZVxcJ10nKTtcbiAgICB2YXIgJGludHJvblNwbGljaW5nID0gJCgnc2VsZWN0W2lkJD1cXCdjYXJ0X2ludHJvblNwbGljaW5nXFwnXScpO1xuICAgIHZhciAkdXBzdHJlYW0gPSAkKCdpbnB1dFtpZCQ9XFwnY2FydF91cHN0cmVhbVxcJ10nKTtcbiAgICB2YXIgJGRvd25zdHJlYW0gPSAkKCdpbnB1dFtpZCQ9XFwnY2FydF9kb3duc3RyZWFtXFwnXScpO1xuICAgIHZhciAkc2V0dXAgPSAkZmVhdHVyZS5jbG9zZXN0KCcjY2FydC1zZXR1cCcpO1xuXG4gICAgaWYgKCdwcm90JyA9PT0gJHR5cGUudmFsKCkpIHtcbiAgICAgICAgJHNldHVwLmhpZGUoKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICAkc2V0dXAuc2hvdygpO1xuICAgIH1cblxuICAgIGlmICgnbG9jdXMnID09PSAkZmVhdHVyZS52YWwoKSkge1xuICAgICAgICAkaW50cm9uU3BsaWNpbmcudmFsKDApO1xuICAgICAgICAkaW50cm9uU3BsaWNpbmcucHJvcCgnZGlzYWJsZWQnLCB0cnVlKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICAkaW50cm9uU3BsaWNpbmcucHJvcCgnZGlzYWJsZWQnLCBmYWxzZSk7XG4gICAgfVxuXG4gICAgaWYgKCcxJyA9PT0gJGludHJvblNwbGljaW5nLnZhbCgpKSB7XG4gICAgICAgICR1cHN0cmVhbS5jbG9zZXN0KCdkaXYuZm9ybS1ncm91cCcpLmhpZGUoKTtcbiAgICAgICAgJGRvd25zdHJlYW0uY2xvc2VzdCgnZGl2LmZvcm0tZ3JvdXAnKS5oaWRlKCk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgJHVwc3RyZWFtLmNsb3Nlc3QoJ2Rpdi5mb3JtLWdyb3VwJykuc2hvdygpO1xuICAgICAgICAkZG93bnN0cmVhbS5jbG9zZXN0KCdkaXYuZm9ybS1ncm91cCcpLnNob3coKTtcbiAgICB9XG5cbiAgICAkdHlwZS5jaGFuZ2UoZnVuY3Rpb24oKSB7XG4gICAgICAgIHNob3dIaWRlQ2FydFNldHVwKCk7XG4gICAgfSk7XG5cbiAgICAkZmVhdHVyZS5jaGFuZ2UoZnVuY3Rpb24oKSB7XG4gICAgICAgIHNob3dIaWRlQ2FydFNldHVwKCk7XG4gICAgfSk7XG5cbiAgICAkaW50cm9uU3BsaWNpbmcuY2hhbmdlKGZ1bmN0aW9uKCkge1xuICAgICAgICBzaG93SGlkZUNhcnRTZXR1cCgpO1xuICAgIH0pO1xufVxuXG5zaG93SGlkZUNhcnRTZXR1cCgpO1xuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jYXJ0LWZvcm0uanMiLCJmdW5jdGlvbiBjb2xsZWN0aW9uVHlwZShjb250YWluZXIsIGJ1dHRvblRleHQsIGJ1dHRvbklkLCBmaWVsZFN0YXJ0LCBmdW5jdGlvbnMpIHtcbiAgICBpZiAoYnV0dG9uSWQgPT09IHVuZGVmaW5lZCkge1xuICAgICAgICBidXR0b25JZCA9IG51bGw7XG4gICAgfVxuXG4gICAgaWYgKGZpZWxkU3RhcnQgPT09IHVuZGVmaW5lZCkge1xuICAgICAgICBmaWVsZFN0YXJ0ID0gZmFsc2U7XG4gICAgfVxuXG4gICAgaWYgKGZ1bmN0aW9ucyA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGZ1bmN0aW9ucyA9IFtdO1xuICAgIH1cblxuICAgIC8vIERlbGV0ZSB0aGUgZmlyc3QgbGFiZWwgKHRoZSBudW1iZXIgb2YgdGhlIGZpZWxkKSwgYW5kIHRoZSByZXF1aXJlZCBjbGFzc1xuICAgIGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykuZmluZCgnbGFiZWw6Zmlyc3QnKS50ZXh0KCcnKTtcbiAgICBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmZpbmQoJ2xhYmVsOmZpcnN0JykucmVtb3ZlQ2xhc3MoJ3JlcXVpcmVkJyk7XG4gICAgY29udGFpbmVyLmNoaWxkcmVuKCdkaXYnKS5maW5kKCdsYWJlbDpmaXJzdCcpLnJlbW92ZUNsYXNzKCdyZXF1aXJlZCcpO1xuXG4gICAgLy8gQ3JlYXRlIGFuZCBhZGQgYSBidXR0b24gdG8gYWRkIG5ldyBmaWVsZFxuICAgIGlmIChidXR0b25JZCkge1xuICAgICAgICB2YXIgaWQgPSBcImlkPSdcIiArIGJ1dHRvbklkICsgXCInXCI7XG4gICAgICAgIHZhciAkYWRkQnV0dG9uID0gJCgnPGEgaHJlZj1cIiNcIiAnICsgaWQgKyAnY2xhc3M9XCJidG4gYnRuLWRlZmF1bHQgYnRuLXhzXCI+PHNwYW4gY2xhc3M9XCJmYSBmYS1wbHVzIGFyaWEtaGlkZGVuPVwidHJ1ZVwiXCI+PC9zcGFuPiAnK2J1dHRvblRleHQrJzwvYT4nKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICB2YXIgJGFkZEJ1dHRvbiA9ICQoJzxhIGhyZWY9XCIjXCIgY2xhc3M9XCJidG4gYnRuLWRlZmF1bHQgYnRuLXhzXCI+PHNwYW4gY2xhc3M9XCJmYSBmYS1wbHVzIGFyaWEtaGlkZGVuPVwidHJ1ZVwiXCI+PC9zcGFuPiAnK2J1dHRvblRleHQrJzwvYT4nKTtcbiAgICB9XG5cbiAgICBjb250YWluZXIuYXBwZW5kKCRhZGRCdXR0b24pO1xuXG4gICAgLy8gQWRkIGEgY2xpY2sgZXZlbnQgb24gdGhlIGFkZCBidXR0b25cbiAgICAkYWRkQnV0dG9uLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAvLyBDYWxsIHRoZSBhZGRGaWVsZCBtZXRob2RcbiAgICAgICAgYWRkRmllbGQoY29udGFpbmVyKTtcbiAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH0pO1xuXG4gICAgLy8gRGVmaW5lIGFuIGluZGV4IHRvIGNvdW50IHRoZSBudW1iZXIgb2YgYWRkZWQgZmllbGQgKHVzZWQgdG8gZ2l2ZSBuYW1lIHRvIGZpZWxkcylcbiAgICB2YXIgaW5kZXggPSBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmxlbmd0aDtcblxuICAgIC8vIElmIHRoZSBpbmRleCBpcyA+IDAsIGZpZWxkcyBhbHJlYWR5IGV4aXN0cywgdGhlbiwgYWRkIGEgZGVsZXRlQnV0dG9uIHRvIHRoaXMgZmllbGRzXG4gICAgaWYgKGluZGV4ID4gMCkge1xuICAgICAgICBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmVhY2goZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBhZGREZWxldGVCdXR0b24oJCh0aGlzKSk7XG4gICAgICAgICAgICBhZGRGdW5jdGlvbnMoJCh0aGlzKSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIC8vIElmIHdlIHdhbnQgdG8gaGF2ZSBhIGZpZWxkIGF0IHN0YXJ0XG4gICAgaWYgKHRydWUgPT0gZmllbGRTdGFydCAmJiAwID09IGluZGV4KSB7XG4gICAgICAgIGFkZEZpZWxkKGNvbnRhaW5lcik7XG4gICAgfVxuXG4gICAgLy8gVGhlIGFkZEZpZWxkIGZ1bmN0aW9uXG4gICAgZnVuY3Rpb24gYWRkRmllbGQoY29udGFpbmVyKSB7XG4gICAgICAgIC8vIFJlcGxhY2Ugc29tZSB2YWx1ZSBpbiB0aGUgwqsgZGF0YS1wcm90b3R5cGUgwrtcbiAgICAgICAgLy8gLSBcIl9fbmFtZV9fbGFiZWxfX1wiIGJ5IHRoZSBuYW1lIHdlIHdhbnQgdG8gdXNlLCBoZXJlIG5vdGhpbmdcbiAgICAgICAgLy8gLSBcIl9fbmFtZV9fXCIgYnkgdGhlIG5hbWUgb2YgdGhlIGZpZWxkLCBoZXJlIHRoZSBpbmRleCBudW1iZXJcbiAgICAgICAgdmFyICRwcm90b3R5cGUgPSAkKGNvbnRhaW5lci5hdHRyKCdkYXRhLXByb3RvdHlwZScpXG4gICAgICAgICAgICAucmVwbGFjZSgvY2xhc3M9XCJjb2wtc20tMiBjb250cm9sLWxhYmVsIHJlcXVpcmVkXCIvLCAnY2xhc3M9XCJjb2wtc20tMiBjb250cm9sLWxhYmVsXCInKVxuICAgICAgICAgICAgLnJlcGxhY2UoL19fbmFtZV9fbGFiZWxfXy9nLCAnJylcbiAgICAgICAgICAgIC5yZXBsYWNlKC9fX25hbWVfXy9nLCBpbmRleCkpO1xuXG4gICAgICAgIC8vIEFkZCBhIGRlbGV0ZSBidXR0b24gdG8gdGhlIG5ldyBmaWVsZFxuICAgICAgICBhZGREZWxldGVCdXR0b24oJHByb3RvdHlwZSk7XG5cbiAgICAgICAgLy8gSWYgdGhlcmUgYXJlIHN1cHBsZW1lbnRhcnkgZnVuY3Rpb25zXG4gICAgICAgIGFkZEZ1bmN0aW9ucygkcHJvdG90eXBlKTtcblxuICAgICAgICAvLyBBZGQgdGhlIGZpZWxkIGluIHRoZSBmb3JtXG4gICAgICAgICRhZGRCdXR0b24uYmVmb3JlKCRwcm90b3R5cGUpO1xuXG4gICAgICAgIC8vIEluY3JlbWVudCB0aGUgY291bnRlclxuICAgICAgICBpbmRleCsrO1xuICAgIH1cblxuICAgIC8vIEEgZnVuY3Rpb24gY2FsbGVkIHRvIGFkZCBkZWxldGVCdXR0b25cbiAgICBmdW5jdGlvbiBhZGREZWxldGVCdXR0b24ocHJvdG90eXBlKSB7XG4gICAgICAgIC8vIEZpcnN0LCBjcmVhdGUgdGhlIGJ1dHRvblxuICAgICAgICB2YXIgJGRlbGV0ZUJ1dHRvbiA9ICQoJzxkaXYgY2xhc3M9XCJjb2wtc20tMVwiPjxhIGhyZWY9XCIjXCIgY2xhc3M9XCJidG4gYnRuLWRhbmdlciBidG4tc21cIj48c3BhbiBjbGFzcz1cImZhIGZhLXRyYXNoXCIgYXJpYS1oaWRkZW49XCJ0cnVlXCI+PC9zcGFuPjwvYT48L2Rpdj4nKTtcblxuICAgICAgICAvLyBBZGQgdGhlIGJ1dHRvbiBvbiB0aGUgZmllbGRcbiAgICAgICAgJCgnLmNvbC1zbS0xMCcsIHByb3RvdHlwZSkucmVtb3ZlQ2xhc3MoJ2NvbC1zbS0xMCcpLmFkZENsYXNzKCdjb2wtc20tOScpO1xuICAgICAgICBwcm90b3R5cGUuYXBwZW5kKCRkZWxldGVCdXR0b24pO1xuXG4gICAgICAgIC8vIENyZWF0ZSBhIGxpc3RlbmVyIG9uIHRoZSBjbGljayBldmVudFxuICAgICAgICAkZGVsZXRlQnV0dG9uLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIC8vIFJlbW92ZSB0aGUgZmllbGRcbiAgICAgICAgICAgIHByb3RvdHlwZS5yZW1vdmUoKTtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gYWRkRnVuY3Rpb25zKHByb3RvdHlwZSkge1xuICAgICAgICAvLyBJZiB0aGVyZSBhcmUgc3VwcGxlbWVudGFyeSBmdW5jdGlvbnNcbiAgICAgICAgaWYgKGZ1bmN0aW9ucy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAvLyBEbyBhIHdoaWxlIG9uIGZ1bmN0aW9ucywgYW5kIGFwcGx5IHRoZW0gdG8gdGhlIHByb3RvdHlwZVxuICAgICAgICAgICAgZm9yICh2YXIgaSA9IDA7IGZ1bmN0aW9ucy5sZW5ndGggPiBpOyBpKyspIHtcbiAgICAgICAgICAgICAgICBmdW5jdGlvbnNbaV0ocHJvdG90eXBlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH1cbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jb2xsZWN0aW9uLXR5cGUuanMiLCJmdW5jdGlvbiBjb3B5MmNsaXBib2FyZChkYXRhU2VsZWN0b3IpIHtcbiAgICBkYXRhU2VsZWN0b3Iuc2VsZWN0KCk7XG4gICAgZG9jdW1lbnQuZXhlY0NvbW1hbmQoJ2NvcHknKTtcbn1cblxuZnVuY3Rpb24gY29weTJjbGlwYm9hcmRPbkNsaWNrKGNsaWNrVHJpZ2dlciwgZGF0YVNlbGVjdG9yKSB7XG4gICAgY2xpY2tUcmlnZ2VyLmNsaWNrKGZ1bmN0aW9uKCl7XG4gICAgICAgIGNvcHkyY2xpcGJvYXJkKGRhdGFTZWxlY3Rvcik7XG4gICAgfSk7XG59XG5cbiQoZnVuY3Rpb24oKSB7XG4gICBjb3B5MmNsaXBib2FyZE9uQ2xpY2soJChcIiNyZXZlcnNlLWNvbXBsZW1lbnQtY29weS1idXR0b25cIiksICQoXCIjcmV2ZXJzZS1jb21wbGVtZW50LXJlc3VsdFwiKSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jb3B5MmNsaXBib2FyZC5qcyIsIi8vIHZhciBkZWxheSA9IChmdW5jdGlvbigpe1xuLy8gICAgIHZhciB0aW1lciA9IDA7XG4vLyAgICAgcmV0dXJuIGZ1bmN0aW9uKGNhbGxiYWNrLCBtcyl7XG4vLyAgICAgICAgIGNsZWFyVGltZW91dCAodGltZXIpO1xuLy8gICAgICAgICB0aW1lciA9IHNldFRpbWVvdXQoY2FsbGJhY2ssIG1zKTtcbi8vICAgICB9O1xuLy8gfSkoKTtcblxubW9kdWxlLmV4cG9ydHMgPSAoZnVuY3Rpb24oKSB7XG4gICAgcmV0dXJuIChmdW5jdGlvbigpe1xuICAgICAgICB2YXIgdGltZXIgPSAwO1xuICAgICAgICByZXR1cm4gZnVuY3Rpb24oY2FsbGJhY2ssIG1zKXtcbiAgICAgICAgICAgIGNsZWFyVGltZW91dCAodGltZXIpO1xuICAgICAgICAgICAgdGltZXIgPSBzZXRUaW1lb3V0KGNhbGxiYWNrLCBtcyk7XG4gICAgICAgIH07XG4gICAgfSkoKTtcbn0pKCk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvZGVsYXkuanMiLCIkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpe1xuICAgICQoJ2Rpdi5sb2N1cy1mZWF0dXJlJykuZWFjaChmdW5jdGlvbihpbmRleCkge1xuICAgICAgICB2YXIgbG9jdXMgPSAkKCB0aGlzICkuZGF0YShcImxvY3VzXCIpO1xuICAgICAgICB2YXIgZmVhdHVyZSA9ICQoIHRoaXMgKS5kYXRhKFwiZmVhdHVyZVwiKTtcbiAgICAgICAgdmFyIHNlcXVlbmNlQ29udGFpbmVyID0gJCggdGhpcyApLmZpbmQoJ2Rpdi5mYXN0YScpO1xuICAgICAgICB2YXIgZm9ybSA9ICQoIHRoaXMgKS5maW5kKCdmb3JtJyk7XG5cbiAgICAgICAgZm9ybS5yZW1vdmVDbGFzcygnaGlkZGVuJyk7XG5cbiAgICAgICAgZm9ybS5zdWJtaXQoZnVuY3Rpb24oZXZlbnQpIHtcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICB2YXIgdXBzdHJlYW0gPSAkKCB0aGlzICkucGFyZW50KCkuZmluZChcImlucHV0W25hbWU9J3Vwc3RyZWFtJ11cIikudmFsKCk7XG4gICAgICAgICAgICB2YXIgZG93bnN0cmVhbSA9ICQoIHRoaXMgKS5wYXJlbnQoKS5maW5kKFwiaW5wdXRbbmFtZT0nZG93bnN0cmVhbSddXCIpLnZhbCgpO1xuICAgICAgICAgICAgdmFyIHNob3dVdHIgPSAkKCB0aGlzICkucGFyZW50KCkuZmluZChcImlucHV0W25hbWU9J3Nob3dVdHInXVwiKS5pcyhcIjpjaGVja2VkXCIpO1xuICAgICAgICAgICAgdmFyIHNob3dJbnRyb24gPSAkKCB0aGlzICkucGFyZW50KCkuZmluZChcImlucHV0W25hbWU9J3Nob3dJbnRyb24nXVwiKS5pcyhcIjpjaGVja2VkXCIpO1xuXG4gICAgICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgICAgIHR5cGU6ICdHRVQnLFxuICAgICAgICAgICAgICAgIHVybDogUm91dGluZy5nZW5lcmF0ZSgnZmVhdHVyZV9zZXF1ZW5jZScsIHsgbG9jdXNfbmFtZTogbG9jdXMsIGZlYXR1cmVfbmFtZTogZmVhdHVyZSwgdXBzdHJlYW06IHVwc3RyZWFtLCBkb3duc3RyZWFtOiBkb3duc3RyZWFtLCBzaG93VXRyOiBzaG93VXRyLCBzaG93SW50cm9uOiBzaG93SW50cm9uIH0pLFxuICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnaHRtbCcsXG4gICAgICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGh0bWwpIHtcbiAgICAgICAgICAgICAgICAgICAgc2VxdWVuY2VDb250YWluZXIuZmlyc3QoKS5odG1sKGh0bWwpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9KTtcbiAgICB9KTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2xpdmUtc2VxdWVuY2UtZGlzcGxheS5qcyIsIiQoZnVuY3Rpb24gKCkge1xuICAgICQoJ1tkYXRhLXRvZ2dsZT1cInRvb2x0aXBcIl0nKS50b29sdGlwKClcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2xvY3VzLXRvb2x0aXAuanMiLCIkKFwiaW5wdXRbdHlwZT1wYXNzd29yZF1baWQqPSdfcGxhaW5QYXNzd29yZF8nXVwiKS5rZXl1cChmdW5jdGlvbigpe1xuICAgIC8vIFNldCByZWdleCBjb250cm9sXG4gICAgdmFyIHVjYXNlID0gbmV3IFJlZ0V4cChcIltBLVpdK1wiKTtcbiAgICB2YXIgbGNhc2UgPSBuZXcgUmVnRXhwKFwiW2Etel0rXCIpO1xuICAgIHZhciBudW0gPSBuZXcgUmVnRXhwKFwiWzAtOV0rXCIpO1xuXG4gICAgLy8gU2V0IHBhc3N3b3JkIGZpZWxkc1xuICAgIHZhciBwYXNzd29yZDEgPSAkKFwiW2lkJD0nX3BsYWluUGFzc3dvcmRfZmlyc3QnXVwiKTtcbiAgICB2YXIgcGFzc3dvcmQyID0gJChcIltpZCQ9J19wbGFpblBhc3N3b3JkX3NlY29uZCddXCIpO1xuICAgIFxuICAgIC8vIFNldCBkaXNwbGF5IHJlc3VsdFxuICAgIHZhciBudW1iZXJDaGFycyA9ICQoXCIjbnVtYmVyLWNoYXJzXCIpO1xuICAgIHZhciB1cHBlckNhc2UgPSAkKFwiI3VwcGVyLWNhc2VcIik7XG4gICAgdmFyIGxvd2VyQ2FzZSA9ICQoXCIjbG93ZXItY2FzZVwiKTtcbiAgICB2YXIgbnVtYmVyID0gJChcIiNudW1iZXJcIik7XG4gICAgdmFyIHBhc3N3b3JkTWF0Y2ggPSAkKFwiI3Bhc3N3b3JkLW1hdGNoXCIpO1xuXG4gICAgLy8gRG8gdGhlIHRlc3RcbiAgICBpZihwYXNzd29yZDEudmFsKCkubGVuZ3RoID49IDgpe1xuICAgICAgICBudW1iZXJDaGFycy5yZW1vdmVDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBudW1iZXJDaGFycy5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBudW1iZXJDaGFycy5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgbnVtYmVyQ2hhcnMucmVtb3ZlQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbnVtYmVyQ2hhcnMuYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbnVtYmVyQ2hhcnMuY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxuXG4gICAgaWYodWNhc2UudGVzdChwYXNzd29yZDEudmFsKCkpKXtcbiAgICAgICAgdXBwZXJDYXNlLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIHVwcGVyQ2FzZS5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICB1cHBlckNhc2UuY3NzKFwiY29sb3JcIixcIiMwMEE0MUVcIik7XG4gICAgfWVsc2V7XG4gICAgICAgIHVwcGVyQ2FzZS5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICB1cHBlckNhc2UuYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgdXBwZXJDYXNlLmNzcyhcImNvbG9yXCIsXCIjRkYwMDA0XCIpO1xuICAgIH1cblxuICAgIGlmKGxjYXNlLnRlc3QocGFzc3dvcmQxLnZhbCgpKSl7XG4gICAgICAgIGxvd2VyQ2FzZS5yZW1vdmVDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBsb3dlckNhc2UuYWRkQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbG93ZXJDYXNlLmNzcyhcImNvbG9yXCIsXCIjMDBBNDFFXCIpO1xuICAgIH1lbHNle1xuICAgICAgICBsb3dlckNhc2UucmVtb3ZlQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbG93ZXJDYXNlLmFkZENsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIGxvd2VyQ2FzZS5jc3MoXCJjb2xvclwiLFwiI0ZGMDAwNFwiKTtcbiAgICB9XG5cbiAgICBpZihudW0udGVzdChwYXNzd29yZDEudmFsKCkpKXtcbiAgICAgICAgbnVtYmVyLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIG51bWJlci5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBudW1iZXIuY3NzKFwiY29sb3JcIixcIiMwMEE0MUVcIik7XG4gICAgfWVsc2V7XG4gICAgICAgIG51bWJlci5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBudW1iZXIuYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbnVtYmVyLmNzcyhcImNvbG9yXCIsXCIjRkYwMDA0XCIpO1xuICAgIH1cblxuICAgIGlmKHBhc3N3b3JkMS52YWwoKSA9PT0gcGFzc3dvcmQyLnZhbCgpICYmIHBhc3N3b3JkMS52YWwoKSAhPT0gJycpe1xuICAgICAgICBwYXNzd29yZE1hdGNoLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2guYWRkQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBwYXNzd29yZE1hdGNoLmFkZENsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2guY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvcGFzc3dvcmQtY29udHJvbC5qcyIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgIHZhciByZXN1bHQgPSAkKCcjc2VhcmNoLXJlc3VsdHMnKTtcblxuICAgIGlmIChyZXN1bHQubGVuZ3RoID4gMCkge1xuICAgICAgICB2YXIga2V5d29yZCA9IHJlc3VsdC5kYXRhKCdzZWFyY2gta2V5d29yZCcpO1xuICAgICAgICBrZXl3b3JkID0gJygnICsga2V5d29yZCArICcpJztcbiAgICAgICAgdmFyIHJlZ2V4ID0gbmV3IFJlZ0V4cChrZXl3b3JkLFwiZ2lcIik7XG4gICAgICAgIHZhciByZXN1bHRIdG1sID0gcmVzdWx0Lmh0bWwoKTtcblxuICAgICAgICByZXN1bHRIdG1sID0gcmVzdWx0SHRtbC5yZXBsYWNlKHJlZ2V4LCBcIjxiPiQxPC9iPlwiKTtcbiAgICAgICAgcmVzdWx0Lmh0bWwocmVzdWx0SHRtbCk7XG4gICAgfVxufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc2VhcmNoLWtleXdvcmQtaGlnaGxpZ2h0LmpzIiwiZnVuY3Rpb24gc3RyYWluc0ZpbHRlcihzdHJhaW5zRmlsdGVyU2VsZWN0LCBzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lcikge1xuXG4gICAgLy8gRGVmaW5lIHZhciB0aGF0IGNvbnRhaW5zIGZpZWxkc1xuICAgIHZhciBzdHJhaW5zQ2hlY2tib3hlcyA9IHN0cmFpbnNDaGVja0JveGVzQ29udGFpbmVyLmZpbmQoICcuZm9ybS1jaGVjaycgKTtcblxuICAgIC8vKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiovL1xuICAgIC8vICBBZGQgdGhlIGxpbmtzIChjaGVjay91bmNoZWNrKSAvL1xuICAgIC8vKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiovL1xuXG4gICAgLy8gRGVmaW5lIGNoZWNrQWxsL3VuY2hlY2tBbGwgbGlua3NcbiAgICB2YXIgY2hlY2tBbGxMaW5rID0gJCgnPGEgaHJlZj1cIiNcIiBjbGFzcz1cImNoZWNrX2FsbF9zdHJhaW5zXCIgPiBDaGVjayBhbGw8L2E+Jyk7XG4gICAgdmFyIHVuY2hlY2tBbGxMaW5rID0gJCgnPGEgaHJlZj1cIiNcIiBjbGFzcz1cInVuY2hlY2tfYWxsX3N0cmFpbnNcIiA+IFVuY2hlY2sgYWxsPC9hPicpO1xuXG4gICAgLy8gSW5zZXJ0IHRoZSBjaGVjay91bmNoZWNrIGxpbmtzXG4gICAgc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIucHJlcGVuZCh1bmNoZWNrQWxsTGluayk7XG4gICAgc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIucHJlcGVuZCgnIC8gJyk7XG4gICAgc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIucHJlcGVuZChjaGVja0FsbExpbmspO1xuXG4gICAgLy8qKioqKioqKioqKioqKioqKioqKioqKioqKiovL1xuICAgIC8vIENyZWF0ZSBhbGwgb25DTGljayBldmVudHMgLy9cbiAgICAvLyoqKioqKioqKioqKioqKioqKioqKioqKioqKi8vXG5cbiAgICAvLyBDcmVhdGUgb25DbGljayBldmVudCBvbiBUZWFtIGZpbHRlclxuICAgIHN0cmFpbnNGaWx0ZXJTZWxlY3QuY2hhbmdlKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgLy8gR2V0IHRoZSBjbGFkZVxuICAgICAgICB2YXIgY2xhZGUgPSAkKHRoaXMpLnZhbCgpO1xuXG4gICAgICAgIC8vIENhbGwgdGhlIGZ1bmN0aW9uIGFuZCBnaXZlIHRoZSBjbGFkZVxuICAgICAgICBzaG93SGlkZVN0cmFpbnMoY2xhZGUpO1xuICAgIH0pO1xuXG4gICAgZnVuY3Rpb24gc2hvd0hpZGVTdHJhaW5zKGNsYWRlKSB7XG4gICAgICAgIGlmICgnJyA9PT0gY2xhZGUpIHtcbiAgICAgICAgICAgIHN0cmFpbnNDaGVja2JveGVzLnNob3coKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIC8vIEhpZGUgYWxsIFN0cmFpbnNcbiAgICAgICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmhpZGUoKTtcblxuICAgICAgICAgICAgLy8gU2hvdyBjbGFkZSBzdHJhaW5zXG4gICAgICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICB2YXIgc3RyYWluQ2xhZGUgPSAkKCB0aGlzICkuZmluZCggXCI6Y2hlY2tib3hcIiApLmRhdGEoJ2NsYWRlJyk7XG5cbiAgICAgICAgICAgICAgICBpZiAoc3RyYWluQ2xhZGUgPT09IGNsYWRlKSB7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuc2hvdygpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgLy8gQ3JlYXRlIG9uQ2xpY2sgZXZlbnQgb24gY2hlY2tBbGxMaW5rXG4gICAgY2hlY2tBbGxMaW5rLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgdmFyIGNsYWRlRmlsdGVyZWQgPSBzdHJhaW5zRmlsdGVyU2VsZWN0LnZhbCgpO1xuXG4gICAgICAgIGlmICgnJyA9PT0gY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICAgICAgY2hlY2tBbGwoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGNoZWNrQWxsQ2xhZGUoY2xhZGVGaWx0ZXJlZCk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIC8vIENyZWF0ZSBvbkNsaWNrIGV2ZW50IG9uIHVuY2hlY2tBbGxMaW5rXG4gICAgdW5jaGVja0FsbExpbmsuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgY2xhZGVGaWx0ZXJlZCA9IHN0cmFpbnNGaWx0ZXJTZWxlY3QudmFsKCk7XG5cbiAgICAgICAgaWYgKCcnID09PSBjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgICAgICB1bmNoZWNrQWxsKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB1bmNoZWNrQWxsQ2xhZGUoY2xhZGVGaWx0ZXJlZCk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIC8vXG4gICAgLy8gQmFzZSBmdW5jdGlvbnM6IGNoZWNrL3VuY2hlY2sgYWxsIGNoZWNrYm94ZXMgYW5kIGNoZWNrL3VuY2hlY2sgc3BlY2lmaWMgc3RyYWlucyAocGVyIGNsYWRlKVxuICAgIC8vXG5cbiAgICBmdW5jdGlvbiBjaGVja0FsbENsYWRlKGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgc3RyYWluQ2xhZGUgPSAkKHRoaXMpLmZpbmQoIFwiaW5wdXQ6Y2hlY2tib3hcIiApLmRhdGEoJ2NsYWRlJyk7XG5cbiAgICAgICAgICAgIGlmIChzdHJhaW5DbGFkZSA9PT0gY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICAgICAgICAgICQodGhpcykuZmluZChcImlucHV0OmNoZWNrYm94XCIpLnByb3AoJ2NoZWNrZWQnLCB0cnVlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gdW5jaGVja0FsbENsYWRlKGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgc3RyYWluQ2xhZGUgPSAkKHRoaXMpLmZpbmQoIFwiaW5wdXQ6Y2hlY2tib3hcIiApLmRhdGEoJ2NsYWRlJyk7XG5cbiAgICAgICAgICAgIGlmIChzdHJhaW5DbGFkZSA9PT0gY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICAgICAgICAgICQodGhpcykuZmluZChcImlucHV0OmNoZWNrYm94XCIpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIGNoZWNrQWxsKCkge1xuICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICQodGhpcykuZmluZChcImlucHV0OmNoZWNrYm94XCIpLnByb3AoJ2NoZWNrZWQnLCB0cnVlKTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gdW5jaGVja0FsbCgpIHtcbiAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAkKHRoaXMpLmZpbmQoXCJpbnB1dDpjaGVja2JveFwiKS5wcm9wKCdjaGVja2VkJywgZmFsc2UpO1xuICAgICAgICB9KTtcbiAgICB9XG59XG5cbiQoZnVuY3Rpb24oKSB7XG4gICAgc3RyYWluc0ZpbHRlcigkKCBcIiNibGFzdF9zdHJhaW5zRmlsdGVyX2ZpbHRlclwiICksICQoIFwiI2JsYXN0X3N0cmFpbnNGaWx0ZXJfc3RyYWluc1wiICkpO1xuICAgIHN0cmFpbnNGaWx0ZXIoJCggXCIjYWR2YW5jZWRfc2VhcmNoX3N0cmFpbnNGaWx0ZXJfZmlsdGVyXCIgKSwgJCggXCIjYWR2YW5jZWRfc2VhcmNoX3N0cmFpbnNGaWx0ZXJfc3RyYWluc1wiICkpO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvc3RyYWlucy1maWx0ZXIuanMiLCIvLyBDaGVjayBhbGwgY2hlY2tib3hlcyBubyBkaXNhYmxlZFxuZnVuY3Rpb24gY2hlY2tBbGwoZ3JvdXBOYW1lKSB7XG4gICAgJChcIjpjaGVja2JveFtkYXRhLW5hbWU9XCIgKyBncm91cE5hbWUgKyBcIl06bm90KDpkaXNhYmxlZClcIikucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xufVxuXG4vLyBVbmNoZWNrIGFsbCBjaGVja2JveGVzIGRpc2FibGVkIHRvb1xuZnVuY3Rpb24gdW5jaGVja0FsbChncm91cE5hbWUpIHtcbiAgICAkKFwiaW5wdXQ6Y2hlY2tib3hbZGF0YS1uYW1lPVwiICsgZ3JvdXBOYW1lICsgXCJdXCIpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG59XG5cbi8vIFVuY2hlY2sgYWxsIGRpc2FibGVkIGNoZWNrYm94XG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcbiAgICAkKFwiOmNoZWNrYm94OmRpc2FibGVkXCIpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG5cbiAgICAvLyBPbiBjaGVja0FsbCBjbGlja1xuICAgICQoJy5jaGVja0FsbFN0cmFpbnMnKS5jbGljayhmdW5jdGlvbigpIHtcbiAgICAgICAgdmFyIHNwZWNpZXMgPSAkKCB0aGlzICkuZGF0YSgnc3BlY2llcycpO1xuICAgICAgICBjb25zb2xlLmxvZyhzcGVjaWVzKTtcbiAgICB9KTtcblxuICAgIC8vIE9uIHVuY2hlY2tBbGxDbGlja1xufSk7XG5cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFkbWluLXN0cmFpbnMuanMiLCJ2YXIgZGVsYXkgPSByZXF1aXJlKCcuL2RlbGF5Jyk7XG5cbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCl7XG4gICAgdmFyIHByb2Nlc3NpbmcgPSBmYWxzZTtcbiAgICB2YXIgc2VhcmNoID0gJCgnI3VzZXItc2VhcmNoLWZpZWxkJyk7XG4gICAgdmFyIHRlYW0gPSAkKCcjdXNlci10ZWFtLWZpZWxkJyk7XG5cbiAgICBzZWFyY2gua2V5dXAoZnVuY3Rpb24oKSB7XG4gICAgICAgIGhpc3RvcnkucmVwbGFjZVN0YXRlKCcnLCAnJywgUm91dGluZy5nZW5lcmF0ZSgndXNlcl9pbmRleCcsIHsgcTogc2VhcmNoLnZhbCgpLCBwOiAxIH0pKTtcblxuICAgICAgICBkZWxheShmdW5jdGlvbigpe1xuICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICB0eXBlOiAnR0VUJyxcbiAgICAgICAgICAgICAgICB1cmw6IFJvdXRpbmcuZ2VuZXJhdGUoJ3VzZXJfaW5kZXhfYWpheCcsIHsgcTogc2VhcmNoLnZhbCgpLCBwOiAxIH0pLFxuICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnaHRtbCcsXG4gICAgICAgICAgICAgICAgZGVsYXk6IDQwMCxcbiAgICAgICAgICAgICAgICBiZWZvcmVTZW5kOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHByb2Nlc3NpbmcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHByb2Nlc3NpbmcgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICAgICAgICAgICAgICAkKCcjdXNlci1saXN0JykucmVwbGFjZVdpdGgoaHRtbCk7XG4gICAgICAgICAgICAgICAgICAgIHByb2Nlc3NpbmcgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSwgNDAwICk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWluc3RhbnQtc2VhcmNoLmpzIl0sInNvdXJjZVJvb3QiOiIifQ==