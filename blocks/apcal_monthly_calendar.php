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

if (!defined('APCAL_BLOCK_MONTHLY_CALENDAR_INCLUDED')) {
    define('APCAL_BLOCK_MONTHLY_CALENDAR_INCLUDED', 1);

    /**
     * @param $options
     * @return mixed
     */
    function apcal_monthly_calendar_show($options)
    {
        global $xoopsConfig, $xoopsDB;

        $moduleDirName = empty($options[0]) ? basename(dirname(__DIR__)) : $options[0];

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

        $original_level = error_reporting(E_ALL ^ E_NOTICE);

        require_once "$mod_path/include/patTemplate.php";
        $tmpl = new PatTemplate();
        $tmpl->readTemplatesFromFile("$cal->images_path/block_monthly.tmpl.html");

        // setting skin folder
        $tmpl->addVar('WholeBoard', 'SKINPATH', $cal->images_url);

        // setting language
        $tmpl->addVar('WholeBoard', 'LANG_PREV_MONTH', _MB_APCAL_PREV_MONTH);
        $tmpl->addVar('WholeBoard', 'LANG_NEXT_MONTH', _MB_APCAL_NEXT_MONTH);
        $tmpl->addVar('WholeBoard', 'LANG_YEAR', _MB_APCAL_YEAR);
        $tmpl->addVar('WholeBoard', 'LANG_MONTH', _MB_APCAL_MONTH);
        $tmpl->addVar('WholeBoard', 'LANG_JUMP', _MB_APCAL_JUMP);

        // Static parameter for the request
        $tmpl->addVar('WholeBoard', 'GET_TARGET', "$mod_url/index.php");
        $tmpl->addVar('WholeBoard', 'QUERY_STRING', '');

        // Variables required in header part etc.
        $tmpl->addVars('WholeBoard', $cal->get_calendar_information('M'));

        // BODY of the calendar
        $tmpl->addVar('WholeBoard', 'CALENDAR_BODY', $cal->get_monthly_html("$mod_url/index.php"));

        // legends of long events
        foreach ($cal->long_event_legends as $bit => $legend) {
            $tmpl->addVar('LongEventLegends', 'BIT_MASK', 1 << ($bit - 1));
            $tmpl->addVar('LongEventLegends', 'LEGEND_ALT', _APCAL_MB_APCALALLDAY_EVENT . " $bit");
            $tmpl->addVar('LongEventLegends', 'LEGEND', $legend);
            $tmpl->addVar('LongEventLegends', 'SKINPATH', $cal->images_url);
            $tmpl->parseTemplate('LongEventLegends', 'a');
        }

        // content generated from patTemplate
        $block['content'] = $tmpl->getParsedTemplate('WholeBoard');
        error_reporting($original_level);

        return $block;
    }

    /**
     * @param $options
     * @return string
     */
    function apcal_monthly_calendar_edit($options)
    {
        return '';
    }
}
