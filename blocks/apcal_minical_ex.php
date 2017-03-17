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

if (!defined('APCAL_BLOCK_MINICAL_EX')) {
    define('APCAL_BLOCK_MINICAL_EX', 1);

    // XOOPS 2.1/2.2
    if (substr(XOOPS_VERSION, 6, 3) > 2.0) {
        $GLOBALS['apcal_blockinstance_id'] = $this->getVar('instanceid');
    }

    /**
     * @param $options
     * @return array|mixed
     */
    function apcal_minical_ex_show($options)
    {
        global $xoopsConfig, $xoopsDB, $xoopsUser;

        // speed check
        //global $GIJ_common_time ;
        //list($usec, $sec) = explode(" ",microtime());
        //echo ((float) $sec + (float) $usec) - $GIJ_common_time ;

        // get bid
        if (substr(XOOPS_VERSION, 6, 3) > 2.0) {
            // XOOPS 2.1/2.2
            // instanceid as bid from block_instance
            $bid = @$GLOBALS['apcal_blockinstance_id'];
        } else {
            // XOOPS 2.0.x
            if (is_object($GLOBALS['block_arr'][$GLOBALS['i']])) {
                // bid from newblocks
                $bid = $GLOBALS['block_arr'][$GLOBALS['i']]->getVar('bid');
            } else {
                return array();
            }
        }

        $moduleDirName = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];
        $gifaday       = empty($options[1]) ? 2 : (int)$options[1];
        $just1gif      = empty($options[2]) ? 0 : 1;
        //  $plugins_tmp = empty( $options[3] ) ? array() : explode( ',' , $options[3] ) ;
        // robots mode (arrows in minicalex will point not a current page but APCal)
        $robots_mode = preg_match('/(msnbot|Googlebot|Yahoo! Slurp)/i', $_SERVER['HTTP_USER_AGENT']);

        // GET URL extraction
        // Only integer values are valid (for preventing from XSS)
        $additional_get = '';
        if (!$robots_mode) {
            foreach ($_GET as $g_key => $g_val) {
                if ($g_key === 'caldate' || $g_key == session_name()) {
                    continue;
                }
                if ((int)$g_val != $g_val) {
                    $additional_get = '';
                    break;
                } else {
                    $additional_get .= '&amp;' . urlencode($g_key) . '=' . (int)$g_val;
                }
            }
        }

        // cache enable or not
        if (empty($_POST['apcal_jumpcaldate'])
            && (empty($_GET['caldate'])
                || in_array(substr($_GET['caldate'], 0, 4), array(date('Y'), date('Y') - 1)))
        ) {
            $enable_cache = true;
            //      $enable_cache = false ;
        } else {
            $enable_cache = false;
        }

        // cache read
        if ($enable_cache) {
            if (empty($_GET['caldate'])) {
                $Ym = date('Ym');
            } else {
                list($Y, $m) = explode('-', $_GET['caldate']);
                if (empty($m)) {
                    $Ym = date('Ym');
                } else {
                    $Ym = sprintf('%04d%02d', $Y, $m);
                }
            }
            $bid_hash   = substr(md5($bid . XOOPS_DB_PREFIX), -6);
            $uid        = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
            $cache_file = XOOPS_CACHE_PATH . "/{$moduleDirName }_minical_ex_{$bid_hash}_{$xoopsConfig['language']}_";
            if (file_exists($cache_file . $Ym)) {
                $cache_bodies = file($cache_file . $Ym);
                if (count($cache_bodies) == 3) {
                    $expire   = (int)$cache_bodies[0];
                    $prev_uid = (int)$cache_bodies[1];
                    if ($expire > time() && $prev_uid == $uid) {
                        $block = unserialize($cache_bodies[2]);
                        if ($robots_mode) {
                            $block['root_url'] = $block['mod_url'] . '/';
                            // $block['php_self'] = '/' ;
                            $block['additional_get'] = '';
                        } else {
                            $block['root_url'] = '';
                            // $block['php_self'] = $_SERVER['SCRIPT_NAME'] ;
                            $block['additional_get'] = $additional_get;
                        }
                        // speed check
                        //list($usec, $sec) = explode(" ",microtime());
                        //echo ((float) $sec + (float) $usec) - $GIJ_common_time ;
                        return $block;
                    }
                }
            }
        }

        // MyTextSanitizer
        $myts = MyTextSanitizer::getInstance();

        // setting physical & virtual paths
        $mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName ";
        $mod_url  = XOOPS_URL . "/modules/$moduleDirName ";

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

        $block = $cal->get_minical_ex($gifaday, $just1gif, $cal->get_plugins("mcx{$bid}"));

        // speed check
        // global $GIJ_common_time ;
        // list($usec, $sec) = explode(" ",microtime());
        // echo ((float) $sec + (float) $usec) - $GIJ_common_time ;

        if ($enable_cache) {
            $fp = fopen($cache_file . sprintf('%04d%02d', $cal->year, $cal->month), 'w');
            fwrite($fp, (time() + 300) . "\n"); // 5 mininutes (hard coded)
            fwrite($fp, (int)$uid . "\n");
            fwrite($fp, serialize($block));
            fclose($fp);
        }

        if ($robots_mode) {
            $block['root_url'] = $block['mod_url'] . '/';
            // $block['php_self'] = '/' ;
            $block['additional_get'] = '';
        } else {
            $block['root_url']       = '';
            $block['additional_get'] = $additional_get;
        }

        return $block;
    }

    /**
     * @param $options
     * @return string
     */
    function apcal_minical_ex_edit($options)
    {
        global $xoopsDB, $xoopsConfig;

        $moduleDirName      = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];
        $gifaday            = empty($options[1]) ? 2 : (int)$options[1];
        $just1gif_radio_yes = empty($options[2]) ? '' : 'checked';
        $just1gif_radio_no  = empty($options[2]) ? 'checked' : '';
        //$plugins4disp = empty( $options[3] ) ? '' : htmlspecialchars( str_replace( array( ' ' , "\t" , "\0" ) , '' ,  $options[3] ) , ENT_QUOTES ) ;

        // setting physical & virtual paths
        $mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName ";
        $mod_url  = XOOPS_URL . "/modules/$moduleDirName ";

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

        $ret = "<input type='hidden' name='options[0]' value='$moduleDirName ' />\n";

        // max dotgifs a day
        $ret .= _MB_APCAL_MAXGIFSADAY . ':';
        $ret .= "<input type='text' size='4' name='options[1]' value='$gifaday' style='text-align:right;' /><br>\n";

        // disallow multi gifs per a plugin per a day
        $ret .= _MB_APCAL_JUSTONCEADAYAPLUGIN . ':';
        $ret .= "<input type='radio' name='options[2]' value='1' $just1gif_radio_yes />" . _YES . "&nbsp;\n";
        $ret .= "<input type='radio' name='options[2]' value='0' $just1gif_radio_no />" . _NO . "<br>\n";

        return $ret;
    }
}
