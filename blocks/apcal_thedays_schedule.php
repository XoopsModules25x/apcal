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
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 */

if (!defined('APCAL_BLOCK_THEDAYS_SCHEDULE_INCLUDED')) {
    define('APCAL_BLOCK_THEDAYS_SCHEDULE_INCLUDED', 1);

    // XOOPS 2.1/2.2
    if (substr(XOOPS_VERSION, 6, 3) > 2.0) {
        $GLOBALS['apcal_blockinstance_object'] = $this;
    }

    /**
     * @param $options
     * @return array
     */
    function apcal_thedays_schedule_show_tpl($options)
    {
        global $xoopsConfig, $xoopsDB;

        $moduleDirName = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];
        $now_cid       = empty($options[1]) ? 0 : (int)$options[1];

        // setting physical & virtual paths
        $mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
        $mod_url  = XOOPS_URL . "/modules/$moduleDirName";

        // defining class of APCal
        if (!class_exists('APCal_xoops')) {
            require_once "$mod_path/class/APCal.php";
            require_once "$mod_path/class/APCal_xoops.php";
        }

        // creating an instance of APCal
        $cal = new APCal_xoops('', $xoopsConfig['language'], true);

        // cid ¤Ë¤è¤ë¹Ê¤ê¹þ¤ß
        $cal->now_cid = $now_cid;

        // setting properties of APCal
        $cal->conn = $GLOBALS['xoopsDB']->conn;
        include "$mod_path/include/read_configs.php";
        $cal->base_url    = $mod_url;
        $cal->base_path   = $mod_path;
        $cal->images_url  = "$mod_url/assets/images/$skin_folder";
        $cal->images_path = "$mod_path/assets/images/$skin_folder";

        // ¥Ö¥í¥Ã¥¯ÇÛÎó¤Î¼«Ê¬¼«¿È¤ò½ñ¤­´¹¤¨¤ë title ¤Ë %s ¤ò´Þ¤á¤ë¤³¤È
        if (substr(XOOPS_VERSION, 6, 3) > 2.0) {
            $title_fmt = $GLOBALS['apcal_blockinstance_object']->getVar('title');
            $GLOBALS['apcal_blockinstance_object']->setVar('title', sprintf($title_fmt, sprintf(_APCAL_FMT_MD, $cal->month_short_names[date('n', $cal->unixtime)],
                                                                                                $cal->date_short_names[date('j', $cal->unixtime)])));
        } else {
            global $block_arr, $i;
            if (is_object($block_arr[$i])) {
                $title_fmt = $block_arr[$i]->getVar('title');
                $title     = sprintf($title_fmt, sprintf(_APCAL_FMT_MD, $cal->month_short_names[date('n', $cal->unixtime)], $cal->date_short_names[date('j', $cal->unixtime)]));
                $block_arr[$i]->setVar('title', $title);
            }
        }

        $block = $cal->get_blockarray_date_event("$mod_url/index.php");

        return $block;
    }

    /**
     * @param $options
     * @return string
     */
    function apcal_thedays_schedule_edit($options)
    {
        global $xoopsDB, $xoopsConfig;

        $moduleDirName = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];
        $now_cid       = empty($options[1]) ? 0 : (int)$options[1];

        // setting physical & virtual paths
        $mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
        $mod_url  = XOOPS_URL . "/modules/$moduleDirName";

        // defining class of APCal
        require_once "$mod_path/class/APCal.php";
        require_once "$mod_path/class/APCal_xoops.php";

        // creating an instance of APCal
        $cal                = new APCal_xoops(date('Y-n-j'), $xoopsConfig['language'], true);
        $cal->use_server_TZ = true;

        // setting properties of APCal
        $cal->conn = $GLOBALS['xoopsDB']->conn;
        include "$mod_path/include/read_configs.php";
        $cal->base_url    = $mod_url;
        $cal->base_path   = $mod_path;
        $cal->images_url  = "$mod_url/assets/images/$skin_folder";
        $cal->images_path = "$mod_path/assets/images/$skin_folder";

        $ret = "<input type='hidden' name='options[0]' value='$moduleDirName' />\n";

        // ¥«¥Æ¥´¥ê¡¼ÁªÂò¥Ü¥Ã¥¯¥¹¤ÎÀ¸À®
        $ret .= _MB_APCAL_CATSEL . ':';
        $ret .= "<select name='options[1]'>\n<option value='0'>" . _ALL . "</option>\n";
        foreach ($cal->categories as $cid => $cat) {
            $selected       = $now_cid == $cid ? 'selected' : '';
            $depth_desc     = str_repeat('-', (int)$cat->cat_depth);
            $cat_title4show = $cal->text_sanitizer_for_show($cat->cat_title);
            $ret            .= "\t<option value='$cid' $selected>$depth_desc $cat_title4show</option>\n";
        }
        $ret .= "</select>\n";

        return $ret;
    }
}
