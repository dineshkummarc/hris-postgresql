<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_report extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getReportUlangtahun($params)
	{
		$this->load->database();

		$query = "
			SELECT p.pegawaiid,p.nik,p.namadepan,
			public.fnsatkerlevel(vj.satkerid,'2') AS divisi,
			public.fnsatkerlevel(vj.satkerid,'1') AS direktorat,
			public.fnsatkerlevel(vj.satkerid,'3') AS departemen,
			public.fnsatkerlevel(vj.satkerid,'4') AS seksi,
			public.fnsatkerlevel(vj.satkerid,'5') AS subseksi,
			p.tempatlahir,TO_CHAR(p.tgllahir, 'DD/MM/YYYY') tgllahir, loc.lokasi,
			CASE WHEN to_char(p.tgllahir, 'DD/MM') = to_char(CURRENT_DATE, 'DD/MM') THEN '1' ELSE NULL END birthday
			FROM pegawai p
			LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
			LEFT JOIN level l ON vj.levelid = l.levelid
			LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
			WHERE vj.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND vj.keteranganpegawai = '1' AND
			( TO_CHAR(p.tgllahir, 'DD') >= '" . $params['v_hari'] . "' AND TO_CHAR(p.tgllahir, 'DD') <= '31' ) AND
			( TO_CHAR(p.tgllahir, 'MM') >= '" . $params['v_bulan'] . "' AND TO_CHAR(p.tgllahir, 'MM') <= '" . $params['v_bulan'] . "' )
			ORDER BY birthday, tgllahir ASC
		";
		// AND  datediff('month'::character varying, NOW()::date, vj.tglakhirkontrak) <= 3

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

	function getReportActingAs($params)
	{
		$this->load->database();
		$q = $this->db->query("
			WITH act1 AS ( SELECT a.id,count(a.id) jml1, ra.actingas AS ke1
                        FROM month a
                        LEFT JOIN riwayatactingas ra ON a.id = date_part('month', tglmulai)
                        WHERE ra.actingas IN ('1') AND ra.satkerid1 LIKE '" . $params['v_satkerid'] . "' || '%'
                        GROUP BY a.id,ra.actingas
                        ORDER BY a.id ASC
                     ),
              act2 AS ( SELECT a.id,count(a.id) jml2, ra.actingas AS ke2
                        FROM month a
                        LEFT JOIN riwayatactingas ra ON a.id = date_part('month', tglmulai)
                        WHERE ra.actingas IN ('2') AND ra.satkerid1 LIKE '" . $params['v_satkerid'] . "' || '%'
                        GROUP BY a.id, ra.actingas
                        ORDER BY a.id ASC
                     ),
              act3 AS ( SELECT a.id,count(a.id) jml3, ra.actingas AS ke3
                        FROM month a
                        LEFT JOIN riwayatactingas ra ON a.id = date_part('month', tglmulai)
                        WHERE ra.actingas IN ('3') AND ra.satkerid1 LIKE '" . $params['v_satkerid'] . "' || '%'
                        GROUP BY a.id, ra.actingas
                        ORDER BY a.id ASC
                     ),
              act4 AS ( SELECT a.id,count(a.id) jml4, ra.actingas AS ke4
                        FROM month a
                        LEFT JOIN riwayatactingas ra ON a.id = date_part('month', tglmulai)
                        WHERE ra.actingas IN ('4') AND ra.satkerid1 LIKE '" . $params['v_satkerid'] . "' || '%'
                        GROUP BY a.id, ra.actingas
                        ORDER BY a.id ASC
                     )
        SELECT *
        FROM month m
        LEFT JOIN act1 a ON m.id = a.id
		LEFT JOIN act2 b ON m.id = b.id
        LEFT JOIN act3 c ON m.id = c.id
        LEFT JOIN act4 d ON m.id = d.id
        	UNION ALL
		SELECT NULL,'Total',NULL, SUM(COALESCE(a.jml1, 0)) AS status1,NULL,NULL,SUM(COALESCE(b.jml2, 0)) AS status2,NULL,NULL,SUM(COALESCE(c.jml3, 0)) AS status3,NULL,NULL,SUM(COALESCE(d.jml4, 0)) AS status4,NULL
		FROM month m
		LEFT JOIN act1 a ON m.id = a.id
		LEFT JOIN act2 b ON m.id = b.id
        LEFT JOIN act3 c ON m.id = c.id
        LEFT JOIN act4 d ON m.id = d.id
		");
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getReportActingAsBySatker($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_satkerid'])) {
			$cond_where = " WHERE rj.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY rmp.tglmulai DESC";
		}

		$query = "
			SELECT rmp.pegawaiid,p.nik,p.namadepan,rmp.nourut,rmp.actingas, rj.satkerid,
	        public.fnsatkerlevel(rmp.satkerid1,'1') AS direktorat1,
	        public.fnsatkerlevel(rmp.satkerid1,'2') AS divisi1,
	        public.fnsatkerlevel(rmp.satkerid1,'3') AS departemen1,
	        public.fnsatkerlevel(rmp.satkerid1,'4') AS seksi1,
	        public.fnsatkerlevel(rmp.satkerid1,'5') AS subseksi1,
	        public.fnsatkerlevel(rmp.satkerid2,'1') AS direktorat2,
	        public.fnsatkerlevel(rmp.satkerid2,'2') AS divisi2,
	        public.fnsatkerlevel(rmp.satkerid2,'3') AS departemen2,
	        public.fnsatkerlevel(rmp.satkerid2,'4') AS seksi2,
	        public.fnsatkerlevel(rmp.satkerid2,'5') AS subseksi2,
	        rmp.jabatan1 as jabatanid1,b.jabatan as jabatan1,rmp.jabatan2 as jabatanid2,c.jabatan as jabatan2,
	        rmp.levelid1 as levelid1,d.level as level1,rmp.levelid2 as levelid2,e.level as level2,
	        rmp.golongan1,rmp.golongan2,
	        rmp.lokasi1 as lokasiid1,f.lokasi as lokasi1,rmp.lokasi2 as lokasiid2,g.lokasi as lokasi2,
	        rmp.satkerid1 as satkerid1,s.satker as satker1,rmp.satkerid2 as satkerid2,st.satker as satker2,
	        rmp.tglmulai,rmp.tglakhir,rmp.keterangan
        FROM riwayatactingas rmp
	        LEFT JOIN jabatan b ON rmp.jabatan1 = b.jabatanid
	        LEFT JOIN jabatan c ON rmp.jabatan2 = c.jabatanid
	        LEFT JOIN level d ON CAST((rmp.levelid1)AS INT) = d.levelid
	        LEFT JOIN level e ON CAST((rmp.levelid2)AS INT) = e.levelid
	        LEFT JOIN lokasi f ON CAST((rmp.lokasi1)AS INT) = f.lokasiid
	        LEFT JOIN lokasi g ON CAST((rmp.lokasi2)AS INT) = g.lokasiid
	        LEFT JOIN satker s ON rmp.satkerid1 = s.satkerid
	        LEFT JOIN satker st ON rmp.satkerid2 = st.satkerid
	        LEFT JOIN pegawai p ON rmp.pegawaiid = p.pegawaiid
	        LEFT JOIN riwayatjabatan rj ON rmp.pegawaiid = rj.pegawaiid
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

	function getMutasiPromosi($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_satkerid'])) {
			$cond_where = " WHERE rj.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY rmp.tglmulai DESC";
		} else {
			$cond_where = " ORDER BY rmp.tglmulai DESC";
		}

		$query = "
			SELECT rmp.pegawaiid,rmp.nourut,p.nik,p.namadepan,
        CASE WHEN rmp.mutasipromosi = '1' THEN 'Mutasi' ELSE 'Promosi' END mutasipromosi,
        public.fnsatkerlevel(rmp.satkerid1,'1') AS direktorat1,
        public.fnsatkerlevel(rmp.satkerid1,'2') AS divisi1,
        public.fnsatkerlevel(rmp.satkerid1,'3') AS departemen1,
        public.fnsatkerlevel(rmp.satkerid1,'4') AS seksi1,
        public.fnsatkerlevel(rmp.satkerid1,'5') AS subseksi1,
        public.fnsatkerlevel(rmp.satkerid2,'1') AS direktorat2,
        public.fnsatkerlevel(rmp.satkerid2,'2') AS divisi2,
        public.fnsatkerlevel(rmp.satkerid2,'3') AS departemen2,
        public.fnsatkerlevel(rmp.satkerid2,'4') AS seksi2,
        public.fnsatkerlevel(rmp.satkerid2,'5') AS subseksi2,
        rmp.jabatan1 as jabatanid1,b.jabatan as jabatan1,rmp.jabatan2 as jabatanid2,c.jabatan as jabatan2,
        rmp.levelid1 as levelid1,d.level as level1,rmp.levelid2 as levelid2,e.level as level2,
        rmp.golongan1,rmp.golongan2,
        rmp.lokasi1 as lokasiid1,f.lokasi as lokasi1,rmp.lokasi2 as lokasiid2,g.lokasi as lokasi2,
        rmp.satkerid1 as satkerid1,s.satker as satker1,rmp.satkerid2 as satkerid2,st.satker as satker2,
        TO_CHAR(rmp.tglmulai, 'DD/MM/YYYY') tglmulai,TO_CHAR(rmp.tglakhir, 'DD/MM/YYYY') tglakhir,rmp.keterangan
        FROM riwayatmutasipromosi rmp
        LEFT JOIN jabatan b ON rmp.jabatan1 = b.jabatanid
        LEFT JOIN jabatan c ON rmp.jabatan2 = c.jabatanid
        LEFT JOIN level d ON CAST((rmp.levelid1)AS INT) = d.levelid
        LEFT JOIN level e ON CAST((rmp.levelid2)AS INT) = e.levelid
        LEFT JOIN lokasi f ON CAST((rmp.lokasi1)AS INT) = f.lokasiid
        LEFT JOIN lokasi g ON CAST((rmp.lokasi2)AS INT) = g.lokasiid
        LEFT JOIN satker s ON rmp.satkerid1 = s.satkerid
        LEFT JOIN satker st ON rmp.satkerid2 = st.satkerid
        LEFT JOIN riwayatjabatan rj ON rmp.pegawaiid = rj.pegawaiid
        LEFT JOIN pegawai p ON rmp.pegawaiid = p.pegawaiid
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

	function statistikDivisi($satkerid)
	{
		$this->load->database();
		$q = $this->db->query("
			select
				a.id as satkerid ,
				a.text as satker,
				count(b.pegawaiid) as jml
			from strukturnew.vwunitkerja a
				left join strukturnew.satkerpegawai b on b.satkerdisp like a.id || '%'
				left join riwayatjabatan c on c.pegawaiid = b.pegawaiid
				left join strukturnew.s1_unitkerja d on d.unitcode = left(a.id,3)
			WHERE c.tglselesai is null
			and   a.id like ? || '%' 
			and (length(a.id) = length(?) + 2 OR length(a.id) = length(?) + 3) 
			and a.id <> ?
			GROUP BY a.id, a.text, d.id
			order by d.id
		", array($satkerid, $satkerid, $satkerid, $satkerid));
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListDivisi($params)
	{
		$this->load->database();
		$query = "
			SELECT * FROM reporthris.vwreportdivisi
			WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
			ORDER BY idnew
		";

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

	function statistikStatusPegawai($satkerid)
	{
		$this->load->database();
		$q = $this->db->query("
		with ctepegawai as ( 
			select 
				a.pegawaiid ,
				b.satkerid ,
				a.statuspegawaiid
			from riwayatjabatan a	
			left join strukturnew.satkerpegawai b on b.pegawaiid = a.pegawaiid
			where a.tglselesai is null )
			select
				a.statuspegawaiid as labelid ,
				a.statuspegawai as label ,
				count(b.pegawaiid) as jml
			from statuspegawai a
			left join ctepegawai b on b.statuspegawaiid = a.statuspegawaiid 
			where a.statuspegawaiid in ('1','2','3')
			and b.satkerid like ? || '%'
			group by a.statuspegawaiid
		", array($satkerid));
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListStatusPegawai($params)
	{
		$this->load->database();

		$query = "
			SELECT * FROM reporthris.vwreportstatuspegawai
			WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
			AND statuspegawaiid = COALESCE(CAST(" . $params['v_statuspegawaiid'] . " AS VARCHAR), statuspegawaiid)
			ORDER BY idnew
		";

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

	function reportListJenisKelamin($params)
	{
		$this->load->database();

		$query = "
			SELECT * from reporthris.vwreportbygender
			WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
			AND jeniskelamin LIKE '" . $params['v_jeniskelamin'] . "' || '%'
			ORDER BY idnew
			";

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
		return $result;
	}

	function statistikJenisKelamin($satkerid)
	{
		$this->load->database();
		$q = $this->db->query("
				SELECT
				sp.label AS labelid,
				CASE WHEN sp.label = 'L' THEN 'Laki-laki' 
					 WHEN sp.label = 'P' THEN 'Perempuan' 
				ELSE NULL 
				END AS label,
				COUNT ( P.pegawaiid ) AS jml 
				FROM
				( SELECT 'L' AS label UNION ALL SELECT 'P' AS label ) sp
				LEFT JOIN pegawai P ON sp.label = P.jeniskelamin
				LEFT JOIN vwjabatanterakhir vj ON P.pegawaiid = vj.pegawaiid
				LEFT JOIN strukturnew.satkerpegawai b ON b.pegawaiid = P.pegawaiid 
				WHERE vj.keteranganpegawai = '1' 
				AND b.satkerid LIKE ? || '%' 
				GROUP BY sp.label
		", array($satkerid));
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getReportSDM($satkerid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('reporthris.sp_getreportstatuspegawai', array($satkerid));
		$result = array('success' => true, 'data' => $mresult['data']);
		return $result;
	}

	function reportListSdm($params)
	{
		$this->load->database();

		$cond_where = '';
		if ($params['v_satkerid'] == 'ECI') {
			if (!empty($params['v_golongan'])) {
				if ($params['v_golongan'] == 'bod') {
					$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND gol = '0'";
				} else {
					if ($params['v_golongan'] == 'null') {
						$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'";
					} else {
						$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND gol = '" . $params['v_golongan'] . "'";
					}
				}
			} else {
				$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'";
			}
		} else {
			if (!empty($params['v_golongan'])) {
				if ($params['v_golongan'] == 'bod') {
					$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND gol = '0'";
				} else {
					if ($params['v_golongan'] == 'null') {
						$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'";
					} else {
						$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND gol = '" . $params['v_golongan'] . "'";
					}
				}
			} else {
				$cond_where = " WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'";
			}
		}

		$query = "
			SELECT * FROM reporthris.vwreportsdm
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


	function getGraphByLocation()
	{
		$this->load->database();
		$q = $this->db->query("
			WITH ctelocation AS (
				SELECT l.lokasiid, l.kodelokasi, COUNT(l.lokasiid) jml
				FROM lokasi l
				LEFT JOIN vwjabatanterakhir vj ON l.lokasiid = vj.lokasikerja
				WHERE vj.satkerid LIKE '01' || '%'
				GROUP BY l.lokasiid
			)
			SELECT a.lokasiid, a.lokasi AS lokasi, COALESCE(b.jml, 0) jml
			FROM lokasi a
			LEFT JOIN ctelocation b ON a.lokasiid = b.lokasiid
			ORDER BY a.lokasiid
		");
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getLokasiByID($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_lokasiid'])) {
			$cond_where = " WHERE a.lokasiid = '" . $params['v_lokasiid'] . "' ";
		}

		$q = $this->db->query("
			WITH ctelocation AS (
				SELECT l.lokasiid, l.kodelokasi, COUNT(l.lokasiid) jml
				FROM lokasi l
				LEFT JOIN vwjabatanterakhir vj ON l.lokasiid = vj.lokasikerja
				WHERE vj.satkerid LIKE '01' || '%' AND vj.keteranganpegawai = '1'
				GROUP BY l.lokasiid, vj.keteranganpegawai = '1'
			)
			SELECT a.lokasiid, a.lokasi AS lokasi, a.kodelokasi, COALESCE(b.jml, 0) jml
			FROM lokasi a
			LEFT JOIN ctelocation b ON a.lokasiid = b.lokasiid
		" . $cond_where);

		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListLocation($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_lokasiid'])) {
			$cond_where = " WHERE vj.lokasikerja = '" . $params['v_lokasiid'] . "' AND vj.keteranganpegawai = '1'";
		}

		$query = "
			SELECT p.pegawaiid, fnnamalengkap(p.namadepan, p.namabelakang) nama, p.nik, vj.satkerid,
				public.fnsatkerlevel(vj.satkerid,'1') AS direktorat,
				public.fnsatkerlevel(vj.satkerid,'2') AS divisi,
				public.fnsatkerlevel(vj.satkerid,'3') AS departemen,
				public.fnsatkerlevel(vj.satkerid,'4') AS seksi,
				public.fnsatkerlevel(vj.satkerid,'5') AS subseksi,
				vj.jabatanid, j.jabatan, vj.levelid, l.level, l.gol,
				p.emailkantor, p.telp, vj.lokasikerja lokasiid, loc.lokasi, sp.statuspegawai,
				TO_CHAR(vj.tglmulai, 'DD/MM/YYYY') tglmulai, TO_CHAR(vj.tglakhirkontrak, 'DD/MM/YYYY') tglakhirkontrak
			FROM pegawai p
			LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
			LEFT JOIN satker s ON vj.satkerid = s.satkerid
			LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
			LEFT JOIN level l ON vj.levelid = l.levelid
			LEFT JOIN statuspegawai sp ON vj.statuspegawaiid = sp.statuspegawaiid
			LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
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

	function getGraphByLevel($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT a.levelid, a.level, COALESCE(b.jml, 0) jml
			FROM level a
			RIGHT JOIN (
				SELECT l.levelid, l.level, COUNT(vj.levelid) jml
				FROM vwjabatanterakhir vj
				LEFT JOIN level l ON vj.levelid = l.levelid
				left join strukturnew.satkerpegawai b on b.pegawaiid = vj.pegawaiid
				WHERE b.satkerid LIKE ? || '%'
				AND vj.keteranganpegawai = '1'
				GROUP BY l.levelid
			) b ON a.levelid = b.levelid
			ORDER BY a.gol, a.levelid
		", $params);
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListLevel($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_levelid'])) {
			$cond_where = " 
			AND levelid = COALESCE('" . $params['v_levelid'] . "', vj.levelid)
			";
		}

		$query = "
			SELECT * from reporthris.vwreportbylevel
			WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
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

	function getReportEndOfContract($params)
	{
		$this->load->database();

		$query = "
			SELECT * FROM reporthris.vwreporteoc
			WHERE satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
			ORDER BY monthexp, to_date(tglakhirkontrak,'DD/MM/YYYY')
		";
		//AND  datediff('month'::character varying, NOW()::date, vj.tglakhirkontrak) <= 3
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

	function getGraphByKetPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('sp_getstatistikketpegawai', $params);
		return $mresult;
	}

	function getReportListKetPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('sp_getreportketpegawai', $params, false);
		return $mresult;
	}

	function getGraphByKetPegawai2()
	{
		$this->load->database();
		$q = $this->db->query("
			WITH cte_ketpegawai AS (
				SELECT 1 AS labelid, vj.pegawaiid
				FROM vwjabatanterakhir vj
				WHERE vj.keteranganpegawai = '1' AND vj.statuspegawaiid IN ('2','3')
				UNION ALL
				SELECT 2 AS labelid, vj.pegawaiid
				FROM vwjabatanterakhir vj
				WHERE vj.keteranganpegawai = '2'
			)
			SELECT a.labelid,
				CASE WHEN a.labelid = 1 THEN 'Karyawan Baru' ELSE 'Karyawan Berhenti' END AS label,
			COUNT(b.pegawaiid) jml
			FROM (
				SELECT 1 AS labelid
				UNION ALL
				SELECT 2 AS labelid
			) a
			LEFT JOIN cte_ketpegawai b ON a.labelid = b.labelid
			GROUP BY a.labelid
		");
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListUsiaPegawai($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_labelid'])) {
			$cond_where = "
			WHERE b.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND b.labelid = '" . $params['v_labelid'] . "'
			GROUP BY b.satkerid,b.nik,b.namadepan,b.tahun,b.bulan,b.direktorat,b.divisi,b.departemen,b.seksi,b.subseksi,b.lokasi,b.statuspegawai,b.labelid,b.tglmulai,b.tgllahir,b.jabatan,b.level,b.levelid,b.gol
			ORDER BY
			case
				when b.levelid = '13' and b.nik = '1100003' then 1
				when b.levelid = '13' and b.nik = '20050035' then 2
				when b.levelid = '13' and b.nik = '20080157' then 3
				when b.levelid = '13' and b.nik = '20080158' then 4
				when b.levelid = '13' and b.nik = '20080159' then 5
				when b.levelid = '13' and b.nik = '18000174' then 6
				when b.levelid = '10' then 7
				when b.levelid = '11' then 8
				else 9 end,
				b.gol ASC,
        	COALESCE(b.satkerid,'99') ASC ";
		} else {
			$cond_where = "
			WHERE b.satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
			GROUP BY b.satkerid,b.nik,b.namadepan,b.tahun,b.bulan,b.direktorat,b.divisi,b.departemen,b.seksi,b.subseksi,b.lokasi,b.statuspegawai,b.labelid,b.tglmulai,b.tgllahir,b.jabatan,b.level,b.levelid,b.gol
			ORDER BY
			case
				when b.levelid = '13' and b.nik = '1100003' then 1
				when b.levelid = '13' and b.nik = '20050035' then 2
				when b.levelid = '13' and b.nik = '20080157' then 3
				when b.levelid = '13' and b.nik = '20080158' then 4
				when b.levelid = '13' and b.nik = '20080159' then 5
				when b.levelid = '13' and b.nik = '18000174' then 6
				when b.levelid = '10' then 7
				when b.levelid = '11' then 8
				else 9 end,
				b.gol ASC,
        	COALESCE(b.satkerid,'99') ASC ";
		}

		$query = "
			WITH act1 AS (
				SELECT
					CASE
					WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
					< '25' THEN '1'
					WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
					>= '25' AND ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
					< '35' THEN '2'
					WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
					>= '35' AND ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
					<'45' THEN '3'
					WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
					>= '45' AND ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 ) <
					'55' THEN '4'
					WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
					>= '55' THEN '5'
					ELSE null END labelid,
					a.pegawaiid,
					a.nik,
					a.namadepan,
					( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 ) tahun,
					( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) % 12 ) bulan,
					b.satkerid,
					public.fnsatkerlevel(b.satkerid,'1') AS direktorat,
					public.fnsatkerlevel(b.satkerid,'2') AS divisi,
					public.fnsatkerlevel(b.satkerid,'3') AS departemen,
					public.fnsatkerlevel(b.satkerid,'4') AS seksi,
					public.fnsatkerlevel(b.satkerid,'5') AS subseksi,
					c.lokasi, b.statuspegawaiid, sp.statuspegawai,
					TO_CHAR(b.tglmulai, 'DD/MM/YYYY') tglmulai,
					TO_CHAR(a.tgllahir, 'DD/MM/YYYY') tgllahir,
					j.jabatan,l.level,l.levelid,l.gol
				FROM
					pegawai a
					LEFT JOIN vwjabatanterakhir b on a.pegawaiid = b.pegawaiid
					LEFT JOIN lokasi c on b.lokasikerja = c.lokasiid
					LEFT JOIN statuspegawai sp ON b.statuspegawaiid = sp.statuspegawaiid
					LEFT JOIN jabatan j ON b.jabatanid = j.jabatanid
					LEFT JOIN level l ON b.levelid = l.levelid
				WHERE
				b.keteranganpegawai = '1'
				ORDER BY labelid ASC
			)
			SELECT b.satkerid,b.nik,b.namadepan,b.direktorat,b.divisi,b.departemen,b.seksi,b.subseksi,
			b.lokasi,b.statuspegawai,b.labelid,b.tahun,b.bulan,b.tglmulai,b.tgllahir,b.jabatan,b.level,b.levelid,b.gol
			FROM (
					SELECT 1 AS labelid
					UNION ALL
					SELECT 2 AS labelid
					UNION ALL
					SELECT 3 AS labelid
					UNION ALL
					SELECT 4 AS labelid
					UNION ALL
					SELECT 5 AS labelid
			) a
			LEFT JOIN act1 b ON a.labelid = CAST(b.labelid AS INT)
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

	function statistikUsiaPegawai($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT sp.label AS labelid,
				CASE
					WHEN sp.label = '1' THEN 'n < 25'
					WHEN sp.label = '2' THEN '25 => n < 35'
					WHEN sp.label = '3' THEN '35 => n < 45'
					WHEN sp.label = '4' THEN '45 => n < 55'
					WHEN sp.label = '5' THEN '55 => n'
				ELSE NULL END AS label,
				COUNT(vj.pegawaiid) jml
			FROM (
				SELECT '1' AS label
				UNION ALL
				SELECT '2' AS label
				UNION ALL
				SELECT '3' AS label
				UNION ALL
				SELECT '4' AS label
				UNION ALL
				SELECT '5' AS label
			) sp
			LEFT JOIN
			(	WITH act1 AS (
						SELECT
							CASE
							WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
							< '25' THEN '1'
							WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
							>= '25' AND ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
							< '35' THEN '2'
							WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
							>= '35' AND ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
							<'45' THEN '3'
							WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
							>= '45' AND ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 ) <
							'55' THEN '4'
							WHEN ( datediff ( 'month' :: CHARACTER VARYING, a.tgllahir, ( now()) :: DATE ) / 12 )
							>= '55' THEN '5'
							ELSE null END labelid,
							a.pegawaiid
						FROM
							pegawai a
							LEFT JOIN vwjabatanterakhir b on a.pegawaiid = b.pegawaiid
						WHERE
						b.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND b.keteranganpegawai = '1'
						ORDER BY labelid ASC
					)
					SELECT a.labelid,b.pegawaiid
					FROM (
							SELECT 1 AS labelid
							UNION ALL
							SELECT 2 AS labelid
							UNION ALL
							SELECT 3 AS labelid
							UNION ALL
							SELECT 4 AS labelid
							UNION ALL
							SELECT 5 AS labelid
					) a
					LEFT JOIN act1 b ON a.labelid = CAST(b.labelid AS INT)
			) vj ON CAST(sp.label AS INT) = vj.labelid
			GROUP BY sp.label
		", array($params));
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function testingData()
	{
		$data = array(
			array('nik' => '199928881', 'nama' => 'Nama 1', 'statuspegawai' => 'permanent', 'jabatan' => 'Analyst', 'satker' => 'Operation'),
			array('nik' => '199928882', 'nama' => 'Nama 2', 'statuspegawai' => 'permanent', 'jabatan' => 'Analyst 2', 'satker' => 'Operation'),
			array('nik' => '199928883', 'nama' => 'Nama 3', 'statuspegawai' => 'permanent', 'jabatan' => 'Analyst 3', 'satker' => 'Operation'),
			array('nik' => '199928884', 'nama' => 'Nama 3', 'statuspegawai' => 'permanent', 'jabatan' => 'Analyst 4', 'satker' => 'Operation'),
			array('nik' => '199928885', 'nama' => 'Nama 5', 'statuspegawai' => 'permanent', 'jabatan' => 'Analyst 5', 'satker' => 'Operation'),
		);
		return $data;
	}

	function testingData2()
	{
		$data = array(
			array('satker' => 'Sub Business Unit & Compliance', 'jml' => 30),
			array('satker' => 'Special Project Market Development', 'jml' => 52),
			array('satker' => 'Operation', 'jml' => 30),
			array('satker' => 'IT & MIS', 'jml' => 10),
		);
		return $data;
	}

	function getReportListKader($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_levelid']) || !empty($params['v_lokasiid'])) {
			if (!empty($params['v_lokasiid']) && !empty($params['v_levelid'])) {
				$cond_where = " AND a.lokasiid = '" . $params['v_lokasiid'] . "' AND a.levelid = COALESCE('" . $params['v_levelid'] . "', a.levelid) ";
			} else if (!empty($params['v_levelid'])) {
				$cond_where = " AND a.levelid =  COALESCE('" . $params['v_levelid'] . "', a.levelid) ";
			} else {
				$cond_where = " AND a.lokasiid = '" . $params['v_lokasiid'] . "' ";
			}
		}

		if (!empty($params['v_pegawaiid'])) {
			$cond_where = " AND a.pegawaiid IN('" . $params['v_pegawaiid'] . "') ";
		}

		$orderby = "ORDER BY CASE WHEN a.levelid = '13' AND a.nik = '1100003' THEN 1
			WHEN a.levelid = '13' AND a.nik = '20050035' THEN 2
			WHEN a.levelid = '13' AND a.nik = '20080157' THEN 3
			WHEN a.levelid = '13' AND a.nik = '20080158' THEN 4
			WHEN a.levelid = '13' AND a.nik = '20080159' THEN 5
			WHEN a.levelid = '13' AND a.nik = '18000174' THEN 6
			WHEN a.levelid = '10' THEN 7
			WHEN a.levelid = '11' THEN 8
			ELSE 9 END, a.gol ASC,
			COALESCE(a.satkerid,'99') ASC
		";

		$query = "
			SELECT * FROM vwreportkader a
			WHERE a.satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
		" . $cond_where . $orderby;

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

	function getReportListKaderGroup($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_levelid']) || !empty($params['v_lokasiid'])) {
			if (!empty($params['v_lokasiid']) && !empty($params['v_levelid'])) {
				$cond_where = " AND a.lokasiid = '" . $params['v_lokasiid'] . "' AND a.levelid = COALESCE('" . $params['v_levelid'] . "', a.levelid) ";
			} else if (!empty($params['v_levelid'])) {
				$cond_where = " AND a.levelid =  COALESCE('" . $params['v_levelid'] . "', a.levelid) ";
			} else {
				$cond_where = " AND a.lokasiid = '" . $params['v_lokasiid'] . "' ";
			}
		}

		if (!empty($params['v_pegawaiid'])) {
			$cond_where = " AND a.pegawaiid IN('" . $params['v_pegawaiid'] . "') ";
		}

		$orderby = "ORDER BY CASE WHEN a.levelid = '13' AND a.nik = '1100003' THEN 1
			WHEN a.levelid = '13' AND a.nik = '20050035' THEN 2
			WHEN a.levelid = '13' AND a.nik = '20080157' THEN 3
			WHEN a.levelid = '13' AND a.nik = '20080158' THEN 4
			WHEN a.levelid = '13' AND a.nik = '20080159' THEN 5
			WHEN a.levelid = '13' AND a.nik = '18000174' THEN 6
			WHEN a.levelid = '10' THEN 7
			WHEN a.levelid = '11' THEN 8
			ELSE 9 END, a.gol ASC,
			COALESCE(a.satkerid,'99') ASC
		";

		$query = "
			SELECT * FROM vwreportkader a
			WHERE a.satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
		" . $cond_where . $orderby;

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

	function getReportBudget($params)
	{
		$this->load->database();
		$q = $this->db->query("
		SELECT b.nik,b.namadepan,d.jenisform,TO_CHAR(c.tglmulai,'DD/MM/YYYY') tglmulai,TO_CHAR(c.tglselesai,'DD/MM/YYYY') tglselesai,e.kota,a.grandtotal,a.idaccomodation
        FROM pjdinas.history_budget a
        LEFT JOIN pegawai b ON a.pegawaiid = b.pegawaiid
        LEFT JOIN pjdinas.detailpengajuandinas c ON a.pengajuanid = c.pengajuanid
        LEFT JOIN pjdinas.jenisform d ON c.jenisformid = d.jenisformid
        LEFT JOIN pjdinas.kota e ON c.kotaid = e.kotaid
		LEFT JOIN vwjabatanterakhir f ON a.pegawaiid = f.pegawaiid
	    WHERE f.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND c.jenisformid = '1'
		");
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getReportLpj($params)
	{
		$this->load->database();
		$q = $this->db->query("
		SELECT b.nik,b.namadepan,d.jenisform,TO_CHAR(c.tglmulai,'DD/MM/YYYY') tglmulai,TO_CHAR(c.tglselesai,'DD/MM/YYYY') tglselesai,e.kota,g.grandtotal,a.idaccomodation,a.kelebihan,a.kekurangan
        FROM pjdinas.history_budget a
        LEFT JOIN pegawai b ON a.pegawaiid = b.pegawaiid
        LEFT JOIN pjdinas.detailpengajuandinas c ON a.pengajuanid = c.pengajuanid
        LEFT JOIN pjdinas.jenisform d ON c.jenisformid = d.jenisformid
        LEFT JOIN pjdinas.kota e ON c.kotaid = e.kotaid
		LEFT JOIN vwjabatanterakhir f ON a.pegawaiid = f.pegawaiid
		LEFT JOIN pjdinas.historypertanggungjawaban g on a.pengajuanid = g.pengajuanid
	    WHERE f.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND c.jenisformid = '2'
		");
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getReportRealisasi($params)
	{
		$this->load->database();
		$q = $this->db->query("
		select y.dept, y.id, y.type, y.coa, y.descr, y.nama, y.januari, y.februari, y.maret, y.april, y.mei, y.juni, y.juli, y.agustus, y.september, y.oktober, y.november, y.desember,
y.januari+y.februari+y.maret+y.april+y.mei+y.juni+y.juli+y.agustus+y.september+y.oktober+y.november+y.desember as grandtotal
from
(
select x.dept, cast('OPEX' as text) as type, x.id, x.periode, x.coa, x.descr, x.nama,
		coalesce(sum(x.januari),0) as januari,
		coalesce(sum(x.februari),0) as februari,
		coalesce(sum(x.maret),0) as maret,
		coalesce(sum(x.april),0) as april,
		coalesce(sum(x.mei),0) as mei,
		coalesce(sum(x.juni),0) as juni,
		coalesce(sum(x.juli),0) as juli,
		coalesce(sum(x.agustus),0) as agustus,
		coalesce(sum(x.september),0) as september,
		coalesce(sum(x.oktober),0) as oktober,
		coalesce(sum(x.november),0) as november,
		coalesce(sum(x.desember),0) as desember,
		coalesce(sum(x.januari),0)+coalesce(sum(x.februari),0)+coalesce(sum(x.maret),0)+coalesce(sum(x.april),0)+coalesce(sum(x.mei),0)+coalesce(sum(x.juni),0)+coalesce(sum(x.juli),0)+coalesce(sum(x.agustus),0)+coalesce(sum(x.september),0)+coalesce(sum(x.oktober),0)+coalesce(sum(x.november),0)+coalesce(sum(x.desember),0) as grandtotal
		from
		(
		select a.id, a.nama, a.periode, a.coa,
		case
			when a.nama LIKE '%Accomodation - %' then substr(a.nama, 0, 13)
			when a.nama LIKE '%Ticket Fee - %' then substr(a.nama, 0, 11)
		end descr,
		case
			when a.nama LIKE '%Accomodation - %' then substr(a.nama, 16)
			when a.nama LIKE '%Ticket Fee - %' then substr(a.nama, 14)
		end dept,
		case
			when EXTRACT(month from f.tglpermohonan) = '1' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '1' then sum(e.accomodation)
		end januari,
		case
			when EXTRACT(month from f.tglpermohonan) = '2' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '2' then sum(e.accomodation)
		end februari,
		case
			when EXTRACT(month from f.tglpermohonan) = '3' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '3' then sum(e.accomodation)
		end maret,
		case
			when EXTRACT(month from f.tglpermohonan) = '4' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '4' then sum(e.accomodation)
		end april,
		case
			when EXTRACT(month from f.tglpermohonan) = '5' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '5' then sum(e.accomodation)
		end mei,
		case
			when EXTRACT(month from f.tglpermohonan) = '6' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '6' then sum(e.accomodation)
		end juni,
		case
			when EXTRACT(month from f.tglpermohonan) = '7' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '7' then sum(e.accomodation)
		end juli,
		case
			when EXTRACT(month from f.tglpermohonan) = '8' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '8' then sum(e.accomodation)
		end agustus,
		case
			when EXTRACT(month from f.tglpermohonan) = '9' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '9' then sum(e.accomodation)
		end september,
		case
			when EXTRACT(month from f.tglpermohonan) = '10' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '10' then sum(e.accomodation)
		end oktober,
		case
			when EXTRACT(month from f.tglpermohonan) = '11' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '11' then sum(e.accomodation)
		end november,
		case
			when EXTRACT(month from f.tglpermohonan) = '12' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '12' then sum(e.accomodation)
		end desember
		from pjdinas.kodesapbudget a
		LEFT JOIN pjdinas.historybiayapengajuan b on a.id = b.idticket
		LEFT JOIN pjdinas.historybiayapengajuan c on a.id = c.idacco
		LEFT JOIN pjdinas.historypertanggungjawaban d on b.id = d.historybiayapengajuanid
		LEFT JOIN pjdinas.historypertanggungjawaban e on c.id = e.historybiayapengajuanid
		LEFT JOIN pjdinas.pengajuandinas f on d.pengajuanid = f.pengajuanid
		LEFT JOIN pjdinas.pengajuandinas g on e.pengajuanid = g.pengajuanid
		where a.periode = '" . $params['v_tahun'] . "'
		GROUP BY a.id, a.nama, a.periode, a.coa, EXTRACT(month from f.tglpermohonan), EXTRACT(month from g.tglpermohonan), d.biayatiket, e.accomodation
		ORDER BY a.id
		) x
		where x.dept is not null
		GROUP BY x.id, x.nama, x.periode, x.coa, x.descr,x.dept
		ORDER BY x.id
) y
union all
select null, null, null, null, null, 'TOTAL', sum(januari), sum(februari), sum(maret), sum(april), sum(mei), sum(juni), sum(juli), sum(agustus), sum(september), sum(oktober), sum(november), sum(desember),
coalesce(sum(januari),0)+coalesce(sum(februari),0)+coalesce(sum(maret),0)+coalesce(sum(april),0)+coalesce(sum(mei),0)+coalesce(sum(juni),0)+coalesce(sum(juli),0)+coalesce(sum(agustus),0)+coalesce(sum(september),0)+coalesce(sum(oktober),0)+coalesce(sum(november),0)+ coalesce(sum(desember),0)
from

(
		select a.id, a.nama, a.periode, a.coa,
		case
			when a.nama LIKE '%Accomodation - %' then substr(a.nama, 0, 13)
			when a.nama LIKE '%Ticket Fee - %' then substr(a.nama, 0, 11)
		end descr,
		case
			when a.nama LIKE '%Accomodation - %' then substr(a.nama, 16)
			when a.nama LIKE '%Ticket Fee - %' then substr(a.nama, 14)
		end dept,
		case
			when EXTRACT(month from f.tglpermohonan) = '1' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '1' then sum(e.accomodation)
		end januari,
		case
			when EXTRACT(month from f.tglpermohonan) = '2' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '2' then sum(e.accomodation)
		end februari,
		case
			when EXTRACT(month from f.tglpermohonan) = '3' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '3' then sum(e.accomodation)
		end maret,
		case
			when EXTRACT(month from f.tglpermohonan) = '4' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '4' then sum(e.accomodation)
		end april,
		case
			when EXTRACT(month from f.tglpermohonan) = '5' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '5' then sum(e.accomodation)
		end mei,
		case
			when EXTRACT(month from f.tglpermohonan) = '6' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '6' then sum(e.accomodation)
		end juni,
		case
			when EXTRACT(month from f.tglpermohonan) = '7' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '7' then sum(e.accomodation)
		end juli,
		case
			when EXTRACT(month from f.tglpermohonan) = '8' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '8' then sum(e.accomodation)
		end agustus,
		case
			when EXTRACT(month from f.tglpermohonan) = '9' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '9' then sum(e.accomodation)
		end september,
		case
			when EXTRACT(month from f.tglpermohonan) = '10' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '10' then sum(e.accomodation)
		end oktober,
		case
			when EXTRACT(month from f.tglpermohonan) = '11' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '11' then sum(e.accomodation)
		end november,
		case
			when EXTRACT(month from f.tglpermohonan) = '12' then sum(d.biayatiket)
			when EXTRACT(month from g.tglpermohonan) = '12' then sum(e.accomodation)
		end desember
		from pjdinas.kodesapbudget a
		LEFT JOIN pjdinas.historybiayapengajuan b on a.id = b.idticket
		LEFT JOIN pjdinas.historybiayapengajuan c on a.id = c.idacco
		LEFT JOIN pjdinas.historypertanggungjawaban d on b.id = d.historybiayapengajuanid
		LEFT JOIN pjdinas.historypertanggungjawaban e on c.id = e.historybiayapengajuanid
		LEFT JOIN pjdinas.pengajuandinas f on d.pengajuanid = f.pengajuanid
		LEFT JOIN pjdinas.pengajuandinas g on e.pengajuanid = g.pengajuanid
		where a.periode = '" . $params['v_tahun'] . "'
		GROUP BY a.id, a.nama, a.periode, a.coa, EXTRACT(month from f.tglpermohonan), EXTRACT(month from g.tglpermohonan), d.biayatiket, e.accomodation
		ORDER BY a.id
		) x
		");
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}
}
