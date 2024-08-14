<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_ticket extends CI_Model {
	function __construct(){
		parent::__construct();
	}	
	function getListTicket($params){
		$mresult = $this->tp_connpgsql->callSpCount('pjdinas.sp_getlistticket', $params, false);
		return $mresult;		
	}
	function getTicketById($params) {
		$mresult = $this->tp_connpgsql->callSpReturn('pjdinas.sp_getticketbyid', $params);
		return $mresult['firstrow'];				
	}
	function addPengajuanTicket($params){
		$this->load->database();		
		$this->db->trans_start();
		$this->db->query("DELETE FROM pjdinas.ticket WHERE id = ?", array($params['id']));
		$this->db->query("INSERT INTO pjdinas.ticket(id,travel,noinvoice,jenis,namadepan,departemen,tgldinas,tglkembali,tujuan,jml,budgetcode) 
						VALUES(?,?,?,?,?,?,?,?,?,?,?)", array($params['id'], $params['travel'], $params['noinvoice'], $params['jenis'],  
						$params['namadepan'], $params['departemen'], $params['tgldinas'], $params['tglkembali'], $params['tujuan'], $params['jml'],
						$params['budgetcode'])
		);
		$this->db->trans_complete();	
		$this->db->close();
		return $this->db->trans_status();		
	}
	// function updTicket($params){
	// 	$this->load->database();		
	// 	$this->db->trans_start();
	// 	$q = $this->db->query("
	// 		UPDATE pjdinas.kodesapbudget SET avail = '".$params['v_avail']."' WHERE codeid = '".$params['v_codeid']."';
	// 		UPDATE pjdinas.ticket SET status = 1 WHERE id = '".$params['v_id']."';
	// 	", array($params));
	// 	$this->db->trans_complete();		
	// 	$this->db->close();
	// 	return $this->db->trans_status();			
	// }	
	function updTicket($params){
		$this->load->database();		
		$this->db->trans_start();
		$query = "	UPDATE pjdinas.ticket SET status = '1' WHERE id = '".$params['v_id']."';
					UPDATE pjdinas.kodesapbudget SET avail = '".$params['v_avail']."' WHERE codeid = '".$params['v_codeid']."';
			 	 ";
		$q = $this->db->query(" ".$query." ");
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();			
	}		
}