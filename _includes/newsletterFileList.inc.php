<?php
// Diese Funktion geht das Newletter-Verzeichnis durch und liefert alle Dateien zurück, die unsere Newsletter-Namenskonvetion entsprechen
// Konvention: 'newsletter-' + Jahr + "-" + "Monat" + "-" + Titel + "html"
$newsletterDir = "./";
// Konvention: Alle Newsletter beginnen mit: 'newslettter+Jahr', z.B. newsletter2011-12.html         
$trenner = "-";
$newsletterNameStart = "newsletter" . $trenner; 
$monat = Array("", "Januar", "Februar", "M&auml;rz", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
$allFilenames = Array();
$umlautErsatz = array("ae", "oe", "ue");
$umlautHTML = array("&auml;", "&ouml;", "&uuml;");
if ( $handle = opendir($newsletterDir) ) {
	while ( false !== ($filename = readdir($handle)) ) {	
		if (substr( pathinfo($filename, PATHINFO_BASENAME), 0,  strlen ($newsletterNameStart)) == $newsletterNameStart ) {
			$allFilenames[] = $filename;
		}
	}
}
natsort($allFilenames); // Sortieren nach Datum
$allFilenames = array_reverse($allFilenames); // Umdrehen -> Neueste zuerst
foreach ($allFilenames as $filename) {
	$infos = explode($trenner, $filename);
	$titel = str_replace("_"," ",pathinfo($infos[3], PATHINFO_FILENAME)); // Unterstrich im Dateinamen durch Leerzeichen ersetzen
	$titel = str_replace($umlautErsatz, $umlautHTML, $titel); // Umlaute im Dateinamen durch HTML ersetzen
	$monatText = $monat[($infos[2])];
	$jahr = $infos[1];
	$datum = strchr(pathinfo($filename, PATHINFO_FILENAME),"-");
	echo '<p><a href="'.$filename . '" target="_blank">'. $titel . ' - ' . $monatText . ' ' . $jahr . '</a><br /></p>';
	echo "\n\t";
}
?>
