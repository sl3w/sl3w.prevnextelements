<?php

namespace Sl3w\PrevNextElements;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class AdminEvents
{
    public static function IBlocksPrevNextButtonsHandler(&$items)
    {
        $currentId = Helpers::Request()->get('ID');
        $currentIblockId = Helpers::Request()->get('IBLOCK_ID');

        $showButtonsIbs = Settings::getIBlocksShowButtons();
        $showButtons = in_array('all', $showButtonsIbs) || in_array($currentIblockId, $showButtonsIbs);

        $prevNextEls = Helpers::getPrevNextElementInIb($currentIblockId, $currentId);

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && Helpers::Application()->GetCurPage() == '/bitrix/admin/iblock_element_edit.php'
            && $showButtons && $currentId > 0) {

            $items[] = [
                'TEXT' => Loc::getMessage('SL3W_PREVNEXTBUTTONS_PREV'),
                'LINK' => ($prev = $prevNextEls['PREV']) ? self::prepareLink($currentIblockId, $prev['ID'], $prev['IBLOCK_SECTION_ID']) : 'javascript:void(0);',
                'TITLE' => Loc::getMessage('SL3W_PREVNEXTBUTTONS_PREV_TITLE'),
            ];

            $items[] = [
                'TEXT' => Loc::getMessage('SL3W_PREVNEXTBUTTONS_NEXT'),
                'LINK' => ($next = $prevNextEls['NEXT']) ? self::prepareLink($currentIblockId, $next['ID'], $next['IBLOCK_SECTION_ID']) : 'javascript:void(0);',
                'TITLE' => Loc::getMessage('SL3W_PREVNEXTBUTTONS_NEXT_TITLE'),
            ];
        }
    }

    private static function prepareLink($iblockId, $elId, $sectionId)
    {
        $type = Helpers::Request()->get('type');
        $lang = Helpers::Request()->get('lang');

        return sprintf('/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=%s&type=%s&lang=%s&ID=%s&find_section_section=%s', $iblockId, $type, $lang, $elId, $sectionId ?: 0);
    }
}