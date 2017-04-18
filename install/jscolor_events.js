BX(function () {
    var className = 'jscolor';

    var installJsColor = function (className) {
        if (document.getElementsByClassName(className).length > 0) {
            jscolor.installByClassName(className);
        }
    };

    BX.addCustomEvent('onFixedNodeChangeState', function () {
        installJsColor(className);
        multyJsColorHandler();
    });
    BX.addCustomEvent('onAjaxSuccess', function () {
        installJsColor(className);
    });
    BX.addCustomEvent('onAdminTabsChange', function () {
        installJsColor(className);
        multyJsColorHandler();
    });

    var multyJsColorHandler = function () {
        document.querySelector('body').onclick = function(event) {
            var target = event.target;

            if(target.matches('input[type="button"]')) {

                var parentRow = target.parentNode.parentNode;

                var previousRow = parentRow.previousSibling;

                var jsColorInput = previousRow.firstChild.firstChild;

                if(!jsColorInput.matches('input[type="text"].jscolor')) {
                    return;
                }

                installJsColor(className);
            } else if(target.matches('input[type="text"].jscolor')) {
                target.addEventListener('keydown', function () {
                    jscolor.hide();
                })
            }

        };
    }
});