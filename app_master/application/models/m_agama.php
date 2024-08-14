<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_agama extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_agama(){
		$this->load->database();
		$q = $this->db->query("SELECT agamaid AS id, agama AS text FROM agama ORDER BY agamaid");
		$this->db->close();
		return $q->result_array();
	}
}