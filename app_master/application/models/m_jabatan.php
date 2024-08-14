<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_jabatan extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getmasterjabatan($node){
		$this->load->database();
		$q = $this->db->query("
			SELECT j.jabatanid, j.jabatan, j.tipejabatan
			FROM jabatan j
			WHERE j.jabatanid LIKE ? || '%'
			AND (COALESCE(LENGTH(j.jabatanid), 0) - COALESCE(LENGTH(REPLACE(j.jabatanid,'.','')), 0)) = (COALESCE(LENGTH(?), 0) - COALESCE(LENGTH(REPLACE(?,'.','')), 0))+1
			ORDER BY j.jabatan
		", array($node, $node, $node));
		$this->db->close();
		return $q;
	}
	function tambah($params){
		$this->load->database();		
		$this->db->trans_start();
		$q = $this->db->query("SELECT public.addjabatan(?,?)", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();
	}	
	function ubah($params){
		$this->load->database();		
		$this->db->trans_start();
		$q = $this->db->query("UPDATE jabatan SET jabatan = ? WHERE jabatanid = ?", array($params['v_text'], $params['v_node']));
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();
	}
	function hapus($satkerid){
		$this->load->database();		
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM jabatan WHERE jabatanid = ?", array($satkerid));
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();
	}	
}