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
 * Table tl_easysale_transactions
 */
$GLOBALS['TL_DCA']['tl_easysale_transactions'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
        'ptable'                      => 'tl_easysale_product',
		'enableVersioning'            => false,
        'closed'                      => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
                'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('tstamp'),
			'flag'                    => 6,
            'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('first_name', 'last_name', 'transaction_ip', 'invoice', 'amount'),
			'format'                  => '%s %s [%s]<br /><span style="color:#b3b3b3">[%s]</span> %s &euro; '
		),
		'global_operations' => array
		(
			'all' => array
			(
				#'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				#'href'                => 'act=select',
				'class'               => 'invisible',
				#'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
/*			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_easysale_transactions']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_easysale_transactions']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_easysale_transactions']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
*/			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_easysale_transactions']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
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
		'__selector__'                => array(''),
		'default'                     => ''
	),

	// Subpalettes
	'subpalettes' => array
	(
		''                            => ''
	),

	// Fields
	'fields' => array
	(
        'tstamp' => array
        (
            'flag'                    => 6,
        ),
        'item_number' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['item_number'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'item_name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['item_name'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'first_name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['first_name'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'last_name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['last_name'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'payer_email' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['payer_email'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'transaction_ip' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['transaction_ip'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'invoice' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['invoice'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'mc_gross' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['mc_gross'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'payment_date' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['payment_date'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'payment_status' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['payment_status'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'transaction_download01' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['transaction_download01'],
            'exclude'                 => true,
            'flag'                    => 6,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'transaction_download02' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['transaction_download02'],
            'exclude'                 => true,
            'flag'                    => 6,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'transaction_download03' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_easysale_product']['transaction_download03'],
            'exclude'                 => true,
            'flag'                    => 6,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            #'sql'                     => "varchar(255) NOT NULL default ''"
        )
	)
);