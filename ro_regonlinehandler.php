<?php

use \Xmf\Request;

require_once __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//XoopsMailer
require_once XOOPS_ROOT_PATH . '/class/xoopsmailer.php';
require_once XOOPS_ROOT_PATH . '/modules/apcal/language/' . $GLOBALS['xoopsConfig']['language'] . '/apcal_constants.php';

//this should be replace by module preferences
$mail_sender     = 'webmaster@mydomain.com';
$mail_sendername = 'Calendar of AP';
$mail_signature  = 'Your AP team';

//images
$roimageedit     = XOOPS_URL . '/modules/apcal/assets/images/regonline/edit.png';
$roimagedelete   = XOOPS_URL . '/modules/apcal/assets/images/regonline/delete.png';
$roimagesave     = XOOPS_URL . '/modules/apcal/assets/images/regonline/save.png';
$roimagesavemore = XOOPS_URL . '/modules/apcal/assets/images/regonline/savemore.png';
$roimagecancel   = XOOPS_URL . '/modules/apcal/assets/images/regonline/cancel.png';
$roimagesend     = XOOPS_URL . '/modules/apcal/assets/images/regonline/sendmail.png';

$show_form_activate = false;
if (isset($_POST['form_activate'])) {
    if (!empty($_POST['eventid'])) {
        //called from edit an event (activate or edit regonline)
        $eventid   = Request::getInt('eventid', 0, 'POST');
        $url       = Request::getString('url', '', 'POST');
        $eventurl  = Request::getString('eventurl', '', 'POST');
        $event     = Request::getString('title', '', 'POST');
        $eventdate = Request::getString('eventdate', '', 'POST');
        $location  = Request::getString('location', '', 'POST');

        $show_form_activate = true;
    }
}
if (isset($_GET['op'])) {
    if ($_GET['op'] == 'show_form_activate') {
        //called after automatically redirect after add new event
        if (isset($_GET['eventid'])) {
            $eventid = $_GET['eventid'];
        }
        if (isset($_GET['eventurl'])) {
            $eventurl = $_GET['eventurl'] . '?smode=' . $_GET['smode'] . '&caldate=' . $_GET['caldate'];
        }
        if (isset($_GET['title'])) {
            $event = $_GET['title'];
        }
        if (isset($_GET['eventdate'])) {
            $eventdate = $_GET['eventdate'];
        }

        $show_form_activate = true;
    }
}

if ($show_form_activate) {
    $uid = $xoopsUser->getVar('uid');

    $email1 = '';
    $email2 = '';
    $email3 = '';
    $email4 = '';
    $email5 = '';

    //read data from apcal_ro_events
    $query    = 'SELECT '
                . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                . '.* FROM '
                . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                . ' WHERE (('
                . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                . ".roe_eventid)=$eventid)";
    $res      = $GLOBALS['xoopsDB']->query($query);
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

    if ($num_rows == 0) {
        //edit new item, make preselection
        $email1    = $xoopsUser->getVar('email');
        $datelimit = $eventdate;
        $number    = '0';
        $typeedit  = '0'; //new
    } else {
        while ($ro_result = $GLOBALS['xoopsDB']->fetchObject($res)) {
            $roeid     = $ro_result->roe_id;
            $number    = $ro_result->roe_number;
            $datelimit = $ro_result->roe_datelimit;
            $typeedit  = '1'; //edit
        }
    }

    //read data from apcal_ro_notify
    $query    = 'SELECT '
                . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                . '.* FROM '
                . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                . ' WHERE (('
                . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                . ".ron_eventid)=$eventid)";
    $res      = $GLOBALS['xoopsDB']->query($query);
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

    $i = 0;
    if ($num_rows == 0) {
        //no data, use email from actual user
    } else {
        while ($ron_result = $GLOBALS['xoopsDB']->fetchObject($res)) {
            ++$i;
            switch ($i) {
                case 1:
                    $email1 = $ron_result->ron_email;
                    break;
                case 2:
                    $email2 = $ron_result->ron_email;
                    break;
                case 3:
                    $email3 = $ron_result->ron_email;
                    break;
                case 4:
                    $email4 = $ron_result->ron_email;
                    break;
                case 5:
                    $email5 = $ron_result->ron_email;
                    break;
            }
        }
    }

    if ($datelimit > 0) {
        $datelimit = date('d.m.Y H:i:s', $datelimit);
    }
    if ($eventdate > 0) {
        $eventdate = date('d.m.Y H:i:s', $eventdate);
    }

    $ret = "
    <table border='0' width='100%'>
        <tr><td width='100%' class='itemHead'><span class='itemTitle'>" . _APCAL_RO_TITLE2 . "</span></td></tr>
        <tr><td width='100%'>
        <form method='post' action='ro_regonlinehandler.php' name='roformactivate' style='margin:0px;'>
            <input type='hidden' name='eventid' value='$eventid' />
            <input type='hidden' name='uid' value='$uid' />
            <input type='hidden' name='eventurl' value='$eventurl' />
            <input type='hidden' name='url' value='$url' />
            <input type='hidden' name='typeedit' value='$typeedit' />
            <table>
                <tr>
                    <td class='even' width='300px'>" . _APCAL_RO_EVENT . ":</td>
                    <td class='odd'><input type='text' name='event' disabled='disabled' value='$event'  size='80' /></td>
                </tr>";
    $ret .= "
                <tr>
                    <td class='even' width='120px'>" . _APCAL_RO_DATE . ":</td>
                    <td class='odd'><input type='text' name='eventdate' disabled='disabled' value='$eventdate'  size='80' /></td>
                </tr>
                 <tr>
                    <td class='even' width='120px'>" . _APCAL_RO_LOCATION . ":</td>
                    <td class='odd'><input type='text' name='location' disabled='disabled' value='$location'  size='80' /></td>
                </tr>";
    $ret .= "
                <tr>
                    <td class='even' width='300px'>" . _APCAL_RO_QUANTITY . ":</td>
                    <td class='odd'><input type='text' name='number' value='$number' size='80' /></td>
                </tr>
                <tr>
                    <td class='even' width='300px'>" . _APCAL_RO_DATELIMIT . ":</td>
                    <td class='odd'><input type='text' name='datelimit' value='$datelimit' size='80' /></td>
                </tr>
                <tr>
                    <td class='even' width='300px'>" . _APCAL_RO_EMAIL_NOTIFY . ":</td>
                    <td class='odd'>
                        <table cellspacing='0' cellpading='0'>
                            <tr>
                                <td class='odd'><input type='text' name='email1' value='$email1' size='80' /></td>
                            </tr>
                            <tr>
                                <td class='odd'><input type='text' name='email2' value='$email2' size='80' /></td>
                            </tr>
                            <tr>
                                <td class='odd'><input type='text' name='email3' value='$email3' size='80' /></td>
                            </tr>
                            <tr>
                                <td class='odd'><input type='text' name='email4' value='$email4' size='80' /></td>
                            </tr>
                            <tr>
                                <td class='odd'><input type='text' name='email5' value='$email5' size='80' /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br><br>
            <div align='center'>";

    if ($typeedit == '0') {
        $ret .= "<input type='image' src='$roimagesave' name='activate' alt='" . _APCAL_RO_BTN_CONF_SAVE . "' title='" . _APCAL_RO_BTN_CONF_SAVE . "' height='32px'/>";
    } else {
        $ret .= "<input type='image' src='$roimagesave' name='activate' alt='" . _APCAL_RO_BTN_CONF_EDIT . "' title='" . _APCAL_RO_BTN_CONF_EDIT . "' height='32px'/>";
    }
    $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $ret .= "<input type='image' src='$roimagedelete' name='deactivate' alt='" . _APCAL_RO_BTN_RO_DEACTIVATE . "' title='" . _APCAL_RO_BTN_RO_DEACTIVATE . "' height='32px'/>";
    $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $ret .= "<input type='image' src='$roimagecancel' name='cancel' alt='" . _APCAL_RO_BTN_CANCEL . "' title='" . _APCAL_RO_BTN_CANCEL . "' height='32px'/>";
    $ret .= "</div>
        </form>
        </td></tr>
    </table>
    \n";
    echo $ret;
}

if (isset($_POST['activate_x'])) {
    if (!empty($_POST['eventid'])) {
        $uid       = $_POST['uid'];
        $eventid   = $_POST['eventid'];
        $eventurl  = $_POST['eventurl'];
        $datelimit = $_POST['datelimit'];
        $number    = $_POST['number'];
        $email1    = $_POST['email1'];
        $email2    = $_POST['email2'];
        $email3    = $_POST['email3'];
        $email4    = $_POST['email4'];
        $email5    = $_POST['email5'];
        $typeedit  = $_POST['typeedit'];

        //default-values
        if ($datelimit == '') {
            $datelimit = 0;
        }
        if ($datelimit > 0) {
            $datelimit = strtotime($datelimit);
        }

        if ($number == '') {
            $number = '0';
        }

        //insert or update data in table apcal_ro_events
        if ($typeedit == '0') {
            $query = 'Insert into '
                     . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                     . " (roe_submitter, roe_eventid, roe_datelimit, roe_number, roe_date_created) values ($uid, $eventid, $datelimit, $number, "
                     . time()
                     . ')';
        } else {
            $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ' SET ';
            $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ".roe_submitter = $uid, ";
            $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ".roe_datelimit = $datelimit, ";
            $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ".roe_number = $number, ";
            $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . '.roe_date_created = ' . time() . ' ';
            $query .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ".roe_eventid)=$eventid))";
        }
        $res = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
        }

        //update data in table apcal_events
        $query = 'UPDATE '
                 . $GLOBALS['xoopsDB']->prefix('apcal_event')
                 . ' SET '
                 . $GLOBALS['xoopsDB']->prefix('apcal_event')
                 . '.extkey0 = 1 WHERE ((('
                 . $GLOBALS['xoopsDB']->prefix('apcal_event')
                 . ".id)=$eventid))";
        $res   = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            //echo $query;
            redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
        }

        //update date in apcal_ro_notify
        if ($typeedit == '1') {
            //delete old data in apcal_ro_notify
            $query = 'DELETE '
                     . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                     . '.* FROM '
                     . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                     . ' WHERE (('
                     . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                     . ".ron_eventid)=$eventid)";
            $res   = $GLOBALS['xoopsDB']->query($query);
        }
        if (!$email1 == '') {
            $submitter = $xoopsUser->getVar('uid');
            $query     = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter) values ($eventid, '$email1', $submitter)";
            $res       = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        if (!$email2 == '') {
            $query = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter) values ($eventid, '$email2', $submitter)";
            $res   = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        if (!$email3 == '') {
            $query = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter) values ($eventid, '$email3', $submitter)";
            $res   = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        if (!$email4 == '') {
            $query = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter) values ($eventid, '$email4', $submitter)";
            $res   = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        if (!$email5 == '') {
            $query = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter) values ($eventid, '$email5', $submitter)";
            $res   = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        redirect_header($eventurl, 3, _APCAL_RO_SUCCESS_RO_ACTIVATE);
    }
}

if (isset($_POST['deactivate_x'])) {
    if (!empty($_POST['eventid'])) {
        $eventid = $_POST['eventid'];
        $url     = $_POST['eventurl'];

        //delete data in table apcal_ro_members
        $query = 'DELETE '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . '.* FROM '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . ' WHERE (('
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . ".rom_eventid)=$eventid)";
        $res   = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            redirect_header($url, 3, _APCAL_RO_ERROR_RO_DEACTIVATE);
        }

        //delete data in table apcal_ro_notify
        $query = 'DELETE '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                 . '.* FROM '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                 . ' WHERE (('
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify')
                 . ".ron_eventid)=$eventid)";
        $res   = $GLOBALS['xoopsDB']->query($query);

        //delete data in table apcal_ro_events
        $query = 'DELETE '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                 . '.* FROM '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                 . ' WHERE (('
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                 . ".roe_eventid)=$eventid)";
        $res   = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            redirect_header($url, 3, _APCAL_RO_ERROR_RO_DEACTIVATE);
        }

        //update data in table apcal_event
        $query = 'UPDATE '
                 . $GLOBALS['xoopsDB']->prefix('apcal_event')
                 . ' SET '
                 . $GLOBALS['xoopsDB']->prefix('apcal_event')
                 . '.extkey0 = 0 WHERE ((('
                 . $GLOBALS['xoopsDB']->prefix('apcal_event')
                 . ".id)=$eventid))";
        $res   = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            redirect_header($url, 3, _APCAL_RO_ERROR_RO_DEACTIVATE);
        } else {
            //Data were correctly deleted from DB;
            redirect_header($url, 3, _APCAL_RO_SUCCESS_RO_DEACTIVATE);
        }
    }
}

if (isset($_REQUEST['form_add'])) {
    if (!empty($_REQUEST['eventid'])) {
        $eventid   = $_REQUEST['eventid'];
        $eventurl  = $_REQUEST['eventurl'];
        $summary   = $_REQUEST['summary'];
        $date      = $_REQUEST['date'];
        $location  = $_REQUEST['location'];
        $title     = '';
        $ret       = '';
        $ret2      = '';
        $classname = '';

        $title = $summary . ' (' . $date . ' ' . $location . ')';

        if (!empty($_SERVER['HTTPS'])) {
            $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } else {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        $url .= '?form_add=1';
        $url .= "&eventid=$eventid";
        $url .= "&eventurl=$eventurl";
        $url .= "&summary=$summary";
        $url .= "&date=$date";
        $url .= "&location=$location";

        //get username and email
        global $xoopsUser;
        $uname = $xoopsUser->getVar('uname');
        $email = $xoopsUser->getVar('email');
        $uid   = $xoopsUser->getVar('uid');

        $ret = "
        <table border='0' width='100%'>
            <tr><td width='100%' class='itemHead'><span class='itemTitle'>" . _APCAL_RO_TITLE1 . "</span></td></tr>
            <tr><td width='100%'>
            <form method='post' action='ro_regonlinehandler.php' name='roformaddmember' style='margin:0px;'>
                <input type='hidden' name='eventid' value='$eventid' />
                <input type='hidden' name='uid' value='$uid' />
                <input type='hidden' name='uname' value='$uname' />
                <input type='hidden' name='url' value='$url' />
                <input type='hidden' name='eventurl' value='$eventurl' />
                <input type='hidden' name='title' value='$title' />
                <input type='hidden' name='summary' value='$summary' />
                <input type='hidden' name='date' value='$date' />
                <input type='hidden' name='location' value='$location' />
                <table>
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EVENT . ":</td>
                        <td class='odd'><input type='text' name='title' disabled='disabled' value='$summary'  size='100' /></td>
                    </tr>
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_DATE . ":</td>
                        <td class='odd'><input type='text' name='date' disabled='disabled' value='$date'  size='100' /></td>
                    </tr>
                     <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_LOCATION . ":</td>
                        <td class='odd'><input type='text' name='location' disabled='disabled' value='$location'  size='100' /></td>
                    </tr>
                     <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_FIRSTNAME . "*:</td>
                        <td class='odd'><input type='text' name='firstname' value='' size='100' /></td>
                    </tr>
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_LASTNAME . "*:</td>
                        <td class='odd'><input type='text' name='lastname' value='' size='100' /></td>
                    </tr>
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EMAIL . ":</td>
                        <td class='odd'>
                            <input type='text' name='email' disabled='disabled' value='$email' size='100' />
                            <input type='hidden' name='email' value='$email' />
                            <br>" . _APCAL_RO_SEND_CONF3 . "
                            <input type='radio' name='sendconf' value='yes' checked> " . _APCAL_RO_RADIO_YES . "
                            <input type='radio' name='sendconf' value='no'> " . _APCAL_RO_RADIO_NO . '
                        </td>
                    </tr>';
        if (_APCAL_RO_EXTRAINFO1 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO1 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo1' value='' size='100' /></td>
                    </tr>";
        }
        if (_APCAL_RO_EXTRAINFO2 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO2 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo2' value='' size='100' /></td>
                    </tr>";
        }
        if (_APCAL_RO_EXTRAINFO3 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO3 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo3' value='' size='100' /></td>
                    </tr>";
        }
        if (_APCAL_RO_EXTRAINFO4 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO4 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo4' value='' size='100' /></td>
                    </tr>";
        }
        if (_APCAL_RO_EXTRAINFO5 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO5 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo5' value='' size='100' /></td>
                    </tr>";
        }
        $ret .= '
                </table>
                * ' . _APCAL_RO_OBLIGATORY . "
                <br><br>
                <div align='center'>
                    <input type='image' src='$roimagesave' name='add_member' alt='" . _APCAL_RO_BTN_CONF_ADD . "' title='" . _APCAL_RO_BTN_CONF_ADD . "' height='32px'/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <!--<input type='image' src='$roimagesavemore' name='add_member_more' alt='" . _APCAL_RO_BTN_CONF_ADD_MORE . "' title='" . _APCAL_RO_BTN_CONF_ADD_MORE . "' height='32px'/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                    <input type='image' src='$roimagecancel' name='cancel' alt='" . _APCAL_RO_BTN_CANCEL . "' title='" . _APCAL_RO_BTN_CANCEL . "' height='32px'/>
                </div>
            </form>
            </td></tr>
        </table>\n<br><br>";

        $query = 'SELECT ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . '.* ';
        $query .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_members');
        $query .= " WHERE (((rom_eventid)=$eventid) AND ((rom_submitter)=$uid))";

        $res      = $GLOBALS['xoopsDB']->query($query);
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

        if ($num_rows > 0) {
            $ret2 .= "
            <table border='0' width='100%'>
                <tr><td width='100%' class='itemHead'><span class='itemTitle'>" . _APCAL_RO_TITLE3 . "</span></td></tr>
                <tr><td width='100%'>
                <table border='1' cellpadding='0' cellspacing='0' width='100%'>
                    <tr>
                        <td class='even'>" . _APCAL_RO_FIRSTNAME . "</td>
                        <td class='even'>" . _APCAL_RO_LASTNAME . "</td>
                        <td class='even'>" . _APCAL_RO_EMAIL . '</td>';
            if (_APCAL_RO_EXTRAINFO1 != '') {
                $ret2 .= "<td class='even'>" . _APCAL_RO_EXTRAINFO1 . '</td>';
            }
            if (_APCAL_RO_EXTRAINFO2 != '') {
                $ret2 .= "<td class='even'>" . _APCAL_RO_EXTRAINFO2 . '</td>';
            }
            if (_APCAL_RO_EXTRAINFO3 != '') {
                $ret2 .= "<td class='even'>" . _APCAL_RO_EXTRAINFO3 . '</td>';
            }
            if (_APCAL_RO_EXTRAINFO4 != '') {
                $ret2 .= "<td class='even'>" . _APCAL_RO_EXTRAINFO4 . '</td>';
            }
            if (_APCAL_RO_EXTRAINFO5 != '') {
                $ret2 .= "<td class='even'>" . _APCAL_RO_EXTRAINFO5 . '</td>';
            }
            $ret2 .= "
                        <td class='even'>" . _APCAL_RO_ACTION . '</td>
                    </tr>';
            while ($member = $GLOBALS['xoopsDB']->fetchObject($res)) {
                $romfirstname  = $member->rom_firstname;
                $romlastname   = $member->rom_lastname;
                $romemail      = $member->rom_email;
                $romextrainfo1 = $member->rom_extrainfo1;
                $romextrainfo2 = $member->rom_extrainfo2;
                $romextrainfo3 = $member->rom_extrainfo3;
                $romextrainfo4 = $member->rom_extrainfo4;
                $romextrainfo5 = $member->rom_extrainfo5;
                $rom_id        = $member->rom_id;

                if ($line == 0) {
                    $classname = 'odd';
                    $line      = 1;
                } else {
                    $classname = 'even';
                    $line      = 0;
                }
                $unique_id      = uniqid(mt_rand());
                $formeditremove = "
                        <form method='post' action='ro_regonlinehandler.php' name='roformeditremovemember_" . $unique_id . "' style='margin:0px;'>
                            <input type='hidden' name='eventid' value='$eventid' />
                            <input type='hidden' name='uid' value='$uid' />
                            <input type='hidden' name='uname' value='$uname' />
                            <input type='hidden' name='url' value='$url' />
                            <input type='hidden' name='eventurl' value='$eventurl' />
                            <input type='hidden' name='summary' value='$summary' />
                            <input type='hidden' name='date' value='$date' />
                            <input type='hidden' name='location' value='$location' />
                            <input type='hidden' name='rom_id' value='$rom_id' />
                            <input type='hidden' name='firstname' value='$romfirstname' />
                            <input type='hidden' name='lastname' value='$romlastname' />
                            <input type='hidden' name='email' value='$romemail' />
                            <input type='hidden' name='extrainfo1' value='$romextrainfo1' />
                            <input type='hidden' name='extrainfo2' value='$romextrainfo2' />
                            <input type='hidden' name='extrainfo3' value='$romextrainfo3' />
                            <input type='hidden' name='extrainfo4' value='$romextrainfo4' />
                            <input type='hidden' name='extrainfo5' value='$romextrainfo5' />
                            <input type='hidden' name='num_members' value='$num_rows' />
                            <input type='image' src='$roimageedit' name='form_edit' alt='" . _APCAL_RO_BTN_EDIT . "' title='" . _APCAL_RO_BTN_EDIT . "'  height='32px' />
                            <input type='image' src='$roimagedelete' name='remove_member' alt='" . _APCAL_RO_BTN_REMOVE . "' title='" . _APCAL_RO_BTN_REMOVE . "'  height='32px' />

                        </form>";
                $ret2           .= "<tr>
                                <td class='$classname'>$romfirstname</td>
                                <td class='$classname'>$romlastname</td>
                                <td class='$classname'>$romemail</td>";
                if (_APCAL_RO_EXTRAINFO1 != '') {
                    $ret2 .= "<td class='$classname'>$romextrainfo1</td>";
                }
                if (_APCAL_RO_EXTRAINFO2 != '') {
                    $ret2 .= "<td class='$classname'>$romextrainfo2</td>";
                }
                if (_APCAL_RO_EXTRAINFO3 != '') {
                    $ret2 .= "<td class='$classname'>$romextrainfo3</td>";
                }
                if (_APCAL_RO_EXTRAINFO4 != '') {
                    $ret2 .= "<td class='$classname'>$romextrainfo4</td>";
                }
                if (_APCAL_RO_EXTRAINFO5 != '') {
                    $ret2 .= "<td class='$classname'>$romextrainfo5</td>";
                }
                $ret2 .= "
                                <td class='$classname'>$formeditremove</td>
                            </tr>";
            }
            $ret2 .= '</table></td></tr></table>';
            $ret2 .= "<p style='text-align:center;align:center'>
            <form method='post' action='ro_regonlinehandler.php' name='roformgoback' style='margin:0px;'>
                <input type='hidden' name='eventurl' value='$eventurl' />
                <div align='center'>
                <input type='image' src='$roimagecancel' name='goback' alt='" . _APCAL_RO_BTN_BACK . "' title='" . _APCAL_RO_BTN_BACK . "' height='32px'/>
                </div>
            </form></p>\n";
            $ret2 .= '<br><br>';
        } else {
            $ret2 = '';
        }

        echo $ret2 . $ret;
    }
}

if (isset($_POST['add_member_x']) || isset($_POST['add_member_more_x'])) {
    if (!empty($_POST['eventid'])) {
        $uid        = $_POST['uid'];
        $url        = $_POST['url'];
        $eventurl   = $_POST['eventurl'];
        $uname      = $_POST['uname'];
        $eventid    = $_POST['eventid'];
        $firstname  = $_POST['firstname'];
        $lastname   = $_POST['lastname'];
        $email      = $_POST['email'];
        $extrainfo1 = $_POST['extrainfo1'];
        $extrainfo2 = $_POST['extrainfo2'];
        $extrainfo3 = $_POST['extrainfo3'];
        $extrainfo4 = $_POST['extrainfo4'];
        $extrainfo5 = $_POST['extrainfo5'];
        $summary    = $_POST['summary'];
        $date       = $_POST['date'];
        $location   = $_POST['location'];
        $sendconf   = $_POST['sendconf'];

        if ($firstname == '') {
            $firstname = '-';
        }
        if ($lastname == '') {
            $lastname = '-';
        }
        if ($email == '') {
            $email = '-';
        }
        if ($extrainfo1 == '') {
            $extrainfo1 = '-';
        }
        if ($extrainfo2 == '') {
            $extrainfo2 = '-';
        }
        if ($extrainfo3 == '') {
            $extrainfo3 = '-';
        }
        if ($extrainfo4 == '') {
            $extrainfo4 = '-';
        }
        if ($extrainfo5 == '') {
            $extrainfo5 = '-';
        }

        //read data from apcal_ro_events
        $query    = 'SELECT '
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                    . '.roe_number, roe_datelimit FROM '
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                    . ' WHERE (('
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                    . ".roe_eventid)=$eventid)";
        $res      = $GLOBALS['xoopsDB']->query($query);
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);
        if ($num_rows == 0) {
            $number_allowed = 0;
        } else {
            while ($ro_result = $GLOBALS['xoopsDB']->fetchObject($res)) {
                $number_allowed = $ro_result->roe_number;
                $datelimit      = $ro_result->roe_datelimit;
            }
        }
        //check limit date expired
        if ($datelimit > 0) {
            $datenow = strtotime(date('d.m.Y H:i:s'));
            if ($datelimit < $datenow) {
                redirect_header($url, 3, _APCAL_RO_ERROR_TIMEOUT);
            }
        }
        //check limit number registrations
        if ($number_allowed > 0) {
            //get existing registrations
            $query    = 'SELECT '
                        . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                        . '.rom_id FROM '
                        . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                        . ' WHERE (('
                        . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                        . ".rom_eventid)=$eventid)";
            $res      = $GLOBALS['xoopsDB']->query($query);
            $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);
            if ($num_rows == 0) {
                $number_total = 0;
            } else {
                $number_total = $GLOBALS['xoopsDB']->getRowsNum($res);
            }

            if ($number_total >= $number_allowed) {
                redirect_header($url, 5, _APCAL_RO_ERROR_FULL);
            }
        }

        $confirmto = $email;
        // check whether email is available and confirmation is selected
        if ($confirmto == '') {
            $confirmto = '-';
        }
        if ($sendconf == 'no') {
            $confirmto = '-';
        }

        $query = 'Insert into '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . " (rom_submitter, rom_eventid, rom_firstname, rom_lastname, rom_email, rom_extrainfo1, rom_extrainfo2, rom_extrainfo3, rom_extrainfo4, rom_extrainfo5, rom_date_created) values ($uid, $eventid, '$firstname', '$lastname', '$email', '$extrainfo1', '$extrainfo2', '$extrainfo3', '$extrainfo4', '$extrainfo5', "
                 . time()
                 . ' )';
        $res   = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            redirect_header($url, 3, _APCAL_RO_ERROR_ADD);
        } else {
            //send email of responsible persons
            $query    = 'SELECT ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . '.* ';
            $query    .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify');
            $query    .= " WHERE (((ron_eventid)=$eventid))";
            $res      = $GLOBALS['xoopsDB']->query($query);
            $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);
            if ($num_rows == 0) {
                //nothing to do
            } else {
                while ($member = $GLOBALS['xoopsDB']->fetchObject($res)) {
                    $xoopsMailer = xoops_getMailer();
                    $xoopsMailer->useMail();
                    //set template path
                    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/')) {
                        $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template/');
                    } else {
                        $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/mail_template/');
                    }
                    //set template name
                    $xoopsMailer->setTemplate('ro_notify_in.tpl');
                    //set sender
                    $xoopsMailer->setFromEmail($mail_sender);
                    //set name of sender
                    $xoopsMailer->setFromName($mail_sendername);
                    //set subject
                    $subject = _APCAL_RO_MAIL_SUBJ_ADD;
                    $xoopsMailer->setSubject($subject);
                    //assign vars in template
                    $xoopsMailer->assign('UNAME', $uname);
                    $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                    $xoopsMailer->assign('SUMMARY', $summary);
                    $xoopsMailer->assign('DATE', $date);
                    $xoopsMailer->assign('LOCATION', $location);
                    $xoopsMailer->assign('URL', $eventurl);
                    $xoopsMailer->assign('SIGNATURE', $mail_signature);
                    //set recipient
                    $recipient = $member->ron_email;
                    $xoopsMailer->setToEmails($recipient);

                    //execute sending
                    $xoopsMailer->send();
                    $xoopsMailer->reset();
                }
            }

            //confirmation mail to registered person
            if ($confirmto == '-') {
                //echo "option not selected or no email-address available";
            } else {
                $xoopsMailer = xoops_getMailer();
                $xoopsMailer->useMail();
                //set template path
                if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/')) {
                    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template/');
                } else {
                    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/mail_template/');
                }
                //set template name
                $xoopsMailer->setTemplate('ro_confirm_in.tpl');
                //set sender
                $xoopsMailer->setFromEmail($mail_sender);
                //set sender name
                $xoopsMailer->setFromName($mail_sendername);
                //set subject
                $subject = _APCAL_RO_MAIL_SUBJ_ADD;
                $xoopsMailer->setSubject($subject);
                //assign vars
                $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                $xoopsMailer->assign('SUMMARY', $summary);
                $xoopsMailer->assign('DATE', $date);
                $xoopsMailer->assign('LOCATION', $location);
                $xoopsMailer->assign('URL', $eventurl);
                $xoopsMailer->assign('SIGNATURE', $mail_signature);
                //set recipient
                $xoopsMailer->setToEmails($confirmto);

                //execute sending
                $xoopsMailer->send();
                $xoopsMailer->reset();
            }

            if (isset($_POST['add_member_more_x'])) {
                redirect_header($url, 3, _APCAL_RO_SUCCESS_ADD);
            } else {
                redirect_header($eventurl, 3, _APCAL_RO_SUCCESS_ADD);
            }
        }
    }
}

if (isset($_POST['remove_member']) || isset($_POST['remove_member_x'])) {
    if (!empty($_POST['rom_id'])) {
        $rom_id      = $_POST['rom_id'];
        $url         = $_POST['url'];
        $eventurl    = $_POST['eventurl'];
        $uid         = $_POST['uid'];
        $uname       = $_POST['uname'];
        $eventid     = $_POST['eventid'];
        $title       = $_POST['title'];
        $firstname   = $_POST['firstname'];
        $lastname    = $_POST['lastname'];
        $confirmto   = $_POST['email'];
        $summary     = $_POST['summary'];
        $date        = $_POST['date'];
        $location    = $_POST['location'];
        $num_members = $_POST['num_members'];

        // check whether confirmation mail should be send
        if ($confirmto == '') {
            $confirmto = '-';
        }

        $query = 'DELETE '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . '.* FROM '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . ' WHERE (('
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . ".rom_id)=$rom_id)";

        $res = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            redirect_header($url, 3, _APCAL_RO_ERROR_REMOVE);
        } else {
            //data was correctly deleted from DB;
            //send mail to responsible person
            $query = 'SELECT ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . '.* ';
            $query .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify');
            $query .= " WHERE (((ron_eventid)=$eventid))";

            $res      = $GLOBALS['xoopsDB']->query($query);
            $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);
            if ($num_rows == 0) {
                //nothing to do
            } else {
                while ($member = $GLOBALS['xoopsDB']->fetchObject($res)) {
                    $xoopsMailer = xoops_getMailer();
                    $xoopsMailer->useMail();
                    //set template path
                    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/')) {
                        $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template/');
                    } else {
                        $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/mail_template/');
                    }
                    //set template name
                    $xoopsMailer->setTemplate('ro_notify_out.tpl');
                    //set sender
                    $xoopsMailer->setFromEmail($mail_sender);
                    //set sender name
                    $xoopsMailer->setFromName($mail_sendername);
                    //set subject
                    $subject = _APCAL_RO_MAIL_SUBJ_REMOVE;
                    $xoopsMailer->setSubject($subject);
                    //assign vars
                    $xoopsMailer->assign('UNAME', $uname);
                    $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                    $xoopsMailer->assign('SUMMARY', $summary);
                    $xoopsMailer->assign('DATE', $date);
                    $xoopsMailer->assign('LOCATION', $location);
                    $xoopsMailer->assign('URL', $eventurl);
                    $xoopsMailer->assign('SIGNATURE', $mail_signature);
                    //set recipient
                    $recipient = $member->ron_email;
                    $xoopsMailer->setToEmails($recipient);

                    //execute sending
                    $xoopsMailer->send();
                    $xoopsMailer->reset();
                }
            }

            //confirmation mail to registered person
            if ($confirmto == '-') {
                //echo "option not selected or no email-address available";
            } else {
                $xoopsMailer = xoops_getMailer();
                $xoopsMailer->useMail();
                //set template path
                if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/')) {
                    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template/');
                } else {
                    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/mail_template/');
                }
                //set template name
                $xoopsMailer->setTemplate('ro_confirm_out.tpl');
                //set sender
                $sender = $mail_sender;
                $xoopsMailer->setFromEmail($sender);
                //set sender name
                $xoopsMailer->setFromName($mail_sendername);
                //set subject
                $subject = _APCAL_RO_MAIL_SUBJ_REMOVE;
                $xoopsMailer->setSubject($subject);
                //assign vars
                $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                $xoopsMailer->assign('SUMMARY', $summary);
                $xoopsMailer->assign('DATE', $date);
                $xoopsMailer->assign('LOCATION', $location);
                $xoopsMailer->assign('URL', $eventurl);
                $xoopsMailer->assign('SIGNATURE', $mail_signature);
                //set recipient
                $xoopsMailer->setToEmails($confirmto);
                //execute sending
                $xoopsMailer->send();
                $xoopsMailer->reset();
            }
            if ($num_members == 1) {
                redirect_header($eventurl, 3, _APCAL_RO_SUCCESS_REMOVE);
            } else {
                redirect_header($url, 3, _APCAL_RO_SUCCESS_REMOVE);
            }
        }
    }
}

if (isset($_REQUEST['list'])) {
    if (!empty($_REQUEST['eventid'])) {
        $uid       = $_REQUEST['uid'];
        $eventid   = $_REQUEST['eventid'];
        $summary   = $_REQUEST['summary'];
        $date      = $_REQUEST['date'];
        $location  = $_REQUEST['location'];
        $eventurl  = $_REQUEST['eventurl'];
        $classname = '';

        if (!empty($_SERVER['HTTPS'])) {
            $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } else {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        $url .= '?list=1';
        $url .= "&uid=$uid";
        $url .= "&eventid=$eventid";
        $url .= "&summary=$summary";
        $url .= "&date=$date";
        $url .= "&location=$location";
        $url .= "&eventurl=$eventurl";

        $title = $summary . ' (' . $date . ' ' . $location . ')';
        $query = 'SELECT '
                 . $GLOBALS['xoopsDB']->prefix('users')
                 . '.uname, '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . '.* FROM '
                 . $GLOBALS['xoopsDB']->prefix('users')
                 . ' INNER JOIN '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . ' ON '
                 . $GLOBALS['xoopsDB']->prefix('users')
                 . '.uid = '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . '.rom_submitter WHERE ((('
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . ".rom_eventid)=$eventid)) ORDER BY "
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . '.rom_date_created';

        $res      = $GLOBALS['xoopsDB']->query($query);
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

        if ($num_rows == 0) {
            $ret = _APCAL_RO_NOMEMBERS;
        } else {
            $ret .= "
           <table border='1' cellpadding='0' cellspacing='0' width='100%'>
             <tr>
               <td width='100px' class='listeheader'>" . _APCAL_RO_UNAME . "</td>
               <td width='100px' class='listeheader'>" . _APCAL_RO_FIRSTNAME . "</td>
               <td width='100px' class='listeheader'>" . _APCAL_RO_LASTNAME . "</td>
               <td class='listeheader'>" . _APCAL_RO_EMAIL . '</td>';
            if (!_APCAL_RO_EXTRAINFO1 == '') {
                $ret .= "<td class='listeheader'>" . _APCAL_RO_EXTRAINFO1 . '</td>';
            }
            if (!_APCAL_RO_EXTRAINFO2 == '') {
                $ret .= "<td class='listeheader'>" . _APCAL_RO_EXTRAINFO2 . '</td>';
            }
            if (!_APCAL_RO_EXTRAINFO3 == '') {
                $ret .= "<td class='listeheader'>" . _APCAL_RO_EXTRAINFO3 . '</td>';
            }
            if (!_APCAL_RO_EXTRAINFO4 == '') {
                $ret .= "<td class='listeheader'>" . _APCAL_RO_EXTRAINFO4 . '</td>';
            }
            if (!_APCAL_RO_EXTRAINFO5 == '') {
                $ret .= "<td class='listeheader'>" . _APCAL_RO_EXTRAINFO5 . '</td>';
            }
            $ret .= "
               <td class='listeheader'>" . _APCAL_RO_ACTION . '</td>
             </tr>';
            while ($member = $GLOBALS['xoopsDB']->fetchObject($res)) {
                $rom_id     = $member->rom_id;
                $uname      = $member->uname;
                $firstname  = $member->rom_firstname;
                $lastname   = $member->rom_lastname;
                $email      = $member->rom_email;
                $extrainfo1 = $member->rom_extrainfo1;
                $extrainfo2 = $member->rom_extrainfo2;
                $extrainfo3 = $member->rom_extrainfo3;
                $extrainfo4 = $member->rom_extrainfo4;
                $extrainfo5 = $member->rom_extrainfo5;
                if ($line == 0) {
                    $classname = 'odd';
                    $line      = 1;
                } else {
                    $classname = 'even';
                    $line      = 0;
                }
                $ret .= "<tr>
                    <td class='$classname'>$uname</td>
                    <td class='$classname'>$firstname</td>
                    <td class='$classname'>$lastname</td>
                    <td class='$classname'>$email</td>";
                if (!_APCAL_RO_EXTRAINFO1 == '') {
                    $ret .= "<td class='$classname'>$extrainfo1</td>";
                }
                if (!_APCAL_RO_EXTRAINFO2 == '') {
                    $ret .= "<td class='$classname'>$extrainfo2</td>";
                }
                if (!_APCAL_RO_EXTRAINFO3 == '') {
                    $ret .= "<td class='$classname'>$extrainfo3</td>";
                }
                if (!_APCAL_RO_EXTRAINFO4 == '') {
                    $ret .= "<td class='$classname'>$extrainfo4</td>";
                }
                if (!_APCAL_RO_EXTRAINFO5 == '') {
                    $ret .= "<td class='$classname'>$extrainfo5</td>";
                }
                $ret       .= "<td class='$classname'>";
                $unique_id = uniqid(mt_rand());
                $ret       .= "
                    <form method='post' action='ro_regonlinehandler.php' name='roformlist_" . $unique_id . "' style='margin:0px;'>
                        <input type='hidden' name='url' value='$url' />
                        <input type='hidden' name='rom_id' value='$rom_id' />
                        <input type='hidden' name='firstname' value='$firstname' />
                        <input type='hidden' name='lastname' value='$lastname' />
                        <input type='hidden' name='email' value='$email' />
                        <input type='hidden' name='extrainfo1' value='$extrainfo1' />
                        <input type='hidden' name='extrainfo2' value='$extrainfo2' />
                        <input type='hidden' name='extrainfo3' value='$extrainfo3' />
                        <input type='hidden' name='extrainfo4' value='$extrainfo4' />
                        <input type='hidden' name='extrainfo5' value='$extrainfo5' />
                        <input type='hidden' name='num_members' value='$num_rows' />
                        <div style='display:inline'>
                            <input type='image' src='$roimageedit' name='form_edit' alt='" . _APCAL_RO_BTN_EDIT . "' title='" . _APCAL_RO_BTN_EDIT . "'  height='22px' />
                            <input type='image' src='$roimagedelete' name='remove_member' alt='" . _APCAL_RO_BTN_REMOVE . "' title='" . _APCAL_RO_BTN_REMOVE . "'  height='22px' />
                        </div>
                    </form>
                    </td>
                </tr>";
            }
            $ret .= "</table>\n<br>";

            $ret .= "
            <form method='post' action='ro_regonlinehandler.php' name='roformgoback' style='margin:0px;'>
                <input type='hidden' name='eventurl' value='$eventurl' />
                <div align='center'>
                <input type='image' src='$roimagecancel' name='goback' alt='" . _APCAL_RO_BTN_BACK . "' title='" . _APCAL_RO_BTN_BACK . "' height='32px'/>
                </div>
            </form>\n";

            $query = 'SELECT ' . $GLOBALS['xoopsDB']->prefix('users') . '.email ';
            $query .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('users');
            $query .= ' WHERE (((' . $GLOBALS['xoopsDB']->prefix('users') . ".uid)=$uid))";

            $res      = $GLOBALS['xoopsDB']->query($query);
            $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

            if ($num_rows == 0) {
                $sender = '';
            } else {
                while ($member = $GLOBALS['xoopsDB']->fetchObject($res)) {
                    $sender = $member->email;
                }
            }
            $mailtext = _APCAL_RO_EVENT . ": $summary\n" . _APCAL_RO_DATE . ": $date\n" . _APCAL_RO_LOCATION . ": $location\n" . _APCAL_RO_LINK . ": $eventurl\n\n";
            $ret      .= "
            <br><br><br>
            <table border='1' cellpadding='0' cellspacing='0' width='100%'>
                <tr>
                    <td class='listeheader'>" . _APCAL_RO_TITLE4 . "</td>
                </tr>
            </table>
            <form method='post' action='ro_regonlinehandler.php' name='roformsendmail' accept-charset='UTF-8'>
            <table border='1' width='100%'>
                <tr>
                    <td class='even' width='100px'>" . _APCAL_RO_MAIL_SENDER . ":</td>
                    <td class='odd'><input type='text' name='sender' size='70' value='$sender'></td>
                </tr>
                <tr>
                    <td class='even' width='100px'>" . _APCAL_RO_MAIL_SUBJ . ":</td>
                    <td class='odd'><input type='text' name='subject' size='70' value='" . _APCAL_RO_MAIL_SUBJ_TEXT . "'></td>
                </tr>
                <tr>
                    <td class='even' width='200px'>" . _APCAL_RO_MAIL_BODY1 . ":<br><br><font size='1'>" . _APCAL_RO_MAIL_BODY2 . "</font></td>
                    <td class='odd' height='200px' valign='top'>
                    <textarea rows='25' name='mailtext' cols='95'>$mailtext</textarea></td>
              </tr>
            </table>
                <input type='hidden' name='url' value='$url' />
                <input type='hidden' name='eventurl' value='$eventurl' />
                <input type='hidden' name='eventid' value='$eventid' />
                <p style='text-align:center;align:center'><input type='image' src='$roimagesend' name='ro_notify_all' alt='" . _APCAL_RO_BTN_SEND . "' title='" . _APCAL_RO_BTN_SEND . "' height='32px'/></p>
            </form>
            \n";
        }
        echo $ret;
    }
}

if (isset($_POST['form_edit']) || isset($_POST['form_edit_x'])) {
    if (!empty($_POST['rom_id'])) {
        $rom_id     = $_POST['rom_id'];
        $uid        = $_POST['uid'];
        $url        = $_POST['url'];
        $eventurl   = $_POST['eventurl'];
        $uname      = $_POST['uname'];
        $eventid    = $_POST['eventid'];
        $firstname  = $_POST['firstname'];
        $lastname   = $_POST['lastname'];
        $email      = $_POST['email'];
        $extrainfo1 = $_POST['extrainfo1'];
        $extrainfo2 = $_POST['extrainfo2'];
        $extrainfo3 = $_POST['extrainfo3'];
        $extrainfo4 = $_POST['extrainfo4'];
        $extrainfo5 = $_POST['extrainfo5'];
        $summary    = $_POST['summary'];
        $date       = $_POST['date'];
        $location   = $_POST['location'];
        $sendconf   = $_POST['sendconf'];

        $ret  = '';
        $ret2 = '';

        $ret = "
        <table border='0' width='100%'>
            <tr><td width='100%' class='itemHead'><span class='itemTitle'>" . _APCAL_RO_TITLE5 . "</span></td></tr>
            <tr><td width='100%'>
            <form method='post' action='ro_regonlinehandler.php' name='roformeditmember' style='margin:0px;'>
                <input type='hidden' name='url' value='$url' />
                <input type='hidden' name='rom_id' value='$rom_id' />

                <table>
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_FIRSTNAME . "*:</td>
                        <td class='odd'><input type='text' name='firstname' value='$firstname' size='100' /></td>
                    </tr>
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_LASTNAME . "*:</td>
                        <td class='odd'><input type='text' name='lastname' value='$lastname' size='100' /></td>
                    </tr>
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EMAIL . ":</td>
                        <td class='odd'><input type='text' name='email' value='$email' size='100' /></td>
                    </tr>";
        if (_APCAL_RO_EXTRAINFO1 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO1 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo1' value='$extrainfo1' size='100' /></td>
                    </tr>";
        }
        if (_APCAL_RO_EXTRAINFO2 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO2 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo2' value='$extrainfo2' size='100' /></td>
                    </tr>";
        }
        if (_APCAL_RO_EXTRAINFO3 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO3 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo3' value='$extrainfo3' size='100' /></td>
                    </tr>";
        }
        if (_APCAL_RO_EXTRAINFO4 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO4 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo4' value='$extrainfo4' size='100' /></td>
                    </tr>";
        }
        if (_APCAL_RO_EXTRAINFO5 != '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . _APCAL_RO_EXTRAINFO5 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo5' value='$extrainfo5' size='100' /></td>
                    </tr>";
        }
        $ret .= '
                </table>
                * ' . _APCAL_RO_OBLIGATORY . "
                <br><br>
                <div align='center'>
                    <input type='image' src='$roimagesave' name='edit_member' alt='" . _APCAL_RO_BTN_CONF_EDIT . "' title='" . _APCAL_RO_BTN_CONF_EDIT . "' height='32px'/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='image' src='$roimagecancel' name='cancel' alt='" . _APCAL_RO_BTN_CANCEL . "' title='" . _APCAL_RO_BTN_CANCEL . "' height='32px'/>
                </div>
            </form>
            </td></tr>
        </table>\n<br><br>";

        echo $ret;
    }
}

if (isset($_POST['edit_member']) || isset($_POST['edit_member_x'])) {
    if (!empty($_POST['rom_id'])) {
        $rom_id     = $_POST['rom_id'];
        $uid        = $_POST['uid'];
        $url        = $_POST['url'];
        $eventurl   = $_POST['eventurl'];
        $uname      = $_POST['uname'];
        $eventid    = $_POST['eventid'];
        $firstname  = $_POST['firstname'];
        $lastname   = $_POST['lastname'];
        $email      = $_POST['email'];
        $extrainfo1 = $_POST['extrainfo1'];
        $extrainfo2 = $_POST['extrainfo2'];
        $extrainfo3 = $_POST['extrainfo3'];
        $extrainfo4 = $_POST['extrainfo4'];
        $extrainfo5 = $_POST['extrainfo5'];
        $summary    = $_POST['summary'];
        $date       = $_POST['date'];
        $location   = $_POST['location'];
        $sendconf   = $_POST['sendconf'];

        if ($firstname == '') {
            $firstname = '-';
        }
        if ($lastname == '') {
            $lastname = '-';
        }
        if ($email == '') {
            $email = '-';
        }
        if ($extrainfo1 == '') {
            $extrainfo1 = '-';
        }
        if ($extrainfo2 == '') {
            $extrainfo2 = '-';
        }
        if ($extrainfo3 == '') {
            $extrainfo3 = '-';
        }
        if ($extrainfo4 == '') {
            $extrainfo4 = '-';
        }
        if ($extrainfo5 == '') {
            $extrainfo5 = '-';
        }

        $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ' SET ';
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_firstname = '$firstname', ";
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_lastname = '$lastname', ";
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_email = '$email', ";
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_extrainfo1 = '$extrainfo1', ";
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_extrainfo2 = '$extrainfo2', ";
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_extrainfo3 = '$extrainfo3', ";
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_extrainfo4 = '$extrainfo4', ";
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_extrainfo5 = '$extrainfo5' ";
        $query .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_id)='$rom_id'))";

        $res = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            redirect_header($url, 3, _APCAL_RO_ERROR_EDIT);
        } else {
            redirect_header($url, 3, _APCAL_RO_SUCCESS_EDIT);
        }
    }
}

if (isset($_POST['cancel']) || isset($_POST['cancel_x'])) {
    if (!empty($_POST['eventurl'])) {
        $url = $_POST['eventurl'];
        redirect_header($url, 1, _APCAL_RO_CANCEL);
    }
    if (!empty($_POST['url'])) {
        $url = $_POST['url'];
        redirect_header($url, 1, _APCAL_RO_CANCEL);
    }
}
if (isset($_POST['goback']) || isset($_POST['goback_x'])) {
    if (!empty($_POST['eventurl'])) {
        $url = $_POST['eventurl'];
        redirect_header($url, 0, _APCAL_RO_BACK);
    }
}

if (isset($_POST['ro_notify_all']) || isset($_POST['ro_notify_all_x'])) {
    if (!empty($_POST['url'])) {
        $url      = $_POST['url'];
        $eventurl = $_POST['eventurl'];
        $eventid  = $_POST['eventid'];
        $sender   = $_POST['sender'];
        $subject  = $_POST['subject'];
        $mailtext = $_POST['mailtext'];
        $counter  = 0;

        //$subject = utf8_encode($subject);
        //$mailtext = utf8_encode($mailtext);

        $query = 'SELECT '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . '.rom_email, rom_firstname, rom_lastname FROM '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . ' WHERE ((('
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . ".rom_eventid)=$eventid) AND not(rom_email is null))";

        $res      = $GLOBALS['xoopsDB']->query($query);
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

        if ($num_rows == 0) {
            //no action
        } else {
            while ($member = $GLOBALS['xoopsDB']->fetchObject($res)) {
                $recipient = $member->rom_email;
                $firstname = $member->rom_firstname;
                $lastname  = $member->rom_lastname;

                if ($recipient != '-') {
                    ++$counter;

                    $xoopsMailer = xoops_getMailer();
                    $xoopsMailer->useMail();
                    //set template path
                    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/')) {
                        $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/mail_template/');
                    } else {
                        $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/mail_template/');
                    }
                    //set template name
                    $xoopsMailer->setTemplate('ro_notify_all.tpl');
                    //set sender
                    $xoopsMailer->setFromEmail($sender); //take email from inputbox
                    //set sender name
                    $xoopsMailer->setFromName($mail_sendername);
                    //set subject
                    $xoopsMailer->setSubject($subject);
                    //assign vars
                    $xoopsMailer->assign('MAILTEXT', $mailtext);
                    $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                    $xoopsMailer->assign('SUMMARY', $summary);
                    $xoopsMailer->assign('DATE', $date);
                    $xoopsMailer->assign('LOCATION', $location);
                    $xoopsMailer->assign('URL', $eventurl);
                    $xoopsMailer->assign('SIGNATURE', $mail_signature);
                    //set recipient
                    $xoopsMailer->setToEmails($recipient);

                    //execute sending
                    $xoopsMailer->send();
                    $xoopsMailer->reset();
                }
            }
        }

        redirect_header($url, 3, $counter . _APCAL_RO_MAILSENT);
    }
}

require XOOPS_ROOT_PATH . '/footer.php';
