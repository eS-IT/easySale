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
 * Table tl_easysale_product
 */
$GLOBALS['TL_DCA']['tl_easysale_product'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
        'ctable'                      => array('tl_easysale_transactions'),
        'switchToEdit'                => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('product_category', 'product_number'),
			'flag'                    => 1,
            'panelLayout'             => 'filter;search,limit',
		),
		'label' => array
		(
			'fields'                  => array('product_number', 'product_name'),
			'format'                  => '<strong>%s:</strong> %s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_easysale_product']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_easysale_product']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_easysale_product']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_easysale_product']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
            'transactions' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_easysale_product']['transactions'],
                'href'                => 'table=tl_easysale_transactions',
                'icon'                => 'system/modules/es_easysale/assets/img/transactions.png'
            )
		)
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addImage'),
		'default'                     => '{settings_legend}, product_name, product_number, product_price, product_tax, seller_mail, product_category;{note_legend}, product_note; {picture_legend}, addImage;{path_legend}, product_path; {active_legend}, product_active;'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addImage'                    => 'product_picture,alt,title,size,imagemargin,caption,floating,fullsize'
	),

	// Fields
	'fields' => array
	(
		'product_name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_name'],
			'exclude'                 => true,
            'filter'                  => true,
            'search'                  => true,
            'sort'                    => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			#'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'product_number' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_number'],
            'exclude'                 => true,
            'filter'                  => true,
            'search'                  => true,
            'sort'                    => true,
            'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'unique' => true, 'nospace' => true, 'doNotCopy' => true, 'alwaysSave' => true),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'product_price' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_price'],
            'exclude'                 => true,
            'search'                  => true,
            'sort'                    => true,
            'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'alwaysSave' => true),
            #'sql'                     => "varchar(255) NOT NULL default '0,00'"
        ),
        'product_tax' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_tax'],
            'exclude'                 => true,
            'filter'                  => true,
            'sort'                    => true,
            'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
            'inputType'               => 'select',
            'options'                 => array('0' => 'Keine Steuer', '7' => '7%', '19' => '19%'),
            'eval'                    => array('tl_class'=>'w50', 'alwaysSave' => true),
            #'sql'                     => "decimal(20,0) NOT NULL default '0'"
        ),
        'seller_mail' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['seller_mail'],
            'exclude'                 => true,
            'filter'                  => true,
            'search'                  => true,
            'sort'                    => true,
            'inputType'               => 'text',
            'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'email', 'tl_class'=>'w50', 'alwaysSave' => true),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'product_category' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_category'],
            'exclude'                 => true,
            'filter'                  => true,
            'sort'                    => true,
            'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50', 'alwaysSave' => true),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'product_note' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_note'],
            'exclude'                 => true,
            'search'                  => true,
            'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
            'inputType'               => 'textarea',
            'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr', 'alwaysSave' => true),
            #'sql'                     => "text NOT NULL,"
        ),
        'product_path' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_path'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'mandatory'=>true, 'files'=>true, 'tl_class'=>'clr'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'addImage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['addImage'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true),
            #'sql'                     => "char(1) NOT NULL default ''"
        ),
        'product_picture' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_picture'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'tl_class'=>'clr'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'alt' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['alt'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'size' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['size'],
            'exclude'                 => true,
            'inputType'               => 'imageSize',
            'options'                 => $GLOBALS['TL_CROP'],
            'reference'               => &$GLOBALS['TL_LANG']['MSC'],
            'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'imagemargin' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['imagemargin'],
            'exclude'                 => true,
            'inputType'               => 'trbl',
            'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(128) NOT NULL default ''"
        ),
        'imageUrl' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['imageUrl'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50 wizard'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'fullsize' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['fullsize'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            #'sql'                     => "char(1) NOT NULL default ''"
        ),
        'caption' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['caption'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'floating' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['floating'],
            'exclude'                 => true,
            'inputType'               => 'radioTable',
            'options'                 => array('above', 'left', 'right', 'below'),
            'eval'                    => array('cols'=>4, 'tl_class'=>'w50'),
            'reference'               => &$GLOBALS['TL_LANG']['MSC'],
            #'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'product_active' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['product_active'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'load_callback'           => array(array('tl_easysale', 'loadSettingValue')),
            'eval'                    => array('alwaysSave' => true),
            #'sql'                     => "char(1) NOT NULL default '0'"
        )
	)
);


// Downloadartikel-Import nur anzeigen, wenn die Erweiterung installiert ist
if(in_array('fb_downloadartikel', $this->Config->getActiveModules()))
{
    $GLOBALS['TL_DCA']['tl_easysale_product']['list']['global_operations']['importDlArt'] = array(
        'label'               => &$GLOBALS['TL_LANG']['tl_easysale_product']['importDlArt'],
        'href'                => 'key=importdlart',
        'class'               => 'header_theme_import',
        'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_easysale_product']['importconfirm'] . '\'))return false;Backend.getScrollOffset()"'
    );
}