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
 * Namespace
 */
#namespace es_easysale;

// Post-Daten umspeichern, wegen dem Contao-Request-Token
$arrPost = $_POST;
unset($_POST);


// Load contao
define('TL_MODE', 'FE');
$contaoSystem = str_replace('/modules/es_easysale/public', '', dirname(__FILE__));
require($contaoSystem  . '/initialize.php');


/**
 * Class es_easysale
 *
 * @copyright  e@sy Solutions IT 2013
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @package    eS_easysale
 */
class insert_site extends \Frontend{


    /**
     * Erstellt eine Instanz der Klasse.
     */
    public function __construct(){
        $this->import('Database');
        $this->import('Input');
        $this->import('tl_easysale');
        $this->import('es_easysale');
        $this->import('es_paypal_inc');

        if(version_compare(VERSION . '.' . BUILD, '3.0.0', '<')){
            $this->import('Config');
        }

        $this->loadLanguageFile('default');
    }


    /**
     * Prueft die Daten und bietet die Datei zum Download an.
     */
    public function download($arrData){
        $strInvoice = $this->Input->get('invoice');

        if((!is_array($arrData) || !array_key_exists('invoice', $arrData)) && $strInvoice != ''){
            $this->es_easysale->writeLog('Keine Post-Daten! GET-invoice:', $strInvoice);
            $arrTrans = $this->tl_easysale->getTransaktion($strInvoice);
            return $this->myredirect($arrTrans['custom'], '');
        } else {

            $this->es_easysale->writeLog('Pruefe Download', $arrData);

            if($this->es_paypal_inc->testTransaction($arrData)){
                $this->es_easysale->writeLog('Transaktion getestet und bereit zum Download', $arrData);
                return $this->myredirect($arrData['custom'], $arrData['invoice']);
            } else {
                return $GLOBALS['TL_LANG']['MSC']['easySale']['error']['wrongTransaction'];
            }
        }
    }


    private function myredirect($strLink, $strInvoice){
        $strRedirect = ($strInvoice != '') ? $strLink . '?art=' . $strInvoice : $strLink;
        $this->es_easysale->writeLog('Adresse der Downloadseite', $strRedirect);

        $thisDomain = (strpos($strRedirect, 'http://' . $_SERVER['HTTP_HOST']) === 0) ? 'Redirect innerhalb dieser Domain: ' . $strRedirect : 'Redirect außerhalb der Domain: ' . $strRedirect;

        $this->es_easysale->writeLog('Pruefe die Adresse der Downloadseite', $thisDomain);
        if(strpos($strRedirect, 'http://' . $_SERVER['HTTP_HOST']) === 0){
            // Nur Weiterleiten, wenn das Ziel in der selben Domain liegt.
            $this->es_easysale->writeLog('Leite weiter auf Downloadseite: ', $strRedirect);

            // Contao-Weiterleitung funktioniert nicht auf allen Hosts!
            #$this->redirect($strRedirect, 301);

            // Manuelle Weiterleitung
            header("HTTP/1.1 301 Moved Permanently");
            header("Location:$strRedirect");

            return 'Wenn Sie nicht automatisch weitergeleitet werden, klicken Sie bitte <a href="' . $strRedirect . '">hier</a>.';
        } else {
            $strTemp = sprintf($GLOBALS['TL_LANG']['MSC']['easySale']['error']['noRidirect'], $strRedirect, $strRedirect);
            $this->es_easysale->writeLog($strTemp);
            return $strTemp;
        }
    }
}


// run
$is = new insert_site();
$strContent = $is->download($arrPost);

?>

<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>easysale for Contao</title>
    <meta name="description" content="easysale for Contao">
    <meta name="author" content="e@sy Solutions IT">
</head>
<body>
    <?php
        // HTML-Geruest, damit die Fehlermeldungen als HTML-Seite angezeigt werden koennen!
        echo $strContent;
    ?>
</body>
</html>