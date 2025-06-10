<?php

use Bitrix\Main\Localization\Loc;
use Sl3w\PrevNextElements\Settings;
use Sl3w\PrevNextElements\EventsRegister;

Loc::loadMessages(__FILE__);

class sl3w_prevnextelements extends CModule
{
    var $MODULE_ID = 'sl3w.prevnextelements';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;
    var $MODULE_DIR;

    public function __construct()
    {
        if (file_exists(__DIR__ . '/version.php')) {

            $arModuleVersion = [];

            include(__DIR__ . '/version.php');

            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

            $this->MODULE_NAME = Loc::getMessage('SL3W_PREVNEXTBUTTONS_MODULE_NAME');
            $this->MODULE_DESCRIPTION = Loc::getMessage('SL3W_PREVNEXTBUTTONS_MODULE_DESC');

            $this->PARTNER_NAME = Loc::getMessage('SL3W_PREVNEXTBUTTONS_PARTNER_NAME');
            $this->PARTNER_URI = Loc::getMessage('SL3W_PREVNEXTBUTTONS_PARTNER_URI');

            $this->MODULE_DIR = dirname(__FILE__) . '/../';
        }
    }

    public function DoInstall()
    {
        global $APPLICATION;

        RegisterModule($this->MODULE_ID);

        $this->includeClasses();

        $this->InstallEvents();
        $this->SetOptions();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SL3W_PREVNEXTBUTTONS_INSTALL_TITLE') . ' "' . Loc::getMessage('SL3W_PREVNEXTBUTTONS_MODULE_NAME') . '"',
            __DIR__ . '/step.php'
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $this->includeClasses();
        
        $this->UnInstallEvents();
        $this->ClearOptions();

        UnRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SL3W_PREVNEXTBUTTONS_UNINSTALL_TITLE') . ' "' . Loc::getMessage('SL3W_PREVNEXTBUTTONS_MODULE_NAME') . '"',
            __DIR__ . '/unstep.php'
        );
    }

    public function InstallEvents()
    {
        EventsRegister::addPrevNextButtons(true);

        return true;
    }

    public function UnInstallEvents()
    {
        EventsRegister::addPrevNextButtons(false);

        return true;
    }

    private function SetOptions()
    {
        Settings::set('switch_on', 'Y');

        Settings::set('iblock_ids', 'all');
    }

    private function ClearOptions()
    {
        Settings::deleteAll();
    }

    private function includeClasses()
    {
        include_once(__DIR__ . '/../lib/classes/Helpers.php');
        include_once(__DIR__ . '/../lib/classes/Settings.php');
        include_once(__DIR__ . '/../lib/classes/EventsRegister.php');
    }
}