/*
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

var valid = {
    'numberChars': false,
    'upperCase': false,
    'lowerCase': false,
    'number': false,
    'passwordMatch': false
};

// Set submitButton
var submit = $('form[name="change_password"] :submit');
submit.prop('disabled', true);

// On each keyup, test
$("input[type=password][id*='_plainPassword_']").keyup(function(){
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
    if(password1.val().length >= 8){
        numberChars.removeClass("fa-times");
        numberChars.addClass("fa-check");
        numberChars.css("color","#00A41E");
        valid['numberChars'] = true;
    }else{
        numberChars.removeClass("fa-check");
        numberChars.addClass("fa-times");
        numberChars.css("color","#FF0004");
        valid['numberChars'] = false;
    }

    if(ucase.test(password1.val())){
        upperCase.removeClass("fa-times");
        upperCase.addClass("fa-check");
        upperCase.css("color","#00A41E");
        valid['upperCase'] = true;
    }else{
        upperCase.removeClass("fa-check");
        upperCase.addClass("fa-times");
        upperCase.css("color","#FF0004");
        valid['upperCase'] = false;
    }

    if(lcase.test(password1.val())){
        lowerCase.removeClass("fa-times");
        lowerCase.addClass("fa-check");
        lowerCase.css("color","#00A41E");
        valid['lowerCase'] = true;
    }else{
        lowerCase.removeClass("fa-check");
        lowerCase.addClass("fa-times");
        lowerCase.css("color","#FF0004");
        valid['lowerCase'] = false;
    }

    if(num.test(password1.val())){
        number.removeClass("fa-times");
        number.addClass("fa-check");
        number.css("color","#00A41E");
        valid['number'] = true;
    }else{
        number.removeClass("fa-check");
        number.addClass("fa-times");
        number.css("color","#FF0004");
        valid['number'] = false;
    }

    if(password1.val() === password2.val() && password1.val() !== ''){
        passwordMatch.removeClass("fa-times");
        passwordMatch.addClass("fa-check");
        passwordMatch.css("color","#00A41E");
        valid['passwordMatch'] = true;
    }else{
        passwordMatch.removeClass("fa-check");
        passwordMatch.addClass("fa-times");
        passwordMatch.css("color","#FF0004");
        valid['passwordMatch'] = false;
    }

    // Test if all tests are valid or not
    var allValid = true;
    $.each(valid, function(index, value) {
       if (false === value) {
           allValid = false;
           return false;
       }
    });

    // If all tests are valid, enable the button
    if (allValid) {
        submit.prop('disabled', false);
    } else { // Else disable it
        submit.prop('disabled', true);
    }
});
