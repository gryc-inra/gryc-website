function copy2clipboard(dataSelector) {
    dataSelector.select();
    document.execCommand('copy');
}

function copy2clipboardOnClick(clickTrigger, dataSelector) {
    clickTrigger.click(function(){
        copy2clipboard(dataSelector);
    });
}

$(function() {
   copy2clipboardOnClick($("#reverse-complement-copy-button"), $("#reverse-complement-result"));
});
