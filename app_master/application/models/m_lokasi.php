<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_lokasi extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getmasterlokasi($node){
		$this->load->database();
		$q = $this->db->query("
			SELECT lokasiid as id, lokasi as text FROM lokasi
		", array($node));
		$this->db->close();
		return $q;
	}
	function getLokasiKerja(){
		$this->load->database();
		$q = $this->db->query("SELECT lokasiid as id, lokasi as text, kodelokasi FROM lokasi ORDER BY text ASC");
		$this->db->close();
		return $q->result_array();
	}
	function tambah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			INSERT INTO lokasi(lokasiid, lokasi, kodelokasi)
			SELECT COALESCE(MAX(lokasiid)+1,1),?,?
			FROM lokasi
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}	
	function ubah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE lokasi SET lokasi = ?, kodelokasi = ? WHERE lokasiid = ?
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}	
	function hapus($id){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM lokasi WHERE lokasiid = ?", array($id));
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}	
}