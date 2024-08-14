<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_pendidikan extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getDataPendidikan(){
		$this->load->database();
		$q = $this->db->query("SELECT pendidikanid AS id, pendidikan AS text, keterangan FROM pendidikan ORDER BY pendidikanid");
		return $q->result_array();
	}
}