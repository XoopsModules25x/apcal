<?php

if (defined('FOR_XOOPS_LANG_CHECKER') || !defined('_APCAL_CNST_LOADED')) {
    define('_APCAL_CNST_LOADED', 1);

    // the language file for jscalendar "DHTML Date/Time Selector"
    define('_APCAL_JS_CALENDAR', 'calendar-en.js');

    // format for jscalendar. see common/jscalendar/doc/html/reference.html
    define('_APCAL_JSFMT_YMDN', '%e %B %Y %A');

    // format for date()  see http://jp.php.net/date
    define('_APCAL_DTFMT_MINUTE', 'i');

    // definition of orders     Y:year  M:month  W:week  D:day  N:dayname  O:operand
    define('_APCAL_FMT_MD', '%1$s %2$s');
    define('_APCAL_FMT_YMD', '%3$s %2$s %1$s');
    define('_APCAL_FMT_YMDN', '%4$s %2$s %3$s, %1$s');
    define('_APCAL_FMT_YMDO', '%4$s%3$s%2$s%1$s');
    define('_APCAL_FMT_YMW', '%3$s %2$s %1$s');
    define('_APCAL_FMT_YW', 'Week %2$s %1$s');
    define('_APCAL_FMT_DHI', '%1$s %2$s:%3$s');
    define('_APCAL_FMT_HI', '%1$s:%2$s');

    // formats for sprintf()
    define('_APCAL_FMT_YEAR_MONTH', '%2$s %1$s');
    define('_APCAL_FMT_YEAR', 'YEAR %s');
    define('_APCAL_FMT_WEEKNO', 'WEEK %s');

    define('_APCAL_ICON_LIST', 'List View');
    define('_APCAL_ICON_DAILY', 'Daily View');
    define('_APCAL_ICON_WEEKLY', 'Weekly View');
    define('_APCAL_ICON_MONTHLY', 'Monthly View');
    define('_APCAL_ICON_YEARLY', 'Yearly View');

    define('_APCAL_MB_SHOWALLCAT', 'All Categories');

    define('_APCAL_MB_LINKTODAY', 'Today');
    define('_APCAL_MB_NOSUBJECT', '(No Subject)');

    define('_APCAL_MB_PREV_DATE', 'Yesterday');
    define('_APCAL_MB_NEXT_DATE', 'Tomorrow');
    define('_APCAL_MB_PREV_WEEK', 'Last Week');
    define('_APCAL_MB_NEXT_WEEK', 'Next Week');
    define('_APCAL_MB_PREV_MONTH', 'Last Month');
    define('_APCAL_MB_NEXT_MONTH', 'Next Month');
    define('_APCAL_MB_PREV_YEAR', 'Last Year');
    define('_APCAL_MB_NEXT_YEAR', 'Next Year');

    define('_APCAL_MB_NOEVENT', 'No Events');
    define('_APCAL_MB_ADDEVENT', 'Add an Event');
    define('_APCAL_MB_CONTINUING', '(continuing)');
    define('_APCAL_MB_RESTEVENT_PRE', '');
    define('_APCAL_MB_RESTEVENT_SUF', 'more item(s)');
    define('_APCAL_MB_TIMESEPARATOR', '--');

    define('_APCAL_MB_ALLDAY_EVENT', 'Allday Event');
    define('_APCAL_MB_LONG_EVENT', 'Show as Bar');
    define('_APCAL_MB_LONG_SPECIALDAY', 'Anniversary etc.');

    define('_APCAL_MB_PUBLIC', 'Public');
    define('_APCAL_MB_PRIVATE', 'Private');
    define('_APCAL_MB_PRIVATETARGET', ' among %s');

    define('_APCAL_MB_LINK_TO_RRULE1ST', 'Jump to the 1st Event ');
    define('_APCAL_MB_RRULE1ST', 'This is the 1st Event');

    define('_APCAL_MB_EVENT_NOTREGISTER', 'Not Registered');
    define('_APCAL_MB_EVENT_ADMITTED', 'Admitted');
    define('_APCAL_MB_EVENT_NEEDADMIT', 'Waiting for Admission');

    define('_APCAL_MB_TITLE_EVENTINFO', 'Scheduler');
    define('_APCAL_MB_SUBTITLE_EVENTDETAIL', 'Detail View');
    define('_APCAL_MB_SUBTITLE_EVENTEDIT', 'Editing View');

    define('_APCAL_MB_HOUR_SUF', ':');
    define('_APCAL_MB_MINUTE_SUF', '');

    define('_APCAL_MB_ORDER_ASC', 'Ascending');
    define('_APCAL_MB_ORDER_DESC', 'Descending');
    define('_APCAL_MB_SORTBY', 'Sort by:');
    define('_APCAL_MB_CURSORTEDBY', 'Events currently sorted by:');

    define('_APCAL_MB_LABEL_CHECKEDITEMS', 'Checked events are:');
    define('_APCAL_MB_LABEL_OUTPUTICS', 'to be exported in iCalendar');

    define('_APCAL_MB_ICALSELECTPLATFORM', 'Select platform');

    define('_APCAL_TH_SUMMARY', 'Event title');
    define('_APCAL_TH_TIMEZONE', 'Time Zone');
    define('_APCAL_TH_STARTDATETIME', 'Beginning Date');
    define('_APCAL_TH_ENDDATETIME', 'Ending Date');
    define('_APCAL_TH_ALLDAYOPTIONS', 'Allday Options');
    define('_APCAL_TH_LOCATION', 'Location');
    define('_APCAL_TH_CONTACT', 'Contact');
    define('_APCAL_TH_CATEGORIES', 'Categories');
    define('_APCAL_TH_SUBMITTER', 'Submitter');
    define('_APCAL_TH_CLASS', 'Class');
    define('_APCAL_TH_DESCRIPTION', 'Description');
    define('_APCAL_TH_RRULE', 'Recur Rules');
    define('_APCAL_TH_ADMISSIONSTATUS', 'Status');
    define('_APCAL_TH_LASTMODIFIED', 'Last Modified');

    define('_APCAL_NTC_MONTHLYBYMONTHDAY', '(Input Number)');
    define('_APCAL_NTC_EXTRACTLIMIT', '** Only %s events are extracted if the max.');
    define('_APCAL_NTC_NUMBEROFNEEDADMIT', '(%s items need to be admitted)');

    define('_APCAL_OPT_PRIVATEMYSELF', 'myself only');
    define('_APCAL_OPT_PRIVATEGROUP', 'group %s');
    define('_APCAL_OPT_PRIVATEINVALID', '(invalid group)');

    define('_APCAL_MB_OP_AFTER', 'After');
    define('_APCAL_MB_OP_BEFORE', 'Before');
    define('_APCAL_MB_OP_ON', 'On');
    define('_APCAL_MB_OP_ALL', 'All');

    define('_APCAL_CNFM_SAVEAS_YN', 'Are you OK saving this as another record ?');
    define('_APCAL_CNFM_DELETE_YN', 'Are you OK deleting this record ?');

    define('_APCAL_ERR_INVALID_EVENT_ID', 'Error: EventID not found');
    define('_APCAL_ERR_NOPERM_TO_SHOW', "Error: You don't have a permission to view this");
    define('_APCAL_ERR_NOPERM_TO_OUTPUTICS', "Error: You don't have a permission to output iCalendar");
    define('_APCAL_ERR_LACKINDISPITEM', 'Item %s is blank.<br>Push the Back button of your browser!');

    define('_APCAL_BTN_JUMP', 'Jump');
    define('_APCAL_BTN_NEWINSERTED', 'New Insert');
    define('_APCAL_BTN_SUBMITCHANGES', ' Change it! ');
    define('_APCAL_BTN_SAVEAS', 'Save as');
    define('_APCAL_BTN_DELETE', 'Remove it');
    define('_APCAL_BTN_DELETE_ONE', 'Remove just one');
    define('_APCAL_BTN_EDITEVENT', 'Edit it');
    define('_APCAL_BTN_RESET', 'Reset');
    define('_APCAL_BTN_OUTPUTICS_WIN', 'iCalendar(Win)');
    define('_APCAL_BTN_OUTPUTICS_MAC', 'iCalendar(Mac)');
    define('_APCAL_BTN_PRINT', 'Print');
    define('_APCAL_BTN_IMPORT', 'Import!');
    define('_APCAL_BTN_UPLOAD', 'Upload!');
    define('_APCAL_BTN_EXPORT', 'Export!');
    define('_APCAL_BTN_EXTRACT', 'Extract');
    define('_APCAL_BTN_ADMIT', 'Admit');
    define('_APCAL_BTN_MOVE', 'Move');
    define('_APCAL_BTN_COPY', 'Copy');

    define('_APCAL_RR_EVERYDAY', 'Everyday');
    define('_APCAL_RR_EVERYWEEK', 'Everyweek');
    define('_APCAL_RR_EVERYMONTH', 'Everymonth');
    define('_APCAL_RR_EVERYYEAR', 'Everyyear');
    define('_APCAL_RR_FREQDAILY', 'Daily');
    define('_APCAL_RR_FREQWEEKLY', 'Weekly');
    define('_APCAL_RR_FREQMONTHLY', 'Monthly');
    define('_APCAL_RR_FREQYEARLY', 'Yearly');
    define('_APCAL_RR_FREQDAILY_PRE', 'Every');
    define('_APCAL_RR_FREQWEEKLY_PRE', 'Every');
    define('_APCAL_RR_FREQMONTHLY_PRE', 'Every');
    define('_APCAL_RR_FREQYEARLY_PRE', 'Every');
    define('_APCAL_RR_FREQDAILY_SUF', 'day(s)');
    define('_APCAL_RR_FREQWEEKLY_SUF', 'week(s)');
    define('_APCAL_RR_FREQMONTHLY_SUF', 'Month(s)');
    define('_APCAL_RR_FREQYEARLY_SUF', 'Year(s)');
    define('_APCAL_RR_PERDAY', 'every %s days');
    define('_APCAL_RR_PERWEEK', 'every %s weeks');
    define('_APCAL_RR_PERMONTH', 'every %s months');
    define('_APCAL_RR_PERYEAR', 'every %s years');
    define('_APCAL_RR_COUNT', '<br>%s times');
    define('_APCAL_RR_UNTIL', '<br>until %s');
    define('_APCAL_RR_R_NORRULE', 'Recur No');
    define('_APCAL_RR_R_YESRRULE', 'Recur Yes');
    define('_APCAL_RR_OR', 'or');
    define('_APCAL_RR_S_NOTSELECTED', '-not selected-');
    define('_APCAL_RR_S_SAMEASBDATE', 'Same as beginning date');
    define('_APCAL_RR_R_NOCOUNTUNTIL', 'No ending conditions');
    define('_APCAL_RR_R_USECOUNT_PRE', 'repeats');
    define('_APCAL_RR_R_USECOUNT_SUF', 'times');
    define('_APCAL_RR_R_USEUNTIL', 'until');

    // Added by goffy for online registration handling
    define('_APCAL_RO_CANCEL', 'Action canceled');
    define('_APCAL_RO_RADIO_YES', 'Yes');
    define('_APCAL_RO_RADIO_NO', 'No');

    define('_APCAL_RO_ONLINE_POSS', 'For these events online registration is possible. ');
    define('_APCAL_RO_ONLINE_POSS_2', 'For this event online registration is possible. ');
    define('_APCAL_RO_ONLY_MEMBERS', 'Online registration is only possible for registered users. ');
    define('_APCAL_RO_ONLINE_NO', 'Online registration is not activated for this event');
    define('_APCAL_RO_ONLINE_YES', 'Online registration is activated for this event');
    define('_APCAL_RO_ONLINE_ACTIVATE', 'Activate online registration for this event');
    define('_APCAL_RO_ONLINE_DEACTIVATE', 'Do not use online registration for this event');
    define('_APCAL_RO_ONLINE', 'Registrations');
    define('_APCAL_RO_BTN_ADD', 'register');
    define('_APCAL_RO_BTN_ADDMORE', 'Edit or add further registrations');
    define('_APCAL_RO_BTN_REMOVE', 'de-register');
    define('_APCAL_RO_ENABLE_ONLINE', 'Online registration');
    define('_APCAL_RO_NOMEMBERS', 'There are no registrations for this event');
    define('_APCAL_RO_BTN_LISTMEMBERS', 'List of participants');
    define('_APCAL_RO_SUCCESS_ADD', 'The registration for this event was successful');
    define('_APCAL_RO_SUCCESS_REMOVE', 'The de-registration for this event was successful');
    define('_APCAL_RO_UNAME', 'Registrating person');
    define('_APCAL_RO_FIRSTNAME', 'first name');
    define('_APCAL_RO_LASTNAME', 'last name');
    define('_APCAL_RO_EMAIL', 'E-mail-address');
    define('_APCAL_RO_EXTRAINFO1', 'Telephone'); //use it as you want; if you keep it blank, it will be invisible; if you change later, it has no effect on the data himself
    define('_APCAL_RO_EXTRAINFO2', 'Remarks'); //use it as you want, if you keep it blank, it will be invisible
    define('_APCAL_RO_EXTRAINFO3', ''); //use it as you want, if you keep it blank, it will be invisible
    define('_APCAL_RO_EXTRAINFO4', ''); //use it as you want, if you keep it blank, it will be invisible
    define('_APCAL_RO_EXTRAINFO5', ''); //use it as you want, if you keep it blank, it will be invisible

    define('_APCAL_RO_BTN_CONF_ADD', 'Confirm registration');
    define('_APCAL_RO_BTN_CONF_ADD_MORE', 'Confirm registration and register more people');
    define('_APCAL_RO_BTN_CONF_REMOVE', 'Confirm de-registration');
    define('_APCAL_RO_BTN_CANCEL', 'Cancel');
    define('_APCAL_RO_EVENT', 'Event');
    define('_APCAL_RO_TITLE1', 'Registration for an event');
    define('_APCAL_RO_OBLIGATORY', 'This fields are obligatory!');

    define('_APCAL_RO_ERROR_REMOVE', 'Unexepted error when deleting registration');
    define('_APCAL_RO_ERROR_ADD', 'Unexepted error when creating registration');
    define('_APCAL_RO_BTN_BACK', 'Go back');
    define('_APCAL_RO_BACK', 'You will be redirected to last page');
    define('_APCAL_RO_ONLINE2', 'Online registrations');
    define('_APCAL_RO_ACTION', 'Action');

    define('_APCAL_RO_TITLE2', 'Activate online registration for this event');
    define('_APCAL_RO_BTN_RO_EDIT', 'Edit online registration');
    define('_APCAL_RO_BTN_RO_DEACTIVATE', 'Delete online registration');
    define('_APCAL_RO_BTN_RO_ACTIVATE', 'Activate');
    define('_APCAL_RO_QUANTITY', 'Maximum number of participants<br>(0 means: no limit)');
    define('_APCAL_RO_QUANTITY2', 'Maximum number of participants');
    define('_APCAL_RO_DATELIMIT', 'Deadline for online registration');
    define('_APCAL_RO_EMAIL_NOTIFY', 'E-mail-address, which should be notified in case of (de-)registrations (keep blank, if you want no notifications)');
    define('_APCAL_RO_BTN_CONF_ACTIVATE', 'Confirm');
    define('_APCAL_RO_ERROR_RO_ACTIVATE', 'Unexpected error while activating online registration');
    define('_APCAL_RO_SUCCESS_RO_ACTIVATE', 'Activating/editing online registration was successful');
    define('_APCAL_RO_ERROR_RO_DEACTIVATE', 'Unexpected error while deactivating online registration');
    define('_APCAL_RO_SUCCESS_RO_DEACTIVATE', 'Deactivating online registration was successful');
    define('_APCAL_RO_ERROR_FULL', 'Sorry, but there are no more places available for this event');
    define('_APCAL_RO_ERROR_TIMEOUT', 'Sorry, but you have exceeded the deadline of this event');
    define('_APCAL_RO_ERROR_OBLIGATORY', 'Please fill in obligatory field \"%f\"');
    define('_APCAL_RO_BTN_CONF_SAVE', 'Save');
    define('_APCAL_RO_BTN_CONF_EDIT', 'Save changes');

    define('_APCAL_RO_MAIL_SUBJ_ADD', 'Info registration');
    define('_APCAL_RO_MAIL_SUBJ_REMOVE', 'Info de-registration');
    define('_APCAL_RO_MAIL_SUBJ_TEXT', 'Information about the event');
    define('_APCAL_RO_DATE', 'Date');
    define('_APCAL_RO_LOCATION', 'Location');
    define('_APCAL_RO_LINK', 'Link to event');
    define('_APCAL_RO_TITLE3', 'List of existing registrations');
    define('_APCAL_RO_TITLE4', 'Send an email to all participant, which have entered an email-address');
    define('_APCAL_RO_MAIL_SENDER', 'Sender');
    define('_APCAL_RO_MAIL_SUBJ', 'Subject');
    define('_APCAL_RO_MAIL_BODY1', 'Mailtext');
    define('_APCAL_RO_MAIL_BODY2', 'The expressions in curly brackets will be replaced (z.B. {NAME} will be replace by first name and last name)');
    define('_APCAL_RO_BTN_SEND', 'Send');
    define('_APCAL_RO_MAILSENT', ' message(s) sent');

    define('_APCAL_RO_TITLE5', 'Change registrations');
    define('_APCAL_RO_BTN_EDIT', 'Edit');
    define('_APCAL_RO_SUCCESS_EDIT', 'Change of registration data successful');
    define('_APCAL_RO_ERROR_EDIT', 'Unexpected error while changing registration');
    define('_APCAL_RO_SEND_CONF1', 'E-Mail confirmation');
    define('_APCAL_RO_SEND_CONF2', 'send to');
    define('_APCAL_RO_SEND_CONF3', 'Send E-Mail-confirmation: ');

    define('_APCAL_RO_REDIRECT', 'You will be redirected to activation of online registration');
    define('_APCAL_RO_SUCCESS_NEW_EVENT', 'Event successfully created');
    define('_APCAL_RO_SUCCESS_DELETE_EVENT', 'Event successfully deleted');
    define('_APCAL_RO_SUCCESS_UPDATE_EVENT', 'Event successfully changed');
    define('_APCAL_RO_SUCCESS_COPY_EVENT', 'Event successfully copied');
}
