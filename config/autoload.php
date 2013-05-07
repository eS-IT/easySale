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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'es_easysale',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    'es_easysale'                   => 'system/modules/es_easysale/es_easysale.php',
    'es_easysale_import'            => 'system/modules/es_easysale/es_easysale_import.php',
    'es_paypal'                     => 'system/modules/es_easysale/es_paypal.php',
    'es_paypal_inc'                 => 'system/modules/es_easysale/es_paypal_inc.php',
    'es_testtools'                  => 'system/modules/es_easysale/es_testtools.php',
	'tl_easysale'                   => 'system/modules/es_easysale/tl_easysale.php',
    'tl_easysale_product_output'    => 'system/modules/es_easysale/tl_easysale_product_output.php',
    'tl_easysale_content'           => 'system/modules/es_easysale/tl_easysale_content.php',
    'tl_easysale_modul'             => 'system/modules/es_easysale/tl_easysale_modul.php',
    'tl_easysale_salesite'          => 'system/modules/es_easysale/tl_easysale_salesite.php'
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'tl_easysale_product'   => 'system/modules/es_easysale/templates',
	'tl_easysale_content'   => 'system/modules/es_easysale/templates',
    'tl_easysale_salesite'  => 'system/modules/es_easysale/templates'
));
