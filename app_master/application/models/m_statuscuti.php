<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_statuscuti extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getStatusCuti(){
		$this->load->database();
		$q = $this->db->query("select statusid as id, status as text from eservices.statusverifikasi order by cast(statusid as int)");
		$this->db->close();
		return $q->result_array();
	}
}