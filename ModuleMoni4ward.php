<?php
class ModuleMoni4ward extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_moni4ward';	
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Moni4ward ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;
			return $objTemplate->parse();
		}
		
		return parent::generate();
	}
	
	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->Template->content = $this->compileServer();
	}	
	
	protected function compileServer()
	{
		$tpl = new FrontendTemplate('moni4ward_server');
		
		$objServer = $this->Database->prepare('SELECT * FROM tl_moni4ward_server ORDER BY title')->execute();
		$arrServer = $objServer->fetchAllAssoc();
		
		foreach($arrServer as $k => $srv)
		{
			$objServices = $this->Database->prepare('SELECT * FROM tl_moni4ward_service WHERE pid=? ORDER BY sorting')->execute($srv['id']);
			$arrServer[$k]['services'] = $objServices->fetchAllAssoc();
		}
		
		$tpl->servers = $arrServer;
		
		return $tpl->parse();
	}
}

