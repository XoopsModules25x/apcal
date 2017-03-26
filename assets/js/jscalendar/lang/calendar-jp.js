// ** I18N

// Calendar EN language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("ÆüÍËÆü",
    "·îÍËÆü",
    "²ÐÍËÆü",
    "¿åÍËÆü",
    "ÌÚÍËÆü",
    "¶âÍËÆü",
    "ÅÚÍËÆü",
    "ÆüÍËÆü");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Æü",
    "·î",
    "²Ð",
    "¿å",
    "ÌÚ",
    "¶â",
    "ÅÚ",
    "Æü");

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 0;

// full month names
Calendar._MN = new Array
("1·î",
    "2·î",
    "3·î",
    "4·î",
    "5·î",
    "6·î",
    "7·î",
    "8·î",
    "9·î",
    "10·î",
    "11·î",
    "12·î");

// short month names
Calendar._SMN = new Array
("1·î",
    "2·î",
    "3·î",
    "4·î",
    "5·î",
    "6·î",
    "7·î",
    "8·î",
    "9·î",
    "10·î",
    "11·î",
    "12·î");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "¤³¤Î¥«¥ì¥ó¥À¡¼¤Ë¤Ä¤¤¤Æ";

Calendar._TT["ABOUT"] =
    "DHTML Date/Time Selector\n" +
    "(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
    "For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
    "Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
    "\n\n" +
    "ÆüÉÕÁªÂòÊýË¡:\n" +
    "- \xab, \xbb ¤ÇÇ¯¤òÁªÂò\n" +
    "- " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " ¤Ç·î¤òÁªÂò\n" +
    "- ³Æ¥Ü¥¿¥ó¤òÄ¹²¡¤·¤¹¤ë¤³¤È¤Ç¡¢Ä¾ÀÜÁªÂò¤¹¤ë¤³¤È¤â²ÄÇ½¤Ç¤¹\n" +
    "- ¥­¡¼¥Ü¡¼¥É¤Ç¤ÎÁàºî¤â²ÄÇ½¤Ç¤¹\n" +
    "- ¥«¡¼¥½¥ë¥­¡¼¤ÇÆüÉÕÊÑ¹¹¡¢CTRL+¥«¡¼¥½¥ë¥­¡¼¤ÇÇ¯·îÊÑ¹¹\n" +
    "- ¥¹¥Ú¡¼¥¹¤Çº£Æü¡¦ENTER¤Ç³ÎÄê¡¦ESC¤Ç¥­¥ã¥ó¥»¥ë¤Ç¤¹\n";

Calendar._TT["ABOUT_TIME"] = "\n\n" +
    "»þ´ÖÁªÂòÊýË¡:\n" +
    "- »þ¡¦Ê¬¤ò¥¯¥ê¥Ã¥¯¤¹¤ë¤³¤È¤Ç£±¤º¤ÄÁý¤¨¤Þ¤¹\n" +
    "- ¥·¥Õ¥È¥¯¥ê¥Ã¥¯¤¹¤ì¤Ð¡¢£±¤º¤Ä¸º¤ê¤Þ¤¹\n" +
    "- ´·¤ì¤Æ¤­¤¿¤é¡¢¥É¥é¥Ã¥°¤¹¤ë¤³¤È¤ÇÁÇÁá¤¤ÊÑ¹¹¤â²ÄÇ½¤Ç¤¹\n";

Calendar._TT["PREV_YEAR"] = "Á°Ç¯";
Calendar._TT["PREV_MONTH"] = "Á°·î";
Calendar._TT["GO_TODAY"] = "º£Æü";
Calendar._TT["NEXT_MONTH"] = "Íâ·î";
Calendar._TT["NEXT_YEAR"] = "ÍâÇ¯";
Calendar._TT["SEL_DATE"] = "ÆüÉÕ¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
Calendar._TT["DRAG_TO_MOVE"] = "¥É¥é¥Ã¥°²ÄÇ½";
Calendar._TT["PART_TODAY"] = " (º£Æü)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "%s ¤ò½µ»Ï¤á¤È¤¹¤ë";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "ÊÄ¤¸¤ë";
Calendar._TT["TODAY"] = "º£Æü";
Calendar._TT["TIME_PART"] = "¥¯¥ê¥Ã¥¯¡¦¥·¥Õ¥È+¥¯¥ê¥Ã¥¯¡¦¥É¥é¥Ã¥°¤ÇÊÑ¹¹²ÄÇ½";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%B %eÆü (%A)";

Calendar._TT["WK"] = "½µ";
Calendar._TT["TIME"] = "Time:";
