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
 * @author       GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */

if (!defined('APCAL_BLOCK_MINI_CALENDAR_INCLUDED')) {
    define('APCAL_BLOCK_MINI_CALENDAR_INCLUDED', 1);

    /**
     * @param $options
     * @return array
     */
    function apcal_mini_calendar_show($options)
    {
        global $xoopsConfig, $xoopsDB, $xoopsUser;

        $moduleDirName = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];

        // caldate ¤äÆüÉÕ¥¸¥ã¥ó¥×¤Î»ØÄê¤¬Ìµ¤¯¡ÊÅö·î¤Î¥Ç¥Õ¥©¥ë¥È¥ß¥Ë¥«¥ì¥ó¥À¡¼É½¼¨¡Ë
        // ¤«¤Ä¡¢¥æ¡¼¥¶¤ÎTimezone¤¬defaultTZ¤È°ì½ï¡Ê°ìÈÖÂ¿¤½¤¦¤Ê¥·¥Á¥å¥¨¡¼¥·¥ç¥ó¡Ë¤Î
        // ¾ì¹ç¤Ë¤Ï¡¢¥­¥ã¥Ã¥·¥å¤ò»È¤¦
        if (empty($_GET['caldate']) && empty($_POST['apcal_jumpcaldate'])
            && (!is_object($xoopsUser)
                || $xoopsUser->timezone() == $xoopsConfig['default_TZ'])
        ) {
            $use_cache = true;
            $cachefile = XOOPS_CACHE_PATH . "/{$moduleDirName }_minical_cache_{$xoopsConfig['language']}.html";
            // 5 minutes
            if (file_exists($cachefile) && filemtime($cachefile) > time() - 300) {
                if (false !== $fp = fopen($cachefile, 'r')) {
                    $block['content'] = '';
                    while (!feof($fp)) {
                        $block['content'] .= fgets($fp, 4096);
                    }
                    fclose($fp);

                    return $block;
                }
            }
        } else {
            $use_cache = false;
        }

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

        // ignoring cid from GET
        $cal->now_cid = 0;

        // setting properties of APCal
        $cal->conn = $GLOBALS['xoopsDB']->conn;
        include "$mod_path/include/read_configs.php";
        $cal->base_url    = $mod_url;
        $cal->base_path   = $mod_path;
        $cal->images_url  = "$mod_url/assets/images/$skin_folder";
        $cal->images_path = "$mod_path/assets/images/$skin_folder";

        switch ($mini_calendar_target) {
            case 'MONTHLY':
                $get_target   = "$mod_url/index.php";
                $query_string = 'smode=Monthly';
                break;
            case 'WEEKLY':
                $get_target   = "$mod_url/index.php";
                $query_string = 'smode=Weekly';
                break;
            case 'DAILY':
                $get_target   = "$mod_url/index.php";
                $query_string = 'smode=Daily';
                break;
            case 'LIST':
                $get_target   = "$mod_url/index.php";
                $query_string = 'smode=List';
                break;
            default:
            case 'PHP_SELF':
                $get_target   = '';
                $query_string = '';
                break;
        }

        $block            = array();
        $block['content'] = $cal->get_mini_calendar_html($get_target, $query_string);

        // ¥­¥ã¥Ã¥·¥å¤Î½ñ¤­½Ð¤·
        if ($use_cache && $mini_calendar_target !== 'PHP_SELF') {
            if (false !== $fp = fopen($cachefile, 'w')) {
                fwrite($fp, $block['content']);
                fclose($fp);
            }
        }

        return $block;
    }

    /**
     * @param $options
     * @return string
     */
    function apcal_mini_calendar_edit($options)
    {
        return '';
    }
}
