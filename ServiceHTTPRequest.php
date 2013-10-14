<?php
class ServiceHTTPRequest extends System
{
	public function __construct($data)
	{
		parent::__construct();
		
		$this->timeout = 3;
		$this->url = $data['http_url'];
		$this->response = $data['http_response'];
		$this->port = $data['http_port'];
	}
	
	public function check()
	{
		if($this->debug)
		{
			echo "HTTP-URL: ".$this->url."\n";
			echo "HTTP-Port: ".$this->port."\n";
			if(strlen($this->response))
			{
				echo "Expected response: ".$this->response."\n";
			}
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
			if(strlen($this->response))
			{
				if($this->debug && $content != $this->response) echo "Response-Text doesnt match!\n";
				return $content == $this->response;
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
		return false;
	}
}