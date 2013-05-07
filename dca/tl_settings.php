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

// palettes
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{easySale_legend}, easysaleEmail, easysaleSubject, easysaleHtml; {download_legend}, easysaleDownload; {usesandbox_legend}, usesandbox, easysaledebug; {easysaleDefault_legend}, easysaleProduktnumber, easysaleCategorie, easysalePrice, easysaleTax, easysaleSeller_mail, easysaleSetHeadline, easysaleProduct_active, easysaleNote, easysaleJumpTo, easysaleProductfields; {rights_legend}, easysaleAgb, easysaleWider, easysaleVersand, easysaleAblauf, easysaleAnbieter, easysaleAgbzusatz, easysaleAgbbox, easysaleWiderbox;';

// fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleEmail'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleEmail'],
    'inputType'               => 'text',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('rgxp' => 'friendly', 'decodeEntities'=>true, 'tl_class'=>'w50', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleSubject'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleSubject'],
    'inputType'               => 'text',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleHtml'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleHtml'],
    'inputType'               => 'textarea',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('tl_class'=>'lond clr', 'rte' => 'tinyMCE', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleDownload'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleDownload'],
    'inputType'               => 'textarea',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('tl_class'=>'lond clr', 'rte' => 'tinyMCE', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['usesandbox'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['usesandbox'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50 m12'),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaledebug'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaledebug'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50 m12'),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleProduktnumber'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleProduktnumber'],
    'inputType'               => 'text',
    'eval'                    => array('tl_class'=>'w50', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleSetHeadline'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleSetHeadline'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysalePrice'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysalePrice'],
    'inputType'               => 'text',
    'eval'                    => array('tl_class'=>'w50', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleTax'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleTax'],
    'inputType'               => 'select',
    'options'                 => array('0' => 'Keine Steuer', '7' => '7%', '19' => '19%'),
    'eval'                    => array('tl_class'=>'w50', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleNote'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleNote'],
    'inputType'               => 'textarea',
    'eval'                    => array('tl_class'=>'lond clr', 'rte' => 'tinyMCE', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleJumpTo'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleJumpTo'],
    'inputType'               => 'pageTree',
    'eval'                    => array('fieldType'=>'radio', 'tl_class' => 'long clr'),
    'relation'                => array('type'=>'hasOne', 'load'=>'eager')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleCategorie'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleCategorie'],
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleProductfields'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleProductfields'],
    'inputType'               => 'checkbox',
    'options_callback'         => array('tl_easysale', 'getproductFields'),
    'eval'                    => array('multiple' => true, 'tl_class' => 'long clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleSeller_mail'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleSeller_mail'],
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>255, 'rgxp'=>'email', 'tl_class'=>'long')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleProduct_active'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleProduct_active'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleVersand'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleVersand'],
    'inputType'               => 'text',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('tl_class'=>'long clr', 'alwaysSave' => true, 'allowHtml' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleAgb'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleAgb'],
    'inputType'               => 'textarea',
    'eval'                    => array('tl_class'=>'long clr', 'rte' => 'tinyMCE', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleWider'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleWider'],
    'inputType'               => 'textarea',
    'eval'                    => array('tl_class'=>'long clr', 'rte' => 'tinyMCE', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleAblauf'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleAblauf'],
    'inputType'               => 'textarea',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('tl_class'=>'long clr', 'rte' => 'tinyMCE', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleAgbzusatz'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleAgbzusatz'],
    'inputType'               => 'textarea',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('tl_class'=>'long clr', 'rte' => 'tinyMCE', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleAnbieter'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleAnbieter'],
    'inputType'               => 'textarea',
    'eval'                    => array('tl_class'=>'long clr', 'rte' => 'tinyMCE', 'alwaysSave' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleAgbbox'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleAgbbox'],
    'inputType'               => 'text',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('tl_class'=>'long clr', 'alwaysSave' => true, 'allowHtml' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['easysaleWiderbox'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['easysaleWiderbox'],
    'inputType'               => 'text',
    'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
    'eval'                    => array('tl_class'=>'long clr', 'alwaysSave' => true, 'allowHtml' => true)
);