<?php
/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require_once '../../initialize.php';

class Moni4ward_ChartData extends System
{
	public function __construct()
	{
		parent::__construct();
		
		if($this->Input->get('id') && preg_match("~^\d+$~",$this->Input->get('id')))
		{
			$this->serviceID = $this->Input->get('id');
		}
		else die('ERROR');
		
		$this->import('Database');
	}
	
	
	public function getJson()
	{
		require_once(TL_ROOT.'/plugins/openflashchart/OFC_Chart.php');
		
		$objService = $this->Database->prepare('SELECT * FROM tl_moni4ward_service WHERE id=?')->execute($this->serviceID);
		if($objService->numRows != 1) die('ERROR: Not found!');
		
		$cntPASS = $this->Database->prepare('SELECT count(id) as anz FROM tl_moni4ward_service_log WHERE pid=? AND status="1"')->execute($this->serviceID)->anz;
		$cntFAIL = $this->Database->prepare('SELECT count(id) as anz FROM tl_moni4ward_service_log WHERE pid=? AND status="0"')->execute($this->serviceID)->anz;
		
		$sum = $cntFAIL + $cntPASS;
		
		$percFAIL= ($cntFAIL == 0) ? 0 : round($cntFAIL/$sum * 100,2);   
		$percPASS = ($cntPASS == 0) ? 0 : round($cntPASS/$sum * 100,2);   
		
		$pie = new OFC_Charts_Pie();
		$pie->set_start_angle(35);
		$pie->set_animate( true );
		$pie->values = array
		(
			new OFC_Charts_Pie_Value($percPASS, "PASS"),
			new OFC_Charts_Pie_Value($percFAIL, 'FAIL')
		);
		$pie->colours = array('#00ff00','#ff0000');
		$pie->tip = '#val#%';
		
		$chart = new OFC_Chart();
		$chart->set_title(new OFC_Elements_Title('Ping'));
		$chart->add_element($pie);
		
		
		$chart->x_axis = null;
		
		return $chart->toPrettyString();		
	}
}

$x = new Moni4ward_ChartData();
echo $x->getJson();

?>