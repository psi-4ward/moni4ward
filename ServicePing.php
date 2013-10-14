<?php
class ServicePing extends System
{
	public function __construct($data)
	{
		parent::__construct();
		
		$this->timeout = 3;
		$this->ip = $data['ip'];
	}
	
	public function check()
	{
		if($this->debug) echo "shellCmd: "."ping -c 1 -q -W {$this->timeout} {$this->ip}\n";
		$ret = shell_exec("ping -c 1 -q -W {$this->timeout} {$this->ip} 2>&1");
		if($this->debug) echo "returnValue: ".$ret;
		
		if(preg_match("~, (\d+) received,.*mdev[^\d]+(\d+\.\d+)/~s",$ret,$erg) && $erg[1]>0)
		{
			$this->responseTime = round((int)$erg[2]);
			return true;
		}
		return false;
	}
	
	public function getValue()
	{
		return $this->responseTime;
	}
}