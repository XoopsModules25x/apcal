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

defined('XOOPS_ROOT_PATH') || exit('XOOPS Root Path not defined');
$moduleDirName = basename(__DIR__);
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];
if (isset($_GET['fct']) && $_GET['fct'] == 'preferences') {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\r\n";
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="en" lang="en"><head>' . "\r\n";
    echo '<script type="text/javascript">var xoops_url = \'' . XOOPS_URL . '\';</script>';
    echo '<script src="' . XOOPS_URL . '/modules/' . $moduleDirName . '/assets/images/prefs.js"></script>';
}
$localesdir  = scandir(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/locales');
$locales[''] = '';
foreach ($localesdir as $locale) {
    if (substr($locale, -4, 4) == '.php') {
        $locales[substr($locale, 0, -4)] = substr($locale, 0, -4);
    }
}
$modversion['version']             = 2.22;
$modversion['module_status']       = 'Beta 2';
$modversion['release_date']        = '2017/03/16'; //NOT RELEASED!!!!  Work in progress
$modversion['name']                = _MI_APCAL_NAME . $mydirnumber;
$modversion['dirname']             = basename(__DIR__);
$modversion['image']               = 'assets/images/logoModule.png';
$modversion['description']         = _MI_APCAL_DESC;
$modversion['author']              = 'Antiques Promotion';
$modversion['license']             = 'GNU GPL 2.0';
$modversion['license_url']         = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']            = 0; //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
$modversion['help']                = 'page=help';
$modversion['module_website_url']  = 'http://xoops.antiquespromotion.ca';
$modversion['module_website_name'] = 'Antiques Promotion';
//$modversion['module_status']       = $modversion['status_version'];
$modversion['author_website_url']  = 'http://www.antiquespromotion.ca';
$modversion['author_website_name'] = 'Antiques Promotion';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';

//$modversion['dirmoduleadmin']      = 'Frameworks/moduleclasses/moduleadmin';
//$modversion['sysicons16']          = 'Frameworks/moduleclasses/icons/16';
//$modversion['sysicons32']          = 'Frameworks/moduleclasses/icons/32';
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';
// mysql
$modversion['sqlfile']['mysql'] = 'sql/apcal.sql';
$modversion['tables'][0]        = 'apcal_event';
$modversion['tables'][1]        = 'apcal_cat';
$modversion['tables'][2]        = 'apcal_plugins';
$modversion['tables'][3]        = 'apcal_pictures';
$modversion['tables'][4]        = 'apcal_ro_events';
$modversion['tables'][5]        = 'apcal_ro_members';
$modversion['tables'][6]        = 'apcal_ro_notify';

// Admin things
$modversion['system_menu'] = 1;
$modversion['hasAdmin']    = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_APCAL_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_APCAL_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_APCAL_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_APCAL_SUPPORT, 'link' => 'page=support'],
];

// Blocks
$b = 0;

++$b;
$modversion['blocks'][$b] = array(
    'file'        => 'apcal_mini_calendar.php',
    'name'        => _MI_APCAL_BNAME_MINICAL,
    'description' => _MI_APCAL_BNAME_MINICAL_DESC,
    'show_func'   => 'apcal_mini_calendar_show',
    'edit_func'   => 'apcal_mini_calendar_edit',
    //  'template'      => "apcal{$mydirnumber}_mini_calendar.tpl",
    'can_clone'   => true,
    'options'     => "{$moduleDirName }"
);

++$b;
$modversion['blocks'][$b] = array(
    'file'        => 'apcal_monthly_calendar.php',
    'name'        => _MI_APCAL_BNAME_MONTHCAL,
    'description' => _MI_APCAL_BNAME_MONTHCAL_DESC,
    'show_func'   => 'apcal_monthly_calendar_show',
    'edit_func'   => 'apcal_monthly_calendar_edit',
    //  'template'      => "apcal{$mydirnumber}_monthly_calendar.tpl" ,
    'options'     => "{$moduleDirName }"
);

++$b;
$modversion['blocks'][$b] = array(
    'file'        => 'apcal_todays_schedule.php',
    'name'        => _MI_APCAL_BNAME_TODAYS,
    'description' => _MI_APCAL_BNAME_TODAYS_DESC,
    'show_func'   => 'apcal_todays_schedule_show_tpl',
    'edit_func'   => 'apcal_todays_schedule_edit',
    'template'    => "apcal{$mydirnumber}_todays_schedule.tpl",
    'can_clone'   => true,
    'options'     => "{$moduleDirName }|0"
);

++$b;
$modversion['blocks'][$b] = array(
    'file'        => 'apcal_coming_schedule.php',
    'name'        => _MI_APCAL_BNAME_COMING,
    'description' => _MI_APCAL_BNAME_COMING_DESC,
    'show_func'   => 'apcal_coming_schedule_show_tpl',
    'edit_func'   => 'apcal_coming_schedule_edit',
    'template'    => "apcal{$mydirnumber}_coming_schedule.tpl",
    'can_clone'   => true,
    'options'     => "{$moduleDirName }|5|0|1|0"
);

++$b;
$modversion['blocks'][$b] = array(
    'file'        => 'apcal_new_event.php',
    'name'        => _MI_APCAL_BNAME_NEW,
    'description' => _MI_APCAL_BNAME_NEW_DESC,
    'show_func'   => 'apcal_new_event_show_tpl',
    'edit_func'   => 'apcal_new_event_edit',
    'template'    => "apcal{$mydirnumber}_new_event.tpl",
    'can_clone'   => true,
    'options'     => "{$moduleDirName }|5|0"
);

++$b;
$modversion['blocks'][$b] = array(
    'file'        => 'apcal_map.php',
    'name'        => _MI_APCAL_BNAME_MAP,
    'description' => _MI_APCAL_BNAME_MAP_DESC,
    'show_func'   => 'apcal_map_show',
    'edit_func'   => 'apcal_map_edit',
    'template'    => 'apcal_map.tpl',
    //'can_clone'       => true ,
    'options'     => "{$moduleDirName }"
);

// Menu
$modversion['hasMain'] = 1;

$subcount = 1;
global $cal;
if (isset($cal) && strtolower(get_class($cal)) == 'apcal_xoops') {
    if ($cal->insertable) {
        $modversion['sub'][$subcount]['name']  = _MI_APCAL_SM_SUBMIT;
        $modversion['sub'][$subcount++]['url'] = "index.php?action=Edit&amp;caldate=$cal->caldate";
    }
    foreach ($cal->categories as $cid => $cat) {
        if ($cat->ismenuitem) {
            $link                                  = $cal->make_cal_link('', $cal->default_view, $cid);
            $pos                                   = strpos($link, XOOPS_URL . '/modules/' . $moduleDirName . '/');
            $pos                                   = strlen(XOOPS_URL . '/modules/' . $moduleDirName . '/');
            $modversion['sub'][$subcount]['name']  = $cal->text_sanitizer_for_show($cat->cat_title);
            $modversion['sub'][$subcount++]['url'] = substr($link, $pos);/*"index.php?cid=$cid"*/
        }
    }
}

// Config Settings
$modversion['hasconfig'] = 1;
$c                       = 0;

// USERS
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_USERS',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}
++$c;
$modversion['config'][$c] = array(
    'name'        => 'users_authority',
    'title'       => '_MI_APCAL_USERS_AUTHORITY',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array(
        '_MI_APCAL_OPT_AUTH_NONE'    => 0,
        '_MI_APCAL_OPT_AUTH_WAIT'    => 1,
        '_MI_APCAL_OPT_AUTH_POST'    => 3,
        '_MI_APCAL_OPT_AUTH_BYGROUP' => 256
    )
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'guests_authority',
    'title'       => '_MI_APCAL_GUESTS_AUTHORITY',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array(
        '_MI_APCAL_OPT_AUTH_NONE' => 0,
        '_MI_APCAL_OPT_AUTH_WAIT' => 1,
        '_MI_APCAL_OPT_AUTH_POST' => 3
    )
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_can_output_ics',
    'title'       => '_MI_APCAL_CANOUTPUTICS',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array('_MI_APCAL_OPT_CANNOTOUTPUTICS' => 0, '_MI_APCAL_OPT_CANOUTPUTICS' => 1)
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_ics_new_cal',
    'title'       => '_MI_APCAL_ICSNEWCAL',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_nameoruname',
    'title'       => '_MI_APCAL_NAMEORUNAME',
    'description' => '_MI_APCAL_DESCNAMEORUNAME',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'uname',
    'options'     => array(
        '_MI_APCAL_OPT_USENAME'  => 'name',
        '_MI_APCAL_OPT_USEUNAME' => 'uname',
        '_MI_APCAL_OPT_NONE'     => 'none'
    )
);

// COLORS
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_COLORS',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}
++$c;
$modversion['config'][$c] = array(
    'name'        => 'skin_folder',
    'title'       => '_MI_APCAL_SKINFOLDER',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'default',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_thmORdefault',
    'title'       => '_MI_APCAL_THMORDEFAULT',
    'description' => '_MI_APCAL_THMORDEFAULTDESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array('_MI_APCAL_OPT_DEFAULT' => 2, '_MI_APCAL_OPT_THM' => 1, '_MI_APCAL_OPT_CUSTOM' => 0)
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_thmCSS',
    'title'       => '_MI_APCAL_GETTHMCOLOR',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'style.css',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_sunday_color',
    'title'       => '_MI_APCAL_SUNDAYCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#CC0000',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_sunday_bgcolor',
    'title'       => '_MI_APCAL_SUNDAYBGCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#FFEEEE',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_weekday_color',
    'title'       => '_MI_APCAL_WEEKDAYCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#000066',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_weekday_bgcolor',
    'title'       => '_MI_APCAL_WEEKDAYBGCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#FFFFFF',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_saturday_color',
    'title'       => '_MI_APCAL_SATURDAYCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#0000FF',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_saturday_bgcolor',
    'title'       => '_MI_APCAL_SATURDAYBGCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#EEF7FF',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_holiday_color',
    'title'       => '_MI_APCAL_HOLIDAYCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#CC0000',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_holiday_bgcolor',
    'title'       => '_MI_APCAL_HOLIDAYBGCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#FFEEEE',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_targetday_bgcolor',
    'title'       => '_MI_APCAL_TARGETDAYBGCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#CCFF99',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_calhead_color',
    'title'       => '_MI_APCAL_CALHEADCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#009900',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_calhead_bgcolor',
    'title'       => '_MI_APCAL_CALHEADBGCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#CCFFCC',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_frame_css',
    'title'       => '_MI_APCAL_CALFRAMECSS',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#000000',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_event_bgcolor',
    'title'       => '_MI_APCAL_EVENTBGCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#EEEEEE',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_event_color',
    'title'       => '_MI_APCAL_EVENTCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#000000',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_allcats_color',
    'title'       => '_MI_APCAL_ALLCATSCOLOR',
    'description' => '',
    'formtype'    => 'color',
    'valuetype'   => 'text',
    'default'     => '#5555AA',
    'options'     => array()
);

// GENERAL SETTINGS
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_SETTINGS',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_enablesocial',
    'title'       => '_MI_APCAL_ENABLESOCIAL',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_enabletellafriend',
    'title'       => '_MI_APCAL_ENABLETELLAFRIEND',
    'description' => '_MI_APCAL_ENABLETELLAFRIEND_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_useurlrewrite',
    'title'       => '_MI_APCAL_USEURLREWRITE',
    'description' => '_MI_APCAL_DESCUSEURLREWRITE',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_enablesharing',
    'title'       => '_MI_APCAL_ENABLESHARING',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_enableregistration',
    'title'       => '_MI_APCAL_ENABLEREGISTRATION',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);

// CALENDAR SETTINGS
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_CALSETTINGS',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_week_start',
    'title'       => '_MI_APCAL_WEEKSTARTFROM',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array('_MI_APCAL_OPT_STARTFROMSUN' => 0, '_MI_APCAL_OPT_STARTFROMMON' => 1)
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_week_numbering',
    'title'       => '_MI_APCAL_WEEKNUMBERING',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array('_MI_APCAL_OPT_WEEKNOEACHMONTH' => 0, '_MI_APCAL_OPT_WEEKNOWHOLEYEAR' => 1)
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_day_start',
    'title'       => '_MI_APCAL_DAYSTARTFROM',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array(
        '0:00' => 0,
        '1:00' => 3600,
        '2:00' => 7200,
        '3:00' => 10800,
        '4:00' => 14400,
        '5:00' => 18000,
        '6:00' => 21600
    )
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_use24',
    'title'       => '_MI_APCAL_USE24HOUR',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'timezone_using',
    'title'       => '_MI_APCAL_TIMEZONE_USING',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'winter',
    'options'     => array(
        '_MI_APCAL_OPT_TZ_USEXOOPS'  => 'xoops',
        '_MI_APCAL_OPT_TZ_USEWINTER' => 'winter',
        '_MI_APCAL_OPT_TZ_USESUMMER' => 'summer'
    )
);

// CALENDAR DISPLAY
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_CALDISPLAY',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_locale',
    'title'       => '_MI_APCAL_LOCALE',
    'description' => '_MI_APCAL_LOCALEDESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => _MI_APCAL_DEFAULTLOCALE,
    'options'     => $locales
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'default_view',
    'title'       => '_MI_APCAL_DEFAULT_VIEW',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'Monthly',
    'options'     => array(
        '_MI_APCAL_OPT_MINI_MONTHLY' => 'Monthly',
        '_MI_APCAL_OPT_MINI_WEEKLY'  => 'Weekly',
        '_MI_APCAL_OPT_MINI_DAILY'   => 'Daily',
        '_MI_APCAL_OPT_MINI_LIST'    => 'List'
    )
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'mini_calendar_target',
    'title'       => '_MI_APCAL_MINICAL_TARGET',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'MONTHLY',
    'options'     => array(
        '_MI_APCAL_OPT_MINI_PHPSELF' => 'PHP_SELF',
        '_MI_APCAL_OPT_MINI_MONTHLY' => 'MONTHLY',
        '_MI_APCAL_OPT_MINI_WEEKLY'  => 'WEEKLY',
        '_MI_APCAL_OPT_MINI_DAILY'   => 'DAILY',
        '_MI_APCAL_OPT_MINI_LIST'    => 'LIST'
    )
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_widerDays',
    'title'       => '_MI_APCAL_WIDERWEEKEND',
    'description' => '_MI_APCAL_WIDERWEEKENDDESC',
    'formtype'    => 'select_multi',
    'valuetype'   => 'array',
    'default'     => array('Saturday', 'Sunday'),
    'options'     => array(
        '_MI_APCAL_MONDAY'    => 'Monday',
        '_MI_APCAL_TUESDAY'   => 'Tuesday',
        '_MI_APCAL_WEDNESDAY' => 'Wednesday',
        '_MI_APCAL_THURSDAY'  => 'Thursday',
        '_MI_APCAL_FRIDAY'    => 'Friday',
        '_MI_APCAL_SATURDAY'  => 'Saturday',
        '_MI_APCAL_SUNDAY'    => 'Sunday'
    )
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_displayCatTitle',
    'title'       => '_MI_APCAL_DISPLAYCATTITLE',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_displayTimezone',
    'title'       => '_MI_APCAL_DISPLAYTIMEZONE',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array()
);

// EVENTS
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_EVENTS',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_max_rrule_extract',
    'title'       => '_MI_APCAL_MAXRRULEEXTRACT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '100',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_eventNavEnabled',
    'title'       => '_MI_APCAL_EVENTNAVENABLED',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);

// PICTURES
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_PICTURES',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_nbPictures',
    'title'       => '_MI_APCAL_NBPICS',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '5',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_picWidth',
    'title'       => '_MI_APCAL_PICSWIDTH',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '150',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_picHeight',
    'title'       => '_MI_APCAL_PICSHEIGHT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '150',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_showPicMonthly',
    'title'       => '_MI_APCAL_SHOWPICMONTHLY',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_showPicWeekly',
    'title'       => '_MI_APCAL_SHOWPICWEEKLY',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_showPicDaily',
    'title'       => '_MI_APCAL_SHOWPICDAILY',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_showPicList',
    'title'       => '_MI_APCAL_SHOWPICLIST',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);

// GOOGLE MAP
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_MAP',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_mapsapi',
    'title'       => '_MI_APCAL_GMAPS_API',
    'description' => '_MI_APCAL_GMAPS_API_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'no key'
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_enablecalmap',
    'title'       => '_MI_APCAL_ENABLECALMAP',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_enableeventmap',
    'title'       => '_MI_APCAL_ENABLEEVENTMAP',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array()
);
++$c;
$modversion['config'][$c] = array(
    'name'        => 'apcal_gmheight',
    'title'       => '_MI_APCAL_GMHEIGHT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '350',
    'options'     => array()
);

// COMMETS AND NOTIFICATIONS
if (substr(XOOPS_VERSION, 6) >= '2.5.0') {
    ++$c;
    $modversion['config'][$c] = array(
        'name'        => 'break' . $c,
        'title'       => '_MI_APCAL_COMMENTSNOT',
        'description' => '',
        'formtype'    => 'line_break',
        'valuetype'   => 'textbox',
        'default'     => 'head'
    );
}

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = "apcal{$mydirnumber}_search";

// Comments
$modversion['hasComments']          = 1;
$modversion['comments']['itemName'] = 'event_id';
$modversion['comments']['pageName'] = 'index.php';
// Comment callback functions
$modversion['comments']['callbackFile']        = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'apcal_comments_approve';
$modversion['comments']['callback']['update']  = 'apcal_comments_update';

// ------------------- Templates ------------------- //

$modversion['templates'] = [
    // User
    ['file' => "apcal{$mydirnumber}_event_detail.tpl", 'description' => ''],
    ['file' => "apcal{$mydirnumber}_print.tpl", 'description' => ''],
    ['file' => "apcal{$mydirnumber}_event_list.tpl", 'description' => ''],

    ['file' => "apcal{$mydirnumber}_getCoords.tpl", 'description' => ''],
    ['file' => "apcal_getCoords.tpl", 'description' => '']
//    ['file' => "apcal{$mydirnumber}_shareCalendar.tpl", 'description' => ''],
//    ['file' => "apcal{$mydirnumber}_googlemap.tpl", 'description' => '']
];

//$modversion['templates'][1]['file']        = "apcal{$mydirnumber}_event_detail.tpl";
//$modversion['templates'][1]['description'] = '';
//$modversion['templates'][2]['file']        = "apcal{$mydirnumber}_print.tpl";
//$modversion['templates'][2]['description'] = '';
//$modversion['templates'][3]['file']        = "apcal{$mydirnumber}_event_list.tpl";
//$modversion['templates'][3]['description'] = '';

// Notification
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = "apcal{$mydirnumber}_notify_iteminfo";

$modversion['notification']['category'][1]['name']           = 'global';
$modversion['notification']['category'][1]['title']          = _MI_APCAL_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_APCAL_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php');
$modversion['notification']['category'][2]['name']           = 'category';
$modversion['notification']['category'][2]['title']          = _MI_APCAL_CATEGORY_NOTIFY;
$modversion['notification']['category'][2]['description']    = _MI_APCAL_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = array('index.php');
$modversion['notification']['category'][2]['item_name']      = 'cid';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

$modversion['notification']['category'][3]['name']           = 'event';
$modversion['notification']['category'][3]['title']          = _MI_APCAL_EVENT_NOTIFY;
$modversion['notification']['category'][3]['description']    = _MI_APCAL_EVENT_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = array('index.php');
$modversion['notification']['category'][3]['item_name']      = 'event_id';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name']          = 'new_event';
$modversion['notification']['event'][1]['category']      = 'global';
$modversion['notification']['event'][1]['title']         = _MI_APCAL_GLOBAL_NEWEVENT_NOTIFY;
$modversion['notification']['event'][1]['caption']       = _MI_APCAL_GLOBAL_NEWEVENT_NOTIFYCAP;
$modversion['notification']['event'][1]['description']   = _MI_APCAL_GLOBAL_NEWEVENT_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'global_newevent_notify';
$modversion['notification']['event'][1]['mail_subject']  = _MI_APCAL_GLOBAL_NEWEVENT_NOTIFYSBJ;

$modversion['notification']['event'][2]['name']          = 'new_event';
$modversion['notification']['event'][2]['category']      = 'category';
$modversion['notification']['event'][2]['title']         = _MI_APCAL_CATEGORY_NEWEVENT_NOTIFY;
$modversion['notification']['event'][2]['caption']       = _MI_APCAL_CATEGORY_NEWEVENT_NOTIFYCAP;
$modversion['notification']['event'][2]['description']   = _MI_APCAL_CATEGORY_NEWEVENT_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'category_newevent_notify';
$modversion['notification']['event'][2]['mail_subject']  = _MI_APCAL_CATEGORY_NEWEVENT_NOTIFYSBJ;

$modversion['onInstall'] = 'include/oninstall.php';
$modversion['onUpdate']  = 'include/onupdate.php';

// Keep the values of block's options when module is updated (by nobunobu)
if (!empty($_POST['fct']) && !empty($_POST['op']) && !empty($_POST['diranme']) && $_POST['fct'] == 'modulesadmin'
    && $_POST['op'] == 'update_ok'
    && $_POST['dirname'] == $modversion['dirname']
) {
    include __DIR__ . '/include/onupdate.inc.php';
}
