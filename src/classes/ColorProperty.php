<?php

namespace Maximaster\Properties;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Класс, который отвечает за отображение кастомного свойства типа "Цвет"
 */
class ColorProperty
{
    /**
     * Получение описания пользовательского свойства
     * @return array
     */
    public function getDescription()
    {
        static::initJsConfig();
        \CUtil::InitJSCore(['jscolor', 'jscolor_events']);
        return [
            //для пользовательских полей
            //*начало*
            'USER_TYPE_ID'          => 'Color',
            'CLASS_NAME'            => 'ColorProperty',
            'BASE_TYPE'             => 'string',
            //*конец*
            'DESCRIPTION'           => Loc::getMessage('CUSTOM_PROPERTY_COLOR:DESCRIPTION'),
            'USER_TYPE'             => 'Color',
            'PROPERTY_TYPE'         => 'S',
            'GetAdminListEditHTML'  => [static::class, 'getAdminListEditHTML'],
            'GetAdminListViewHTML'  => [static::class, 'getAdminListViewHTML'],
            'GetEditFormHTML'       => [static::class, 'getEditFormHTML'],
            'GetPropertyFieldHtml'  => [static::class, 'getPropertyFieldHtml'],
            'GetAdminFilterHTML'    => [static::class, 'getAdminFilterHTML'],
            'GetFilterHTML'         => [static::class, 'getFilterHTML'],
            'PrepareSettings'       => [static::class, 'prepareSettings'],
            'GetSettingsHTML'       => [static::class, 'getSettingsHTML'],
            'ConvertToDB'           => [static::class, 'convertToDB'],
            'ConvertFromDB'         => [static::class, 'convertFromDB']
        ];
    }

    /**
     * Регистрация JS для последующего подключения
     */
    protected function initJsConfig()
    {
        $arJsConfig = [
            'jscolor' => [
                'js' => '/bitrix/js/jscolor.forBitrix/jscolor.min.js'
            ],
            'jscolor_events' => [
                'js' => '/bitrix/js/jscolor.forBitrix/jscolor_events.js'
            ],
        ];
        foreach ($arJsConfig as $ext => $arExt) {
            \CJSCore::RegisterExt($ext, $arExt);
        }
    }

    /**
     * Перевод из hex в RGB
     * @param $color - hex-код цвета
     * @return array|bool - RGB
     */
    protected function hexToRgb($color)
    {
        // проверяем наличие # в начале, если есть, то отрезаем ее
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        // разбираем строку на массив
        if (strlen($color) == 6) {
            list($red, $green, $blue) = [
                $color[0] . $color[1],
                $color[2] . $color[3],
                $color[4] . $color[5]
            ];
        } else {
            return false;
        }

        // переводим шестнадцатиричные числа в десятичные
        $red = hexdec($red);
        $green = hexdec($green);
        $blue = hexdec($blue);

        return [$red, $green, $blue];
    }

    /**
     * Получение цвета
     * @param $property array - настройки свойства
     * @param $value string - текущее значение
     * @return string hex-код цвета
     */
    protected function getColor($property, $value)
    {
        return empty($value) && $property['USER_TYPE_SETTINGS']['USE_DEFAULT_VALUE'] == 'Y' ?
            $property['DEFAULT_VALUE'] :
            $value;
    }

    /**
     * Получение html инпута для редактирования свойства
     * @param $property array - настройки свойства
     * @param $name string - атрбиут name
     * @param $value  string - значение свойства
     * @return string HTML
     */
    protected function getEditHTML($property, $name, $value)
    {
        $value = static::getColor($property, $value);
        $html = '<input class="jscolor" type="text" data-jscolor="{required:false, hash:true, zIndex: 1100, closable:true, closeText:\'X\'}"  name="' . $name . '" value="' . $value . '">';

        return $html;
    }

    /**
     * Получение отображения значения свойства
     * @param $property array - настройки свойства
     * @param $value string - значение свойства
     * @return string HTML
     */
    protected function getViewHTML($property, $value)
    {
        $value = static::getColor($property, $value);
        $rgb = static::hexToRgb($value);

        $isLight = (0.213 * $rgb[0] + 0.715 * $rgb[1] + 0.072 * $rgb[2]) > (255 / 2);

        $textColor = $isLight ? '#000000' : '#FFFFFF';

        return '
        <div style="background-color:' . $value . '; width: 50px; height: 30px; line-height: 30px; text-align: center; ">
            <span style="vertical-align: middle; color:' . $textColor . '; font-size: 10px;">' . $value . '</span>
        </div>';
    }

    /**
     * Получение поля редактирования свойства на странице редактирования пользовательского свойства
     * @param array $property - настройки свойства
     * @param array $value - значение свойства
     * @return string HTML
     */
    public function getAdminListEditHTML($property, $value)
    {
        return static::getEditHTML($property, $value['NAME'], $value['VALUE']);
    }

    /**
     * Получение отображения значения свойства в списке в главном модуле
     * @param $property array - настройки свойства
     * @param $value string - значение свойства
     * @return string HTML
     */
    public function getAdminListViewHTML($property, $value)
    {
        return static::getViewHTML($property, $value['VALUE']);
    }

    /**
     * Получение поля редактирования свойства на странице редактирования пользовательского свойства
     * @param array $property - настройки свойства
     * @param array $value - значение свойства
     * @return string HTML
     */
    public function getEditFormHTML($property, $value)
    {
        return static::getEditHTML($property, $value['NAME'], $value['VALUE']);
    }

    /**
     * Получение поля редактирования свойства на странице редактирования элемента
     * @param $property array - настройки свойства
     * @param $value string - значение свойства
     * @param $htmlControlName string - атрибут name
     * @return string HTML
     */
    public function getPropertyFieldHtml($property, $value, $htmlControlName)
    {
        return static::getEditHTML($property, $htmlControlName['VALUE'], $value['VALUE']);
    }

    /**
     * Получение HTML для фильтра по свойству в модуле ИБ
     * @param array $property - настройки свойства
     * @param array $htmlControlName
     * @return string HTML
     */
    public function getAdminFilterHTML($property, $htmlControlName)
    {
        $html = '<div style="margin-left: 12px;">';
        $html .= static::getEditHTML($property, $htmlControlName['VALUE'], '');
        $html .= '</div>';
        return $html;
    }

    /**
     * Получение HTML для фильтра по свойству в главном модуле
     * @param array $property - настройки свойства
     * @param array $htmlControlName
     * @return string HTML
     */
    public function getFilterHTML($property, $htmlControlName)
    {
        $html = '<div style="margin-left: 12px;">';
        $html .= static::getEditHTML($property, $htmlControlName['NAME'], '');
        $html .= '</div>';
        return $html;
    }

    /**
     * Подготовка настроек
     * @param array $property
     * @return array изменённые значения
     */
    public function prepareSettings($property)
    {
        if (is_array($property['USER_TYPE_SETTINGS']) && $property['USER_TYPE_SETTINGS']['USE_DEFAULT_VALUE'] === 'Y') {
            $useDefaultValue = 'Y';
        } else {
            $useDefaultValue = 'N';
        }

        return [
            'USE_DEFAULT_VALUE' => $useDefaultValue
        ];
    }

    /**
     * Получение настроек
     * @param array $property - настройки свойства
     * @param array $htmlControlName - атрибут name
     * @param $propertyFields array - поля для настройки
     * @return string HTML
     */
    public function getSettingsHTML($property, $htmlControlName, &$propertyFields)
    {
        $settings = static::PrepareSettings($property);

        $propertyFields = [
            'HIDE' => ['ROW_COUNT', 'COL_COUNT']
        ];
        return '
            <tr valign="top">
                <td>' . Loc::getMessage('CUSTOM_PROPERTY_COLOR:USE_DEFAULT_VALUE') . '</td>
                <td><input type="checkbox" name="' . $htmlControlName["NAME"] . '[USE_DEFAULT_VALUE]" value="Y" ' . ($settings['USE_DEFAULT_VALUE'] == 'Y' ? 'checked' : '') . '></td>
		    </tr>
		';
    }

    /**
     * Запись в БД
     * @param $property
     * @param $value
     * @return mixed
     */
    public function convertToDB($property, $value)
    {
        return $value;
    }

    /**
     * Получение из БД
     * @param $property
     * @param $value
     * @return mixed
     */
    public function convertFromDB($property, $value)
    {
        return $value;
    }
}