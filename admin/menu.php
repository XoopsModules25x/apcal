<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright   {@link http://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @package
 * @since
 * @author       XOOPS Development Team,
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 * @author       GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */



if (!isset($moduleDirName)) {
    $moduleDirName = basename(dirname(__DIR__));
}

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon32    = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');
$i                      = 0;
$adminmenu[$i]['title'] = _MI_APCAL_INDEX;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = 'assets/images/admin/home.png';
++$i;
$adminmenu[$i]['title'] = _MI_APCAL_ADMENU0;
$adminmenu[$i]['link']  = 'admin/admission.php';
$adminmenu[$i]['icon']  = 'assets/images/admin/admitting.png';
++$i;
$adminmenu[$i]['title'] = _MI_APCAL_ADMENU1;
$adminmenu[$i]['link']  = 'admin/events.php';
$adminmenu[$i]['icon']  = 'assets/images/admin/events.png';
++$i;
$adminmenu[$i]['title'] = _MI_APCAL_ADMENU_CAT;
$adminmenu[$i]['link']  = 'admin/categories.php';
$adminmenu[$i]['icon']  = 'assets/images/admin/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_APCAL_ADMENU_CAT2GROUP;
$adminmenu[$i]['link']  = 'admin/cat2groupperm.php';
$adminmenu[$i]['icon']  = 'assets/images/admin/permissions.png';
++$i;
$adminmenu[$i]['title'] = _MI_APCAL_ADMENU2;
$adminmenu[$i]['link']  = 'admin/groupperm.php';
$adminmenu[$i]['icon']  = 'assets/images/admin/permissions.png';
++$i;
$adminmenu[$i]['title'] = _MI_APCAL_ADMENU_ICAL;
$adminmenu[$i]['link']  = 'admin/icalendar_import.php';
$adminmenu[$i]['icon']  = 'assets/images/admin/import.png';
//++$i;
//$adminmenu[$i]['title'] = _MI_APCAL_ADMENU_TM;
//$adminmenu[$i]['link'] = "admin/maintenance.php";
//++$i;
//$adminmenu[$i]['title'] = _MI_APCAL_ADMENU_PLUGINS;
//$adminmenu[$i]['link'] = "admin/pluginsmanager.php";
//++$i;
//$adminmenu[$i]['title'] = _MI_APCAL_ADMENU_MYTPLSADMIN;
//$adminmenu[$i]['link'] = "admin/mytplsadmin.php";
//++$i;
//$adminmenu[$i]['title'] = _MI_APCAL_ADMENU_MYBLOCKSADMIN;
//$adminmenu[$i]['link'] = "admin/myblocksadmin.php";
//++$i;
//$adminmenu[$i]['title'] = _PREFERENCES;
//$adminmenu[$i]['link'] = "admin/admin.php?fct=preferences&op=showmod";

$adminmenu[] = [
    'title' => _AM_MODULEADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
];
