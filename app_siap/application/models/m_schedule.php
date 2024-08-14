<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_schedule extends CI_Model {
	function __construct(){
		parent::__construct();
	}	
	function getListSchedule($params){
		$mresult = $this->tp_connpgsql->callSpCount('absensi.sp_getlistschedule', $params, false);
		return $mresult;		
	}
	function addPengajuanSchedule($params){
		$this->load->database();		
		$this->db->trans_start();
		$this->db->query("
			DELETE FROM absensi.schedule WHERE nik = ? and tgl = ?", array($params['nik'],$params['tgl'])
		);
		$this->db->query("
			SELECT absensi.sp_addschedule(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();	
		$this->db->close();
		return $this->db->trans_status();		
	}	
	function updateSchedule($params){
		$this->load->database();		
		$this->db->trans_start();
		$this->db->query("
			SELECT absensi.sp_updschedule(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();	
		$this->db->close();
		return $this->db->trans_status();		
	}	
	function getSchedule($sendparams){
		$this->load->database();
		$q = $this->db->query("
			SELECT id FROM absensi.schedule WHERE nik = '".$sendparams['v_nik']."' and tgl = '".$sendparams['v_tgl']."'
		", $sendparams);
		$this->db->close();
		return $q->result_array();
	}	
}