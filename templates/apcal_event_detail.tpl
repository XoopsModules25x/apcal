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
 * @author      Antiques Promotion (http://www.antiquespromotion.ca)
 * @author      GIJ=CHECKMATE (PEAK Corp. http://www.peak.ne.jp/)
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

<div class="eventPics" style="width: <{$picsWidth}>; float: right; text-align: center;">
    <{$pictures}>
</div>
<div style="margin-right: <{$picsMargin}>;">
    <{if $showMap == 1}>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=<{$api_key}>&callback=initMap" type="text/javascript"></script>
        <script type="text/javascript">
            var GMlat = <{$GMLat}>;
            var GMlong = <{$GMLong}>;
            var GMzoom = <{$GMZoom}>;
            var GMheight = '<{$GMheight}>';

            function GMinit() {
                if (GMlat > 0 || GMlong > 0) {
                    var latlng = new google.maps.LatLng(GMlat, GMlong);
                    var myOptions = {
                        zoom: GMzoom,
                        center: latlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        scrollwheel: false
                    };
                    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                    document.getElementById('map_canvas').style.height = GMheight;

                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(GMlat, GMlong),
                        map: map,
                        title: '<{$title}>',
                        location: '<{$location}>',
                        contact: "<{$contact}>"
                    });

                    var infowindow = new google.maps.InfoWindow({maxWidth: 25});
                    google.maps.event.addListener(marker, 'click', function () {
                        var content =
                                '<b>' + this.title + '</b><br>' +
                                this.location + '<br><br>' +
                                this.contact;
                        infowindow.setContent(content);
                        infowindow.open(map, this);
                    });
                }
            }

            window.onload = GMinit;
        </script>
        <div id="map_canvas" style="width:100%; height: 0;"></div>
    <{/if}>
    <div>
        <div class="eventTitle">
            <h2><{$title}></h2>
        </div>
        <{if $showSocial}>
            <div class="socialNetworks" style="float: right; text-align: right;">
        <span class="print">
            <a href="<{$print_link}>" target="_blank">
                <img src="<{$skinpath}>/print.gif" alt="<{$lang_print}>" title="<{$lang_print}>" border="0"/>
            </a>
        </span>
                <{if $showTellaFriend}>
                    <span class="tellafriend">
            <a href="" title="<{$smarty.const._APCAL_TELLAFRIEND}>"
               onclick="window.open('<{$xoops_url}>/modules/apcal/tellafriend.php?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title), '<{$smarty.const._APCAL_TELLAFRIEND}>', 'toolbar=no, width=550, height=550'); return false;">
                <img src="<{$xoops_url}>/modules/apcal/assets/images/tellafriend.png" height="20" width="20"
                     alt="<{$smarty.const._APCAL_TELLAFRIEND}>" title="<{$smarty.const._APCAL_TELLAFRIEND}>"/>
            </a>
        </span>
                <{/if}>
                <span class="delicious">
            <a href="http://www.delicious.com/save" title="Delicious"
               onclick="window.open('http://www.delicious.com/save?v=5&noui&jump=close&url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;">
                <img src="<{$xoops_url}>/modules/apcal/assets/images/delicious.png" height="20" width="20"
                     alt="Delicious" title="Delicious"/>
            </a>
        </span>
                <span class="googleplus">
            <script type="text/javascript" src="https://apis.google.com/js/plusone.js">{
                    lang: '<{$smarty.const._APCAL_GPLUS_LNG}>'
                }</script>
            <g:plusone size="medium" count="false" href="<{$xoops_url}>/modules/APCal"></g:plusone>
        </span>
                <span class="linkedIn">
            <script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
            <script type="IN/Share" data-url="<{$xoops_url}>/modules/APCal"></script>
        </span>
                <span class="twitter">
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<{$xoops_url}>/modules/APCal"
               data-count="none">Tweet</a>
            <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
        </span>
                <span class="facebook">
            <script type="text/javascript"
                    src="http://connect.facebook.net/<{$smarty.const._APCAL_FB_LNG}>/all.js#xfbml=1"></script>
            <div class="fb-like" data-href="<{$xoops_url}>/modules/APCal" data-send="false" data-layout="button_count"
                 data-action="recommend" data-show-faces="false"></div>
        </span>
            </div>
        <{/if}>
    </div>
    <div style="clear: left;"></div>

    <{if $eventNavEnabled}>
        <div class="eventNav">
            <div class="leftDiv">
                <{if $prevEvent}>
                    <a href="<{$prevEvent}>"
                       title="<{$smarty.const._APCAL_PREVEVENT}>"><{$smarty.const._APCAL_PREVEVENT}></a>
                <{else}>
                    &nbsp;
                <{/if}>
            </div>
            <div class="centerDiv">
                <a href="<{$calLink}>" title="<{$smarty.const._APCAL_BACKTOCAL}>"><{$smarty.const._APCAL_BACKTOCAL}></a>
            </div>
            <div class="rightDiv">
                <{if $nextEvent}>
                    <a href="<{$nextEvent}>"
                       title="<{$smarty.const._APCAL_NEXTEVENT}>"><{$smarty.const._APCAL_NEXTEVENT}></a>
                <{else}>
                    &nbsp;
                <{/if}>
            </div>
        </div>
    <{/if}>

    <{$detail_body}>
</div>

<div style="text-align: center; padding: 3px; margin: 3px;">
    <{$commentsnav}>
    <{$lang_notice}>
</div>

<div style="margin: 3px; padding: 3px;">
    <!-- start comments loop -->
    <{if $comment_mode == "flat"}>
        <{include file="db:system_comments_flat.tpl"}>
    <{elseif $comment_mode == "thread"}>
        <{include file="db:system_comments_thread.tpl"}>

    <{elseif $comment_mode == "nest"}>
        <{include file="db:system_comments_nest.tpl"}>
    <{/if}>
    <!-- end comments loop -->
</div>

<{include file='db:system_notification_select.tpl'}>
