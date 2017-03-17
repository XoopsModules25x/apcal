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
 * @author       Antiques Promotion (http://www.antiquespromotion.ca)
 */
 *}>

<script type="text/javascript" src="<{$xoops_url}>/modules/apcal/assets/images/js/highslide/highslide-with-gallery.js"></script>
<link rel="stylesheet" type="text/css" href="<{$xoops_url}>/modules/apcal/assets/images/js/highslide/highslide.css"/>
<script type="text/javascript">
    hs.graphicsDir = '<{$xoops_url}>/modules/apcal/assets/images/js/highslide/graphics/';
    hs.align = 'center';
    hs.transitions = ['expand', 'crossfade'];
    hs.outlineType = 'glossy-dark';
    hs.wrapperClassName = 'dark';
    hs.fadeInOut = true;

    // Add the controlbar
    if (hs.addSlideshow) hs.addSlideshow({
        interval: 5000,
        repeat: false,
        useControls: true,
        fixedControls: 'fit',
        overlayOptions: {
            opacity: .6,
            position: 'bottom center',
            hideOnMouseOut: true
        }
    });
</script>

<{if $block.num_rows == 0}>
    <{$block.lang_APCAL_MB_APCALNOEVENT}>
<{/if}>

<dl>
    <{foreach item=event from=$block.events}>
        <dt>
            <{if $block.showPictures > 0 && $event.picture != ''}>
            <span style='font-size: x-small; '>
                <a href="<{$xoops_upload_url}>/apcal/<{$event.picture}>" class="highslide" title="<{$event.summary}>"
                   onclick="return hs.expand(this)">
                    <img src="<{$xoops_upload_url}>/apcal/thumbs/<{$event.picture}>" alt="<{$event.summary}>"
                         title="<{$event.summary}>" style='max-width: 18px; max-height: 20px;'/>
                </a>
                <{else}>
                <span style='font-size: x-small; '>
                    <img src="<{$block.images_url}>/<{$event.dot_gif}>" alt="<{$event.summary}>"
                         title="<{$event.summary}>" style='max-width: 18px; max-height: 20px;'/>&nbsp;
                    <{/if}>

                    <{if $event.distance == 0}>
                        <{$block.lang_APCAL_MB_APCALCONTINUING}> - <{$event.end_desc}>
                    <{elseif $event.distance == 1}>
                        <{$event.start_desc}> - <{$event.end_desc}>
                    <{elseif $event.distance == 2}>
                        <{$event.start_desc}><{if $event.multiday}> - <{$event.end_desc}><{/if}>
                    <{else}>
                        <{$event.start_desc}><{if $event.multiday}> - <{$event.end_desc}><{/if}>
                    <{/if}>
                </span>
        </dt>
        <dd style='margin-left:20px;'>
            <span style='font-size: x-small; '><a
                        href='<{$block.get_target}>?smode=Daily&amp;action=View&amp;event_id=<{$event.id}>&amp;caldate=<{$block.caldate}>'
                        class='calsummary'
                        title='<{$event.mainCat}> - <{$event.location}>'><{$event.summary}></a></span>
        </dd>
    <{/foreach}>
</dl>

<{if $block.num_rows_rest > 0}>
    <table border='0' cellspacing='0' cellpadding='0' width='100%'>
        <tr>
            <td>
                <small>
                    <a href="<{$xoops_url}>/modules/APCal"><{$block.lang_APCAL_MB_APCALRESTEVENT_PRE}> <{$block.num_rows_rest}> <{$block.lang_APCAL_MB_APCALRESTEVENT_SUF}></a>
                </small>
            </td>
        </tr>
    </table>
<{/if}>

<{if $block.insertable <> false}>
    <table border='0' cellspacing='0' cellpadding='0' width='100%'>
        <tr>
            <td align='left'>
                <span style='font-size: x-small; '>
                    <small><a href='<{$block.get_target}>?smode=Daily&amp;action=Edit&amp;caldate=<{$block.caldate}>'>
                            <img src='<{$block.images_url}>/addevent.gif' border='0' width='14'
                                 height='12'/><{$block.lang_APCAL_MB_APCALADDEVENT}>
                        </a></small>
                </span>
            </td>
        </tr>
    </table>
<{/if}>
