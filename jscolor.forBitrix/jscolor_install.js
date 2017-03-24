BX.addCustomEvent('onFixedNodeChangeState', function () {
    if(document.getElementsByClassName('jscolor').length > 0) {
        jscolor.installByClassName('jscolor');
    }
});
BX.addCustomEvent('onAjaxSuccess', function () {
    if(document.getElementsByClassName('jscolor').length > 0) {
        jscolor.installByClassName('jscolor');
    }
});


