<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_cuti extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getListCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('eservices.sp_getlistcutipeg', $params, false);
		return $mresult;
	}
	
	function getListCetakCuti($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT a.pengajuanid,f.nik,f.namadepan,a.tglpermohonan,b.tglmulai,b.tglselesai,b.lama,e.status,d1.jeniscuti,
			CASE WHEN d1.jeniscutiid = '3' THEN d.detailjeniscuti ELSE b.alasancuti END AS alasancuti, g.namadepan as hrd
			FROM eservices.pengajuancuti a
			LEFT JOIN eservices.detailpengajuancuti b ON a.pengajuanid = b.pengajuanid
			LEFT JOIN eservices.detailjeniscuti d ON b.detailjeniscutiid = d.detailjeniscutiid
            LEFT JOIN eservices.jeniscuti d1 ON b.jeniscutiid = d1.jeniscutiid
			LEFT JOIN riwayatjabatan c ON a.pegawaiid = c.pegawaiid
			LEFT JOIN eservices.statusverifikasi e ON a.status = e.statusid
			LEFT JOIN pegawai f ON a.pegawaiid = f.pegawaiid
			LEFT JOIN pegawai g ON a.hrd = g.pegawaiid
			WHERE b.tglmulai BETWEEN TO_DATE('" . $params['v_mulai'] . "','DD/MM/YYYY') AND TO_DATE('" . $params['v_selesai'] . "','DD/MM/YYYY')
			AND c.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' AND e.statusid LIKE '" . $params['v_nstatus'] . "' || '%'
			ORDER BY a.nourut
		", array($params));
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
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

	function getDetailPengajuanCutiHidden($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getdetailpengajuancutiHidden', array($pengajuanid));
		return $mresult['data'];
	}

	function updStatusCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$this->db->query("
			SELECT eservices.sp_updstatusverifikasi(?,?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function setLooping($param)
	{
		$this->load->database();

		$pegawai = $this->db->query("
					SELECT a.pegawaiid 
					FROM pegawai a
					INNER JOIN riwayatjabatan b on b.pegawaiid = a.pegawaiid
					WHERE b.tglselesai IS NULL
					AND a.pegawaiid = '" . $param . "'
					ORDER BY a.pegawaiid;
					");

		$pegawaiid = $pegawai->result_array();

		foreach ($pegawaiid as $pi) {
			$idpegawai = $pi['pegawaiid'];

			$q = $this->db->query("
				SELECT 	ROW_NUMBER() OVER(PARTITION BY a.pegawaiid ORDER BY a.tglmulai) rn ,
						a.pegawaiid ,
						a.tglmulai , 
						a.tglselesai , 
						a.lama ,
						a.jeniscutiid , 
				b.jatahawal ,
				DATE_PART('MONTH', a.tglmulai) BlnAwal , 
				DATE_PART('MONTH', a.tglselesai) BlnAkhir ,
				b.saldo ,
				b.sisacutithnlalu
				FROM eservices.vwcuti a
				INNER JOIN eservices.historysaldocuti b ON b.pegawaiid = a.pegawaiid AND b.tahun = a.tahun
				WHERE a.status IN('7','9','10','11','12','13','15') 
				AND		a.jeniscutiid IN ('1','6')
				AND 	a.tahun = DATE_PART('YEAR',NOW())
				AND 	a.pegawaiid = ?
				ORDER BY rn;
				", $idpegawai);


			$qu = $this->db->query("
				SELECT 	a.saldo ,
				a.sisacutithnlalu
				FROM eservices.historysaldocuti a
				WHERE	a.tahun = DATE_PART('YEAR',NOW())
				AND 	a.pegawaiid = ?;
				", $idpegawai);
			$this->db->close();


			$params = $q->result_array();
			$cekLY = $qu->result_array();
			$rnlast = !empty(end($params)['rn']) ? end($params)['rn'] : null;
			$saldoCY = null;
			$saldoLY = null;

			if (!empty($params)) {
				$jatahAwal = $params[0]['saldo'];
				$saldoCY = $params[0]['saldo'];
				$saldoLY = $params[0]['sisacutithnlalu'];
			} else {
				$jatahAwal = !empty($cekLY[0]['saldo']) ? $cekLY[0]['saldo'] : 0;
				$saldoCY = !empty($cekLY[0]['saldo']) ? $cekLY[0]['saldo'] : 0;
				$saldoLY = !empty($cekLY[0]['sisacutithnlalu']) ? $cekLY[0]['sisacutithnlalu'] : 0;
			}

			for ($i = 0; $i < $rnlast; $i++) {
				$blnAwal = $params[$i]['blnawal'];
				$jeniscuti = $params[$i]['jeniscutiid'];
				$lamacuti = $params[$i]['lama'];

				if (in_array($blnAwal[0], array('1', '2', '3')) && $saldoLY > 0) {
					if ($jeniscuti == '6') {
						$saldoCY = $saldoCY - $lamacuti;
						$saldoLY = $saldoLY;
					} else if ($saldoLY - $lamacuti < 0) {
						$saldoCY = ($saldoCY + $saldoLY) - $lamacuti;
						$saldoLY = 0;
					} else {
						$saldoCY = $saldoCY;
						$saldoLY = $saldoLY - $lamacuti;
					}
				} else if ($jeniscuti == '6') {
					$saldoCY = $saldoCY - $lamacuti;
					$saldoLY = $saldoLY;
				} else {
					$saldoCY = $saldoCY - $lamacuti;
					$saldoLY = 0;
				}
			}

			$arr = array(
				'pegawaiid' => $idpegawai,
				'tahun' => date("Y"),
				'jatahAwal' => $jatahAwal,
				'saldoCY' => $saldoCY,
				'saldoLY' => $saldoLY,
			);

			$this->db->query("
			DELETE FROM eservices.stgsisacuti
			WHERE pegawaiid = '" . $arr['pegawaiid'] . "' 
			 AND tahun  = " . $arr['tahun'] . " ;

			INSERT INTO eservices.stgsisacuti
			SELECT  '" . $arr['pegawaiid'] . "' ,
			" . $arr['tahun'] . " ,
			" . $arr['jatahAwal'] . " ,
			" . $arr['saldoCY'] . " ,
			" . $arr['saldoLY'] . " ;
			");
		}
		return $this->db->trans_status();
	}

	function updSisaCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE eservices.stgsisacuti
			SET saldocy = 
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
}
