<?php

class ServiceVConnector extends System
{
	protected $value = false;

	public function __construct($data)
	{
		parent::__construct();
		
		$this->timeout = 3;
		$this->ip = $data['ip'];
		$this->port = 50231;

//		$this->url = 'http://'.$this-ip.':'.$this->port.'/api/getstatus';
		$this->url = 'http://localhost/test.xml';
	}

	public function check()
	{
		if($this->debug)
		{
			echo "HTTP-URL: ".$this->url."\n";
			echo "\nSending HTTP-GET request...\n";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Moni4ward)');
		$content = curl_exec($ch);
		curl_close($ch);

		if($this->debug)
		{
			echo "Response: \n";
			echo "------------------------------------\n";
			echo $content;
			echo "\n------------------------------------\n\n";
		}

		list($header,$content) = explode("\r\n\r\n",$content);

		if(preg_match("~ 200 OK~",$header))
		{
			if($this->debug) echo "Code 200 received\n";
			if(strlen($content))
			{
				$xml = simplexml_load_string($content);
				if(!$xml) {
					if($this->debug) {
						echo "Could not parse XML\n";
						echo $contente;
					}
					return false;
				}
				$this->value = $content;

				return ($xml->IsOkay == 'True');
			}
			else
			{
				return true;
			}
		}
		if($this->debug) echo "Code 200 NOT received\n";
		return false;
	}

	public function getValue()
	{
		return $this->value;
	}
}