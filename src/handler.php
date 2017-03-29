<?php

if(PHP_SAPI == 'cli') {
    return;
}
else {
    AddEventHandler('iblock', 'OnIBlockPropertyBuildList', ['ColorProperty', 'getDescription']);
    AddEventHandler('main', 'OnUserTypeBuildList', ['ColorProperty', 'getDescription']);
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/jscolor.ForBitrix')) {
        return;
    } else {
        mkdir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/jscolor.ForBitrix');
        copy(__DIR__ . '/../install/jscolor.min.js', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/jscolor.ForBitrix/jscolor.min.js');
        copy(__DIR__ . '/../install/jscolor_events.js', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/jscolor.ForBitrix/jscolor_events.js');
    }
}
