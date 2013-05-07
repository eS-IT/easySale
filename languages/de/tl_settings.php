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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['easysaleEmail']         = array('E-Mail-Adresse des Verkäufers', 'Bitte geben Sie die E-Mailadresse des Verkäufers ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleSubject']       = array('Betreff', 'Bitte geben Sie den Betreff der Bestätigungsmail ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleHtml']          = array('Mailtext', 'Bitte geben Sie den Text der Bestätigungsmail ein. Auf die Felder der Transaktion greifen Sie mit {{paypal::FELDNAME}} zu. Als Platzhalter für den Link benutzen Sie bitte {{paypal::link}}.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleDownload']      = array('Downloadtext', 'Bitte geben Sie den Text für die Downloadseite ein. Als Platzhalter für den Link benutzen Sie bitte {{download::link}}.');
$GLOBALS['TL_LANG']['tl_settings']['usesandbox']            = array('Testmodus einschalten', 'Im Testmodus wird die PayPal-Sandbox verwendet (Anmeldung erforderlich). Die Transaktionen werden nur simuliert.');
$GLOBALS['TL_LANG']['tl_settings']['easysaledebug']         = array('Debug-Modus', 'Im Debug-Modus wird bei jeder Transaktion ein ausführliches Log geschrieben. Dieser Modus sollte niemals im Produktivsystem eingeschalteet sein!');
$GLOBALS['TL_LANG']['tl_settings']['easysaleProduktnumber'] = array('Produktnummer', 'Bitte geben Sie die Vorgabe für die Produktnummer ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleSetHeadline']   = array('Produktüberschrift anzeigen', 'Soll für die Produkte standardmäßig eine Überschrift angezeigt werden?');
$GLOBALS['TL_LANG']['tl_settings']['easysalePrice']         = array('Preis', 'Bitte geben Sie die Vorgabe für den Preis des Produkts ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleTax']           = array('Steuer', 'Bitte wählen Sie die Vorgabe für die Steuer des Produkts aus.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleNote']          = array('Beschreibung', 'Bitte geben Sie die Vorgabe für die Beschreibung des Produkts ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleJumpTo']        = array('Weiterleitungsseite', 'Bitte wählen Sie die Vorgabe für die Seite aus, zu der Besucher beim Anklicken eines Links oder Abschicken eines Formulars weitergeleitet werden.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleCategorie']     = array('Kategorie', 'Bitte geben Sie die Vorgabe für die Kategorie des Produkts ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleProductfields'] = array('Auswahl der Felder', 'Bitte wählen Sie die Vorgabe für die anzuzeigenden Felder.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleSeller_mail']   = array('PayPal-Adresse', 'Bitte geben Sie die Vorgabe für die PayPal-E-Mailadresse des Verkäufers ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleProduct_active']= array('Aktives Produkt', 'Sollen neue Produkte sofort auf aktiv gesetzt werden?');
$GLOBALS['TL_LANG']['tl_settings']['easysaleAgbbox']        = array('Text für Checkbox zur Bestätigung der AGB', 'Bitte geben Sie die Vorgabe für den Text für die Checkbox zur Bestätigung der AGB und der Widerrufsbelehrung ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleVersand']       = array('Heinweistext-Versandkosten', 'Bitte geben Sie den Hinweistext für die Versandkosten ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleAblauf']        = array('Ablaufhinweis', 'Bitte geben Sie den Ablaufhinweis ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleAgbzusatz']     = array('Zusatzbemerkung zu AGB', 'Bitte geben Sie die Zusatzbemerkung zu den AGB ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleAnbieter']      = array('Anbieter', 'Bitte geben Sie die vollständigen Angaben des Anbeiters ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleAgb']           = array('AGB', 'Bitte geben Sie Ihre AGB ein.');
$GLOBALS['TL_LANG']['tl_settings']['easysaleWider']         = array('Widerrufsbelehrung', 'Bitte geben Sie Ihre Widerrufsbelehrung ein.');

/*
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['easySale_legend']       = 'easySale-Mail';
$GLOBALS['TL_LANG']['tl_settings']['download_legend']       = 'easySale-Downloadtext';
$GLOBALS['TL_LANG']['tl_settings']['usesandbox_legend']     = 'easySale-Debug';
$GLOBALS['TL_LANG']['tl_settings']['easysaleDefault_legend']= 'easySale-Vorgabewerte';
$GLOBALS['TL_LANG']['tl_settings']['rights_legend']         = 'easySale-rechtliche Angaben';