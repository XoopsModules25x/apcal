<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'APCAL_MI_LOADED' ) ) {


// Appended by Xoops Language Checker -GIJOE- in 2006-02-15 05:31:20
define('_MI_APCAL_ADMENU_MYTPLSADMIN','Templates');

define( 'APCAL_MI_LOADED' , 1 ) ;

// Module Info

// The name of this module
define("_MI_APCAL_NAME","APCal");

// A brief description of this module
define("_MI_APCAL_DESC","������ ���������");

// Default Locale
define("_MI_APCAL_DEFAULTLOCALE","russia");

// Names of blocks for this module (Not all module has blocks)
define("_MI_APCAL_BNAME_MINICAL","�������������");
define("_MI_APCAL_BNAME_MINICAL_DESC","���������� ���� �������������");
define("_MI_APCAL_BNAME_MINICALEX","�������������");
define("_MI_APCAL_BNAME_MINICALEX_DESC","���� ������������� � �������� ��������");
define("_MI_APCAL_BNAME_MONTHCAL","���������");
define("_MI_APCAL_BNAME_MONTHCAL_DESC","���������� �������������� �������� ���������");
define("_MI_APCAL_BNAME_TODAYS","����������� �������");
define("_MI_APCAL_BNAME_TODAYS_DESC","���������� ����������� �������");
define("_MI_APCAL_BNAME_THEDAYS","������� �� %s");
define("_MI_APCAL_BNAME_THEDAYS_DESC","���������� ������� ���������� ���");
define("_MI_APCAL_BNAME_COMING","��������� �������");
define("_MI_APCAL_BNAME_COMING_DESC","���������� ����������� �������");
define("_MI_APCAL_BNAME_AFTER","������� ����� %s");
define("_MI_APCAL_BNAME_AFTER_DESC","���������� ������� ����� ���������� ���");
define("_MI_APCAL_BNAME_NEW","����� �������");
define("_MI_APCAL_BNAME_NEW_DESC","���������� ������� � ������� �������� (����� ������)");

// Names of submenu
define("_MI_APCAL_SM_SUBMIT","��������");

//define("_MI_APCAL_ADMENU1","");

// Title of config items
define("_MI_USERS_AUTHORITY", "����� ������������");
define("_MI_GUESTS_AUTHORITY", "����� �����");
define("_MI_DEFAULT_VIEW", "��� �� ��������� � ������");
define("_MI_MINICAL_TARGET", "��� �� ��������� �� ������ �� �������������");
define("_MI_COMING_NUMROWS", "���������� ������� � ����� ��������� �������");
define("_MI_SKINFOLDER", "���� (��� ���������� � images)");
define("_MI_APCAL_LOCALE", "������ (��������� ����� � locales/*.php)");
define("_MI_SUNDAYCOLOR", "���� �����������");
define("_MI_WEEKDAYCOLOR", "���� ��� ������");
define("_MI_SATURDAYCOLOR", "���� �������");
define("_MI_HOLIDAYCOLOR", "���� ���������");
define("_MI_TARGETDAYCOLOR", "���� ���������� ���");
define("_MI_SUNDAYBGCOLOR", "��� �����������");
define("_MI_WEEKDAYBGCOLOR", "��� ��� ������");
define("_MI_SATURDAYBGCOLOR", "��� �������");
define("_MI_HOLIDAYBGCOLOR", "��� ���������");
define("_MI_TARGETDAYBGCOLOR", "��� ���������� ���");
define("_MI_CALHEADCOLOR", "���� ��������� ���������");
define("_MI_CALHEADBGCOLOR", "��� ��������� ���������");
define("_MI_CALFRAMECSS", "����� ����� ���������");
define("_MI_CANOUTPUTICS", "������� � ics-�����");
define("_MI_MAXRRULEEXTRACT", "������������ ���-�� �������, ����������� �� ������� �������");
define("_MI_WEEKSTARTFROM", "���� ������ ������");
define("_MI_WEEKNUMBERING", "������� ��������� ������");
define("_MI_DAYSTARTFROM", "������� ��� ���������� ����");
define("_MI_TIMEZONE_USING", "������� ���� �������");
define("_MI_USE24HOUR", "24 ������� ������� (��� - 12 ������� �������)");
define("_MI_NAMEORUNAME" , "���������� ���" ) ;
define("_MI_DESCNAMEORUNAME" , "��������, ����� ��� ����������" ) ;

// Description of each config items
define("_MI_EDITBYGUESTDSC", "��������� ��������� ������� ������");

// Options of each config items
define("_MI_OPT_AUTH_NONE", "�� ����� ��������� �������");
define("_MI_OPT_AUTH_WAIT", "����� ��������� �������, ��������� �������������");
define("_MI_OPT_AUTH_POST", "����� ��������� �������, �������������� �������������");
define("_MI_OPT_AUTH_BYGROUP", "��������� � ������ ��� �����");
define("_MI_OPT_MINI_PHPSELF", "������� ��������");
define("_MI_OPT_MINI_MONTHLY", "��������� �� �������");
define("_MI_OPT_MINI_WEEKLY", "��������� �� �������");
define("_MI_OPT_MINI_DAILY", "��������� �� ����");
define("_MI_OPT_MINI_LIST", "������ �������");
define("_MI_OPT_CANOUTPUTICS", "�� (����� ��������������)");
define("_MI_OPT_CANNOTOUTPUTICS", "��� (������ ��������������)");
define("_MI_OPT_STARTFROMSUN", "�����������");
define("_MI_OPT_STARTFROMMON", "�����������");
define("_MI_OPT_WEEKNOEACHMONTH", "������ ������");
define("_MI_OPT_WEEKNOWHOLEYEAR", "������ ����");
define("_MI_OPT_USENAME" , "��������� ���" ) ;
define("_MI_OPT_USEUNAME" , "��� ������������" ) ;
define("_MI_OPT_TZ_USEXOOPS" , "�� ������������ XOOPS" ) ;
define("_MI_OPT_TZ_USEWINTER" , "������ �����, ���������� �������� (�������������)" ) ;
define("_MI_OPT_TZ_USESUMMER" , "������ �����, ���������� ��������" ) ;

// Admin Menus
define("_MI_APCAL_ADMENU0","������������� �������");
define("_MI_APCAL_ADMENU1","�������� �������");
define("_MI_APCAL_ADMENU_CAT","���������");
define("_MI_APCAL_ADMENU_CAT2GROUP","����� ���������");
define("_MI_APCAL_ADMENU2","����� �����");
define("_MI_APCAL_ADMENU_TM","��������� ������");
define("_MI_APCAL_ADMENU_PLUGINS","�������");
define("_MI_APCAL_ADMENU_ICAL","������");
define("_MI_APCAL_ADMENU_MYBLOCKSADMIN","�����");

// Text for notifications
define('_MI_APCAL_GLOBAL_NOTIFY', '����������');
define('_MI_APCAL_GLOBAL_NOTIFYDSC', '���������� ��������� ���������� APCal.');
define('_MI_APCAL_CATEGORY_NOTIFY', '���������');
define('_MI_APCAL_CATEGORY_NOTIFYDSC', '��������� ���������� ��� ������� ���������.');
define('_MI_APCAL_EVENT_NOTIFY', '�������');
define('_MI_APCAL_EVENT_NOTIFYDSC', '��������� ���������� ��� �������� �������.');

define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFY', '����� �������');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYCAP', '���������� ���� ��� �������� ������ �������.');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYDSC', '���������� ���� � �������� ������ �������, ������� �������� �������.');
define('_MI_APCAL_GLOBAL_NEWEVENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} ����-���������� : ����� �������');

define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFY', '����� ������� � ���������');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYCAP', '���������� ���� � �������� ������ ������� � ���������.');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYDSC', '���������� ���� � �������� ������ ������� � ���������, ������� �������� �������.');
define('_MI_APCAL_CATEGORY_NEWEVENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} ����-���������� : ����� ������� � ��������� {CATEGORY_TITLE}');



}

?>