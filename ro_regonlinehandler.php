<?php

use Xmf\Request;

require_once __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
//XoopsMailer
require_once XOOPS_ROOT_PATH . '/class/xoopsmailer.php';
require_once XOOPS_ROOT_PATH . '/modules/apcal/language/' . $GLOBALS['xoopsConfig']['language'] . '/apcal_constants.php';

$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="' . XOOPS_URL . '/modules/apcal/assets/css/apcal.css" />' . $xoopsTpl->get_template_vars('xoops_module_header'));

//read module preferences
require_once XOOPS_ROOT_PATH."/modules/apcal/class/APCal.php";
$cal = new APCal();
include_once XOOPS_ROOT_PATH.'/modules/apcal/include/read_configs.php';

//images
$roimageedit          = XOOPS_URL . '/modules/apcal/assets/images/regonline/edit.png';
$roimagedelete        = XOOPS_URL . '/modules/apcal/assets/images/regonline/delete.png';
$roimagesave          = XOOPS_URL . '/modules/apcal/assets/images/regonline/save.png';
$roimagesavemore      = XOOPS_URL . '/modules/apcal/assets/images/regonline/savemore.png';
$roimagecancel        = XOOPS_URL . '/modules/apcal/assets/images/regonline/cancel.png';
$roimagesend          = XOOPS_URL . '/modules/apcal/assets/images/regonline/sendmail.png';
$roimageprint         = XOOPS_URL . '/modules/apcal/assets/images/regonline/print.png';
$roimagestatusok      = XOOPS_URL . '/modules/apcal/assets/images/regonline/status_ok.png';
$roimagestatuslist    = XOOPS_URL . '/modules/apcal/assets/images/regonline/status_list.png';
$roimagestatuspending = XOOPS_URL . '/modules/apcal/assets/images/regonline/status_pend.png';
$roimagedownload      = XOOPS_URL . '/modules/apcal/assets/images/regonline/download.png';

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
    if ($_GET['op'] === 'show_form_activate') {
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
        $email1      = $xoopsUser->getVar('email');
        $datelimit   = $eventdate;
        $number      = 0;
        $waitinglist = 1;
        $needconfirm = 0;
        $typeedit    = 0; //new
    } else {
        while ($ro_result = $GLOBALS['xoopsDB']->fetchObject($res)) {
            $roeid       = $ro_result->roe_id;
            $number      = (int)$ro_result->roe_number;
            $datelimit   = (int)$ro_result->roe_datelimit;
            $waitinglist = ($number > 0) ? (int)$ro_result->roe_waitinglist : 0;
            $needconfirm = (int)$ro_result->roe_needconfirm;
            $typeedit    = 1; //edit
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
        <form class='apcalForm' method='post' id='RegOnlineForm' action='ro_regonlinehandler.php' name='roformactivate' style='margin:0px;'>
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
                </tr>";
    $ret .= "
                    <tr>
                        <td class='even' width='300px'>" . _APCAL_RO_STATUS_ACT. ":</td>
                        <td class='odd'>
                            <input id='needconfirm1' type='radio' value='1'";
                            if ($needconfirm ==1) $ret .=" checked='checked'";
                            $ret .=" title='" . _APCAL_RO_STATUS_ACT . "' name='needconfirm' />
                            <label for='needconfirm1' name='xolb_needconfirm1'>"._APCAL_RO_RADIO_YES."</label>
                            <input id='needconfirm2' type='radio' value='0'";
                            if ($needconfirm == 0) $ret .=" checked='checked'";
                            $ret .=" title='" . _APCAL_RO_STATUS_ACT . "' name='needconfirm' />
                            <label for='needconfirm2' name='xolb_needconfirm2'>"._APCAL_RO_RADIO_NO."</label>
                        </td>
                    </tr>";
    $ret .= "
                    <tr>
                        <td class='even' width='300px'>" . _APCAL_RO_LIST_ACT . ":</td>
                        <td class='odd'>
                            <input id='waitinglist1' type='radio' value='1'";
                        if ($waitinglist == 1) $ret .=" checked='checked'";
                        $ret .=" title='" . _APCAL_RO_LIST_ACT . "' name='waitinglist' />
                        <label for='waitinglist1' name='xolb_waitinglist1'>"._APCAL_RO_RADIO_YES."</label>
                            <input id='waitinglist2' type='radio' value='0'";
                        if ($waitinglist == 0) $ret .=" checked='checked'";
                        $ret .=" title='" . _APCAL_RO_LIST_ACT . "' name='waitinglist' />
                        <label for='waitinglist2' name='xolb_waitinglist2'>"._APCAL_RO_RADIO_NO."</label>
                        </td>
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

    if ($typeedit == 0) {
        $ret .= "<input type='image' src='$roimagesave' name='activate' alt='" . _APCAL_RO_BTN_CONF_SAVE . "' title='" . _APCAL_RO_BTN_CONF_SAVE . "' height='24px'/>";
    } else {
        $ret .= "<input type='image' src='$roimagesave' name='activate' alt='" . _APCAL_RO_BTN_CONF_EDIT . "' title='" . _APCAL_RO_BTN_CONF_EDIT . "' height='24px'/>";
    }
    $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $ret .= "<input type='image' src='$roimagedelete' name='deactivate' alt='" . _APCAL_RO_BTN_RO_DEACTIVATE . "' title='" . _APCAL_RO_BTN_RO_DEACTIVATE . "' height='24px'/>";
    $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $ret .= "<input type='image' src='$roimagecancel' name='cancel' alt='" . _APCAL_RO_BTN_CANCEL . "' title='" . _APCAL_RO_BTN_CANCEL . "' height='24px'/>";
    $ret .= "</div>
        </form>
        </td></tr>
    </table>
    \n";
    echo $ret;
}

if (isset($_POST['activate_x'])) {
    if (!empty($_POST['eventid'])) {
        $uid         = Request::getInt('uid');
        $eventid     = Request::getInt('eventid');
        $eventurl    = Request::getString('eventurl', '');
        $datelimit   = Request::getString('datelimit', '');
        $number      = Request::getInt('number');
        $needconfirm = Request::getInt('needconfirm');
        $waitinglist = ($number > 0 ) ? Request::getInt('waitinglist') : 0;
        $email1      = Request::getString('email1', '');
        $email2      = Request::getString('email2', '');
        $email3      = Request::getString('email3', '');
        $email4      = Request::getString('email4', '');
        $email5      = Request::getString('email5', '');
        $typeedit    = Request::getInt('typeedit');

        if ($datelimit === '') {
            $datelimit = 0;
        } else {
            $datelimit = strtotime($datelimit);
        }

        //insert or update data in table apcal_ro_events
        if ($typeedit == 0) {
            $query = 'Insert into '
                     . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                     . " (roe_submitter, roe_eventid, roe_datelimit, roe_number, roe_needconfirm, roe_waitinglist, roe_date_created) values ($uid, $eventid, $datelimit, $number, $needconfirm, $waitinglist, "
                     . time()
                     . ')';
        } else {
            $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ' SET ';
            $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ".roe_submitter = $uid, ";
            $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ".roe_datelimit = $datelimit, ";
            $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ".roe_number = $number, ";
            $query .= $GLOBALS['xoopsDB']->prefix("apcal_ro_events") . ".roe_needconfirm = $needconfirm, ";
            $query .= $GLOBALS['xoopsDB']->prefix("apcal_ro_events") . ".roe_waitinglist = $waitinglist, ";
            $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . '.roe_date_created = ' . time() . ' ';
            $query .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('apcal_ro_events') . ".roe_eventid)=$eventid))";
        }
        $res = $GLOBALS['xoopsDB']->query($query);
        if (!$res) {
            redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
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
        if ($typeedit == 1) {
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
        if ($email1 !== '') {
            $submitter = $xoopsUser->getVar('uid');
            $query     = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter, ron_date_created) values ($eventid, '$email1', $submitter, ". time().")";
            $res       = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        if ($email2 !== '') {
            $query = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter, ron_date_created) values ($eventid, '$email2', $submitter, ". time().")";
            $res   = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        if ($email3 !== '') {
            $query = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter, ron_date_created) values ($eventid, '$email3', $submitter, ". time().")";
            $res   = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        if ($email4 !== '') {
            $query = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter, ron_date_created) values ($eventid, '$email4', $submitter, ". time().")";
            $res   = $GLOBALS['xoopsDB']->query($query);
            if (!$res) {
                redirect_header($eventurl, 3, _APCAL_RO_ERROR_RO_ACTIVATE);
            }
        }
        if ($email5 !== '') {
            $query = 'Insert into ' . $GLOBALS['xoopsDB']->prefix('apcal_ro_notify') . " (ron_eventid, ron_email, ron_submitter, ron_date_created) values ($eventid, '$email5', $submitter, ". time().")";
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
        $eventid = Request::getInt('eventid');
        $url     = Request::getString('eventurl', '');

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
        $eventid   = Request::getInt('eventid');
        $eventurl  = Request::getString('eventurl', '');
        $summary   = Request::getString('summary', '');
        $date      = Request::getString('date');
        $eventdate = Request::getInt('eventdate');
        $location  = Request::getString('location', '');
        $title     = '';
        $ret       = '';
        $retList      = '';
        $classname = '';
        $event_uid = Request::getInt('event_uid');
        
        $firstname  = Request::getString('firstname', '');
        $lastname   = Request::getString('lastname', '');
        $email      = Request::getString('email', '');
        $extrainfo1 = Request::getString('extrainfo1', '');
        $extrainfo2 = Request::getString('extrainfo2', '');
        $extrainfo3 = Request::getString('extrainfo3', '');
        $extrainfo4 = Request::getString('extrainfo4', '');
        $extrainfo5 = Request::getString('extrainfo5', '');

        $eventdate = date('d.m.Y H:i:s', $eventdate);

        $title = $summary . ' (' . $eventdate . ' ' . $location . ')';

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
        $url .= "&eventdate=$eventdate";
        $url .= "&location=$location";

        //get username and email
        global $xoopsUser;
        if (!isset($xoopsUser) || !is_object($xoopsUser)) {
            $uname = '';
            $email = '';
            $uid   = 0;
        } else {
            $uname = $xoopsUser->getVar('uname');
            $email = $xoopsUser->getVar('email');
            $uid   = $xoopsUser->getVar('uid');
        }

        $ret = "
        <div class='row'>
            <div><span class='itemTitle'>" . _APCAL_RO_TITLE1 . "</span></div>
            <form class='apcalForm' method='post' id='RegOnlineForm' action='ro_regonlinehandler.php' name='roformaddmember' style='margin:0px;'>
                <input type='hidden' name='eventid' value='$eventid' />
                <input type='hidden' name='uid' value='$uid' />
                <input type='hidden' name='uname' value='$uname' />
                <input type='hidden' name='url' value='$url' />
                <input type='hidden' name='eventurl' value='$eventurl' />
                <input type='hidden' name='title' value='$title' />
                <input type='hidden' name='summary' value='$summary' />
                <input type='hidden' name='date' value='$date' />
                <input type='hidden' name='location' value='$location' />
                        <div class='col-xs-12 col-sm-4'>" . _APCAL_RO_EVENT . ":</div>
                        <div class='col-xs-12 col-sm-8'><input type='text' name='title' disabled='disabled' value='$summary' style='width:100%' /></div>
                        <div class='col-xs-12 col-sm-4'>" . _APCAL_RO_DATE . ":</div>
                        <div class='col-xs-12 col-sm-8'><input type='text' name='date' disabled='disabled' value='$date' style='width:100%' /></div>
                        <div class='col-xs-12 col-sm-4'>" . _APCAL_RO_LOCATION . ":</div>
                        <div class='col-xs-12 col-sm-8'><input type='text' name='location' disabled='disabled' value='$location' style='width:100%' /></div>
                        <div class='even col-xs-12 col-sm-4'>" . _APCAL_RO_FIRSTNAME . "*:</div>
                        <div class='odd col-xs-12 col-sm-8'><input type='text' name='firstname' value='$firstname' style='width:100%' /></div>
                        <div class='even col-xs-12 col-sm-4'>" . _APCAL_RO_LASTNAME . "*:</div>
                        <div class='odd col-xs-12 col-sm-8'><input type='text' name='lastname' value='$lastname' style='width:100%' /></div>
                        <div class='even col-xs-12 col-sm-4'>" . _APCAL_RO_EMAIL . ":</div>
                        <div class='odd col-xs-12 col-sm-8'>
                            <input type='text' name='email' value='$email' style='width:100%' />
                            <br>" . _APCAL_RO_SEND_CONF3 . "
                            <input type='radio' name='sendconf' value='yes' checked> " . _APCAL_RO_RADIO_YES . "
                            <input type='radio' name='sendconf' value='no'> " . _APCAL_RO_RADIO_NO . '
                        </div>
                    ';
        if ($cal->ro_extrainfo1 !== '') {
            $extrainfo1_obligatory = ($cal->ro_extrainfo1_obl > 0) ? '*' : '';
            $ret .= "
                        <div class='even col-xs-12 col-sm-4'>" . $cal->ro_extrainfo1 . "$extrainfo1_obligatory:</div>
                        <div class='odd col-xs-12 col-sm-8'><input type='text' name='extrainfo1' value='$extrainfo1' style='width:100%' /></div>
                    ";
        }
        if ($cal->ro_extrainfo2 !== '') {
            $extrainfo2_obligatory = ($cal->ro_extrainfo2_obl > 0) ? '*' : '';
            $ret .= "
                        <div class='even col-xs-12 col-sm-4'>" . $cal->ro_extrainfo2 . "$extrainfo2_obligatory:</div>
                        <div class='odd col-xs-12 col-sm-8'><input type='text' name='extrainfo2' value='$extrainfo2' style='width:100%' /></div>
                    ";
        }
        if ($cal->ro_extrainfo3 !== '') {
            $extrainfo3_obligatory = ($cal->ro_extrainfo3_obl > 0) ? '*' : '';
            $ret .= "
                        <div class='even col-xs-12 col-sm-4'>" . $cal->ro_extrainfo3 . "$extrainfo3_obligatory:</div>
                        <div class='odd col-xs-12 col-sm-8'><input type='text' name='extrainfo3' value='$extrainfo3' style='width:100%' /></div>
                    ";
        }
        if ($cal->ro_extrainfo4 !== '') {
            $extrainfo4_obligatory = ($cal->ro_extrainfo4_obl > 0) ? '*' : '';
            $ret .= "
                        <div class='even col-xs-12 col-sm-4'>" . $cal->ro_extrainfo4 . "$extrainfo4_obligatory:</div>
                        <div class='odd col-xs-12 col-sm-8'><input type='text' name='extrainfo4' value='$extrainfo4' style='width:100%' /></div>
                    ";
        }
        if ($cal->ro_extrainfo5 !== '') {
            $extrainfo5_obligatory = ($cal->ro_extrainfo5_obl > 0) ? '*' : '';
            $ret .= "
                        <div class='even col-xs-12 col-sm-4'>" . $cal->ro_extrainfo5 . "$extrainfo5_obligatory:</div>
                        <div class='odd col-xs-12 col-sm-8'><input type='text' name='extrainfo5' value='$extrainfo5' style='width:100%' /></div>
                    ";
        }
        
        if (($event_uid == $uid && $uid > 0) || //current user is event owner
            ($cal->isadmin == 1) || //current user is admin
            ($cal->ro_superedit == 1)) //current user can edit/delete registrations of other persons
        {
            $ret .= "
                <div class='even col-xs-12 col-sm-4'>" . _APCAL_RO_STATUS . ":</div>
                <div class='odd col-xs-12 col-sm-8'>
                    <input id='status0' type='radio' value='0' checked='checked' title=" . _APCAL_RO_STATUS_OK . " name='status' />
                    <label for='status0' name='xolb_status0'>" . _APCAL_RO_STATUS_OK . "</label>&nbsp;&nbsp;
                    <input id='status1' type='radio' value='1' title=" . _APCAL_RO_STATUS_PENDING . " name='status' />
                    <label for='status1' name='xolb_status1'>" . _APCAL_RO_STATUS_PENDING . "</label>&nbsp;&nbsp;
                    <input id='status2' type='radio' value='2' title=" . _APCAL_RO_STATUS_LIST . " name='status' />
                    <label for='status2' name='xolb_status2'>" . _APCAL_RO_STATUS_LIST . "</label>
                </div>";
        } else {
            $ret .= "<input type='hidden' name='status' value='-1' size='100' />";
        }
        $ret .= '
                </div>
                * ' . _APCAL_RO_OBLIGATORY . "
                <br><br>
                <div align='center'>
                    <input type='image' src='$roimagesave' name='add_member' alt='" . _APCAL_RO_BTN_CONF_ADD . "' title='" . _APCAL_RO_BTN_CONF_ADD . "' height='24px'/>&nbsp;&nbsp;
                    <input type='image' src='$roimagesavemore' name='add_member_more' alt='" . _APCAL_RO_BTN_CONF_ADD_MORE . "' title='" . _APCAL_RO_BTN_CONF_ADD_MORE . "' height='24px'/>&nbsp;&nbsp;
                    <input type='image' src='$roimagecancel' name='cancel' alt='" . _APCAL_RO_BTN_CANCEL . "' title='" . _APCAL_RO_BTN_CANCEL . "' height='24px'/>
                </div>
            </form>
         </div>\n<br><br>";

        $retList = '';
        $query = "SELECT ".$GLOBALS['xoopsDB']->prefix("apcal_ro_members").".* ";
        $query .= "FROM ".$GLOBALS['xoopsDB']->prefix("apcal_ro_members");
        //replaced one line by goffy2
        //$query .= " WHERE (((rom_eventid)=$eventid) AND ((rom_submitter)=$uid))";
        $query .= " WHERE (((rom_eventid)=$eventid) AND ((rom_submitter)=$uid)";
        if ($uid==0) {
            $poster_ip = gethostbyaddr(getenv("REMOTE_ADDR"));
            if ($poster_ip=='') $poster_ip='x';
            $query .= " AND ((rom_poster_ip)='$poster_ip')";
        }
        $query .= ")";
        
        $res = $GLOBALS['xoopsDB']->query($query);
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);


        if ($num_rows > 0) {
            $retList .= "
                <table border='0' width='100%'>
                    <tr><td width='100%' class='itemHead'><span class='itemTitle'>" . _APCAL_RO_TITLE3 . "</span></td></tr>
                    <tr><td width='100%'>
                    <table class='ro_table' width='100%'>
                        <tr>
                            <th class='even'>" . _APCAL_RO_FIRSTNAME . "</th>
                            <th class='even'>" . _APCAL_RO_LASTNAME . "</th>
                            <th class='even'>" . _APCAL_RO_EMAIL . '</th>';
            if ($cal->ro_extrainfo1 !== '') {
                $retList .= "<th class='even'>" . $cal->ro_extrainfo1 . '</th>';
            }
            if ($cal->ro_extrainfo2 !== '') {
                $retList .= "<th class='even'>" . $cal->ro_extrainfo2 . '</th>';
            }
            if ($cal->ro_extrainfo3 !== '') {
                $retList .= "<th class='even'>" . $cal->ro_extrainfo3 . '</th>';
            }
            if ($cal->ro_extrainfo4 !== '') {
                $retList .= "<th class='even'>" . $cal->ro_extrainfo4 . '</th>';
            }
            if ($cal->ro_extrainfo5 !== '') {
                $retList .= "<th class='even'>" . $cal->ro_extrainfo5 . '</th>';
            }
            $retList .= "<th class='even'>"._APCAL_RO_STATUS."</th>";
            $retList .= "
                    <th class='even'>" . _APCAL_RO_ACTION . '</th>
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
                $status        = (int)$member->rom_status;

                if ($line == 0) {
                    $classname = 'odd';
                    $line = 1;
                } else {
                    $classname = 'even';
                    $line = 0;
                }
                $unique_id = uniqid(mt_rand());
                $retList .= "
                    <form class='apcalForm' method='post' id='RegOnlineForm' action='ro_regonlinehandler.php' name='roformeditremovemember_" . $unique_id . "' style='margin:0px;'>
                        <input type='hidden' name='eventid' value='$eventid' />
                        <input type='hidden' name='uid' value='$uid' />
                        <input type='hidden' name='uname' value='$uname' />
                        <input type='hidden' name='url' value='$url' />
                        <input type='hidden' name='eventurl' value='$eventurl' />
                        <input type='hidden' name='summary' value='$summary' />
                        <input type='hidden' name='date' value='$date' />
                        <input type='hidden' name='eventdate' value='$eventdate' />
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
                        <input type='hidden' name='status' value='$status' />
                        <input type='hidden' name='num_members' value='$num_rows' />
                    ";
                $retList .= "<tr>
                            <td class='$classname'>$romfirstname</td>
                            <td class='$classname'>$romlastname</td>
                            <td class='$classname'>$romemail</td>";
                if ($cal->ro_extrainfo1 !== '') {
                    $retList .= "<td class='$classname'>$romextrainfo1</td>";
                }
                if ($cal->ro_extrainfo2 !== '') {
                    $retList .= "<td class='$classname'>$romextrainfo2</td>";
                }
                if ($cal->ro_extrainfo3 !== '') {
                    $retList .= "<td class='$classname'>$romextrainfo3</td>";
                }
                if ($cal->ro_extrainfo4 !== '') {
                    $retList .= "<td class='$classname'>$romextrainfo4</td>";
                }
                if ($cal->ro_extrainfo5 !== '') {
                    $retList .= "<td class='$classname'>$romextrainfo5</td>";
                }
                $retList .= "<td class='$classname'>";
                if (($event_uid == $uid && $uid > 0) || //current user is event owner
                    ($cal->isadmin == 1) || //current user is admin
                    ($cal->ro_superedit == 1)) //current user can edit/delete registrations of other persons
                {
                    if ($status == 1) {
                        $retList .= "<input type='image' src='$roimagestatuspending' name='confirm_member' alt='"._APCAL_RO_STATUS_PENDING."' title='"._APCAL_RO_STATUS_PENDING."'  height='22px' />";
                    } else if ($status == 2){
                        $retList .= "<input type='image' src='$roimagestatuslist' name='confirm_member' alt='"._APCAL_RO_STATUS_LIST."' title='"._APCAL_RO_STATUS_LIST."'  height='22px' />";
                    } else {
                        $retList .= "<input type='image' src='$roimagestatusok' name='confirm_member' alt='"._APCAL_RO_STATUS_OK."' title='"._APCAL_RO_STATUS_OK."'  height='22px' />";
                    }
                } else {
                    if ($status == 1) {
                        $retList .= "<img type='image' src='$roimagestatuspending' name='confirm_member' alt='"._APCAL_RO_STATUS_PENDING."' title='"._APCAL_RO_STATUS_PENDING."'  height='22px' />";
                    } else if ($status == 2){
                        $retList .= "<img type='image' src='$roimagestatuslist' name='confirm_member' alt='"._APCAL_RO_STATUS_LIST."' title='"._APCAL_RO_STATUS_LIST."'  height='22px' />";
                    } else {
                        $retList .= "<img type='image' src='$roimagestatusok' name='confirm_member' alt='"._APCAL_RO_STATUS_OK."' title='"._APCAL_RO_STATUS_OK."'  height='22px' />";
                    }
                }
                $retList .= '</td>';
                $retList .= "
                            <td class='$classname'>
                                <input type='image' src='$roimageedit' name='form_edit' alt='" . _APCAL_RO_BTN_EDIT . "' title='" . _APCAL_RO_BTN_EDIT . "'  height='24px' />
                                <input type='image' src='$roimagedelete' name='remove_member' alt='" . _APCAL_RO_BTN_REMOVE . "' title='" . _APCAL_RO_BTN_REMOVE . "'  height='24px' />
                            </td>
                        </tr>";
            }
            $retList .= '</form></table></td></tr></table>';
            $retList .= "<p style='text-align:center;align:center;'>
        <form class='apcalForm' method='post' id='RegOnlineForm' action='ro_regonlinehandler.php' name='roformgoback' style='margin:0px;'>
            <input type='hidden' name='eventurl' value='$eventurl' />
            <div align='center'>
            <input type='image' src='$roimagecancel' name='goback' alt='" . _APCAL_RO_BTN_BACK . "' title='" . _APCAL_RO_BTN_BACK . "' height='24px'/>
            </div>
        </form></p>\n";
            $retList .= '<br><br>';
        }
        

        echo $retList . $ret;
    }
}

if (isset($_POST['add_member_x']) || isset($_POST['add_member_more_x'])) {
    if (!empty($_POST['eventid'])) {
        $uid        = Request::getInt('uid');
        $url        = Request::getString('url', '');
        $eventurl   = Request::getString('eventurl', '');
        $uname      = Request::getString('uname', '');
        $eventid    = Request::getInt('eventid');
        $firstname  = Request::getString('firstname', '');
        $lastname   = Request::getString('lastname', '');
        $email      = Request::getString('email', '');
        $extrainfo1 = Request::getString('extrainfo1', '-');
        $extrainfo2 = Request::getString('extrainfo2', '-');
        $extrainfo3 = Request::getString('extrainfo3', '-');
        $extrainfo4 = Request::getString('extrainfo4', '-');
        $extrainfo5 = Request::getString('extrainfo5', '-');
        $summary    = Request::getString('summary', '');
        $date       = Request::getString('date');
        $location   = Request::getString('location', '');
        $sendconf   = Request::getString('sendconf', '');
        $status     = Request::getInt('status');
        $eventdate  = Request::getString('eventdate', '');

        $url_redirect = "&firstname=".$firstname;
        $url_redirect .= "&lastname=".$lastname;
        $url_redirect .= "&email=".$email;
        $url_redirect .= "&extrainfo1=".$extrainfo1;
        $url_redirect .= "&extrainfo2=".$extrainfo2;
        $url_redirect .= "&extrainfo3=".$extrainfo3;
        $url_redirect .= "&extrainfo4=".$extrainfo4;
        $url_redirect .= "&extrainfo5=".$extrainfo5;
        
        if ($firstname=='') {
            redirect_header($url.$url_redirect, 3, str_replace('%s', _APCAL_RO_FIRSTNAME, _APCAL_RO_MISSING_ITEM));
        }
        if ($lastname=='') {
          redirect_header($url.$url_redirect, 3, str_replace('%s', _APCAL_RO_LASTNAME, _APCAL_RO_MISSING_ITEM));
        }
/*
        if ($email=='') {
          redirect_header($url.$url_redirect, 3, str_replace('%s', _APCAL_RO_EMAIL, _APCAL_RO_MISSING_ITEM));
        }*/
        if ($cal->ro_extrainfo1_obl > 0 && $extrainfo1=='') {
            redirect_header($url . $url_redirect, 3, str_replace('%s', $cal->ro_extrainfo1, _APCAL_RO_MISSING_ITEM));
        }
        if ($cal->ro_extrainfo2_obl > 0 && $extrainfo2=='') {
          redirect_header($url.$url_redirect, 3, str_replace('%s', $cal->ro_extrainfo2, _APCAL_RO_MISSING_ITEM));
        }
        if ($cal->ro_extrainfo3_obl > 0 && $extrainfo3=='') {
          redirect_header($url.$url_redirect, 3, str_replace('%s', $cal->ro_extrainfo3, _APCAL_RO_MISSING_ITEM));
        }
        if ($cal->ro_extrainfo4_obl > 0 && $extrainfo4=='') {
          redirect_header($url.$url_redirect, 3, str_replace('%s', $cal->ro_extrainfo4, _APCAL_RO_MISSING_ITEM));
        }
        if ($cal->ro_extrainfo5_obl > 0 && $extrainfo5=='') {
            redirect_header($url.$url_redirect, 3, str_replace('%s', $cal->ro_extrainfo5, _APCAL_RO_MISSING_ITEM));
        }

        if ($email === '') {
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
                    . '.roe_number, roe_datelimit, roe_needconfirm, roe_waitinglist FROM '
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                    . ' WHERE (('
                    . $GLOBALS['xoopsDB']->prefix('apcal_ro_events')
                    . ".roe_eventid)=$eventid)";
        $res      = $GLOBALS['xoopsDB']->query($query);
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);
        if ($num_rows == 0) {
            $number_allowed = 0;
            $datelimit      = 0;
            $needconfirm    = 0;
            $waitinglist    = 0;
        } else {
            while ($ro_result = $GLOBALS['xoopsDB']->fetchObject($res)) {
                $number_allowed = (int)$ro_result->roe_number;
                $datelimit      = $ro_result->roe_datelimit;
                $needconfirm    = (int)$ro_result->roe_needconfirm;
                $waitinglist    = (int)$ro_result->roe_waitinglist;
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
        $waitinglist_used  = '';
        $waitinglist_used2 = '';
        if ($number_allowed > 0) {
            //get existing registrations
            $query    = 'SELECT '
                        . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                        . '.rom_id FROM '
                        . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                        . ' WHERE (('
                        . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                        . ".rom_eventid)=$eventid)";
            $res          = $GLOBALS['xoopsDB']->query($query);
            $number_total = $GLOBALS['xoopsDB']->getRowsNum($res);

            if ($number_total >= $number_allowed) {
                if($waitinglist > 0 ) {
                    $waitinglist_used  =  _APCAL_RO_PUT_ON_WAITINGLIST;
                    $waitinglist_used2 =  _APCAL_RO_PUT_ON_WAITINGLIST2;
                } else {
                    redirect_header($url, 5, _APCAL_RO_ERROR_FULL);
                }
            }
        }

        $confirmto = $email;
        // check whether email is available and confirmation is selected
        if ($confirmto === '') {
            $confirmto = '-';
        }
        if ($sendconf === 'no') {
            $confirmto = '-';
        }

        $poster_ip = gethostbyaddr(getenv("REMOTE_ADDR"));
        if ($poster_ip=='') $poster_ip='-';

        if ($status == -1) {
            if ($number_total >= $number_allowed) {
                $status = 2;
            } else if ($needconfirm > 0) {
                $status = 1;
            } else {
                $status = 0;
            }
        }

        $query = 'Insert into '
                 . $GLOBALS['xoopsDB']->prefix('apcal_ro_members')
                 . " (rom_submitter, rom_eventid, rom_firstname, rom_lastname, rom_email, rom_extrainfo1, rom_extrainfo2, rom_extrainfo3, rom_extrainfo4, rom_extrainfo5, rom_poster_ip, rom_status, rom_date_created) values ($uid, $eventid, '$firstname', '$lastname', '$email', '$extrainfo1', '$extrainfo2', '$extrainfo3', '$extrainfo4', '$extrainfo5', '$poster_ip', $status, "
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
                    $xoopsMailer->setFromEmail($cal->ro_mail_sender);
                    //set name of sender
                    $xoopsMailer->setFromName($cal->ro_mail_sendername);
                    //set subject
                    $subject = _APCAL_RO_MAIL_SUBJ_ADD;
                    $xoopsMailer->setSubject($subject);
                    //assign vars in template
                    $xoopsMailer->assign('UNAME', $uname);
                    $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                    $xoopsMailer->assign('SUMMARY', $summary);
                    $xoopsMailer->assign('DATE', $date);
                    $xoopsMailer->assign('LOCATION', $location);
                    $xoopsMailer->assign('WAITINGLIST', $waitinglist_used2);
                    $xoopsMailer->assign('URL', $eventurl);
                    $xoopsMailer->assign('SIGNATURE', $cal->ro_mail_signature);
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
                $xoopsMailer->setFromEmail($cal->ro_mail_sender);
                //set sender name
                $xoopsMailer->setFromName($cal->ro_mail_sendername);
                //set subject
                $subject = _APCAL_RO_MAIL_SUBJ_ADD;
                $xoopsMailer->setSubject($subject);
                //assign vars
                $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                $xoopsMailer->assign('SUMMARY', $summary);
                $xoopsMailer->assign('DATE', $date);
                $xoopsMailer->assign('LOCATION', $location);
                $xoopsMailer->assign('WAITINGLIST', $waitinglist_used);
                $xoopsMailer->assign('URL', $eventurl);
                $xoopsMailer->assign('SIGNATURE', $cal->ro_mail_signature);
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

if (isset($_POST['confirm_member']) || isset($_POST['confirm_member_x'])){

    if (!empty($_POST['eventid'])){
        $uid       = Request::getInt('uid');
        $url       = Request::getString('url', '');
        $eventurl  = Request::getString('eventurl', '');
        $uname     = Request::getString('uname', '');
        $eventid   = Request::getInt('eventid');
        $firstname = Request::getString('firstname', '');
        $lastname  = Request::getString('lastname', '');
        $email     = Request::getString('email', '');
        $summary   = Request::getString('summary', '');
        $date      = Request::getString('date');
        $location  = Request::getString('location', '');
        $status    = Request::getInt('status');
        $rom_id    = Request::getInt('rom_id');
        $eventdate = Request::getString('eventdate', '');

        if ($email=='') $email='-';

        $confirmto = $email;

        if ($status == 0) {
            $status = 1;
            $roinfo = _APCAL_RO_STATUS_SUCCESS_CHANGE_PENDING;
        } else {
            $status = 0;
            $roinfo = _APCAL_RO_STATUS_SUCCESS_CHANGE_OK;
        }

        $query = "UPDATE `".$GLOBALS['xoopsDB']->prefix("apcal_ro_members")."` SET `rom_status` = '$status' WHERE `rom_id` = $rom_id;";
        $res = $GLOBALS['xoopsDB']->query($query);
        if(!$res) {
            redirect_header($url, 3, _APCAL_RO_ERROR_STATUS_CONF);
        } else {
            //confirmation mail to registrated person
            if ($confirmto=='-') {
                //echo "option not selected or no email-address available";
            } else {
                $xoopsMailer =& xoops_getMailer();
                $xoopsMailer->useMail();
                //set template path
                if( file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/'. $xoopsConfig['language'] .'/')) {
                    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/'. $xoopsConfig['language'] .'/mail_template/');
                } else {
                    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/mail_template/');
                }
                //set template name 
                $xoopsMailer->setTemplate('ro_confirm_status.tpl');
                //set sender 
                $xoopsMailer->setFromEmail($cal->ro_mail_sender);
                //set sender name
                $xoopsMailer->setFromName($cal->ro_mail_sendername);
                //set subject
                $subject = _APCAL_RO_MAIL_SUBJ_STATUS;
                $xoopsMailer->setSubject($subject);
                //assign vars
                $xoopsMailer->assign("NAME", $firstname." ".$lastname);
                $xoopsMailer->assign("SUMMARY", $summary);
                $xoopsMailer->assign("DATE", $date);
                $xoopsMailer->assign("LOCATION", $location);
                $xoopsMailer->assign("URL", $eventurl);
                $xoopsMailer->assign("SIGNATURE", $cal->ro_mail_signature);
                $xoopsMailer->assign("INFOTEXT", $roinfo.".");
                //set recipient
                $xoopsMailer->setToEmails($confirmto);

                //execute sending
                $xoopsMailer->send();
                $xoopsMailer->reset();
            }
            redirect_header($url, 3, $roinfo);
        }
    }
}

if (isset($_POST['remove_member']) || isset($_POST['remove_member_x'])) {
    if (!empty($_POST['rom_id'])) {
        $rom_id      = Request::getInt('rom_id');
        $url         = Request::getString('url', '');
        $eventurl    = Request::getString('eventurl', '');
        $uid         = Request::getInt('uid');
        $uname       = Request::getString('uname', '');
        $eventid     = Request::getInt('eventid');
        $title       = Request::getString('title', '');
        $firstname   = Request::getString('firstname', '');
        $lastname    = Request::getString('lastname', '');
        $confirmto   = Request::getString('email', '');
        $summary     = Request::getString('summary', '');
        $date        = Request::getInt('date');
        $location    = Request::getString('location', '');
        $num_members = Request::getInt('num_members');
        $eventdate   = Request::getString('eventdate', '');

        // check whether confirmation mail should be send
        if ($confirmto === '') {
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
                    $xoopsMailer->setFromEmail($cal->ro_mail_sender);
                    //set sender name
                    $xoopsMailer->setFromName($cal->ro_mail_sendername);
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
                    $xoopsMailer->assign('SIGNATURE', $cal->ro_mail_signature);
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
                $sender = $cal->ro_mail_sender;
                $xoopsMailer->setFromEmail($sender);
                //set sender name
                $xoopsMailer->setFromName($cal->ro_mail_sendername);
                //set subject
                $subject = _APCAL_RO_MAIL_SUBJ_REMOVE;
                $xoopsMailer->setSubject($subject);
                //assign vars
                $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                $xoopsMailer->assign('SUMMARY', $summary);
                $xoopsMailer->assign('DATE', $date);
                $xoopsMailer->assign('LOCATION', $location);
                $xoopsMailer->assign('URL', $eventurl);
                $xoopsMailer->assign('SIGNATURE', $cal->ro_mail_signature);
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
        $uid       = Request::getInt('uid');
        $eventid   = Request::getInt('eventid');
        $summary   = Request::getString('summary', '');
        $date      = Request::getInt('date');
        $location  = Request::getString('location', '');
        $eventurl  = Request::getString('eventurl', '');
        $event_uid = Request::getInt('event_uid');
        $classname = '';

        if (!empty($_SERVER['HTTPS'])) {
            $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } else {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        if (!isset($xoopsUser) || !is_object($xoopsUser)) {
            $current_uid = 0;
            $current_uname = "";
        } else {
            $current_uid   = $xoopsUser->getVar('uid');
            $current_uname = $xoopsUser->getVar('uname');
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
           <table class='ro_table'>
             <tr>
               <th width='100px' class='listeheader'>" . _APCAL_RO_UNAME . "</th>
               <th width='100px' class='listeheader'>" . _APCAL_RO_FIRSTNAME . "</th>
               <th width='100px' class='listeheader'>" . _APCAL_RO_LASTNAME . "</th>
               <th class='listeheader'>" . _APCAL_RO_EMAIL . '</th>';
            if ($cal->ro_extrainfo1 !== '') {
                $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo1 . '</th>';
            }
            if ($cal->ro_extrainfo2 !== '') {
                $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo2 . '</th>';
            }
            if ($cal->ro_extrainfo3 !== '') {
                $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo3 . '</th>';
            }
            if ($cal->ro_extrainfo4 !== '') {
                $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo4 . '</th>';
            }
            if ($cal->ro_extrainfo5 !== '') {
                $ret .= "<th class='listeheader'>" . $cal->ro_extrainfo5 . '</th>';
            }
            $ret .= "<th class='listeheader'>"._APCAL_RO_STATUS."</th>";
            $ret .= "
               <th class='listeheader'>" . _APCAL_RO_ACTION . '</th>
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
                $status     = (int)$member->rom_status;
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
                if ($cal->ro_extrainfo1 !== '') {
                    $ret .= "<td class='$classname'>$extrainfo1</td>";
                }
                if ($cal->ro_extrainfo2 !== '') {
                    $ret .= "<td class='$classname'>$extrainfo2</td>";
                }
                if ($cal->ro_extrainfo3 !== '') {
                    $ret .= "<td class='$classname'>$extrainfo3</td>";
                }
                if ($cal->ro_extrainfo4 !== '') {
                    $ret .= "<td class='$classname'>$extrainfo4</td>";
                }
                if ($cal->ro_extrainfo5 !== '') {
                    $ret .= "<td class='$classname'>$extrainfo5</td>";
                }
                $ret .= "<td class='$classname' style='text-align:center'>";
                $unique_id = uniqid(mt_rand());
                $ret .= "
                    <form method='post' action='ro_regonlinehandler.php' name='roformlistconfirm_".$unique_id."' style='margin:0px;'>
                      <input type='hidden' name='eventid' value='$eventid' />
                      <input type='hidden' name='url' value='$url' />
                      <input type='hidden' name='eventurl' value='$eventurl' />
                      <input type='hidden' name='rom_id' value='$rom_id' />
                      <input type='hidden' name='summary' value='$summary' />
                      <input type='hidden' name='date' value='$date' />
                      <input type='hidden' name='location' value='$location' />
                      <input type='hidden' name='uid' value='$uid' />  
                      <input type='hidden' name='firstname' value='$firstname' />
                      <input type='hidden' name='lastname' value='$lastname' />
                      <input type='hidden' name='email' value='$email' />
                      <input type='hidden' name='uname' value='$uname' />
                      <input type='hidden' name='current_uname' value='$current_uname' />              
                      <input type='hidden' name='status' value='$status' />
                      <div style='display:inline;'>";
                        if (($event_uid == $current_uid && $current_uid > 0) || //current user is event owner
                            ($cal->isadmin == 1) || //current user is admin
                            ($cal->ro_superedit == 1)) //current user can edit/delete registrations of other persons
                        {
                            if ($status == 1) {
                                $ret .= "<input type='image' src='$roimagestatuspending' name='confirm_member' alt='"._APCAL_RO_STATUS_PENDING."' title='"._APCAL_RO_STATUS_PENDING."'  height='22px' />";
                            } else if ($status == 2){
                                $ret .= "<input type='image' src='$roimagestatuslist' name='confirm_member' alt='"._APCAL_RO_STATUS_LIST."' title='"._APCAL_RO_STATUS_LIST."'  height='22px' />";
                            } else {
                                $ret .= "<input type='image' src='$roimagestatusok' name='confirm_member' alt='"._APCAL_RO_STATUS_OK."' title='"._APCAL_RO_STATUS_OK."'  height='22px' />";
                            }
                        } else {
                            if ($status == 1) {
                                $ret .= "<img src='$roimagestatuspending' name='confirm_member' alt='"._APCAL_RO_STATUS_PENDING."' title='"._APCAL_RO_STATUS_PENDING."'  height='22px' />";
                            } else if ($status == 2){
                                $ret .= "<img src='$roimagestatuslist' name='confirm_member' alt='"._APCAL_RO_STATUS_LIST."' title='"._APCAL_RO_STATUS_LIST."'  height='22px' />";
                            } else {
                                $ret .= "<img src='$roimagestatusok' name='confirm_member' alt='"._APCAL_RO_STATUS_OK."' title='"._APCAL_RO_STATUS_OK."'  height='22px' />";
                            }
                        }

                        $ret .= "
                      </div>
                    </form>";
                $ret .= "</td>";
                $ret .= "<td class='$classname'>";
                
                $current_ip = gethostbyaddr(getenv("REMOTE_ADDR"));
                if ($current_ip=='') $current_ip='-';
                if (!isset($xoopsUser) || !is_object($xoopsUser)) {
                    $current_uid = 0;
                } else {
                    $current_uid = $xoopsUser->getVar('uid');
                }
                if (($event_uid == $current_uid && $current_uid > 0) || //current user is event owner
                    ($submitter == $current_uid && $current_uid > 0) || //current user made registration
                    ($cal->isadmin == 1) || //current user is admin
                    ($cal->superedit == 1) || //current user can edit/delete registrations of other persons
                    ($submitter == $current_uid && $current_uid == 0 && $poster_ip == $current_ip)) //current user is guest, but ip is the same as guest who made registration 
                    { //end added
                        $unique_id = uniqid(mt_rand());
                        $ret .= "
                        <form class='apcalForm' method='post' id='RegOnlineForm' action='ro_regonlinehandler.php' name='roformlist_" . $unique_id . "' style='margin:0px;'>
                            <input type='hidden' name='eventid' value='$eventid' />
                            <input type='hidden' name='url' value='$url' />
                            <input type='hidden' name='eventurl' value='$eventurl' />
                            <input type='hidden' name='rom_id' value='$rom_id' />
                            <input type='hidden' name='firstname' value='$firstname' />
                            <input type='hidden' name='lastname' value='$lastname' />
                            <input type='hidden' name='email' value='$email' />
                            <input type='hidden' name='summary' value='$summary' />
                            <input type='hidden' name='date' value='$date' />
                            <input type='hidden' name='location' value='$location' />
                            <input type='hidden' name='uname' value='$uname' />  
                            <input type='hidden' name='uid' value='$uid' />              
                            <input type='hidden' name='extrainfo1' value='$extrainfo1' />
                            <input type='hidden' name='extrainfo2' value='$extrainfo2' />
                            <input type='hidden' name='extrainfo3' value='$extrainfo3' />
                            <input type='hidden' name='extrainfo4' value='$extrainfo4' />
                            <input type='hidden' name='extrainfo5' value='$extrainfo5' />
                            <input type='hidden' name='status' value='$status' />
                            <input type='hidden' name='current_uname' value='$current_uname' />
                            <input type='hidden' name='num_members' value='$num_rows' />
                            <div style='display:inline;'>
                                <input type='image' src='$roimageedit' name='form_edit' alt='" . _APCAL_RO_BTN_EDIT . "' title='" . _APCAL_RO_BTN_EDIT . "'  height='22px' />
                                <input type='image' src='$roimagedelete' name='remove_member' alt='" . _APCAL_RO_BTN_REMOVE . "' title='" . _APCAL_RO_BTN_REMOVE . "'  height='22px' />
                            </div>
                        </form>";
                    }
                    $ret .= '
                    </td>
                </tr>';
            }
            $ret .= "</table>\n<br>";

            $ret .= "<div align='center'><a href='$eventurl' target='_self'><img src='$roimagecancel' name='goback' alt='" . _APCAL_RO_BTN_BACK . "' title='" . _APCAL_RO_BTN_BACK . "' style='height:24px;margin:0 10px;'/></a>";
            if ($cal->enableprint) {
                $ret .= "<a href='print.php?smode=ro_list&eventid=$eventid&summary=$summary&date=$date&location=$location' target='_blank'><img src='$roimageprint' name='print' alt='" . _APCAL_RO_PRINT_LIST . "' title='" . _APCAL_RO_PRINT_LIST . "' style='height:24px;margin:0 10px;'/></a>";
                $ret .= "<a href='print.php?smode=ro_list&op=exportxls&eventid=$eventid&summary=$summary&date=$date&location=$location' target='_blank'><img src='$roimagedownload' name='download' alt='" . _DOWNLOAD . "' title='" . _DOWNLOAD . "' style='height:24px;margin:0 10px;'/></a>";
            }
            $ret .= "</div>\n";

            //show form for sending mail to all registered persons; only allowed for event owner or admins
            if (($event_uid == $current_uid && $current_uid > 0) || //current user is event owner
                ($cal->isadmin == 1) || //current user is admin
                ($cal->superedit == 1) //current user can edit/delete registrations of other persons
               )
            {
                $query = 'SELECT ' . $GLOBALS['xoopsDB']->prefix('users') . '.email ';
                $query .= 'FROM ' . $GLOBALS['xoopsDB']->prefix('users');
                $query .= ' WHERE (((' . $GLOBALS['xoopsDB']->prefix('users') . ".uid)=$uid))";

                $res = $GLOBALS['xoopsDB']->query($query);
                $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

                if ($num_rows == 0) {
                    $sender = '';
                } else {
                    while ($member = $GLOBALS['xoopsDB']->fetchObject($res)) {
                        $sender = $member->email;
                    }
                }
                $mailtext = _APCAL_RO_EVENT . ": $summary\n" . _APCAL_RO_DATE . ": $date\n" . _APCAL_RO_LOCATION . ": $location\n" . _APCAL_RO_LINK . ": $eventurl\n\n";
                $ret .= "
                    <br><br><br>
                    <p class='listeheader'>" . _APCAL_RO_TITLE4 . "</p>
                    <form class='apcalForm' method='post' id='RegOnlineForm' action='ro_regonlinehandler.php' name='roformsendmail' accept-charset='UTF-8'>
                    <table border='0' width='100%'>
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
                        <p style='text-align:center;align:center'><input type='image' src='$roimagesend' name='ro_notify_all' alt='" . _APCAL_RO_BTN_SEND . "' title='" . _APCAL_RO_BTN_SEND . "' height='24px'/></p>
                    </form>
                    \n";
            }
        }
        echo $ret;
    }
}

if (isset($_POST['sendmail_member']) || isset($_POST['sendmail_member_x'])) {
    if (!empty($_REQUEST['eventid'])){

        $uid       = Request::getInt('uid');
        $event_uid = Request::getInt('event_uid');
        $eventid   = Request::getInt('eventid');
        $summary   = Request::getString('summary', '');
        $date      = Request::getInt('date');
        $location  = Request::getString('location', '');
        $eventurl  = Request::getString('eventurl', '');
        $email     = Request::getString('email', '');
        $firstname = Request::getString('firstname', '');
        $lastname  = Request::getString('lastname', '');
        $classname ='';

        if( ! empty( $_SERVER['HTTPS'] ) ) {
            $url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
        } else {
            $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
        }

        $url .= "?list=1";
        $url .= "&uid=$uid";
        $url .= "&eventid=$eventid";
        $url .= "&summary=$summary";
        $url .= "&date=$date";
        $url .= "&location=$location";
        $url .= "&eventurl=$eventurl";

        //show form for sending mail to registered persons
        $unique_id = uniqid(mt_rand());

        $query = "SELECT ".$GLOBALS['xoopsDB']->prefix("users").".email ";
        $query .= "FROM ".$GLOBALS['xoopsDB']->prefix("users");
        $query .= " WHERE (((".$GLOBALS['xoopsDB']->prefix("users").".uid)=$uid))";

        $res = $GLOBALS['xoopsDB']->query($query);
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($res);

        if( $num_rows == 0 ) $sender= "" ;
        else while( $member = $GLOBALS['xoopsDB']->fetchObject($res) ) {
            $sender=$member->email;
        }
        $mailtext = _APCAL_RO_EVENT.": $summary\n"._APCAL_RO_DATE.": $date\n"._APCAL_RO_LOCATION.": $location\n"._APCAL_RO_LINK.": $eventurl\n\n";
        $mailtext .= "Hallo $firstname $lastname\n\n";
        $ret .= "
    <br /><br /><br />
    <table border='1' cellpadding='0' cellspacing='0' width='100%'>
      <tr>
        <td class='listeheader'>"._APCAL_RO_TITLE4."</td>
      </tr>
    </table>
    <form method='post' action='ro_regonlinehandler.php' name='roformsendmail".$unique_id."' accept-charset='UTF-8'>
    <table border='1' width='100%'>
      <tr>
        <td class='even' width='100px'>"._APCAL_RO_MAIL_SENDER.":</td>
        <td class='odd'><input type='text' name='sender' size='70' value='$sender'></td>
      </tr>
      <tr>
        <td class='even' width='100px'>"._APCAL_RO_MAIL_RECEPIENT.":</td>
        <td class='odd'><input type='text' name='email' size='70' value='$email'></td>
      </tr>
      <tr>
        <td class='even' width='100px'>"._APCAL_RO_MAIL_SUBJ.":</td>
        <td class='odd'><input type='text' name='subject' size='70' value='"._APCAL_RO_MAIL_SUBJ_TEXT."'></td>
      </tr>
      <tr>
        <td class='even' width='200px'>"._APCAL_RO_MAIL_BODY1.":<br/><br/><font size='1'>"._APCAL_RO_MAIL_BODY2."</font></td>
        <td class='odd' height='200px' valign='top'>
        <textarea rows='25' name='mailtext' cols='95'>$mailtext</textarea></td>
      </tr>
    </table>
        <input type='hidden' name='url' value='$url' />
        <input type='hidden' name='eventurl' value='$eventurl' />
        <input type='hidden' name='eventid' value='$eventid' />
        <p style='text-align:center;align:center'>
        <input type='image' src='$roimagesend' name='ro_notify_one' alt='"._APCAL_RO_BTN_SEND."' title='"._APCAL_RO_BTN_SEND."' height='32px'/>
        <input type='image' src='$roimagecancel' name='goback' alt='"._APCAL_RO_BTN_BACK."' title='"._APCAL_RO_BTN_BACK."' height='32px'/>
        </p>
    </form>
    \n";


        echo $ret;
    }
}

if (isset($_POST['form_edit']) || isset($_POST['form_edit_x'])) {
    if (!empty($_POST['rom_id'])) {
        $rom_id     = Request::getInt('rom_id');
        $uid        = Request::getInt('uid');
        $url        = Request::getString('url', '');
        $eventurl   = Request::getString('eventurl', '');
        $uname      = Request::getString('uname', '');
        $eventid    = Request::getInt('eventid');
        $firstname  = Request::getString('firstname', '');
        $lastname   = Request::getString('lastname', '');
        $email      = Request::getString('email', '');
        $extrainfo1 = Request::getString('extrainfo1', '');
        $extrainfo2 = Request::getString('extrainfo2', '');
        $extrainfo3 = Request::getString('extrainfo3', '');
        $extrainfo4 = Request::getString('extrainfo4', '');
        $extrainfo5 = Request::getString('extrainfo5', '');
        $summary    = Request::getString('summary', '');
        $date       = Request::getInt('date');
        $location   = Request::getString('location', '');
        $sendconf   = Request::getInt('sendconf');
        $status     = Request::getInt('status');

        $ret  = '';
        $retList = '';

        $ret = "
        <table border='0' width='100%'>
            <tr><td width='100%' class='itemHead'><span class='itemTitle'>" . _APCAL_RO_TITLE5 . "</span></td></tr>
            <tr><td width='100%'>
            <form class='apcalForm' method='post' id='RegOnlineForm' action='ro_regonlinehandler.php' name='roformeditmember' style='margin:0px;'>
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
        if ($cal->ro_extrainfo1 !== '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . $cal->ro_extrainfo1 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo1' value='$extrainfo1' size='100' /></td>
                    </tr>";
        }
        if ($cal->ro_extrainfo2 !== '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . $cal->ro_extrainfo2 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo2' value='$extrainfo2' size='100' /></td>
                    </tr>";
        }
        if ($cal->ro_extrainfo3 !== '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . $cal->ro_extrainfo3 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo3' value='$extrainfo3' size='100' /></td>
                    </tr>";
        }
        if ($cal->ro_extrainfo4 !== '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . $cal->ro_extrainfo4 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo4' value='$extrainfo4' size='100' /></td>
                    </tr>";
        }
        if ($cal->ro_extrainfo5 !== '') {
            $ret .= "
                    <tr>
                        <td class='even' width='120px'>" . $cal->ro_extrainfo5 . ":</td>
                        <td class='odd'><input type='text' name='extrainfo5' value='$extrainfo5' size='100' /></td>
                    </tr>";
        }
        if (($event_uid == $uid && $uid > 0) || //current user is event owner
            ($cal->isadmin == 1) || //current user is admin
            ($cal->ro_superedit == 1)) //current user can edit/delete registrations of other persons
        {
            $ret .= "
          <tr>
            <td class='even' width='120px'>"._APCAL_RO_STATUS.":</td>
            <td class='odd'>
              <input id='status0' type='radio' value='0'";
            if ($status == 0 ) $ret .=" checked='checked'";
            $ret .=" title="._APCAL_RO_STATUS_OK." name='status' />
              <label for='status0' name='xolb_status0'>"._APCAL_RO_STATUS_OK."</label>&nbsp;&nbsp;
              <input id='status1' type='radio' value='1'";
            if ($status == 1) $ret .=" checked='checked'";
            $ret .=" title="._APCAL_RO_STATUS_PENDING." name='status' />
              <label for='status1' name='xolb_status1'>"._APCAL_RO_STATUS_PENDING."</label>&nbsp;&nbsp;
              <input id='status2' type='radio' value='2'";
            if ($status == 2) $ret .=" checked='checked'";
            $ret .=" title="._APCAL_RO_STATUS_LIST." name='status' />
              <label for='status2' name='xolb_status2'>"._APCAL_RO_STATUS_LIST."</label>
            </td>
          </tr>";
        } else {
            $ret .= "<input type='hidden' name='status' value='$status' size='100' />";
        }
        $ret .= '
                </table>
                * ' . _APCAL_RO_OBLIGATORY . "
                <br><br>
                <div align='center'>
                    <input type='image' src='$roimagesave' name='edit_member' alt='" . _APCAL_RO_BTN_CONF_EDIT . "' title='" . _APCAL_RO_BTN_CONF_EDIT . "' height='24px'/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='image' src='$roimagecancel' name='cancel' alt='" . _APCAL_RO_BTN_CANCEL . "' title='" . _APCAL_RO_BTN_CANCEL . "' height='24px'/>
                </div>
            </form>
            </td></tr>
        </table>\n<br><br>";

        echo $ret;
    }
}

if (isset($_POST['edit_member']) || isset($_POST['edit_member_x'])) {
    if (!empty($_POST['rom_id'])) {
        $rom_id     = Request::getInt('rom_id');
        $uid        = Request::getInt('uid');
        $url        = Request::getString('url', '');
        $eventurl   = Request::getString('eventurl', '');
        $uname      = Request::getString('uname', '');
        $eventid    = Request::getInt('eventid');
        $firstname  = Request::getString('firstname', '');
        $lastname   = Request::getString('lastname', '');
        $email      = Request::getString('email', '');
        $extrainfo1 = Request::getString('extrainfo1', '');
        $extrainfo2 = Request::getString('extrainfo2', '');
        $extrainfo3 = Request::getString('extrainfo3', '');
        $extrainfo4 = Request::getString('extrainfo4', '');
        $extrainfo5 = Request::getString('extrainfo5', '');
        $summary    = Request::getString('summary', '');
        $date       = Request::getInt('date');
        $location   = Request::getString('location', '');
        $sendconf   = Request::getInt('sendconf');
        $status     = Request::getInt('status');

        if ($firstname === '') {
            $firstname = '-';
        }
        if ($lastname === '') {
            $lastname = '-';
        }
        if ($email === '') {
            $email = '-';
        }
        if ($extrainfo1 === '') {
            $extrainfo1 = '-';
        }
        if ($extrainfo2 === '') {
            $extrainfo2 = '-';
        }
        if ($extrainfo3 === '') {
            $extrainfo3 = '-';
        }
        if ($extrainfo4 === '') {
            $extrainfo4 = '-';
        }
        if ($extrainfo5 === '') {
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
        $query .= $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_extrainfo5 = '$extrainfo5', ";
        $query .= $GLOBALS['xoopsDB']->prefix("apcal_ro_members") . ".rom_status = $status ";
        $query .= 'WHERE (((' . $GLOBALS['xoopsDB']->prefix('apcal_ro_members') . ".rom_id)=$rom_id))";

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
        $url = Request::getString('eventurl', '');
        redirect_header($url, 1, _APCAL_RO_CANCEL);
    }
    if (!empty($_POST['url'])) {
        $url = Request::getString('url', '');
        redirect_header($url, 1, _APCAL_RO_CANCEL);
    }
}
if (isset($_POST['goback']) || isset($_POST['goback_x'])) {
    if (!empty($_POST['eventurl'])) {
        $url = Request::getString('eventurl', '');
        redirect_header($url, 0, _APCAL_RO_BACK);
    }
}

if (isset($_POST['ro_notify_all']) || isset($_POST['ro_notify_all_x'])) {
    if (!empty($_POST['url'])) {
        $url      = Request::getString('url', '');
        $eventurl = Request::getString('eventurl', '');
        $eventid  = Request::getInt('eventid');
        $sender   = Request::getString('sender', '');
        $subject  = Request::getString('subject', '');
        $mailtext = Request::getString('mailtext', '');
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
                    $xoopsMailer->setFromName($cal->ro_mail_sendername);
                    //set subject
                    $xoopsMailer->setSubject($subject);
                    //assign vars
                    $xoopsMailer->assign('MAILTEXT', $mailtext);
                    $xoopsMailer->assign('NAME', $firstname . ' ' . $lastname);
                    $xoopsMailer->assign('SUMMARY', $summary);
                    $xoopsMailer->assign('DATE', $date);
                    $xoopsMailer->assign('LOCATION', $location);
                    $xoopsMailer->assign('URL', $eventurl);
                    $xoopsMailer->assign('SIGNATURE', $cal->ro_mail_signature);
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

if (isset($_POST['ro_notify_one']) || isset($_POST['ro_notify_one_x'])) {

    if (!empty($_POST['url'])){

        $url      = Request::getString('url', '');
        $eventurl = Request::getString('eventurl', '');
        $eventid  = Request::getInt('eventid');
        $sender   = Request::getString('sender', '');
        $email    = Request::getString('email', '');
        $subject  = Request::getString('subject', '');
        $mailtext = Request::getString('mailtext', '');
        $counter=1;

        //$subject = utf8_encode($subject);
        //$mailtext = utf8_encode($mailtext);
        $recipient=$email;

        $xoopsMailer =& xoops_getMailer();
        $xoopsMailer->useMail();
        //set template path
        if( file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/'. $xoopsConfig['language'] .'/')) {
            $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/'. $xoopsConfig['language'] .'/mail_template/');
        } else {
            $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/mail_template/');
        }
        //set template name
        $xoopsMailer->setTemplate('ro_notify_all.tpl');
        //set sender
        $xoopsMailer->setFromEmail($sender); //take email from inputbox
        //set sender name
        $xoopsMailer->setFromName($cal->ro_mail_sendername);
        //set subject
        $xoopsMailer->setSubject($subject);
        //assign vars
        $xoopsMailer->assign("MAILTEXT", $mailtext);
        //set recipient
        $xoopsMailer->setToEmails($recipient);

        //execute sending
        $xoopsMailer->send();
        $xoopsMailer->reset();

        redirect_header($url, 3, $counter._APCAL_RO_MAILSENT) ;

    }
}

require XOOPS_ROOT_PATH . '/footer.php';
