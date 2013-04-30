<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( '_MI_APCAL_APCALLOADED' ) ) {

define( '_MI_APCAL_APCALLOADED' , 1 ) ;

// Module Info

// The name of this module
define("_MI_APCAL_NAME","APCal");

// A brief description of this module
define("_MI_APCAL_DESC","Calendar module with Scheduler");

// Default Locale
define("_MI_APCAL_DEFAULTLOCALE","usa");

// Names of blocks for this module (Not all module has blocks)
define("_MI_APCAL_BNAME_MINICAL","MiniCalendar");
define("_MI_APCAL_BNAME_MINICAL_DESC","Display MiniCalendar block");
define("_MI_APCAL_BNAME_MINICALEX","MiniCalendarEx");
define("_MI_APCAL_BNAME_MINICALEX_DESC","Extensible minicalendar with plugin system");
define("_MI_APCAL_BNAME_MONTHCAL","Monthly calendar");
define("_MI_APCAL_BNAME_MONTHCAL_DESC","Display full size of Monthly calendar");
define("_MI_APCAL_BNAME_TODAYS","Today's events");
define("_MI_APCAL_BNAME_TODAYS_DESC","Display events for today");
define("_MI_APCAL_BNAME_THEDAYS","Events on %s");
define("_MI_APCAL_BNAME_THEDAYS_DESC","Display events for the day indicated");
define("_MI_APCAL_BNAME_COMING","Coming Events");
define("_MI_APCAL_BNAME_COMING_DESC","Display Coming Events");
define("_MI_APCAL_BNAME_AFTER","Events after %s");
define("_MI_APCAL_BNAME_AFTER_DESC","Display events after the day indicated");
define("_MI_APCAL_BNAME_NEW","Events newly posted");
define("_MI_APCAL_BNAME_NEW_DESC","Display events ordered like that newer is upper");
define("_MI_APCAL_BNAME_MAP", 'Map of events for this month');
define("_MI_APCAL_BNAME_MAP_DESC", '');

// Names of submenu
define("_MI_APCAL_SM_SUBMIT","Submit");

//define("_MI_APCAL_ADMENU1","");

// Title of config items
define("_MI_APCAL_USERS_AUTHORITY", "Authorities of Users");
define("_MI_APCAL_GUESTS_AUTHORITY", "Authorities of Guests");
define("_MI_APCAL_DEFAULT_VIEW", "Default View in center");
define("_MI_APCAL_MINICAL_TARGET", "Target View from MiniCalendar");
define("_MI_APCAL_COMING_NUMROWS", "The number of events in Coming Events block");
define("_MI_APCAL_SKINFOLDER", "Name of skin folder");
define("_MI_APCAL_LOCALE", "Display Holidays in:");
define("_MI_APCAL_SUNDAYCOLOR", "Color of Sunday");
define("_MI_APCAL_WEEKDAYCOLOR", "Color of weekday");
define("_MI_APCAL_SATURDAYCOLOR", "Color of Saturday");
define("_MI_APCAL_HOLIDAYCOLOR", "Color of holiday");
define("_MI_APCAL_TARGETDAYCOLOR", "Color of targeted day");
define("_MI_APCAL_SUNDAYBGCOLOR", "Bgcolor of Sunday");
define("_MI_APCAL_WEEKDAYBGCOLOR", "Bgcolor of weekday");
define("_MI_APCAL_SATURDAYBGCOLOR", "Bgcolor of Saturday");
define("_MI_APCAL_HOLIDAYBGCOLOR", "Bgcolor of holiday");
define("_MI_APCAL_TARGETDAYBGCOLOR", "Bgcolor of targeted day");
define("_MI_APCAL_CALHEADCOLOR", "Color of header part of calendar");
define("_MI_APCAL_CALHEADBGCOLOR", "Bgcolor of header part of calendar");
define("_MI_APCAL_CALFRAMECSS", "Style for the frame of calendar");
define("_MI_APCAL_CANOUTPUTICS", "Permission of outputting ics files");
define("_MI_APCAL_MAXRRULEEXTRACT", "Upper limit of events extracted by Rrule.(COUNT)");
define("_MI_APCAL_WEEKSTARTFROM", "Beginning day of the week");
define("_MI_APCAL_WEEKNUMBERING", "Numbering rule for weeks");
define("_MI_APCAL_DAYSTARTFROM", "Borderline to separate days");
define("_MI_APCAL_TIMEZONE_USING", "Timezone of the server");
define("_MI_APCAL_USE24HOUR", "24hours system (No means 12hours system)");
define("_MI_APCAL_NAMEORUNAME" , "Poster name displayed" ) ;

define("_MI_APCAL_GMLAT" , "\"Google Map\" default latitude" ) ;
define("_MI_APCAL_GMLNG" , "\"Google Map\" default longitude" ) ;
define("_MI_APCAL_GMZOOM" , "\"Google Map\" default zoom level" ) ;
define("_MI_APCAL_GMHEIGHT" , "\"Google Map\" height (in pixels)" ) ;
define("_MI_APCAL_USEURLREWRITE" , "Use URL rewriting" ) ;
define("_MI_APCAL_WIDERWEEKEND" , "Wider columns in month view for:" ) ;
define('_MI_APCAL_GETTHMCOLOR', 'Main css filename in your theme folder');
define('_MI_APCAL_THMORDEFAULT', 'Colors from:');
define('_MI_APCAL_ENABLECALMAP', 'Show google map in calendar view');
define('_MI_APCAL_ENABLEEVENTMAP', 'Show google map in event view');
define('_MI_APCAL_ENABLESHARING', 'Enable users to share your calendar on their website');
define('_MI_APCAL_EVENTNAVENABLED', 'Enable navigation menu in event view');
define('_MI_APCAL_DISPLAYCATTITLE', 'Display category tilte as module page title');
define('_MI_APCAL_ENABLESOCIAL', 'Enable social networks links');
define('_MI_APCAL_NBPICS', 'Maximum number of pictures allowed (0 if you don\'t want any)');
define('_MI_APCAL_PICSWIDTH', 'Maximum width of pictures for display in event view');
define('_MI_APCAL_PICSHEIGHT', 'Maximum height of pictures for display in event view');
define('_MI_APCAL_SHOWPICMONTHLY', 'Show main picture in monthly view');
define('_MI_APCAL_SHOWPICWEEKLY', 'Show main picture in weekly view');
define('_MI_APCAL_SHOWPICDAILY', 'Show main picture in daily view');
define('_MI_APCAL_SHOWPICLIST', 'Show main picture in list view');
define('_MI_APCAL_EVENTBGCOLOR', ' Bgcolor of event\'s row');
define('_MI_APCAL_EVENTCOLOR', 'Color of event\'s row');
define('_MI_APCAL_ALLCATSCOLOR', 'Color for the default category');
define('_MI_APCAL_DISPLAYTIMEZONE', 'Display Timezone when showing time');
define('_MI_APCAL_ICSNEWCAL', 'Create a new calendar for ical exportation');
define('_MI_APCAL_ENABLEREGISTRATION', 'Enable online registration');

define('_MI_APCAL_USERS', 'Users');
define('_MI_APCAL_COLORS', 'Colors');
define('_MI_APCAL_SETTINGS', 'General Settings');
define('_MI_APCAL_CALSETTINGS', 'Calendar Settings');
define('_MI_APCAL_CALDISPLAY', 'Calendar Display');
define('_MI_APCAL_EVENTS', 'Events');
define('_MI_APCAL_PICTURES', 'Pictures');
define('_MI_APCAL_MAP', 'Google Map');
define('_MI_APCAL_COMMENTSNOT', 'Comments & Notifications');

// Description of each config items
define("_MI_APCAL_EDITBYGUESTDSC", "Permission of adding events by Guest");
define("_MI_APCAL_LOCALEDESC", "N.B.: Holidays are perpetual for USA, Canada, Canada-fr and France only. For all other countries, you must make it yourself.");
define("_MI_APCAL_DESCNAMEORUNAME" , "Select which 'name' is displayed" );
define("_MI_APCAL_DESCUSEURLREWRITE" , "<b>In order to make it works, copy /modules/APCal/doc/.htaccess to /modules/APCal/.</b><br /><br />N.B.: If you don't have the apache \"mod_rewrite\" installed on your server, it might not work. Please contact your system admin for details." ) ;
define("_MI_APCAL_WIDERWEEKENDDESC" , "Hold down the \"Ctrl\" key on your keyboard while you click to select more than one day." );
define('_MI_APCAL_THMORDEFAULTDESC', 'Theme: Give your main css filename in the next field. (Recommended)<br />Custom: Fill up the next 13 fields.<br />Default: Use the default colors.');

// Options of each config items
define("_MI_APCAL_OPT_AUTH_NONE", "cannot add");
define("_MI_APCAL_OPT_AUTH_WAIT", "can add but Events need approval");
define("_MI_APCAL_OPT_AUTH_POST", "can add Events without approval");
define("_MI_APCAL_OPT_AUTH_BYGROUP", "Specified in Group's permissions");
define("_MI_APCAL_OPT_MINI_PHPSELF", "Current Page");
define("_MI_APCAL_OPT_MINI_MONTHLY", "Monthly Calendar");
define("_MI_APCAL_OPT_MINI_WEEKLY", "Weekly Calendar");
define("_MI_APCAL_OPT_MINI_DAILY", "Daily Calendar");
define("_MI_APCAL_OPT_MINI_LIST", "Event List");
define("_MI_APCAL_OPT_CANOUTPUTICS", "can output");
define("_MI_APCAL_OPT_CANNOTOUTPUTICS", "cannot output");
define("_MI_APCAL_OPT_STARTFROMSUN", "Sunday");
define("_MI_APCAL_OPT_STARTFROMMON", "Monday");
define("_MI_APCAL_OPT_WEEKNOEACHMONTH", "by each month");
define("_MI_APCAL_OPT_WEEKNOWHOLEYEAR", "by whole year");
define("_MI_APCAL_OPT_USENAME" , "Real Name" ) ;
define("_MI_APCAL_OPT_USEUNAME" , "Login Name" ) ;
define("_MI_APCAL_OPT_TZ_USEXOOPS" , "value of XOOPS config" ) ;
define("_MI_APCAL_OPT_TZ_USEWINTER" , "value told from the server as winter time (recommended)" ) ;
define("_MI_APCAL_OPT_TZ_USESUMMER" , "value told from the server as summer time" ) ;
define('_MI_APCAL_OPT_THM', 'Theme');
define('_MI_APCAL_OPT_CUSTOM', 'Custom');
define('_MI_APCAL_OPT_DEFAULT', 'Default');
define("_MI_APCAL_OPT_NONE", "None");
define('_MI_APCAL_SUNDAY', 'Sunday');
define('_MI_APCAL_MONDAY', 'Monday');
define('_MI_APCAL_TUESDAY', 'Tuesday');
define('_MI_APCAL_WEDNESDAY', 'Wednesday');
define('_MI_APCAL_THURSDAY', 'Thursday');
define('_MI_APCAL_FRIDAY', 'Friday');
define('_MI_APCAL_SATURDAY', 'Saturday');

// Admin Menus
define('_MI_APCAL_INDEX', 'Index');
define("_MI_APCAL_ADMENU0","Admitting Events");
define("_MI_APCAL_ADMENU1","Events Manager");
define("_MI_APCAL_ADMENU_CAT","Categories Manager");
define("_MI_APCAL_ADMENU_CAT2GROUP","Category's Permissions");
define("_MI_APCAL_ADMENU2","Global Permissions");
define("_MI_APCAL_ADMENU_TM","Table Maintenance");
define("_MI_APCAL_ADMENU_PLUGINS","Plugins Manager");
define("_MI_APCAL_ADMENU_ICAL","Importing iCalendar");
define('_MI_APCAL_ADMENU_MYTPLSADMIN','Templates');
define("_MI_APCAL_ADMENU_MYBLOCKSADMIN","Blocks & Groups Admin");

// Text for notifications
define('_MI_APCAL_GLOBAL_NOTIFY', 'Global');
define('_MI_APCAL_GLOBAL_NOTIFYDSC', 'Global APCal notification options.');
define('_MI_APCAL_CATEGORY_NOTIFY', 'Category');
define('_MI_APCAL_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current category.');
define('_MI_APCAL_EVENT_NOTIFY', 'Event');
define('_MI_APCAL_EVENT_NOTIFYDSC', 'Notification options that apply to the current event.');

define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFY', 'New Event');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYCAP', 'Notify me when a new event is created.');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYDSC', 'Notify me with the description included when a new event is created.');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New event');

define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFY', 'New Event in the Category');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYCAP', 'Notify me when a new event is created in the Category.');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYDSC', 'Notify me with the description included when a new event is created in the Category.');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New event in {CATEGORY_TITLE}');

}

?>