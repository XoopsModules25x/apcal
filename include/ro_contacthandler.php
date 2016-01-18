<?php

Function convertmycontacts($strcontact){ 
// Ermitteln aller einzelnen Wörter von Kontakt aus Termin piCal
// Umwandeln der einzelnen Namen in Link auf Benutzerkonto, wenn Name ein Mitgliedsname ist 
    $strsearch = " ";
    $strnew = "";
    $strseperator = "";
    
    $pos1 = 0;
    $pos2 = strpos($strcontact, $strsearch, $pos1);
    
    If ($pos2 === false) {
		//echo "<br/>kein leerzeichen";
        $struser = $strcontact;
        $struid = getuid($struser);
        If ($struid == -1) {
            $strnew = $struser;
        } Else {
            $strnew = "<a href='".XOOPS_URL ."/userinfo.php?uid=".$struid."' title='".$struser."'>".$struser."</a>";
        }
    } Else {
		//Leerzeichen vorhanden
        While ($pos2 !== false) {
			//alle wörter zwischen Leerzeichen ermitteln
            $struser = substr($strcontact, $pos1, $pos2-$pos1);
            If (substr($struser, -1) == ",") {
                $struser = substr($struser, 0, strlen($struser)-1);
                $strseperator = ", ";
            } Else {
                $strseperator = " ";
            }
			$struid = getuid($struser);
            If ($struid == -1) {
                $strnew = $strnew . $struser . $strseperator;
            } Else {
                $strnew = $strnew . "<a href='".XOOPS_URL ."/userinfo.php?uid=".$struid."' title='".$struser."'>".$struser."</a>".$strseperator;
            }
            $pos1 = $pos2 + 1;
            $pos2 = strpos($strcontact, $strsearch, $pos1);
        } 
        
        If ($pos2 == 0) {
            //Rest ab letztem Leerzeichen einlesen
            $struser = substr($strcontact, $pos1);
            $struid = getuid($struser);
            If ($struid == -1) {
				$strnew = $strnew . $struser;
            } Else {
                $strnew = $strnew."<a href='".XOOPS_URL ."/userinfo.php?uid=".$struid."' title='".$struser."'>".$struser."</a>";
            }
        } Else {

        }
    } 

	return $strnew;
}

function getuid($UserName) {
	$rc = -1;
	$db = &XoopsDatabaseFactory::getDatabaseConnection();
	$sql = 'Select uid from ' . $db->prefix('users') . " where uname = '" .$UserName . "' LIMIT 0,1" ;

	$result = $db->query($sql);
	if($result){
		if ($db->getRowsNum($result) == 1){
			$member = mysql_fetch_object( $result ) ;
			$userid = $member->uid;
			$rc = $userid;
		}
	}
	return $rc;
} 
?>