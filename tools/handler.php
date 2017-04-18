<?php

if(PHP_SAPI == 'cli') {
    return;
}
else {
    define('PATH_TO_JSCOLOR_DIR', '/bitrix/js/jscolor.ForBitrix');
    define('PATH_TO_JSCOLORMIN', '/bitrix/js/jscolor.ForBitrix/jscolor.min.js');
    define('PATH_TO_JSCOLOREVENTS', '/bitrix/js/jscolor.ForBitrix/jscolor_events.js');
    define('CLASS_NAME_OF_JSCOLOR_USERTYPE', '\\Peshek\\Properties\\ColorProperty');
    AddEventHandler('iblock', 'OnIBlockPropertyBuildList', [CLASS_NAME_OF_JSCOLOR_USERTYPE, 'getDescription']);
    AddEventHandler('main', 'OnUserTypeBuildList', [CLASS_NAME_OF_JSCOLOR_USERTYPE, 'getDescription']);
    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . PATH_TO_JSCOLOR_DIR)) {
        mkdir($_SERVER['DOCUMENT_ROOT'] . PATH_TO_JSCOLOR_DIR);
        copy(__DIR__ . '/../install/jscolor.min.js', $_SERVER['DOCUMENT_ROOT'] . PATH_TO_JSCOLORMIN);
        copy(__DIR__ . '/../install/jscolor_events.js', $_SERVER['DOCUMENT_ROOT'] . PATH_TO_JSCOLOREVENTS);
    }
}