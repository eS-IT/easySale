<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   eS_easysale
 * @author    Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @link      http://easySolutionsIT.de
 * @license   LGPL
 * @copyright e@sy Solutions IT 2013
 */


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['easySale']['error']['noproduct']        = '<h1>Danke für Ihren Einkauf</h1><div class="info noData">Sie erhalten in Kürze eine E-Mail mit allen nötigen Informationen zum Herunterladen der Datei. Falls diese E-Mail nicht ankommt, prüfen Sie bitte den Spam-Ordner ob sie irrtümlich dort gelandet ist.<br><br>Falls die E-Mail gar nicht ankommt, wenden Sie sich bitte an den Seiteninhaber (siehe Impressum).</div>';
$GLOBALS['TL_LANG']['MSC']['easySale']['error']['noRidirect']       = '<h1>Automatische Weiterleitung deaktiviert!</h1>Aus Sicherheitsgründen wurde die automatische Weiterleitung deaktiviert! Bitte prüfen Sie den Link. Wenn Sie der Meinung sind, dass er korekkt ist, können Sie ihm manuell aufrufen.<br /> <a href="%s">%s</a>';
$GLOBALS['TL_LANG']['MSC']['easySale']['error']['noTransaction']    = 'Die Transaktion wurde nicht gefunden! Es kann sich um einen Manipulationsversuch handeln. Bitte setzen Sie sich mit dem Seitenbetreiber in Verbindung!';
$GLOBALS['TL_LANG']['MSC']['easySale']['error']['wrongTransaction'] = 'Die übermittelten Daten passen nicht zur Transaktion! Es kann sich um einen Manipulationsversuch handeln. Bitte setzen Sie sich mit dem Seitenbetreiber in Verbindung!';
$GLOBALS['TL_LANG']['MSC']['easySale']['error']['noFile']           = 'Die Datei zu dieser Transaktion konnte nicht gefunden werden. Bitte setzen Sie sich mit dem Seitenbetreiber in Verbindung.';
$GLOBALS['TL_LANG']['MSC']['easySale']['error']['noDownload']       = 'Zu diesem Transaktionscode wurde kein Downlaod gefunden. Dies kann daran liegen, dass die Zahlungsbestätigung von PayPal noch nicht eingetroffen ist. <br />Bitte warten Sie einen Augenbilck und laden dann die Seite neu. Sollte dies wiederholt auftreten, setzen Sie sich bitte mit dem Seitenbetreiber in Verbindung!<br />Transactionscode: <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['easySale']['error']['noCheckboxes']     = 'Sie müssen die AGB und die Widerrufsbelehrung bestätigen, um fortfahren zu können.';

$GLOBALS['TL_LANG']['MSC']['easySale']['mail']['download']          = 'Vielen Dank, Ihre Zahlung wurde bestätigt. Sie können die Datei nun herunterladen: {{download::link}}';

$GLOBALS['TL_LANG']['MSC']['easySale']['agb']['easysaleAgbbox']     = 'Ich habe die Allgemeinen Geschäftsbedingungen (AGB) gelesen und akzeptiere sie.';
$GLOBALS['TL_LANG']['MSC']['easySale']['agb']['easysaleWiderbox']   = 'Ich habe die Widerrufsbelehrung gelesen und akzeptiere sie.';

$GLOBALS['TL_LANG']['MSC']['easySale']['output']['price']           = '%s &euro;';
$GLOBALS['TL_LANG']['MSC']['easySale']['output']['tax']             = ' inkl. %s &#037; MwSt.*';
$GLOBALS['TL_LANG']['MSC']['easySale']['output']['easysaleVersand'] = '<br /><small>* Es fallen keine Versandkosten an, da es sich um eine digitale Download-Datei handelt.</small> ';
$GLOBALS['TL_LANG']['MSC']['easySale']['output']['fileKind']        = '1 digitale Datei im %s-Format ';
$GLOBALS['TL_LANG']['MSC']['easySale']['output']['ablauf']          = '<strong>Bestellablauf:</strong><br />Wenn Sie auf den Bestell-Button klicken, werden Sie zum externen Zahlungsanbieter Paypal weitergeleitet. Nach erfolgter Bezahlung bei Paypal erhalten Sie eine E-Mail. Darin befindet sich der Download-Link mit dem Sie die digitale Datei im {{file::extension}} herunterladen können. Bitte beachten Sie, dass der Download nur maximal dreimal möglich ist. Falls Sie dabei irgendwelche technischen Schwierigkeiten haben, können Sie sich gerne an den Anbieter wenden ({{admin::mail}}). ';
$GLOBALS['TL_LANG']['MSC']['easySale']['output']['buttonText']      = 'Kostenpflichtig bestellen';
$GLOBALS['TL_LANG']['MSC']['easySale']['output']['detailbuttonText']= 'Details';
$GLOBALS['TL_LANG']['MSC']['easySale']['output']['easysaleAgbzusatz']= 'Sie können die AGB und die Widerrufsbelehrung über die Druckfunktion Ihres Internet-Browsers ausdrucken. Diese Texte werden zudem in der E-Mail mitgesandt, die Sie nach der Bestellung erhalten. Beide Texte stehen nur in deutscher Sprache zur Verfügung.';

$GLOBALS['TL_LANG']['MSC']['easySale']['redirect']['paypalHeadline']= 'Sie werden zu PayPal weitergeleitet';
$GLOBALS['TL_LANG']['MSC']['easySale']['redirect']['paypalLabel']   = 'Klicken Sie hier wenn Sie nicht in 10 Sekunden weitergeleitet werden.';

/*
 * Content elements
 */
$GLOBALS['TL_LANG']['CTE']['es_easysale']                           = array('easy Sale', 'Einfach Download-Produkte verkaufen.');
$GLOBALS['TL_LANG']['CTE']['tl_easysale_salesite']                  = array('easy Sale-PayPal', 'Das easy Sale-PayPal-Element.');


/*
 * easySale-Mail
 */
$GLOBALS['TL_LANG']['MSC']['easySale']['mail']['subject']           = "Vielen Dank für Ihren Einkauf. Ihr Downlad-Produkt wartet auf Sie.";
$GLOBALS['TL_LANG']['MSC']['easySale']['mail']['html']              = 'Bestellbestätigung:<br><br><br>Herzlichen Dank für Ihre Bestellung auf <a href="{{env::url}}">{{env::url}}</a>.<br><br>{{product::format}}<br>Gesamtpreis: {{product::price}} Euro (inkl. {{product::tax}} % MwSt.)<br><br>Zahlungsart: Zahlung über den externen Zahlungsanbieter Paypal.<br><br>Sie können die digitale Datei nun hier herunterladen:<br><br>{{product::link}}<br><br>Bitte beachten Sie, dass der Download nur maximal dreimal möglich ist. Falls Sie dabei irgendwelche technischen Schwierigkeiten haben, können Sie sich gerne an den Anbieter wenden.<br><br>-------------------------<br><br>Anbieterkennzeichnung:<br><br>{{offeror::text}}<br><br>-------------------------<br><br>Allgemeine Geschäftsbedingungen:<br><br>{{agb::text}}<br><br>-------------------------<br><br>Widerrufsbelehrung:<br><br>{{disclaimer::text}}<br><br>-------------------------<br>';