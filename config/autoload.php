<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Moni4ward
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'ServicePing'        => 'system/modules/moni4ward/ServicePing.php',
	'MoniAlert'          => 'system/modules/moni4ward/MoniAlert.php',
	'ServiceHTTPRequest' => 'system/modules/moni4ward/ServiceHTTPRequest.php',
	'ModuleMoni4ward'    => 'system/modules/moni4ward/ModuleMoni4ward.php',
	'ServicePort'        => 'system/modules/moni4ward/ServicePort.php',
	// Bin
	'check_service'      => 'system/modules/moni4ward/bin/check_service.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_moni4ward'    => 'system/modules/moni4ward/templates',
	'moni4ward_server' => 'system/modules/moni4ward/templates',
));
