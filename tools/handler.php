<?php

if(PHP_SAPI == 'cli') {
    return;
} else {
    define('PESHEK_PATH_TO_JSCOLORMIN', '/bitrix/js/jscolor.ForBitrix/jscolor.min.js');
    define('PESHEK_PATH_TO_JSCOLOREVENTS', '/bitrix/js/jscolor.ForBitrix/jscolor_events.js');

    \AddEventHandler('iblock', 'OnIBlockPropertyBuildList', ['\\Peshek\\Properties\\ColorProperty', 'getDescription']);
    \AddEventHandler('main', 'OnUserTypeBuildList', ['\\Peshek\\Properties\\ColorProperty', 'getDescription']);
    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/jscolor.ForBitrix')) {
        mkdir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/jscolor.ForBitrix');
        copy(__DIR__ . '/../install/jscolor.min.js', $_SERVER['DOCUMENT_ROOT'] . PESHEK_PATH_TO_JSCOLORMIN);
        copy(__DIR__ . '/../install/jscolor_events.js', $_SERVER['DOCUMENT_ROOT'] . PESHEK_PATH_TO_JSCOLOREVENTS);
    }
}