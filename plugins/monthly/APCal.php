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

// a plugin for APCal (Don't refer this plugin!)

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

/*
    $db : db instance
    $myts : MyTextSanitizer instance
    $this->year : year
    $this->month : month
    $this->user_TZ : user's timezone (+1.5 etc)
    $this->server_TZ : server's timezone (-2.5 etc)
    $tzoffset_s2u : the offset from server to user
    $now : the result of time()
    $plugin = array('dirname'=>'dirname','name'=>'name','dotgif'=>'*.gif')
    $just1gif : 0 or 1

    $plugin_returns[ DATE ][]
*/

// for Duplicatable
if (!preg_match('/^(\D+)(\d*)$/', $plugin['dirname'], $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($plugin['dirname']));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

if ($this->base_url == XOOPS_URL . '/modules/' . $plugin['dirname']) {
    $cal = $this;
} else {
    // create a targeting instance of APCal
    $cal = new APCal_xoops('', $this->language, true);

    // this should not be affected by $_GET['cid']
    $cal->now_cid = '';

    // setting properties of APCal
    global $xoopsDB;
    $cal->conn = $GLOBALS['xoopsDB']->conn;
    include XOOPS_ROOT_PATH . "/modules/{$plugin['dirname']}/include/read_configs.php";
    $cal->base_url    = XOOPS_URL . '/modules/' . $plugin['dirname'];
    $cal->base_path   = XOOPS_ROOT_PATH . '/modules/' . $plugin['dirname'];
    $cal->images_url  = "$cal->base_url/assets/images/$skin_folder";
    $cal->images_path = "$cal->base_path/assets/images/$skin_folder";
}

// options
$options = explode('|', $plugin['options']);
// options[0] : category extract
if (!empty($options[0])) {
    $cids          = explode(',', $options[0]);
    $whr_cid_limit = '0';
    foreach ($cids as $cid) {
        $whr_cid_limit .= " OR categories LIKE '%" . sprintf('%05d,', (int)$cid) . "%'";
    }
} else {
    $whr_cid_limit = '1';
}

// ¥«¥Æ¥´¥ê¡¼´ØÏ¢¤ÎWHERE¾ò·ï¼èÆÀ
$whr_categories = $cal->get_where_about_categories();

// CLASS´ØÏ¢¤ÎWHERE¾ò·ï¼èÆÀ
$whr_class = $cal->get_where_about_class();

// ÈÏ°Ï¤Î¼èÆÀ
$range_start_s = mktime(0, 0, 0, $this->month, 0, $this->year);
$range_end_s   = mktime(0, 0, 0, $this->month + 1, 1, $this->year);

// Á´Æü¥¤¥Ù¥ó¥È°Ê³°¤Î½èÍý
$result = $GLOBALS['xoopsDB']->query("SELECT summary,id,start FROM $cal->table WHERE admission > 0 AND start >= $range_start_s AND start < $range_end_s AND ($whr_categories) AND ($whr_class) AND ($whr_cid_limit) AND allday <= 0");

while (list($title, $id, $server_time) = $db->fetchRow($result)) {
    $user_time = $server_time + $tzoffset_s2u;
    if (date('n', $user_time) != $this->month) {
        continue;
    }
    $target_date = date('j', $user_time);
    $tmp_array   = array(
        'dotgif'      => $plugin['dotgif'],
        'dirname'     => $plugin['dirname'],
        'link'        => XOOPS_URL . "/modules/{$plugin['dirname']}/index.php?smode=Daily&amp;caldate={$this->year}-{$this->month}-{$target_date}",
        'id'          => $id,
        'server_time' => $server_time,
        'user_time'   => $user_time,
        'name'        => 'id',
        'title'       => $this->text_sanitizer_for_show($title));
    if ($just1gif) {
        // just 1 gif per a plugin & per a day
        $plugin_returns[$target_date][$plugin['dirname']] = $tmp_array;
    } else {
        // multiple gifs allowed per a plugin & per a day
        $plugin_returns[$target_date][] = $tmp_array;
    }
}

// Á´Æü¥¤¥Ù¥ó¥ÈÀìÍÑ¤Î½èÍý
$result = $GLOBALS['xoopsDB']->query("SELECT summary,id,start,end FROM $cal->table WHERE admission > 0 AND start >= $range_start_s AND start < $range_end_s AND ($whr_categories) AND ($whr_class) AND ($whr_cid_limit) AND allday > 0");

while (list($title, $id, $start_s, $end_s) = $db->fetchRow($result)) {
    if ($start_s < $range_start_s) {
        $start_s = $range_start_s;
    }
    if ($end_s > $range_end_s) {
        $end_s = $range_end_s;
    }

    while ($start_s < $end_s) {
        $user_time = $start_s + $tzoffset_s2u;
        if (date('n', $user_time) == $this->month) {
            $target_date = date('j', $user_time);
            $tmp_array   = array(
                'dotgif'      => $plugin['dotgif'],
                'dirname'     => $plugin['dirname'],
                'link'        => XOOPS_URL . "/modules/{$plugin['dirname']}/index.php?smode=Daily&amp;caldate={$this->year}-{$this->month}-{$target_date}",
                'id'          => $id,
                'server_time' => $server_time,
                'user_time'   => $user_time,
                'name'        => 'id',
                'title'       => $this->text_sanitizer_for_show($title));
            if ($just1gif) {
                // just 1 gif per a plugin & per a day
                $plugin_returns[$target_date][$plugin['dirname']] = $tmp_array;
            } else {
                // multiple gifs allowed per a plugin & per a day
                $plugin_returns[$target_date][] = $tmp_array;
            }
        }
        $start_s += 86400;
    }
}
