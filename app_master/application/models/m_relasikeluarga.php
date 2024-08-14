<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_relasikeluarga extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getRelasiKeluarga(){
		$this->load->database();
		$q = $this->db->query("select id, relasi as text from public.relasikeluarga order by id");
		return $q->result_array();
	}
}