<?php

$GLOBALS['TL_DCA']['tl_moni4ward_alert'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'flag'					  => 1,
			'fields'                  => array('title'),
			'panelLayout'             => 'filter;search,limit',
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
		),		
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			),
			'back' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['backBT'],
				'href'                => 'table=tl_moni4ward_server',
				'class'               => 'header_back',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="b"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type'),
		'default'                     => '{title_legend},title,type',
		'EMail'                    	  => '{title_legend},title,type;{email_legend},email_subject,email_sendto,email_from,email_fromName,email_msg',
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['type'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'default'				  => 'EMail',
			'inputType'               => 'select',
			'options'        		  => array('EMail'),
			'eval'                    => array('submitOnChange'=>true,'tl_class'=>'w50')
		),
		'email_subject' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['email_subject'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'decodeEntities'=>true )
		),		
		'email_msg' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['email_msg'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'					  => array('tl_class'=>'clr', 'decodeEntities'=>true)
		),		
		'email_sendto' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['email_sendto'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'decodeEntities'=>true)
		),		
		'email_from' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['email_from'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'decodeEntities'=>true)
		),
		'email_fromName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_alert']['email_fromName'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'decodeEntities'=>true)
		)
	)
);

class tl_moni4ward_alert extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
}
?>