<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_budget extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function getBudget($years){
		$this->load->database();
		$q = $this->db->query("SELECT codeid,id,nama,coa,avail,jan,feb,mar,apr,mei,jun,jul,agu,sep,okt,nov,des,periode,
		( CAST(jan AS INT)+CAST(feb AS INT)+CAST(mar AS INT)+CAST(apr AS INT)+CAST(mei AS INT)+CAST(jun AS INT)+CAST(jul AS INT)
		+CAST(agu AS INT)+CAST(sep AS INT)+CAST(okt AS INT)+CAST(nov AS INT)+CAST(des AS INT) ) AS subtotal
		FROM pjdinas.kodesapbudget WHERE periode = '".$years."' ORDER BY codeid ASC");
		$this->db->close();
		return $q->result_array();
	}
	function tambah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			INSERT INTO pjdinas.kodesapbudget(id,nama,periode,jan,feb,mar,apr,mei,jun,jul,agu,sep,okt,nov,des)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) 
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}	
	function ubah($params){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE pjdinas.kodesapbudget SET id=?,nama=?,periode=?,jan=?,
			feb=?,mar=?,apr=?,mei=?,jun=?,jul=?,agu=?,sep=?,
			okt=?,nov=?,des=? WHERE id = '".$params['id']."'
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}	
	function hapus($id){
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM pjdinas.kodesapbudget WHERE id = ?", array($id));
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();								
	}
	function addBudget($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$this->db->query("DELETE FROM pjdinas.kodesapbudget WHERE codeid = ?", array($params['codeid']));
		$this->db->query(
			"INSERT INTO pjdinas.kodesapbudget(codeid,id,nama,coa,satker,avail,periode,jan,feb,mar,apr,mei,jun,jul,agu,sep,okt,nov,des,budget_avail,jenisbudgetid)
						VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,1)",
			array(
				$params['codeid'], $params['id'], $params['nama'], $params['coa'],
				$params['satker'], $params['avail'], $params['periode'], $params['jan'], $params['feb'], $params['mar'],
				$params['apr'], $params['mei'], $params['jun'], $params['jul'], $params['agu'], $params['sep'], $params['okt'], $params['nov'], $params['des'], $params['avail']
			)
		);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}