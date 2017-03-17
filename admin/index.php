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
 * @author       GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

//header( "Location: admission.php" ) ;
///exit ;
require_once __DIR__ . '/admin_header.php';
//require_once __DIR__ . '/../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/modules/apcal/class/APCal.php';

xoops_cp_header();
//require_once XOOPS_ROOT_PATH . '/modules/apcal/admin/displayMenu.php';

$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));



$MODURL  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname');
$MODPATH = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname');

$cal = new APCal();
include XOOPS_ROOT_PATH . '/modules/apcal/include/read_configs.php';

$rs = $GLOBALS['xoopsDB']->query("SELECT COUNT(id) FROM {$cal->table} WHERE admission<1 AND (rrule_pid=0 OR rrule_pid=id)");
//$nbWaitingEvents = mysql_result($rs, 0, 0);

$nbWaitingEvents = 0;
$resultRow       = $GLOBALS['xoopsDB']->fetchRow($rs);
if (false !== $resultRow && isset($resultRow[0])) {
    $nbWaitingEvents = $resultRow[0];
}

$rs = $GLOBALS['xoopsDB']->query("SELECT COUNT(id) FROM {$cal->table} WHERE end>UNIX_TIMESTAMP() AND (rrule_pid=0 OR rrule_pid=id)");
//$nbEvents = mysql_result($rs, 0, 0);
$nbEvents  = 0;
$resultRow = $GLOBALS['xoopsDB']->fetchRow($rs);
if (false !== $resultRow && isset($resultRow[0])) {
    $nbEvents = $resultRow[0];
}

$rs = $GLOBALS['xoopsDB']->query("SELECT COUNT(cid) FROM {$cal->cat_table}");
//$nbCats = mysql_result($rs, 0, 0);
$nbCats    = 0;
$resultRow = $GLOBALS['xoopsDB']->fetchRow($rs);
if (false !== $resultRow && isset($resultRow[0])) {
    $nbCats = $resultRow[0];
}
/*
$infoBoxes                         = array();
$infoBoxes[_MI_APCAL_ADMENU1][]    = sprintf(_AM_APCAL_NBWAITINGEVENTS,
                                             '<span style="color:' . ($nbWaitingEvents > 0 ? '#aa0000' : '#00aa00') . '; font-weight : bold;">' . $nbWaitingEvents . '</span>');
$infoBoxes[_MI_APCAL_ADMENU1][]    = sprintf(_AM_APCAL_NBEVENTS, '<span style="font-weight : bold;">' . $nbEvents . '</span>');
$infoBoxes[_MI_APCAL_ADMENU_CAT][] = sprintf(_AM_APCAL_NBCATS, '<span style="font-weight : bold;">' . $nbCats . '</span>');
$infoBoxes[_AM_APCAL_TIMEZONE][]   = sprintf(_AM_APCAL_FMT_SERVER_TZ_ALL, date('Z', 1104537600) / 3600, date('Z', 1120176000) / 3600, date('T'), $xoopsConfig['server_TZ'], $cal->server_TZ);
*/

$adminObject->addInfoBox(_MI_APCAL_ADMENU1);
$adminObject->addInfoBoxLine(sprintf(_AM_APCAL_NBWAITINGEVENTS,
                                     '<span style="color:' . ($nbWaitingEvents > 0 ? '#aa0000' : '#00aa00') . '; font-weight : bold;">' . $nbWaitingEvents . '</span>'));
$adminObject->addInfoBoxLine(sprintf(_AM_APCAL_NBEVENTS, '<span style="font-weight : bold;">' . $nbEvents . '</span>'));

$adminObject->addInfoBox(_MI_APCAL_ADMENU_CAT);
$adminObject->addInfoBoxLine(sprintf(_AM_APCAL_NBCATS, '<span style="font-weight : bold;">' . $nbCats . '</span>'));

$adminObject->addInfoBox(_AM_APCAL_TIMEZONE);
$adminObject->addInfoBoxLine(sprintf(_AM_APCAL_FMT_SERVER_TZ_ALL, date('Z', 1104537600) / 3600, date('Z', 1120176000) / 3600, date('T'), $xoopsConfig['server_TZ'], $cal->server_TZ));

$adminObject->displayIndex();
//$xoopsModule->loadAdminMenu();

$xoopsTpl->assign('moduleURL', $MODURL);
$xoopsTpl->assign('imgURL', $MODURL . '/assets/images/admin/');
$xoopsTpl->assign('minphp', $xoopsModule->getInfo('min_php'));
$xoopsTpl->assign('minxoops', $xoopsModule->getInfo('min_xoops'));
$xoopsTpl->assign('phpversion', PHP_VERSION);
$xoopsTpl->assign('xoopsversion', substr(XOOPS_VERSION, 6));

$xoopsTpl->assign('adminmenu', $xoopsModule->adminmenu);
$xoopsTpl->assign('moduleID', $xoopsModule->getVar('mid', 's'));
$xoopsTpl->assign('moduleHelp', $xoopsModule->getInfo('help'));
//$xoopsTpl->assign('infoBoxes', $infoBoxes);

$xoTheme->addStylesheet($MODURL . '/assets/css/admin.css');
//echo $xoopsTpl->fetch(XOOPS_ROOT_PATH . '/modules/apcal/templates/admin/apcal_index.tpl');


xoops_cp_footer();
