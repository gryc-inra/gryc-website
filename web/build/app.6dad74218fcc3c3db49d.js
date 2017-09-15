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
});

// On checkAll click


// On uncheckAllClick
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYXV0by1kaXNtaXNzLWFsZXJ0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9ibGFzdC1zY3JvbGxzcHkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2JsYXN0LXNlbGVjdC1jaGFuZ2UuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NhcnQtYnRuLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZhc3RhLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZvcm0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxlY3Rpb24tdHlwZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvY29weTJjbGlwYm9hcmQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2RlbGF5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9saXZlLXNlcXVlbmNlLWRpc3BsYXkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2xvY3VzLXRvb2x0aXAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Bhc3N3b3JkLWNvbnRyb2wuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlYXJjaC1rZXl3b3JkLWhpZ2hsaWdodC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc3RyYWlucy1maWx0ZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItYWRtaW4tc3RyYWlucy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci1pbnN0YW50LXNlYXJjaC5qcyJdLCJuYW1lcyI6WyIkIiwiZG9jdW1lbnQiLCJyZWFkeSIsImhpZGUiLCJmYWRlSW4iLCJkZWxheSIsImZhZGVPdXQiLCJ3aW5kb3ciLCJvbiIsIiRhbGxMaSIsInJlbW92ZUNsYXNzIiwiJGFjdGl2ZUxpIiwicHJldiIsImFkZENsYXNzIiwibmV4dCIsImVxIiwiJHRvb2wiLCJjaGFuZ2UiLCIkZm9ybSIsImNsb3Nlc3QiLCJkYXRhIiwiYXR0ciIsInZhbCIsImFqYXgiLCJ1cmwiLCJ0eXBlIiwic3VjY2VzcyIsImh0bWwiLCJyZXBsYWNlV2l0aCIsImZpbmQiLCIkY2FydEJhZGdlIiwiY2xpY2siLCJlIiwicHJldmVudERlZmF1bHQiLCIkdXJsIiwiZ2V0IiwiJG5iSXRlbXMiLCJpdGVtcyIsImxlbmd0aCIsInRleHQiLCJyZWFjaGVkX2xpbWl0IiwibG9jYXRpb24iLCJyZWxvYWQiLCIkdGFibGVSb3ciLCJyZW1vdmUiLCJnZW5lcmF0ZUNhcnRGYXN0YSIsInRleHRhcmVhSWQiLCJtb2RhbElkIiwiJG1vZGFsIiwiJHZhbHVlcyIsImVhY2giLCJlbGVtZW50cyIsImkiLCJmaWVsZCIsIm5hbWUiLCJ2YWx1ZSIsImRhdGFUeXBlIiwibW9kYWwiLCJzaG93SGlkZUNhcnRTZXR1cCIsIiR0eXBlIiwiJGZlYXR1cmUiLCIkaW50cm9uU3BsaWNpbmciLCIkdXBzdHJlYW0iLCIkZG93bnN0cmVhbSIsIiRzZXR1cCIsInNob3ciLCJwcm9wIiwiY29sbGVjdGlvblR5cGUiLCJjb250YWluZXIiLCJidXR0b25UZXh0IiwiYnV0dG9uSWQiLCJmaWVsZFN0YXJ0IiwiZnVuY3Rpb25zIiwidW5kZWZpbmVkIiwiY2hpbGRyZW4iLCJpZCIsIiRhZGRCdXR0b24iLCJhcHBlbmQiLCJhZGRGaWVsZCIsImluZGV4IiwiYWRkRGVsZXRlQnV0dG9uIiwiYWRkRnVuY3Rpb25zIiwiJHByb3RvdHlwZSIsInJlcGxhY2UiLCJiZWZvcmUiLCJwcm90b3R5cGUiLCIkZGVsZXRlQnV0dG9uIiwiY29weTJjbGlwYm9hcmQiLCJkYXRhU2VsZWN0b3IiLCJzZWxlY3QiLCJleGVjQ29tbWFuZCIsImNvcHkyY2xpcGJvYXJkT25DbGljayIsImNsaWNrVHJpZ2dlciIsIm1vZHVsZSIsImV4cG9ydHMiLCJ0aW1lciIsImNhbGxiYWNrIiwibXMiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwibG9jdXMiLCJmZWF0dXJlIiwic2VxdWVuY2VDb250YWluZXIiLCJmb3JtIiwic3VibWl0IiwiZXZlbnQiLCJ1cHN0cmVhbSIsInBhcmVudCIsImRvd25zdHJlYW0iLCJzaG93VXRyIiwiaXMiLCJzaG93SW50cm9uIiwiUm91dGluZyIsImdlbmVyYXRlIiwibG9jdXNfbmFtZSIsImZlYXR1cmVfbmFtZSIsImZpcnN0IiwidG9vbHRpcCIsImtleXVwIiwidWNhc2UiLCJSZWdFeHAiLCJsY2FzZSIsIm51bSIsInBhc3N3b3JkMSIsInBhc3N3b3JkMiIsIm51bWJlckNoYXJzIiwidXBwZXJDYXNlIiwibG93ZXJDYXNlIiwibnVtYmVyIiwicGFzc3dvcmRNYXRjaCIsImNzcyIsInRlc3QiLCJyZXN1bHQiLCJrZXl3b3JkIiwicmVnZXgiLCJyZXN1bHRIdG1sIiwic3RyYWluc0ZpbHRlciIsInN0cmFpbnNGaWx0ZXJTZWxlY3QiLCJzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lciIsInN0cmFpbnNDaGVja2JveGVzIiwiY2hlY2tBbGxMaW5rIiwidW5jaGVja0FsbExpbmsiLCJwcmVwZW5kIiwiY2xhZGUiLCJzaG93SGlkZVN0cmFpbnMiLCJzdHJhaW5DbGFkZSIsImNsYWRlRmlsdGVyZWQiLCJjaGVja0FsbCIsImNoZWNrQWxsQ2xhZGUiLCJ1bmNoZWNrQWxsIiwidW5jaGVja0FsbENsYWRlIiwiZ3JvdXBOYW1lIiwicmVxdWlyZSIsInByb2Nlc3NpbmciLCJzZWFyY2giLCJ0ZWFtIiwiaGlzdG9yeSIsInJlcGxhY2VTdGF0ZSIsInEiLCJwIiwiYmVmb3JlU2VuZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7OztBQUFBLHlDQUFBQSxFQUFFQyxRQUFGLEVBQVlDLEtBQVosQ0FBa0IsWUFBVztBQUN6QkYsTUFBRSw4QkFBRixFQUFrQ0csSUFBbEM7QUFDQUgsTUFBRSw4QkFBRixFQUFrQ0ksTUFBbEMsQ0FBeUMsS0FBekM7QUFDQUosTUFBRSw4QkFBRixFQUFrQ0ssS0FBbEMsQ0FBd0MsTUFBeEMsRUFBZ0RDLE9BQWhELENBQXdELEtBQXhEO0FBQ0gsQ0FKRCxFOzs7Ozs7Ozs7Ozs7O0FDQUEseUNBQUFOLEVBQUVPLE1BQUYsRUFBVUMsRUFBVixDQUFhLHVCQUFiLEVBQXNDLFlBQVk7QUFDOUM7QUFDQSxRQUFJQyxTQUFTVCxFQUFFLDBDQUFGLENBQWI7QUFDQVMsV0FBT0MsV0FBUCxDQUFtQixTQUFuQjs7QUFFQTtBQUNBLFFBQUlDLFlBQVlYLEVBQUUsaURBQUYsQ0FBaEI7QUFDQVcsY0FBVUMsSUFBVixHQUFpQkMsUUFBakIsQ0FBMEIsU0FBMUI7QUFDQUYsY0FBVUMsSUFBVixHQUFpQkEsSUFBakIsR0FBd0JDLFFBQXhCLENBQWlDLFNBQWpDO0FBQ0FGLGNBQVVHLElBQVYsR0FBaUJELFFBQWpCLENBQTBCLFNBQTFCO0FBQ0FGLGNBQVVHLElBQVYsR0FBaUJBLElBQWpCLEdBQXdCRCxRQUF4QixDQUFpQyxTQUFqQzs7QUFFQTtBQUNBSixXQUFPTSxFQUFQLENBQVUsQ0FBVixFQUFhRixRQUFiLENBQXNCLFNBQXRCO0FBQ0FKLFdBQU9NLEVBQVAsQ0FBVSxDQUFWLEVBQWFGLFFBQWIsQ0FBc0IsU0FBdEI7QUFDSCxDQWZELEU7Ozs7Ozs7Ozs7Ozs7QUNBQSx5Q0FBQWIsRUFBR0MsUUFBSCxFQUFjQyxLQUFkLENBQW9CLFlBQVk7QUFDNUIsUUFBSWMsUUFBUWhCLEVBQUUsYUFBRixDQUFaOztBQUVBO0FBQ0FnQixVQUFNQyxNQUFOLENBQWEsWUFBWTtBQUNyQjtBQUNBLFlBQUlDLFFBQVFsQixFQUFFLElBQUYsRUFBUW1CLE9BQVIsQ0FBZ0IsTUFBaEIsQ0FBWjtBQUNBO0FBQ0EsWUFBSUMsT0FBTyxFQUFYO0FBQ0FBLGFBQUtKLE1BQU1LLElBQU4sQ0FBVyxNQUFYLENBQUwsSUFBMkJMLE1BQU1NLEdBQU4sRUFBM0I7O0FBRUE7QUFDQXRCLFVBQUV1QixJQUFGLENBQU87QUFDSEMsaUJBQUtOLE1BQU1HLElBQU4sQ0FBVyxRQUFYLENBREY7QUFFSEksa0JBQU1QLE1BQU1HLElBQU4sQ0FBVyxRQUFYLENBRkg7QUFHSEQsa0JBQU1BLElBSEg7QUFJSE0scUJBQVMsaUJBQVVDLElBQVYsRUFBZ0I7QUFDckI7QUFDQTNCLGtCQUFFLHVCQUFGLEVBQTJCNEIsV0FBM0I7QUFDSTtBQUNBNUIsa0JBQUUyQixJQUFGLEVBQVFFLElBQVIsQ0FBYSx1QkFBYixDQUZKO0FBSUE3QixrQkFBRSxxQkFBRixFQUF5QjRCLFdBQXpCO0FBQ0k7QUFDQTVCLGtCQUFFMkIsSUFBRixFQUFRRSxJQUFSLENBQWEscUJBQWIsQ0FGSjtBQUlIO0FBZEUsU0FBUDtBQWdCSCxLQXhCRDtBQXlCSCxDQTdCRCxFOzs7Ozs7Ozs7Ozs7O0FDQUEseUNBQUE3QixFQUFFQyxRQUFGLEVBQVlDLEtBQVosQ0FBa0IsWUFBVztBQUN6QixRQUFJNEIsYUFBYTlCLEVBQUUsbUJBQUYsQ0FBakI7O0FBRUFBLE1BQUUsZ0JBQUYsRUFBb0IrQixLQUFwQixDQUEwQixVQUFTQyxDQUFULEVBQVk7QUFDbENBLFVBQUVDLGNBQUY7QUFDQSxZQUFJQyxPQUFPbEMsRUFBRSxJQUFGLEVBQVFxQixJQUFSLENBQWEsTUFBYixDQUFYOztBQUVBckIsVUFBRW1DLEdBQUYsQ0FBT0QsSUFBUCxFQUFhLFVBQVVkLElBQVYsRUFBaUI7QUFDMUI7QUFDQSxnQkFBSWdCLFdBQVdoQixLQUFLaUIsS0FBTCxDQUFXQyxNQUExQjtBQUNBUix1QkFBV1MsSUFBWCxDQUFnQkgsUUFBaEI7O0FBRUE7QUFDQSxnQkFBSSxTQUFTaEIsS0FBS29CLGFBQWxCLEVBQWlDO0FBQzdCQyx5QkFBU0MsTUFBVDtBQUNIO0FBQ0osU0FURDtBQVVILEtBZEQ7O0FBZ0JBMUMsTUFBRSxtQkFBRixFQUF1QitCLEtBQXZCLENBQTZCLFVBQVNDLENBQVQsRUFBWTtBQUNyQ0EsVUFBRUMsY0FBRjtBQUNBLFlBQUlDLE9BQU9sQyxFQUFFLElBQUYsRUFBUXFCLElBQVIsQ0FBYSxNQUFiLENBQVg7QUFDQSxZQUFJc0IsWUFBWTNDLEVBQUUsSUFBRixFQUFRbUIsT0FBUixDQUFnQixJQUFoQixDQUFoQjs7QUFFQW5CLFVBQUVtQyxHQUFGLENBQU9ELElBQVAsRUFBYSxVQUFVZCxJQUFWLEVBQWlCO0FBQzFCO0FBQ0EsZ0JBQUlnQixXQUFXaEIsS0FBS2lCLEtBQUwsQ0FBV0MsTUFBMUI7QUFDQVIsdUJBQVdTLElBQVgsQ0FBZ0JILFFBQWhCOztBQUVBO0FBQ0FPLHNCQUFVQyxNQUFWO0FBQ0gsU0FQRDtBQVFILEtBYkQ7QUFjSCxDQWpDRCxFOzs7Ozs7Ozs7Ozs7O0FDQUEsa0RBQVNDLGlCQUFULENBQTJCQyxVQUEzQixFQUF1Q0MsT0FBdkMsRUFBZ0Q7QUFDNUMsUUFBSUMsU0FBU2hELEVBQUUrQyxPQUFGLENBQWI7QUFDQSxRQUFJN0IsUUFBUThCLE9BQU9uQixJQUFQLENBQVksTUFBWixDQUFaOztBQUVBLFFBQUlvQixVQUFVLEVBQWQ7O0FBRUFqRCxNQUFFa0QsSUFBRixDQUFRaEMsTUFBTSxDQUFOLEVBQVNpQyxRQUFqQixFQUEyQixVQUFTQyxDQUFULEVBQVlDLEtBQVosRUFBbUI7QUFDMUNKLGdCQUFRSSxNQUFNQyxJQUFkLElBQXNCRCxNQUFNRSxLQUE1QjtBQUNILEtBRkQ7O0FBSUF2RCxNQUFFdUIsSUFBRixDQUFPO0FBQ0hFLGNBQVlQLE1BQU1HLElBQU4sQ0FBVyxRQUFYLENBRFQ7QUFFSEcsYUFBWU4sTUFBTUcsSUFBTixDQUFXLFFBQVgsQ0FGVDtBQUdIbUMsa0JBQVksTUFIVDtBQUlIcEMsY0FBWTZCLE9BSlQ7QUFLSHZCLGlCQUFTLGlCQUFVTixJQUFWLEVBQWdCO0FBQ3JCcEIsY0FBRStDLE9BQUYsRUFBV1UsS0FBWCxDQUFpQixNQUFqQjtBQUNBekQsY0FBRThDLFVBQUYsRUFBY3hCLEdBQWQsQ0FBa0JGLElBQWxCO0FBQ0g7QUFSRSxLQUFQO0FBVUgsQzs7Ozs7Ozs7Ozs7OztBQ3BCRCxrREFBU3NDLGlCQUFULEdBQTZCO0FBQ3pCLFFBQUlDLFFBQVEzRCxFQUFFLDJCQUFGLENBQVo7QUFDQSxRQUFJNEQsV0FBVzVELEVBQUUsOEJBQUYsQ0FBZjtBQUNBLFFBQUk2RCxrQkFBa0I3RCxFQUFFLHFDQUFGLENBQXRCO0FBQ0EsUUFBSThELFlBQVk5RCxFQUFFLDhCQUFGLENBQWhCO0FBQ0EsUUFBSStELGNBQWMvRCxFQUFFLGdDQUFGLENBQWxCO0FBQ0EsUUFBSWdFLFNBQVNKLFNBQVN6QyxPQUFULENBQWlCLGFBQWpCLENBQWI7O0FBRUEsUUFBSSxXQUFXd0MsTUFBTXJDLEdBQU4sRUFBZixFQUE0QjtBQUN4QjBDLGVBQU83RCxJQUFQO0FBQ0gsS0FGRCxNQUVPO0FBQ0g2RCxlQUFPQyxJQUFQO0FBQ0g7O0FBRUQsUUFBSSxZQUFZTCxTQUFTdEMsR0FBVCxFQUFoQixFQUFnQztBQUM1QnVDLHdCQUFnQnZDLEdBQWhCLENBQW9CLENBQXBCO0FBQ0F1Qyx3QkFBZ0JLLElBQWhCLENBQXFCLFVBQXJCLEVBQWlDLElBQWpDO0FBQ0gsS0FIRCxNQUdPO0FBQ0hMLHdCQUFnQkssSUFBaEIsQ0FBcUIsVUFBckIsRUFBaUMsS0FBakM7QUFDSDs7QUFFRCxRQUFJLFFBQVFMLGdCQUFnQnZDLEdBQWhCLEVBQVosRUFBbUM7QUFDL0J3QyxrQkFBVTNDLE9BQVYsQ0FBa0IsZ0JBQWxCLEVBQW9DaEIsSUFBcEM7QUFDQTRELG9CQUFZNUMsT0FBWixDQUFvQixnQkFBcEIsRUFBc0NoQixJQUF0QztBQUNILEtBSEQsTUFHTztBQUNIMkQsa0JBQVUzQyxPQUFWLENBQWtCLGdCQUFsQixFQUFvQzhDLElBQXBDO0FBQ0FGLG9CQUFZNUMsT0FBWixDQUFvQixnQkFBcEIsRUFBc0M4QyxJQUF0QztBQUNIOztBQUVETixVQUFNMUMsTUFBTixDQUFhLFlBQVc7QUFDcEJ5QztBQUNILEtBRkQ7O0FBSUFFLGFBQVMzQyxNQUFULENBQWdCLFlBQVc7QUFDdkJ5QztBQUNILEtBRkQ7O0FBSUFHLG9CQUFnQjVDLE1BQWhCLENBQXVCLFlBQVc7QUFDOUJ5QztBQUNILEtBRkQ7QUFHSDs7QUFFREEsb0I7Ozs7Ozs7Ozs7Ozs7QUMxQ0Esa0RBQVNTLGNBQVQsQ0FBd0JDLFNBQXhCLEVBQW1DQyxVQUFuQyxFQUErQ0MsUUFBL0MsRUFBeURDLFVBQXpELEVBQXFFQyxTQUFyRSxFQUFnRjtBQUM1RSxRQUFJRixhQUFhRyxTQUFqQixFQUE0QjtBQUN4QkgsbUJBQVcsSUFBWDtBQUNIOztBQUVELFFBQUlDLGVBQWVFLFNBQW5CLEVBQThCO0FBQzFCRixxQkFBYSxLQUFiO0FBQ0g7O0FBRUQsUUFBSUMsY0FBY0MsU0FBbEIsRUFBNkI7QUFDekJELG9CQUFZLEVBQVo7QUFDSDs7QUFFRDtBQUNBSixjQUFVTSxRQUFWLENBQW1CLEtBQW5CLEVBQTBCN0MsSUFBMUIsQ0FBK0IsYUFBL0IsRUFBOENVLElBQTlDLENBQW1ELEVBQW5EO0FBQ0E2QixjQUFVTSxRQUFWLENBQW1CLEtBQW5CLEVBQTBCN0MsSUFBMUIsQ0FBK0IsYUFBL0IsRUFBOENuQixXQUE5QyxDQUEwRCxVQUExRDtBQUNBMEQsY0FBVU0sUUFBVixDQUFtQixLQUFuQixFQUEwQjdDLElBQTFCLENBQStCLGFBQS9CLEVBQThDbkIsV0FBOUMsQ0FBMEQsVUFBMUQ7O0FBRUE7QUFDQSxRQUFJNEQsUUFBSixFQUFjO0FBQ1YsWUFBSUssS0FBSyxTQUFTTCxRQUFULEdBQW9CLEdBQTdCO0FBQ0EsWUFBSU0sYUFBYTVFLEVBQUUsaUJBQWlCMkUsRUFBakIsR0FBc0IscUZBQXRCLEdBQTRHTixVQUE1RyxHQUF1SCxNQUF6SCxDQUFqQjtBQUNILEtBSEQsTUFHTztBQUNILFlBQUlPLGFBQWE1RSxFQUFFLG9HQUFrR3FFLFVBQWxHLEdBQTZHLE1BQS9HLENBQWpCO0FBQ0g7O0FBRURELGNBQVVTLE1BQVYsQ0FBaUJELFVBQWpCOztBQUVBO0FBQ0FBLGVBQVc3QyxLQUFYLENBQWlCLFVBQVNDLENBQVQsRUFBWTtBQUN6QkEsVUFBRUMsY0FBRjtBQUNBO0FBQ0E2QyxpQkFBU1YsU0FBVDtBQUNBLGVBQU8sS0FBUDtBQUNILEtBTEQ7O0FBT0E7QUFDQSxRQUFJVyxRQUFRWCxVQUFVTSxRQUFWLENBQW1CLEtBQW5CLEVBQTBCcEMsTUFBdEM7O0FBRUE7QUFDQSxRQUFJeUMsUUFBUSxDQUFaLEVBQWU7QUFDWFgsa0JBQVVNLFFBQVYsQ0FBbUIsS0FBbkIsRUFBMEJ4QixJQUExQixDQUErQixZQUFXO0FBQ3RDOEIsNEJBQWdCaEYsRUFBRSxJQUFGLENBQWhCO0FBQ0FpRix5QkFBYWpGLEVBQUUsSUFBRixDQUFiO0FBQ0gsU0FIRDtBQUlIOztBQUVEO0FBQ0EsUUFBSSxRQUFRdUUsVUFBUixJQUFzQixLQUFLUSxLQUEvQixFQUFzQztBQUNsQ0QsaUJBQVNWLFNBQVQ7QUFDSDs7QUFFRDtBQUNBLGFBQVNVLFFBQVQsQ0FBa0JWLFNBQWxCLEVBQTZCO0FBQ3pCO0FBQ0E7QUFDQTtBQUNBLFlBQUljLGFBQWFsRixFQUFFb0UsVUFBVS9DLElBQVYsQ0FBZSxnQkFBZixFQUNkOEQsT0FEYyxDQUNOLHlDQURNLEVBQ3FDLGdDQURyQyxFQUVkQSxPQUZjLENBRU4sa0JBRk0sRUFFYyxFQUZkLEVBR2RBLE9BSGMsQ0FHTixXQUhNLEVBR09KLEtBSFAsQ0FBRixDQUFqQjs7QUFLQTtBQUNBQyx3QkFBZ0JFLFVBQWhCOztBQUVBO0FBQ0FELHFCQUFhQyxVQUFiOztBQUVBO0FBQ0FOLG1CQUFXUSxNQUFYLENBQWtCRixVQUFsQjs7QUFFQTtBQUNBSDtBQUNIOztBQUVEO0FBQ0EsYUFBU0MsZUFBVCxDQUF5QkssU0FBekIsRUFBb0M7QUFDaEM7QUFDQSxZQUFJQyxnQkFBZ0J0RixFQUFFLGdJQUFGLENBQXBCOztBQUVBO0FBQ0FBLFVBQUUsWUFBRixFQUFnQnFGLFNBQWhCLEVBQTJCM0UsV0FBM0IsQ0FBdUMsV0FBdkMsRUFBb0RHLFFBQXBELENBQTZELFVBQTdEO0FBQ0F3RSxrQkFBVVIsTUFBVixDQUFpQlMsYUFBakI7O0FBRUE7QUFDQUEsc0JBQWN2RCxLQUFkLENBQW9CLFVBQVNDLENBQVQsRUFBWTtBQUM1QkEsY0FBRUMsY0FBRjtBQUNBO0FBQ0FvRCxzQkFBVXpDLE1BQVY7QUFDQSxtQkFBTyxLQUFQO0FBQ0gsU0FMRDtBQU1IOztBQUVELGFBQVNxQyxZQUFULENBQXNCSSxTQUF0QixFQUFpQztBQUM3QjtBQUNBLFlBQUliLFVBQVVsQyxNQUFWLEdBQW1CLENBQXZCLEVBQTBCO0FBQ3RCO0FBQ0EsaUJBQUssSUFBSWMsSUFBSSxDQUFiLEVBQWdCb0IsVUFBVWxDLE1BQVYsR0FBbUJjLENBQW5DLEVBQXNDQSxHQUF0QyxFQUEyQztBQUN2Q29CLDBCQUFVcEIsQ0FBVixFQUFhaUMsU0FBYjtBQUNIO0FBQ0o7QUFDSjtBQUNKLEM7Ozs7Ozs7Ozs7Ozs7QUN0R0Qsa0RBQVNFLGNBQVQsQ0FBd0JDLFlBQXhCLEVBQXNDO0FBQ2xDQSxpQkFBYUMsTUFBYjtBQUNBeEYsYUFBU3lGLFdBQVQsQ0FBcUIsTUFBckI7QUFDSDs7QUFFRCxTQUFTQyxxQkFBVCxDQUErQkMsWUFBL0IsRUFBNkNKLFlBQTdDLEVBQTJEO0FBQ3ZESSxpQkFBYTdELEtBQWIsQ0FBbUIsWUFBVTtBQUN6QndELHVCQUFlQyxZQUFmO0FBQ0gsS0FGRDtBQUdIOztBQUVEeEYsRUFBRSxZQUFXO0FBQ1YyRiwwQkFBc0IzRixFQUFFLGlDQUFGLENBQXRCLEVBQTREQSxFQUFFLDRCQUFGLENBQTVEO0FBQ0YsQ0FGRCxFOzs7Ozs7Ozs7Ozs7O0FDWEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE2RixPQUFPQyxPQUFQLEdBQWtCLFlBQVc7QUFDekIsV0FBUSxZQUFVO0FBQ2QsWUFBSUMsUUFBUSxDQUFaO0FBQ0EsZUFBTyxVQUFTQyxRQUFULEVBQW1CQyxFQUFuQixFQUFzQjtBQUN6QkMseUJBQWNILEtBQWQ7QUFDQUEsb0JBQVFJLFdBQVdILFFBQVgsRUFBcUJDLEVBQXJCLENBQVI7QUFDSCxTQUhEO0FBSUgsS0FOTSxFQUFQO0FBT0gsQ0FSZ0IsRUFBakIsQzs7Ozs7Ozs7Ozs7O0FDUkEseUNBQUFqRyxFQUFFQyxRQUFGLEVBQVlDLEtBQVosQ0FBa0IsWUFBVTtBQUN4QkYsTUFBRSxtQkFBRixFQUF1QmtELElBQXZCLENBQTRCLFVBQVM2QixLQUFULEVBQWdCO0FBQ3hDLFlBQUlxQixRQUFRcEcsRUFBRyxJQUFILEVBQVVvQixJQUFWLENBQWUsT0FBZixDQUFaO0FBQ0EsWUFBSWlGLFVBQVVyRyxFQUFHLElBQUgsRUFBVW9CLElBQVYsQ0FBZSxTQUFmLENBQWQ7QUFDQSxZQUFJa0Ysb0JBQW9CdEcsRUFBRyxJQUFILEVBQVU2QixJQUFWLENBQWUsV0FBZixDQUF4QjtBQUNBLFlBQUkwRSxPQUFPdkcsRUFBRyxJQUFILEVBQVU2QixJQUFWLENBQWUsTUFBZixDQUFYOztBQUVBMEUsYUFBSzdGLFdBQUwsQ0FBaUIsUUFBakI7O0FBRUE2RixhQUFLQyxNQUFMLENBQVksVUFBU0MsS0FBVCxFQUFnQjtBQUN4QkEsa0JBQU14RSxjQUFOO0FBQ0EsZ0JBQUl5RSxXQUFXMUcsRUFBRyxJQUFILEVBQVUyRyxNQUFWLEdBQW1COUUsSUFBbkIsQ0FBd0Isd0JBQXhCLEVBQWtEUCxHQUFsRCxFQUFmO0FBQ0EsZ0JBQUlzRixhQUFhNUcsRUFBRyxJQUFILEVBQVUyRyxNQUFWLEdBQW1COUUsSUFBbkIsQ0FBd0IsMEJBQXhCLEVBQW9EUCxHQUFwRCxFQUFqQjtBQUNBLGdCQUFJdUYsVUFBVTdHLEVBQUcsSUFBSCxFQUFVMkcsTUFBVixHQUFtQjlFLElBQW5CLENBQXdCLHVCQUF4QixFQUFpRGlGLEVBQWpELENBQW9ELFVBQXBELENBQWQ7QUFDQSxnQkFBSUMsYUFBYS9HLEVBQUcsSUFBSCxFQUFVMkcsTUFBVixHQUFtQjlFLElBQW5CLENBQXdCLDBCQUF4QixFQUFvRGlGLEVBQXBELENBQXVELFVBQXZELENBQWpCOztBQUVBOUcsY0FBRXVCLElBQUYsQ0FBTztBQUNIRSxzQkFBTSxLQURIO0FBRUhELHFCQUFLd0YsUUFBUUMsUUFBUixDQUFpQixrQkFBakIsRUFBcUMsRUFBRUMsWUFBWWQsS0FBZCxFQUFxQmUsY0FBY2QsT0FBbkMsRUFBNENLLFVBQVVBLFFBQXRELEVBQWdFRSxZQUFZQSxVQUE1RSxFQUF3RkMsU0FBU0EsT0FBakcsRUFBMEdFLFlBQVlBLFVBQXRILEVBQXJDLENBRkY7QUFHSHZELDBCQUFVLE1BSFA7QUFJSDlCLHlCQUFTLGlCQUFVQyxJQUFWLEVBQWdCO0FBQ3JCMkUsc0NBQWtCYyxLQUFsQixHQUEwQnpGLElBQTFCLENBQStCQSxJQUEvQjtBQUNIO0FBTkUsYUFBUDtBQVFILFNBZkQ7QUFnQkgsS0F4QkQ7QUF5QkgsQ0ExQkQsRTs7Ozs7Ozs7Ozs7OztBQ0FBLHlDQUFBM0IsRUFBRSxZQUFZO0FBQ1ZBLE1BQUUseUJBQUYsRUFBNkJxSCxPQUE3QjtBQUNILENBRkQsRTs7Ozs7Ozs7Ozs7OztBQ0FBLHlDQUFBckgsRUFBRSw2Q0FBRixFQUFpRHNILEtBQWpELENBQXVELFlBQVU7QUFDN0Q7QUFDQSxRQUFJQyxRQUFRLElBQUlDLE1BQUosQ0FBVyxRQUFYLENBQVo7QUFDQSxRQUFJQyxRQUFRLElBQUlELE1BQUosQ0FBVyxRQUFYLENBQVo7QUFDQSxRQUFJRSxNQUFNLElBQUlGLE1BQUosQ0FBVyxRQUFYLENBQVY7O0FBRUE7QUFDQSxRQUFJRyxZQUFZM0gsRUFBRSw4QkFBRixDQUFoQjtBQUNBLFFBQUk0SCxZQUFZNUgsRUFBRSwrQkFBRixDQUFoQjs7QUFFQTtBQUNBLFFBQUk2SCxjQUFjN0gsRUFBRSxlQUFGLENBQWxCO0FBQ0EsUUFBSThILFlBQVk5SCxFQUFFLGFBQUYsQ0FBaEI7QUFDQSxRQUFJK0gsWUFBWS9ILEVBQUUsYUFBRixDQUFoQjtBQUNBLFFBQUlnSSxTQUFTaEksRUFBRSxTQUFGLENBQWI7QUFDQSxRQUFJaUksZ0JBQWdCakksRUFBRSxpQkFBRixDQUFwQjs7QUFFQTtBQUNBLFFBQUcySCxVQUFVckcsR0FBVixHQUFnQmdCLE1BQWhCLElBQTBCLENBQTdCLEVBQStCO0FBQzNCdUYsb0JBQVluSCxXQUFaLENBQXdCLFVBQXhCO0FBQ0FtSCxvQkFBWWhILFFBQVosQ0FBcUIsVUFBckI7QUFDQWdILG9CQUFZSyxHQUFaLENBQWdCLE9BQWhCLEVBQXdCLFNBQXhCO0FBQ0gsS0FKRCxNQUlLO0FBQ0RMLG9CQUFZbkgsV0FBWixDQUF3QixVQUF4QjtBQUNBbUgsb0JBQVloSCxRQUFaLENBQXFCLFVBQXJCO0FBQ0FnSCxvQkFBWUssR0FBWixDQUFnQixPQUFoQixFQUF3QixTQUF4QjtBQUNIOztBQUVELFFBQUdYLE1BQU1ZLElBQU4sQ0FBV1IsVUFBVXJHLEdBQVYsRUFBWCxDQUFILEVBQStCO0FBQzNCd0csa0JBQVVwSCxXQUFWLENBQXNCLFVBQXRCO0FBQ0FvSCxrQkFBVWpILFFBQVYsQ0FBbUIsVUFBbkI7QUFDQWlILGtCQUFVSSxHQUFWLENBQWMsT0FBZCxFQUFzQixTQUF0QjtBQUNILEtBSkQsTUFJSztBQUNESixrQkFBVXBILFdBQVYsQ0FBc0IsVUFBdEI7QUFDQW9ILGtCQUFVakgsUUFBVixDQUFtQixVQUFuQjtBQUNBaUgsa0JBQVVJLEdBQVYsQ0FBYyxPQUFkLEVBQXNCLFNBQXRCO0FBQ0g7O0FBRUQsUUFBR1QsTUFBTVUsSUFBTixDQUFXUixVQUFVckcsR0FBVixFQUFYLENBQUgsRUFBK0I7QUFDM0J5RyxrQkFBVXJILFdBQVYsQ0FBc0IsVUFBdEI7QUFDQXFILGtCQUFVbEgsUUFBVixDQUFtQixVQUFuQjtBQUNBa0gsa0JBQVVHLEdBQVYsQ0FBYyxPQUFkLEVBQXNCLFNBQXRCO0FBQ0gsS0FKRCxNQUlLO0FBQ0RILGtCQUFVckgsV0FBVixDQUFzQixVQUF0QjtBQUNBcUgsa0JBQVVsSCxRQUFWLENBQW1CLFVBQW5CO0FBQ0FrSCxrQkFBVUcsR0FBVixDQUFjLE9BQWQsRUFBc0IsU0FBdEI7QUFDSDs7QUFFRCxRQUFHUixJQUFJUyxJQUFKLENBQVNSLFVBQVVyRyxHQUFWLEVBQVQsQ0FBSCxFQUE2QjtBQUN6QjBHLGVBQU90SCxXQUFQLENBQW1CLFVBQW5CO0FBQ0FzSCxlQUFPbkgsUUFBUCxDQUFnQixVQUFoQjtBQUNBbUgsZUFBT0UsR0FBUCxDQUFXLE9BQVgsRUFBbUIsU0FBbkI7QUFDSCxLQUpELE1BSUs7QUFDREYsZUFBT3RILFdBQVAsQ0FBbUIsVUFBbkI7QUFDQXNILGVBQU9uSCxRQUFQLENBQWdCLFVBQWhCO0FBQ0FtSCxlQUFPRSxHQUFQLENBQVcsT0FBWCxFQUFtQixTQUFuQjtBQUNIOztBQUVELFFBQUdQLFVBQVVyRyxHQUFWLE9BQW9Cc0csVUFBVXRHLEdBQVYsRUFBcEIsSUFBdUNxRyxVQUFVckcsR0FBVixPQUFvQixFQUE5RCxFQUFpRTtBQUM3RDJHLHNCQUFjdkgsV0FBZCxDQUEwQixVQUExQjtBQUNBdUgsc0JBQWNwSCxRQUFkLENBQXVCLFVBQXZCO0FBQ0FvSCxzQkFBY0MsR0FBZCxDQUFrQixPQUFsQixFQUEwQixTQUExQjtBQUNILEtBSkQsTUFJSztBQUNERCxzQkFBY3ZILFdBQWQsQ0FBMEIsVUFBMUI7QUFDQXVILHNCQUFjcEgsUUFBZCxDQUF1QixVQUF2QjtBQUNBb0gsc0JBQWNDLEdBQWQsQ0FBa0IsT0FBbEIsRUFBMEIsU0FBMUI7QUFDSDtBQUNKLENBbkVELEU7Ozs7Ozs7Ozs7Ozs7QUNBQSx5Q0FBQWxJLEVBQUVDLFFBQUYsRUFBWUMsS0FBWixDQUFrQixZQUFXO0FBQ3pCLFFBQUlrSSxTQUFTcEksRUFBRSxpQkFBRixDQUFiOztBQUVBLFFBQUlvSSxPQUFPOUYsTUFBUCxHQUFnQixDQUFwQixFQUF1QjtBQUNuQixZQUFJK0YsVUFBVUQsT0FBT2hILElBQVAsQ0FBWSxnQkFBWixDQUFkO0FBQ0FpSCxrQkFBVSxNQUFNQSxPQUFOLEdBQWdCLEdBQTFCO0FBQ0EsWUFBSUMsUUFBUSxJQUFJZCxNQUFKLENBQVdhLE9BQVgsRUFBbUIsSUFBbkIsQ0FBWjtBQUNBLFlBQUlFLGFBQWFILE9BQU96RyxJQUFQLEVBQWpCOztBQUVBNEcscUJBQWFBLFdBQVdwRCxPQUFYLENBQW1CbUQsS0FBbkIsRUFBMEIsV0FBMUIsQ0FBYjtBQUNBRixlQUFPekcsSUFBUCxDQUFZNEcsVUFBWjtBQUNIO0FBQ0osQ0FaRCxFOzs7Ozs7Ozs7Ozs7O0FDQUEsa0RBQVNDLGFBQVQsQ0FBdUJDLG1CQUF2QixFQUE0Q0MsMEJBQTVDLEVBQXdFOztBQUVwRTtBQUNBLFFBQUlDLG9CQUFvQkQsMkJBQTJCN0csSUFBM0IsQ0FBaUMsYUFBakMsQ0FBeEI7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsUUFBSStHLGVBQWU1SSxFQUFFLHVEQUFGLENBQW5CO0FBQ0EsUUFBSTZJLGlCQUFpQjdJLEVBQUUsMkRBQUYsQ0FBckI7O0FBRUE7QUFDQTBJLCtCQUEyQkksT0FBM0IsQ0FBbUNELGNBQW5DO0FBQ0FILCtCQUEyQkksT0FBM0IsQ0FBbUMsS0FBbkM7QUFDQUosK0JBQTJCSSxPQUEzQixDQUFtQ0YsWUFBbkM7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0FILHdCQUFvQnhILE1BQXBCLENBQTJCLFlBQVk7QUFDbkM7QUFDQSxZQUFJOEgsUUFBUS9JLEVBQUUsSUFBRixFQUFRc0IsR0FBUixFQUFaOztBQUVBO0FBQ0EwSCx3QkFBZ0JELEtBQWhCO0FBQ0gsS0FORDs7QUFRQSxhQUFTQyxlQUFULENBQXlCRCxLQUF6QixFQUFnQztBQUM1QixZQUFJLE9BQU9BLEtBQVgsRUFBa0I7QUFDZEosOEJBQWtCMUUsSUFBbEI7QUFDSCxTQUZELE1BRU87QUFDSDtBQUNBMEUsOEJBQWtCeEksSUFBbEI7O0FBRUE7QUFDQXdJLDhCQUFrQnpGLElBQWxCLENBQXVCLFlBQVk7QUFDL0Isb0JBQUkrRixjQUFjakosRUFBRyxJQUFILEVBQVU2QixJQUFWLENBQWdCLFdBQWhCLEVBQThCVCxJQUE5QixDQUFtQyxPQUFuQyxDQUFsQjs7QUFFQSxvQkFBSTZILGdCQUFnQkYsS0FBcEIsRUFBMkI7QUFDdkIvSSxzQkFBRSxJQUFGLEVBQVFpRSxJQUFSO0FBQ0g7QUFDSixhQU5EO0FBT0g7QUFDSjs7QUFFRDtBQUNBMkUsaUJBQWE3RyxLQUFiLENBQW1CLFVBQVVDLENBQVYsRUFBYTtBQUM1QkEsVUFBRUMsY0FBRjtBQUNBLFlBQUlpSCxnQkFBZ0JULG9CQUFvQm5ILEdBQXBCLEVBQXBCOztBQUVBLFlBQUksT0FBTzRILGFBQVgsRUFBMEI7QUFDdEJDO0FBQ0gsU0FGRCxNQUVPO0FBQ0hDLDBCQUFjRixhQUFkO0FBQ0g7QUFDSixLQVREOztBQVdBO0FBQ0FMLG1CQUFlOUcsS0FBZixDQUFxQixVQUFVQyxDQUFWLEVBQWE7QUFDOUJBLFVBQUVDLGNBQUY7QUFDQSxZQUFJaUgsZ0JBQWdCVCxvQkFBb0JuSCxHQUFwQixFQUFwQjs7QUFFQSxZQUFJLE9BQU80SCxhQUFYLEVBQTBCO0FBQ3RCRztBQUNILFNBRkQsTUFFTztBQUNIQyw0QkFBZ0JKLGFBQWhCO0FBQ0g7QUFDSixLQVREOztBQVdBO0FBQ0E7QUFDQTs7QUFFQSxhQUFTRSxhQUFULENBQXVCRixhQUF2QixFQUFzQztBQUNsQ1AsMEJBQWtCekYsSUFBbEIsQ0FBdUIsWUFBWTtBQUMvQixnQkFBSStGLGNBQWNqSixFQUFFLElBQUYsRUFBUTZCLElBQVIsQ0FBYyxnQkFBZCxFQUFpQ1QsSUFBakMsQ0FBc0MsT0FBdEMsQ0FBbEI7O0FBRUEsZ0JBQUk2SCxnQkFBZ0JDLGFBQXBCLEVBQW1DO0FBQy9CbEosa0JBQUUsSUFBRixFQUFRNkIsSUFBUixDQUFhLGdCQUFiLEVBQStCcUMsSUFBL0IsQ0FBb0MsU0FBcEMsRUFBK0MsSUFBL0M7QUFDSDtBQUNKLFNBTkQ7QUFPSDs7QUFFRCxhQUFTb0YsZUFBVCxDQUF5QkosYUFBekIsRUFBd0M7QUFDcENQLDBCQUFrQnpGLElBQWxCLENBQXVCLFlBQVk7QUFDL0IsZ0JBQUkrRixjQUFjakosRUFBRSxJQUFGLEVBQVE2QixJQUFSLENBQWMsZ0JBQWQsRUFBaUNULElBQWpDLENBQXNDLE9BQXRDLENBQWxCOztBQUVBLGdCQUFJNkgsZ0JBQWdCQyxhQUFwQixFQUFtQztBQUMvQmxKLGtCQUFFLElBQUYsRUFBUTZCLElBQVIsQ0FBYSxnQkFBYixFQUErQnFDLElBQS9CLENBQW9DLFNBQXBDLEVBQStDLEtBQS9DO0FBQ0g7QUFDSixTQU5EO0FBT0g7O0FBRUQsYUFBU2lGLFFBQVQsR0FBb0I7QUFDaEJSLDBCQUFrQnpGLElBQWxCLENBQXVCLFlBQVk7QUFDL0JsRCxjQUFFLElBQUYsRUFBUTZCLElBQVIsQ0FBYSxnQkFBYixFQUErQnFDLElBQS9CLENBQW9DLFNBQXBDLEVBQStDLElBQS9DO0FBQ0gsU0FGRDtBQUdIOztBQUVELGFBQVNtRixVQUFULEdBQXNCO0FBQ2xCViwwQkFBa0J6RixJQUFsQixDQUF1QixZQUFZO0FBQy9CbEQsY0FBRSxJQUFGLEVBQVE2QixJQUFSLENBQWEsZ0JBQWIsRUFBK0JxQyxJQUEvQixDQUFvQyxTQUFwQyxFQUErQyxLQUEvQztBQUNILFNBRkQ7QUFHSDtBQUNKOztBQUVEbEUsRUFBRSxZQUFXO0FBQ1R3SSxrQkFBY3hJLEVBQUcsNkJBQUgsQ0FBZCxFQUFrREEsRUFBRyw4QkFBSCxDQUFsRDtBQUNBd0ksa0JBQWN4SSxFQUFHLHVDQUFILENBQWQsRUFBNERBLEVBQUcsd0NBQUgsQ0FBNUQ7QUFDSCxDQUhELEU7Ozs7Ozs7Ozs7Ozs7QUM5R0E7QUFDQSxTQUFTbUosUUFBVCxDQUFrQkksU0FBbEIsRUFBNkI7QUFDekJ2SixNQUFFLHlCQUF5QnVKLFNBQXpCLEdBQXFDLGtCQUF2QyxFQUEyRHJGLElBQTNELENBQWdFLFNBQWhFLEVBQTJFLElBQTNFO0FBQ0g7O0FBRUQ7QUFDQSxTQUFTbUYsVUFBVCxDQUFvQkUsU0FBcEIsRUFBK0I7QUFDM0J2SixNQUFFLDhCQUE4QnVKLFNBQTlCLEdBQTBDLEdBQTVDLEVBQWlEckYsSUFBakQsQ0FBc0QsU0FBdEQsRUFBaUUsS0FBakU7QUFDSDs7QUFFRDtBQUNBbEUsRUFBRUMsUUFBRixFQUFZQyxLQUFaLENBQWtCLFlBQVc7QUFDekJGLE1BQUUsb0JBQUYsRUFBd0JrRSxJQUF4QixDQUE2QixTQUE3QixFQUF3QyxLQUF4QztBQUNILENBRkQ7O0FBSUE7OztBQUdBLHFCOzs7Ozs7Ozs7Ozs7O0FDbEJBLDZDQUFJN0QsUUFBUSxtQkFBQW1KLENBQVEscUNBQVIsQ0FBWjs7QUFFQXhKLEVBQUVDLFFBQUYsRUFBWUMsS0FBWixDQUFrQixZQUFVO0FBQ3hCLFFBQUl1SixhQUFhLEtBQWpCO0FBQ0EsUUFBSUMsU0FBUzFKLEVBQUUsb0JBQUYsQ0FBYjtBQUNBLFFBQUkySixPQUFPM0osRUFBRSxrQkFBRixDQUFYOztBQUVBMEosV0FBT3BDLEtBQVAsQ0FBYSxZQUFXO0FBQ3BCc0MsZ0JBQVFDLFlBQVIsQ0FBcUIsRUFBckIsRUFBeUIsRUFBekIsRUFBNkI3QyxRQUFRQyxRQUFSLENBQWlCLFlBQWpCLEVBQStCLEVBQUU2QyxHQUFHSixPQUFPcEksR0FBUCxFQUFMLEVBQW1CeUksR0FBRyxDQUF0QixFQUEvQixDQUE3Qjs7QUFFQTFKLGNBQU0sWUFBVTtBQUNaTCxjQUFFdUIsSUFBRixDQUFPO0FBQ0hFLHNCQUFNLEtBREg7QUFFSEQscUJBQUt3RixRQUFRQyxRQUFSLENBQWlCLGlCQUFqQixFQUFvQyxFQUFFNkMsR0FBR0osT0FBT3BJLEdBQVAsRUFBTCxFQUFtQnlJLEdBQUcsQ0FBdEIsRUFBcEMsQ0FGRjtBQUdIdkcsMEJBQVUsTUFIUDtBQUlIbkQsdUJBQU8sR0FKSjtBQUtIMkosNEJBQVksc0JBQVc7QUFDbkIsd0JBQUlQLFVBQUosRUFBZ0I7QUFDWiwrQkFBTyxLQUFQO0FBQ0gscUJBRkQsTUFFTztBQUNIQSxxQ0FBYSxJQUFiO0FBQ0g7QUFDSixpQkFYRTtBQVlIL0gseUJBQVMsaUJBQVVDLElBQVYsRUFBZ0I7QUFDckIzQixzQkFBRSxZQUFGLEVBQWdCNEIsV0FBaEIsQ0FBNEJELElBQTVCO0FBQ0E4SCxpQ0FBYSxLQUFiO0FBQ0g7QUFmRSxhQUFQO0FBaUJILFNBbEJELEVBa0JHLEdBbEJIO0FBbUJILEtBdEJEO0FBdUJILENBNUJELEUiLCJmaWxlIjoiYXBwLjZkYWQ3NDIxOGZjYzNjM2RiNDlkLmpzIiwic291cmNlc0NvbnRlbnQiOlsiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwiYXV0by1kaXNtaXNzXCJdJykuaGlkZSgpO1xuICAgICQoJ1tkYXRhLXRvZ2dsZT1cImF1dG8tZGlzbWlzc1wiXScpLmZhZGVJbihcImxvd1wiKTtcbiAgICAkKCdbZGF0YS10b2dnbGU9XCJhdXRvLWRpc21pc3NcIl0nKS5kZWxheSgnNTAwMCcpLmZhZGVPdXQoXCJsb3dcIik7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9hdXRvLWRpc21pc3MtYWxlcnQuanMiLCIkKHdpbmRvdykub24oJ2FjdGl2YXRlLmJzLnNjcm9sbHNweScsIGZ1bmN0aW9uICgpIHtcbiAgICAvLyBSZW1vdmUgYWxsIGRpc3BsYXkgY2xhc3NcbiAgICB2YXIgJGFsbExpID0gJCgnbmF2I2JsYXN0LXNjcm9sbHNweSBuYXYgYS5hY3RpdmUgKyBuYXYgYScpO1xuICAgICRhbGxMaS5yZW1vdmVDbGFzcygnZGlzcGxheScpO1xuXG4gICAgLy8gQWRkIGRpc3BsYXkgY2xhc3Mgb24gMiBiZWZvcmUgYW5kIDIgYWZ0ZXJcbiAgICB2YXIgJGFjdGl2ZUxpID0gJCgnbmF2I2JsYXN0LXNjcm9sbHNweSBuYXYgYS5hY3RpdmUgKyBuYXYgYS5hY3RpdmUnKTtcbiAgICAkYWN0aXZlTGkucHJldigpLmFkZENsYXNzKCdkaXNwbGF5Jyk7XG4gICAgJGFjdGl2ZUxpLnByZXYoKS5wcmV2KCkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbiAgICAkYWN0aXZlTGkubmV4dCgpLmFkZENsYXNzKCdkaXNwbGF5Jyk7XG4gICAgJGFjdGl2ZUxpLm5leHQoKS5uZXh0KCkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcblxuICAgIC8vIEFkZCBkaXNwbGF5IG9uIHRoZSBmaXJzdCBhbmQgMm5kXG4gICAgJGFsbExpLmVxKDApLmFkZENsYXNzKCdkaXNwbGF5Jyk7XG4gICAgJGFsbExpLmVxKDEpLmFkZENsYXNzKCdkaXNwbGF5Jyk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9ibGFzdC1zY3JvbGxzcHkuanMiLCIkKCBkb2N1bWVudCApLnJlYWR5KGZ1bmN0aW9uICgpIHtcbiAgICB2YXIgJHRvb2wgPSAkKCcjYmxhc3RfdG9vbCcpO1xuXG4gICAgLy8gV2hlbiBnZW51cyBnZXRzIHNlbGVjdGVkIC4uLlxuICAgICR0b29sLmNoYW5nZShmdW5jdGlvbiAoKSB7XG4gICAgICAgIC8vIC4uLiByZXRyaWV2ZSB0aGUgY29ycmVzcG9uZGluZyBmb3JtLlxuICAgICAgICB2YXIgJGZvcm0gPSAkKHRoaXMpLmNsb3Nlc3QoJ2Zvcm0nKTtcbiAgICAgICAgLy8gU2ltdWxhdGUgZm9ybSBkYXRhLCBidXQgb25seSBpbmNsdWRlIHRoZSBzZWxlY3RlZCBnZW51cyB2YWx1ZS5cbiAgICAgICAgdmFyIGRhdGEgPSB7fTtcbiAgICAgICAgZGF0YVskdG9vbC5hdHRyKCduYW1lJyldID0gJHRvb2wudmFsKCk7XG5cbiAgICAgICAgLy8gU3VibWl0IGRhdGEgdmlhIEFKQVggdG8gdGhlIGZvcm0ncyBhY3Rpb24gcGF0aC5cbiAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgIHVybDogJGZvcm0uYXR0cignYWN0aW9uJyksXG4gICAgICAgICAgICB0eXBlOiAkZm9ybS5hdHRyKCdtZXRob2QnKSxcbiAgICAgICAgICAgIGRhdGE6IGRhdGEsXG4gICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICAgICAgICAgIC8vIFJlcGxhY2UgY3VycmVudCBwb3NpdGlvbiBmaWVsZCAuLi5cbiAgICAgICAgICAgICAgICAkKCdzZWxlY3QjYmxhc3RfZGF0YWJhc2UnKS5yZXBsYWNlV2l0aChcbiAgICAgICAgICAgICAgICAgICAgLy8gLi4uIHdpdGggdGhlIHJldHVybmVkIG9uZSBmcm9tIHRoZSBBSkFYIHJlc3BvbnNlLlxuICAgICAgICAgICAgICAgICAgICAkKGh0bWwpLmZpbmQoJ3NlbGVjdCNibGFzdF9kYXRhYmFzZScpXG4gICAgICAgICAgICAgICAgKTtcbiAgICAgICAgICAgICAgICAkKCdzZWxlY3QjYmxhc3RfbWF0cml4JykucmVwbGFjZVdpdGgoXG4gICAgICAgICAgICAgICAgICAgIC8vIC4uLiB3aXRoIHRoZSByZXR1cm5lZCBvbmUgZnJvbSB0aGUgQUpBWCByZXNwb25zZS5cbiAgICAgICAgICAgICAgICAgICAgJChodG1sKS5maW5kKCdzZWxlY3QjYmxhc3RfbWF0cml4JylcbiAgICAgICAgICAgICAgICApO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9KTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2JsYXN0LXNlbGVjdC1jaGFuZ2UuanMiLCIkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcbiAgICB2YXIgJGNhcnRCYWRnZSA9ICQoJ2EjY2FydCBzcGFuLmJhZGdlJyk7XG5cbiAgICAkKCdhLmNhcnQtYWRkLWJ0bicpLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgJHVybCA9ICQodGhpcykuYXR0cignaHJlZicpO1xuXG4gICAgICAgICQuZ2V0KCAkdXJsLCBmdW5jdGlvbiggZGF0YSApIHtcbiAgICAgICAgICAgIC8vIENvdW50IG9iamVjdHMgaW4gZGF0YVxuICAgICAgICAgICAgdmFyICRuYkl0ZW1zID0gZGF0YS5pdGVtcy5sZW5ndGg7XG4gICAgICAgICAgICAkY2FydEJhZGdlLnRleHQoJG5iSXRlbXMpO1xuXG4gICAgICAgICAgICAvLyBpZiByZWFjaGVkIGxpbWl0XG4gICAgICAgICAgICBpZiAodHJ1ZSA9PT0gZGF0YS5yZWFjaGVkX2xpbWl0KSB7XG4gICAgICAgICAgICAgICAgbG9jYXRpb24ucmVsb2FkKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0pO1xuXG4gICAgJCgnYS5jYXJ0LXJlbW92ZS1idG4nKS5jbGljayhmdW5jdGlvbihlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgdmFyICR1cmwgPSAkKHRoaXMpLmF0dHIoJ2hyZWYnKTtcbiAgICAgICAgdmFyICR0YWJsZVJvdyA9ICQodGhpcykuY2xvc2VzdCgndHInKTtcblxuICAgICAgICAkLmdldCggJHVybCwgZnVuY3Rpb24oIGRhdGEgKSB7XG4gICAgICAgICAgICAvLyBDb3VudCBvYmplY3RzIGluIGRhdGFcbiAgICAgICAgICAgIHZhciAkbmJJdGVtcyA9IGRhdGEuaXRlbXMubGVuZ3RoO1xuICAgICAgICAgICAgJGNhcnRCYWRnZS50ZXh0KCRuYkl0ZW1zKTtcblxuICAgICAgICAgICAgLy8gUmVtb3ZlIHRoZSBsaW5lIGluIHRoZSBwYWdlXG4gICAgICAgICAgICAkdGFibGVSb3cucmVtb3ZlKCk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY2FydC1idG4uanMiLCJmdW5jdGlvbiBnZW5lcmF0ZUNhcnRGYXN0YSh0ZXh0YXJlYUlkLCBtb2RhbElkKSB7XG4gICAgdmFyICRtb2RhbCA9ICQobW9kYWxJZCk7XG4gICAgdmFyICRmb3JtID0gJG1vZGFsLmZpbmQoJ2Zvcm0nKTtcblxuICAgIHZhciAkdmFsdWVzID0ge307XG5cbiAgICAkLmVhY2goICRmb3JtWzBdLmVsZW1lbnRzLCBmdW5jdGlvbihpLCBmaWVsZCkge1xuICAgICAgICAkdmFsdWVzW2ZpZWxkLm5hbWVdID0gZmllbGQudmFsdWU7XG4gICAgfSk7XG5cbiAgICAkLmFqYXgoe1xuICAgICAgICB0eXBlOiAgICAgICAkZm9ybS5hdHRyKCdtZXRob2QnKSxcbiAgICAgICAgdXJsOiAgICAgICAgJGZvcm0uYXR0cignYWN0aW9uJyksXG4gICAgICAgIGRhdGFUeXBlOiAgICd0ZXh0JyxcbiAgICAgICAgZGF0YTogICAgICAgJHZhbHVlcyxcbiAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICQobW9kYWxJZCkubW9kYWwoJ2hpZGUnKTtcbiAgICAgICAgICAgICQodGV4dGFyZWFJZCkudmFsKGRhdGEpO1xuICAgICAgICB9XG4gICAgfSk7XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY2FydC1mYXN0YS5qcyIsImZ1bmN0aW9uIHNob3dIaWRlQ2FydFNldHVwKCkge1xuICAgIHZhciAkdHlwZSA9ICQoJ3NlbGVjdFtpZCQ9XFwnY2FydF90eXBlXFwnXScpO1xuICAgIHZhciAkZmVhdHVyZSA9ICQoJ3NlbGVjdFtpZCQ9XFwnY2FydF9mZWF0dXJlXFwnXScpO1xuICAgIHZhciAkaW50cm9uU3BsaWNpbmcgPSAkKCdzZWxlY3RbaWQkPVxcJ2NhcnRfaW50cm9uU3BsaWNpbmdcXCddJyk7XG4gICAgdmFyICR1cHN0cmVhbSA9ICQoJ2lucHV0W2lkJD1cXCdjYXJ0X3Vwc3RyZWFtXFwnXScpO1xuICAgIHZhciAkZG93bnN0cmVhbSA9ICQoJ2lucHV0W2lkJD1cXCdjYXJ0X2Rvd25zdHJlYW1cXCddJyk7XG4gICAgdmFyICRzZXR1cCA9ICRmZWF0dXJlLmNsb3Nlc3QoJyNjYXJ0LXNldHVwJyk7XG5cbiAgICBpZiAoJ3Byb3QnID09PSAkdHlwZS52YWwoKSkge1xuICAgICAgICAkc2V0dXAuaGlkZSgpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgICRzZXR1cC5zaG93KCk7XG4gICAgfVxuXG4gICAgaWYgKCdsb2N1cycgPT09ICRmZWF0dXJlLnZhbCgpKSB7XG4gICAgICAgICRpbnRyb25TcGxpY2luZy52YWwoMCk7XG4gICAgICAgICRpbnRyb25TcGxpY2luZy5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgICRpbnRyb25TcGxpY2luZy5wcm9wKCdkaXNhYmxlZCcsIGZhbHNlKTtcbiAgICB9XG5cbiAgICBpZiAoJzEnID09PSAkaW50cm9uU3BsaWNpbmcudmFsKCkpIHtcbiAgICAgICAgJHVwc3RyZWFtLmNsb3Nlc3QoJ2Rpdi5mb3JtLWdyb3VwJykuaGlkZSgpO1xuICAgICAgICAkZG93bnN0cmVhbS5jbG9zZXN0KCdkaXYuZm9ybS1ncm91cCcpLmhpZGUoKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICAkdXBzdHJlYW0uY2xvc2VzdCgnZGl2LmZvcm0tZ3JvdXAnKS5zaG93KCk7XG4gICAgICAgICRkb3duc3RyZWFtLmNsb3Nlc3QoJ2Rpdi5mb3JtLWdyb3VwJykuc2hvdygpO1xuICAgIH1cblxuICAgICR0eXBlLmNoYW5nZShmdW5jdGlvbigpIHtcbiAgICAgICAgc2hvd0hpZGVDYXJ0U2V0dXAoKTtcbiAgICB9KTtcblxuICAgICRmZWF0dXJlLmNoYW5nZShmdW5jdGlvbigpIHtcbiAgICAgICAgc2hvd0hpZGVDYXJ0U2V0dXAoKTtcbiAgICB9KTtcblxuICAgICRpbnRyb25TcGxpY2luZy5jaGFuZ2UoZnVuY3Rpb24oKSB7XG4gICAgICAgIHNob3dIaWRlQ2FydFNldHVwKCk7XG4gICAgfSk7XG59XG5cbnNob3dIaWRlQ2FydFNldHVwKCk7XG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NhcnQtZm9ybS5qcyIsImZ1bmN0aW9uIGNvbGxlY3Rpb25UeXBlKGNvbnRhaW5lciwgYnV0dG9uVGV4dCwgYnV0dG9uSWQsIGZpZWxkU3RhcnQsIGZ1bmN0aW9ucykge1xuICAgIGlmIChidXR0b25JZCA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGJ1dHRvbklkID0gbnVsbDtcbiAgICB9XG5cbiAgICBpZiAoZmllbGRTdGFydCA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIGZpZWxkU3RhcnQgPSBmYWxzZTtcbiAgICB9XG5cbiAgICBpZiAoZnVuY3Rpb25zID09PSB1bmRlZmluZWQpIHtcbiAgICAgICAgZnVuY3Rpb25zID0gW107XG4gICAgfVxuXG4gICAgLy8gRGVsZXRlIHRoZSBmaXJzdCBsYWJlbCAodGhlIG51bWJlciBvZiB0aGUgZmllbGQpLCBhbmQgdGhlIHJlcXVpcmVkIGNsYXNzXG4gICAgY29udGFpbmVyLmNoaWxkcmVuKCdkaXYnKS5maW5kKCdsYWJlbDpmaXJzdCcpLnRleHQoJycpO1xuICAgIGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykuZmluZCgnbGFiZWw6Zmlyc3QnKS5yZW1vdmVDbGFzcygncmVxdWlyZWQnKTtcbiAgICBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmZpbmQoJ2xhYmVsOmZpcnN0JykucmVtb3ZlQ2xhc3MoJ3JlcXVpcmVkJyk7XG5cbiAgICAvLyBDcmVhdGUgYW5kIGFkZCBhIGJ1dHRvbiB0byBhZGQgbmV3IGZpZWxkXG4gICAgaWYgKGJ1dHRvbklkKSB7XG4gICAgICAgIHZhciBpZCA9IFwiaWQ9J1wiICsgYnV0dG9uSWQgKyBcIidcIjtcbiAgICAgICAgdmFyICRhZGRCdXR0b24gPSAkKCc8YSBocmVmPVwiI1wiICcgKyBpZCArICdjbGFzcz1cImJ0biBidG4tZGVmYXVsdCBidG4teHNcIj48c3BhbiBjbGFzcz1cImZhIGZhLXBsdXMgYXJpYS1oaWRkZW49XCJ0cnVlXCJcIj48L3NwYW4+ICcrYnV0dG9uVGV4dCsnPC9hPicpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgIHZhciAkYWRkQnV0dG9uID0gJCgnPGEgaHJlZj1cIiNcIiBjbGFzcz1cImJ0biBidG4tZGVmYXVsdCBidG4teHNcIj48c3BhbiBjbGFzcz1cImZhIGZhLXBsdXMgYXJpYS1oaWRkZW49XCJ0cnVlXCJcIj48L3NwYW4+ICcrYnV0dG9uVGV4dCsnPC9hPicpO1xuICAgIH1cblxuICAgIGNvbnRhaW5lci5hcHBlbmQoJGFkZEJ1dHRvbik7XG5cbiAgICAvLyBBZGQgYSBjbGljayBldmVudCBvbiB0aGUgYWRkIGJ1dHRvblxuICAgICRhZGRCdXR0b24uY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIC8vIENhbGwgdGhlIGFkZEZpZWxkIG1ldGhvZFxuICAgICAgICBhZGRGaWVsZChjb250YWluZXIpO1xuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfSk7XG5cbiAgICAvLyBEZWZpbmUgYW4gaW5kZXggdG8gY291bnQgdGhlIG51bWJlciBvZiBhZGRlZCBmaWVsZCAodXNlZCB0byBnaXZlIG5hbWUgdG8gZmllbGRzKVxuICAgIHZhciBpbmRleCA9IGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykubGVuZ3RoO1xuXG4gICAgLy8gSWYgdGhlIGluZGV4IGlzID4gMCwgZmllbGRzIGFscmVhZHkgZXhpc3RzLCB0aGVuLCBhZGQgYSBkZWxldGVCdXR0b24gdG8gdGhpcyBmaWVsZHNcbiAgICBpZiAoaW5kZXggPiAwKSB7XG4gICAgICAgIGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykuZWFjaChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIGFkZERlbGV0ZUJ1dHRvbigkKHRoaXMpKTtcbiAgICAgICAgICAgIGFkZEZ1bmN0aW9ucygkKHRoaXMpKTtcbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgLy8gSWYgd2Ugd2FudCB0byBoYXZlIGEgZmllbGQgYXQgc3RhcnRcbiAgICBpZiAodHJ1ZSA9PSBmaWVsZFN0YXJ0ICYmIDAgPT0gaW5kZXgpIHtcbiAgICAgICAgYWRkRmllbGQoY29udGFpbmVyKTtcbiAgICB9XG5cbiAgICAvLyBUaGUgYWRkRmllbGQgZnVuY3Rpb25cbiAgICBmdW5jdGlvbiBhZGRGaWVsZChjb250YWluZXIpIHtcbiAgICAgICAgLy8gUmVwbGFjZSBzb21lIHZhbHVlIGluIHRoZSDCqyBkYXRhLXByb3RvdHlwZSDCu1xuICAgICAgICAvLyAtIFwiX19uYW1lX19sYWJlbF9fXCIgYnkgdGhlIG5hbWUgd2Ugd2FudCB0byB1c2UsIGhlcmUgbm90aGluZ1xuICAgICAgICAvLyAtIFwiX19uYW1lX19cIiBieSB0aGUgbmFtZSBvZiB0aGUgZmllbGQsIGhlcmUgdGhlIGluZGV4IG51bWJlclxuICAgICAgICB2YXIgJHByb3RvdHlwZSA9ICQoY29udGFpbmVyLmF0dHIoJ2RhdGEtcHJvdG90eXBlJylcbiAgICAgICAgICAgIC5yZXBsYWNlKC9jbGFzcz1cImNvbC1zbS0yIGNvbnRyb2wtbGFiZWwgcmVxdWlyZWRcIi8sICdjbGFzcz1cImNvbC1zbS0yIGNvbnRyb2wtbGFiZWxcIicpXG4gICAgICAgICAgICAucmVwbGFjZSgvX19uYW1lX19sYWJlbF9fL2csICcnKVxuICAgICAgICAgICAgLnJlcGxhY2UoL19fbmFtZV9fL2csIGluZGV4KSk7XG5cbiAgICAgICAgLy8gQWRkIGEgZGVsZXRlIGJ1dHRvbiB0byB0aGUgbmV3IGZpZWxkXG4gICAgICAgIGFkZERlbGV0ZUJ1dHRvbigkcHJvdG90eXBlKTtcblxuICAgICAgICAvLyBJZiB0aGVyZSBhcmUgc3VwcGxlbWVudGFyeSBmdW5jdGlvbnNcbiAgICAgICAgYWRkRnVuY3Rpb25zKCRwcm90b3R5cGUpO1xuXG4gICAgICAgIC8vIEFkZCB0aGUgZmllbGQgaW4gdGhlIGZvcm1cbiAgICAgICAgJGFkZEJ1dHRvbi5iZWZvcmUoJHByb3RvdHlwZSk7XG5cbiAgICAgICAgLy8gSW5jcmVtZW50IHRoZSBjb3VudGVyXG4gICAgICAgIGluZGV4Kys7XG4gICAgfVxuXG4gICAgLy8gQSBmdW5jdGlvbiBjYWxsZWQgdG8gYWRkIGRlbGV0ZUJ1dHRvblxuICAgIGZ1bmN0aW9uIGFkZERlbGV0ZUJ1dHRvbihwcm90b3R5cGUpIHtcbiAgICAgICAgLy8gRmlyc3QsIGNyZWF0ZSB0aGUgYnV0dG9uXG4gICAgICAgIHZhciAkZGVsZXRlQnV0dG9uID0gJCgnPGRpdiBjbGFzcz1cImNvbC1zbS0xXCI+PGEgaHJlZj1cIiNcIiBjbGFzcz1cImJ0biBidG4tZGFuZ2VyIGJ0bi1zbVwiPjxzcGFuIGNsYXNzPVwiZmEgZmEtdHJhc2hcIiBhcmlhLWhpZGRlbj1cInRydWVcIj48L3NwYW4+PC9hPjwvZGl2PicpO1xuXG4gICAgICAgIC8vIEFkZCB0aGUgYnV0dG9uIG9uIHRoZSBmaWVsZFxuICAgICAgICAkKCcuY29sLXNtLTEwJywgcHJvdG90eXBlKS5yZW1vdmVDbGFzcygnY29sLXNtLTEwJykuYWRkQ2xhc3MoJ2NvbC1zbS05Jyk7XG4gICAgICAgIHByb3RvdHlwZS5hcHBlbmQoJGRlbGV0ZUJ1dHRvbik7XG5cbiAgICAgICAgLy8gQ3JlYXRlIGEgbGlzdGVuZXIgb24gdGhlIGNsaWNrIGV2ZW50XG4gICAgICAgICRkZWxldGVCdXR0b24uY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgLy8gUmVtb3ZlIHRoZSBmaWVsZFxuICAgICAgICAgICAgcHJvdG90eXBlLnJlbW92ZSgpO1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBhZGRGdW5jdGlvbnMocHJvdG90eXBlKSB7XG4gICAgICAgIC8vIElmIHRoZXJlIGFyZSBzdXBwbGVtZW50YXJ5IGZ1bmN0aW9uc1xuICAgICAgICBpZiAoZnVuY3Rpb25zLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIC8vIERvIGEgd2hpbGUgb24gZnVuY3Rpb25zLCBhbmQgYXBwbHkgdGhlbSB0byB0aGUgcHJvdG90eXBlXG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgZnVuY3Rpb25zLmxlbmd0aCA+IGk7IGkrKykge1xuICAgICAgICAgICAgICAgIGZ1bmN0aW9uc1tpXShwcm90b3R5cGUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfVxufVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NvbGxlY3Rpb24tdHlwZS5qcyIsImZ1bmN0aW9uIGNvcHkyY2xpcGJvYXJkKGRhdGFTZWxlY3Rvcikge1xuICAgIGRhdGFTZWxlY3Rvci5zZWxlY3QoKTtcbiAgICBkb2N1bWVudC5leGVjQ29tbWFuZCgnY29weScpO1xufVxuXG5mdW5jdGlvbiBjb3B5MmNsaXBib2FyZE9uQ2xpY2soY2xpY2tUcmlnZ2VyLCBkYXRhU2VsZWN0b3IpIHtcbiAgICBjbGlja1RyaWdnZXIuY2xpY2soZnVuY3Rpb24oKXtcbiAgICAgICAgY29weTJjbGlwYm9hcmQoZGF0YVNlbGVjdG9yKTtcbiAgICB9KTtcbn1cblxuJChmdW5jdGlvbigpIHtcbiAgIGNvcHkyY2xpcGJvYXJkT25DbGljaygkKFwiI3JldmVyc2UtY29tcGxlbWVudC1jb3B5LWJ1dHRvblwiKSwgJChcIiNyZXZlcnNlLWNvbXBsZW1lbnQtcmVzdWx0XCIpKTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2NvcHkyY2xpcGJvYXJkLmpzIiwiLy8gdmFyIGRlbGF5ID0gKGZ1bmN0aW9uKCl7XG4vLyAgICAgdmFyIHRpbWVyID0gMDtcbi8vICAgICByZXR1cm4gZnVuY3Rpb24oY2FsbGJhY2ssIG1zKXtcbi8vICAgICAgICAgY2xlYXJUaW1lb3V0ICh0aW1lcik7XG4vLyAgICAgICAgIHRpbWVyID0gc2V0VGltZW91dChjYWxsYmFjaywgbXMpO1xuLy8gICAgIH07XG4vLyB9KSgpO1xuXG5tb2R1bGUuZXhwb3J0cyA9IChmdW5jdGlvbigpIHtcbiAgICByZXR1cm4gKGZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciB0aW1lciA9IDA7XG4gICAgICAgIHJldHVybiBmdW5jdGlvbihjYWxsYmFjaywgbXMpe1xuICAgICAgICAgICAgY2xlYXJUaW1lb3V0ICh0aW1lcik7XG4gICAgICAgICAgICB0aW1lciA9IHNldFRpbWVvdXQoY2FsbGJhY2ssIG1zKTtcbiAgICAgICAgfTtcbiAgICB9KSgpO1xufSkoKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9kZWxheS5qcyIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCl7XG4gICAgJCgnZGl2LmxvY3VzLWZlYXR1cmUnKS5lYWNoKGZ1bmN0aW9uKGluZGV4KSB7XG4gICAgICAgIHZhciBsb2N1cyA9ICQoIHRoaXMgKS5kYXRhKFwibG9jdXNcIik7XG4gICAgICAgIHZhciBmZWF0dXJlID0gJCggdGhpcyApLmRhdGEoXCJmZWF0dXJlXCIpO1xuICAgICAgICB2YXIgc2VxdWVuY2VDb250YWluZXIgPSAkKCB0aGlzICkuZmluZCgnZGl2LmZhc3RhJyk7XG4gICAgICAgIHZhciBmb3JtID0gJCggdGhpcyApLmZpbmQoJ2Zvcm0nKTtcblxuICAgICAgICBmb3JtLnJlbW92ZUNsYXNzKCdoaWRkZW4nKTtcblxuICAgICAgICBmb3JtLnN1Ym1pdChmdW5jdGlvbihldmVudCkge1xuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIHZhciB1cHN0cmVhbSA9ICQoIHRoaXMgKS5wYXJlbnQoKS5maW5kKFwiaW5wdXRbbmFtZT0ndXBzdHJlYW0nXVwiKS52YWwoKTtcbiAgICAgICAgICAgIHZhciBkb3duc3RyZWFtID0gJCggdGhpcyApLnBhcmVudCgpLmZpbmQoXCJpbnB1dFtuYW1lPSdkb3duc3RyZWFtJ11cIikudmFsKCk7XG4gICAgICAgICAgICB2YXIgc2hvd1V0ciA9ICQoIHRoaXMgKS5wYXJlbnQoKS5maW5kKFwiaW5wdXRbbmFtZT0nc2hvd1V0ciddXCIpLmlzKFwiOmNoZWNrZWRcIik7XG4gICAgICAgICAgICB2YXIgc2hvd0ludHJvbiA9ICQoIHRoaXMgKS5wYXJlbnQoKS5maW5kKFwiaW5wdXRbbmFtZT0nc2hvd0ludHJvbiddXCIpLmlzKFwiOmNoZWNrZWRcIik7XG5cbiAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdHlwZTogJ0dFVCcsXG4gICAgICAgICAgICAgICAgdXJsOiBSb3V0aW5nLmdlbmVyYXRlKCdmZWF0dXJlX3NlcXVlbmNlJywgeyBsb2N1c19uYW1lOiBsb2N1cywgZmVhdHVyZV9uYW1lOiBmZWF0dXJlLCB1cHN0cmVhbTogdXBzdHJlYW0sIGRvd25zdHJlYW06IGRvd25zdHJlYW0sIHNob3dVdHI6IHNob3dVdHIsIHNob3dJbnRyb246IHNob3dJbnRyb24gfSksXG4gICAgICAgICAgICAgICAgZGF0YVR5cGU6ICdodG1sJyxcbiAgICAgICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICAgICAgICAgICAgICBzZXF1ZW5jZUNvbnRhaW5lci5maXJzdCgpLmh0bWwoaHRtbCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbGl2ZS1zZXF1ZW5jZS1kaXNwbGF5LmpzIiwiJChmdW5jdGlvbiAoKSB7XG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwidG9vbHRpcFwiXScpLnRvb2x0aXAoKVxufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvbG9jdXMtdG9vbHRpcC5qcyIsIiQoXCJpbnB1dFt0eXBlPXBhc3N3b3JkXVtpZCo9J19wbGFpblBhc3N3b3JkXyddXCIpLmtleXVwKGZ1bmN0aW9uKCl7XG4gICAgLy8gU2V0IHJlZ2V4IGNvbnRyb2xcbiAgICB2YXIgdWNhc2UgPSBuZXcgUmVnRXhwKFwiW0EtWl0rXCIpO1xuICAgIHZhciBsY2FzZSA9IG5ldyBSZWdFeHAoXCJbYS16XStcIik7XG4gICAgdmFyIG51bSA9IG5ldyBSZWdFeHAoXCJbMC05XStcIik7XG5cbiAgICAvLyBTZXQgcGFzc3dvcmQgZmllbGRzXG4gICAgdmFyIHBhc3N3b3JkMSA9ICQoXCJbaWQkPSdfcGxhaW5QYXNzd29yZF9maXJzdCddXCIpO1xuICAgIHZhciBwYXNzd29yZDIgPSAkKFwiW2lkJD0nX3BsYWluUGFzc3dvcmRfc2Vjb25kJ11cIik7XG4gICAgXG4gICAgLy8gU2V0IGRpc3BsYXkgcmVzdWx0XG4gICAgdmFyIG51bWJlckNoYXJzID0gJChcIiNudW1iZXItY2hhcnNcIik7XG4gICAgdmFyIHVwcGVyQ2FzZSA9ICQoXCIjdXBwZXItY2FzZVwiKTtcbiAgICB2YXIgbG93ZXJDYXNlID0gJChcIiNsb3dlci1jYXNlXCIpO1xuICAgIHZhciBudW1iZXIgPSAkKFwiI251bWJlclwiKTtcbiAgICB2YXIgcGFzc3dvcmRNYXRjaCA9ICQoXCIjcGFzc3dvcmQtbWF0Y2hcIik7XG5cbiAgICAvLyBEbyB0aGUgdGVzdFxuICAgIGlmKHBhc3N3b3JkMS52YWwoKS5sZW5ndGggPj0gOCl7XG4gICAgICAgIG51bWJlckNoYXJzLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIG51bWJlckNoYXJzLmFkZENsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIG51bWJlckNoYXJzLmNzcyhcImNvbG9yXCIsXCIjMDBBNDFFXCIpO1xuICAgIH1lbHNle1xuICAgICAgICBudW1iZXJDaGFycy5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBudW1iZXJDaGFycy5hZGRDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBudW1iZXJDaGFycy5jc3MoXCJjb2xvclwiLFwiI0ZGMDAwNFwiKTtcbiAgICB9XG5cbiAgICBpZih1Y2FzZS50ZXN0KHBhc3N3b3JkMS52YWwoKSkpe1xuICAgICAgICB1cHBlckNhc2UucmVtb3ZlQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgdXBwZXJDYXNlLmFkZENsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIHVwcGVyQ2FzZS5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgdXBwZXJDYXNlLnJlbW92ZUNsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIHVwcGVyQ2FzZS5hZGRDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICB1cHBlckNhc2UuY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxuXG4gICAgaWYobGNhc2UudGVzdChwYXNzd29yZDEudmFsKCkpKXtcbiAgICAgICAgbG93ZXJDYXNlLnJlbW92ZUNsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIGxvd2VyQ2FzZS5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBsb3dlckNhc2UuY3NzKFwiY29sb3JcIixcIiMwMEE0MUVcIik7XG4gICAgfWVsc2V7XG4gICAgICAgIGxvd2VyQ2FzZS5yZW1vdmVDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBsb3dlckNhc2UuYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbG93ZXJDYXNlLmNzcyhcImNvbG9yXCIsXCIjRkYwMDA0XCIpO1xuICAgIH1cblxuICAgIGlmKG51bS50ZXN0KHBhc3N3b3JkMS52YWwoKSkpe1xuICAgICAgICBudW1iZXIucmVtb3ZlQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbnVtYmVyLmFkZENsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIG51bWJlci5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgbnVtYmVyLnJlbW92ZUNsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIG51bWJlci5hZGRDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBudW1iZXIuY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxuXG4gICAgaWYocGFzc3dvcmQxLnZhbCgpID09PSBwYXNzd29yZDIudmFsKCkgJiYgcGFzc3dvcmQxLnZhbCgpICE9PSAnJyl7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2gucmVtb3ZlQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5hZGRDbGFzcyhcImZhLWNoZWNrXCIpO1xuICAgICAgICBwYXNzd29yZE1hdGNoLmNzcyhcImNvbG9yXCIsXCIjMDBBNDFFXCIpO1xuICAgIH1lbHNle1xuICAgICAgICBwYXNzd29yZE1hdGNoLnJlbW92ZUNsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2guYWRkQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5jc3MoXCJjb2xvclwiLFwiI0ZGMDAwNFwiKTtcbiAgICB9XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9wYXNzd29yZC1jb250cm9sLmpzIiwiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgdmFyIHJlc3VsdCA9ICQoJyNzZWFyY2gtcmVzdWx0cycpO1xuXG4gICAgaWYgKHJlc3VsdC5sZW5ndGggPiAwKSB7XG4gICAgICAgIHZhciBrZXl3b3JkID0gcmVzdWx0LmRhdGEoJ3NlYXJjaC1rZXl3b3JkJyk7XG4gICAgICAgIGtleXdvcmQgPSAnKCcgKyBrZXl3b3JkICsgJyknO1xuICAgICAgICB2YXIgcmVnZXggPSBuZXcgUmVnRXhwKGtleXdvcmQsXCJnaVwiKTtcbiAgICAgICAgdmFyIHJlc3VsdEh0bWwgPSByZXN1bHQuaHRtbCgpO1xuXG4gICAgICAgIHJlc3VsdEh0bWwgPSByZXN1bHRIdG1sLnJlcGxhY2UocmVnZXgsIFwiPGI+JDE8L2I+XCIpO1xuICAgICAgICByZXN1bHQuaHRtbChyZXN1bHRIdG1sKTtcbiAgICB9XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zZWFyY2gta2V5d29yZC1oaWdobGlnaHQuanMiLCJmdW5jdGlvbiBzdHJhaW5zRmlsdGVyKHN0cmFpbnNGaWx0ZXJTZWxlY3QsIHN0cmFpbnNDaGVja0JveGVzQ29udGFpbmVyKSB7XG5cbiAgICAvLyBEZWZpbmUgdmFyIHRoYXQgY29udGFpbnMgZmllbGRzXG4gICAgdmFyIHN0cmFpbnNDaGVja2JveGVzID0gc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIuZmluZCggJy5mb3JtLWNoZWNrJyApO1xuXG4gICAgLy8qKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi8vXG4gICAgLy8gIEFkZCB0aGUgbGlua3MgKGNoZWNrL3VuY2hlY2spIC8vXG4gICAgLy8qKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi8vXG5cbiAgICAvLyBEZWZpbmUgY2hlY2tBbGwvdW5jaGVja0FsbCBsaW5rc1xuICAgIHZhciBjaGVja0FsbExpbmsgPSAkKCc8YSBocmVmPVwiI1wiIGNsYXNzPVwiY2hlY2tfYWxsX3N0cmFpbnNcIiA+IENoZWNrIGFsbDwvYT4nKTtcbiAgICB2YXIgdW5jaGVja0FsbExpbmsgPSAkKCc8YSBocmVmPVwiI1wiIGNsYXNzPVwidW5jaGVja19hbGxfc3RyYWluc1wiID4gVW5jaGVjayBhbGw8L2E+Jyk7XG5cbiAgICAvLyBJbnNlcnQgdGhlIGNoZWNrL3VuY2hlY2sgbGlua3NcbiAgICBzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lci5wcmVwZW5kKHVuY2hlY2tBbGxMaW5rKTtcbiAgICBzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lci5wcmVwZW5kKCcgLyAnKTtcbiAgICBzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lci5wcmVwZW5kKGNoZWNrQWxsTGluayk7XG5cbiAgICAvLyoqKioqKioqKioqKioqKioqKioqKioqKioqKi8vXG4gICAgLy8gQ3JlYXRlIGFsbCBvbkNMaWNrIGV2ZW50cyAvL1xuICAgIC8vKioqKioqKioqKioqKioqKioqKioqKioqKioqLy9cblxuICAgIC8vIENyZWF0ZSBvbkNsaWNrIGV2ZW50IG9uIFRlYW0gZmlsdGVyXG4gICAgc3RyYWluc0ZpbHRlclNlbGVjdC5jaGFuZ2UoZnVuY3Rpb24gKCkge1xuICAgICAgICAvLyBHZXQgdGhlIGNsYWRlXG4gICAgICAgIHZhciBjbGFkZSA9ICQodGhpcykudmFsKCk7XG5cbiAgICAgICAgLy8gQ2FsbCB0aGUgZnVuY3Rpb24gYW5kIGdpdmUgdGhlIGNsYWRlXG4gICAgICAgIHNob3dIaWRlU3RyYWlucyhjbGFkZSk7XG4gICAgfSk7XG5cbiAgICBmdW5jdGlvbiBzaG93SGlkZVN0cmFpbnMoY2xhZGUpIHtcbiAgICAgICAgaWYgKCcnID09PSBjbGFkZSkge1xuICAgICAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuc2hvdygpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgLy8gSGlkZSBhbGwgU3RyYWluc1xuICAgICAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuaGlkZSgpO1xuXG4gICAgICAgICAgICAvLyBTaG93IGNsYWRlIHN0cmFpbnNcbiAgICAgICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHZhciBzdHJhaW5DbGFkZSA9ICQoIHRoaXMgKS5maW5kKCBcIjpjaGVja2JveFwiICkuZGF0YSgnY2xhZGUnKTtcblxuICAgICAgICAgICAgICAgIGlmIChzdHJhaW5DbGFkZSA9PT0gY2xhZGUpIHtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKS5zaG93KCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICAvLyBDcmVhdGUgb25DbGljayBldmVudCBvbiBjaGVja0FsbExpbmtcbiAgICBjaGVja0FsbExpbmsuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgY2xhZGVGaWx0ZXJlZCA9IHN0cmFpbnNGaWx0ZXJTZWxlY3QudmFsKCk7XG5cbiAgICAgICAgaWYgKCcnID09PSBjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgICAgICBjaGVja0FsbCgpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgY2hlY2tBbGxDbGFkZShjbGFkZUZpbHRlcmVkKTtcbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgLy8gQ3JlYXRlIG9uQ2xpY2sgZXZlbnQgb24gdW5jaGVja0FsbExpbmtcbiAgICB1bmNoZWNrQWxsTGluay5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHZhciBjbGFkZUZpbHRlcmVkID0gc3RyYWluc0ZpbHRlclNlbGVjdC52YWwoKTtcblxuICAgICAgICBpZiAoJycgPT09IGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgICAgIHVuY2hlY2tBbGwoKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHVuY2hlY2tBbGxDbGFkZShjbGFkZUZpbHRlcmVkKTtcbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgLy9cbiAgICAvLyBCYXNlIGZ1bmN0aW9uczogY2hlY2svdW5jaGVjayBhbGwgY2hlY2tib3hlcyBhbmQgY2hlY2svdW5jaGVjayBzcGVjaWZpYyBzdHJhaW5zIChwZXIgY2xhZGUpXG4gICAgLy9cblxuICAgIGZ1bmN0aW9uIGNoZWNrQWxsQ2xhZGUoY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBzdHJhaW5DbGFkZSA9ICQodGhpcykuZmluZCggXCJpbnB1dDpjaGVja2JveFwiICkuZGF0YSgnY2xhZGUnKTtcblxuICAgICAgICAgICAgaWYgKHN0cmFpbkNsYWRlID09PSBjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5maW5kKFwiaW5wdXQ6Y2hlY2tib3hcIikucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiB1bmNoZWNrQWxsQ2xhZGUoY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBzdHJhaW5DbGFkZSA9ICQodGhpcykuZmluZCggXCJpbnB1dDpjaGVja2JveFwiICkuZGF0YSgnY2xhZGUnKTtcblxuICAgICAgICAgICAgaWYgKHN0cmFpbkNsYWRlID09PSBjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5maW5kKFwiaW5wdXQ6Y2hlY2tib3hcIikucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gY2hlY2tBbGwoKSB7XG4gICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJCh0aGlzKS5maW5kKFwiaW5wdXQ6Y2hlY2tib3hcIikucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiB1bmNoZWNrQWxsKCkge1xuICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICQodGhpcykuZmluZChcImlucHV0OmNoZWNrYm94XCIpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG4gICAgICAgIH0pO1xuICAgIH1cbn1cblxuJChmdW5jdGlvbigpIHtcbiAgICBzdHJhaW5zRmlsdGVyKCQoIFwiI2JsYXN0X3N0cmFpbnNGaWx0ZXJfZmlsdGVyXCIgKSwgJCggXCIjYmxhc3Rfc3RyYWluc0ZpbHRlcl9zdHJhaW5zXCIgKSk7XG4gICAgc3RyYWluc0ZpbHRlcigkKCBcIiNhZHZhbmNlZF9zZWFyY2hfc3RyYWluc0ZpbHRlcl9maWx0ZXJcIiApLCAkKCBcIiNhZHZhbmNlZF9zZWFyY2hfc3RyYWluc0ZpbHRlcl9zdHJhaW5zXCIgKSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9zdHJhaW5zLWZpbHRlci5qcyIsIi8vIENoZWNrIGFsbCBjaGVja2JveGVzIG5vIGRpc2FibGVkXG5mdW5jdGlvbiBjaGVja0FsbChncm91cE5hbWUpIHtcbiAgICAkKFwiOmNoZWNrYm94W2RhdGEtbmFtZT1cIiArIGdyb3VwTmFtZSArIFwiXTpub3QoOmRpc2FibGVkKVwiKS5wcm9wKCdjaGVja2VkJywgdHJ1ZSk7XG59XG5cbi8vIFVuY2hlY2sgYWxsIGNoZWNrYm94ZXMgZGlzYWJsZWQgdG9vXG5mdW5jdGlvbiB1bmNoZWNrQWxsKGdyb3VwTmFtZSkge1xuICAgICQoXCJpbnB1dDpjaGVja2JveFtkYXRhLW5hbWU9XCIgKyBncm91cE5hbWUgKyBcIl1cIikucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcbn1cblxuLy8gVW5jaGVjayBhbGwgZGlzYWJsZWQgY2hlY2tib3hcbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgICQoXCI6Y2hlY2tib3g6ZGlzYWJsZWRcIikucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcbn0pO1xuXG4vLyBPbiBjaGVja0FsbCBjbGlja1xuXG5cbi8vIE9uIHVuY2hlY2tBbGxDbGlja1xuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWFkbWluLXN0cmFpbnMuanMiLCJ2YXIgZGVsYXkgPSByZXF1aXJlKCcuL2RlbGF5Jyk7XG5cbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCl7XG4gICAgdmFyIHByb2Nlc3NpbmcgPSBmYWxzZTtcbiAgICB2YXIgc2VhcmNoID0gJCgnI3VzZXItc2VhcmNoLWZpZWxkJyk7XG4gICAgdmFyIHRlYW0gPSAkKCcjdXNlci10ZWFtLWZpZWxkJyk7XG5cbiAgICBzZWFyY2gua2V5dXAoZnVuY3Rpb24oKSB7XG4gICAgICAgIGhpc3RvcnkucmVwbGFjZVN0YXRlKCcnLCAnJywgUm91dGluZy5nZW5lcmF0ZSgndXNlcl9pbmRleCcsIHsgcTogc2VhcmNoLnZhbCgpLCBwOiAxIH0pKTtcblxuICAgICAgICBkZWxheShmdW5jdGlvbigpe1xuICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICB0eXBlOiAnR0VUJyxcbiAgICAgICAgICAgICAgICB1cmw6IFJvdXRpbmcuZ2VuZXJhdGUoJ3VzZXJfaW5kZXhfYWpheCcsIHsgcTogc2VhcmNoLnZhbCgpLCBwOiAxIH0pLFxuICAgICAgICAgICAgICAgIGRhdGFUeXBlOiAnaHRtbCcsXG4gICAgICAgICAgICAgICAgZGVsYXk6IDQwMCxcbiAgICAgICAgICAgICAgICBiZWZvcmVTZW5kOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHByb2Nlc3NpbmcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHByb2Nlc3NpbmcgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoaHRtbCkge1xuICAgICAgICAgICAgICAgICAgICAkKCcjdXNlci1saXN0JykucmVwbGFjZVdpdGgoaHRtbCk7XG4gICAgICAgICAgICAgICAgICAgIHByb2Nlc3NpbmcgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSwgNDAwICk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy91c2VyLWluc3RhbnQtc2VhcmNoLmpzIl0sInNvdXJjZVJvb3QiOiIifQ==