<{*
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
 * @copyright    {@link http://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team,
 * @author       GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
 */
 *}>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<{$charset}>"/>
    <title><{$sitename}></title>
    <meta name="COPYRIGHT" content="Copyright (c) 2004 by <{$sitename}>"/>
    <meta name="GENERATOR" content="APCal with XOOPS"/>
    <style><!--
        table.outer {
            border-collapse: collapse;
            border: 1px solid black;
        }

        }
        .head {
            padding: 3px;
            border: 1px black solid;
        }

        .even {
            padding: 3px;
            border: 1px black solid;
        }

        .odd {
            padding: 3px;
            border: 1px black solid;
        }

        table td {
            vertical-align: top;
        }

        a {
            text-decoration: none;
        }

        --></style>
</head>
<body bgcolor="#ffffff" text="#000000" onload="window.print()">
<table border="0" style="font: 12px;">
    <tr>
        <td>
            <table border="0" width="100%" cellpadding="0" cellspacing="1" bgcolor="#000000">
                <tr>
                    <td>
                        <table border="0" width="100%" cellpadding="20" cellspacing="1" bgcolor="#ffffff">
                            <tr valign="top">
                                <td>
                                    <{if $for_event_list == true}>
                                        <{include file="db:apcal_event_list.tpl"}>
                                    <{else}>
                                        <{$contents}>
                                    <{/if}>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br><br>
            <hr>
            <br>
            <{$lang_comesfrom}>
            <br><a href="<{$site_url}>/"><{$site_url}></a>
        </td>
    </tr>
</table>
</body>
</html>
