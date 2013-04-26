<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
 
/**
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Antiques Promotion (http://www.antiquespromotion.ca)
 * @version     $Id:$
 */
 
//header( "Location: admission.php" ) ;
///exit ;
require_once '../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/APCal/class/APCal.php';

xoops_cp_header();
require_once XOOPS_ROOT_PATH.'/modules/APCal/admin/displayMenu.php';

$MODURL = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname');
$MODPATH = XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname');

$cal = new APCal();
include XOOPS_ROOT_PATH.'/modules/APCal/include/read_configs.php';

$rs = mysql_query("SELECT COUNT(id) FROM {$cal->table} WHERE admission<1 AND (rrule_pid=0 OR rrule_pid=id)", $xoopsDB->conn);
$nbWaitingEvents = mysql_result($rs, 0, 0);

$rs = mysql_query("SELECT COUNT(id) FROM {$cal->table} WHERE end>UNIX_TIMESTAMP() AND (rrule_pid=0 OR rrule_pid=id)", $xoopsDB->conn);
$nbEvents = mysql_result($rs, 0, 0);

$rs = mysql_query("SELECT COUNT(cid) FROM {$cal->cat_table}", $xoopsDB->conn);
$nbCats = mysql_result($rs, 0, 0);

$infoBoxes = array();
$infoBoxes[_MI_APCAL_ADMENU1][] = sprintf(_AM_APCAL_NBWAITINGEVENTS, '<span style="color:'.($nbWaitingEvents>0 ? '#aa0000' : '#00aa00').'; font-weight : bold;">'.$nbWaitingEvents.'</span>');
$infoBoxes[_MI_APCAL_ADMENU1][] = sprintf(_AM_APCAL_NBEVENTS, '<span style="font-weight : bold;">'.$nbEvents.'</span>');
$infoBoxes[_MI_APCAL_ADMENU_CAT][] = sprintf(_AM_APCAL_NBCATS, '<span style="font-weight : bold;">'.$nbCats.'</span>');
$infoBoxes[_AM_APCAL_TIMEZONE][] = sprintf(_AM_FMT_SERVER_TZ_ALL, date('Z', 1104537600)/3600, date('Z', 1120176000)/3600, date('T'), $xoopsConfig['server_TZ'], $cal->server_TZ);

$xoopsModule->loadAdminMenu();

$xoopsTpl->assign('moduleURL', $MODURL);
$xoopsTpl->assign('imgURL', $MODURL.'/images/admin/');
$xoopsTpl->assign('minphp', $xoopsModule->getInfo('min_php'));
$xoopsTpl->assign('minxoops', $xoopsModule->getInfo('min_xoops'));
$xoopsTpl->assign('phpversion', phpversion());
$xoopsTpl->assign('xoopsversion', substr(XOOPS_VERSION, 6));

$xoopsTpl->assign('adminmenu', $xoopsModule->adminmenu);
$xoopsTpl->assign('moduleID', $xoopsModule->getVar('mid', 's'));
$xoopsTpl->assign('moduleHelp', $xoopsModule->getInfo('help'));
$xoopsTpl->assign('infoBoxes', $infoBoxes);

$xoTheme->addStylesheet($MODURL.'/admin.css');
echo $xoopsTpl->fetch(XOOPS_ROOT_PATH.'/modules/APCal/templates/admin/apcal_index.html');

xoops_cp_footer();

?>
