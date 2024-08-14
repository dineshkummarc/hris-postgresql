<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_history extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getListPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('sp_getdatahistorypegawai_new', $params, false);
		return $mresult;
	}
	
	function getListHistoryCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('eservices.sp_getlistcutipeg', $params, false);

		$data = array();
		foreach ($mresult['data'] as $r) {
			$r['fileexist'] = false;
			if (!empty($r['files'])) {
				$filePath = config_item('eservices_upload_dok_path') . $r['files'];
				if (file_exists($filePath) && is_file($filePath)) {
					$r['fileexist'] = true;
				} else {
					$r['fileexist'] = false;
				}
			}
			$data[] = $r;
		}

		$result = array('success' => true, 'count' => $mresult['count'], 'data' => $data);
		return $result;
	}

	function deleteCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT eservices.sp_deletecuti(?,?);		
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

	// update status batal cuti
	function updStatusCuti($params)
	{
		$this->db->where('pengajuanid', $params['pengajuanid']);
		$this->db->update('eservices.pengajuancuti', $params);
	}

	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getinfopegawai', $params);
		return $mresult;
	}

	function addAlasan($batalCuti, $pengajuanid)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query(
			"
			UPDATE eservices.detailpengajuancuti set alasancuti = '" . $batalCuti . "' WHERE pengajuanid = $pengajuanid",
			array($pengajuanid)
		);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getComboStatusCuti()
	{
		$this->load->database();
		$q = $this->db->query("select * from eservices.statusverifikasi");
		$this->db->close();
		return $q->result_array();
	}

	function getVerifikatorCuti($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT p.pegawaiid as atasanid, p.nik as atasannik, fnnamalengkap(p.namadepan, p.namabelakang) atasannama, 
				public.fnsatkerlevel(vj.satkerid,'2') AS atasandivisi, j.jabatan AS atasanjabatan, loc.lokasi AS atasanlokasi, p.emailkantor AS atasanemail
			FROM pegawai p
			LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
			LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
			LEFT JOIN level lv ON vj.levelid = lv.levelid
			LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
			WHERE p.pegawaiid = public.fngetatasan(?) AND lv.gol NOT IN ('0','1','2','3')
		", array($pegawaiid));
		$this->db->close();

		$result = array(
			'atasanid' => '',
			'atasannik' => '',
			'atasannama' => '',
			'atasandivisi' => '',
			'atasanjabatan' => '',
			'atasanlokasi' => '',
			'atasanemail' => '',
		);

		if ($q->num_rows() > 0) {
			$r = $q->first_row();
			$result = array(
				'atasanid' => $r->atasanid,
				'atasannik' => $r->atasannik,
				'atasannama' => $r->atasannama,
				'atasandivisi' => $r->atasandivisi,
				'atasanjabatan' => $r->atasanjabatan,
				'atasanlokasi' => $r->atasanlokasi,
				'atasanemail' => $r->atasanemail,
			);
		}
		return $result;
	}

	function getApprovalCuti($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT p.pegawaiid as atasanid, p.nik as atasannik, fnnamalengkap(p.namadepan, p.namabelakang) atasannama, 
				public.fnsatkerlevel(vj.satkerid,'2') AS atasandivisi, j.jabatan AS atasanjabatan, loc.lokasi AS atasanlokasi, p.emailkantor AS atasanemail
			FROM pegawai p
			LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
			LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
			LEFT JOIN level lv ON vj.levelid = lv.levelid
			LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
			WHERE p.pegawaiid = public.fngetatasan(?) AND lv.gol IN ('0','1','2','3')
		", array($pegawaiid));
		$this->db->close();

		$result = array(
			'atasanid' => '',
			'atasannik' => '',
			'atasannama' => '',
			'atasandivisi' => '',
			'atasanjabatan' => '',
			'atasanlokasi' => '',
			'atasanemail' => '',
		);

		if ($q->num_rows() > 0) {
			$r = $q->first_row();
			$result = array(
				'atasanid' => $r->atasanid,
				'atasannik' => $r->atasannik,
				'atasannama' => $r->atasannama,
				'atasandivisi' => $r->atasandivisi,
				'atasanjabatan' => $r->atasanjabatan,
				'atasanlokasi' => $r->atasanlokasi,
				'atasanemail' => $r->atasanemail,
			);
		}
		return $result;
	}
}
