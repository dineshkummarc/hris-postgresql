<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class c_statuspjdinas extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pjdinas');
	}
	function getStatusPjdinas()
	{
		$mresult = $this->m_pjdinas->getStatusPjdinas();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function getJenisForm()
	{
		$mresult = $this->m_pjdinas->getJenisForm();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}
