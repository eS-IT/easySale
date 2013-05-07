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
 * Class tl_easysale_modul_detail
 *
 * @copyright  e@sy Solutions IT 2013
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @package    eS_easysale
 */
class tl_easysale_modul extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'tl_easysale_content';


	/**
	 * Generate the module
	 */
	protected function compile(){
        $this->import('tl_easysale_product_output');

        if (TL_MODE == 'BE'){
            $this->Template = $this->tl_easysale_product_output->parseproductBe($this, '### easySale - Modul ###');
        } else {
            $this->Template->content = $this->tl_easysale_product_output->parseproductFe($this);
        }
	}

}
