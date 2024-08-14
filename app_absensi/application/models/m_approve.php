<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_approve extends CI_Model
{
	var $CI;
	function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
	}

	function addNotif($params)
	{
		$this->CI->load->database();
		$this->CI->db->trans_start();
		$q = $this->CI->db->query("
			INSERT INTO notifikasi_absensi(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
			VALUES(NOW(),?,?,?,CAST(? AS INT),CAST(? AS INT),?,'1',CAST(? AS INT),?,0);
		", $params);
		$this->CI->db->trans_complete();
		$this->CI->db->close();
		return $this->CI->db->trans_status();
	}

	function getListApprovalCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('public.sp_getverifikasiabsensi', $params, false);
		return $mresult;
	}

	function approvebulk($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT eservices.sp_approvebulkkehadiran(?)
			", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updStatusAbsensi($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updstatusverifikasi(?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getCutiById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getabsensibyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getdetailpengajuanabsensi', array($pengajuanid));
		return $mresult['data'];
	}

	function getHariLibur()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT DISTINCT TO_CHAR(tgl, 'YYYY-MM-DD') AS tgl FROM eservices.harilibur WHERE tgl >= TO_DATE('01/01/2018','DD/MM/YYYY') ORDER BY tgl ASC
		");
		$this->db->close();

		return $q->result_array();
	}

	function addlogs()
	{
		$params = array(
			'nik' => $this->session->userdata('username'),
			'url' => $_SERVER['REQUEST_URI'],
			'ipuser' => $_SERVER['REMOTE_ADDR'],
		);
		$this->load->database();
		$this->db->query("
				INSERT INTO reporthris.userlogs VALUES (?,?,NOW(),?)  ", $params);
	}
}
