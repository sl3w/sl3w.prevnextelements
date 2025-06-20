<?php if (!check_bitrix_sessid()) return;

/** @global CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($errorException = $APPLICATION->GetException()) {
    CAdminMessage::ShowMessage($errorException->GetString());
} else {
    CAdminMessage::ShowNote(sprintf('%s "%s" %s',
        Loc::getMessage('SL3W_PREVNEXTBUTTONS_STEP_BEFORE'),
        Loc::getMessage('SL3W_PREVNEXTBUTTONS_MODULE_NAME'),
        Loc::getMessage('SL3W_PREVNEXTBUTTONS_STEP_AFTER')
    ));
}
?>

<form action="<?= $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="submit" name="" value="<?= Loc::getMessage('SL3W_PREVNEXTBUTTONS_STEP_BACK') ?>">
<form>

<a href="/bitrix/admin/settings.php?mid=sl3w.prevnextelements&lang=<?= urlencode(LANGUAGE_ID) ?>&mid_menu=1">
    <input type="button" name="" value="<?= Loc::getMessage('SL3W_PREVNEXTBUTTONS_STEP_GO_TO_SETTINGS') ?>">
</a>