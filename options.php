<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Sl3w\PrevNextElements\OptionsDrawer;
use Sl3w\PrevNextElements\Settings;
use Sl3w\PrevNextElements\EventsRegister;

Loc::loadMessages(__FILE__);

/** @global CMain $APPLICATION */

const LANGS_PREFIX = 'SL3W_PREVNEXTBUTTONS_';

$request = HttpApplication::getInstance()->getContext()->getRequest();

$moduleId = htmlspecialcharsbx($request['mid'] != '' ? $request['mid'] : $request['id']);

if (!Loader::includeModule($moduleId)) {
    ShowMessage(Loc::getMessage(LANGS_PREFIX . 'MODULE_INCLUDE_ERROR'));

    return false;
}

if (!Loader::includeModule('iblock')) {
    ShowMessage(Loc::getMessage(LANGS_PREFIX . 'MODULE_IBLOCK_ERROR'));

    return false;
}

$APPLICATION->SetAdditionalCss('/bitrix/css/' . $moduleId . '/options.min.css');

$selectIBlocks = ['all' => Loc::getMessage(LANGS_PREFIX . 'OPTION_ALL')];

$dbIBlocks = CIBlock::GetList(['ID' => 'ASC'], ['ACTIVE' => 'Y']);

while ($arIBlock = $dbIBlocks->GetNext()) {
    $selectIBlocks[$arIBlock['ID']] = sprintf('[%s] %s', $arIBlock['ID'], $arIBlock['NAME']);
}

$settingsTabOptions = [
    'switch_on' => [
        'code' => 'switch_on',
        'name' => Loc::getMessage(LANGS_PREFIX . 'OPTION_SWITCH_ON'),
        'type' => 'checkbox',
        'default' => 'Y',
    ],
    'iblock_ids' => [
        'code' => 'iblock_ids',
        'name' => Loc::getMessage(LANGS_PREFIX . 'OPTION_IBLOCK_IDS'),
        'type' => 'select',
        'multi' => 'Y',
        'options' => $selectIBlocks,
        'default' => 'all',
    ],
];

$supportTabOptions = [
    'support_note' => [
        'type' => 'note',
        'name' => Loc::getMessage(LANGS_PREFIX . 'SUPPORT_NOTE'),
    ],
];

$allTabsOptions = array_merge($settingsTabOptions, $supportTabOptions);

$tabControl = new CAdminTabControl(
    'tabControl',
    [
        [
            'DIV' => 'settings',
            'TAB' => Loc::getMessage(LANGS_PREFIX . 'OPTIONS_TAB_NAME'),
            'TITLE' => Loc::getMessage(LANGS_PREFIX . 'OPTIONS_TAB_NAME'),
        ],
        [
            'DIV' => 'support',
            'TAB' => Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_NAME'),
            'TITLE' => Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_NAME'),
        ],
    ],
);

$tabControl->Begin();

$optionsDrawer = new OptionsDrawer('.sl3w_prevnextelements');
?>

<form enctype="multipart/form-data" method="post" name="sl3w_prevnextelements"
      action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= $moduleId ?>&lang=<?= LANG ?>">

    <?php
    $tabControl->BeginNextTab();

    $optionsDrawer->drawOptions($settingsTabOptions);

    $tabControl->BeginNextTab();
    ?>

    <p>
        <?= Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_TEXT') ?>
    </p>
    <p>
        <?= Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_TEXT2') ?>
    </p>
    <p>
        <?= Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_TEXT2_QR') ?>
    </p>
    <p>
        <?= Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_TEXT3') ?>
    </p>
    <p>
        <?= Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_TEXT4') ?>
    </p>

    <iframe
            src="https://yoomoney.ru/quickpay/shop-widget?writer=seller&default-sum=1000&button-text=12&payment-type-choice=on&successURL=&quickpay=shop&account=410014134044507&targets=%D0%9F%D0%B5%D1%80%D0%B5%D0%B2%D0%BE%D0%B4%20%D0%BF%D0%BE%20%D0%BA%D0%BD%D0%BE%D0%BF%D0%BA%D0%B5&"
            width="423" height="222" frameborder="0" allowtransparency="true" scrolling="no"></iframe>

    <p>
        <?= Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_TEXT5') ?>
    </p>
    <p>
        <?= Loc::getMessage(LANGS_PREFIX . 'SUPPORT_TAB_TEXT6') ?>
    </p>

    <?php
    $optionsDrawer->drawOptions($supportTabOptions);

    $tabControl->Buttons();
    ?>

    <input type="submit" name="apply" value="<?= Loc::getMessage(LANGS_PREFIX . 'BUTTON_APPLY') ?>" class="adm-btn-save">

    <?= bitrix_sessid_post() ?>
</form>

<?php
$tabControl->End();

if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($allTabsOptions as $option) {
        $type = $option['type'];
        $code = $option['code'];

        if (in_array($type, ['note', 'block_title'])) {
            continue;
        }

        if ($request['apply']) {
            $value = $request->getPost($code);
            $value = $type == 'checkbox' && $value == '' ? 'N' : $value;

            switch ($code) {
                case 'switch_on':
                    EventsRegister::addPrevNextButtons($value == 'Y');

                    break;
            }

            $value = is_array($value) ? implode(',', $value) : $value;

            Settings::set($code, $value);
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . $moduleId . '&lang=' . urlencode(LANGUAGE_ID) . '&mid_menu=1');
}

$optionsDrawer->drawExtensions();
?>

<script>
    const iBsSelector = document.querySelector('select[name="iblock_ids[]"]');

    iBsSelector.size = iBsSelector.length > 10 ? 10 : (iBsSelector.length < 5 ? 5 : iBsSelector.length);
</script>
