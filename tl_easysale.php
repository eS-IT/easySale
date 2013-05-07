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
 * Class tl_easysale
 *
 * @copyright  e@sy Solutions IT 2013
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @package    eS_easysale
 */
class tl_easysale extends \BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = '';


	/**
	 * Generate the module
	 */
	protected function compile(){
	}


    /**
     * Fügt die CSS-Datei mit den minimal Definitionen ein.
     * @param $strContent
     * @param $strTemplate
     * @return string
     */
    public function hoOutputFrontendTemplate($strContent, $strTemplate){
        if($GLOBALS['EASY_SALE']['FE']['include_css']){
            $strCss = '<link rel="stylesheet" href="system/modules/es_easysale/assets/css/easysale.css">';
            $strContent =  str_replace('</head>', $strCss . "\n" . '</head>', $strContent);
        }

        return $strContent;
    }


    /**
     * Options_callback: Gibt alle aktiven Artikel zurück.
     * @return array
     */
    public function getActiveproduct(){
        $this->import('Database');
        $sql    = 'SELECT * FROM `tl_easysale_product` WHERE `product_active` = 1 ORDER BY `product_number`, `product_name`';
        $result = $this->Database->query($sql);
        $arrData= array();

        if($result->numRows){
            while($result->next()){
                $arrData[$result->id] = '[' . $result->product_number . '] ' . $result->product_name;
            }
        }

        return $arrData;
    }


    /**
     * Options_callback: Die Felder der Artikel zurück.
     * @param $dc
     * @return array
     */
    public function getproductFields($dc){
        $this->import('Database');
        $this->loadLanguageFile('tl_easysale_product');
        $arrFields  = $this->Database->listFields('tl_easysale_product');
        $arrData    = array();

        foreach ($arrFields as $arrRow) {
            $strName = $arrRow['name'];

            if(!in_array($strName, $GLOBALS['EASY_SALE']['BE']['exclude_fields'])){
                $arrData[$strName] = $GLOBALS['TL_LANG']['tl_easysale_product'][$strName][0];
            }
        }

        return $arrData;
    }


    /**
     * Gibt die Artikel zurück.
     * @param $intId
     * @return array
     */
    public function getproductById($intId){
        if($intId != ''){
            $this->import('Database');
            $sql    = 'SELECT * FROM `tl_easysale_product` WHERE `product_active` = 1 AND `id` = ' . $intId . ' ORDER BY `product_name`';
            $result = $this->Database->query($sql);

            if($result->numRows){
                return $result->fetchAssoc();
            }
        }

        return false;
    }


    public function getTransaktion($strInvoice){
        $sql    = 'SELECT * FROM `tl_easysale_transactions` WHERE `invoice` = "' . $strInvoice .'"';
        $result = $this->Database->query($sql);

        if($result->numRows){
            return $result->fetchAssoc();
        } else {
            return array();
        }
    }


    /**
     * Gibt den Pfad zu einer singleSRC-Id zurueck.
     * @param $varImg
     * @return mixed|null|string
     */
    public function getPath($varImg){
        if(is_numeric($varImg)){
            // Contao 3.x.x
            $objFile = \FilesModel::findByPk($varImg);

            if($objFile !== null && is_file(TL_ROOT . '/' . $objFile->path)){
                return $objFile->path;
            } else {
                return '';
            }
        }

        // Contao 2.11.x
        return $varImg;
    }


    /**
     * Erzeugt aus einer Id die Url der Seite.
     * @param $intID
     * @return string
     */
    public function getPageUrl($intID, $strAdd = ''){
        if(version_compare(VERSION . '.' . BUILD, '3.0.0', '>')){
            // Contao 3.x.x
            $objPages   = \PageModel::findPublishedById($intID);
        } else {
            // Contao 2.11.x
            $this->import('Environment');
            $objPages   = $this->getPageDetails($intID);
        }

        $strAlias   = ($objPages->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objPages->alias : $objPages->id;
        $strUrl     = ($objPages->domain != '' ? ($this->Environment->ssl ? 'https://' : 'http://').$objPages->domain : $this->Environment->url);
        $arrRequest = explode('?', $this->Environment->requestUri);
        $strUrl    .= (is_array($arrRequest) && substr_count($arrRequest[0], '.php') == 0 && substr_count($arrRequest[0], '.html') == 0) ? $arrRequest[0] : '/';
        $strUrl    .= (!$GLOBALS['TL_CONFIG']['rewriteURL']) ? 'index.php/' : '';  // ist in requestUri mit drin!!!
        $strUrl    .= (array_key_exists('urlSuffix', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['urlSuffix'] != '' && !substr_count($strAlias, $GLOBALS['TL_CONFIG']['urlSuffix'])) ? $strAlias . $GLOBALS['TL_CONFIG']['urlSuffix'] : $strAlias;
        $strUrl    .= $strAdd;
        return $strUrl;
    }


    /* ======================================= *
     * Methoden fuer das Befüllen der Settings *
     * ======================================= */

    /**
     * Gibt den Default-Wert fuer die Einstellungen zurueck.
     * @param $varData
     * @param $dc
     * @return string
     */
    public function loadSettingValue($varData, $dc){
        if(version_compare(VERSION . '.' . BUILD, '3.0.0', '<')){
            // Contao 2.11.x
            $this->import('Config');
        }

        $this->loadLanguageFile('default');

        if($varData == '' || $varData == '0' || $varData == '0,00' || $dc->field == 'showheadline'){
            switch($dc->field){
                case 'easysaleEmail':
                    return $GLOBALS['TL_CONFIG']['adminEmail'];
                    break;

                case 'product_number':
                    return $GLOBALS['TL_CONFIG']['easysaleProduktnumber'];
                    break;

                case 'showheadline':
                    return $GLOBALS['TL_CONFIG']['easysaleSetHeadline'];
                    break;

                case 'product_price':
                    return $GLOBALS['TL_CONFIG']['easysalePrice'];
                    break;

                case 'product_tax':
                    return $GLOBALS['TL_CONFIG']['easysaleTax'];
                    break;

                case 'product_note':
                    return $GLOBALS['TL_CONFIG']['easysaleNote'];
                    break;

                case 'productfields':
                case 'productmulti':
                    return $GLOBALS['TL_CONFIG']['easysaleProductfields'];
                    break;

                case 'seller_mail':
                    return $GLOBALS['TL_CONFIG']['easysaleSeller_mail'];
                    break;

                case 'product_active':
                    return $GLOBALS['TL_CONFIG']['easysaleProduct_active'];
                    break;

                case 'product_category':
                    return $GLOBALS['TL_CONFIG']['easysaleCategorie'];
                    break;

                case 'easysaleSubject':
                    return $GLOBALS['TL_LANG']['MSC']['easySale']['mail']['subject'];
                    break;

                case 'easysaleHtml':
                    return $GLOBALS['TL_LANG']['MSC']['easySale']['mail']['html'];
                    break;

                case 'easysaleDownload':
                    return $GLOBALS['TL_LANG']['MSC']['easySale']['mail']['download'];
                    break;

                case 'jumpTo':
                    if($this->getModuleTyp($dc->id) == 'es_easysale' || $dc->table != 'tl_module'){
                        return $GLOBALS['TL_CONFIG']['easysaleJumpTo'];
                    } else {
                        return '';
                    }
                    break;

                case 'easysaleAgbbox':
                    return $GLOBALS['TL_LANG']['MSC']['easySale']['agb']['easysaleAgbbox'];
                    break;

                case 'easysaleWiderbox':
                    return $GLOBALS['TL_LANG']['MSC']['easySale']['agb']['easysaleWiderbox'];
                    break;

                case 'easysaleVersand':
                    return $GLOBALS['TL_LANG']['MSC']['easySale']['output']['easysaleVersand'];
                    break;

                case 'easysaleAblauf':
                    return $GLOBALS['TL_LANG']['MSC']['easySale']['output']['ablauf'];
                    break;

                case 'easysaleAgbzusatz':
                    return $GLOBALS['TL_LANG']['MSC']['easySale']['output']['easysaleAgbzusatz'];
                    break;

                default:
                    return '';
                    break;
            }
        } else {
            return $varData;
        }
    }


    /**
     * Gibt den Typ eines Moduls zurueck.
     * @param $intId
     * @return bool
     */
    private function getModuleTyp($intId){
        $this->import('Database');
        $query  = "SELECT `type` FROM `tl_module` WHERE `id` = $intId";
        $result = $this->Database->query($query);

        if($result->numRows){
            return $result->type;
        }

        return false;
    }


    /**
     * Hook: Ersetzt die easySale-InsertTags.
     * @param $strTag
     * @return bool
     */
    public function replaceEasySaleTags($strTag){
        switch($strTag){
            case 'easysale::ablauf':
                return $GLOBALS['TL_CONFIG']['easysaleAblauf'];
                break;

            case 'easysale::agb':
                return $GLOBALS['TL_CONFIG']['easysaleAgb'];
                break;

            case 'easysale::anbieter':
                return $GLOBALS['TL_CONFIG']['easysaleAnbieter'];
                break;

            case 'easysale::versand':
                return $GLOBALS['TL_CONFIG']['easysaleVersand'];
                break;

            case 'easysale::widerruf':
                return $GLOBALS['TL_CONFIG']['easysaleWider'];
                break;

            case 'easysale::zusatz':
                return $GLOBALS['TL_CONFIG']['easysaleAgbzusatz'];
                break;

            default:
                return false;
                break;
        }
    }
}
