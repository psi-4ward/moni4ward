<?php
class ServicePort extends System
{
	public function __construct($data)
	{
		parent::__construct();
		
		$this->timeout = 3;
		$this->ip = $data['ip'];
		$this->port = $data['port_port'];
	}
	
	public function check()
	{
		if($this->debug)
		{
			echo "Port: {$this->port}\n";	
		}
		
		$fp = @fsockopen($this->ip,$this->port,$errno,$errstr,$this->timeout);
		if($fp)
		{
			fclose($fp);
			return true;
		}
		else
			return false;
	}
	
	public function getValue()
	{
		return false;
	}
}