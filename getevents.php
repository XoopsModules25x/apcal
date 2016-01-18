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

require_once '../../mainfile.php';

$xoopsErrorHandler->activated = false;
error_reporting(E_NONE);

header('Access-Control-Allow-Origin: *');

$locales = new apcal_locale();

$array = array();
$catcrit = $_GET['c'] > 0 ? 'categories LIKE \'%'.str_pad($_GET['c'], 5, '0', STR_PAD_LEFT).'%\' AND' : '';
$result = $xoopsDB->queryF("SELECT id, start, end, summary, shortsummary FROM {$xoopsDB->prefix('apcal_event')} WHERE {$catcrit} end>UNIX_TIMESTAMP() ORDER BY start ASC LIMIT 0,{$_GET['n']}");
while($row=$xoopsDB->fetchArray($result))
{
    $start = $row['start'];
    $startD = $locales->date_long_names[intval(gmstrftime('%d', $row['start'] + (date('I', $row['start'])*3600)))];
    $startM = $locales->month_long_names[intval(gmstrftime('%m', $row['start'] + (date('I', $row['start'])*3600)))];
    
    $endD = $locales->date_long_names[intval(gmstrftime('%d', $row['end'] + (date('I', $row['end'])*3600)))];
    $endM = $locales->month_long_names[intval(gmstrftime('%m', $row['end'] + (date('I', $row['end'])*3600)))];

    $row['start'] = $startD.' '.htmlentities($startM, ENT_QUOTES, "UTF-8");
    $row['end'] = $endD.' '.htmlentities($endM, ENT_QUOTES, "UTF-8");
    $row['summary'] = htmlentities($row['summary'], ENT_QUOTES, "UTF-8");
    $row['link'] = $xoopsModuleConfig['apcal_useurlrewrite'] ? XOOPS_URL.'/modules/APCal/'.$row['shortsummary'].'-'.date('j-n-Y', $start) : XOOPS_URL.'/modules/APCal/?event_id='.$row['id'];
    $array[] = $row;
}
$c = $_GET['c'] > 0 ? htmlentities($xoopsDB->fetchObject($xoopsDB->queryF("SELECT cat_title FROM {$xoopsDB->prefix('apcal_cat')} WHERE cid={$_GET['c']} LIMIT 0,1"))->cat_title, ENT_QUOTES, "UTF-8") : '';
$l = '</dl><div class="APfooter">'._APCAL_PROVIDEDBY.' <a href="'.XOOPS_URL.'" title="'.htmlentities($xoopsConfig['sitename'], ENT_QUOTES, "UTF-8").'" target="_blank">'.htmlentities($xoopsConfig['sitename'], ENT_QUOTES, "UTF-8").'</a><br /><a href="'._APCAL_APURL.'" title="'._APCAL_AP.'" target="_blank">APCal</a> '._APCAL_X.' <a href="'._APCAL_APURL2.'" title="'._APCAL_AP.'" target="_blank">AP</a></div>';
echo check() ? json_encode(array($array, $l, '<div class="APtitle">'.$c.'</div>')) : '';

class apcal_locale
{
    var $hour_names_24;
    var $hour_names_12;
    var $holidays;
    var $date_short_names;
    var $date_long_names;
    var $week_numbers;
    var $week_short_names;
    var $week_middle_names;
    var $week_long_names;
    var $month_short_names;
    var $month_middle_names;
    var $month_long_names;
    var $byday2langday_w;
    var $byday2langday_m;

    function __construct()
    {
        include XOOPS_ROOT_PATH.'/modules/APCal/language/'.$GLOBALS['xoopsConfig']['language'].'/apcal_vars.phtml';
    }

    function apcal_locale()
    {
        self::__construct();
    }
}

function check()
{
    global $l;return preg_match('/<a href="http:\/\/xoops.antique(s?)promotion.(com|ca)/', $l);
}

?>
