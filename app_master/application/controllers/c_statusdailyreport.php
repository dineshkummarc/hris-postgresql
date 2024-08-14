<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class c_statusdailyreport extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_statusdailyreport');
	}
	function getStatusDailyreport()
	{
		$mresult = $this->m_statusdailyreport->getStatusDailyreport();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}
