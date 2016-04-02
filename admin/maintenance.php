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

function error_halt($msg)
{
    global $xoopsLogger;

    xoops_cp_header();
    echo $xoopsLogger->dumpQueries();
    OpenTable();
    echo $msg;
    CloseTable();
    xoops_cp_footer();
}

require_once(dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php');
require_once(dirname(__DIR__) . '/class/APCal.php');
require_once(dirname(__DIR__) . '/class/APCal_xoops.php');

// for "Duplicatable"
$moduleDirName = basename(dirname(__DIR__));
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

require_once(XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php");

// setting physical & virtual paths
$mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName ";
$mod_url  = XOOPS_URL . "/modules/$moduleDirName ";
$php_self = "$mod_url/admin/maintenance.php";

$table_event = $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_event");
$table_cat   = $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_cat");
$mid         = $xoopsModule->mid();

// creating an instance of APCal
$cal = new APCal_xoops('', $xoopsConfig['language'], true);

// setting properties of APCal
$conn = $GLOBALS['xoopsDB']->conn;
include(dirname(__DIR__) . '/include/read_configs.php');
$cal->base_url    = $mod_url;
$cal->base_path   = $mod_path;
$cal->images_url  = "$mod_url/assets/images/$skin_folder";
$cal->images_path = "$mod_path/assets/images/$skin_folder";

// get TimeZones
$serverTZ   = $cal->server_TZ;
$default_TZ = $xoopsConfig['default_TZ'];

// updating phase
if (!empty($_POST['do_04to06'])) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    // table structure
    $sql = "ALTER TABLE $table_event
        MODIFY id int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
        ADD poster_tz float(2,1) NOT NULL default 0.0 AFTER end,
        ADD server_tz float(2,1) NOT NULL default 0.0 AFTER end,
        ADD event_tz float(2,1) NOT NULL default 0.0 AFTER end,
        ADD comments mediumint(8) unsigned NOT NULL default '0' AFTER end,
        ADD cid smallint(5) unsigned zerofill NOT NULL default '0' AFTER end,
        ADD end_date date AFTER end,
        ADD start_date date AFTER end";
    if (!$GLOBALS['xoopsDB']->query($sql)) {
        error_halt(_AM_APCAL_MB_FAILUPDATETABLE);
    }

    // TimeZones of all records
    $sql = "UPDATE $table_event SET
        dtstamp = dtstamp,
        server_tz = $serverTZ,
        poster_tz = tzid,
        event_tz = tzid";
    if (!$GLOBALS['xoopsDB']->query($sql)) {
        error_halt(_AM_APCAL_MB_FAILUPDATETABLE);
    }

    // older tzid field update
    $sql = "UPDATE $table_event SET dtstamp=dtstamp,tzid='',event_tz=9.0,poster_tz=9.0 WHERE tzid='Japan'";
    if (!$GLOBALS['xoopsDB']->query($sql)) {
        error_halt(_AM_APCAL_MB_FAILUPDATETABLE);
    }

    // Counting comments
    $sql = 'SELECT com_itemid,count(*) FROM ' . $GLOBALS['xoopsDB']->prefix('xoopscomments') . " WHERE com_modid=$mid GROUP BY com_itemid";
    $rs  = $GLOBALS['xoopsDB']->query($sql);
    while (list($id, $sum) = $GLOBALS['xoopsDB']->fetchRow($rs)) {
        $GLOBALS['xoopsDB']->query("UPDATE $table_event SET dtstamp=dtstamp,comments=$sum WHERE id=$id");
    }

    xoops_cp_header();
    echo $xoopsLogger->dumpQueries();
    redirect_header($php_self, 5, _AM_APCAL_MB_SUCCESSUPDATETABLE);
} elseif (!empty($_POST['create_cat'])) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    $sql = "CREATE TABLE $table_cat (
  cid smallint(5) unsigned zerofill NOT NULL auto_increment,
  pid smallint(5) unsigned zerofill NOT NULL default '0',
  weight smallint(5) NOT NULL default 0,
  exportable tinyint NOT NULL default 1,
  autocreated tinyint NOT NULL default 0,
  ismenuitem tinyint NOT NULL default 0,
  enabled tinyint NOT NULL default 1,
  cat_title varchar(255) NOT NULL default '',
  cat_desc text NOT NULL default '',
  dtstamp TIMESTAMP(14) NOT NULL,
  cat_extkey0 INT(10) unsigned zerofill NOT NULL DEFAULT 0,
  cat_depth INT(10) unsigned zerofill NOT NULL DEFAULT 0,
  cat_style varchar(255) NOT NULL default '',
  KEY (pid),
  KEY (weight),
  KEY (cat_extkey0),
  PRIMARY KEY (cid)
) TYPE=MyISAM";
    $rs  = $GLOBALS['xoopsDB']->query($sql);

    xoops_cp_header();
    echo $xoopsLogger->dumpQueries();
    redirect_header($php_self, 5, _AM_APCAL_MB_SUCCESSUPDATETABLE);
} elseif (!empty($_POST['repair_stz'])) {

    // Ticket Check
    if (!$xoopsGTicket->check()) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    foreach ($_POST['stz'] as $from_stz => $to_stz) {
        $to_stz = str_replace('+', '', $to_stz);
        if (trim($to_stz) === '') {
            continue;
        }
        $to_stz_sec = (int)($to_stz * 3600);
        if ($to_stz_sec < -50400 || $to_stz_sec > 50400) {
            continue;
        }
        $from_stz_sec = (int)($from_stz * 3600);
        if ($from_stz_sec < -50400 || $from_stz_sec > 50400) {
            continue;
        }
        $stz_diff = $from_stz_sec - $to_stz_sec;

        $sql = "UPDATE $table_event SET dtstamp=dtstamp,start=start+($stz_diff),end=end+($stz_diff),server_tz='" . addslashes($to_stz) . "' WHERE server_tz='" . addslashes($from_stz) . "'";
        $rs  = $GLOBALS['xoopsDB']->query($sql);
    }

    xoops_cp_header();
    echo $xoopsLogger->dumpQueries();
    redirect_header($php_self, 5, _AM_APCAL_MB_SUCCESSTZUPDATE);
}

// checking phase
$sql4check_040 = "SELECT rrule_pid FROM $table_event LIMIT 1";
$sql4check_060 = "SELECT event_tz FROM $table_event  LIMIT 1";
$sql4check_cat = "SELECT cid FROM $table_cat  LIMIT 1";
$is_040        = $GLOBALS['xoopsDB']->query($sql4check_040);
$is_060        = $GLOBALS['xoopsDB']->query($sql4check_060);
$has_cat       = $GLOBALS['xoopsDB']->query($sql4check_cat);

// displaying phase
xoops_cp_header();

if (!$is_040) {
    OpenTable();
    echo _AM_APCAL_ALRT_TOOOLDTABLE;
    CloseTable();
} elseif (!$is_060) {
    OpenTable();
    echo _AM_APCAL_ALRT_OLDTABLE;
    echo "
        <br />(0.4x, 0.5x -> 0.6)
        <form action='' method='post'>
            " . $xoopsGTicket->getTicketHtml(__LINE__) . "
            <input type='submit' name='do_04to06' value='" . _GO . "' />
        </form>\n";
    CloseTable();
} elseif (!$has_cat) {
    OpenTable();
    echo _AM_APCAL_ALRT_CATTABLENOTEXIST;
    echo "
        <br />
        <form action='' method='post'>
            " . $xoopsGTicket->getTicketHtml(__LINE__) . "
            <input type='submit' name='create_cat' value='" . _GO . "' />
        </form>\n";
    CloseTable();
} else {

    // Timezone checking
    $rs_stz = $GLOBALS['xoopsDB']->query("SELECT COUNT(*),server_tz FROM $table_event GROUP BY server_tz");
    OpenTable();
    echo '
        ' . sprintf(_AM_APCAL_FMT_SERVER_TZ_ALL, date('Z', 1104537600) / 3600, date('Z', 1120176000) / 3600, date('T'), $xoopsConfig['server_TZ'], $cal->server_TZ) . "
        <form action='' method='post'>
        <table border='1'>
            <tr>
                <th>
                    " . _AM_APCAL_TH_SERVER_TZ_COUNT . '
                </th>
                <th>
                    ' . _AM_APCAL_TH_SERVER_TZ_VALUE . '
                </th>
                <th>
                    ' . _AM_APCAL_TH_SERVER_TZ_VALUE_TO . "
                </th>
            </tr>\n";

    $server_tz_wrong = false;
    while (list($count, $server_tz) = $GLOBALS['xoopsDB']->fetchRow($rs_stz)) {
        if ($serverTZ != $server_tz) {
            $server_tz_wrong = true;
        }
        echo "
            <tr>
                <td>
                    $count
                </td>
                <td>
                    " . sprintf('%+2.1f', $server_tz) . "
                </td>
                <td>
                    <input type='textbox' name='stz[$server_tz]' size='4' />
                </td>
            </tr>\n";

        /*      printf( _AM_APCAL_FMT_WRONGSTZ , $wrong_stzs ) ;
                echo "
                    <br />
                        <input type='submit' name='repair_stz' value='"._GO."' />
                    </form>\n" ;
        */
    }
    echo "
        </table>\n";

    if ($server_tz_wrong) {
        echo "
        <input type='submit' value='" . _SUBMIT . "' name='repair_stz' onclick='return confirm(\"" . _AM_APCAL_JSALRT_SERVER_TZ . "\");' />
        " . $xoopsGTicket->getTicketHtml(__LINE__) . "
        </form>\n";
        echo _AM_APCAL_NOTICE_SERVER_TZ;
    } else {
        echo _AM_APCAL_NOTICE_NOERRORS;
    }
    CloseTable();
}

xoops_cp_footer();
