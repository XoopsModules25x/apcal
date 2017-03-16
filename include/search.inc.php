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
 * @author      GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$moduleDirName = basename(dirname(__DIR__));
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    die('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

eval('

function apcal' . $mydirnumber . '_search( $keywords , $andor , $limit , $offset , $uid )
{
    return apcal_search_base( "' . $moduleDirName . '" , $keywords , $andor , $limit , $offset , $uid ) ;
}

');

if (!function_exists('apcal_search_base')) {
    function apcal_search_base($moduleDirName, $keywords, $andor, $limit, $offset, $uid)
    {
        global $xoopsConfig, $xoopsDB, $xoopsUser;

        // setting physical & virtual paths
        $mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
        $mod_url  = XOOPS_URL . "/modules/$moduleDirName";

        // defining class of APCal
        if (!class_exists('APCal_xoops')) {
            require_once("$mod_path/class/APCal.php");
            require_once("$mod_path/class/APCal_xoops.php");
        }

        // creating an instance of APCal
        $cal                = new APCal_xoops('', $xoopsConfig['language'], true);
        $cal->use_server_TZ = true;

        // setting properties of APCal
        $cal->conn = $GLOBALS['xoopsDB']->conn;
        include("$mod_path/include/read_configs.php");
        $cal->images_url  = "$mod_url/assets/images/$skin_folder";
        $cal->images_path = "$mod_path/assets/images/$skin_folder";

        $ret = $cal->get_xoops_search_result($keywords, $andor, $limit, $offset, $uid);

        return $ret;
    }
}