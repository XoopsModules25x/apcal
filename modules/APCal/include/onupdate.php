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
 
function xoops_module_update_APCal($xoopsModule)
{
    global $xoopsDB;

    if(!$xoopsDB->queryF("SELECT shortsummary FROM {$xoopsDB->prefix('apcal_event')}"))
    {
        if($xoopsDB->queryF("ALTER TABLE {$xoopsDB->prefix('apcal_event')} ADD shortsummary VARCHAR(255) AFTER groupid"))
        {
        }
    }
    $result = $xoopsDB->queryF("SELECT id, summary FROM {$xoopsDB->prefix('apcal_event')}");
    while($row = $xoopsDB->fetchArray($result))
    {
        $shortsummary = makeShort($row['summary']);
        $xoopsDB->queryF("UPDATE {$xoopsDB->prefix('apcal_event')} SET shortsummary='{$shortsummary}' WHERE id={$row['id']}");
    }

    if(!$xoopsDB->queryF("SELECT cat_shorttitle FROM {$xoopsDB->prefix('apcal_cat')}"))
    {
        if($xoopsDB->queryF("ALTER TABLE {$xoopsDB->prefix('apcal_cat')} ADD cat_shorttitle VARCHAR(255) AFTER enabled"))
        {
        }
    }
    $result = $xoopsDB->queryF("SELECT cid, cat_title FROM {$xoopsDB->prefix('apcal_cat')}");
    while($row = $xoopsDB->fetchArray($result))
    {
        $cat_shorttitle = makeShort($row['cat_title']);
        $xoopsDB->queryF("UPDATE {$xoopsDB->prefix('apcal_cat')} SET cat_shorttitle='{$cat_shorttitle}' WHERE cid={$row['cid']}");
    }
    
    if(!$xoopsDB->queryF("SELECT email,url,mainCategory, otherHours FROM {$xoopsDB->prefix('apcal_event')}"))
    {
        $sql = "ALTER TABLE {$xoopsDB->prefix('apcal_event')} ";
        $sql .= 'ADD url VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\' AFTER location,';
        $sql .= 'ADD email VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\' AFTER url,';
        $sql .= 'ADD mainCategory SMALLINT( 5 ) UNSIGNED ZEROFILL NOT NULL DEFAULT \'00000\' AFTER dtstamp,';
        $sql .= 'ADD otherHours VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\' AFTER end';
        $xoopsDB->queryF($sql);
    }
    
    if(!$xoopsDB->queryF("SELECT color,canbemain FROM {$xoopsDB->prefix('apcal_cat')}"))
    {
        $sql = "ALTER TABLE {$xoopsDB->prefix('apcal_cat')} ";
        $sql .= 'ADD color VARCHAR( 7 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'#5555AA\' AFTER cat_desc,';
        $sql .= 'ADD canbemain TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT \'0\' AFTER autocreated';
        $xoopsDB->queryF($sql);
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS {$xoopsDB->prefix('apcal_pictures')} "; 
    $sql .= '(id int(10) unsigned NOT NULL AUTO_INCREMENT,';
    $sql .= 'event_id int(10) unsigned zerofill NOT NULL,';
    $sql .= 'picture varchar(255) NOT NULL,';
    $sql .= 'main_pic tinyint(1) unsigned NOT NULL DEFAULT \'0\',';
    $sql .= 'PRIMARY KEY (id)) ';
    $sql .= 'ENGINE=MyISAM DEFAULT CHARSET=utf8';
    $xoopsDB->queryF($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS {$xoopsDB->prefix('apcal_ro_events')} (
            roe_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            roe_eventid mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
            roe_number int(10) NOT NULL DEFAULT '0',
            roe_datelimit int(10) NOT NULL DEFAULT '0',
            roe_submitter int(10) NOT NULL DEFAULT '0',
            roe_date_created int(10) NOT NULL DEFAULT '0',
            PRIMARY KEY (roe_id),
            KEY event (roe_eventid)) 
            ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $xoopsDB->queryF($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS {$xoopsDB->prefix('apcal_ro_members')} (
            rom_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            rom_eventid mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
            rom_firstname varchar(200) DEFAULT NULL,
            rom_lastname varchar(200) DEFAULT NULL,
            rom_email varchar(200) DEFAULT NULL,
            rom_extrainfo1 varchar(200) DEFAULT NULL,
            rom_extrainfo2 varchar(200) DEFAULT NULL,
            rom_extrainfo3 varchar(200) DEFAULT NULL,
            rom_extrainfo4 varchar(200) DEFAULT NULL,
            rom_extrainfo5 varchar(200) DEFAULT NULL,
            rom_submitter int(10) NOT NULL DEFAULT '0',
            rom_date_created int(10) NOT NULL DEFAULT '0',
            PRIMARY KEY (rom_id),
            UNIQUE KEY UNQ_EMAIL (rom_eventid, rom_email),
            KEY event (rom_eventid)) 
            ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $xoopsDB->queryF($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS {$xoopsDB->prefix('apcal_ro_notify')} (
            ron_id int(10) unsigned NOT NULL AUTO_INCREMENT,
            ron_eventid mediumint(8) unsigned zerofill NOT NULL DEFAULT '00000000',
            ron_email varchar(200) DEFAULT NULL,
            ron_submitter int(10) DEFAULT NULL,
            ron_date_created int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (ron_id),
            KEY event (ron_eventid)) 
            ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
    $xoopsDB->queryF($sql);

    $xoopsDB->queryF("UPDATE {$xoopsDB->prefix('apcal_event')} SET start_date=NULL,end_date=NULL");
    $xoopsDB->queryF("UPDATE {$xoopsDB->prefix('apcal_event')} t, (SELECT id, shortsummary FROM {$xoopsDB->prefix('apcal_event')} x WHERE x.rrule_pid>0 GROUP BY x.shortsummary ORDER BY start) AS e SET t.rrule_pid=e.id WHERE t.shortsummary=e.shortsummary;");

    if(!is_dir(XOOPS_UPLOAD_PATH.'/APCal/')) {mkdir(XOOPS_UPLOAD_PATH.'/APCal/', 0755);}
    if(!is_dir(XOOPS_UPLOAD_PATH.'/APCal/thumbs/')) {mkdir(XOOPS_UPLOAD_PATH.'/APCal/thumbs/', 0755);}

    return true;
}

function makeShort($str)
{
    $replacements = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                          'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                          'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                          'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                          'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y');

    $str = strip_tags($str);
    $str = strtr($str, $replacements);

    return str_replace(array(" ", "-", "/", "\\", "'", "\"", "\r", "\n", "&", "?", "!", "%", ",", "."), "", $str);
}

?>
