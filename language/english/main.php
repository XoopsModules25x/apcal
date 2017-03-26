<?php

if (defined('FOR_XOOPS_LANG_CHECKER') || !defined('APCAL_MB_APCALLOADED')) {
    define('APCAL_MB_APCALLOADED', 1);

    // index.php
    define('_MB_APCAL_ERR_NOPERMTOUPDATE', 'You do not have permission to change events');
    define('_APCAL_APURL', 'http://xoops.antiquespromotion.ca');
    define('_APCAL_APURL2', 'http://www.antiquespromotion.ca');
    define('_MD_APCAL_COPYRIGHT', '<a href="http://xoops.antiquespromotion.ca" title="Calendar for Xoops" target="_blank">APCal</a> by <a href="http://www.antiquespromotion.ca" title="Antiques Promotion Canada" target="_blank">AP</a>');
    define('_MD_APCAL_ERR_NOPERMTOINSERT', 'You do not have permission to create events');
    define('_MD_APCAL_ERR_NOPERMTODELETE', 'You do not have permission to delete events');
    define('_MD_APCAL_ALT_PRINTTHISEVENT', 'Print this event');
    define('_APCAL_CALENDAR', 'Calendar');

    // print.php
    define('_MB_APCAL_COMESFROM', 'This event comes from %s');

    // edit event
    define('_APCAL_TH_GETCOORDS', 'Find position');
    define('_APCAL_TH_LATITUDE', 'Latitude');
    define('_APCAL_TH_LONGITUDE', 'Longitude');
    define('_APCAL_TH_ZOOM', 'Zoom level');
    define('_APCAL_TH_EMAIL', 'Email');
    define('_APCAL_TH_URL', 'URL');
    define('_APCAL_MAINPICTURE', 'Main picture');
    define('_APCAL_PICTURES', 'Other pictures');
    define('_APCAL_TH_MAINCATEGORY', 'Main Category');
    define('_APCAL_NONE', 'None');
    define('_APCAL_DAY', 'Day');
    define('_APCAL_DIFFERENTHOURS', 'Different hours for each days');
    define('_APCAL_SAMEHOURS', 'Use same hours for each day');

    // API texts
    define('_APCAL_PROVIDEDBY', 'Results provided by');
    define('_APCAL_X', 'by');
    define('_APCAL_AP', 'Antiques Promotion Canada');
    define('_APCAL_CALENDAROF', 'Calendar of');

    // Share admin
    define('_APCAL_SHARECALENDARFORM', 'Fill up that form to show the calendar on your website.');
    define('_APCAL_SHOWALLCAT', 'All categories');
    define('_APCAL_GENERATE', 'Generate');
    define('_APCAL_GENERATEHINT', 'You might have to click generate two times on certain browsers.');
    define('_APCAL_CATEGORIES', 'categories');
    define('_APCAL_NBEVENTS', 'Number of events displayed');
    define('_APCAL_WIDTH', 'Block width');
    define('_APCAL_STYLE', 'Style and colors');
    define('_APCAL_IFCUSTOM', 'If you chose "custom", fill the section below (in CSS).');
    define('_APCAL_BORDER', 'Block border');
    define('_APCAL_TITLE', 'Calendar title');
    define('_APCAL_TEXT', 'Calendar text');
    define('_APCAL_LINK', 'Event links');
    define('_APCAL_EVEN', 'Even rows');
    define('_APCAL_ODD', 'Odd rows');
    define('_APCAL_DEFAULT', 'Default');
    define('_APCAL_CUSTOM', 'Custom');
    define('_APCAL_THEME', 'From your theme');
    define('_APCAL_SHAREINFO', 'In order to display the calendar on your website, you must copy & paste this HTML code on your desired webpage.');
    define('_APCAL_SHARECALENDAR', 'Display this calendar on your website');

    // Event view
    define('_APCAL_PREVEVENT', 'Previous event');
    define('_APCAL_NEXTEVENT', 'Next event');
    define('_APCAL_BACKTOCAL', 'Back to calendar');

    // Tooltip
    define('_APCAL_CLICKFORDETAILS', 'Click to see event');
    define('_APCAL_BEGIN', 'From');
    define('_APCAL_END', 'To');
    define('_APCAL_LOCATION', 'Location');

    // Tell a friend
    define('_APCAL_TELLAFRIEND', 'Tell a friend');
    define('_APCAL_FROM', 'From');
    define('_APCAL_TO', 'To');
    define('_APCAL_CAPTCHA', 'Confirmation code');
    define('_APCAL_SUBJECT', 'Subject');
    define('_APCAL_MESSAGE', 'Message');
    define('_APCAL_TELLAFRIENDTEXT', 'You should visit this fabulous calendar at');

    // Share
    define('_APCAL_FB_LNG', 'en_US');
    define('_APCAL_GPLUS_LNG', 'en');
    define('_MD_APCAL_DBUPDATED', 'Database Updated');

    define('_MD_APCAL_ERR_NOPERMTOINSERT', 'en_US');

    define('_MD_APCAL_MODCONFIG', _MD_AM_MODCONFIG); //'Module Config Options'
    define('_MD_APCAL_TPLSETS', _AM_SYSTEM_TPLSETS); //'Templates'
    define('_MD_APCAL_FILENAME', 'File Name');
    define('_MD_APCAL_GENERATE', 'Generate');

    define('_MD_APCAL_PERMADDNG', _MD_AM_PERMADDNG);
    define('_MD_APCAL_PERMADDNGP', _MD_AM_PERMADDNGP);
    define('_MD_APCAL_PERMADDOK', _MD_AM_PERMADDOK);
    define('_MD_APCAL_PERMRESETNG', _MD_AM_PERMRESETNG);

//    define('_MD_AM_PERMADDNG', 'Could not add %s permission to %s for group %s');
//    define('_MD_AM_PERMADDOK', 'Added %s permission to %s for group %s');
//    define('_MD_AM_PERMRESETNG', 'Could not reset group permission for module %s');
//    define('_MD_AM_PERMADDNGP', 'All parent items must be selected.');

}
