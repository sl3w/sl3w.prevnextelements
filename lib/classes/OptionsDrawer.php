<?php

namespace Sl3w\PrevNextElements;

use Bitrix\Main\UI\Extension;

class OptionsDrawer
{
    private $formName;
    private $extensionToInclude = [];

    public function __construct($formName)
    {
        $this->formName = $formName;
    }

    private function addExtension($extName)
    {
        if (!in_array($extName, $this->extensionToInclude)) {
            $this->extensionToInclude[] = $extName;
        }
    }

    public function drawExtensions()
    {
        echo $this->compileExtensions();
    }

    private function compileExtensions()
    {
        if (empty($this->extensionToInclude)) return '';

        ob_start();

        foreach ($this->extensionToInclude as $extName) {
            Extension::load($extName);

            switch ($extName) {
                case 'ui.hint': ?>

                    <script type="text/javascript">
                        BX.ready(function () {
                            BX.UI.Hint.init(document.querySelector('<?= $this->formName ?>'));
                        });
                    </script>

                    <?php break;
            }
        }

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function drawOptions($options)
    {
        if ($options) {
            foreach ($options as $optionParameters) {
                echo $this->drawTableRaw($optionParameters);
            }
        }
    }

    public function drawTableRaw($parameters)
    {
        ob_start();

        switch ($parameters['type']) {
            case 'block_title':
                ?>

                <tr class="heading">
                    <td colspan="2">
                        <?= $parameters['name'] ?>
                    </td>
                </tr>

                <?php break;

            case 'note':
                ?>

                <tr>
                    <td colspan="2" align="center">
                        <div class="adm-info-message-wrap" align="center">
                            <div class="adm-info-message">
                                <?= $parameters['name'] ?>
                            </div>
                        </div>
                    </td>
                </tr>

                <?php break;

            default: ?>

                <tr>
                    <td width="50%">
                        <?php if ($hintBefore = $parameters['hint_before']) {
                            $this->addExtension('ui.hint'); ?>

                            <span data-hint="<?= $hintBefore ?>" data-hint-html></span>
                        <?php } ?>

                        <?= $parameters['name'] ?>
                    </td>
                    <td width="50%">
                        <?= $this->drawOption($parameters); ?>

                        <?php if ($hintAfter = $parameters['hint_after']) {
                            $this->addExtension('ui.hint'); ?>

                            <span data-hint="<?= $hintAfter ?>" data-hint-html></span>
                        <?php } ?>
                    </td>
                </tr>

            <?php
        }

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function drawOption($parameters)
    {
        $type = $parameters['type'];
        $code = $parameters['code'];
        $value = Settings::get($code, $parameters['default'] ?? '');
        $textAfter = $parameters['text_after'];

        ob_start();

        switch ($type) {
            case 'checkbox':
                $checkValue = 'Y';
                ?>

                <div class="sl3w-option-setting-field _checkbox">
                    <input
                            type="checkbox"
                            id="<?= $code ?>"
                            name="<?= $code ?>"
                            value="<?= $checkValue ?>"
                        <?= $value == $checkValue ? 'checked' : '' ?>
                    >
                </div>

                <?php break;

            case 'select':
                $isMulti = $parameters['multi'] === 'Y';
                $options = $parameters['options'] ?? [];
                $value = Helpers::arrayTrimExplode($value);
                ?>

                <div class="sl3w-option-setting-field _select <?= $isMulti ? '_select-multi' : '' ?>">
                    <select name="<?= $code ?><?= $isMulti ? '[]' : '' ?>" <?= $isMulti ? 'multiple' : '' ?>>
                        <?php foreach ($options as $val => $name): ?>
                            <option value="<?= $val ?>" <?= in_array($val, $value) ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="_angle"></div>
                </div>

                <?php break;
        }

        if (isset($textAfter)) {
            echo $textAfter;
        }

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}