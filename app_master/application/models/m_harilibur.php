<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_harilibur extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getListHariLibur(){
		$this->load->database();
		$q = $this->db->query("
			SELECT hariliburid, TO_CHAR(tgl, 'DD/MM/YYYY') tgl, keterangan, status, jenis AS jenisid,
				CASE WHEN jenis = '1' THEN 'Hari Libur Nasional' WHEN jenis = '2' THEN 'Cuti Bersama' ELSE NULL END jenis
			FROM eservices.harilibur
			ORDER BY TO_CHAR(tgl, 'YYYY/MM') DESC
		");
		$this->db->close();
		return $q->result_array();
	}
	function tambah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			INSERT INTO eservices.harilibur(hariliburid, tgl, keterangan, status, jenis)
			SELECT COALESCE(MAX(hariliburid)+1,1), TO_DATE(?, 'DD/MM/YYYY'), ?, ?, ?
			FROM eservices.harilibur 
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}	
	function ubah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE eservices.harilibur SET tgl = TO_DATE(?, 'DD/MM/YYYY'), keterangan = ?, status = ?, jenis = ? WHERE hariliburid = ?
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}	
	function hapus($id){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM eservices.harilibur WHERE hariliburid = ?", array($id));
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}	
}