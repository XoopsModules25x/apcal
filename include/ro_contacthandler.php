<?php

function convertmycontacts($strcontact)
{
    // Ermitteln aller einzelnen Wörter von Kontakt aus Termin piCal
    // Umwandeln der einzelnen Namen in Link auf Benutzerkonto, wenn Name ein Mitgliedsname ist
    $strsearch    = ' ';
    $strnew       = '';
    $strseperator = '';

    $pos1 = 0;
    $pos2 = strpos($strcontact, $strsearch, $pos1);

    if ($pos2 === false) {
        //echo "<br/>kein leerzeichen";
        $struser = $strcontact;
        $struid  = getuid($struser);
        if ($struid == -1) {
            $strnew = $struser;
        } else {
            $strnew = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $struid . "' title='" . $struser . "'>" . $struser . '</a>';
        }
    } else {
        //Leerzeichen vorhanden
        while ($pos2 !== false) {
            //alle wörter zwischen Leerzeichen ermitteln
            $struser = substr($strcontact, $pos1, $pos2 - $pos1);
            if (substr($struser, -1) == ',') {
                $struser      = substr($struser, 0, strlen($struser) - 1);
                $strseperator = ', ';
            } else {
                $strseperator = ' ';
            }
            $struid = getuid($struser);
            if ($struid == -1) {
                $strnew = $strnew . $struser . $strseperator;
            } else {
                $strnew = $strnew . "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $struid . "' title='" . $struser . "'>" . $struser . '</a>' . $strseperator;
            }
            $pos1 = $pos2 + 1;
            $pos2 = strpos($strcontact, $strsearch, $pos1);
        }

        if ($pos2 == 0) {
            //Rest ab letztem Leerzeichen einlesen
            $struser = substr($strcontact, $pos1);
            $struid  = getuid($struser);
            if ($struid == -1) {
                $strnew = $strnew . $struser;
            } else {
                $strnew = $strnew . "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $struid . "' title='" . $struser . "'>" . $struser . '</a>';
            }
        } else {
        }
    }

    return $strnew;
}

function getuid($UserName)
{
    $rc  = -1;
    $db  = xoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'Select uid from ' . $db->prefix('users') . " where uname = '" . $UserName . "' LIMIT 0,1";

    $result = $db->query($sql);
    if ($result) {
        if ($db->getRowsNum($result) == 1) {
            $member = $GLOBALS['xoopsDB']->fetchObject($result);
            $userid = $member->uid;
            $rc     = $userid;
        }
    }

    return $rc;
}
