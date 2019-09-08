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
<i id='today_schedule'></i>
<{if $block.num_rows == 0}>
    <{$block.lang_APCAL_MB_NOEVENT}>
<{/if}>

<dl>
    <{foreach item=event from=$block.events}>

        <{if $event.allday == 0}>
            <dt>
                <{if $event.is_start_date == true}>
                    <span style='font-size: x-small; '><img border='0'
                                        src='<{$block.images_url}>/<{$event.dot_gif}>'/>&nbsp;&nbsp;<{$event.start_desc}>
                        - <{$event.end_desc}></span>
                <{else}>
                    <span style='font-size: x-small; '><img border='0'
                                        src='<{$block.images_url}>/<{$event.dot_gif}>'/>&nbsp;&nbsp;<{$block.lang_APCAL_MB_CONTINUING}>
                        - <{$event.end_desc}></span>
                <{/if}>
            </dt>
            <dd style='margin-left:20px;'>
                <span style='font-size: x-small; '><a
                            href='<{$block.get_target}>?smode=Daily&amp;action=View&amp;event_id=<{$event.id}>&amp;caldate=<{$block.caldate}>'
                            class='calsummary'><{$event.summary}></a></span>
            </dd>
        <{else}>
            <dt>
                <span style='font-size: x-small; '><img border='0'
                                    src='<{$block.images_url}>/<{$event.dot_gif}>'/>&nbsp;&nbsp;<{$block.lang_APCAL_MB_ALLDAY_EVENT}>
                </span>
            </dt>
            <dd style='margin-left:20px;'>
                <a href='<{$block.get_target}>?smode=Daily&amp;action=View&amp;event_id=<{$event.id}>&amp;caldate=<{$block.caldate}>'
                   class='calsummary_allday'><{$event.summary}></a>
            </dd>
        <{/if}>

    <{/foreach}>

</dl>

<{if $block.insertable <> false}>
    <dl>
        <dt>
            &nbsp; <span style='font-size: x-small; '><a
                        href='<{$block.get_target}>?smode=Daily&amp;action=Edit&amp;caldate=<{$block.caldate}>'><img
                            src='<{$block.images_url}>/addevent.gif' border='0' width='14'
                            height='12'/><{$block.lang_APCAL_MB_ADDEVENT}></a></span>
        </dt>
    </dl>
<{/if}>
