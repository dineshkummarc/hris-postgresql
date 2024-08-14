<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pengajuan extends CI_Model
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

	function getShortNotif($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, fnnamalengkap(p.namadepan, p.namabelakang) nama, p.nik
			FROM notifikasi_absensi n
			LEFT JOIN pegawai p ON n.pengirim = p.pegawaiid
			WHERE n.penerima = ?
			ORDER BY n.tglnotif DESC
			FETCH FIRST 5 ROWS ONLY
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}

	function getCountNotifUnread($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT COUNT(*) jml FROM notifikasi_absensi WHERE penerima = ? AND (isread IS NULL OR isread = 0)
		", array($penerimaid));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}

	function getCountNotif($penerimaid)
	{
		$this->CI->db->where('penerima', $penerimaid);
		return $this->CI->db->count_all_results("notifikasi_absensi");
	}

	function getAllNotification($penerimaid, $row)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT * FROM vwnotifabsen a
			WHERE a.penerima = ?
			ORDER BY a.tglnotif DESC
			OFFSET " . $row . " ROWS
			FETCH NEXT 25 ROWS ONLY;
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}

	function getAllNotificationHR($penerimaid, $nik, $row)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT * FROM vwnotifabsen a
			WHERE a.penerima = ? And a.nik = ?
			ORDER BY a.tglnotif DESC
			OFFSET ? ROWS
			FETCH NEXT 25 ROWS ONLY
		", array($penerimaid, $nik, $row));
		$this->CI->db->close();
		return $q->result_array();
	}

	function updateNotifRead($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("UPDATE notifikasi_absensi SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}

	function getOpendate()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM reporthris.opendate
		");
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

	function getappver($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM vwinfoatasan a
			WHERE a.pegawaiid = ?
		", $pegawaiid);
		$this->db->close();

		return $q->result_array();
	}

	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getinfopegawai', $params);
		return $mresult;
	}

	function cekPengajuanKehadiran($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT public.cekpengajuan(?, ?, ?, ?) AS jml
		", $params);
		$this->db->close();
		return $q->first_row()->jml;
	}

	function addPengajuanAbsensi($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_addpengajuanabsen', $params);
		return $mresult['firstrow'];
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
