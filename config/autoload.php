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
	'ModuleMoni4ward'    => 'system/modules/moni4ward/ModuleMoni4ward.php',
	'MoniAlert'          => 'system/modules/moni4ward/MoniAlert.php',
	'ServicePing'        => 'system/modules/moni4ward/ServicePing.php',
	'ServicePort'        => 'system/modules/moni4ward/ServicePort.php',
	'ServiceHTTPRequest' => 'system/modules/moni4ward/ServiceHTTPRequest.php',
	'ServiceVConnector'  => 'system/modules/moni4ward/ServiceVConnector.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_moni4ward'    => 'system/modules/moni4ward/templates',
	'moni4ward_server' => 'system/modules/moni4ward/templates',
));
