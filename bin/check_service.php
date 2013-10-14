<?php
if(PHP_SAPI != 'cli')
{
	echo "This is a commandline-tool!\n";
	exit;
}

/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require_once realpath(dirname(__FILE__).'/../../../').'/initialize.php';

/**
 * 
 * Commandline actor for running the tests
 * @author Christoph Wiechert <cw@4wardmedia.de>
 * @package Moni4ward
 * @copyright by 4ward.media GbR
 *
 */
class check_service extends System
{
	protected $alertAndStore = false;
	
	/**
	 * Constructor to read the CLI-Args
	 * and runs the corresponding actions
	 */
	public function __construct()
	{
		$this->import('Database');
		
		if($_SERVER['argc'] < 2)
		{
			$this->help(); exit(1);
		}
		
		$this->readCliArgs();
	}

	/**
	 * run all service checks for all servers in the right order
	 * forks a subshell for each server if not in debug mode
	 */
	protected function runAllChecks()
	{
		
		$objServer = $this->Database->prepare('SELECT * FROM tl_moni4ward_server')->execute();
		
		while($objServer->next())
		{
			if($this->debug)
			{
				$this->runServerChecks($objServer->id);	
			}
			else
			{
				// fork subshell
				$str = exec("/usr/bin/php ".__FILE__." --serverID {$objServer->id} &");
				// echo "/usr/bin/php ".__FILE__." --serverID {$objServer->id} &";
				echo $str;
			}
		}
	}
	
	/**
	 * run all service checks for a specific server
	 * and stop if a exclusive check fails
	 * @param mixed $idOrObj ServerID or Database_Result
	 */
	protected function runServerChecks($idOrObj)
	{
		if(is_object($idOrObj))
		{
			$objServer = $idOrObj;
		}
		else
		{
			$objServer = $this->Database->prepare('SELECT * FROM tl_moni4ward_server WHERE id=?')->execute($idOrObj);
		}
		if($objServer->numRows < 1)
		{
			echo "ERROR: SERVER with ID $id not found!\n";
			exit(1);
		}
		
		$objTest = $this->Database->prepare('	SELECT s.*,srv.ip,srv.title as serverTitle 
													FROM tl_moni4ward_service AS s
													LEFT JOIN tl_moni4ward_server AS srv ON (s.pid = srv.id)
													WHERE pid=?
													ORDER BY exclusive DESC, sorting')->execute($objServer->id);
			
		while($objTest->next())
		{
			$erg = $this->runCheck($objTest->row(),true);
			if(!$erg && $objTest->exclusive)
			{
				// no more tests if an exclusive test fails
				break;
			}
		}		
		
	}
	
	/**
	 * run a specific test
	 * 
	 * @param mixed $idOrObj Check-ID or Object 
	 * @param bool $storeAndAlert Store check-result and alert send alerts on FAIL 
	 */
	protected function runCheck($idOrObj,$storeAndAlert = false)
	{
		if(!is_object($idOrObj))
		{
			$objTest = $this->Database->prepare('	SELECT s.*,srv.ip,srv.title as serverTitle ,srv.alias as serverAlias 
													FROM tl_moni4ward_service AS s
													LEFT JOIN tl_moni4ward_server AS srv ON (s.pid = srv.id)
													WHERE s.id=?')->execute($idOrObj);
		}
		else
		{
			$objTest = $idOrObj;
		}	
	
		
		if($objTest->numRows < 1)
		{
			echo "ERROR: ServiceTest with ID $id not found!\n";
			exit(1);
		}
		
		if($this->debug) echo "Testing {$objTest->title} [{$objTest->type}] for Server {$objTest->serverTitle}...\n";
		
		$class = 'Service'.$objTest->type;
		$objCheck = new $class($objTest->row());
		$objCheck->debug = $this->debug;
		$result = $objCheck->check();
		
		if($storeAndAlert)
		{
			$arrAlerts = deserialize($objTest->alerts,true);
			
			if($result == true)
			{
				// alert when change from FAIL to PASS
				if($objTest->status == 'FAIL')
				{
					foreach($arrAlerts AS $alert)
					{
						if($objTest->failCount >= $alert['cycles'])
						{
							$objAlert = $this->Database->prepare('SELECT * FROM tl_moni4ward_alert WHERE id=?')->execute($alert['alert']);
							new MoniAlert($objAlert,true,$objTest->row());
						}
					}
					$objTest->failCount = 0;
				}
				
				$this->Database->prepare('UPDATE tl_moni4ward_service SET lastCheck=UNIX_TIMESTAMP(), failCount=0, status="PASS" WHERE id=?')->execute($objTest->id);
				
				if($objCheck->getValue() !== false)
					$this->Database->prepare('INSERT INTO tl_moni4ward_service_log SET tstamp=UNIX_TIMESTAMP(), status="1", pid=?, value=?')->execute($objTest->id, $objCheck->getValue());
				else
					$this->Database->prepare('INSERT INTO tl_moni4ward_service_log SET tstamp=UNIX_TIMESTAMP(), status="1", pid=?')->execute($objTest->id);
			}
			else 
			{
				$failCount = $objTest->failCount + 1;
				foreach($arrAlerts as $alert)
				{
					if($failCount == $alert['cycles'])
					{
						$objAlert = $this->Database->prepare('SELECT * FROM tl_moni4ward_alert WHERE id=?')->execute($alert['alert']);
						$objTest->failCount = $failCount;
						new MoniAlert($objAlert,false,$objTest->row());	
					}
					
				}
				
				$this->Database->prepare('UPDATE tl_moni4ward_service SET lastCheck=UNIX_TIMESTAMP(), failCount=failCount+1, status="FAIL" WHERE id=?')->execute($objTest->id);
				$this->Database->prepare('INSERT INTO tl_moni4ward_service_log SET tstamp=UNIX_TIMESTAMP(), status="0",pid=?')->execute($objTest->id);
			}
		}
		
		return $result;
	}
	
	/**
	 * Read the cli-arguments
	 */
	protected function readCliArgs()
	{
		reset($_SERVER['argv']);
		// first elem is the filename
		next($_SERVER['argv']);

		// Read all commandline options
		do
		{
			$param = current($_SERVER['argv']);
			switch($param)
			{
				case '--debug':
					$this->debug = true;
				break;
				
				case '--cron':
					$cron = true;
				break;
				
				case '--testID':
					$testID = next($_SERVER['argv']);
					if(!strlen($testID) || !preg_match("~^\d+$~",$testID)){ $this->help(); exit(1); }
					$this->testID = $testID;
				break;
				
				case '--serverID':
					$serverID = next($_SERVER['argv']);
					if(!strlen($serverID) || !preg_match("~^\d+$~",$serverID)){ $this->help(); exit(1); }
					$this->serverID = $serverID;
				break;
				
				case '--alertAndStore':
					$this->alertAndStore = true;					
				break;
				
				default:
					echo "Ignoring unknowen parameter $param. \n";
					next($_SERVER['argv']);
				break;
			}
			
		} while(next($_SERVER['argv']));		
		
		
		if($cron)
		{
			$this->runAllChecks();
		}
		else if(strlen($this->testID))
		{
			$erg = $this->runCheck($this->testID,$this->alertAndStore);
			echo "Result: ".($erg ? 'PASS' : 'FAIL')."\n";
		}		
		else if(strlen($this->serverID))
		{
			$this->runServerChecks($this->serverID);
		}		
	}
	
	/**
	 * Print the help-screen 
	 */
	public function help(){
		echo "Usage:\n\n";
		echo $_SERVER['argv'][0]." --cron [--debug]\n";
		echo "Run all checks for all servers an store the results in the database.\n\n";
		echo $_SERVER['argv'][0]." --serverID xx [--debug]\n";
		echo "Run all checks for the server with ID xx an store the results in the database.\n\n";
		echo $_SERVER['argv'][0]." --testID xx [--debug]\n";
		echo "Run the service-test with ID xx. Doesnt store the result.\n\n";
	}	
}

$x = new check_service();
?>