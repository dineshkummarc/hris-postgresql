<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_kehadiran extends CI_Model
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
			INSERT INTO public.notifikasi_absensi(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
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
			FROM public.notifikasi_absensi n
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
			SELECT COUNT(*) jml FROM public.notifikasi_absensi WHERE penerima = ? AND (isread IS NULL OR isread = 0)
		", array($penerimaid));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}
	function updateNotifRead($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("UPDATE public.notifikasi_absensi SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}

	function get_satker($nik)
	{
		$fingerid = '';
		$this->db = $this->load->database('hrd', TRUE);
		$q = $this->db->query("SELECT [USERID] FROM [FINGERPRINT].[dbo].[USERINFO] WHERE [BADGENUMBER] = ?", array($nik));

		if ($q->num_rows() > 0) {
			$fingerid = $q->first_row()->USERID;
		}

		return $fingerid;
	}

	function getData($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_statusid']) || !empty($params['v_satkerid'])) {
			if (!empty($params['v_statusid'])) {
				$cond_where = " where statusid = '" . $params['v_statusid'] . "' and satkerid LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY tglmulai DESC";
			} else {
				$cond_where = " where satkerid LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY tglmulai DESC";
			}
		} else {
			$cond_where = " ORDER BY CASE WHEN statusid = '2' THEN '1' ELSE '0' END DESC, tglmulai DESC";
		}

		$query = "
		SELECT * FROM vwkehadiran
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getKehadiranById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getabsensibyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getdetailpengajuanabsensi', array($pengajuanid));
		return $mresult['data'];
	}

	function prosesImportData($params)
	{
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$tglmulai = date('Y/m/d H:i:s', strtotime($params['v_tglmulai']));
		$tglselesai = date('Y/m/d H:i:s', strtotime($params['v_tglselesai']));

		$this->db = $this->load->database('hrd', TRUE);
		$this->db->query("INSERT INTO USER_SPEDAY(USERID,STARTSPECDAY,ENDSPECDAY,DATEID,YUANYING) VALUES (?,?,?,?,?)", array($params['v_nik'], $params['v_tglmulai'], $params['v_tglselesai'], $params['v_jenis'], $params['v_alasan']));
	}
	function updStatusKehadiran($params)
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
	function updStatusExp()
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query(
			"
						/*update public.absen a set
			status = '6'
			where status = '1' and
			a.jamupd <= cast(concat(extract(year from CURRENT_DATE),'-',extract(month from CURRENT_DATE)-1,'-','14') as date) and a.jamupd <= cast(concat(extract(year from CURRENT_DATE),'-',extract(month from CURRENT_DATE),'-','14') as date)*/
			
			update public.absen a set
			status = '6'
			where status = '1' and
			a.jamupd <= cast(concat(extract(year from CURRENT_DATE)-1,'-','12','-','14') as date) and a.jamupd <= cast(concat(extract(year from CURRENT_DATE),'-',extract(month from CURRENT_DATE),'-','14') as date)
		"
		);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
