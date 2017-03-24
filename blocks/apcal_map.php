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

if (!defined('APCAL_BLOCK_MAP_INCLUDED')) {
    define('APCAL_BLOCK_MAP_INCLUDED', 1);

    /**
     * @param $options
     * @return array
     */
    function apcal_map_show($options)
    {
        global $xoopsConfig, $xoopsDB;

        $original_level = error_reporting(E_ALL ^ E_NOTICE);

        $moduleDirName = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];

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

        $cal->get_monthly_html("$mod_url");

        if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
        } else {
            $moduleHelper = Xmf\Module\Helper::getHelper('system');
        }

        $block = array();
        if (is_array($cal->gmPoints) && !empty($cal->gmPoints)) {
            $tpl = new XoopsTpl();
            $tpl->assign('GMlatitude', $cal->gmlat);
            $tpl->assign('GMlongitude', $cal->gmlng);
            $tpl->assign('GMzoom', $cal->gmzoom);
            $tpl->assign('GMheight', $cal->gmheight . 'px');
            $tpl->assign('GMPoints', $cal->gmPoints);
            $tpl->assign('api_key', $moduleHelper->getConfig('apcal_mapsapi'));
            $block['map'] = $tpl->fetch(XOOPS_ROOT_PATH . '/modules/apcal/templates/apcal_googlemap.tpl');
        }

        error_reporting($original_level);

        return $block;
    }

    /**
     * @param $options
     * @return string
     */
    function apcal_map_edit($options)
    {
        return '';
    }
}
