<?php

### Konfiguration ###

# Bitte passen Sie die folgenden Werte an, bevor Sie das Script benutzen!

# An welche Adresse sollen die Mails gesendet werden? Dies ist die Default-Adresse, der eigentliche Empfängeer wird im Formular mitgegeben und unten ausgewertet
$strEmpfaenger = 'andreasdirscherl@googlemail.com';

# Welche Adresse soll als Absender angegeben werden?
# (Manche Hoster lassen diese Angabe vor dem Versenden der Mail ueberschreiben)
$strFrom       = '"=?iso-8859-1?Q?' . imap_8bit('Kontaktformular M�nchner Alexander-Technik Kooperation'). '?=' . '"<kontakt@muenchen-alexander-technik.de>';

# Welchen Betreff sollen die Mails erhalten?
$strSubject    = '=?iso-8859-1?Q?' . imap_8bit('Kontaktformular M�nchner Alexander-Technik Kooperation'). '?=';

# Zu welcher Seite soll als "Danke-Seite" weitergeleitet werden?
# Wichtig: Sie muessen hier eine gueltige HTTP-Adresse angeben!
$strReturnhtml = 'http://'.$_SERVER[HTTP_HOST].'/kontaktdanke.html';

# Welche(s) Zeichen soll(en) zwischen dem Feldnamen und dem angegebenen Wert stehen (alt. Für reine Textmail)
$strDelimiter  = ":\t";

### Ende Konfiguration ###

#Empfänger vorbelegen, falls übergeben
$to = "";
if ($_GET["To"])
{
	$to = $_GET["To"];
}
if($_POST)
{

# Hier wird die eigentliche Empfängeradsresse ermittelt und eingetragen, dies machen wir hier um die Mailadressen im Formular vor Spammern zu verbergen. Außerdem legen wir bei der Gelegenheit gleich noch das zugehörige Minibild fest. 
if ($_POST["Empfaenger"])
 {
	switch ($_POST["Empfaenger"]) {
		case "Pia":
			$strEmpfaenger = "info@qf-alexander-technik.de";
			break;
		case "Andrea":
			$strEmpfaenger = "aj.rohac@googlemail.com";
			break;
		case "Dagmar":
			$strEmpfaenger = "info@alexander-technik-thuernagel.de";
			break;
		case "Jana":
			$strEmpfaenger = "info@steppiano.de";
			break;
		case "Andreas":
			$strEmpfaenger = "alexandertechnik@andreasdirscherl.de";
			break;
		case "Gudrun":
			$strEmpfaenger = "ausbildung@fmat.de";
			break;
		case "Jutta":
			$strEmpfaenger = "jutta-hillebrand@web.de";
			break;
		case "Maleen":
			$strEmpfaenger = "m.schultka@googlemail.com";
			break;
		default:
#			$strEmpfaenger = "kontakt@muenchen-alexander-technik.de";
			$strEmpfaenger = 'info@qf-alexander-technik.de, aj.rohac@googlemail.com, info@alexander-technik-thuernagel.de, info@steppiano.de, alexandertechnik@andreasdirscherl.de, ausbildung@fmat.de, jutta-hillebrand@web.de, m.schultka@googlemail.com';
	}  #  END switch  
} # END if

# Hier wird die eigentliche Absenderadsresse ermittelt und als auch Mail-Absender eingetragen, falls vorhanden
 if ($_POST["Mail"])
 {
    $strFrom = ($_POST["Vorname"] ? $_POST["Vorname"] . ' ' : '') . $_POST["Name"] . '<'. $_POST["Mail"] . '>';
 }


# SPAMMER-Test, falls unerlaubte Zeichen im Body sind, keine Mail senden
 $strMailtext = "";
 while(list($strName,$value) = each($_POST))
 {
  if(is_array($value))
  {
   foreach($value as $value_array)
   {
    $strMailtext .= $strName.$strDelimiter.$value_array."\n\n";
   }
  }
  else
  {
   $strMailtext .= $strName.$strDelimiter.$value."\n\n";
  }
 }
   $strMailtext = stripslashes($strMailtext);
   if (preg_match("/(content-type:|bcc:|cc:|to:|from:)/im", $strMailtext)) die ("Unerlaubte Zeichenkette im Nachrichtentext!");
   $strMailtext = preg_replace( "/(content-type:|bcc:|cc:|to:|from:)/im", "", $strMailtext);

// HTML Mail Content erzeugen, hierzu verwende ich die HTML Vorlage "/_misc/Kontakt-Mail Vorlage.html" 

$content = '
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
<title>Kontaktformular - M&uuml;nchner Alexander-Technik Kooperation</title>
</head>
<body style="color:#f5f5f5; font-family:Arial, Helvetica, sans-serif; font-size: 12px">
<table width="690" cellpadding="0" cellspacing="0" bgcolor="#f5f5f5" style="border: 1px solid #CCC; border-collapse: collapse; color: #555; font-size: 12px; ">
<tr>
<td><h1 style="margin:10px 15px">Kontaktformular M&uuml;nchner Alexander-Technik Kooperation</h1></td>
<td><img src="http://www.muenchen-alexander-technik.de/_images/m-a-t-Logo.png" alt="Logo" width="180" height="180" title="Logo"></td>
</tr>
<tr>
	<td colspan="2">
    <table width="690" cellpadding="10" cellspacing="0" bgcolor="#888888" border="0" style="font-size: 12px; border-collapse:collapse; color:#f5f5f5 ">
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7">Empf&auml;nger</td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold"> '. $_POST["Empfaenger"] . '<img src="http://m-a-t.andreasdirscherl.de/_images/select'. $_POST["Empfaenger"] .'.jpg" width="30" height="30" style="margin-left:10px">'. '</td>
      </tr>
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7"> Absender:</td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold">'. ($_POST["Vorname"] ? $_POST["Vorname"] . ' ' : '') . $_POST["Name"] .'</td>
      </tr>
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7">Telefonnummer:</td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold">'. $_POST["Telefon"] .'</td>
      </tr>
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7"> E-Mail-Adresse:</td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold"><a style="text-decoration: underline; font-weight:bold; color:#f5f5f5;" href="mailto:'. $_POST["Mail"] .'">'. $_POST["Mail"] .'</a></td>
      </tr>
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7">Stra&szlig;e: </td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold"> '. $_POST["Strasse"] .'</td>
      </tr>
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7">PLZ / Ort:</td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold"> '. $_POST["PLZ"] .' '. $_POST["Ort"] .'</td>
      </tr>
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7"> Interesse an:</td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold"><input type="checkbox" name="EinzelArbeit" disabled '. ($_POST["EinzelArbeit"] === 'on' ? 'checked' : '' ).'>
Einzel-Arbeit/Termin <br>
<input type="checkbox" name="Workshop" disabled '. ($_POST["Workshop"] === 'on' ? 'checked' : '' ).'>
Workshop/Seminar<br>
<input type="checkbox" name="Allgemein" disabled '. ($_POST["Allgemein"] === 'on' ? 'checked' : '' ).'>
Allgemein </td>
      </tr>
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7">Aufmerksam geworden: </td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold">' . stripslashes(nl2br($_POST["Aufmerksam-Geworden"])) .'</td>
      </tr>
      <tr>
        <td style="width: 170px; border: 1px solid #CCC; background-color:#a7a7a7">Nachricht: </td>
        <td style="width: 470px; border: 1px solid #CCC; font-weight:bold">' . stripslashes(nl2br($_POST["Nachricht"])) .'</td>
      </tr>
  </table></td></tr>
</table>
</body>
</html>
';

// To send HTML mail, the Content-type header must be set
$headers = "From: ". $strFrom . "\r\n";
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
// $headers .= "To:" . $strEmpfaenger . "\r\n";

// Testempf�nger
# $strEmpfaenger = "andreas.dirscherl@web.de, andreas.dirscherl@yahoo.de"; 

// Mail it
 mail($strEmpfaenger, $strSubject, $content, $headers) 
  or die("Die Mail konnte nicht versendet werden.");
 header("Location: $strReturnhtml");
 exit;
}

?>
