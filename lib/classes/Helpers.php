<?php

namespace Sl3w\PrevNextElements;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Page\Asset;

class Helpers
{
    public static function yes($value)
    {
        return $value == 'yes';
    }

    public static function arrayWrap($value)
    {
        return is_array($value) ? $value : [$value];
    }

    public static function arrayTrimExplode($array, $separator = ',')
    {
        return array_map('trim', explode($separator, $array));
    }

    public static function includeModules($modulesName)
    {
        $modulesName = self::arrayWrap($modulesName);

        foreach ($modulesName as $moduleName) {
            self::includeModule($moduleName);
        }
    }

    public static function includeModule($moduleName)
    {
        return Loader::includeModule($moduleName);
    }

    public static function getPrevNextElementInIb($iblockId, $elementId)
    {
        self::includeModule('iblock');

        $allElements = [];

        $resEls = \CIBlockElement::GetList(['ID' => 'ASC'], ['IBLOCK_ID' => $iblockId], false, false, ['ID', 'IBLOCK_SECTION_ID']);

        while ($el = $resEls->Fetch()) {
            $allElements[$el['ID']] = $el;
        }

        if (count($allElements) === 1) {
            return [
                'PREV' => false,
                'NEXT' => false,
            ];
        }

        while (key($allElements) != $elementId) next($allElements);

        $prev = prev($allElements);

        if (!$prev) {
            $prev = $allElements[array_key_last($allElements)];
            reset($allElements);
            $next = next($allElements);
        } else {
            next($allElements);
            $next = next($allElements);

            if (!$next) {
                $next = $allElements[array_key_first($allElements)];
            }
        }

        return [
            'PREV' => $prev,
            'NEXT' => $next,
        ];
    }

    public static function Application()
    {
        global $APPLICATION;

        return $APPLICATION;
    }

    public static function Request()
    {
        return Application::getInstance()->getContext()->getRequest();
    }

    public static function EventManager()
    {
        return EventManager::getInstance();
    }

    public static function Asset()
    {
        return Asset::getInstance();
    }
}