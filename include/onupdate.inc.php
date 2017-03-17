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
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 */

defined('XOOPS_ROOT_PATH') || exit('XOOPS Root Path not defined');

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if ($ref == '' || strpos($ref, XOOPS_URL . '/modules/system/admin.php') === 0) {
    /* module specific part */
    global $xoopsDB;

    // 0.75 to 0.76
    $result = $GLOBALS['xoopsDB']->query('SELECT event_tz FROM ' . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_event") . ' LIMIT 1');
    if ($result) {
        list($event_tz) = $GLOBALS['xoopsDB']->fetchRow($result);
        if (false === strpos($event_tz, '.')) {
            $GLOBALS['xoopsDB']->queryF('ALTER TABLE '
                                        . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_event")
                                        . ' MODIFY event_tz FLOAT(2,1) NOT NULL DEFAULT 0.0, MODIFY server_tz FLOAT(2,1) NOT NULL DEFAULT 0.0, MODIFY poster_tz FLOAT(2,1) NOT NULL DEFAULT 0.0, ADD KEY (admission), ADD KEY (allday), ADD KEY (START), ADD KEY (END), ADD KEY (start_date), ADD KEY (end_date), ADD KEY (dtstamp), ADD KEY (unique_id), ADD KEY (cid), ADD KEY (event_tz), ADD KEY (server_tz), ADD KEY (poster_tz), ADD KEY (uid), ADD KEY (groupid), ADD KEY (class), ADD KEY (rrule_pid), ADD KEY (categories)');
        }
    }
    // ALTER TABLE xoops_apcal_event MODIFY event_tz smallint(5) NOT NULL default 0, MODIFY server_tz smallint(5) NOT NULL default 0, MODIFY poster_tz smallint(5) NOT NULL default 0, DROP KEY admission, DROP KEY allday, DROP KEY start, DROP KEY end, DROP KEY start_date, DROP KEY end_date, DROP KEY dtstamp, DROP KEY unique_id, DROP KEY cid, DROP KEY event_tz, DROP KEY server_tz, DROP KEY poster_tz, DROP KEY uid, DROP KEY groupid, DROP KEY class, DROP KEY rrule_pid, DROP KEY categories ;

    // 0.76 to 0.8
    $result = $GLOBALS['xoopsDB']->query('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_plugins") . ' LIMIT 1');
    if (!$result) {
        $GLOBALS['xoopsDB']->queryF('ALTER TABLE '
                                    . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_cat")
                                    . " CHANGE cat_extkey1 cat_depth TINYINT UNSIGNED NOT NULL DEFAULT 0, ADD cat_style VARCHAR(255) NOT NULL DEFAULT '', ADD KEY (pid), ADD KEY (weight), ADD KEY (cat_depth)");

        $GLOBALS['xoopsDB']->queryF('CREATE TABLE ' . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_plugins") . " (
          pi_id SMALLINT(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
          pi_title VARCHAR(255) NOT NULL DEFAULT '',
          pi_type VARCHAR(8) NOT NULL DEFAULT '',
          pi_dirname VARCHAR(50) NOT NULL DEFAULT '',
          pi_file VARCHAR(50) NOT NULL DEFAULT '',
          pi_dotgif VARCHAR(255) NOT NULL DEFAULT '',
          pi_options VARCHAR(255) NOT NULL DEFAULT '',
          pi_enabled TINYINT NOT NULL DEFAULT 0,
          pi_weight SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
          last_modified TIMESTAMP ,

          KEY (pi_weight),
          KEY (pi_type),
          KEY (pi_dirname),
          KEY (pi_file),
          KEY (pi_options),
          KEY (pi_enabled),
          PRIMARY KEY (pi_id)
        ) ENGINE=MyISAM ");
    }
    // DROP TABLE xoops_apcal_plugins ;

    // 0.8RC to 0.8RC2
    $result = $GLOBALS['xoopsDB']->query('SELECT pi_weight FROM ' . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_plugins") . ' LIMIT 1');
    if (!$result) {
        $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix("apcal{$mydirnumber}_plugins") . ' ADD pi_weight SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0, ADD KEY (pi_weight)');
    }

    /* General part */

    // Keep the values of block's options when module is updated (by nobunobu)
    include __DIR__ . '/updateblock.inc.php';
}
