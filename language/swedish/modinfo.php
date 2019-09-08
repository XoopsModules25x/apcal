<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'APCAL_MI_LOADED' ) ) {






// Appended by Xoops Language Checker -GIJOE- in 2006-02-15 05:31:21
define('_MI_APCAL_ADMENU_MYTPLSADMIN','Templates');

// Appended by Xoops Language Checker -GIJOE- in 2005-05-06 18:04:00
define('_MI_TIMEZONE_USING','Timezone of the server');
define('_MI_OPT_TZ_USEXOOPS','value of XOOPS config');
define('_MI_OPT_TZ_USEWINTER','value told from the server as winter time (recommended)');
define('_MI_OPT_TZ_USESUMMER','value told from the server as summer time');

// Appended by Xoops Language Checker -GIJOE- in 2005-05-03 05:31:13
define('_MI_USE24HOUR','24hours system (No means 12hours system)');
define('_MI_APCAL_ADMENU_PLUGINS','Plugins Manager');

// Appended by Xoops Language Checker -GIJOE- in 2005-04-22 12:02:02
define('_MI_APCAL_BNAME_MINICALEX','MiniCalendarEx');
define('_MI_APCAL_BNAME_MINICALEX_DESC','Utbyggbar Minikalender med plugin system');

// Appended by Xoops Language Checker -GIJOE- in 2005-01-08 04:36:50
define('_MI_APCAL_DEFAULTLOCALE','');
define('_MI_APCAL_LOCALE','Locale (kontrollera filer i locales/*.php)');

define( 'APCAL_MI_LOADED' , 1 ) ;

// Module Info

// The name of this module

// Appended by Xoops Language Checker -GIJOE- in 2004-06-22 18:39:03
define('_MI_APCAL_ADMENU_MYBLOCKSADMIN','Block&Grupp Admin');

define("_MI_APCAL_NAME","APCal");

// A brief description of this module
define("_MI_APCAL_DESC","Kalender modul med schema");

// Names of blocks for this module (Not all module has blocks)
define("_MI_APCAL_BNAME_MINICAL","Minikalender");
define("_MI_APCAL_BNAME_MINICAL_DESC","Visa Minikalender block");
define("_MI_APCAL_BNAME_MONTHCAL","MéÏadskalender");
define("_MI_APCAL_BNAME_MONTHCAL_DESC","Visa MéÏadskalender i full storlek");
define("_MI_APCAL_BNAME_TODAYS","Dagens hçÏdelser");
define("_MI_APCAL_BNAME_TODAYS_DESC","Visa Dagens hçÏdelser");
define("_MI_APCAL_BNAME_THEDAYS","Denna dagens %s");
define("_MI_APCAL_BNAME_THEDAYS_DESC","Visa hçÏdelser f‹Ó markerad dag");
define("_MI_APCAL_BNAME_COMING","Kommande hçÏdelser");
define("_MI_APCAL_BNAME_COMING_DESC","Visa kommande hçÏdelser");
define("_MI_APCAL_BNAME_AFTER","HçÏdelser efter %s");
define("_MI_APCAL_BNAME_AFTER_DESC","Visa hçÏdelser efter markerad dag");

// Names of submenu
// define("_MI_APCAL_SMNAME1","");

//define("_MI_APCAL_ADMENU1","");

// Title of config items
define("_MI_USERS_AUTHORITY", "Beh‹Óigheter f‹Ó anvçÏdare");
define("_MI_GUESTS_AUTHORITY", "Beh‹Óigheter f‹Ó gçÔter");
define("_MI_MINICAL_TARGET", "Sida som visas i center blocket om man klickar på Minikalendern");
define("_MI_COMING_NUMROWS", "Antalet visade hçÏdelser i blocket f‹Ó kommande hçÏdelser");
define("_MI_SKINFOLDER", "Namnet på den katalog som innehéÍler 'skin' filerna");
define("_MI_SUNDAYCOLOR", "FçÓg på texten f‹Ó S‹Ïdag");
define("_MI_WEEKDAYCOLOR", "FçÓg på texten f‹Ó Veckodagar");
define("_MI_SATURDAYCOLOR", "FçÓg på texten f‹Ó L‹Ódag");
define("_MI_HOLIDAYCOLOR", "FçÓg på texten f‹Ó Helgdag");
define("_MI_TARGETDAYCOLOR", "FçÓg på texten f‹Ó Markerad dag");
define("_MI_SUNDAYBGCOLOR", "BakgrundsfçÓg f‹Ó S‹Ïdagar");
define("_MI_WEEKDAYBGCOLOR", "BakgrundsfçÓg f‹Ó Veckodagar");
define("_MI_SATURDAYBGCOLOR", "BakgrundsfçÓg f‹Ó L‹Ódagar");
define("_MI_HOLIDAYBGCOLOR", "BakgrundsfçÓg f‹Ó Helgdagar");
define("_MI_TARGETDAYBGCOLOR", "BakgrundsfçÓg på markerad dag");
define("_MI_CALHEADCOLOR", "FçÓg på texten i 'headern' på kalendern");
define("_MI_CALHEADBGCOLOR", "BakgrundsfçÓg i 'headern' på kalendern");
define("_MI_CALFRAMECSS", "Stil på ramen runt kalendern");
define("_MI_CANOUTPUTICS", "TilléÕelse att mata ut ics filer?");
define("_MI_MAXRRULEEXTRACT", "ŽÖvre grçÏs på antalet hçÏdelser som féÓ extraheras med regel.(ANTAL)");
define("_MI_WEEKSTARTFROM", "F‹Ósta dagen i veckan");
define("_MI_DAYSTARTFROM", "GrçÏs f‹Ó att separera dagar");
define("_MI_NAMEORUNAME" , "Vilket namn på anvçÏdaren skall visas" ) ;
define("_MI_DESCNAMEORUNAME" , "VçÍj vilket 'namn' som visas" ) ;

// Description of each config items
define("_MI_EDITBYGUESTDSC", "TilléÕelse f‹Ó gçÔter att lçÈga till hçÏdelser");

// Options of each config items
define("_MI_OPT_AUTH_NONE", "kan inte lçÈga till");
define("_MI_OPT_AUTH_WAIT", "kan lçÈga till men krç×er godkçÏnande");
define("_MI_OPT_AUTH_POST", "kan lçÈga till utan godkçÏnande");
define("_MI_OPT_AUTH_BYGROUP", "Specificerad i GrupprçÕtigheterna");
define("_MI_OPT_MINI_PHPSELF", "nuvarande sida");
define("_MI_OPT_MINI_MONTHLY", "méÏadskalender");
define("_MI_OPT_MINI_WEEKLY", "veckokalender");
define("_MI_OPT_MINI_DAILY", "dagskalender");
define("_MI_OPT_CANOUTPUTICS", "kan mata ut");
define("_MI_OPT_CANNOTOUTPUTICS", "kan inte mata ut");
define("_MI_OPT_STARTFROMSUN", "S‹Ïdag");
define("_MI_OPT_STARTFROMMON", "MéÏdag");
define("_MI_OPT_USENAME" , "AnvçÏdarnamn" ) ;
define("_MI_OPT_USEUNAME" , "Login Namn" ) ;

// Admin Menus
define("_MI_APCAL_ADMENU0","GodkçÏna hçÏdelser");
define("_MI_APCAL_ADMENU1","iCalendar I/O");
define("_MI_APCAL_ADMENU2","Grupp rçÕtigheter");


// Text for notifications
define('_MI_APCAL_GLOBAL_NOTIFY', 'Globala');
define('_MI_APCAL_GLOBAL_NOTIFYDSC', 'Globala APCal underrçÕtelse instçÍlningar.');
define('_MI_APCAL_CATEGORY_NOTIFY', 'Kategori');
define('_MI_APCAL_CATEGORY_NOTIFYDSC', 'UnderrçÕtelse instçÍlningar som gçÍler f‹Ó aktuell kategori.');
define('_MI_APCAL_EVENT_NOTIFY', 'HçÏdelse');
define('_MI_APCAL_EVENT_NOTIFYDSC', 'UnderrçÕtelse instçÍlningar som gçÍler f‹Ó aktuell hçÏdelse.');

define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFY', 'Ny hçÏdelse');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYCAP', 'UnderrçÕta mig nçÓ en ny hçÏdelse har skapats.');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYDSC', 'Mottag underrçÕtelse nçÓ en ny hçÏdelse har skapats.');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatiska underrçÕtelser : Ny hçÏdelse');



// Appended by Xoops Language Checker -GIJOE- in 2004-01-14 18:31:01
define('_MI_APCAL_BNAME_NEW','Nyligen publicerade hçÏdelser');
define('_MI_APCAL_BNAME_NEW_DESC','Visa hçÏdelser sorterade med nyaste ‹×erst');
define('_MI_APCAL_SM_SUBMIT','LçÈg till');
define('_MI_DEFAULT_VIEW','Default Vy i center');
define('_MI_WEEKNUMBERING','Numrerings regel f‹Ó veckor');
define('_MI_OPT_MINI_LIST','HçÏdelse lista');
define('_MI_OPT_WEEKNOEACHMONTH','f‹Ó varje méÏad');
define('_MI_OPT_WEEKNOWHOLEYEAR','f‹Ó hela éÓet');
define('_MI_APCAL_ADMENU_CAT','Kategori Administration');
define('_MI_APCAL_ADMENU_CAT2GROUP','RçÕtigheter f‹Ó Kategorier');
define('_MI_APCAL_ADMENU_TM','Tabell UnderhéÍl');
define('_MI_APCAL_ADMENU_ICAL','Importera iCalendar');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFY','Ny hçÏdelse i denna kategori');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYCAP','Meddela mig nçÓ en ny hçÏdelse çÓ skapad i denna kategori.');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYDSC','Mottag underrçÕtelse nçÓ en ny hçÏdelse çÓ skapad i denna kategori.');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYSBJ','[{X_SITENAME}] {X_MODULE} auto-notify : Ny hçÏdelse');

}

?>
