<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pjdinas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getStatusPjdinas()
	{
		$this->load->database();
		$q = $this->db->query("select statusid as id, status as text from pjdinas.statusverifikasi order by cast(statusid as int)");
		$this->db->close();
		return $q->result_array();
	}
	function getJenisForm()
	{
		$this->load->database();
		$q = $this->db->query("select jenisformid as id, jenisform as text from pjdinas.jenisform order by cast(jenisformid as int)");
		$this->db->close();
		return $q->result_array();
	}
}
