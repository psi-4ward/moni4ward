<?php
class MoniAlert extends System
{
	
	public function __construct(Database_Result $objAlert, $status, $data = array())
	{
		$this->data = $data;
		$this->data['result'] = ($status) ? 'PASS' : 'FAIL';
		$this->data['date'] = date('d.m.Y');
		$this->data['time'] = date('H:i:s');
		
		$mail = new Email();
		$mail->from = $this->replaceVariables($objAlert->email_from);
		$mail->fromName = $this->replaceVariables($objAlert->email_fromName);
		$mail->subject = $this->replaceVariables($objAlert->email_subject);
		$mail->priority = 'highest';
		$mail->text = $this->replaceVariables($objAlert->email_msg);
		$mail->sendTo($this->replaceVariables($objAlert->email_sendto));
	}
	
	
	protected function replaceVariables($str)
	{
		// extend $data-keys with ## delimiter
		$dataNew = array();
		foreach($this->data as $k => $v)
		{
			$dataNew['##'.$k.'##'] = $v;
		}
		
		return str_ireplace(array_keys($dataNew), array_values($dataNew), $str);
	}
}