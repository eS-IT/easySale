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
 * Class tl_easysale_content_detail
 *
 * @copyright  e@sy Solutions IT 2013
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @package    eS_easysale
 */
class tl_easysale_product_output extends \Frontend
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'tl_easysale_list';


    /**
     * Default picture for the buy button.
     * @var string
     */
    private $defaultbuyButton = 'system/modules/es_easysale/assets/img/buy.png';


	/**
	 * Generate the module
	 */
	protected function compile(){
	}


    /**
     * Erstellt die Backend-Ausgabe für die Artikelliste oder Detailansicht.
     * @param $object
     * @return string
     */
    public function parseproductBe($object, $strText = '### easySale - ContentElement ###', $printproduct = true){
        $this->strTemplate      = 'be_wildcard';
        $objTemplate            = new \BackendTemplate('be_wildcard');
        $objTemplate->wildcard  = $strText;
        $objTemplate->wildcard .= ($printproduct) ? $this->makeproductList($object->productmulti) : $this->printSettings($object);
        return $objTemplate;
    }


    /**
     * Erstellt eine Liste der Artikel.
     * @param $strData
     * @return string
     */
    private function makeproductList($strData){
        $this->import('es_easysale');
        $arrData = unserialize($strData);

        if(is_array($arrData)){
            $strOutput = '<br /><br /><strong>Artikelliste:</strong><br />';
            foreach ($arrData as $intId) {
                $arrArt = $this->es_easysale->getproductData($intId);
                $strOutput .= $arrArt['product_number'] . ' - ' . $arrArt['product_name'] . '<br />';
            }

            return $strOutput;
        } else {
            return 'Keine Artikel gefunden';
        }
    }


    /**
     * Gibt die Einstellungen aus.
     * @param $object
     * @return string
     */
    private function printSettings($object){
        $this->loadLanguageFile('tl_easysale_product');
        $strContent = '<br />';
        $arrFields  = unserialize($object->productfields);
        $strContent.= 'Liste der anzuzeigenden Felder:<br />';

        if(is_array($arrFields)){
            foreach ($arrFields as $strFields) {
                $strContent .= '+ ' . $GLOBALS['TL_LANG']['tl_easysale_product'][$strFields][0] . '<br />';
            }
        } else {
            $strContent .= '- Keine Produkte gewählt ';
        }

        return $strContent;
    }


    /**
     * Erstellt die Forntend-Ausgabe für die Artikelliste oder Detailansicht.
     * @param $intproduct
     * @param $arrArtikles
     * @param $strTemplate
     * @return string
     */
    public function parseproductFe($object, $strTemplate = 'tl_easysale_product'){
        $this->loadLanguageFile('tl_easysale_product');
        $this->loadLanguageFile('default');
        $this->import('tl_easysale');
        $this->import('es_easysale');
        $strConent = '';
        $arrFields = null;

        // Felder fuer die Anzeige laden
        if($object->productfields != ''){
            $arrFields = @unserialize($object->productfields);
        }

        if(!is_array($arrFields) && array_key_exists('easysaleProductfields', $GLOBALS['TL_CONFIG'])){
            $arrFields = unserialize($GLOBALS['TL_CONFIG']['easysaleProductfields']);
        }

        if(!is_array($arrFields)){
            $arrFields = array(); // Fallback
        }

        // Artikeldaten laden
        if($object->productmulti && $arrFields){
            $arrIds = unserialize($object->productmulti);

            if(is_array($arrIds) && count($arrIds) && $arrIds[0] !== null){
                foreach($arrIds as $indId) {
                    $objTemplate    = new \FrontendTemplate($strTemplate);
                    $arrProduct     = $this->tl_easysale->getproductById($indId);

                    // Bilddaten verarbeiten und ins Template einfuegen
                    $objTemplate = $this->handlePicData($arrProduct, $arrFields, $objTemplate);

                    // Bildfeld nicht anzeigen (wird ueber addImage gemacht)!
                    if(in_array('product_picture', $arrFields)){
                        $tmpKey = array_search('product_picture', $arrFields);
                        unset($arrFields[$tmpKey]);
                        unset($tmpKey);
                    }

                    // Produktdaten verarbeiten
                    $arrContent = $this->handleProduktData($arrProduct, $object, $arrFields);

                    // Hinweis auf Versandkosten einfuegen:
                    $arrContent['product_price'] .= '<span class="esproduct_versand">';
                    $arrContent['product_price'] .= (is_array($GLOBALS['TL_CONFIG']) && array_key_exists('easysaleVersand', $GLOBALS['TL_CONFIG'])) ? $GLOBALS['TL_CONFIG']['easysaleVersand'] : $GLOBALS['TL_LANG']['MSC']['easySale']['output']['versand'];
                    $arrContent['product_price'] .= '</span>';

                    // Preis ans Ende des Arrays setzen
                    $strPrice = $arrContent['product_price'];
                    unset($arrContent['product_price']);
                    $arrContent['product_price'] = $strPrice;

                    // Heinweis auf Dateityp einfügen
                    $objTemplate->strFileFormate = $this->es_easysale->getFileFormate($arrProduct);

                    $objTemplate->arrHeadline   = $this->getHeadline($arrProduct, $object);
                    $objTemplate->arrContent    = $arrContent;
                    $objTemplate->arrLabels     = $GLOBALS['TL_LANG']['tl_easysale_product'];
                    $objTemplate->buybutton     = $GLOBALS['TL_LANG']['MSC']['easySale']['output']['detailbuttonText'];

                    if($object->jumpTo){
                        $objTemplate->buyLink   = $this->tl_easysale->getPageUrl($object->jumpTo, '?id=' . $arrProduct['id']);
                    }



                    $strConent .= $objTemplate->parse();
                }
            }
        }

        return $strConent;
    }


    /**
     * Verarbeitet die Produktdaten und weist sie dem Datenarray fuer das Template zu.
     * @param $arrProduct
     * @param $object
     * @param $arrFields
     * @return array
     */
    private function handleProduktData($arrProduct, $object, $arrFields){
        $arrContent = array();

        if(is_array($arrProduct) && count($arrProduct)){
            foreach($arrProduct as $key => $value) {
                if(substr_count($key, 'product_')){
                    if($key != 'product_path' && $key != 'seller_mail' && in_array($key, $arrFields)){
                        if($value == '' && array_key_exists($key, $GLOBALS['TL_CONFIG'])){
                            $value = $GLOBALS['TL_CONFIG'][$key]; // Wenn kein Wert gefunden wird, vorgabe nehmen.
                        }

                        if($object->showheadline && ($key == 'product_name' || $key == 'product_number')){
                            $arrHeadline[$key] = ($key == 'product_number') ? '[' . $value . ']' : $value;
                        } else {

                            if($key == 'product_price'){
                                $arrContent[$key] = sprintf($GLOBALS['TL_LANG']['MSC']['easySale']['output']['price'], $value);
                            } elseif($key == 'product_tax'){
                                $arrContent['product_price'] .= sprintf($GLOBALS['TL_LANG']['MSC']['easySale']['output']['tax'], $value);
                            } else {
                                $arrContent[$key] = $value;
                            }

                        }
                    }
                }
            }
        }

        return $arrContent;
    }


    /**
     * Verarbeitet die Bilddaten und weist sie dem Datenarray fuer das Template zu.
     * @param $arrProduct
     * @return array
     */
    private function handlePicData($arrProduct, $arrFields, $objTemplate){
        $addImage   = (in_array('product_picture', $arrFields)) ? $arrProduct['addImage'] : false;  // Wenn das Feld "Bild" nicht ausgewaehlt ist, auch addImage auf false setzen

        if(is_array($arrProduct) && count($arrProduct)){
            foreach($arrProduct as $key => $value) {
                if(!substr_count($key, 'product_')){
                    $arrPicData[$key] = $value;
                }
            }
        }

        if($addImage){
            $arrPicData['singleSRC']    = $this->tl_easysale->getPath($arrProduct['product_picture']);
            $this->addImageToTemplate($objTemplate, $arrPicData);

        }

        // Wenn das Feld "Bild" nicht ausgewaehlt ist, auch das Bild nicht anzeigen.
        $objTemplate->addImage      = $addImage;

        return $objTemplate;
    }


    private function getHeadline($arrProduct, $object){
        if($object->showheadline){
            $arrHeadline['product_number'] = '[' . $arrProduct['product_number'] . ']';
            $arrHeadline['product_name'] = $arrProduct['product_name'];
            return ($GLOBALS['EASY_SALE']['FE']['nummer_first']) ? array_reverse($arrHeadline) : $arrHeadline;
        } else {
            return '';
        }
    }
}