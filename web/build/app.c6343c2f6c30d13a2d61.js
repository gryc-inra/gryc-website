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
/***/ (function(module, exports) {

throw new Error("Module build failed: SyntaxError: Unexpected token (17:22)\n\n\u001b[0m \u001b[90m 15 | \u001b[39m    \u001b[90m// On checkAll click\u001b[39m\n \u001b[90m 16 | \u001b[39m    $(\u001b[32m'.checkAllStrains'\u001b[39m)\u001b[33m.\u001b[39mclick(\u001b[36mfunction\u001b[39m() {\n\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m 17 | \u001b[39m        \u001b[36mvar\u001b[39m species \u001b[33m=\u001b[39m \u001b[33m;\u001b[39m\n \u001b[90m    | \u001b[39m                      \u001b[31m\u001b[1m^\u001b[22m\u001b[39m\n \u001b[90m 18 | \u001b[39m    })\u001b[33m;\u001b[39m\n \u001b[90m 19 | \u001b[39m\n \u001b[90m 20 | \u001b[39m    \u001b[90m// On uncheckAllClick\u001b[39m\u001b[0m\n");

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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYXV0by1kaXNtaXNzLWFsZXJ0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9ibGFzdC1zY3JvbGxzcHkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2JsYXN0LXNlbGVjdC1jaGFuZ2UuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NhcnQtYnRuLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZhc3RhLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9jYXJ0LWZvcm0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2NvbGxlY3Rpb24tdHlwZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvY29weTJjbGlwYm9hcmQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2RlbGF5LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9saXZlLXNlcXVlbmNlLWRpc3BsYXkuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2xvY3VzLXRvb2x0aXAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3Bhc3N3b3JkLWNvbnRyb2wuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3NlYXJjaC1rZXl3b3JkLWhpZ2hsaWdodC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvc3RyYWlucy1maWx0ZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXItaW5zdGFudC1zZWFyY2guanMiXSwibmFtZXMiOlsiJCIsImRvY3VtZW50IiwicmVhZHkiLCJoaWRlIiwiZmFkZUluIiwiZGVsYXkiLCJmYWRlT3V0Iiwid2luZG93Iiwib24iLCIkYWxsTGkiLCJyZW1vdmVDbGFzcyIsIiRhY3RpdmVMaSIsInByZXYiLCJhZGRDbGFzcyIsIm5leHQiLCJlcSIsIiR0b29sIiwiY2hhbmdlIiwiJGZvcm0iLCJjbG9zZXN0IiwiZGF0YSIsImF0dHIiLCJ2YWwiLCJhamF4IiwidXJsIiwidHlwZSIsInN1Y2Nlc3MiLCJodG1sIiwicmVwbGFjZVdpdGgiLCJmaW5kIiwiJGNhcnRCYWRnZSIsImNsaWNrIiwiZSIsInByZXZlbnREZWZhdWx0IiwiJHVybCIsImdldCIsIiRuYkl0ZW1zIiwiaXRlbXMiLCJsZW5ndGgiLCJ0ZXh0IiwicmVhY2hlZF9saW1pdCIsImxvY2F0aW9uIiwicmVsb2FkIiwiJHRhYmxlUm93IiwicmVtb3ZlIiwiZ2VuZXJhdGVDYXJ0RmFzdGEiLCJ0ZXh0YXJlYUlkIiwibW9kYWxJZCIsIiRtb2RhbCIsIiR2YWx1ZXMiLCJlYWNoIiwiZWxlbWVudHMiLCJpIiwiZmllbGQiLCJuYW1lIiwidmFsdWUiLCJkYXRhVHlwZSIsIm1vZGFsIiwic2hvd0hpZGVDYXJ0U2V0dXAiLCIkdHlwZSIsIiRmZWF0dXJlIiwiJGludHJvblNwbGljaW5nIiwiJHVwc3RyZWFtIiwiJGRvd25zdHJlYW0iLCIkc2V0dXAiLCJzaG93IiwicHJvcCIsImNvbGxlY3Rpb25UeXBlIiwiY29udGFpbmVyIiwiYnV0dG9uVGV4dCIsImJ1dHRvbklkIiwiZmllbGRTdGFydCIsImZ1bmN0aW9ucyIsInVuZGVmaW5lZCIsImNoaWxkcmVuIiwiaWQiLCIkYWRkQnV0dG9uIiwiYXBwZW5kIiwiYWRkRmllbGQiLCJpbmRleCIsImFkZERlbGV0ZUJ1dHRvbiIsImFkZEZ1bmN0aW9ucyIsIiRwcm90b3R5cGUiLCJyZXBsYWNlIiwiYmVmb3JlIiwicHJvdG90eXBlIiwiJGRlbGV0ZUJ1dHRvbiIsImNvcHkyY2xpcGJvYXJkIiwiZGF0YVNlbGVjdG9yIiwic2VsZWN0IiwiZXhlY0NvbW1hbmQiLCJjb3B5MmNsaXBib2FyZE9uQ2xpY2siLCJjbGlja1RyaWdnZXIiLCJtb2R1bGUiLCJleHBvcnRzIiwidGltZXIiLCJjYWxsYmFjayIsIm1zIiwiY2xlYXJUaW1lb3V0Iiwic2V0VGltZW91dCIsImxvY3VzIiwiZmVhdHVyZSIsInNlcXVlbmNlQ29udGFpbmVyIiwiZm9ybSIsInN1Ym1pdCIsImV2ZW50IiwidXBzdHJlYW0iLCJwYXJlbnQiLCJkb3duc3RyZWFtIiwic2hvd1V0ciIsImlzIiwic2hvd0ludHJvbiIsIlJvdXRpbmciLCJnZW5lcmF0ZSIsImxvY3VzX25hbWUiLCJmZWF0dXJlX25hbWUiLCJmaXJzdCIsInRvb2x0aXAiLCJrZXl1cCIsInVjYXNlIiwiUmVnRXhwIiwibGNhc2UiLCJudW0iLCJwYXNzd29yZDEiLCJwYXNzd29yZDIiLCJudW1iZXJDaGFycyIsInVwcGVyQ2FzZSIsImxvd2VyQ2FzZSIsIm51bWJlciIsInBhc3N3b3JkTWF0Y2giLCJjc3MiLCJ0ZXN0IiwicmVzdWx0Iiwia2V5d29yZCIsInJlZ2V4IiwicmVzdWx0SHRtbCIsInN0cmFpbnNGaWx0ZXIiLCJzdHJhaW5zRmlsdGVyU2VsZWN0Iiwic3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIiLCJzdHJhaW5zQ2hlY2tib3hlcyIsImNoZWNrQWxsTGluayIsInVuY2hlY2tBbGxMaW5rIiwicHJlcGVuZCIsImNsYWRlIiwic2hvd0hpZGVTdHJhaW5zIiwic3RyYWluQ2xhZGUiLCJjbGFkZUZpbHRlcmVkIiwiY2hlY2tBbGwiLCJjaGVja0FsbENsYWRlIiwidW5jaGVja0FsbCIsInVuY2hlY2tBbGxDbGFkZSIsInJlcXVpcmUiLCJwcm9jZXNzaW5nIiwic2VhcmNoIiwidGVhbSIsImhpc3RvcnkiLCJyZXBsYWNlU3RhdGUiLCJxIiwicCIsImJlZm9yZVNlbmQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7QUFBQSx5Q0FBQUEsRUFBRUMsUUFBRixFQUFZQyxLQUFaLENBQWtCLFlBQVc7QUFDekJGLE1BQUUsOEJBQUYsRUFBa0NHLElBQWxDO0FBQ0FILE1BQUUsOEJBQUYsRUFBa0NJLE1BQWxDLENBQXlDLEtBQXpDO0FBQ0FKLE1BQUUsOEJBQUYsRUFBa0NLLEtBQWxDLENBQXdDLE1BQXhDLEVBQWdEQyxPQUFoRCxDQUF3RCxLQUF4RDtBQUNILENBSkQsRTs7Ozs7Ozs7Ozs7OztBQ0FBLHlDQUFBTixFQUFFTyxNQUFGLEVBQVVDLEVBQVYsQ0FBYSx1QkFBYixFQUFz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QjNCLHNCQUFFLFlBQUYsRUFBZ0I0QixXQUFoQixDQUE0QkQsSUFBNUI7QUFDQTZILGlDQUFhLEtBQWI7QUFDSDtBQWZFLGFBQVA7QUFpQkgsU0FsQkQsRUFrQkcsR0FsQkg7QUFtQkgsS0F0QkQ7QUF1QkgsQ0E1QkQsRSIsImZpbGUiOiJhcHAuYzYzNDNjMmY2YzMwZDEzYTJkNjEuanMiLCJzb3VyY2VzQ29udGVudCI6WyIkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcbiAgICAkKCdbZGF0YS10b2dnbGU9XCJhdXRvLWRpc21pc3NcIl0nKS5oaWRlKCk7XG4gICAgJCgnW2RhdGEtdG9nZ2xlPVwiYXV0by1kaXNtaXNzXCJdJykuZmFkZUluKFwibG93XCIpO1xuICAgICQoJ1tkYXRhLXRvZ2dsZT1cImF1dG8tZGlzbWlzc1wiXScpLmRlbGF5KCc1MDAwJykuZmFkZU91dChcImxvd1wiKTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2F1dG8tZGlzbWlzcy1hbGVydC5qcyIsIiQod2luZG93KS5vbignYWN0aXZhdGUuYnMuc2Nyb2xsc3B5JywgZnVuY3Rpb24gKCkge1xuICAgIC8vIFJlbW92ZSBhbGwgZGlzcGxheSBjbGFzc1xuICAgIHZhciAkYWxsTGkgPSAkKCduYXYjYmxhc3Qtc2Nyb2xsc3B5IG5hdiBhLmFjdGl2ZSArIG5hdiBhJyk7XG4gICAgJGFsbExpLnJlbW92ZUNsYXNzKCdkaXNwbGF5Jyk7XG5cbiAgICAvLyBBZGQgZGlzcGxheSBjbGFzcyBvbiAyIGJlZm9yZSBhbmQgMiBhZnRlclxuICAgIHZhciAkYWN0aXZlTGkgPSAkKCduYXYjYmxhc3Qtc2Nyb2xsc3B5IG5hdiBhLmFjdGl2ZSArIG5hdiBhLmFjdGl2ZScpO1xuICAgICRhY3RpdmVMaS5wcmV2KCkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbiAgICAkYWN0aXZlTGkucHJldigpLnByZXYoKS5hZGRDbGFzcygnZGlzcGxheScpO1xuICAgICRhY3RpdmVMaS5uZXh0KCkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbiAgICAkYWN0aXZlTGkubmV4dCgpLm5leHQoKS5hZGRDbGFzcygnZGlzcGxheScpO1xuXG4gICAgLy8gQWRkIGRpc3BsYXkgb24gdGhlIGZpcnN0IGFuZCAybmRcbiAgICAkYWxsTGkuZXEoMCkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbiAgICAkYWxsTGkuZXEoMSkuYWRkQ2xhc3MoJ2Rpc3BsYXknKTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2JsYXN0LXNjcm9sbHNweS5qcyIsIiQoIGRvY3VtZW50ICkucmVhZHkoZnVuY3Rpb24gKCkge1xuICAgIHZhciAkdG9vbCA9ICQoJyNibGFzdF90b29sJyk7XG5cbiAgICAvLyBXaGVuIGdlbnVzIGdldHMgc2VsZWN0ZWQgLi4uXG4gICAgJHRvb2wuY2hhbmdlKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgLy8gLi4uIHJldHJpZXZlIHRoZSBjb3JyZXNwb25kaW5nIGZvcm0uXG4gICAgICAgIHZhciAkZm9ybSA9ICQodGhpcykuY2xvc2VzdCgnZm9ybScpO1xuICAgICAgICAvLyBTaW11bGF0ZSBmb3JtIGRhdGEsIGJ1dCBvbmx5IGluY2x1ZGUgdGhlIHNlbGVjdGVkIGdlbnVzIHZhbHVlLlxuICAgICAgICB2YXIgZGF0YSA9IHt9O1xuICAgICAgICBkYXRhWyR0b29sLmF0dHIoJ25hbWUnKV0gPSAkdG9vbC52YWwoKTtcblxuICAgICAgICAvLyBTdWJtaXQgZGF0YSB2aWEgQUpBWCB0byB0aGUgZm9ybSdzIGFjdGlvbiBwYXRoLlxuICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgdXJsOiAkZm9ybS5hdHRyKCdhY3Rpb24nKSxcbiAgICAgICAgICAgIHR5cGU6ICRmb3JtLmF0dHIoJ21ldGhvZCcpLFxuICAgICAgICAgICAgZGF0YTogZGF0YSxcbiAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChodG1sKSB7XG4gICAgICAgICAgICAgICAgLy8gUmVwbGFjZSBjdXJyZW50IHBvc2l0aW9uIGZpZWxkIC4uLlxuICAgICAgICAgICAgICAgICQoJ3NlbGVjdCNibGFzdF9kYXRhYmFzZScpLnJlcGxhY2VXaXRoKFxuICAgICAgICAgICAgICAgICAgICAvLyAuLi4gd2l0aCB0aGUgcmV0dXJuZWQgb25lIGZyb20gdGhlIEFKQVggcmVzcG9uc2UuXG4gICAgICAgICAgICAgICAgICAgICQoaHRtbCkuZmluZCgnc2VsZWN0I2JsYXN0X2RhdGFiYXNlJylcbiAgICAgICAgICAgICAgICApO1xuICAgICAgICAgICAgICAgICQoJ3NlbGVjdCNibGFzdF9tYXRyaXgnKS5yZXBsYWNlV2l0aChcbiAgICAgICAgICAgICAgICAgICAgLy8gLi4uIHdpdGggdGhlIHJldHVybmVkIG9uZSBmcm9tIHRoZSBBSkFYIHJlc3BvbnNlLlxuICAgICAgICAgICAgICAgICAgICAkKGh0bWwpLmZpbmQoJ3NlbGVjdCNibGFzdF9tYXRyaXgnKVxuICAgICAgICAgICAgICAgICk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvYmxhc3Qtc2VsZWN0LWNoYW5nZS5qcyIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAgIHZhciAkY2FydEJhZGdlID0gJCgnYSNjYXJ0IHNwYW4uYmFkZ2UnKTtcblxuICAgICQoJ2EuY2FydC1hZGQtYnRuJykuY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHZhciAkdXJsID0gJCh0aGlzKS5hdHRyKCdocmVmJyk7XG5cbiAgICAgICAgJC5nZXQoICR1cmwsIGZ1bmN0aW9uKCBkYXRhICkge1xuICAgICAgICAgICAgLy8gQ291bnQgb2JqZWN0cyBpbiBkYXRhXG4gICAgICAgICAgICB2YXIgJG5iSXRlbXMgPSBkYXRhLml0ZW1zLmxlbmd0aDtcbiAgICAgICAgICAgICRjYXJ0QmFkZ2UudGV4dCgkbmJJdGVtcyk7XG5cbiAgICAgICAgICAgIC8vIGlmIHJlYWNoZWQgbGltaXRcbiAgICAgICAgICAgIGlmICh0cnVlID09PSBkYXRhLnJlYWNoZWRfbGltaXQpIHtcbiAgICAgICAgICAgICAgICBsb2NhdGlvbi5yZWxvYWQoKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSk7XG5cbiAgICAkKCdhLmNhcnQtcmVtb3ZlLWJ0bicpLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICB2YXIgJHVybCA9ICQodGhpcykuYXR0cignaHJlZicpO1xuICAgICAgICB2YXIgJHRhYmxlUm93ID0gJCh0aGlzKS5jbG9zZXN0KCd0cicpO1xuXG4gICAgICAgICQuZ2V0KCAkdXJsLCBmdW5jdGlvbiggZGF0YSApIHtcbiAgICAgICAgICAgIC8vIENvdW50IG9iamVjdHMgaW4gZGF0YVxuICAgICAgICAgICAgdmFyICRuYkl0ZW1zID0gZGF0YS5pdGVtcy5sZW5ndGg7XG4gICAgICAgICAgICAkY2FydEJhZGdlLnRleHQoJG5iSXRlbXMpO1xuXG4gICAgICAgICAgICAvLyBSZW1vdmUgdGhlIGxpbmUgaW4gdGhlIHBhZ2VcbiAgICAgICAgICAgICR0YWJsZVJvdy5yZW1vdmUoKTtcbiAgICAgICAgfSk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jYXJ0LWJ0bi5qcyIsImZ1bmN0aW9uIGdlbmVyYXRlQ2FydEZhc3RhKHRleHRhcmVhSWQsIG1vZGFsSWQpIHtcbiAgICB2YXIgJG1vZGFsID0gJChtb2RhbElkKTtcbiAgICB2YXIgJGZvcm0gPSAkbW9kYWwuZmluZCgnZm9ybScpO1xuXG4gICAgdmFyICR2YWx1ZXMgPSB7fTtcblxuICAgICQuZWFjaCggJGZvcm1bMF0uZWxlbWVudHMsIGZ1bmN0aW9uKGksIGZpZWxkKSB7XG4gICAgICAgICR2YWx1ZXNbZmllbGQubmFtZV0gPSBmaWVsZC52YWx1ZTtcbiAgICB9KTtcblxuICAgICQuYWpheCh7XG4gICAgICAgIHR5cGU6ICAgICAgICRmb3JtLmF0dHIoJ21ldGhvZCcpLFxuICAgICAgICB1cmw6ICAgICAgICAkZm9ybS5hdHRyKCdhY3Rpb24nKSxcbiAgICAgICAgZGF0YVR5cGU6ICAgJ3RleHQnLFxuICAgICAgICBkYXRhOiAgICAgICAkdmFsdWVzLFxuICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgJChtb2RhbElkKS5tb2RhbCgnaGlkZScpO1xuICAgICAgICAgICAgJCh0ZXh0YXJlYUlkKS52YWwoZGF0YSk7XG4gICAgICAgIH1cbiAgICB9KTtcbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9jYXJ0LWZhc3RhLmpzIiwiZnVuY3Rpb24gc2hvd0hpZGVDYXJ0U2V0dXAoKSB7XG4gICAgdmFyICR0eXBlID0gJCgnc2VsZWN0W2lkJD1cXCdjYXJ0X3R5cGVcXCddJyk7XG4gICAgdmFyICRmZWF0dXJlID0gJCgnc2VsZWN0W2lkJD1cXCdjYXJ0X2ZlYXR1cmVcXCddJyk7XG4gICAgdmFyICRpbnRyb25TcGxpY2luZyA9ICQoJ3NlbGVjdFtpZCQ9XFwnY2FydF9pbnRyb25TcGxpY2luZ1xcJ10nKTtcbiAgICB2YXIgJHVwc3RyZWFtID0gJCgnaW5wdXRbaWQkPVxcJ2NhcnRfdXBzdHJlYW1cXCddJyk7XG4gICAgdmFyICRkb3duc3RyZWFtID0gJCgnaW5wdXRbaWQkPVxcJ2NhcnRfZG93bnN0cmVhbVxcJ10nKTtcbiAgICB2YXIgJHNldHVwID0gJGZlYXR1cmUuY2xvc2VzdCgnI2NhcnQtc2V0dXAnKTtcblxuICAgIGlmICgncHJvdCcgPT09ICR0eXBlLnZhbCgpKSB7XG4gICAgICAgICRzZXR1cC5oaWRlKCk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgJHNldHVwLnNob3coKTtcbiAgICB9XG5cbiAgICBpZiAoJ2xvY3VzJyA9PT0gJGZlYXR1cmUudmFsKCkpIHtcbiAgICAgICAgJGludHJvblNwbGljaW5nLnZhbCgwKTtcbiAgICAgICAgJGludHJvblNwbGljaW5nLnByb3AoJ2Rpc2FibGVkJywgdHJ1ZSk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgJGludHJvblNwbGljaW5nLnByb3AoJ2Rpc2FibGVkJywgZmFsc2UpO1xuICAgIH1cblxuICAgIGlmICgnMScgPT09ICRpbnRyb25TcGxpY2luZy52YWwoKSkge1xuICAgICAgICAkdXBzdHJlYW0uY2xvc2VzdCgnZGl2LmZvcm0tZ3JvdXAnKS5oaWRlKCk7XG4gICAgICAgICRkb3duc3RyZWFtLmNsb3Nlc3QoJ2Rpdi5mb3JtLWdyb3VwJykuaGlkZSgpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgICR1cHN0cmVhbS5jbG9zZXN0KCdkaXYuZm9ybS1ncm91cCcpLnNob3coKTtcbiAgICAgICAgJGRvd25zdHJlYW0uY2xvc2VzdCgnZGl2LmZvcm0tZ3JvdXAnKS5zaG93KCk7XG4gICAgfVxuXG4gICAgJHR5cGUuY2hhbmdlKGZ1bmN0aW9uKCkge1xuICAgICAgICBzaG93SGlkZUNhcnRTZXR1cCgpO1xuICAgIH0pO1xuXG4gICAgJGZlYXR1cmUuY2hhbmdlKGZ1bmN0aW9uKCkge1xuICAgICAgICBzaG93SGlkZUNhcnRTZXR1cCgpO1xuICAgIH0pO1xuXG4gICAgJGludHJvblNwbGljaW5nLmNoYW5nZShmdW5jdGlvbigpIHtcbiAgICAgICAgc2hvd0hpZGVDYXJ0U2V0dXAoKTtcbiAgICB9KTtcbn1cblxuc2hvd0hpZGVDYXJ0U2V0dXAoKTtcblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY2FydC1mb3JtLmpzIiwiZnVuY3Rpb24gY29sbGVjdGlvblR5cGUoY29udGFpbmVyLCBidXR0b25UZXh0LCBidXR0b25JZCwgZmllbGRTdGFydCwgZnVuY3Rpb25zKSB7XG4gICAgaWYgKGJ1dHRvbklkID09PSB1bmRlZmluZWQpIHtcbiAgICAgICAgYnV0dG9uSWQgPSBudWxsO1xuICAgIH1cblxuICAgIGlmIChmaWVsZFN0YXJ0ID09PSB1bmRlZmluZWQpIHtcbiAgICAgICAgZmllbGRTdGFydCA9IGZhbHNlO1xuICAgIH1cblxuICAgIGlmIChmdW5jdGlvbnMgPT09IHVuZGVmaW5lZCkge1xuICAgICAgICBmdW5jdGlvbnMgPSBbXTtcbiAgICB9XG5cbiAgICAvLyBEZWxldGUgdGhlIGZpcnN0IGxhYmVsICh0aGUgbnVtYmVyIG9mIHRoZSBmaWVsZCksIGFuZCB0aGUgcmVxdWlyZWQgY2xhc3NcbiAgICBjb250YWluZXIuY2hpbGRyZW4oJ2RpdicpLmZpbmQoJ2xhYmVsOmZpcnN0JykudGV4dCgnJyk7XG4gICAgY29udGFpbmVyLmNoaWxkcmVuKCdkaXYnKS5maW5kKCdsYWJlbDpmaXJzdCcpLnJlbW92ZUNsYXNzKCdyZXF1aXJlZCcpO1xuICAgIGNvbnRhaW5lci5jaGlsZHJlbignZGl2JykuZmluZCgnbGFiZWw6Zmlyc3QnKS5yZW1vdmVDbGFzcygncmVxdWlyZWQnKTtcblxuICAgIC8vIENyZWF0ZSBhbmQgYWRkIGEgYnV0dG9uIHRvIGFkZCBuZXcgZmllbGRcbiAgICBpZiAoYnV0dG9uSWQpIHtcbiAgICAgICAgdmFyIGlkID0gXCJpZD0nXCIgKyBidXR0b25JZCArIFwiJ1wiO1xuICAgICAgICB2YXIgJGFkZEJ1dHRvbiA9ICQoJzxhIGhyZWY9XCIjXCIgJyArIGlkICsgJ2NsYXNzPVwiYnRuIGJ0bi1kZWZhdWx0IGJ0bi14c1wiPjxzcGFuIGNsYXNzPVwiZmEgZmEtcGx1cyBhcmlhLWhpZGRlbj1cInRydWVcIlwiPjwvc3Bhbj4gJytidXR0b25UZXh0Kyc8L2E+Jyk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgdmFyICRhZGRCdXR0b24gPSAkKCc8YSBocmVmPVwiI1wiIGNsYXNzPVwiYnRuIGJ0bi1kZWZhdWx0IGJ0bi14c1wiPjxzcGFuIGNsYXNzPVwiZmEgZmEtcGx1cyBhcmlhLWhpZGRlbj1cInRydWVcIlwiPjwvc3Bhbj4gJytidXR0b25UZXh0Kyc8L2E+Jyk7XG4gICAgfVxuXG4gICAgY29udGFpbmVyLmFwcGVuZCgkYWRkQnV0dG9uKTtcblxuICAgIC8vIEFkZCBhIGNsaWNrIGV2ZW50IG9uIHRoZSBhZGQgYnV0dG9uXG4gICAgJGFkZEJ1dHRvbi5jbGljayhmdW5jdGlvbihlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgLy8gQ2FsbCB0aGUgYWRkRmllbGQgbWV0aG9kXG4gICAgICAgIGFkZEZpZWxkKGNvbnRhaW5lcik7XG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICB9KTtcblxuICAgIC8vIERlZmluZSBhbiBpbmRleCB0byBjb3VudCB0aGUgbnVtYmVyIG9mIGFkZGVkIGZpZWxkICh1c2VkIHRvIGdpdmUgbmFtZSB0byBmaWVsZHMpXG4gICAgdmFyIGluZGV4ID0gY29udGFpbmVyLmNoaWxkcmVuKCdkaXYnKS5sZW5ndGg7XG5cbiAgICAvLyBJZiB0aGUgaW5kZXggaXMgPiAwLCBmaWVsZHMgYWxyZWFkeSBleGlzdHMsIHRoZW4sIGFkZCBhIGRlbGV0ZUJ1dHRvbiB0byB0aGlzIGZpZWxkc1xuICAgIGlmIChpbmRleCA+IDApIHtcbiAgICAgICAgY29udGFpbmVyLmNoaWxkcmVuKCdkaXYnKS5lYWNoKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgYWRkRGVsZXRlQnV0dG9uKCQodGhpcykpO1xuICAgICAgICAgICAgYWRkRnVuY3Rpb25zKCQodGhpcykpO1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICAvLyBJZiB3ZSB3YW50IHRvIGhhdmUgYSBmaWVsZCBhdCBzdGFydFxuICAgIGlmICh0cnVlID09IGZpZWxkU3RhcnQgJiYgMCA9PSBpbmRleCkge1xuICAgICAgICBhZGRGaWVsZChjb250YWluZXIpO1xuICAgIH1cblxuICAgIC8vIFRoZSBhZGRGaWVsZCBmdW5jdGlvblxuICAgIGZ1bmN0aW9uIGFkZEZpZWxkKGNvbnRhaW5lcikge1xuICAgICAgICAvLyBSZXBsYWNlIHNvbWUgdmFsdWUgaW4gdGhlIMKrIGRhdGEtcHJvdG90eXBlIMK7XG4gICAgICAgIC8vIC0gXCJfX25hbWVfX2xhYmVsX19cIiBieSB0aGUgbmFtZSB3ZSB3YW50IHRvIHVzZSwgaGVyZSBub3RoaW5nXG4gICAgICAgIC8vIC0gXCJfX25hbWVfX1wiIGJ5IHRoZSBuYW1lIG9mIHRoZSBmaWVsZCwgaGVyZSB0aGUgaW5kZXggbnVtYmVyXG4gICAgICAgIHZhciAkcHJvdG90eXBlID0gJChjb250YWluZXIuYXR0cignZGF0YS1wcm90b3R5cGUnKVxuICAgICAgICAgICAgLnJlcGxhY2UoL2NsYXNzPVwiY29sLXNtLTIgY29udHJvbC1sYWJlbCByZXF1aXJlZFwiLywgJ2NsYXNzPVwiY29sLXNtLTIgY29udHJvbC1sYWJlbFwiJylcbiAgICAgICAgICAgIC5yZXBsYWNlKC9fX25hbWVfX2xhYmVsX18vZywgJycpXG4gICAgICAgICAgICAucmVwbGFjZSgvX19uYW1lX18vZywgaW5kZXgpKTtcblxuICAgICAgICAvLyBBZGQgYSBkZWxldGUgYnV0dG9uIHRvIHRoZSBuZXcgZmllbGRcbiAgICAgICAgYWRkRGVsZXRlQnV0dG9uKCRwcm90b3R5cGUpO1xuXG4gICAgICAgIC8vIElmIHRoZXJlIGFyZSBzdXBwbGVtZW50YXJ5IGZ1bmN0aW9uc1xuICAgICAgICBhZGRGdW5jdGlvbnMoJHByb3RvdHlwZSk7XG5cbiAgICAgICAgLy8gQWRkIHRoZSBmaWVsZCBpbiB0aGUgZm9ybVxuICAgICAgICAkYWRkQnV0dG9uLmJlZm9yZSgkcHJvdG90eXBlKTtcblxuICAgICAgICAvLyBJbmNyZW1lbnQgdGhlIGNvdW50ZXJcbiAgICAgICAgaW5kZXgrKztcbiAgICB9XG5cbiAgICAvLyBBIGZ1bmN0aW9uIGNhbGxlZCB0byBhZGQgZGVsZXRlQnV0dG9uXG4gICAgZnVuY3Rpb24gYWRkRGVsZXRlQnV0dG9uKHByb3RvdHlwZSkge1xuICAgICAgICAvLyBGaXJzdCwgY3JlYXRlIHRoZSBidXR0b25cbiAgICAgICAgdmFyICRkZWxldGVCdXR0b24gPSAkKCc8ZGl2IGNsYXNzPVwiY29sLXNtLTFcIj48YSBocmVmPVwiI1wiIGNsYXNzPVwiYnRuIGJ0bi1kYW5nZXIgYnRuLXNtXCI+PHNwYW4gY2xhc3M9XCJmYSBmYS10cmFzaFwiIGFyaWEtaGlkZGVuPVwidHJ1ZVwiPjwvc3Bhbj48L2E+PC9kaXY+Jyk7XG5cbiAgICAgICAgLy8gQWRkIHRoZSBidXR0b24gb24gdGhlIGZpZWxkXG4gICAgICAgICQoJy5jb2wtc20tMTAnLCBwcm90b3R5cGUpLnJlbW92ZUNsYXNzKCdjb2wtc20tMTAnKS5hZGRDbGFzcygnY29sLXNtLTknKTtcbiAgICAgICAgcHJvdG90eXBlLmFwcGVuZCgkZGVsZXRlQnV0dG9uKTtcblxuICAgICAgICAvLyBDcmVhdGUgYSBsaXN0ZW5lciBvbiB0aGUgY2xpY2sgZXZlbnRcbiAgICAgICAgJGRlbGV0ZUJ1dHRvbi5jbGljayhmdW5jdGlvbihlKSB7XG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICAvLyBSZW1vdmUgdGhlIGZpZWxkXG4gICAgICAgICAgICBwcm90b3R5cGUucmVtb3ZlKCk7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIGFkZEZ1bmN0aW9ucyhwcm90b3R5cGUpIHtcbiAgICAgICAgLy8gSWYgdGhlcmUgYXJlIHN1cHBsZW1lbnRhcnkgZnVuY3Rpb25zXG4gICAgICAgIGlmIChmdW5jdGlvbnMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgLy8gRG8gYSB3aGlsZSBvbiBmdW5jdGlvbnMsIGFuZCBhcHBseSB0aGVtIHRvIHRoZSBwcm90b3R5cGVcbiAgICAgICAgICAgIGZvciAodmFyIGkgPSAwOyBmdW5jdGlvbnMubGVuZ3RoID4gaTsgaSsrKSB7XG4gICAgICAgICAgICAgICAgZnVuY3Rpb25zW2ldKHByb3RvdHlwZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9XG59XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY29sbGVjdGlvbi10eXBlLmpzIiwiZnVuY3Rpb24gY29weTJjbGlwYm9hcmQoZGF0YVNlbGVjdG9yKSB7XG4gICAgZGF0YVNlbGVjdG9yLnNlbGVjdCgpO1xuICAgIGRvY3VtZW50LmV4ZWNDb21tYW5kKCdjb3B5Jyk7XG59XG5cbmZ1bmN0aW9uIGNvcHkyY2xpcGJvYXJkT25DbGljayhjbGlja1RyaWdnZXIsIGRhdGFTZWxlY3Rvcikge1xuICAgIGNsaWNrVHJpZ2dlci5jbGljayhmdW5jdGlvbigpe1xuICAgICAgICBjb3B5MmNsaXBib2FyZChkYXRhU2VsZWN0b3IpO1xuICAgIH0pO1xufVxuXG4kKGZ1bmN0aW9uKCkge1xuICAgY29weTJjbGlwYm9hcmRPbkNsaWNrKCQoXCIjcmV2ZXJzZS1jb21wbGVtZW50LWNvcHktYnV0dG9uXCIpLCAkKFwiI3JldmVyc2UtY29tcGxlbWVudC1yZXN1bHRcIikpO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvY29weTJjbGlwYm9hcmQuanMiLCIvLyB2YXIgZGVsYXkgPSAoZnVuY3Rpb24oKXtcbi8vICAgICB2YXIgdGltZXIgPSAwO1xuLy8gICAgIHJldHVybiBmdW5jdGlvbihjYWxsYmFjaywgbXMpe1xuLy8gICAgICAgICBjbGVhclRpbWVvdXQgKHRpbWVyKTtcbi8vICAgICAgICAgdGltZXIgPSBzZXRUaW1lb3V0KGNhbGxiYWNrLCBtcyk7XG4vLyAgICAgfTtcbi8vIH0pKCk7XG5cbm1vZHVsZS5leHBvcnRzID0gKGZ1bmN0aW9uKCkge1xuICAgIHJldHVybiAoZnVuY3Rpb24oKXtcbiAgICAgICAgdmFyIHRpbWVyID0gMDtcbiAgICAgICAgcmV0dXJuIGZ1bmN0aW9uKGNhbGxiYWNrLCBtcyl7XG4gICAgICAgICAgICBjbGVhclRpbWVvdXQgKHRpbWVyKTtcbiAgICAgICAgICAgIHRpbWVyID0gc2V0VGltZW91dChjYWxsYmFjaywgbXMpO1xuICAgICAgICB9O1xuICAgIH0pKCk7XG59KSgpO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2RlbGF5LmpzIiwiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKXtcbiAgICAkKCdkaXYubG9jdXMtZmVhdHVyZScpLmVhY2goZnVuY3Rpb24oaW5kZXgpIHtcbiAgICAgICAgdmFyIGxvY3VzID0gJCggdGhpcyApLmRhdGEoXCJsb2N1c1wiKTtcbiAgICAgICAgdmFyIGZlYXR1cmUgPSAkKCB0aGlzICkuZGF0YShcImZlYXR1cmVcIik7XG4gICAgICAgIHZhciBzZXF1ZW5jZUNvbnRhaW5lciA9ICQoIHRoaXMgKS5maW5kKCdkaXYuZmFzdGEnKTtcbiAgICAgICAgdmFyIGZvcm0gPSAkKCB0aGlzICkuZmluZCgnZm9ybScpO1xuXG4gICAgICAgIGZvcm0ucmVtb3ZlQ2xhc3MoJ2hpZGRlbicpO1xuXG4gICAgICAgIGZvcm0uc3VibWl0KGZ1bmN0aW9uKGV2ZW50KSB7XG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgdmFyIHVwc3RyZWFtID0gJCggdGhpcyApLnBhcmVudCgpLmZpbmQoXCJpbnB1dFtuYW1lPSd1cHN0cmVhbSddXCIpLnZhbCgpO1xuICAgICAgICAgICAgdmFyIGRvd25zdHJlYW0gPSAkKCB0aGlzICkucGFyZW50KCkuZmluZChcImlucHV0W25hbWU9J2Rvd25zdHJlYW0nXVwiKS52YWwoKTtcbiAgICAgICAgICAgIHZhciBzaG93VXRyID0gJCggdGhpcyApLnBhcmVudCgpLmZpbmQoXCJpbnB1dFtuYW1lPSdzaG93VXRyJ11cIikuaXMoXCI6Y2hlY2tlZFwiKTtcbiAgICAgICAgICAgIHZhciBzaG93SW50cm9uID0gJCggdGhpcyApLnBhcmVudCgpLmZpbmQoXCJpbnB1dFtuYW1lPSdzaG93SW50cm9uJ11cIikuaXMoXCI6Y2hlY2tlZFwiKTtcblxuICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICB0eXBlOiAnR0VUJyxcbiAgICAgICAgICAgICAgICB1cmw6IFJvdXRpbmcuZ2VuZXJhdGUoJ2ZlYXR1cmVfc2VxdWVuY2UnLCB7IGxvY3VzX25hbWU6IGxvY3VzLCBmZWF0dXJlX25hbWU6IGZlYXR1cmUsIHVwc3RyZWFtOiB1cHN0cmVhbSwgZG93bnN0cmVhbTogZG93bnN0cmVhbSwgc2hvd1V0cjogc2hvd1V0ciwgc2hvd0ludHJvbjogc2hvd0ludHJvbiB9KSxcbiAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2h0bWwnLFxuICAgICAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChodG1sKSB7XG4gICAgICAgICAgICAgICAgICAgIHNlcXVlbmNlQ29udGFpbmVyLmZpcnN0KCkuaHRtbChodG1sKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfSk7XG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9saXZlLXNlcXVlbmNlLWRpc3BsYXkuanMiLCIkKGZ1bmN0aW9uICgpIHtcbiAgICAkKCdbZGF0YS10b2dnbGU9XCJ0b29sdGlwXCJdJykudG9vbHRpcCgpXG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9sb2N1cy10b29sdGlwLmpzIiwiJChcImlucHV0W3R5cGU9cGFzc3dvcmRdW2lkKj0nX3BsYWluUGFzc3dvcmRfJ11cIikua2V5dXAoZnVuY3Rpb24oKXtcbiAgICAvLyBTZXQgcmVnZXggY29udHJvbFxuICAgIHZhciB1Y2FzZSA9IG5ldyBSZWdFeHAoXCJbQS1aXStcIik7XG4gICAgdmFyIGxjYXNlID0gbmV3IFJlZ0V4cChcIlthLXpdK1wiKTtcbiAgICB2YXIgbnVtID0gbmV3IFJlZ0V4cChcIlswLTldK1wiKTtcblxuICAgIC8vIFNldCBwYXNzd29yZCBmaWVsZHNcbiAgICB2YXIgcGFzc3dvcmQxID0gJChcIltpZCQ9J19wbGFpblBhc3N3b3JkX2ZpcnN0J11cIik7XG4gICAgdmFyIHBhc3N3b3JkMiA9ICQoXCJbaWQkPSdfcGxhaW5QYXNzd29yZF9zZWNvbmQnXVwiKTtcbiAgICBcbiAgICAvLyBTZXQgZGlzcGxheSByZXN1bHRcbiAgICB2YXIgbnVtYmVyQ2hhcnMgPSAkKFwiI251bWJlci1jaGFyc1wiKTtcbiAgICB2YXIgdXBwZXJDYXNlID0gJChcIiN1cHBlci1jYXNlXCIpO1xuICAgIHZhciBsb3dlckNhc2UgPSAkKFwiI2xvd2VyLWNhc2VcIik7XG4gICAgdmFyIG51bWJlciA9ICQoXCIjbnVtYmVyXCIpO1xuICAgIHZhciBwYXNzd29yZE1hdGNoID0gJChcIiNwYXNzd29yZC1tYXRjaFwiKTtcblxuICAgIC8vIERvIHRoZSB0ZXN0XG4gICAgaWYocGFzc3dvcmQxLnZhbCgpLmxlbmd0aCA+PSA4KXtcbiAgICAgICAgbnVtYmVyQ2hhcnMucmVtb3ZlQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbnVtYmVyQ2hhcnMuYWRkQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbnVtYmVyQ2hhcnMuY3NzKFwiY29sb3JcIixcIiMwMEE0MUVcIik7XG4gICAgfWVsc2V7XG4gICAgICAgIG51bWJlckNoYXJzLnJlbW92ZUNsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIG51bWJlckNoYXJzLmFkZENsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIG51bWJlckNoYXJzLmNzcyhcImNvbG9yXCIsXCIjRkYwMDA0XCIpO1xuICAgIH1cblxuICAgIGlmKHVjYXNlLnRlc3QocGFzc3dvcmQxLnZhbCgpKSl7XG4gICAgICAgIHVwcGVyQ2FzZS5yZW1vdmVDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICB1cHBlckNhc2UuYWRkQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgdXBwZXJDYXNlLmNzcyhcImNvbG9yXCIsXCIjMDBBNDFFXCIpO1xuICAgIH1lbHNle1xuICAgICAgICB1cHBlckNhc2UucmVtb3ZlQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgdXBwZXJDYXNlLmFkZENsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIHVwcGVyQ2FzZS5jc3MoXCJjb2xvclwiLFwiI0ZGMDAwNFwiKTtcbiAgICB9XG5cbiAgICBpZihsY2FzZS50ZXN0KHBhc3N3b3JkMS52YWwoKSkpe1xuICAgICAgICBsb3dlckNhc2UucmVtb3ZlQ2xhc3MoXCJmYS10aW1lc1wiKTtcbiAgICAgICAgbG93ZXJDYXNlLmFkZENsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIGxvd2VyQ2FzZS5jc3MoXCJjb2xvclwiLFwiIzAwQTQxRVwiKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgbG93ZXJDYXNlLnJlbW92ZUNsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIGxvd2VyQ2FzZS5hZGRDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBsb3dlckNhc2UuY3NzKFwiY29sb3JcIixcIiNGRjAwMDRcIik7XG4gICAgfVxuXG4gICAgaWYobnVtLnRlc3QocGFzc3dvcmQxLnZhbCgpKSl7XG4gICAgICAgIG51bWJlci5yZW1vdmVDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBudW1iZXIuYWRkQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbnVtYmVyLmNzcyhcImNvbG9yXCIsXCIjMDBBNDFFXCIpO1xuICAgIH1lbHNle1xuICAgICAgICBudW1iZXIucmVtb3ZlQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgbnVtYmVyLmFkZENsYXNzKFwiZmEtdGltZXNcIik7XG4gICAgICAgIG51bWJlci5jc3MoXCJjb2xvclwiLFwiI0ZGMDAwNFwiKTtcbiAgICB9XG5cbiAgICBpZihwYXNzd29yZDEudmFsKCkgPT09IHBhc3N3b3JkMi52YWwoKSAmJiBwYXNzd29yZDEudmFsKCkgIT09ICcnKXtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5yZW1vdmVDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBwYXNzd29yZE1hdGNoLmFkZENsYXNzKFwiZmEtY2hlY2tcIik7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2guY3NzKFwiY29sb3JcIixcIiMwMEE0MUVcIik7XG4gICAgfWVsc2V7XG4gICAgICAgIHBhc3N3b3JkTWF0Y2gucmVtb3ZlQ2xhc3MoXCJmYS1jaGVja1wiKTtcbiAgICAgICAgcGFzc3dvcmRNYXRjaC5hZGRDbGFzcyhcImZhLXRpbWVzXCIpO1xuICAgICAgICBwYXNzd29yZE1hdGNoLmNzcyhcImNvbG9yXCIsXCIjRkYwMDA0XCIpO1xuICAgIH1cbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3Bhc3N3b3JkLWNvbnRyb2wuanMiLCIkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcbiAgICB2YXIgcmVzdWx0ID0gJCgnI3NlYXJjaC1yZXN1bHRzJyk7XG5cbiAgICBpZiAocmVzdWx0Lmxlbmd0aCA+IDApIHtcbiAgICAgICAgdmFyIGtleXdvcmQgPSByZXN1bHQuZGF0YSgnc2VhcmNoLWtleXdvcmQnKTtcbiAgICAgICAga2V5d29yZCA9ICcoJyArIGtleXdvcmQgKyAnKSc7XG4gICAgICAgIHZhciByZWdleCA9IG5ldyBSZWdFeHAoa2V5d29yZCxcImdpXCIpO1xuICAgICAgICB2YXIgcmVzdWx0SHRtbCA9IHJlc3VsdC5odG1sKCk7XG5cbiAgICAgICAgcmVzdWx0SHRtbCA9IHJlc3VsdEh0bWwucmVwbGFjZShyZWdleCwgXCI8Yj4kMTwvYj5cIik7XG4gICAgICAgIHJlc3VsdC5odG1sKHJlc3VsdEh0bWwpO1xuICAgIH1cbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3NlYXJjaC1rZXl3b3JkLWhpZ2hsaWdodC5qcyIsImZ1bmN0aW9uIHN0cmFpbnNGaWx0ZXIoc3RyYWluc0ZpbHRlclNlbGVjdCwgc3RyYWluc0NoZWNrQm94ZXNDb250YWluZXIpIHtcblxuICAgIC8vIERlZmluZSB2YXIgdGhhdCBjb250YWlucyBmaWVsZHNcbiAgICB2YXIgc3RyYWluc0NoZWNrYm94ZXMgPSBzdHJhaW5zQ2hlY2tCb3hlc0NvbnRhaW5lci5maW5kKCAnLmZvcm0tY2hlY2snICk7XG5cbiAgICAvLyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqLy9cbiAgICAvLyAgQWRkIHRoZSBsaW5rcyAoY2hlY2svdW5jaGVjaykgLy9cbiAgICAvLyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqLy9cblxuICAgIC8vIERlZmluZSBjaGVja0FsbC91bmNoZWNrQWxsIGxpbmtzXG4gICAgdmFyIGNoZWNrQWxsTGluayA9ICQoJzxhIGhyZWY9XCIjXCIgY2xhc3M9XCJjaGVja19hbGxfc3RyYWluc1wiID4gQ2hlY2sgYWxsPC9hPicpO1xuICAgIHZhciB1bmNoZWNrQWxsTGluayA9ICQoJzxhIGhyZWY9XCIjXCIgY2xhc3M9XCJ1bmNoZWNrX2FsbF9zdHJhaW5zXCIgPiBVbmNoZWNrIGFsbDwvYT4nKTtcblxuICAgIC8vIEluc2VydCB0aGUgY2hlY2svdW5jaGVjayBsaW5rc1xuICAgIHN0cmFpbnNDaGVja0JveGVzQ29udGFpbmVyLnByZXBlbmQodW5jaGVja0FsbExpbmspO1xuICAgIHN0cmFpbnNDaGVja0JveGVzQ29udGFpbmVyLnByZXBlbmQoJyAvICcpO1xuICAgIHN0cmFpbnNDaGVja0JveGVzQ29udGFpbmVyLnByZXBlbmQoY2hlY2tBbGxMaW5rKTtcblxuICAgIC8vKioqKioqKioqKioqKioqKioqKioqKioqKioqLy9cbiAgICAvLyBDcmVhdGUgYWxsIG9uQ0xpY2sgZXZlbnRzIC8vXG4gICAgLy8qKioqKioqKioqKioqKioqKioqKioqKioqKiovL1xuXG4gICAgLy8gQ3JlYXRlIG9uQ2xpY2sgZXZlbnQgb24gVGVhbSBmaWx0ZXJcbiAgICBzdHJhaW5zRmlsdGVyU2VsZWN0LmNoYW5nZShmdW5jdGlvbiAoKSB7XG4gICAgICAgIC8vIEdldCB0aGUgY2xhZGVcbiAgICAgICAgdmFyIGNsYWRlID0gJCh0aGlzKS52YWwoKTtcblxuICAgICAgICAvLyBDYWxsIHRoZSBmdW5jdGlvbiBhbmQgZ2l2ZSB0aGUgY2xhZGVcbiAgICAgICAgc2hvd0hpZGVTdHJhaW5zKGNsYWRlKTtcbiAgICB9KTtcblxuICAgIGZ1bmN0aW9uIHNob3dIaWRlU3RyYWlucyhjbGFkZSkge1xuICAgICAgICBpZiAoJycgPT09IGNsYWRlKSB7XG4gICAgICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5zaG93KCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAvLyBIaWRlIGFsbCBTdHJhaW5zXG4gICAgICAgICAgICBzdHJhaW5zQ2hlY2tib3hlcy5oaWRlKCk7XG5cbiAgICAgICAgICAgIC8vIFNob3cgY2xhZGUgc3RyYWluc1xuICAgICAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgdmFyIHN0cmFpbkNsYWRlID0gJCggdGhpcyApLmZpbmQoIFwiOmNoZWNrYm94XCIgKS5kYXRhKCdjbGFkZScpO1xuXG4gICAgICAgICAgICAgICAgaWYgKHN0cmFpbkNsYWRlID09PSBjbGFkZSkge1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpLnNob3coKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgIH1cblxuICAgIC8vIENyZWF0ZSBvbkNsaWNrIGV2ZW50IG9uIGNoZWNrQWxsTGlua1xuICAgIGNoZWNrQWxsTGluay5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIHZhciBjbGFkZUZpbHRlcmVkID0gc3RyYWluc0ZpbHRlclNlbGVjdC52YWwoKTtcblxuICAgICAgICBpZiAoJycgPT09IGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgICAgIGNoZWNrQWxsKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBjaGVja0FsbENsYWRlKGNsYWRlRmlsdGVyZWQpO1xuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICAvLyBDcmVhdGUgb25DbGljayBldmVudCBvbiB1bmNoZWNrQWxsTGlua1xuICAgIHVuY2hlY2tBbGxMaW5rLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgdmFyIGNsYWRlRmlsdGVyZWQgPSBzdHJhaW5zRmlsdGVyU2VsZWN0LnZhbCgpO1xuXG4gICAgICAgIGlmICgnJyA9PT0gY2xhZGVGaWx0ZXJlZCkge1xuICAgICAgICAgICAgdW5jaGVja0FsbCgpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdW5jaGVja0FsbENsYWRlKGNsYWRlRmlsdGVyZWQpO1xuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICAvL1xuICAgIC8vIEJhc2UgZnVuY3Rpb25zOiBjaGVjay91bmNoZWNrIGFsbCBjaGVja2JveGVzIGFuZCBjaGVjay91bmNoZWNrIHNwZWNpZmljIHN0cmFpbnMgKHBlciBjbGFkZSlcbiAgICAvL1xuXG4gICAgZnVuY3Rpb24gY2hlY2tBbGxDbGFkZShjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHN0cmFpbkNsYWRlID0gJCh0aGlzKS5maW5kKCBcImlucHV0OmNoZWNrYm94XCIgKS5kYXRhKCdjbGFkZScpO1xuXG4gICAgICAgICAgICBpZiAoc3RyYWluQ2xhZGUgPT09IGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmZpbmQoXCJpbnB1dDpjaGVja2JveFwiKS5wcm9wKCdjaGVja2VkJywgdHJ1ZSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIHVuY2hlY2tBbGxDbGFkZShjbGFkZUZpbHRlcmVkKSB7XG4gICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdmFyIHN0cmFpbkNsYWRlID0gJCh0aGlzKS5maW5kKCBcImlucHV0OmNoZWNrYm94XCIgKS5kYXRhKCdjbGFkZScpO1xuXG4gICAgICAgICAgICBpZiAoc3RyYWluQ2xhZGUgPT09IGNsYWRlRmlsdGVyZWQpIHtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmZpbmQoXCJpbnB1dDpjaGVja2JveFwiKS5wcm9wKCdjaGVja2VkJywgZmFsc2UpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICBmdW5jdGlvbiBjaGVja0FsbCgpIHtcbiAgICAgICAgc3RyYWluc0NoZWNrYm94ZXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAkKHRoaXMpLmZpbmQoXCJpbnB1dDpjaGVja2JveFwiKS5wcm9wKCdjaGVja2VkJywgdHJ1ZSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIGZ1bmN0aW9uIHVuY2hlY2tBbGwoKSB7XG4gICAgICAgIHN0cmFpbnNDaGVja2JveGVzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJCh0aGlzKS5maW5kKFwiaW5wdXQ6Y2hlY2tib3hcIikucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcbiAgICAgICAgfSk7XG4gICAgfVxufVxuXG4kKGZ1bmN0aW9uKCkge1xuICAgIHN0cmFpbnNGaWx0ZXIoJCggXCIjYmxhc3Rfc3RyYWluc0ZpbHRlcl9maWx0ZXJcIiApLCAkKCBcIiNibGFzdF9zdHJhaW5zRmlsdGVyX3N0cmFpbnNcIiApKTtcbiAgICBzdHJhaW5zRmlsdGVyKCQoIFwiI2FkdmFuY2VkX3NlYXJjaF9zdHJhaW5zRmlsdGVyX2ZpbHRlclwiICksICQoIFwiI2FkdmFuY2VkX3NlYXJjaF9zdHJhaW5zRmlsdGVyX3N0cmFpbnNcIiApKTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL3N0cmFpbnMtZmlsdGVyLmpzIiwidmFyIGRlbGF5ID0gcmVxdWlyZSgnLi9kZWxheScpO1xuXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpe1xuICAgIHZhciBwcm9jZXNzaW5nID0gZmFsc2U7XG4gICAgdmFyIHNlYXJjaCA9ICQoJyN1c2VyLXNlYXJjaC1maWVsZCcpO1xuICAgIHZhciB0ZWFtID0gJCgnI3VzZXItdGVhbS1maWVsZCcpO1xuXG4gICAgc2VhcmNoLmtleXVwKGZ1bmN0aW9uKCkge1xuICAgICAgICBoaXN0b3J5LnJlcGxhY2VTdGF0ZSgnJywgJycsIFJvdXRpbmcuZ2VuZXJhdGUoJ3VzZXJfaW5kZXgnLCB7IHE6IHNlYXJjaC52YWwoKSwgcDogMSB9KSk7XG5cbiAgICAgICAgZGVsYXkoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdHlwZTogJ0dFVCcsXG4gICAgICAgICAgICAgICAgdXJsOiBSb3V0aW5nLmdlbmVyYXRlKCd1c2VyX2luZGV4X2FqYXgnLCB7IHE6IHNlYXJjaC52YWwoKSwgcDogMSB9KSxcbiAgICAgICAgICAgICAgICBkYXRhVHlwZTogJ2h0bWwnLFxuICAgICAgICAgICAgICAgIGRlbGF5OiA0MDAsXG4gICAgICAgICAgICAgICAgYmVmb3JlU2VuZDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChwcm9jZXNzaW5nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBwcm9jZXNzaW5nID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGh0bWwpIHtcbiAgICAgICAgICAgICAgICAgICAgJCgnI3VzZXItbGlzdCcpLnJlcGxhY2VXaXRoKGh0bWwpO1xuICAgICAgICAgICAgICAgICAgICBwcm9jZXNzaW5nID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sIDQwMCApO1xuICAgIH0pO1xufSk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvdXNlci1pbnN0YW50LXNlYXJjaC5qcyJdLCJzb3VyY2VSb290IjoiIn0=