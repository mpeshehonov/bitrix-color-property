<?php

class CustomPropertyColor extends CUserTypeString
{
    /**
     * Получение описания пользовательского свойства
     * @return array
     */
    public function GetUserTypeDescription()
    {
        static::initJsConfig();
        CUtil::InitJSCore(['jscolor_core','jscolor_install','jscolor_multiple']);
        return array(
            "USER_TYPE_ID" => "Color",
            "CLASS_NAME" => "CustomPropertyColor",
            "DESCRIPTION" => "Цвет",
            "BASE_TYPE" => "string",
        );
    }

    /**
     * Получение описания свойства в модуле ИБ
     * @return array
     */
    public function GetIBlockPropertyDescription()
    {
        static::initJsConfig();
        CUtil::InitJSCore(['jscolor_core','jscolor_install']);
        return [
            'USER_TYPE' => 'Color',
            'PROPERTY_TYPE' => 'S',
            'DESCRIPTION' => 'Цвет',
            'GetPropertyFieldHtml'      => [static::class, 'GetPropertyFieldHtml'],
            'GetPropertyFieldHtmlMulty' => [static::class, 'GetPropertyFieldHtmlMulty'],
            'GetAdminListViewHTML'      => [static::class, 'GetAdminListViewHTML'],
            'ConvertToDB'               => [static::class, 'ConvertToDB'],
            'ConvertFromDB'             => [static::class, 'ConvertFromDB'],
            'GetSettingsHTML'           => [static::class, 'GetSettingsHTML'],
            'GetAdminFilterHTML'        => [static::class, 'GetAdminFilterHTML'],
            'PrepareSettings'           => [static::class, 'PrepareSettings'],
        ];
    }

    /**
     * Регистрация JS для последующего подключения
     */
    protected function initJsConfig() {
        $arJsConfig = array(
            'jscolor_core' => array(
                'js' => '/bitrix/js/jscolor.forBitrix/jscolor_core.js'
            ),
            'jscolor_multiple' => array(
                'js' => '/bitrix/js/jscolor.forBitrix/jscolor_multiple.js'
            ),
            'jscolor_install' => array(
                'js' => '/bitrix/js/jscolor.forBitrix/jscolor_install.js'
            ),
        );
        foreach ($arJsConfig as $ext => $arExt) {
            \CJSCore::RegisterExt($ext, $arExt);
        }
    }

    /**
     * Получение цвета
     * @param $arProperty array - настройки свойства
     * @param $value string - текущее значение
     * @return string hex-код цвета
     */
    protected function getColor($arProperty, $value)
    {
        if(empty($value) && $arProperty['USER_TYPE_SETTINGS']['USE_DEFAULT_VALUE'] == 'Y'){
            $color = $arProperty['DEFAULT_VALUE'];
        }
        else {
            return $value;
        }
        return $color;
    }

    /**
     * Получение html инпута для редактирования свойства
     * @param $arProperty array - настройки свойства
     * @param $name string - атрбиут name
     * @param $value  string - значение свойства
     * @return string HTML
     */
    function getEditHTML($arProperty, $name, $value)
    {
        if (empty($value)) {
            $value = static::getColor($arProperty, $value);
        }
        $html =  '<input class="jscolor" type="text" data-jscolor="{required:false, hash:true, closable:true, closeText:\'X\'}"  name="' . $name . '" value="' . $value . '">';

        return $html;
    }

    /**
     * Получение таблицы с инпутами для редактирования множественного свойства
     * @param $arProperty array - настройки свойства
     * @param $inputs array - массив с инпутами
     * @return string HTML
     */
    function getEditHTMLMulty($arProperty, $inputs)
    {
        $html = '<table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%">';

        $html .= '<tr><td>';

        $html .= implode('</td></tr><tr><td>', $inputs);

        $html .= '</td></tr>';

        $html .= '</table>';

        $html .= '<input type="button" id="jsColorPickerAdd" value="' . 'Добавить цвет' . '">';

        return $html;
    }


    /**
     * Получение отображения значения свойства
     * @param $arProperty array - настройки свойства
     * @param $value string - значение свойства
     * @return string HTML
     */
    function getViewHTML($arProperty, $value)
    {
        if (empty($value)) {
            $value = static::getColor($arProperty, $value);
        }
        if (substr($value, 1, 2) == '0000') {
            $textColor = '#ffffff';
        } else {
            $textColor = '#000000';
        }
        return '
        <div style="background-color:' . $value . '; width: 50px; height: 30px; line-height: 30px; text-align: center; ">
            <span style="vertical-align: middle; color:' . $textColor . '; font-size: 10px; font-family: segoe ui;">' . $value . '</span>
        </div>';
    }


    /**
     * Получение отображения значения свойства в списке в главном модуле
     * @param $arProperty array - настройки свойства
     * @param $value string - значение свойства
     * @return string HTML
     */
    function GetAdminListViewHTML($arProperty, $value)
    {
        return static::getViewHTML($arProperty, $value['VALUE']);
    }

    /**
     * Получение html инпута для редактирования свойства в списке в главном модуле
     * @param array $arProperty - настройки свойства
     * @param array $value - значение свойства
     * @return string HTML
     */
    function GetAdminListEditHTML($arProperty, $value)
    {
        return static::getEditHTML($arProperty, $value['NAME'], $value['VALUE']);
    }


    /**
     * Подготовка настроек
     * @param array $arProperty
     * @return array изменённые значения
     */
    function PrepareSettings($arProperty)
    {
        if(is_array($arProperty["USER_TYPE_SETTINGS"]) && $arProperty["USER_TYPE_SETTINGS"]["USE_DEFAULT_VALUE"] === "Y") {
            $useDefaultValue = "Y";
        } else {
            $useDefaultValue = "N";
        }

        return [
            'USE_DEFAULT_VALUE' => $useDefaultValue
        ];
    }

    /**
     * Получение настроек
     * @param array $arProperty - настройки свойства
     * @param array $strHTMLControlName - атрибут name
     * @param $arPropertyFields array - поля для настройки
     * @return string HTML
     */
    function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {
        $settings = static::PrepareSettings($arProperty);

        $arPropertyFields = [
            'HIDE' => ['ROW_COUNT', 'COL_COUNT']
        ];
        return '
            <tr valign="top">
                <td>Использовать значение по умолчанию:</td>
                <td><input type="checkbox" name="'.$strHTMLControlName["NAME"].'[USE_DEFAULT_VALUE]" value="Y" '.($settings["USE_DEFAULT_VALUE"]=="Y"? 'checked': '').'></td>
		    </tr>
		';
    }

    /**
     * Получение поля редактирования свойства на странице редактирования элемента
     * @param $arProperty array - настройки свойства
     * @param $value string - значение свойства
     * @param $strHTMLControlName string - атрибут name
     * @return string HTML
     */
    function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        return static::getEditHTML($arProperty, $strHTMLControlName['VALUE'], $value['VALUE']);
    }

    /**
     * Получение поля редактирования свойства на странице редактирования пользовательского свойства
     * @param array $arProperty - настройки свойства
     * @param array $value - значение свойства
     * @return string HTML
     */
    function GetEditFormHTML($arProperty, $value)
    {
        return static::getEditHTML($arProperty, $value['NAME'], $value['VALUE']);
    }

    /**
     * Получение множественной формы для редактирования свойств в списке в главном модуле
     * @param array $arProperty - настройки свойства
     * @param array $value - значение свойства
     * @return string HTML
     */
    function GetAdminListEditHTMLMulty($arProperty, $value)
    {
        $inputs = [];
        $name = $value['NAME'];
        $first = strpos($value['NAME'],'[]');
        $second = strpos($value['NAME'], ']',$first);
        if (is_array($value['VALUE']) && !empty($value['VALUE'])) {
            foreach ($value['VALUE'] as $valueId => $valueColor) {
                $valueName = substr($name,0,$first+1).$valueId.substr($name,$second);
                $inputs[] = static::getEditHTML($arProperty, $valueName, $valueColor);
            }
        }
        else {
            $valueName = substr($name,0,$first+1).'0'.substr($name,$second);
            $inputs[] = static::getEditHTML($arProperty, $valueName, '');
        }

        $html = static::getEditHTMLMulty($arProperty,$inputs);

        return $html;

    }


    /**
     * Получение множественной формы для редактирования свойств на странице редактирования пользовательских свойств
     * @param array $arProperty - настройки свойства
     * @param array $value - значение свойств
     * @return string HTML
     */
    function GetEditFormHTMLMulty($arProperty, $value)
    {
        CUtil::InitJSCore(['jscolor_multiple']);
        $inputs = [];
        $name = $value['NAME'];
        $first = strpos($value['NAME'],'[');
        $second = strpos($value['NAME'], ']');
        if (is_array($value['VALUE']) && !empty($value['VALUE'])) {
            foreach ($value['VALUE'] as $valueId => $valueColor) {
                $valueName = substr($name,0,$first+1).$valueId.substr($name,$second);
                $inputs[] = static::getEditHTML($arProperty, $valueName, $valueColor);
            }
        }
        else {
            $valueName = substr($name,0,$first+1).'0'.substr($name,$second);
            $inputs[] = static::getEditHTML($arProperty, $valueName, '');
        }

        $html = static::getEditHTMLMulty($arProperty,$inputs);

        return $html;
    }


    /**
     * Получение полей редактирования свойства на странице редактирования элемента
     * @param $arProperty array - настройки свойства
     * @param $value array - значение свойств
     * @param $strHTMLControlName string - атрибут name
     * @return string HTML
     */
    function GetPropertyFieldHtmlMulty($arProperty, $arValues, $strHTMLControlName)
    {
        CUtil::InitJSCore(['jscolor_multiple']);
        $inputs = [];
        if (is_array($arValues) && !empty($arValues)) {
            foreach ($arValues as $valueId => $value) {
                $inputs[] = self::getEditHTML($arProperty, $strHTMLControlName['VALUE'] . '[' . $valueId . '][VALUE]',
                    $value['VALUE']);
            }
        }
        $count = count($inputs);
        if ($count < $arProperty['MULTIPLE_CNT']) {
            for ($i = 0; $i < $arProperty['MULTIPLE_CNT'] - $count; $i++) {
                $inputs[] = self::getEditHTML($arProperty, $strHTMLControlName['VALUE'] . '[n' . $i . '][VALUE]',
                    $arValues[$i]['VALUE']);
            }
        }

        $html = '<table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%">';

        $html .= '<tr><td>';

        $html .= implode('</td></tr><tr><td>', $inputs);

        $html .= '</td></tr>';

        $html .= '</table>';

        $html .= '<input type="button" id="jsColorPickerAdd" value="' . 'Добавить цвет' . '">';

        return $html;

    }


    /**
     * Получение HTML для фильтра по свойству в модуле ИБ
     * @param array $arProperty - настройки свойства
     * @param array $strHTMLControlName
     * @return string HTML
     */
    function GetAdminFilterHTML($arProperty, $strHTMLControlName)
    {
        $html = '<div style="margin-left: 20px;">';
        $html .= static::getEditHTML($arProperty,$strHTMLControlName['VALUE'],'');
        $html .= '</div>';
        return $html;
    }

    /**
     * Получение HTML для фильтра по свойству в главном модуле
     * @param array $arProperty - настройки свойства
     * @param array $strHTMLControlName
     * @return string HTML
     */
    function GetFilterHTML($arUserField, $arHTMLControl)
    {
        $html = '<div style="margin-left: 20px;">';
        $html .= static::getEditHTML($arUserField,$arHTMLControl['NAME'],'');
        $html .= '</div>';
        return $html;
    }

    /**
     * Запись в БД
     * @param $arProperty
     * @param $value
     * @return
     */
    function ConvertToDB($arProperty, $value)
    {
        return $value;
    }

    /**
     * Получение из БД
     * @param $arProperty
     * @param $value
     * @return mixed
     */
    function ConvertFromDB($arProperty, $value)
    {
        return $value;
    }
}