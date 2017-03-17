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

// a plugin for mylinks

defined('XOOPS_ROOT_PATH') || exit('XOOPS Root Path not defined');

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

// set range (added 86400 second margin "begin" & "end")
$range_start_s = $this->month * 100;
$range_end_s   = $this->month * 100 + 31;

// query (added 86400 second margin "begin" & "end")
$result = $db->query('SELECT u.uname,u.uid,h.birthday FROM '
                     . $db->prefix('hakusen_users')
                     . ' h LEFT JOIN '
                     . $db->prefix('users')
                     . " u ON u.uid=h.uid WHERE h.birthday % 10000 >= $range_start_s AND h.birthday % 10000 <= $range_end_s AND `bd_open` AND u.level > 0");

while (list($uname, $uid, $birthday) = $db->fetchRow($result)) {
    $target_date = $birthday % 100;
    $tmp_array   = array(
        'dotgif'      => $plugin['dotgif'],
        'dirname'     => $plugin['dirname'],
        'link'        => XOOPS_URL . "/modules/{$plugin['dirname']}/userinfo.php?uid=$uid&amp;caldate={$this->year}-{$this->month}-$target_date",
        'id'          => $uid,
        'server_time' => 0,
        'user_time'   => 0,
        'name'        => 'uid',
        'title'       => $myts->htmlSpecialChars($uname)
    );
    if ($just1gif) {
        // just 1 gif per a plugin & per a day
        $plugin_returns[$target_date][$plugin['dirname']] = $tmp_array;
    } else {
        // multiple gifs allowed per a plugin & per a day
        $plugin_returns[$target_date][] = $tmp_array;
    }
}
