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

defined('XOOPS_ROOT_PATH') || exit('XOOPS Root Path not defined');

$moduleDirName = basename(dirname(__DIR__));

eval('

function b_sitemap_' . $moduleDirName . '(){
    return b_sitemap_APCal_base( "' . $moduleDirName . '" ) ;
}

');

if (!function_exists('b_sitemap_APCal_base')) {
    /**
     * @param $moduleDirName
     * @return array
     */
    function b_sitemap_APCal_base($moduleDirName)
    {
        global $xoopsConfig, $xoopsDB, $xoopsUser;
        $myts = MyTextSanitizer::getInstance();

        // get $mydirnumber
        if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
            echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
        }
        $mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

        // setting physical & virtual paths
        $mod_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName";
        $mod_url  = XOOPS_URL . "/modules/$moduleDirName";

        // defining class of APCal
        if (!class_exists('APCal_xoops')) {
            require_once "$mod_path/class/APCal.php";
            require_once "$mod_path/class/APCal_xoops.php";
        }

        // creating an instance of APCal
        $cal                = new APCal_xoops('', $xoopsConfig['language'], true);
        $cal->use_server_TZ = true;

        // setting properties of APCal
        $cal->conn = $GLOBALS['xoopsDB']->conn;
        //$cal->table = $xoopsDB->prefix( APCAL_EVENT_TABLE ) ;
        include "$mod_path/include/read_configs.php";

        $ret = array();
        foreach ($cal->categories as $cat) {

            // only Top category is shown
            if ($cat->cat_depth > 1) {
                continue;
            }

            $ret['parent'][] = array(
                'id'    => $cat->cid,
                'title' => $myts->htmlSpecialChars($cat->cat_title),
                'url'   => "index.php?cid=$cat->cid"
            );
        }

        return $ret;
    }
}
