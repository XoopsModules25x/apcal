<?php

if (substr(XOOPS_VERSION, 6) < '2.5.0' && isset($xoopsModule) && $xoopsModule->getInfo('system_menu')) {
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/APCal/admin/menu.css');
    $xoopsModule->loadAdminMenu();
    // Get menu tab handler
    $menu_handler = xoops_getModuleHandler('adminMenu', 'APCal');
    // Define top navigation
    $menu_handler->addMenuTop(XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid', 'e'), _AM_APCAL_PREFS);
    $menu_handler->addMenuTop(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module=' . $xoopsModule->getVar('dirname', 'e'), _AM_APCAL_UPDATE);
    $menu_handler->addMenuTop(XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin&amp;op=list&amp;filter=1&amp;selgen=' . $xoopsModule->getVar('mid', 'e') . '&amp;selmod=-2&amp;selgrp=-1&amp;selvis=-1', _AM_APCAL_BLOCKS);
    $menu_handler->addMenuTop(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'e') . '/', _AM_APCAL_GOTOMODULE);
    // Define main tab navigation
    $i = 0;
    foreach ($xoopsModule->adminmenu as $menu) {
        if (stripos($_SERVER['REQUEST_URI'], $menu['link']) !== false) {
            $current = $i;
        }
        $menu_handler->addMenuTabs($menu['link'], $menu['title']);
        ++$i;
    }
    if ($xoopsModule->getInfo('help')) {
        if (stripos($_SERVER['REQUEST_URI'], 'admin/' . $xoopsModule->getInfo('help')) !== false) {
            $current = $i;
        }
        $menu_handler->addMenuTabs('../system/help.php?mid=' . $xoopsModule->getVar('mid', 's') . '&amp;page=' . $xoopsModule->getInfo('help'), _AM_APCAL_SYSTEM_HELP);
    }

    // Display navigation tabs
    echo $menu_handler->render($current, false);
}
