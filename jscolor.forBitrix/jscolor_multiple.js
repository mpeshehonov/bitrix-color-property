var jsColorMultipleAdd = function () {
    var jsColorPickerAdd = document.getElementById('jsColorPickerAdd');
    if(jsColorPickerAdd) {
        jsColorPickerAdd.addEventListener('click', function () {
            var row_to_clone = -1;
            var tbl = jsColorPickerAdd.closest('table').querySelector('table');
            var cnt = tbl.rows.length;
            if (row_to_clone == null)
                row_to_clone = -2;
            var oRow = tbl.insertRow(cnt + row_to_clone + 1);
            var oCell = oRow.insertCell(0);
            var HTMLCollection = tbl.rows[cnt + row_to_clone].cells[0].getElementsByClassName('jscolor');
            var HTMLNode = HTMLCollection[0];
            var HTMLCopy = HTMLNode.cloneNode(false);
            var HTMLname = HTMLCopy.getAttribute('name');
            var isIBlockProperty = HTMLname.indexOf('[n', 0) < 0 && HTMLname.indexOf('UF') < 0;
            var isListEditField = HTMLname.indexOf('FIELDS') >= 0;
            var isMainModuleProperty = HTMLname.indexOf('UF') >= 0;
            console.log(HTMLname);
            var s, e, n, p;
            p = 0;
            while (true) {
                s = HTMLname.indexOf('[n', p);
                if (s < 0)break;
                e = HTMLname.indexOf(']', s);
                if (e < 0)break;
                n = parseInt(HTMLname.substr(s + 2, e - s));
                HTMLname = HTMLname.substr(0, s) + '[n' + (++n) + ']';// + HTMLname.substr(e + 1);
                p = s + 1;
            }
            p = isListEditField ? 20 : 5;
            if (isIBlockProperty) {
                s = HTMLname.indexOf('[', p);
                e = HTMLname.indexOf(']', s);
                HTMLname = HTMLname.substr(0, s) + '[n' + (0) + ']' + HTMLname.substr(e + 1);
            }
            p = isListEditField ? 20 : 0;
            if (isMainModuleProperty) {
                s = HTMLname.indexOf('[', p);
                e = HTMLname.indexOf(']', s);
                n = parseInt(HTMLname.substr(s + 1, e - s));
                HTMLname = HTMLname.substr(0, s + 1) + (++n) + HTMLname.substr(e);
            }
            p = 0;
            while (true) {
                s = HTMLname.indexOf('__n', p);
                if (s < 0)break;
                e = HTMLname.indexOf('_', s + 2);
                if (e < 0)break;
                n = parseInt(HTMLname.substr(s + 3, e - s));
                HTMLname = HTMLname.substr(0, s) + '__n' + (++n) + '_' + HTMLname.substr(e + 1);
                p = e + 1;
            }
            p = 0;
            while (true) {
                s = HTMLname.indexOf('__N', p);
                if (s < 0)break;
                e = HTMLname.indexOf('__', s + 2);
                if (e < 0)break;
                n = parseInt(HTMLname.substr(s + 3, e - s));
                HTMLname = HTMLname.substr(0, s) + '__N' + (++n) + '__' + HTMLname.substr(e + 2);
                p = e + 2;
            }
            p = 0;
            while (true) {
                s = HTMLname.indexOf('xxn', p);
                if (s < 0)break;
                e = HTMLname.indexOf('xx', s + 2);
                if (e < 0)break;
                n = parseInt(HTMLname.substr(s + 3, e - s));
                HTMLname = HTMLname.substr(0, s) + 'xxn' + (++n) + 'xx' + HTMLname.substr(e + 2);
                p = e + 2;
            }
            p = 0;
            while (true) {
                s = HTMLname.indexOf('%5Bn', p);
                if (s < 0)break;
                e = HTMLname.indexOf('%5D', s + 3);
                if (e < 0)break;
                n = parseInt(HTMLname.substr(s + 4, e - s));
                HTMLname = HTMLname.substr(0, s) + '%5Bn' + (++n) + '%5D' + HTMLname.substr(e + 3);
                p = e + 3;
            }
            HTMLCopy.setAttribute('name', HTMLname);
            var picker = new jscolor(HTMLCopy);
            picker.hash = true;
            picker.required = false;
            picker.closable = true;
            picker.closeText = 'X';
            oCell.appendChild(HTMLCopy);
        });
    }
};

BX.addCustomEvent('onFixedNodeChangeState', function () {
    var jsColorPickerAdd = document.getElementById('jsColorPickerAdd');
    if (jsColorPickerAdd) {
        if (jsColorPickerAdd.hasAttribute('hasListener')) {
            return;
        }
        else {
            jsColorMultipleAdd();
            jsColorPickerAdd.setAttribute('hasListener', 'yes');
        }
    }
});





