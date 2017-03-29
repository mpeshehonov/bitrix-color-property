<?php

if(PHP_SAPI == 'cli') {
    return;
}
else {
    AddEventHandler('iblock', 'OnIBlockPropertyBuildList', ['ColorProperty', 'getDescription']);
    AddEventHandler('main', 'OnUserTypeBuildList', ['ColorProperty', 'getDescription']);
    if(file_exists(DOCUMENT_ROOT . '/bitrix/js/jscolor.ForBitrix')) {
        return;
    } else {
        mkdir('/bitrix/js/jscolor.ForBitrix');
        copy(__DIR__ . '/../install/jscolor.min.js', DOCUMENT_ROOT . '/bitrix/js/jscolor.ForBitrix/jscolor.min.js');
        copy(__DIR__ . '../install/jscolor_events.js', DOCUMENT_ROOT . '/bitrix/js/jscolor.ForBitrix/jscolor_events.js');
    }
}
