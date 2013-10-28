<?php

$GLOBALS['TL_DCA']['tl_moni4ward_service'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'					  => 'tl_moni4ward_server',
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'filter;search,limit',
			'headerFields'			  => array('title','ip'),
			'child_record_callback'   => array('tl_moni4ward_service', 'listItem')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type'),
		'default'                     => '{title_legend},title,type,exclusive;{alerts_legend},alerts',
		'Ping'						  => '{title_legend},title,type,exclusive;{alerts_legend},alerts',
		'HTTPRequest'				  => '{title_legend},title,type,exclusive;{alerts_legend},alerts;{http_legend},http_url,http_port,http_response',
		'Port'				  		  => '{title_legend},title,type,exclusive;{alerts_legend},alerts;{port_legend},port_port',
		'VConnector'                  => '{title_legend},title,type,exclusive;{alerts_legend},alerts',
	),

	// Subpalettes
	'subpalettes' => array
	(
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['type'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'default'				  => 'Ping',
			'inputType'               => 'select',
			'options'        		  => array('Ping','HTTPRequest','Port', 'VConnector'),
			'eval'                    => array('submitOnChange'=>true)
		),
		'exclusive' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['exclusive'],
			'filter'				  => true,
			'inputType'				  => 'checkbox',
			'eval'					  => array('tl_class'=>'')
		),
		'alerts' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['alerts'],
			'inputType'				  => 'multiColumnWizard',
			'eval'					  => array
			(
				'style'=>'width:100%;',
				'columnFields' => array
				(
					'cycles' => array
					(
						'label'                   => array('within Cycles'),
						'inputType'               => 'text',
						'eval'					  => array('style'=>'width:80px;','rgxp'=>'digit')
					),
					'alert' => array
					(
						'label'					  => array('Notification'),
						'inputType'				  => 'select',
						'foreignKey'			  => 'tl_moni4ward_alert.title',
						'eval'					  => array('includeBlankOption'=>true)
					)
				)
			)
		),
		'http_url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['http_url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),		
		'http_port' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['http_port'],
			'exclude'                 => true,
			'default'				  => '80',
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>6, 'rgxp'=>'digit', 'tl_class'=>'w50')
		),		
		'http_response' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['http_response'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>false, 'tl_class'=>'clt')
		),	
		'port_port' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_service']['port_port'],
			'exclude'                 => true,
			'default'				  => '',
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>6, 'rgxp'=>'digit', 'tl_class'=>'w50')
		),
	)
);

class tl_moni4ward_service extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	public function listItem($arrRow)
	{
		return '
<div><strong>' . $arrRow['title'] . '</strong></div>
<div class="block">
Type: '.$arrRow['type'].'
</div>' . "\n";
	}
}
?>
