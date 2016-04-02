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

if (!defined('APCAL_BLOCK_TODAYS_SCHEDULE_INCLUDED')) {
    define('APCAL_BLOCK_TODAYS_SCHEDULE_INCLUDED', 1);

    function apcal_todays_schedule_show_tpl($options)
    {
        global $xoopsConfig, $xoopsDB;

        $moduleDirName = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];
        $now_cid       = empty($options[1]) ? 0 : (int)$options[1];

        // setting physical & virtual paths
        $mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName ";
        $mod_url  = XOOPS_URL . "/modules/$moduleDirName ";

        // defining class of APCal
        if (!class_exists('APCal_xoops')) {
            require_once("$mod_path/class/APCal.php");
            require_once("$mod_path/class/APCal_xoops.php");
        }

        // creating an instance of APCal
        $cal                = new APCal_xoops(date('Y-n-j'), $xoopsConfig['language'], true);
        $cal->use_server_TZ = true;

        // cid ¤Ë¤è¤ë¹Ê¤ê¹þ¤ß
        $cal->now_cid = $now_cid;

        // setting properties of APCal
        $cal->conn = $GLOBALS['xoopsDB']->conn;
        include("$mod_path/include/read_configs.php");
        $cal->base_url    = $mod_url;
        $cal->base_path   = $mod_path;
        $cal->images_url  = "$mod_url/assets/images/$skin_folder";
        $cal->images_path = "$mod_path/assets/images/$skin_folder";

        $block = $cal->get_blockarray_date_event("$mod_url/index.php");

        return $block;
    }

    function apcal_todays_schedule_edit($options)
    {
        global $xoopsDB, $xoopsConfig;

        $moduleDirName = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];
        $now_cid       = empty($options[1]) ? 0 : (int)$options[1];

        // setting physical & virtual paths
        $mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName ";
        $mod_url  = XOOPS_URL . "/modules/$moduleDirName ";

        // defining class of APCal
        require_once("$mod_path/class/APCal.php");
        require_once("$mod_path/class/APCal_xoops.php");

        // creating an instance of APCal
        $cal                = new APCal_xoops(date('Y-n-j'), $xoopsConfig['language'], true);
        $cal->use_server_TZ = true;

        // setting properties of APCal
        $cal->conn = $GLOBALS['xoopsDB']->conn;
        include("$mod_path/include/read_configs.php");
        $cal->base_url    = $mod_url;
        $cal->base_path   = $mod_path;
        $cal->images_url  = "$mod_url/assets/images/$skin_folder";
        $cal->images_path = "$mod_path/assets/images/$skin_folder";

        $ret = "<input type='hidden' name='options[0]' value='$moduleDirName ' />\n";

        // ¥«¥Æ¥´¥ê¡¼ÁªÂò¥Ü¥Ã¥¯¥¹¤ÎÀ¸À®
        $ret .= _MB_APCAL_CATSEL . ':';
        $ret .= "<select name='options[1]'>\n<option value='0'>" . _ALL . "</option>\n";
        foreach ($cal->categories as $cid => $cat) {
            $selected       = $now_cid == $cid ? "selected='selected'" : '';
            $depth_desc     = str_repeat('-', (int)$cat->cat_depth);
            $cat_title4show = $cal->text_sanitizer_for_show($cat->cat_title);
            $ret .= "\t<option value='$cid' $selected>$depth_desc $cat_title4show</option>\n";
        }
        $ret .= "</select>\n";

        return $ret;
    }
}
