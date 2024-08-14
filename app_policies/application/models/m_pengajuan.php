<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class m_pengajuan extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	function getComboNamaDokumen() {
		$this->load->database();		
		$sql = "
			SELECT id, nama AS text FROM public.policiesnamadokumen
			ORDER BY id asc
		";
		
		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
	}
	
	function getComboDivisi() {
		$this->load->database();		
		$sql = "
		SELECT  a.unitcode as id, 
			 	CONCAT(b.unitcode,'_',a.unitdesc) as text 
		FROM strukturnew.vwsatkernewdisplay a
			LEFT JOIN strukturnew.s1_unitkerja b ON b.unitcode = LEFT(a.unitcode,3)
		WHERE LENGTH(a.unitcode) = '5'
		ORDER BY b.id, a.unitcode asc
		";
		
		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
	}
	
	function getComboDept($params) {
		$this->load->database();		
		$q = $this->db->query("
		SELECT 
			a.unitcode AS id, 
			a.unitdesc AS text 
		FROM strukturnew.vwsatkernewdisplay a
			LEFT JOIN strukturnew.s1_unitkerja b on b.unitcode = LEFT(a.unitcode,3)
		WHERE a.unitcode LIKE '".$params['v_dept']."' || '%' 
			AND LENGTH(a.unitcode) = '7'
		ORDER BY b.id, a.unitcode
		", array($params));
		$this->db->close();
		return $q->result_array();
	}
	
	function addPolicies($params) {
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_addpolicies', $params);
		return $mresult['firstrow'];		
	}
		
	function updPolicies($params){
		$this->load->database();		
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updpolicies(?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();		
	}	
}