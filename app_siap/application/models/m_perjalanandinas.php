<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_perjalanandinas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getListCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('pjdinas.sp_getlistcutipeg', $params, false);
		return $mresult;
	}

	function getListCetakForm($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT a.pengajuanid,b.jenisformid,b.tglmulai,b.tglselesai,b.keperluan,c.namadepan,e.level,
			public.fnsatkerlevel(d.satkerid,'1') AS departemen,
            trim(replace(to_char(CAST(f.transport AS INT),'99,999,999,999,999'), ',', '.')) AS transport,
            trim(replace(to_char((CAST(f.hotelprice AS INT)*(b.lama-1)),'99,999,999,999,999'), ',', '.')) AS hotelprice,
            trim(replace(to_char(((CAST(f.uangsaku AS INT)*(b.lama))+(CAST(f.uangmakan AS INT)*(b.lama))),'99,999,999,999,999'), ',', '.')) AS uangsaku,
            CASE WHEN g.lainlain IS NULL THEN '0' ELSE trim(replace(to_char(CAST(g.lainlain AS INT),'99,999,999,999,999'), ',', '.')) END lainlain,
            CASE WHEN g.uangmuka IS NULL THEN '0' ELSE trim(replace(to_char(CAST(g.uangmuka AS INT),'99,999,999,999,999'), ',', '.')) END uangmuka,
            CASE WHEN g.kelebihan IS NULL THEN '0' ELSE trim(replace(to_char(CAST(g.kelebihan AS INT),'99,999,999,999,999'), ',', '.')) END kelebihan,
            CASE WHEN g.kekurangan IS NULL THEN '0' ELSE trim(replace(to_char(CAST(g.kekurangan AS INT),'99,999,999,999,999'), ',', '.')) END kekurangan,
			trim(replace(to_char(((CAST(f.hotelprice AS INT)*(b.lama-1))+((CAST(f.uangsaku AS INT)*(b.lama))+CAST(b.biayajenistiket AS INT)+CAST(f.transport AS INT)+(CAST(f.uangmakan AS INT)*(b.lama)))),'99,999,999,999,999'), ',', '.')) AS total, a.tglpermohonan, b.jenisperjalananid, case when y.bag is not null then 'Bank Artha Graha' else null end namabank,
			c1.namadepan as namasharing, y.bag, x.jenisperjalanan, a.verifikasinotes,
			trim(replace(to_char(CAST(z.transport AS INT),'99,999,999,999,999'), ',', '.')) AS historytransport,
			trim(replace(to_char(CAST(z.hotelprice AS INT),'99,999,999,999,999'), ',', '.')) AS historyhotelprice,
			trim(replace(to_char(CAST(z.uangsaku AS INT),'99,999,999,999,999'), ',', '.')) AS historyuangsaku,
			trim(replace(to_char(CAST(z.biayatiket AS INT),'99,999,999,999,999'), ',', '.')) AS historybiayatiket,
			trim(replace(to_char(CAST(z.total AS INT),'99,999,999,999,999'), ',', '.')) AS historytotal,
			trim(replace(to_char(CAST(z1.transport AS INT),'99,999,999,999,999'), ',', '.')) AS historylpjtransport,
			trim(replace(to_char(CAST(z1.hotelprice AS INT),'99,999,999,999,999'), ',', '.')) AS historylpjhotelprice,
			trim(replace(to_char(CAST(z1.uangsaku AS INT),'99,999,999,999,999'), ',', '.')) AS historylpjuangsaku,
			trim(replace(to_char(CAST(z1.biayatiket AS INT),'99,999,999,999,999'), ',', '.')) AS historylpjbiayatiket,
			trim(replace(to_char(CAST(z1.grandtotal AS INT),'99,999,999,999,999'), ',', '.')) AS historylpjtotal,
			trim(replace(to_char(CAST(z1.lainlain AS INT),'99,999,999,999,999'), ',', '.')) AS historylpjlainlain,
			c2.namadepan as namasharinglpj,
			trim(replace(to_char(CAST(z.southeastasia AS INT),'99,999,999,999,999'), ',', '.')) AS southeastasia,
			trim(replace(to_char(CAST(z.non_southeastasia AS INT),'99,999,999,999,999'), ',', '.')) AS non_southeastasia,
			trim(replace(to_char(CAST(z.japan AS INT),'99,999,999,999,999'), ',', '.')) AS japan,
			trim(replace(to_char(CAST(z.eropa_usa_dubai AS INT),'99,999,999,999,999'), ',', '.')) AS eropa_usa_dubai,
			trim(replace(to_char(CAST(z1.southeastasia AS INT),'99,999,999,999,999'), ',', '.')) AS lpjsoutheastasia,
			trim(replace(to_char(CAST(z1.non_southeastasia AS INT),'99,999,999,999,999'), ',', '.')) AS lpjnon_southeastasia,
			trim(replace(to_char(CAST(z1.japan AS INT),'99,999,999,999,999'), ',', '.')) AS lpjjapan,
			trim(replace(to_char(CAST(z1.eropa_usa_dubai AS INT),'99,999,999,999,999'), ',', '.')) AS lpjeropa_usa_dubai,
			now()::date as now
			FROM pjdinas.pengajuandinas a
			LEFT JOIN pjdinas.detailpengajuandinas b ON a.pengajuanid = b.pengajuanid
			LEFT JOIN pegawai c ON a.pegawaiid = c.pegawaiid
			LEFT JOIN vwjabatanterakhir d ON a.pegawaiid = d.pegawaiid
			LEFT JOIN level e ON d.levelid = e.levelid
            LEFT JOIN pjdinas.rincianbiaya f ON d.levelid = f.levelid
			LEFT JOIN pjdinas.rincianbiayapengajuan g ON a.pengajuanid = g.pengajuanid
			left join pjdinas.rincianbiayaluarnegeri lur ON d.levelid = lur.levelid and lur.jenisperjalananid = '2'
			left join pjdinas.rincianbiayaluarnegeri no_lur ON d.levelid = no_lur.levelid and no_lur.jenisperjalananid = '1'
			LEFT JOIN pjdinas.historybiayapengajuan z on a.pengajuanid = z.pengajuanid
			LEFT JOIN pjdinas.historypertanggungjawaban z1 on a.pengajuanid = z1.pengajuanid
			LEFT JOIN pjdinas.historybiayapengajuan z2 on z1.historybiayapengajuanid = z2.id
			LEFT JOIN pegawai c1 ON z.idsharingpegawai = c1.pegawaiid
			LEFT JOIN pegawai c2 ON z2.idsharingpegawai = c2.pegawaiid
			LEFT JOIN pjdinas.jenisperjalanan x ON cast(b.jenisperjalananid as int) = x.id
			LEFT JOIN rekening y on c.nik = y.nik
			WHERE a.pengajuanid = '" . $params['v_pengajuanid'] . "'
		", array($params));
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
	}

	function getCutiById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('pjdinas.sp_getdinasbyid', $params);
		return $mresult['firstrow'];
	}
	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('pjdinas.sp_getdetailpengajuandinas', array($pengajuanid));
		return $mresult['data'];
	}

	function getSharingBudget($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('pjdinas.sp_getsharingbudget', $params);
		return $mresult['data'];
	}

	function getRincianBiaya($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('pjdinas.sp_getrincianbiaya', array($pengajuanid));
		return $mresult['data'];
	}
	function getRincianBiayaDinas($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('pjdinas.sp_getrincianbiayadinas', array($pengajuanid));
		return $mresult['data'];
	}

	function getRincianBiayaPertanggungan($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('pjdinas.sp_getrincianbiayapertanggungan', array($pengajuanid));
		return $mresult['data'];
	}
	function getDetailPengajuanCutiHidden($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getdetailpengajuancutiHidden', array($pengajuanid));
		return $mresult['data'];
	}
	function updStatusCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT pjdinas.sp_updstatusverifikasi(?,?,?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function updDitolakStatusCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT pjdinas.sp_updstatusverifikasibatal(?,?,?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function getInfoPegawai($params)
	{
		$conditionLevel = array('0', '1', '2', '3');
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getinfopegawaibynik', $params);
		$result1 = $mresult['firstrow'];

		$satkeridpegawai = $result1['satkerid'];
		$res = $this->getVerifikatorCuti($result1['pegawaiid']);

		$result2 = array(
			'pegawaiid' => $result1['pegawaiid'],
			'nik' => $result1['nik'],
			'nama' => $result1['nama'],
			'jabatan' => $result1['jabatan'],
			'direktorat' => $result1['direktorat'],
			'divisi' => $result1['divisi'],
			'departemen' => $result1['departemen'],
			'seksi' => $result1['seksi'],
			'subseksi' => $result1['subseksi'],
			'lokasi' => $result1['lokasi'],
			'telp' => $result1['telp'],
			'hp' => $result1['hp'],
			'alamat' => $result1['alamat'],
			'jatahcuti' => $result1['jatahcuti'],
			'sisacutithnini' => $result1['sisacutithnini'],
			'sisacutithnlalu' => $result1['sisacutithnlalu'],
			'lamacutithnini' => $result1['lamacutithnini'],
			'verifikatorid' => '',
			'verifikatornik' => '',
			'verifikatornama' => '',
			'verifikatordivisi' => '',
			'verifikatorjabatan' => '',
			'verifikatorlokasi' => '',
			'verifikatoremail' => '',
			'approvalid' => '',
			'approvalnik' => '',
			'approvalnama' => '',
			'approvaldivisi' => '',
			'approvaljabatan' => '',
			'approvallokasi' => '',
			'approvalemail' => '',
		);

		// Jika atasannya level manager up maka tidak butuh verifikator
		if (in_array($res->atasangol, $conditionLevel)) {
			$result2 = array(
				'pegawaiid' => $result1['pegawaiid'],
				'nik' => $result1['nik'],
				'nama' => $result1['nama'],
				'jabatan' => $result1['jabatan'],
				'direktorat' => $result1['direktorat'],
				'divisi' => $result1['divisi'],
				'departemen' => $result1['departemen'],
				'seksi' => $result1['seksi'],
				'subseksi' => $result1['subseksi'],
				'lokasi' => $result1['lokasi'],
				'telp' => $result1['telp'],
				'hp' => $result1['hp'],
				'alamat' => $result1['alamat'],
				'jatahcuti' => $result1['jatahcuti'],
				'sisacutithnini' => $result1['sisacutithnini'],
				'sisacutithnlalu' => $result1['sisacutithnlalu'],
				'lamacutithnini' => $result1['lamacutithnini'],
				'verifikatorid' => '',
				'verifikatornik' => '',
				'verifikatornama' => '',
				'verifikatordivisi' => '',
				'verifikatorjabatan' => '',
				'verifikatorlokasi' => '',
				'verifikatoremail' => '',
				'approvalid' => $res->atasanid,
				'approvalnik' => $res->atasannik,
				'approvalnama' => $res->atasannama,
				'approvaldivisi' => '',
				'approvaljabatan' => $res->atasanjabatan,
				'approvallokasi' => '',
				'approvalemail' => $res->email,
			);
		} else {
			$res2 = $this->getVerifikatorCuti($res->atasanid);
			// Perlakuan khusus untuk auditor tidak langsung ke presdir untuk approvalnya
			if ($satkeridpegawai == '010402') {
				$result2 = array(
					'pegawaiid' => $result1['pegawaiid'],
					'nik' => $result1['nik'],
					'nama' => $result1['nama'],
					'jabatan' => $result1['jabatan'],
					'direktorat' => $result1['direktorat'],
					'divisi' => $result1['divisi'],
					'departemen' => $result1['departemen'],
					'seksi' => $result1['seksi'],
					'subseksi' => $result1['subseksi'],
					'lokasi' => $result1['lokasi'],
					'telp' => $result1['telp'],
					'hp' => $result1['hp'],
					'alamat' => $result1['alamat'],
					'jatahcuti' => $result1['jatahcuti'],
					'sisacutithnini' => $result1['sisacutithnini'],
					'sisacutithnlalu' => $result1['sisacutithnlalu'],
					'lamacutithnini' => $result1['lamacutithnini'],
					'verifikatorid' => '',
					'verifikatornik' => '',
					'verifikatornama' => '',
					'verifikatordivisi' => '',
					'verifikatorjabatan' => '',
					'verifikatorlokasi' => '',
					'verifikatoremail' => '',
					'approvalid' => $res->atasanid,
					'approvalnik' => $res->atasannik,
					'approvalnama' => $res->atasannama,
					'approvaldivisi' => '',
					'approvaljabatan' => $res->atasanjabatan,
					'approvallokasi' => '',
					'approvalemail' => $res->email,
				);
			} else {
				$result2 = array(
					'pegawaiid' => $result1['pegawaiid'],
					'nik' => $result1['nik'],
					'nama' => $result1['nama'],
					'jabatan' => $result1['jabatan'],
					'direktorat' => $result1['direktorat'],
					'divisi' => $result1['divisi'],
					'departemen' => $result1['departemen'],
					'seksi' => $result1['seksi'],
					'subseksi' => $result1['subseksi'],
					'lokasi' => $result1['lokasi'],
					'telp' => $result1['telp'],
					'hp' => $result1['hp'],
					'alamat' => $result1['alamat'],
					'jatahcuti' => $result1['jatahcuti'],
					'sisacutithnini' => $result1['sisacutithnini'],
					'sisacutithnlalu' => $result1['sisacutithnlalu'],
					'lamacutithnini' => $result1['lamacutithnini'],
					'verifikatorid' => $res->atasanid,
					'verifikatornik' => $res->atasannik,
					'verifikatornama' => $res->atasannama,
					'verifikatordivisi' => '',
					'verifikatorjabatan' => $res->atasanjabatan,
					'verifikatorlokasi' => '',
					'verifikatoremail' => $res->email,
					'approvalid' => $res2->atasanid,
					'approvalnik' => $res2->atasannik,
					'approvalnama' => $res2->atasannama,
					'approvaldivisi' => '',
					'approvaljabatan' => $res2->atasanjabatan,
					'approvallokasi' => '',
					'approvalemail' => $res2->email,
				);
			}
		}
		return $result2;
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
	function getVerifikatorCuti($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			select p.pegawaiid, p.nik, p.namadepan nama,
				vj.levelid, l.level, l.gol,
				pa1.pegawaiid atasanid, pa1.nik atasannik, pa1.namadepan atasannama, vj1.satkerid atasansatkerid, pa1.emailkantor email,
				vj1.levelid atasanlevelid, l1.level atasanlevel, l1.gol atasangol, j1.jabatan atasanjabatan
			from pegawai p
			left join vwjabatanterakhir vj on p.pegawaiid = vj.pegawaiid
			left join level l on vj.levelid = l.levelid
			left join pegawai pa1 on pa1.pegawaiid = (
				case when p.pegawaiid = public.fnsatkerlevel2(vj.satkerid) then
						CASE WHEN vj.satkerid = '010802' THEN '000000000726' ELSE public.fnsatkerlevel2(substring(vj.satkerid,1,length(vj.satkerid)-2)) END
					else public.fnsatkerlevel2(vj.satkerid)
				end
			)
			left join vwjabatanterakhir vj1 on vj1.pegawaiid = pa1.pegawaiid
			LEFT JOIN jabatan j1 ON vj1.jabatanid = j1.jabatanid
			left join level l1 on vj1.levelid = l1.levelid
			where p.pegawaiid = ?
		", array($pegawaiid));
		$this->db->close();
		return $q->first_row();
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
	function updStatusCutiKosong($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE eservices.pengajuancuti SET status = '" . $params['v_status'] . "' WHERE pengajuanid = '" . $params['v_pengajuanid'] . "'
		", array($params));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	// budget from SAP
	function getBudgetFromLocalDb($params)
	{
		$this->load->database();
		$query = "SELECT a.pengajuanid,b.satkerid,c.id AS tid,c.nama AS nticket,c.avail AS ticket,
                d.id AS aid,d.nama AS naccomodation,d.avail AS accomodation
                --CASE WHEN f.jenisformid = '1' THEN
                --	CAST(c.avail AS INT) - ((CAST(e.hotelprice AS INT)*(f.lama-1))+((CAST(e.uangsaku AS INT)*(f.lama))+(CAST(e.uangmakan AS INT)*(f.lama)))+CAST(e.transport AS INT))
                --ELSE NULL
                --END total
                FROM pjdinas.pengajuandinas a
                LEFT JOIN vwjabatanterakhir b ON a.pegawaiid = b.pegawaiid
                LEFT JOIN pjdinas.kodesapbudget c ON (SELECT codeid FROM pjdinas.kodesapbudget WHERE satker LIKE CONCAT('%',(SELECT pjdinas.fnsatkerbudget(b.satkerid)),'%')
                	AND CAST(periode AS INT) = CAST(date_part('year', CURRENT_DATE) AS INT)
                	AND nama LIKE '%Ticket%') = c.codeid
                LEFT JOIN pjdinas.kodesapbudget d ON (SELECT codeid FROM pjdinas.kodesapbudget WHERE satker LIKE CONCAT('%',(SELECT pjdinas.fnsatkerbudget(b.satkerid)),'%')
                	AND CAST(periode AS INT) = CAST(date_part('year', CURRENT_DATE) AS INT)
                	AND nama LIKE '%Accom%') = d.codeid
                LEFT JOIN pjdinas.rincianbiaya e ON b.levelid = e.levelid
                LEFT JOIN pjdinas.detailpengajuandinas f ON a.pengajuanid = f.pengajuanid
				WHERE a.pengajuanid = '" . $params['v_pengajuanid'] . "' ";
		$q = $this->db->query("SELECT a.* FROM (" . $query . ") a", $params);
		$this->db->close();
		return $q->result_array();
	}
	function addBudgetFromSap($params_budget)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT pjdinas.sp_addbudgetpengajuan(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
		", $params_budget);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function uptBudget($params_budget)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE pjdinas.kodesapbudget SET avail = (cast(avail as int) + '" . $params_budget['v_grandacco'] . "')
			WHERE id = '" . $params_budget['v_aid'] . "' and periode = '" . $params_budget['v_periode']  . "';

			UPDATE pjdinas.kodesapbudget SET avail = (cast(avail as int) + '" . $params_budget['v_grandtiket'] . "')
			WHERE id = '" . $params_budget['v_tid'] . "' and periode = '" . $params_budget['v_periode']  . "';
		", array($params_budget));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// function uptBudget($params_budget)
	// {
	// 	$this->load->database();
	// 	$this->db->trans_start();

	// $query = '';
	// if (!empty($params_budget['v_sumt']) && !empty($params_budget['v_suma'])) {
	// 	$query = " UPDATE pjdinas.kodesapbudget SET avail = '" . $params_budget['v_suma'] . "' WHERE id = '" . $params_budget['v_aid'] . "';
	// 			UPDATE pjdinas.kodesapbudget SET avail = '" . $params_budget['v_sumt'] . "' WHERE id = '" . $params_budget['v_tid'] . "';
	// 			";
	// } elseif (!empty($params_budget['v_suma'])) {
	// 	$query = " UPDATE pjdinas.kodesapbudget SET avail = '" . $params_budget['v_suma'] . "' WHERE id = '" . $params_budget['v_aid'] . "' ";
	// }
	// 	$q = $this->db->query(" " . $query . " ");
	// 	$this->db->trans_complete();
	// 	$this->db->close();
	// 	return $this->db->trans_status();
	// }

	function resetrincianbiaya($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
		UPDATE pjdinas.kodesapbudget SET avail = (cast(avail as int) + '" . $params['v_ticket'] . "')
		WHERE id = '" . $params['v_tid'] . "' and periode = '" . $params['v_periode']  . "';

		UPDATE pjdinas.kodesapbudget SET avail = (cast(avail as int) + '" . $params['v_totalacco'] . "')
		WHERE id = '" . $params['v_aid'] . "' and periode = '" . $params['v_periode']  . "';

		UPDATE pjdinas.historybiayapengajuan SET
		uangsaku ='0',
		biayatiket = '0',
		transport = '0',
		hotelprice = '0',
		lainlain = '0',
		total = '0',
		accomodation = '0',
		aktif = 'Reset'
		WHERE pengajuanid = '" . $params['v_pengajuanid'] . "';

		", array($params));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function addrincianbiaya($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
		UPDATE pjdinas.historybiayapengajuan SET
		uangsaku = '" . $params['v_uangsaku'] . "',
		biayatiket = '" . $params['v_ticket'] . "',
		transport = '" . $params['v_transport'] . "',
		hotelprice = '" . $params['v_hotel'] . "',
		lainlain = '" . $params['v_lainlain'] . "',
		aktif = 'Edit'
		WHERE pengajuanid = '" . $params['v_pengajuanid'] . "';

		", array($params));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function totalrincianbiaya($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
		UPDATE pjdinas.historybiayapengajuan SET total = '" . $params['v_total'] . "',accomodation =  '" . $params['v_totalacco'] . "' ,aktif = 'Y' WHERE pengajuanid = '" . $params['v_pengajuanid'] . "';

		UPDATE pjdinas.kodesapbudget SET avail = (cast(avail as int) - '" . $params['v_ticket'] . "')
		WHERE id = '" . $params['v_tid'] . "' and periode = '" . $params['v_periode']  . "';

		UPDATE pjdinas.kodesapbudget SET avail = (cast(avail as int) - '" . $params['v_totalacco'] . "')
		WHERE id = '" . $params['v_aid'] . "' and periode = '" . $params['v_periode']  . "';
		", array($params));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function directtotalrincianbiaya($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
		UPDATE pjdinas.historybiayapengajuan SET aktif = 'Y' WHERE pengajuanid = '" . $params['v_pengajuanid'] . "';
		", array($params));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	// update reject budget
	function uptRejectBudget($params_budget)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
				SELECT pjdinas.sp_updpengajuandinasbudget(?,?,?,?,?)
			", $params_budget);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	// end update reject budget

	// update reject budget
	function updStatusKelebihan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
				SELECT pjdinas.sp_updrincianbiayapengajuan(?,?)
			", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	// end update reject budget

	function updSharingBudget($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT pjdinas.sp_updsharingbudget(?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function cancelSharingBudget($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE pjdinas.historybiayapengajuan SET hotelprice = '" . $params['v_hotelprice'] . "', total = '" . $params['v_total'] . "', sharingbudget = '" . '0' . "', idsharing = '" . '0' . "', idsharingpegawai = '" . null . "',accomodation = '" . $params['v_total'] . "' WHERE pengajuanid = '" . $params['v_pengajuanid'] . "';

			UPDATE pjdinas.historybiayapengajuan SET sharingbudget = '" . '0' . "', idsharing = '" . '0' . "', idsharingpegawai = '" . null . "' WHERE pengajuanid = '" . $params['v_idsharing'] . "';

			UPDATE pjdinas.kodesapbudget SET avail = '" .  $params['v_totalbudget'] . "' WHERE id = '" . $params['v_idacco'] . "' and periode = '" . $params['v_periode'] . "';
		", array($params));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
