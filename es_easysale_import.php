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
class es_easysale_import extends \Backend{


    /**
     * Zu importierende Felder
     * @var array
     */
    private $arrDlArtFields = array('fb_downloadartikel_dateiurl', 'fb_downloadartikel_paypalemail', 'fb_downloadartikel_artikelname', 'fb_downloadartikel_nummer', 'fb_downloadartikel_preis', 'fb_downloadartikel_umsatzsteuerprozent', 'fb_downloadartikel_artikelbeschreibung');


    /**
     * Felder der Erweiterung es_easysale
     * @var array
     */
    private $arrFields = array('fb_downloadartikel_dateiurl' => 'product_path', 'fb_downloadartikel_paypalemail' => 'seller_mail', 'fb_downloadartikel_nummer' => 'product_name', 'fb_downloadartikel_artikelname' => 'product_category', 'fb_downloadartikel_preis' => 'product_price', 'fb_downloadartikel_umsatzsteuerprozent' => 'product_tax', 'fb_downloadartikel_artikelbeschreibung' => 'product_note');


    /**
     * Erstellt eine Instanz der Klasse.
     */
    public function __construct(){
        $this->import('Database');
    }


    /**
     * Fuehrt den Import der Artikel aus der Erweiterung downloadartikel durch.
     */
    public function importFromDlArt(){
        $arrDlArt   = $this->getDlArtData();
        $arrData    = $this->handleData($arrDlArt);
        $this->saveData($arrData);
        return $this->makeOutput($arrData);
    }


    /**
     * Laedt die Artikel der Erweiterung downloadartikel durch.
     * @param string $strTable
     * @return array
     */
    private function getDlArtData($strTable = 'tl_content'){
        $arrData    = array();
        $strQuery   = "SELECT * FROM `$strTable` WHERE `fb_downloadartikel_dateiurl` != ''";
        $result     = $this->Database->query($strQuery);

        if($result->numRows){
            $i = 0;
            while($result->next()){
                foreach ($this->arrDlArtFields as $strField) {
                    $arrData[$i][$strField] = $result->$strField;
                }

                ++$i;
            }
        }

        return $arrData;
    }


    /**
     * Stellt ein Array mit den Daten der Erweiterung downloadartikel zusammen, dass zur DB der Erweiterung es_easysale passt.
     * @param $arrDlArt
     * @return array
     */
    private function handleData($arrDlArt){
        $arrData    = array();
        $i          = 0;

        foreach($arrDlArt as $arrRow) {
            $arrData[$i]['tstamp']              = time();
            $arrData[$i]['product_active']      = 1;

            foreach($arrRow as $key => $value) {
                if($key == 'fb_downloadartikel_dateiurl'){
                    $value = $this->getPath($value);
                }

                $arrData[$i][$this->arrFields[$key]] = htmlspecialchars($value, ENT_QUOTES);
            }

            $arrData[$i]['product_number'] = $arrData[$i]['product_name'];
            ++$i;
        }

        return $arrData;
    }


    private function getPath($strData){
        if(version_compare(VERSION . '.' . BUILD, '3.0.0', '>')){
            $this->import('Database');
            $query  = "SELECT `id` FROM `tl_files` WHERE `path` = '$strData'";
            $result = $this->Database->query($query);
            if($result->numRows){
                return $result->id;
            } else {
                return $strData;
            }
        } else {
            return $strData;
        }
    }


    private function saveData($arrData, $strTable = 'tl_easysale_product'){
        // Contao quotet die Feldnamen nicht richtig!
        foreach ($arrData as $arrRow) {
            $strQuery   = "INSERT INTO `$strTable` SET ";

            foreach ($arrRow as $key => $value) {
                $strQuery .= "`$key` = '$value', ";
            }

            $strQuery = substr($strQuery, 0, -2);

            $this->Database->query($strQuery);
        }
    }


    private function makeOutput($arrData){
        $strOutput  = '';
        $strOutput .= '<div class="block" style="padding: 10px;">';
        $strOutput .= '<a href="contao/main.php?do=es_product" class="header_back" title="" accesskey="b" onclick="Backend.getScrollOffset()" style="margin-left: 0;">Zurück</a>';
        $strOutput .= '<h2>Importiere Daten aus downloadartikel</h2>';
        $strOutput .= '<ul>';

        foreach ($arrData as $arrRow) {
            $strOutput .= '<li><strong>Importiere:</strong> [' . $arrRow['product_number'] . '] ' . $arrRow['product_name'] . '</li>';
        }


        return $strOutput . '</ul></div>';
    }
}