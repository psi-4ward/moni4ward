<?php

$GLOBALS['TL_DCA']['tl_moni4ward_server'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'					  => array('tl_moni4ward_service'),
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
			'alert' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['alert'],
				'href'                => 'table=tl_moni4ward_alert',
				'class'               => 'header_css_import',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
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
			'servies' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['services'],
				'href'                => 'table=tl_moni4ward_service',
				'icon'                => 'system/modules/moni4ward/html/plugin.png'
			),
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'           => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['copy'],
				'href'            => 'act=copy',
				'icon'            => 'copy.gif',
				'attributes'      => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(),
		'default'                     => '{title_legend},title,alias;{config_legend},ip',
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_moni4ward_server', 'generateAlias')
			)
		),
		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_moni4ward_server']['ip'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>15,'mandatory'=>true)
		)
	)
);

class tl_moni4ward_server extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	
	/**
	 * Auto-generate the alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue))
		{
			$autoAlias = true;
			$varValue = standardize($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_moni4ward_server WHERE alias=?")
								   ->execute($varValue);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}	
}
?>