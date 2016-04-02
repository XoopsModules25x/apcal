<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
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
 * @copyright   {@link http://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      Antiques Promotion (http://www.antiquespromotion.ca)
 * @author      GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

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
