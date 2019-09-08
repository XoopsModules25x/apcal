<script type="text/javascript">
    function showBox(id) {
        var box = document.getElementById(id);
        box.style.display = "inline";
        //moveBox(id);
    }

    function hideBox(id) {
        var box = document.getElementById(id);
        box.style.display = "none";
    }

    function moveBox(id) {
        var box = document.getElementById(id);
        var browWidth = window.innerWidth ? window.innerWidth : document.body.offsetWidth;
        var browHeight = window.innerHeight ? window.innerHeight : document.body.offsetHeight;
        var boxWidth = box.offsetWidth;
        var boxHeight = box.offsetHeight;
        var boxX = window.event.pageX ? window.event.pageX : window.event.clientX + document.documentElement.scrollLeft;
        var boxY = window.event.pageY ? window.event.pageY : window.event.clientY + document.documentElement.scrollTop;

        box.style.left = ((boxX + boxWidth + 20 >= browWidth) ? browWidth - boxWidth - 20 : boxX) + "px";
        box.style.top = ((window.event.clientY + boxHeight + 20 >= browHeight) ? boxY - boxHeight - 25 : boxY + 25) + "px";
    }
</script>

<div>&nbsp;</div>
<table class="month">
    <tr>
        <td class="weekno"><img src="<{$images_url}>/spacer.gif" alt="" width="10" height="20"/></td>
        <{if !$week_start}>
            <td class="dayname"
                style="color: <{$colors[0]}>; width:<{$widths.Sunday}>; <{$frame_css}>"><{$week_middle_names[0]}></td><{/if}>
        <td class="dayname"
            style="color: <{$colors[1]}>; width:<{$widths.Monday}>; <{$frame_css}>"><{$week_middle_names[1]}></td>
        <td class="dayname"
            style="color: <{$colors[2]}>; width:<{$widths.Tuesday}>; <{$frame_css}>"><{$week_middle_names[2]}></td>
        <td class="dayname"
            style="color: <{$colors[3]}>; width:<{$widths.Wednesday}>; <{$frame_css}>"><{$week_middle_names[3]}></td>
        <td class="dayname"
            style="color: <{$colors[4]}>; width:<{$widths.Thursday}>; <{$frame_css}>"><{$week_middle_names[4]}></td>
        <td class="dayname"
            style="color: <{$colors[5]}>; width:<{$widths.Friday}>; <{$frame_css}>"><{$week_middle_names[5]}></td>
        <td class="dayname"
            style="color: <{$colors[6]}>; width:<{$widths.Saturday}>; <{$frame_css}>"><{$week_middle_names[6]}></td>
        <{if $week_start}>
            <td class="dayname"
                style="color: <{$colors[0]}>; width:<{$widths.Sunday}>; <{$frame_css}>"><{$week_middle_names[0]}></td><{/if}>
    </tr>
    <{if $slots < 3}><{assign var=slots value=3}><{/if}>
    <{counter start=$day print=false}>
    <{section name=weeks start=0 loop=6}>
        <{if $day < $last_day}>
            <tr>
                <td rowspan="<{math equation=x+1 x=$slots}>" class="weekno">
                    <{if $week_numbering}><{assign var=alt_week value=$smarty.section.weeks.index+$weekno}>
                    <{else}><{assign var=alt_week value=$smarty.section.weeks.index+1}><{/if}>
                    <{assign var=caldate value=$year|cat:'-'|cat:$month|cat:'-'|cat:$day+1}>
                    <a href="<{$cal->make_cal_link('', 'Weekly', $cid, $caldate)}>">
                        <{$alt_week}>
                    </a>
                </td>
                <{section name=wdays start=$week_start loop=$week_end}>
                    <{counter assign=day print=false}>
                    <{if $smarty.section.wdays.first}><{assign var=firstDay value=$day}><{/if}>
                    <{if $smarty.section.wdays.last}><{assign var=lastDay value=$day}><{/if}>
                    <{assign var=wday value=$smarty.section.wdays.index}>
                    <{assign var=link value=$year|cat:'-'|cat:$month|cat:'-'|cat:$day}>
                    <{if $day <= $last_day && $day > 0}>
                        <{if isset($holidays[$link])}>
                            <{assign var=bgcolor value=$holiday_bgcolor}>
                            <{assign var=color value=$holiday_color}>
                        <{else}>
                            <{assign var=bgcolor value=$bgcolors[$wday]}>
                            <{assign var=color value=$colors[$wday]}>
                        <{/if}>
                        <{if $day == $selectedday}>
                            <{assign var=bgcolor value=$targetday_bgcolor}>
                        <{/if}>
                        <td class="day" style="background: <{$bgcolor}>; <{$frame_css}>">
                            <table width="100%" cellspacing="0" cellpadding="0" style="table-layout:fixed; margin:0;">
                                <tr>
                                    <td align="left" width="25"
                                        style="font-size: 1.3em; font-weight: bold; color:<{$color}>;">
                                        <a href="<{$cal->make_cal_link('', 'Daily', $cid, $link)}>"
                                           style="color: inherit;">
                                            <{$day}>
                                        </a>
                                    </td>
                                    <td style="overflow: hidden; color: <{$color}>;">
                                        <a href="<{$cal->make_cal_link('', 'Monthly', $cid, $link)}>"
                                           style="color: inherit;">
                                            <{if isset($holidays[$link]) && $holidays[$link] != 1}>
                                                <{$holidays[$link]}>
                                            <{else}>
                                                <img src="<{$images_url}>/spacer.gif" alt="" border="0" width="100%"
                                                     height="12"/>
                                            <{/if}>
                                        </a>
                                    </td>
                                    <td align="right" width="15">
                                        <{if $insertable}>
                                            <a href="<{$xoops_url}>/modules/apcal/?cid=<{$cid}>&smode=Monthly&action=Edit&caldate=<{$link}>">
                                                <img src="<{$images_url}>/addevent.gif" border="0" width="14"
                                                     height="12" alt="<{$smarty.const._APCAL_MB_ADDEVENT}>"/>
                                            </a>
                                        <{/if}>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <{else}>
                        <td class="day" style="<{$frame_css}>">&nbsp;</td>
                    <{/if}>
                <{/section}>
            </tr>
            <{if $firstDay < 0}><{assign var=adjust value=$firstDay}><{math equation=x-y x=$lastDay y=$firstDay assign=lastDay}><{assign var=firstDay value=0}>
            <{else}><{assign var=adjust value=0}><{/if}>
            <{section name=slots start=0 loop=$slots}>
                <tr>
                    <{section name=blocks start=$firstDay loop=$lastDay+1}>
                        <{if $adjust}><{assign var=day value=$smarty.section.blocks.index+$adjust}>
                        <{else}><{assign var=day value=$smarty.section.blocks.index}><{/if}>
                        <{assign var=slot value=$smarty.section.slots.index}>
                        <{assign var=id value=$e[$day][$slot].id}>
                        <{assign var=event value=$events[$id]}>
                        <{if !isset($e[$day][$slot])}>
                            <td class="noevent" style="<{$frame_css}>">&nbsp;</td>
                        <{elseif $e[$day][$slot].first}>
                            <td colspan="<{$e[$day][$slot].duration}>" class="event" style="<{$frame_css}>"
                                onmouseover="showBox('<{$id}>');" onmouseout="hideBox('<{$id}>');">
                                <a href="<{$events[$id].link}>"
                                   style="border-left-color: <{$cats_color[$event.cat]}>; border-bottom-color: <{$cats_color[$event.cat]}>; background: <{$event_bgcolor}>; color: <{$event_color}>;">
                                    <{if $events[$id].extkey0 == 1}><img src="<{$ro_image}>" height="12px"
                                                                         alt="<{$smarty.const._APCAL_RO_ONLINE_POSS}>"
                                                                         title="<{$smarty.const._APCAL_RO_ONLINE_POSS}>" /><{/if}>
                                    <{$events[$id].summary}>
                                </a>
                                <{if !$for_print}>
                                    <div id="<{$id}>" class="apcaltooltip"
                                         style="border-color: <{$cats_color[$event.cat]}>;">
                                        <div class="summary" style="background: <{$cats_color[$event.cat]}>;">
                                            <{if $events[$id].extkey0 == 1}><img src="<{$ro_image}>" height="15px"
                                                                                 alt="<{$smarty.const._APCAL_RO_ONLINE_POSS}>"
                                                                                 title="<{$smarty.const._APCAL_RO_ONLINE_POSS}>" /><{/if}>
                                            &nbsp;<{$events[$id].summary}>
                                        </div>
                                        <div class="details">
                                            <{if $events[$id].picture}><img src="<{$events[$id].picture}>"
                                                                            alt="<{$events[$id].summary}>"
                                                                            title="<{$events[$id].summary}>" /><{/if}>
                                            <div class="info">
                                                <{if $events[$id].start}><span
                                                        class="bold"><{$smarty.const._APCAL_BEGIN}>
                                                    :</span> <{$events[$id].start}>
                                                    <br>
                                                <{/if}>
                                                <{if $events[$id].end}><span class="bold"><{$smarty.const._APCAL_END}>
                                                    :</span> <{$events[$id].end}>
                                                    <br>
                                                <{/if}>
                                                <{if $events[$id].location}><span
                                                        class="bold"><{$smarty.const._APCAL_LOCATION}>
                                                    : </span><{$events[$id].location}>
                                                    <br>
                                                <{/if}>
                                                <br>
                                                <div class="click"
                                                     <{if $events[$id].picture}>style="margin-left: 60px;"<{/if}>>
                                                    <br><{$smarty.const._APCAL_CLICKFORDETAILS}></div>
                                            </div>
                                        </div>
                                    </div>
                                <{/if}>
                            </td>
                        <{/if}>
                    <{/section}>
                </tr>
            <{/section}>
        <{/if}>
    <{/section}>
    <tr>
        <td></td>
        <td colspan="7" style="border-top: 1px solid; <{$frame_css}>">
            <{foreach item=cat from=$categories}>
                <{assign var=id value=$cat->cid}>
                <span style="background: <{$cats_color[$id]}>; border: 1px solid;">&nbsp;&nbsp;</span>
                <a href="<{$cat->link}>"><{$cat->cat_title}></a>
            <{/foreach}>
            <span style="background: <{$cats_color.00000}>; border: 1px solid;">&nbsp;&nbsp;</span>
            <a href="<{$cal->make_cal_link('', 'Monthly', 0, '')}>"><{$smarty.const._APCAL_MB_SHOWALLCAT}></a>
        </td>
    </tr>
</table>
