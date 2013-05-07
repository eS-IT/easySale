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
 * Table tl_content
 */
// selectors
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][]          = 'showtextagb';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][]          = 'showtextwiderruf';

// palettes
$GLOBALS['TL_DCA']['tl_content']['palettes']['es_easysale']             = '{type_legend},type,headline;{esproduct_legend},productmulti;{fields_legend},productfields;{showheadline_legend},showheadline;{redirect_legend},jumpTo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tl_easysale_salesite']    = '{type_legend},type,headline;{fields_legend},productfields;{showheadline_legend},showheadline;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

// subpalettes
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['showtextagb']          = 'textagb';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['showtextwiderruf']     = 'textwiderruf';

// fields
$GLOBALS['TL_DCA']['tl_content']['fields']['productmulti'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['productmulti'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'         => array('tl_easysale', 'getActiveproduct'),
    'eval'                    => array('mandatory'=>true, 'multiple' => true, 'size' => 30),
    #'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['jumpTo'],
    'exclude'                 => true,
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => array('fieldType'=>'radio', 'alwaysSave' => true),
    #'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'eager')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['productfields'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['productfields'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'options_callback'         => array('tl_easysale', 'getproductFields'),
    'eval'                    => array('mandatory'=>true, 'multiple' => true, 'alwaysSave' => true),
    #'sql'                     => "text NOT NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['showheadline'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['showheadline'],
    'exclude'                 => true,
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'inputType'               => 'checkbox',
    'default'                 => true,
    'eval'                    => array('alwaysSave' => true),
    #'sql'                     => "char(1) NOT NULL default ''"
);