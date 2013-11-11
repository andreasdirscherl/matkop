<?php

### Konfiguration ###

# Bitte passen Sie die folgenden Werte an, bevor Sie das Script benutzen!

# An welche Adresse sollen die Mails gesendet werden? Dies ist die Default-Adresse, der eigentliche Empfängeer wird im Formualr mitgegeben und unten ausgewert
$strEmpfaenger = 'andreasdirscherl@googlemail.com';

# Welche Adresse soll als Absender angegeben werden?
# (Manche Hoster lassen diese Angabe vor dem Versenden der Mail ueberschreiben)
$strFrom       = '"Kontaktformular Münchner Alexander-Technik Kooperation" <kontakt@muenchen-alexander-technik.de>';

# Welchen Betreff sollen die Mails erhalten?
$strSubject    = 'Kontaktformular Münchner Alexander-Technik Kooperation';

# Zu welcher Seite soll als "Danke-Seite" weitergeleitet werden?
# Wichtig: Sie muessen hier eine gueltige HTTP-Adresse angeben!
$strReturnhtml = 'http://'.$_SERVER[HTTP_HOST].'/kontaktdanke.html';

# Welche(s) Zeichen soll(en) zwischen dem Feldnamen und dem angegebenen Wert stehen?
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

# Hier wird die eigentliche Empfängeradsresse ermittelt und eingetragen, dies machen wir hier um die Mailadressen im Formular vor Spammern zu verbergen.
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

# Hier wird die eigentliche Absenderadsresse ermittelt und eingetragen, falls vorhanden
 if ($_POST["Mail"])
 {
    $strFrom = ($_POST["Vorname"] ? $_POST["Vorname"] . ' ' : '') . $_POST["Name"] . '<'. $_POST["Mail"] . '>';
 }


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

 mail($strEmpfaenger, $strSubject, $strMailtext, "From: ".$strFrom)
  or die("Die Mail konnte nicht versendet werden.");
 header("Location: $strReturnhtml");
 exit;
}

?>
