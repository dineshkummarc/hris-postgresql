<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_approve extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getListApprovalCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('eservices.sp_getverifikasicuti', $params, false);
		return $mresult;
	}

	function approvebulk($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT eservices.sp_approvebulk(?)
			", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updStatusCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT eservices.sp_updstatusverifikasi(?,?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getCutiById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getcutibyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getdetailpengajuancuti', array($pengajuanid));
		return $mresult['data'];
	}

	function getComboJenisCuti($jeniscutiid = '')
	{
		$this->load->database();
		$whereClause = '';
		if (!empty($jeniscutiid)) {
			$whereClause = " WHERE jeniscutiid = '" . $jeniscutiid . "' ";
		}
		$sql = "
			SELECT jeniscutiid AS id, jeniscuti AS text 
			FROM eservices.jeniscuti 
			" . $whereClause . "
			ORDER BY jeniscutiid
		";

		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
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

	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getinfopegawai', $params);
		return $mresult;
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
