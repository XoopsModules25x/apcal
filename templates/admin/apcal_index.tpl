<table>
    <tr>
        <td width="40%">
            <div class="rmmenuicon">
        <{foreach item=menuitem from=$adminmenu}>
                    <{if $menuitem.link != 'admin/index.php'}>
                        <a href="<{$moduleURL}>/<{$menuitem.link}>" title="<{$menuitem.title}>">
                            <img src="<{$moduleURL}>/<{$menuitem.icon}>" alt="<{$menuitem.title}>"/>
                            <span><{$menuitem.title}></span>
                        </a>
                    <{/if}>
                <{/foreach}>
                <{if $moduleHelp && $xoopsversion >= '2.5.0'}>
                    <a href="<{$xoopsurl}>/modules/system/help.php?mid=<{$moduleID}>&<{$moduleHelp}>"
                       title="<{$smarty.const._AM_APCAL_SYSTEM_HELP}>">
                        <img width="32px" src="<{$imgURL}>/help.png" alt="<{$smarty.const._AM_APCAL_SYSTEM_HELP}>"/>
                        <span><{$smarty.const._AM_APCAL_SYSTEM_HELP}></span>
                    </a>
                <{/if}>
            </div>
            <div style="clear: both;"></div>
        </td>
        <td width="60%">
            <{foreach item=infoBox key=title from=$infoBoxes}>
                <fieldset>
                    <legend class="label">
                        <{$title}>
                    </legend>
                    <br>
                    <{foreach item=infoLine from=$infoBox}>
                        <{$infoLine}>
                        <br>
                    <{/foreach}>
                </fieldset>
                <br>
            <{/foreach}>
        </td>
    </tr>

    <{if $minphp || $minxoops}>
        <tr>
            <td colspan="2">
                <fieldset>
                    <legend class="label">
                        <{$smarty.const._AM_APCAL_MODULEADMIN_CONFIG}>
                    </legend>
                    <br>
                    <{if $minphp}>
                        <{if $phpversion < $minphp}>
                            <span style="color : red; font-weight : bold;"><img
                                        src="<{$imgURL}>/off.png"/><{$smarty.const._AM_APCAL_MODULEADMIN_CONFIG_PHP|sprintf:$minphp:$phpversion}></span>
                        <{else}>
                            <span style="color : green;"><img
                                        src="<{$imgURL}>/on.png"/><{$smarty.const._AM_APCAL_MODULEADMIN_CONFIG_PHP|sprintf:$minphp:$phpversion}></span>
                        <{/if}>
                        <br>
                    <{/if}>
                    <{if $minxoops}>
                        <{if $xoopsversion < $minxoops}>
                            <span style="color : red; font-weight : bold;"><img
                                        src="<{$imgURL}>/off.png"/><{$smarty.const._AM_APCAL_MODULEADMIN_CONFIG_XOOPS|sprintf:$minxoops:$xoopsversion}></span>
                        <{else}>
                            <span style="color : green;"><img
                                        src="<{$imgURL}>/on.png"/><{$smarty.const._AM_APCAL_MODULEADMIN_CONFIG_XOOPS|sprintf:$minxoops:$xoopsversion}></span>
                        <{/if}>
                        <br>
                    <{/if}>
                </fieldset>
            </td>
        </tr>
    <{/if}>
</table>
