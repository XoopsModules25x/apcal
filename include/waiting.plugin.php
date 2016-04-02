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

eval('

function b_waiting_' . $moduleDirName . '(){
    return b_waiting_APCal_base( "' . $moduleDirName . '" ) ;
}

');

if (!function_exists('b_waiting_APCal_base')) {
    function b_waiting_APCal_base($moduleDirName)
    {
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $block   = array();

        // get $mydirnumber
        if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
            echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
        }
        $mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

        $result = $GLOBALS['xoopsDB']->query('SELECT COUNT(*) FROM ' . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_event") . ' WHERE admission<1 AND (rrule_pid=0 OR rrule_pid=id)');
        if ($result) {
            $block['adminlink'] = XOOPS_URL . "/modules/$moduleDirName/admin/admission.php";
            list($block['pendingnum']) = $GLOBALS['xoopsDB']->fetchRow($result);
            $block['lang_linkname'] = _PI_WAITING_EVENTS;
        }

        return $block;
    }
}
