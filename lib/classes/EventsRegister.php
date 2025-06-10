<?php

namespace Sl3w\PrevNextElements;

class EventsRegister
{
    /**
     * @param bool $reg 'true' if register, 'false' if unregister
     * @return void
     */
    public static function addPrevNextButtons($reg)
    {
        if ($reg) {
            self::registerAddPrevNextButtonsEvents();
        } else {
            self::unRegisterAddPrevNextButtonsEvents();
        }
    }

    private static function registerAddPrevNextButtonsEvents()
    {
        Helpers::EventManager()->registerEventHandler(
            'main',
            'OnAdminContextMenuShow',
            Settings::MODULE_ID,
            'Sl3w\PrevNextElements\AdminEvents',
            'IBlocksPrevNextButtonsHandler'
        );
    }

    private static function unRegisterAddPrevNextButtonsEvents()
    {
        Helpers::EventManager()->unRegisterEventHandler(
            'main',
            'OnAdminContextMenuShow',
            Settings::MODULE_ID,
            'Sl3w\PrevNextElements\AdminEvents',
            'IBlocksPrevNextButtonsHandler'
        );
    }
}