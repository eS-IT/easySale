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


/**
 * Class es_easysale
 *
 * @copyright  e@sy Solutions IT 2013
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @package    eS_easysale
 */
class es_easysale extends \Controller{


    /**
     * Erstellt eine Instanz der Klasse.
     */
    public function __construct(){
        $this->import('Database');
        $this->import('Environment');
        $this->import('tl_easysale');
    }


    public function mailCustomer($arrData){
        if(is_array($arrData) && (!array_key_exists('error' , $arrData) || (array_key_exists('error' , $arrData) && $arrData['error'] == ''))){
            if(version_compare(VERSION . '.' . BUILD, '3.0.0', '>')){
                //Contao 3.x.x
                $email = new \Contao\Email();
            } else {
                // Contao 2.11.x
                $email = new Email();
                $this->import('Config');
            }

            $this->loadLanguageFile('default');

            // Workaround: no language file on server
            if(!is_array($GLOBALS['TL_LANG']['MSC']['easySale'])){
                $strFile = dirname(__FILE__) . '/languages/de/default.php';
                include_once($strFile);
                $this->writeLog('Lade Language-File: ', $strFile);
            }

            $mailText                   = ($GLOBALS['TL_CONFIG']['easysaleHtml'] != '') ? $GLOBALS['TL_CONFIG']['easysaleHtml'] : $GLOBALS['TL_LANG']['MSC']['easySale']['mail']['html'];
            $strSubject                 = (array_key_exists('easysaleSubject', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['easysaleSubject'] != '') ? $GLOBALS['TL_CONFIG']['easysaleSubject'] : $GLOBALS['TL_LANG']['MSC']['easySale']['mail']['subject'];
            $strFrom                    = ($GLOBALS['TL_CONFIG']['easysaleEmail'] != '') ? $GLOBALS['TL_CONFIG']['easysaleEmail'] : $GLOBALS['TL_CONFIG']['adminEmail'];
            $strLink                    = $arrData['custom'] . '?art=' .  $arrData['invoice'] . '&token=' . $arrData['verify_sign'];
            $arrProduct                 = $this->tl_easysale->getproductById($arrData['pid']);
            $arrTags['product::link']   = sprintf('<a href="%s">%s</a>', $strLink, $strLink);
            $arrTags['product::format'] = $this->getFileFormate($arrProduct);
            $arrTags['product::price']  = str_replace('.', ',', $arrData['amount']);
            $arrTags['product::tax']    = str_replace('.', ',', $arrData['tax']);
            $arrTags['product::name']   = $arrData['item_name'];
            $arrTags['product::number'] = $arrData['item_number'];
            $arrTags['offeror::text']   = $GLOBALS['TL_CONFIG']['easysaleAnbieter'];
            $arrTags['agb::text']       = $GLOBALS['TL_CONFIG']['easysaleAgb'];
            $arrTags['disclaimer::text']= $GLOBALS['TL_CONFIG']['easysaleWider'];
            $arrTags['page::rootTitle'] = $GLOBALS['TL_CONFIG']['websiteTitle'];

            // Die Tags der Erweiterung VOR den Contao-Tags ersetzen!
            foreach ($arrTags as $key => $value) {
                $mailText = str_replace('{{' . $key . '}}' , $value, $mailText);
            }

            $mailText = $this->replaceInsertTags($mailText);

            $this->writeLog('Versende die Mail an den Kaeufer.', $arrData);
            $this->writeLog('Folgende Tags werden ersetzt:', $arrTags);
            $this->writeLog('Absender erstellt:', $strFrom);
            $this->writeLog('Betreff erstellt:', $strSubject);
            $this->writeLog('Mailtext erstellt:', $mailText);

            $email->charset = 'UTF-8';
            $email->html    = $mailText;
            $email->subject = $strSubject;
            $email->from    = $strFrom;
            $email->sendTo($arrData['payer_email']);
            return true;
        } else {
            $this->writeLog('Mail-Error: Keine oder fehlerhafte Daten erhalten!', $arrData);
            return false;
        }
    }


    /* ======================= *
     * Transaction-DB-Methoden *
     * ======================= */

    /**
     * Speichert eine Transaktion.
     * @param $arrData
     * @param string $strTable
     * @return array
     */
    public function saveTransaction($arrData, $strTable = 'tl_easysale_transactions'){
        $this->writeLog('Suche die Transaktion');
        $arrData['transaction_ip']  = $this->Environment->ip;
        $arrData['tstamp']          = time();
        $this->writeLog('Suche die Transaktion [' . $arrData['invoice'] . '] in der Tabelle [' . $strTable . ']');

        if($this->getTransId($arrData['invoice'])){
            $this->writeLog('Transaktion [' . $arrData['invoice'] . '] gefunden', $this->getTransId($arrData['invoice']));
            $strQuery = 'UPDATE ' . $strTable . ' SET %s WHERE invoice = "' . $arrData['invoice'] . '"';
        } else {
            $this->writeLog('Transaktion [' . $arrData['invoice'] . '] nicht gefunden', $this->getTransId($arrData['invoice']));
            $strQuery = 'INSERT INTO ' . $strTable . ' SET %s';
        }

        $arrFieldNames = $this->Database->getFieldNames($strTable);

        $strTmp = '';
        foreach($arrData as $k => $v){
            if(in_array($k, $arrFieldNames)){
                $strTmp .= '`' . $k . '` = "' . $v . '", ';
            }
        }

        $strTmp     = substr($strTmp, 0, -2);
        $strQuery   = sprintf($strQuery, $strTmp);
        $this->writeLog('Speichere die Transaktion.', $strQuery);
        $result     = $this->Database->query($strQuery);
        $this->writeLog('Transaktion gespeichert.', $result);
        return $this->getTransByInvoice($arrData['invoice']);
    }


    /**
     * Laedt eine Transaction.
     * @param $strInvoice
     * @param string $strTable
     * @return bool
     */
    public function getTransByInvoice($strInvoice, $strTable = 'tl_easysale_transactions'){
        $this->import('Database');
        $query  = "SELECT * FROM `$strTable` WHERE `invoice` = '" . htmlspecialchars($strInvoice) . "'";
        $this->writeLog('Lade die Transaktion mit der Invoice-Id [' . $strInvoice . ']', $query);
        $result = $this->Database->query($query);

        if($result->numRows){
            $arrData = $result->fetchAssoc();
            $this->writeLog('Transaktion mit der Invoice-Id [' . $strInvoice . '] gefunden.', $arrData);
            return $arrData;
        } else {
            $this->writeLog('KEINE Transaktion mit der Invoice-Id [' . $strInvoice . '] gefunden!');
            return false;
        }
    }


    /**
     * Gibt die Id einer Transaktion zurueck.
     * @param $strInvoice
     * @param string $strTable
     * @return bool
     */
    private function getTransId($strInvoice, $strTable = 'tl_easysale_transactions'){
        $query  = 'SELECT `id` FROM `' . $strTable . '` WHERE `invoice` = "' . htmlspecialchars($strInvoice) . '"';
        $result = $this->Database->query($query);
        if($result->numRows){
            return $result->id;
        } else {
            return false;
        }
    }


    /**
     * Erhaelt eine Id (pid in der Transaktionstabelle) und gibt den Artikel zurueck.
     * @param $strInvoice
     * @param string $strTable
     * @return bool
     */
    public function getproductData($intId, $strTable = 'tl_easysale_product'){
        if($intId){
            $query  = "SELECT * FROM `$strTable` WHERE `id` = $intId";
            $this->writeLog('Lade die Artikeldaten.', $query);
            $result = $this->Database->query($query);

            if($result->numRows){
                $this->writeLog('Artikeldaten gefunden.', $result);
                return $result->fetchAssoc();
            }
        }

        return false;
    }


    /**
     * Erstellt einen Log-Eintrag.
     * @param $strMsg
     * @param null $varData
     * @param bool $bolBreak
     * @param bool $bolEmptyFile
     */
    public function writeLog($strMsg, $varData = null, $bolBreak = false, $bolEmptyFile = false){
        $logfile    = TL_ROOT . '/system/modules/es_easysale/log/paypal.log';
        $arrBack    = debug_backtrace();
        $arrOutput  = array(
            'Date'      => date($GLOBALS['TL_CONFIG']['dateFormat']),
            'Time'      => date('H:i:s'),
            'File'      => $arrBack[0]['file'],
            'Method'    => $arrBack[1]['class'] . '::' . $arrBack[1]['function'] . '()',
            'Line'      => $arrBack[0]['line'],
            'Message'   => $strMsg,
            'Data'      => (is_array($varData) || is_object($varData)) ? "\n" . substr(print_r($varData, true), 0, -1) : $varData
        );

        if($GLOBALS['EASY_SALE']['PP']['debug'] || $GLOBALS['TL_CONFIG']['easysaledebug']){

            if($bolBreak){
                file_put_contents($logfile, "\n" . str_repeat('=', 80) . "\n" . str_repeat('#', 80) . "\n" . str_repeat('=', 80) . "\n", FILE_APPEND);
            }

            if($bolEmptyFile){
                file_put_contents($logfile, str_repeat('=', 80) . "\n\n");
            } else {
                file_put_contents($logfile, "\n" . str_repeat('=', 80) . "\n\n", FILE_APPEND);
            }

            foreach ($arrOutput as $key => $value) {
                $strContent = ($key != 'Message') ? "$key:\t\t$value\n" : "$key:\t$value\n";

                if($value != ''){
                    $strContent = ($key == 'Data') ? "\n" . $strContent : $strContent;
                    file_put_contents($logfile, $strContent, FILE_APPEND);
                }
            }
        }
    }


    /**
     * Gibt den Typ einer Datei zurueck.
     * @param $arrProduct
     * @return string
     */
    public function getFileFormate($arrProduct){
        if(is_numeric($arrProduct['product_path'])){
            $this->import('tl_easysale');
            $strPath = $this->tl_easysale->getPath($arrProduct['product_path']);
        } else {
            $strPath = $arrProduct['product_path'];
        }

        $arrFile    = explode('.', $strPath);
        $strExt     = strtoupper(array_pop($arrFile));
        $strTxt     = sprintf($GLOBALS['TL_LANG']['MSC']['easySale']['output']['fileKind'], $strExt);
        return $strTxt;
    }
}
