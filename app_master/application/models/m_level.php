<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_level extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getLevel(){
		$this->load->database();
		$q = $this->db->query("SELECT levelid AS id, level AS text, gol FROM level ORDER BY gol");
		return $q->result_array();
	}
	function tambah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("INSERT INTO level(levelid, level, gol) VALUES(?,?,?)", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}
	function ubah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("UPDATE level SET level = ?, gol = ? WHERE levelid = ?", array($params['level'], $params['gol'], $params['levelid']));
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}
	function hapus($id){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM level WHERE levelid = ?", array($id));
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}
}