<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_statuspegawai extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_statuspegawai(){
		$this->load->database();
		$q = $this->db->query("SELECT statuspegawaiid AS id, statuspegawai AS text FROM statuspegawai ORDER BY statuspegawaiid");
		$this->db->close();
		return $q->result_array();
	}
	function ubah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("UPDATE riwayatjabatan SET statuspegawaiid = ?, tglakhirkontrak = TO_DATE(?,'DD/MM/YYYY') , tglpermanent = TO_DATE(?,'DD/MM/YYYY') WHERE pegawaiid = ?", array($params['statuspegawaiid'], $params['tglakhirkontrak'], $params['tglpermanent'], $params['pegawaiid']));
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}
}