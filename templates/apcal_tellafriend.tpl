<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title><{$title}></title>
    <meta name="description" content=""/>
    <link rel="stylesheet" href="<{$xoops_url}>//modules/apcal/assets/css/apcal.css" type="text/css"/>
</head>
<body>
<script type="text/javascript">
    function validate() {
        var from = document.tellafriend.from.value;
        var to = document.tellafriend.to.value;
        var subject = document.tellafriend.subject.value;
        var message = document.tellafriend.message.value;

        var email = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

        var fromErr = !email.test(from) ? '<{$smarty.const._APCAL_ENTERFROM}>' + "\r\n" : '';
        var toErr = !email.test(to) ? '<{$smarty.const._APCAL_ENTERTO}>' + "\r\n" : '';
        var subErr = subject == '' ? '<{$smarty.const._APCAL_ENTERSUBJECT}>' + "\r\n" : '';
        var messErr = message == '' ? '<{$smarty.const._APCAL_ENTERMESSAGE}>' + "\r\n" : '';

        if (fromErr || toErr || subErr || messErr) {
            alert(fromErr + toErr + subErr + messErr);
        }
        else {
            document.tellafriend.submit();
        }
    }
</script>
<form class='apcalForm' name="tellafriend" action="<{$xoops_url}>/modules/apcal/tellafriend.php" method="post">
    <table>
        <tr>
            <td class="head"><{$smarty.const._APCAL_TO}></td>
            <td class="even"><input type="text" name="to" value="<{if isset($vars.to)}><{$vars.to}><{/if}>"
                                    style="width: 400px;" onkeypress="if(window.event.keyCode == 13){validate();}"/>
            </td>
        </tr>
        <tr>
            <td class="head"><{$smarty.const._APCAL_FROM}></td>
            <td class="odd"><input type="text" name="from"
                                   value="<{if isset($vars.from)}><{$vars.from}><{else}><{$from}><{/if}>"
                                   style="width: 400px;"
                                   onkeypress="if(window.event.keyCode == 13){validate();}"/></td>
        </tr>
        <{if $captcha != ''}>
            <tr>
                <td class="head"><{$smarty.const._APCAL_CAPTCHA}></td>
                <td class="even">
                    <span style="color: red; font-weight: bold; font: 1.1em;"><{$captchaMsg}></span>
                    <br>
                    <{$captcha}>
                </td>
            </tr>
        <{/if}>
        <tr>
            <td class="head"><{$smarty.const._APCAL_SUBJECT}></td>
            <td class="odd"><input type="text" name="subject"
                                   value="<{if isset($vars.subject)}><{$vars.subject}><{else}><{$title}><{/if}>"
                                   style="width: 400px;"
                                   onkeypress="if(window.event.keyCode == 13){validate();}"/></td>
        </tr>
        <tr>
            <td class="head"><{$smarty.const._APCAL_MESSAGE}></td>
            <td class="even">
                    <textarea name="message" style="width: 400px; height: 150px;">
<{if isset($vars.message)}>
    <{$vars.message}>
<{else}>
    <{$smarty.const._APCAL_TELLAFRIENDTEXT}>
    <{$url}>
<{/if}>
                    </textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="button" name="send" value="<{$smarty.const._SUBMIT}>" onclick="validate();"/></td>
        </tr>
    </table>

</form>
</body>
</html>
