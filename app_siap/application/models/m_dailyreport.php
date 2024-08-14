<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_dailyreport extends CI_Model
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
		if (!empty($params['v_satkerid'])) {
			$cond_where =
				" where g.statusid = '"
				. $params['v_statusid'] .
				"' and a.waktu BETWEEN TO_DATE('"
				. $params['v_tglmulai'] .
				"','YYYY/MM/DD') AND TO_DATE('"
				. $params['v_tglselesai'] .
				"','YYYY/MM/DD') and c.satkerid LIKE '"
				. $params['v_satkerid'] .
				"' || '%' ORDER BY a.waktu";
		} else {
			$cond_where =
				" where g.statusid = '"
				. $params['v_statusid'] .
				"' and a.waktu BETWEEN TO_DATE('"
				. $params['v_tglmulai'] .
				"','YYYY/MM/DD') AND TO_DATE('"
				. $params['v_tglselesai'] .
				"','YYYY/MM/DD') ORDER BY a.waktu";
		}

		$query = "
		SELECT  a.pengajuanid,
				a.pegawaiid,
				a.nourut,
				b.nik,
				fnnamalengkap(b.namadepan, b.namabelakang) nama,
				i.level,
				d.jabatan,
				f.lokasi,
				c.satkerid,
				public.fnsatkerlevel(c.satkerid,'1') AS direktorat,
				public.fnsatkerlevel(c.satkerid,'2') AS divisi,
				public.fnsatkerlevel(c.satkerid,'3') AS departemen,
				public.fnsatkerlevel(c.satkerid,'4') AS seksi,
				public.fnsatkerlevel(c.satkerid,'5') AS subseksi,
				to_char(a.jamupd,'DD/MM/YYYY') tglpermohonan,
				to_char(a.waktu,'DD/MM/YYYY') tglmulai,
				a.actjob,
				a.keterangan,
				a.atasannotes,
				case when a.jenisid = '1' then 'Daily Report Activity' end jenis,
				a.status statusid,
				g.status,
				h.pegawaiid atasanid,
				fnnamalengkap(h.namadepan, h.namabelakang) atasannama,
				a.waktu
		FROM dailyreport.absen a
		left join public.pegawai b on a.pegawaiid = b.pegawaiid
		left join public.vwjabatanterakhir c on b.pegawaiid = c.pegawaiid
		left join public.jabatan d on c.jabatanid = d.jabatanid
		left join public.satker e on c.satkerid = e.satkerid
		left join public.lokasi f on c.lokasikerja = f.lokasiid
		left join dailyreport.statusreport g on a.status = g.statusid
		left join public.pegawai h on a.atasanid = h.pegawaiid
		LEFT JOIN public.level i on i.levelid = c.levelid
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

	function getDailyreportById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('dailyreport.sp_getdailybyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanDailyreport($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('dailyreport.sp_getdetailpengajuandaily', array($pengajuanid));
		return $mresult['data'];
	}

	function updStatusDailyreport($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT dailyreport.sp_updstatusverifikasi(?,?,?,?)
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
			update dailyreport.absen a set
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
