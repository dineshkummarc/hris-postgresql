<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pegawai extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_data_satker()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM satker
		");
		return $q->result_array();
	}

	function getListPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('sp_getdatapegawai', $params, false);
		return $mresult;
	}

	function getListPegawai2($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('sp_getdatapegawai2', $params, false);
		return $mresult;
	}

	function getListPegawai1($params)
	{
		$mresult1 = $this->tp_connpgsql->callSpCount('sp_getdatapegawaichunz1', $params, false);
		return $mresult1;
	}

	function getListPegawai10($params)
	{
		$mresult10 = $this->tp_connpgsql->callSpCount('sp_getdatapegawaichunz10', $params, false);
		return $mresult10;
	}

	function tambahPegawai($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addpegawai(
				?,?,?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?
			);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function ubahPegawai($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updpegawai(
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?,?,?
			);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
		// var_dump($this->db->trans_status());
	}

	function updAtasan()
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
				UPDATE satker SET kepalaid = NULL, kepalajabatan = NULL, statusjabatan = NULL
				WHERE satkerid IN (
			    SELECT a.satkerid
				FROM vwjabatanterakhir a
				LEFT JOIN pegawai b ON a.pegawaiid = b.pegawaiid
				LEFT JOIN satker c ON a.satkerid = c.satkerid
				WHERE a.keteranganpegawai = '2' AND a.pegawaiid = c.kepalaid
				ORDER BY a.tglselesai desc
				)
			");
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getPegawaiByID($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getpegawaibyid', $params);
		return $mresult;
	}

	function getRiwayatJabatan($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatjabatan', $params);
		return $mresult;
	}

	function addRiwayatJabatan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatjabatan(?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatJabatan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatjabatan(?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatJabatan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatjabatan(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getRiwayatKeluarga($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatkeluarga', $params);
		return $mresult;
	}

	function getRiwayatKeluargaInti($params)
	{
		$this->load->database();
		$q = $this->db->query("
        WITH cteA AS (
			SELECT
				A.pegawaiid,
				A.nourut,
				A.relasi,
				A.nama,
				A.jeniskelamin,
				TO_CHAR( A.tgllahir, 'DD/MM/YYYY' ) tgllahir,
				A.pendidikan,
				A.pekerjaan,
				A.foto,
				A.tmptlahir,
				A.alamat
			FROM
				riwayatkeluarga A
			WHERE
				A.relasi IN ( 'Pasangan', 'Anak Ke - 1', 'Anak Ke - 2', 'Anak Ke - 3', 'Anak Ke - 4', 'Anak Ke - 5', 'Anak Ke - 6' )
				AND A.pegawaiid = '" . $params['v_pegawaiid'] . "' UNION ALL
			SELECT
				'" . $params['v_pegawaiid'] . "' pegawaiid,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
			NULL
			) SELECT * FROM cteA
		ORDER BY CASE
				WHEN relasi ILIKE'%Pasangan%' THEN 1
				WHEN relasi ILIKE'%Anak Ke - 1%' THEN 2
				WHEN relasi ILIKE'%Anak Ke - 2%' THEN 3
				WHEN relasi ILIKE'%Anak Ke - 3%' THEN 4
				WHEN relasi ILIKE'%Anak Ke - 4%' THEN 5
				WHEN relasi ILIKE'%Anak Ke - 5%' THEN 6
				WHEN relasi ILIKE'%Anak Ke - 6%' THEN 7
				WHEN relasi ILIKE'%Ayah Kandung%' THEN 8
				WHEN relasi ILIKE'%Ibu Kandung%' THEN 9
				WHEN relasi ILIKE'%Saudara Ke - 1%' THEN 10
				WHEN relasi ILIKE'%Saudara Ke - 2%' THEN 11
				WHEN relasi ILIKE'%Saudara Ke - 3%' THEN 12
				WHEN relasi ILIKE'%Saudara Ke - 4%' THEN 13
				WHEN relasi ILIKE'%Saudara Ke - 5%' THEN 14
				WHEN relasi ILIKE'%Saudara Ke - 6%' THEN 15
				WHEN relasi ILIKE'%Saudara Ke - 7%' THEN 16
				ELSE 17
		END
			LIMIT ( SELECT CASE WHEN COUNT ( pegawaiid ) > 1 THEN COUNT ( pegawaiid ) - 1 ELSE COUNT ( pegawaiid ) END FROM cteA );
	", array($params));
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
	}

	function getdataprint($params2)
	{
		$this->load->database();
		$q = $this->db->query("SELECT userid, username, nama, pegawaiid, cast(now() as date) tanggal FROM users WHERE pegawaiid = '" . $params2['v_pegawaiid'] . "'", array($params2));
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
	}

	function getRiwayatKeluargaBesar($params)
	{
		$this->load->database();
		$q = $this->db->query("
        WITH cteA AS (
			SELECT
				A.pegawaiid,
				A.nourut,
				A.relasi,
				A.nama,
				A.jeniskelamin,
				TO_CHAR( A.tgllahir, 'DD/MM/YYYY' ) tgllahir,
				A.pendidikan,
				A.pekerjaan,
				A.foto,
				A.tmptlahir,
				A.alamat
			FROM
				riwayatkeluarga A
			WHERE
				A.relasi IN ( 'Ayah Kandung','Ibu Kandung','Saudara Ke - 1','Saudara Ke - 2','Saudara Ke - 3','Saudara Ke - 4','Saudara Ke - 5','Saudara Ke - 6')
				AND A.pegawaiid = '" . $params['v_pegawaiid'] . "' UNION ALL
			SELECT
				'" . $params['v_pegawaiid'] . "' pegawaiid,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
			NULL
			) SELECT * FROM cteA
		ORDER BY CASE
				WHEN relasi ILIKE'%Pasangan%' THEN 1
				WHEN relasi ILIKE'%Anak Ke - 1%' THEN 2
				WHEN relasi ILIKE'%Anak Ke - 2%' THEN 3
				WHEN relasi ILIKE'%Anak Ke - 3%' THEN 4
				WHEN relasi ILIKE'%Anak Ke - 4%' THEN 5
				WHEN relasi ILIKE'%Anak Ke - 5%' THEN 6
				WHEN relasi ILIKE'%Anak Ke - 6%' THEN 7
				WHEN relasi ILIKE'%Ayah Kandung%' THEN 8
				WHEN relasi ILIKE'%Ibu Kandung%' THEN 9
				WHEN relasi ILIKE'%Saudara Ke - 1%' THEN 10
				WHEN relasi ILIKE'%Saudara Ke - 2%' THEN 11
				WHEN relasi ILIKE'%Saudara Ke - 3%' THEN 12
				WHEN relasi ILIKE'%Saudara Ke - 4%' THEN 13
				WHEN relasi ILIKE'%Saudara Ke - 5%' THEN 14
				WHEN relasi ILIKE'%Saudara Ke - 6%' THEN 15
				WHEN relasi ILIKE'%Saudara Ke - 7%' THEN 16
				ELSE 17
		END
			LIMIT ( SELECT CASE WHEN COUNT ( pegawaiid ) > 1 THEN COUNT ( pegawaiid ) - 1 ELSE COUNT ( pegawaiid ) END FROM cteA );

	", array($params));
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
	}

	function addRiwayatKeluarga($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatkeluarga(?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatKeluarga($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatkeluarga(?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatKeluarga($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatkeluarga(?,?);
		", array($params['pegawaiid'], $params['nourut']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getRiwayatPendidikan($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatpendidikan', $params);
		return $mresult;
	}

	function addRiwayatPendidikan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatpendidikan(?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatPendidikan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatpendidikan(?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatPendidikan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatpendidikan(?,?);
		", array($params['pegawaiid'], $params['nourut']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getRiwayatPengalamanKerja($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatpengalamankerja', $params);
		return $mresult;
	}

	function addRiwayatPengalamanKerja($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatpengalamankerja(?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatPengalamanKerja($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatpengalamankerja(?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatPengalamanKerja($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatpengalamankerja(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getRiwayatRekening($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatrekening', $params);
		return $mresult;
	}

	function addRiwayatRekening($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatrekening(?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatRekening($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatrekening(?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatRekening($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatrekening(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getRiwayatKursus($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatkursus', $params);
		return $mresult;
	}

	function addRiwayatKursus($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatkursus(?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatKursus($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatkursus(?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatKursus($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatkursus(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getRiwayatBahasa($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatbahasa', $params);
		return $mresult;
	}

	function addRiwayatBahasa($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatbahasa(?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatBahasa($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatbahasa(?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatBahasa($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatbahasa(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getRiwayatPenyakit($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatpenyakit', $params);
		return $mresult;
	}

	function addRiwayatPenyakit($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatpenyakit(?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatPenyakit($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatpenyakit(?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatPenyakit($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatpenyakit(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// Keahlian khusus
	function getRiwayatKeahlian($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatkeahlian', $params);
		return $mresult;
	}

	function addRiwayatKeahlian($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatkeahlian(?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatKeahlian($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatkeahlian(?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatKeahlian($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatkeahlian(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// end
	// Catatan Tambahan
	function getRiwayatCatatanTambahan($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatcatatantambahan', $params);
		return $mresult;
	}

	function addRiwayatCatatanTambahan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatcatatantambahan(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatCatatanTambahan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatcatatantambahan(?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatCatatanTambahan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatcatatantambahan(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// end
	function getRiwayatAGP($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatagp', $params);
		return $mresult;
	}

	function addRiwayatAGP($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatagp(?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatAGP($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatagp(?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatAGP($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatagp(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// mutasi & promosi
	function getMutasiPromosi($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getmutasipromosi', $params);
		return $mresult;
	}

	function addRiwayatMutasiPromosi($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatmutasipromosi(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatMutasiPromosi($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatmutasipromosi(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatJabatanMP($params2)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatjabatanrmp(?,?,?,?,?,?,?);
		", $params2);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updModulpegawai($pegawaiid)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updmodulpegawai(?);
		", $pegawaiid);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatMutasiPromosi($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatmutasipromosi(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// indiplisiner
	function getRiwayatIndiplisiner($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatindiplisiner', $params);
		return $mresult;
	}

	function addRiwayatIndiplisiner($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatind(?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatIndiplisiner($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatind(?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatIndiplisiner($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatindiplisiner(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// end indiplisiner
	function getRiwayatPA($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getriwayatpa', $params);
		return $mresult;
	}

	function addRiwayatPA($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatpa(?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatPA($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatpa(?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatPA($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatpa(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// end pa
	// acting as
	function getActingAs($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getactingas', $params);
		return $mresult;
	}

	function addRiwayatActingAs($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addriwayatactingas(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatActingAs($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatactingas(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatJabatanAct($params2)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updriwayatjabatanact(?,?,?,?,?,?,?);
		", $params2);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatActingAs($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_delriwayatactingas(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// Acting AS END
	function getReportIdentitasRiwJabatan()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT rj.pegawaiid, sp.statuspegawai, jab.jabatan, l.level, l.gol
			FROM riwayatjabatan rj
			LEFT JOIN statuspegawai sp ON rj.statuspegawaiid = sp.statuspegawaiid
			LEFT JOIN jabatan jab ON rj.jabatanid = jab.jabatanid
			LEFT JOIN level l ON rj.levelid = l.levelid
			WHERE rj.pegawaiid = '000000000018'
		");
		$this->db->close();
	}

	function movingSatker($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("update pegawai set satkerid = ? where pegawaiid = ?", array($params['v_satkeridtarget'], $params['v_pegawaiid']));
		$q = $this->db->query("update riwayatjabatan set satkerid = ? where pegawaiid = ?", array($params['v_satkeridtarget'], $params['v_pegawaiid']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getreportpegawaiByID($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_pegawaiid'])) {
			$cond_where = " WHERE p.pegawaiid = '" . $params['v_pegawaiid'] . "'";
		}

		$q = $this->db->query("
		SELECT * FROM public.vwreportcetakpegawai p
		" . $cond_where);

		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getMutasiPromosiByNik($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_nik'])) {
			$cond_where = " WHERE p.nik = '" . $params['v_nik'] . "'";
		}

		$q = $this->db->query("
		SELECT vj.satkerid, j.jabatan, vj.jabatanid, l.level, vj.levelid, l.gol, vj.lokasikerja, loc.lokasi,
        	public.fnsatkerlevel(vj.satkerid,'1') AS direktorat,
            public.fnsatkerlevel(vj.satkerid,'2') AS divisi,
            public.fnsatkerlevel(vj.satkerid,'3') AS departemen,
            public.fnsatkerlevel(vj.satkerid,'4') AS seksi,
            public.fnsatkerlevel(vj.satkerid,'5') AS subseksi
        FROM pegawai p
        LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
        LEFT JOIN satker s ON vj.satkerid = s.satkerid
        LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
        LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
        LEFT JOIN level l ON vj.levelid = l.levelid
		" . $cond_where);

		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
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
