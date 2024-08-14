<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_statusdailyreport extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getStatusDailyreport()
	{
		$this->load->database();
		$q = $this->db->query("select statusid as id, status as text from dailyreport.statusreport order by cast(statusid as int)");
		$this->db->close();
		return $q->result_array();
	}
}
