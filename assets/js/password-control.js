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
    }else{
        numberChars.removeClass("fa-check");
        numberChars.addClass("fa-times");
        numberChars.css("color","#FF0004");
    }

    if(ucase.test(password1.val())){
        upperCase.removeClass("fa-times");
        upperCase.addClass("fa-check");
        upperCase.css("color","#00A41E");
    }else{
        upperCase.removeClass("fa-check");
        upperCase.addClass("fa-times");
        upperCase.css("color","#FF0004");
    }

    if(lcase.test(password1.val())){
        lowerCase.removeClass("fa-times");
        lowerCase.addClass("fa-check");
        lowerCase.css("color","#00A41E");
    }else{
        lowerCase.removeClass("fa-check");
        lowerCase.addClass("fa-times");
        lowerCase.css("color","#FF0004");
    }

    if(num.test(password1.val())){
        number.removeClass("fa-times");
        number.addClass("fa-check");
        number.css("color","#00A41E");
    }else{
        number.removeClass("fa-check");
        number.addClass("fa-times");
        number.css("color","#FF0004");
    }

    if(password1.val() === password2.val() && password1.val() !== ''){
        passwordMatch.removeClass("fa-times");
        passwordMatch.addClass("fa-check");
        passwordMatch.css("color","#00A41E");
    }else{
        passwordMatch.removeClass("fa-check");
        passwordMatch.addClass("fa-times");
        passwordMatch.css("color","#FF0004");
    }
});
