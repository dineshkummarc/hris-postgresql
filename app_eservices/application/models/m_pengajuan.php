<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pengajuan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_crott');
	}

	function getInfoCuti($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT COUNT(*) FROM eservices.pengajuancuti WHERE status IN ('2','3','5','9','10','12') AND pegawaiid = ?		
		", array($pegawaiid));
		$this->db->close();
		return $q->first_row();
	}

	function getappver($pegawaiid)
	{
		if ($pegawaiid === null || $pegawaiid === '') {
			return null;
		}
	
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM vwinfoatasan a
			WHERE a.pegawaiid = ?
		", $pegawaiid);
		$result = $q->result_array();
		$this->db->close();
	
		return $result;
	}

	function getcutiid($pegawaiid, $pengajuan)
	{
		if ($pegawaiid === null || $pegawaiid === '') {
			return null;
		}
		if ($pengajuan === null || $pengajuan === '') {
			return null;
		}
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM eservices.pengajuancuti a
			WHERE a.pegawaiid = '" . $pegawaiid . "'
			AND a.pengajuanid = '" . $pengajuan . "'
		");
		$this->db->close();

		return $q->result_array();
	}

	function getStatusCuti($pegawaiid)
	{
		if ($pegawaiid === null || $pegawaiid === '') {
			return null;
		}
		$this->load->database();
		$q = $this->db->query("
			SELECT 
				b.status 
			FROM eservices.pengajuancuti a
				LEFT JOIN eservices.statusverifikasi b on a.status = b.statusid
			WHERE a.status IN ('2','3','5','9','10','12') 
				  AND a.pegawaiid = ?
		", array($pegawaiid));
		$this->db->close();
		return $q->first_row();
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

	function getInfoSisaCuti($pegawaiid)
	{
		if ($pegawaiid === null || $pegawaiid === '') {
			return null;
		}
		
		$this->load->database();
		$q = $this->db->query("
			SELECT 	* FROM eservices.vwsisacuti
			WHERE pegawaiid = ?
			AND tahun = DATE_PART('YEAR',NOW())
		", $pegawaiid);

		$params = $q->result_array();
		$arr = array(
			'pegawaiid' => $pegawaiid,
			'jatahAwal' => $params[0]['saldo'],
			'saldoCY' => $params[0]['saldocy'],
			'saldoLY' => $params[0]['saldoly'],
		);

		return $arr;
	}

	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getinfopegawai', $params);
		return $mresult;
	}

	function getListRekan($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getrekanpelimpahan', $params);
		return $mresult['data'];
	}

	function getComboJenisCuti($jeniscutiid = '')
	{
		$this->load->database();
		$whereClause = '';
		if (!empty($jeniscutiid)) {
			$whereClause = " AND jeniscutiid = '" . $jeniscutiid . "' ";
		}
		$sql = "
			SELECT jeniscutiid AS id, jeniscuti AS text 
			FROM eservices.jeniscuti 
			WHERE jeniscutiid NOT IN('6')			
			" . $whereClause . "
			ORDER BY jeniscutiid
		";

		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
	}

	function getComboDetailJenisCuti($jeniscutiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT detailjeniscutiid AS id, detailjeniscuti AS text, jatahcuti, satuan 
			FROM eservices.detailjeniscuti 
			WHERE jeniscutiid = ?
			ORDER BY detailjeniscutiid
		", array($jeniscutiid));
		$this->db->close();
		return $q->result_array();
	}

	function cekPengajuanCuti($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT eservices.cekpengajuan(?, ?, ?, ?) AS jml
		", $params);
		$this->db->close();
		return $q->first_row()->jml;
	}

	function addPengajuanCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_addpengajuancuti', $params);
		return $mresult['firstrow'];
	}

	function addDetailPengajuanCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT eservices.sp_adddetailpengajuancuti(?,?,?,?,?,?,?,?,?)
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
