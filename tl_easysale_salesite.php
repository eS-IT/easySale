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
class tl_easysale_salesite extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'tl_easysale_salesite';


    /**
     * Artikel-Ids
     * MUSS public, damit die Artikeldaten in tl_easysale_product_output ausgelesen werden koennen!!!
     * @var string
     */
    public $productmulti = '';



	/**
	 * Generate the module
	 */
	protected function compile(){
        $this->import('Environment');
        $this->import('tl_easysale_product_output');
        $this->import('tl_easysale');
        $this->import('es_easysale');
        $this->loadLanguageFile('default');

        $this->Template->showtextagb        = false;    // Wird spaeter auf ture gesetzt.
        $this->Template->showtextwiderruf   = false;    // Wird spaeter auf ture gesetzt.
        $this->Template->formAction         = false;    // Wird spaeter auf ture gesetzt.

        $this->Template->buttonText         = $GLOBALS['TL_LANG']['MSC']['easySale']['output']['buttonText'];
        $this->Template->textagb            = (array_key_exists('easysaleAgb', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['easysaleAgb'] != '') ? $GLOBALS['TL_CONFIG']['easysaleAgb'] : '';
        $this->Template->textwieder         = (array_key_exists('easysaleWider', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['easysaleWider'] != '') ? $GLOBALS['TL_CONFIG']['easysaleWider'] : '';
        $this->Template->textanbieter       = (array_key_exists('easysaleAnbieter', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['easysaleAnbieter'] != '') ? $GLOBALS['TL_CONFIG']['easysaleAnbieter'] : '';
        $this->Template->textagbzusatz      = (array_key_exists('easysaleAgbzusatz', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['easysaleAgbzusatz'] != '') ? $GLOBALS['TL_CONFIG']['easysaleAgbzusatz'] : $GLOBALS['TL_LANG']['MSC']['easySale']['output']['easysaleAgbzusatz'];

        $indId                              = $this->Input->get('id');
        $arrProduct                         = $this->tl_easysale->getproductById($indId);
        $strFileExtension                   = $this->es_easysale->getFileFormate($arrProduct);
        $strTextAblauf                      = (array_key_exists('easysaleAblauf', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['easysaleAblauf'] != '') ? $GLOBALS['TL_CONFIG']['easysaleAblauf'] : $GLOBALS['TL_LANG']['MSC']['easySale']['output']['ablauf'];
        $strTextAblauf                      = str_replace('{{file::extension}}', $strFileExtension, $strTextAblauf);
        $this->Template->textablauf         = str_replace('{{admin::mail}}', $GLOBALS['TL_CONFIG']['adminEmail'], $strTextAblauf);

        $this->Template->textagbbox         = (array_key_exists('easysaleAgbbox', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['easysaleAgbbox'] != '') ? $GLOBALS['TL_CONFIG']['easysaleAgbbox'] : $GLOBALS['TL_LANG']['MSC']['easySale']['agb']['easysaleAgbbox'];
        $this->Template->textwiderbox       = (array_key_exists('easysaleWiderbox', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['easysaleWiderbox'] != '') ? $GLOBALS['TL_CONFIG']['easysaleWiderbox'] : $GLOBALS['TL_LANG']['MSC']['easySale']['agb']['easysaleWiderbox'];

        if (TL_MODE == 'BE'){
            $this->Template = $this->tl_easysale_product_output->parseproductBe($this, '### easy Sale - PayPal ###', false);
        } else {
            $this->Template->content    = $this->makeOutput();
        }
	}


    /**
     * Prueft, wellche Ausgabe erstellt werden soll.
     * @return string
     */
    private function makeOutput(){
        if(!$this->Input->get('art')){
            return $this->makeBuyForm();
        } else {
            $transData  = $this->es_easysale->getTransByInvoice($this->Input->get('art'));
            $artData    = $this->es_easysale->getproductData($transData['pid']);
            $this->es_easysale->writeLog('Erstellt Downlaod fuer: ', $artData, true);

            if($this->testDowload($artData, $transData)){
                return $this->makeDownlaod($artData, $transData);
            } else {
                return sprintf($GLOBALS['TL_LANG']['MSC']['easySale']['error']['noDownload'], $this->Input->get('art'));
            }
        }
    }


    /**
     * Erstellt den Download, wenn ein Transaktionscode uebergeben wird.
     * @return string
     */
    private function makeDownlaod($artData, $transData){
        $varPath = $artData['product_path'];
        if (is_numeric($varPath)){
            // Contoa 3.x.x
            $this->es_easysale->writeLog('Bereite Contao 3.x.x-Download vor', $varPath);
            $objFile = \FilesModel::findByPk($varPath);

            if ($objFile !== null){

                $strFile = $objFile->path;  // nur relativer Pfad!
                $this->es_easysale->writeLog('Download-Pfad:', $strFile);

                if(is_file($strFile)){
                    return $this->makeDl($strFile, $transData);
                }
            }

            return $GLOBALS['TL_LANG']['MSC']['easySale']['error']['noFile'];
        } else {
            // Contao 2.11.x
            $this->es_easysale->writeLog('Bereite Contao 2.11.x-Download vor', $varPath, true);
            $this->es_easysale->writeLog('Download-Pfad:', $varPath);

            if(is_file($varPath)){  // nur relativer Pfad! Pfad steht bei 2.11.x dirket in $varPath!
                return $this->makeDl($varPath, $transData);
            }

            return $GLOBALS['TL_LANG']['MSC']['easySale']['error']['noFile'];
        }

    }


    private function makeDl($strFile, $transData){
        $strToken = $transData['verify_sign'];

        if($this->Input->get('token') != $strToken){
            $legend     = ucfirst(basename($strFile));
            $requestUri = substr($this->Environment->requestUri, 1, strlen($this->Environment->requestUri));
            $link       = '<img src="system/themes/default/images/regular.gif" width="18" height="18" alt="image/jpeg" class="mime_icon" style="vertical-align: middle;"><a href="%s&token=%s" title="%s">%s</a>';
            $strOutLink = sprintf($link, $requestUri, $strToken, $legend, $legend);
            $strText    = ($GLOBALS['TL_CONFIG']['easysaleDownload'] != '') ?  $GLOBALS['TL_CONFIG']['easysaleDownload'] : $GLOBALS['TL_LANG']['MSC']['easySale']['mail']['download'];
            return str_replace('{{download::link}}', $strOutLink, $strText);
        } else {
            $this->saveDownload($transData);
            $this->sendFileToBrowser($strFile);
            return '';
        }
    }


    private function testDowload($artData, $transData){
        if( is_array($artData) && array_key_exists('product_path', $artData) && $artData['product_path'] != '' &&
            $transData['payment_status'] == 'Completed' && $transData['verify_sign'] != '' &&
            ($transData['transaction_download01'] == '' || $transData['transaction_download02'] == '' || $transData['transaction_download03'] == '')
        ){
            return true;
        } else {
            return false;
        }
    }


    /**
     * Speichert die Zeit des Dowanloads.
     * @param $transData
     */
    private function saveDownload($transData){
        if($transData['transaction_download01'] == ''){
            $transData['transaction_download01'] = time();
        } elseif($transData['transaction_download02'] == ''){
            $transData['transaction_download02'] = time();
        } elseif($transData['transaction_download03'] == ''){
            $transData['transaction_download03'] = time();
        }

        $this->es_easysale->saveTransaction($transData);
    }


    /**
     * Erstellt das Kaufformular.
     * @return string
     */
    private function makeBuyForm(){
        $strContent = '';

        if($this->Input->post('FORM_SUBMIT') != 'acceptTerm' || !$this->Input->post('acceptAgb') || !$this->Input->post('acceptWider')){
            $this->Template->formError          = ((!$this->Input->post('acceptAgb') || !$this->Input->post('acceptWider')) && $this->Input->post('FORM_SUBMIT') == 'acceptTerm') ? 'error' : '';
            $this->productmulti                 = serialize(array($this->Input->get('id')));
            $strContent                        .= $this->tl_easysale_product_output->parseproductFe($this);

            if($this->Input->post('FORM_SUBMIT') == 'acceptTerm' && (!$this->Input->post('acceptAgb') || !$this->Input->post('acceptWider'))){
                $this->Template->checkboxError = '<div class="error">' . $GLOBALS['TL_LANG']['MSC']['easySale']['error']['noCheckboxes'] . '</div>';
            }

            if($strContent){
                $this->Template->formAction         = $this->Environment->url . $this->Environment->requestUri;
                $this->Template->showtextagb        = true;
                $this->Template->showtextwider      = true;
                $this->Template->showtextablauf     = true;
                $this->Template->showtextanbieter   = true;
                $this->Template->showtextagbzusatz  = ($this->Template->textagb != '' || $this->Template->textwieder != '') ? true : false;
                return $strContent;
            } else {
                return $GLOBALS['TL_LANG']['MSC']['easySale']['error']['noproduct'];
            }

        } else {
            $this->import('es_paypal');
            $strContent .= $this->es_paypal->buy($this->Input->get('id'));
            return $strContent;
        }
    }
}
