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
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['es_easysale']     = '{title_legend},name,headline,type;{product_legend},productmulti;{fields_legend},productfields;{showheadline_legend},showheadline;{redirect_legend},jumpTo;{protected_legend:hide},protected;{expert_legend:hide},guests';

$GLOBALS['TL_DCA']['tl_module']['fields']['productmulti'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['productmulti'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'options_callback'         => array('tl_easysale', 'getActiveproduct'),
    'eval'                    => array('mandatory'=>true, 'multiple' => true, 'size' => 30, 'alwaysSave' => true),
    #'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['productfields'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['productfields'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'options_callback'         => array('tl_easysale', 'getproductFields'),
    'eval'                    => array('mandatory'=>true, 'multiple' => true, 'alwaysSave' => true),
    #'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['showheadline'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showheadline'],
    'exclude'                 => true,
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'inputType'               => 'checkbox',
    'default'                 => true,
    'eval'                    => array('alwaysSave' => true),
    #'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['jumpTo']['load_callback'][] = array('tl_easysale', 'loadSettingValue');
$GLOBALS['TL_DCA']['tl_module']['fields']['jumpTo']['eval']['alwasySave'] = true;