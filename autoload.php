<?php

\Bitrix\Main\Loader::registerAutoLoadClasses(
    'sl3w.prevnextelements',
    [
        'Sl3w\PrevNextElements\Helpers' => 'lib/classes/Helpers.php',
        'Sl3w\PrevNextElements\OptionsDrawer' => 'lib/classes/OptionsDrawer.php',
        'Sl3w\PrevNextElements\Settings' => 'lib/classes/Settings.php',
        'Sl3w\PrevNextElements\AdminEvents' => 'lib/classes/AdminEvents.php',
        'Sl3w\PrevNextElements\EventsRegister' => 'lib/classes/EventsRegister.php',
    ]
);