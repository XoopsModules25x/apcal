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
<i id='minical'></i>
<{strip}>
    <table border="0" cellspacing="0" cellpadding="0" width="150" style="width:150px;line-height:normal;margin:0;">
        <tr>
            <td width="150" class="calframe" style="<{$block.frame_css}>">
                <table border="0" cellspacing="0" cellpadding="0" width="100%"
                       style="border-collapse:collapse;margin:0;">

                    <!-- header part -->
                    <tr>
                        <td nowrap="nowrap" colspan="7"
                            style="background-color:<{$block.calhead_bgcolor}>;text-align:center;vertical-align:middle;padding:3px 1px;">
                            <a href="<{$block.root_url}>?caldate=<{$block.prev_month}><{$block.additional_get}>"><img
                                        src="<{$block.skinpath}>/miniarrowleft.gif" width="18" height="14" border="0"
                                        alt="<{$block.lang_prev_month}>" title="<{$block.lang_prev_month}>"/></a>
                            <span style="font-size: x-small; "><span class="apcal_head"
                                                                     style="color:<{$block.calhead_color}>;"><b><{$block.year_month_title}></b></span></span>
                            <a href="<{$block.root_url}>?caldate=<{$block.next_month}><{$block.additional_get}>"><img
                                        src="<{$block.skinpath}>/miniarrowright.gif" width="18" height="14" border="0"
                                        alt="<{$block.lang_next_month}>" title="<{$block.lang_next_month}>"/></a>
                        </td>
                    </tr>

                    <tr>
                        <!-- day name loop -->
                        <{foreach from=$block.daynames item=dayname}>
                            <td class="apcal_minidayname" style="text-align:center;padding:3px 1px;">
                                <span class="apcal_minidayname"
                                      style="color:<{$dayname.color}>;"><{$dayname.dayname}></span>
                            </td>
                        <{/foreach}>
                    </tr>

                    <!-- weekly loop -->
                    <{foreach from=$block.weeks item=week}>
                        <tr>
                            <!-- daily loop -->
                            <{foreach from=$week item=day}>
                                <{if $day.type == 0 }>
                                    <td class="apcal_miniday">
                                        <span class="apcal_miniday"><img src="<{$block.skinpath}>/spacer.gif" alt=""
                                                                         width="20" height="20"/></span>
                                    </td>
                                <{else}>
                                    <td class="apcal_miniday"
                                        style="background-color:<{$day.bgcolor}>;text-align:center;height:20px;">
                                        <span class="apcal_miniday"
                                              style="color:<{$day.color}>;"><{$day.date}></span><br>
                                        <span class="apcal_miniday">

                  <{foreach from=$day.ex item=ex}>
                      <a href="<{$ex.link}>"><img src="<{$block.skinpath}>/<{$ex.dotgif}>" alt="<{$ex.title}>"
                                                  title="<{$ex.title}>" width="8" height="8"/></a>





                                                                                                                                                    <{foreachelse}>





                      <img src="<{$block.skinpath}>/spacer.gif" alt="" width="8" height="8"/>
                  <{/foreach}>
                </span>
                                    </td>
                                <{/if}>
                            <{/foreach}>
                        </tr>
                    <{/foreach}>

                </table>

                <div align=right style="padding:3px;">
                    <{foreach from=$block.plugins item=plugin}>
                        <div style='float:right;height:1.5em;'>
                            <nobr>
                                <img src="<{$block.skinpath}>/<{$plugin.dotgif}>"
                                     alt="<{$plugin.pi_title}>"/>&nbsp;<{$plugin.pi_title}>&nbsp;&nbsp;
                            </nobr>
                        </div>
                    <{/foreach}>
                </div>

            </td>
        </tr>
    </table>
<{/strip}>
