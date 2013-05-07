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
 * include paypal class
 */
#include_once('es_paypal_inc.php');


/**
 * Class es_paypal
 *
 * @copyright  e@sy Solutions IT 2013
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @package    eS_easysale
 */
class es_paypal extends \Frontend
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = '';


    /**
     * Url des ipn-Skripts
     * @var string
     */
    private $ipn = '';


    /**
     * Url bei erfolgreicher Zahlung
     * @var string
     */
    private $returnUrl = '';


    /**
     * Auf dieser Seite werden die von PaPal empfangenen Daten geprüft.
     * @var string
     */
    private $insertSite = '';


    /**
     * Url bei einem Fehler.
     * @var string
     */
    private $cancelUrl = '';


    /**
     * paypal object
     * @var null
     */
    private $paypal = null;


    /**
     * Erstellt eine Instanz der Klasse.
     */
    public function __construct(){
        $this->import('Database');
        $this->import('es_easysale');
        $this->import('es_paypal_inc');
        $this->import('Environment');
        $this->import('Input');
        $arrRequest         = explode('?', $this->Environment->request);
        $strRequest         = $arrRequest[0];
        $this->returnUrl    = $this->Environment->url . '/' . $strRequest;
        $this->cancelUrl    = $this->Environment->url . '/' . $strRequest . '?id=' . $this->Input->get('id');
        $this->insertSite   = $this->Environment->url . '/system/modules/es_easysale/public/insert_site.php';
        $this->ipn          = $this->Environment->url . '/system/modules/es_easysale/public/ipn.php';
    }


	/**
	 * Generate the module
	 */
	protected function compile(){
	}


    /**
     * Wickelt die Transaktion ab.
     * @param $intproductId
     */
    public function buy($intproductId, $strTable = 'tl_easysale_product'){
        if($GLOBALS['EASY_SALE']['PP']['use_sandbox'] || $GLOBALS['TL_CONFIG']['usesandbox']){
            $this->es_paypal_inc->useSandbox();
        }

        $this->setupPayPal($intproductId, $strTable);
        $strContent = $this->es_paypal_inc->parseForm();

        return $strContent;
    }


    /**
     * Liesst die Artikeldaten aus der DB aus.
     * @param $intproductId
     * @return bool
     */
    private function getproductData($intproductId, $strTable){
        $query  = 'SELECT * FROM `' . $strTable . '` WHERE `id` = ' . $intproductId;
        $result = $this->Database->query($query);

        if($result->numRows){
            return $result->fetchAssoc();
        } else {
            return false;
        }
    }


    /**
     * Setzt die Einstellungen fuer den Kauf.
     * @param $arrData
     */
    private function setupPayPal($intproductId, $strTable){
        $arrData = $this->getproductData($intproductId, $strTable);

        if($arrData){
            $strPrice                   = $this->getSettings($arrData['product_price'], $GLOBALS['TL_CONFIG']['product_price']);
            $strPrice                   = (substr_count($strPrice, ',')) ? str_replace(',', '.', $strPrice) : $strPrice;
            $arrPayPal['ipn']           = $this->ipn;
            $arrPayPal['custom']        = $this->returnUrl;     // die return-Seite leitet nach dem Speichern der Daten auf die in custom gespeicherten Seite weiter. so wird der Contao-Request-Token-Error umgangen!
            $arrPayPal['cancel_return'] = $this->cancelUrl;
            $arrPayPal['quantity']      = 1;
            $arrPayPal['business']      = $this->getSettings($arrData['seller_mail'], $GLOBALS['TL_CONFIG']['seller_mail']);
            $arrPayPal['item_number']   = $this->getSettings($arrData['product_number'], $GLOBALS['TL_CONFIG']['product_number']);
            $arrPayPal['item_name']     = $this->getSettings($arrData['product_name'], $GLOBALS['TL_CONFIG']['product_name']);
            $arrPayPal['amount']        = number_format(floatval($strPrice), 2, '.', '');
            $arrPayPal['invoice']       = $this->makeTransactinCode($arrPayPal);
            $arrPayPal['return']        = $this->insertSite . '?invoice=' . $arrPayPal['invoice'];    // retrun gaht auf die Seite, die die Daten Speichert.

            $this->es_paypal_inc->addArray($arrPayPal);
            $this->saveTransaction($arrPayPal, $arrData);
        }
    }


    /**
     * Gibt einen Wert entweder aus dem Data-Array oder aus den Einstellungen zurück,
     * wenn kein entsprchender Wert im Data-Array vorhanden ist.
     * @param $strData
     * @param $strSettings
     * @return mixed
     */
    private function getSettings($strData, $strSettings){
        if($strData != ''){
            $arrData = @unserialize($strData);

            if(is_array($arrData)){
                return array_shift($arrData);
            } else {
                return $strData;
            }
        } else {
            $arrSettings = @unserialize($strSettings);

            if(is_array($arrSettings)){
                return array_shift($arrSettings);
            } else {
                return $strSettings;
            }
        }
    }


    /**
     * Speichert eine Transaktion.
     * @param $arrPayPal
     * @param $arrData
     */
    private function saveTransaction($arrPayPal, $arrData){
        $arrPayPal['pid']       = $arrData['id'];
        $arrPayPal['mc_gross']  = $arrPayPal['amount'];           // Fuer den spaeteren Vergleich, ob die Summe passt, es MUSS so gemacht werden !!!
        $this->es_easysale->saveTransaction($arrPayPal);
    }


    /**
     * Erzeugt eine eindeutige Transaktions-ID.
     * @param $arrPayPal
     * @return string
     */
    private function makeTransactinCode($arrPayPal){
       return sha1(serialize($arrPayPal) . uniqid(rand(), true) . $GLOBALS['TL_CONFIG']['encryptionKey']);
    }
}
