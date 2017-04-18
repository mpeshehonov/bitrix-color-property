<?php

if(PHP_SAPI == 'cli') {
    return;
}
else {
    AddEventHandler('iblock', 'OnIBlockPropertyBuildList', ['\\Peshek\\Properties\\ColorProperty', 'getDescription']);
    AddEventHandler('main', 'OnUserTypeBuildList', ['\\Peshek\\Properties\\ColorProperty', 'getDescription']);
    $pathToJsColor = '/bitrix/js/jscolor.ForBitrix/jscolor.min.js';
    $pathToJsEvents = '/bitrix/js/jscolor.ForBitrix/jscolor_events.js';
    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/jscolor.ForBitrix')) {
        mkdir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/jscolor.ForBitrix');
        copy(__DIR__ . '/../install/jscolor.min.js', $_SERVER['DOCUMENT_ROOT'] . $pathToJsColor);
        copy(__DIR__ . '/../install/jscolor_events.js', $_SERVER['DOCUMENT_ROOT'] . $pathToJsEvents);
    }
}