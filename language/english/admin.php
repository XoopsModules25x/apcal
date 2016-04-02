<?php

if (defined('FOR_XOOPS_LANG_CHECKER') || !defined('_AM_APCAL_LOADED')) {
    define('_AM_APCAL_LOADED', 1);
    define('_AM_APCAL_COPYRIGHT', '<a href="http://xoops.antiquespromotion.ca" title="Calendar for Xoops" target="_blank">APCal</a> by <a href="http://www.antiquespromotion.ca" title="Antiques Promotion Canada" target="_blank">AP</a>');

    // Index
    define('_AM_APCAL_TIMEZONE', 'Timezones');
    define('_AM_APCAL_MODULEADMIN_CONFIG', 'Configurations');
    define('_AM_APCAL_NBWAITINGEVENTS', 'There are %s events waiting for approval.');
    define('_AM_APCAL_NBEVENTS', 'There are %s upcomming events.');
    define('_AM_APCAL_NBCATS', 'There are %s categories.');
    define('_AM_APCAL_MODULEADMIN_CONFIG_PHP', 'You must have at least php version %s (your current version is %s)');
    define('_AM_APCAL_MODULEADMIN_CONFIG_XOOPS', 'You must have at least xoops version %s (your current version is %s)');
    define('_AM_APCAL_PREFS', 'Preferences');
    define('_AM_APCAL_UPDATE', 'Update');
    define('_AM_APCAL_BLOCKS', 'Blocks');
    define('_AM_APCAL_GOTOMODULE', 'Go to module');

    // titles
    define('_AM_APCAL_ADMISSION', 'Admitting Events');
    define('_AM_APCAL_MENU_EVENTS', 'Events Manager');
    define('_AM_APCAL_MENU_CATEGORIES', 'Categories Manager');
    define('_AM_APCAL_MENU_CAT2GROUP', "Category's Permission");
    define('_AM_APCAL_ICALENDAR_IMPORT', 'Importing iCalendar');
    define('_AM_APCAL_GROUPPERM', 'Global Permissions');
    define('_AM_APCAL_TABLEMAINTAIN', 'Table Maintenance (Upgrade)');
    define('_AM_APCAL_MYBLOCKSADMIN', "APCal's Block&Group admin");

    // forms
    define('_AM_APCAL_BUTTON_EXTRACT', 'Extract');
    define('_AM_APCAL_BUTTON_ADMIT', 'Admit');
    define('_AM_APCAL_BUTTON_MOVE', 'Move');
    define('_AM_APCAL_BUTTON_COPY', 'Copy');
    define('_AM_APCAL_CONFIRM_DELETE', 'Delete event(s) OK?');
    define('_AM_APCAL_CONFIRM_MOVE', 'Remove a link to the old category and Add a link to the specified category OK?');
    define('_AM_APCAL_CONFIRM_COPY', 'Add a link to specified category OK?');
    define('_AM_APCAL_OPT_PAST', 'Past');
    define('_AM_APCAL_OPT_FUTURE', 'Future');
    define('_AM_APCAL_OPT_PASTANDFUTURE', 'Past & Future');

    // format
    define('_AM_APCAL_DTFMT_LIST_ALLDAY', 'y-m-d');
    define('_AM_APCAL_DTFMT_LIST_NORMAL', 'y-m-d<\b\r />H:i');

    // timezones
    define('_AM_APCAL_TZOPT_SERVER', 'As server timezone');
    define('_AM_APCAL_TZOPT_GMT', 'As GMT');
    define('_AM_APCAL_TZOPT_USER', "As user's timezone");

    // admission
    define('_AM_APCAL_LABEL_ADMIT', 'Checked events are: to be admitted');
    define('_AM_APCAL_MES_ADMITTED', 'Event(s) has been admitted');
    define('_AM_APCAL_ADMIT_TH0', 'User');
    define('_AM_APCAL_ADMIT_TH1', 'Start datetime');
    define('_AM_APCAL_ADMIT_TH2', 'Finish datetime');
    define('_AM_APCAL_ADMIT_TH3', 'Title');
    define('_AM_APCAL_ADMIT_TH4', 'Rrule');

    // events manager & importing iCalendar
    define('_AM_APCAL_LABEL_IMPORTFROMWEB', "Import iCalendar data from web (Input URI started from 'http://' or 'webcal://')");
    define('_AM_APCAL_LABEL_UPLOADFROMFILE', 'Upload iCalendar data (Select a file from your local machine)');
    define('_AM_APCAL_LABEL_IO_CHECKEDITEMS', 'Checked events are:');
    define('_AM_APCAL_LABEL_IO_OUTPUT', 'to be exported in iCalendar');
    define('_AM_APCAL_LABEL_IO_DELETE', 'to be deleted');
    define('_AM_APCAL_MES_EVENTLINKTOCAT', 'event(s) has been linked to this category');
    define('_AM_APCAL_MES_EVENTUNLINKED', 'event(s) link has been removed to the old category');
    define('_AM_APCAL_FMT_IMPORTED', "event(s) has been imported from '%s'");
    define('_AM_APCAL_MES_DELETED', 'event(s) has been deleted');
    define('_AM_APCAL_IO_TH0', 'User');
    define('_AM_APCAL_IO_TH1', 'Start datetime');
    define('_AM_APCAL_IO_TH2', 'Finish datetime');
    define('_AM_APCAL_IO_TH3', 'Title');
    define('_AM_APCAL_IO_TH4', 'Rrule');
    define('_AM_APCAL_IO_TH5', 'Admission');

    // Group's Permissions
    define('_AM_APCAL_GPERM_G_INSERTABLE', 'Can add');
    define('_AM_APCAL_GPERM_G_SUPERINSERT', 'Super add');
    define('_AM_APCAL_GPERM_G_EDITABLE', 'Can edit');
    define('_AM_APCAL_GPERM_G_SUPEREDIT', 'Super edit');
    define('_AM_APCAL_GPERM_G_DELETABLE', 'Can delete');
    define('_AM_APCAL_GPERM_G_SUPERDELETE', 'Super delete');
    define('_AM_APCAL_GPERM_G_TOUCHOTHERS', 'Can touch others');
    define('_AM_APCAL_CAT2GROUPDESC', 'Check categories which you allow to access');
    define('_AM_APCAL_GROUPPERMDESC', "Select permissions that each group is allowed to do<br />If you need this feature, set 'Authorities of users' to Specified in Group's permissions first.<br />The settings of two groups of Administrator and Guest will be ignored.");

    // Table Maintenance
    define('_AM_APCAL_MB_SUCCESSUPDATETABLE', 'Updating table(s) has succeeded');
    define('_AM_APCAL_MB_FAILUPDATETABLE', 'Updating table(s) has failed');
    define('_AM_APCAL_NOTICE_NOERRORS', 'There is no error with tables or records.');
    define('_AM_APCAL_ALRT_CATTABLENOTEXIST', "The categories table does not exist.<br />\nDo you wish to create the table?");
    define('_AM_APCAL_ALRT_OLDTABLE', "The structure of events table is old.<br />\nDo you wish to upgrade the table?");
    define('_AM_APCAL_ALRT_TOOOLDTABLE', "Table error occured.<br />\nPerhaps you used APCal 0.3x or earlier.<br />\nFirst, update into 0.4x or 0.5x.");
    define('_AM_APCAL_FMT_SERVER_TZ_ALL', 'Timezone of the server (winter): %+2.1f<br />Timezone of the server (summer): %+2.1f<br />Zonename of the server: %s<br />The value of XOOPS config: %+2.1f<br />The value of APCal using: %+2.1f<br />');
    define('_AM_APCAL_FMT_WRONGSTZ', 'Ther is %s event(s) saved with a bad timezone.<br />Would you like to repair them?');
    define('_AM_APCAL_TH_SERVER_TZ_COUNT', 'Events');
    define('_AM_APCAL_TH_SERVER_TZ_VALUE', 'Timezone');
    define('_AM_APCAL_TH_SERVER_TZ_VALUE_TO', 'Changes (-14.0��14.0)');
    define('_AM_APCAL_JSALRT_SERVER_TZ', "Don't forget backing-up events table before this operation");
    define('_AM_APCAL_NOTICE_SERVER_TZ', "If your server set the timezone area with summer time (=Day Light Saving) and some events were registerd in APCal 0.6x or 0.7x, dont't push this button.<br />eg) It is natural to display both -5.0 and -4.0 in EDT");
    define('_AM_APCAL_MB_SUCCESSTZUPDATE', 'Events are modified with the timezone(s).');

    // Categories
    define('_AM_APCAL_CAT_TH_TITLE', 'Title');
    define('_AM_APCAL_CAT_TH_DESC', 'Description');
    define('_AM_APCAL_CAT_TH_PARENT', 'Parent Category');
    define('_AM_APCAL_CAT_TH_OPTIONS', 'Options');
    define('_AM_APCAL_CAT_TH_LASTMODIFY', 'Last Modified');
    define('_AM_APCAL_CAT_TH_OPERATION', 'Operation');
    define('_AM_APCAL_CAT_TH_ENABLED', 'Enable');
    define('_AM_APCAL_CAT_TH_WEIGHT', 'Weight');
    define('_AM_APCAL_CAT_TH_SUBMENU', 'register in SubMenu');
    define('_AM_APCAL_BTN_UPDATE', 'UPDATE');
    define('_AM_APCAL_MENU_CAT_EDIT', 'Editing a Category');
    define('_AM_APCAL_MENU_CAT_NEW', 'Create a new Category');
    define('_AM_APCAL_MB_MAKESUBCAT', 'SubCategory');
    define('_AM_APCAL_MB_MAKETOPCAT', 'Create a category in a top level');
    define('_AM_APCAL_MB_CAT_INSERTED', 'New Category created');
    define('_AM_APCAL_MB_CAT_UPDATED', 'Category updated');
    define('_AM_APCAL_FMT_CAT_DELETED', '%s Categories deleted');
    define('_AM_APCAL_FMT_CAT_BATCHUPDATED', '%s Categories updated');
    define('_AM_APCAL_FMT_CATDELCONFIRM', 'Do you want to delete category %s ?');
    define('_AM_APCAL_CANBEMAIN', 'Use as a main category');
    define('_AM_APCAL_COLOR', 'Color');

    // Plugins
    define('_AM_APCAL_PI_UPDATED', 'Plugins are updated');
    define('_AM_APCAL_PI_TH_TYPE', 'Type');
    define('_AM_APCAL_PI_TH_OPTIONS', 'Options (usually blank)');
    define('_AM_APCAL_PI_TH_TITLE', 'Title');
    define('_AM_APCAL_PI_TH_DIRNAME', 'Module\'s dirname');
    define('_AM_APCAL_PI_TH_FILE', 'Plugin file');
    define('_AM_APCAL_PI_TH_DOTGIF', 'Dot GIF');
    define('_AM_APCAL_PI_TH_OPERATION', 'Operation');
    define('_AM_APCAL_PI_ENABLED', 'Enabled');
    define('_AM_APCAL_PI_DELETE', 'Delete');
    define('_AM_APCAL_PI_NEW', 'New');
    define('_AM_APCAL_PI_VIEWYEARLY', 'Yearly View');
    define('_AM_APCAL_PI_VIEWMONTHLY', 'Monthly View');
    define('_AM_APCAL_PI_VIEWWEEKLY', 'Weekly View');
    define('_AM_APCAL_PI_VIEWDAILY', 'Daily View');

    // Blocks & Groups Admin
    define('_AM_APCAL_TITLE', 'Title');
    define('_AM_APCAL_SIDE', 'Location');
    define('_AM_APCAL_WEIGHT', 'Weight');
    define('_AM_APCAL_VISIBLEIN', 'Visible in');
    define('_AM_APCAL_BCACHETIME', 'Cache time');
    define('_AM_APCAL_ACTION', 'Action');
    define('_AM_APCAL_ACTIVERIGHTS', 'Module administration rights');
    define('_AM_APCAL_ACCESSRIGHTS', 'Module access rights');
    define('_AM_APCAL_BADMIN', 'Blocks administration');
    //define('_AM_APCAL_ADGS', 'Groups');

    define('_AM_APCALAM_APCALDBUPDATED', 'Database Updated');
}
