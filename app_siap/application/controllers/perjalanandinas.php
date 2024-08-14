<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class perjalanandinas extends SIAP_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_perjalanandinas');
		$this->load->model('m_pegawai');
	}
	function getListCuti()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pegawaiid' => null,
			'v_status' => null,
			'v_mulai' => ifunsetempty($_POST, 'tglmulai', null),
			'v_selesai' => ifunsetempty($_POST, 'tglselesai', null),
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_keyword' => ifunsetempty($_POST, 'keyword', ''),
			'v_nstatus' => ifunsetempty($_POST, 'statusid', null),
			'v_usergroupid' => $this->session->userdata('aksesid_siap'),
			'v_lokasiid' => $this->session->userdata('lokasiid'),
			'v_jenisformid' => null,
			'v_njenisformid' => ifunsetempty($_POST, 'jenisformid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);

		$mresult = $this->m_perjalanandinas->getListCuti($params);
		echo json_encode($mresult);
	}
	function getDetailCutiPegawai()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pegawaiid' => ifunsetemptybase64($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetemptybase64($_POST, 'nourut', null),
			'v_tahun' => ifunsetemptybase64($_POST, 'tahun', null),
		);
		$mresult = $this->m_perjalanandinas->getCutiById($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function getDetailPengajuanCuti()
	{
		$this->m_pegawai->addlogs();
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$mresult = $this->m_perjalanandinas->getDetailPengajuanCuti($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function getRincianBiaya()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$mresult = $this->m_perjalanandinas->getRincianBiaya($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function getRincianBiayaDinas()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$mresult = $this->m_perjalanandinas->getRincianBiayaDinas($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function getRincianBiayaPertanggungan()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$mresult = $this->m_perjalanandinas->getRincianBiayaPertanggungan($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function getDetailPengajuanCutiHidden()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$mresult = $this->m_cuti->getDetailPengajuanCutiHidden($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function resetrincianbiaya()
	{
		$this->m_pegawai->addlogs();
		$total = ifunsetempty($_POST, 'totalacco', 0);
		$params = array(
			'v_pengajuanid' 	=> ifunsetempty($_POST, 'pengajuanid', null),
			'v_periode'			=> ifunsetempty($_POST, 'periode', 0),
			'v_aid'				=> ifunsetempty($_POST, 'aid', null),
			'v_tid'				=> ifunsetempty($_POST, 'tid', null),
			'v_ticket' 			=> ifunsetempty($_POST, 'tiket', 0),
			'v_totalacco'		=> (string) $total,

		);
		$mresult = $this->m_perjalanandinas->resetrincianbiaya($params);

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}

	function addrincianbiaya()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' 	=> ifunsetempty($_POST, 'pengajuanid', null),
			'v_uangsaku' 		=> ifunsetempty($_POST, 'uangsaku', 0),
			'v_ticket' 			=> ifunsetempty($_POST, 'tiket', 0),
			'v_transport' 		=> ifunsetempty($_POST, 'transport', 0),
			'v_hotel' 			=> ifunsetempty($_POST, 'hotel', 0),
			'v_lainlain' 		=> ifunsetempty($_POST, 'lainlain', 0),
			'v_periode'			=> ifunsetempty($_POST, 'periode', 0),
		);
		$mresult = $this->m_perjalanandinas->addrincianbiaya($params);

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}

	function totalrincianbiaya()
	{
		$this->m_pegawai->addlogs();
		$total = ifunsetempty($_POST, 'total', 0);
		$totalacco = ifunsetempty($_POST, 'totalacco', 0);
		$aktif = ifunsetempty($_POST, 'aktif', null);
		if ($aktif == 'Edit') {
			$params = array(
				'v_pengajuanid' 	=> ifunsetempty($_POST, 'pengajuanid', null),
				'v_periode'			=> ifunsetempty($_POST, 'periode', 0),
				'v_ticket' 			=> ifunsetempty($_POST, 'tiket', 0),
				'v_uangsaku' 		=> ifunsetempty($_POST, 'uangsaku', 0),
				'v_transport' 		=> ifunsetempty($_POST, 'transport', 0),
				'v_hotelprice' 		=> ifunsetempty($_POST, 'hotelprice', 0),
				'v_lainlain' 		=> ifunsetempty($_POST, 'lainlain', 0),
				'v_total' 			=> (string) $total,
				'v_totalacco' 		=> (string) $totalacco,
				'v_aid'				=> ifunsetempty($_POST, 'aid', null),
				'v_tid'				=> ifunsetempty($_POST, 'tid', null),
				'v_totalhistory'	=> ifunsetempty($_POST, 'totalhistory', 0),

			);
			$mresult = $this->m_perjalanandinas->totalrincianbiaya($params);
		} else {
			$params = array(
				'v_pengajuanid' 	=> ifunsetempty($_POST, 'pengajuanid', null),
				'v_periode'			=> ifunsetempty($_POST, 'periode', 0),
				'v_aid'				=> ifunsetempty($_POST, 'aid', null),
				'v_tid'				=> ifunsetempty($_POST, 'tid', null),

			);
			$mresult = $this->m_perjalanandinas->directtotalrincianbiaya($params);
		}


		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}



	function kelebihanDikembalikan()
	{
		$this->m_pegawai->addlogs();
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$kelebihan = ifunsetempty($_POST, 'kelebihan', '0');
		$active = ifunsetempty($_POST, 'active', null);
		if (($active == false) && ($kelebihan > 0)) {
			$newkelebihan = 't'; //kelebihan yang harus dikembalikan
		} else {
			$newkelebihan = 'f'; //tidak ada kelebihan yang harus dikembalikan
		}
		$params = array(
			'v_pengajuanid' => (string) $pengajuanid,
			'v_active' => (string) $newkelebihan,
		);
		$mresult = $this->m_perjalanandinas->updStatusKelebihan($params);
	}
	function approvePerjalanan()
	{
		$this->m_pegawai->addlogs();
		$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		$pengajuanid = ifunsetemptybase64($_POST, 'pengajuanid', null);
		$nik = ifunsetempty($_POST, 'nik', null);
		$nama = ifunsetempty($_POST, 'nama', null);
		$tglpermohonan = ifunsetempty($_POST, 'tglpermohonan', null);
		$email = ifunsetempty($_POST, 'email', null);
		$statusid = ifunsetempty($_POST, 'statusid', null);
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$kelebihan = ifunsetempty($_POST, 'kelebihan', '0');
		$jenisformid = ifunsetempty($_POST, 'jenisformid', '0');
		$relasiid = ifunsetempty($_POST, 'relasiid', null);
		// add budget
		$grandtotal = ifunsetempty($_POST, 'grandtotal', '0');
		$normalisasi = ifunsetempty($_POST, 'normalisasi', '0');
		$aid = ifunsetempty($_POST, 'aid', null);
		$tid = ifunsetempty($_POST, 'tid', null);
		$accomodation = ifunsetempty($_POST, 'accomodation', '0');
		$ticket = ifunsetempty($_POST, 'ticket', '0');
		$sum_a = ifunsetempty($_POST, 'sum_a', '0');
		$sum_t = ifunsetempty($_POST, 'sum_t', '0');
		$kelebihan = ifunsetempty($_POST, 'kelebihan', '0');
		$kekurangan = ifunsetempty($_POST, 'kekurangan', '0');
		$grandacco = ifunsetempty($_POST, 'grandacco', '0');
		$grandtiket = ifunsetempty($_POST, 'grandtiket', '0');
		$periode = ifunsetempty($_POST, 'periode', '0');
		$sharingbudget = ifunsetempty($_POST, 'sharingbudget', '0');
		// end add
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		//get data from store
		$detailallcuti = array();
		$detailallcuti = json_decode(ifunsetempty($_POST, 'detailallcuti', null));
		//end
		$newstatusid = null;
		$newstatustext = '';
		// email
		$keterangan = '';
		$penerima = '';
		$atasanemail = '';

		// jika disetujui oleh GA
		if (($statusid == '5') || ($statusid == '7') || ($statusid == '9')) {
			if ($statusid == '5') {
				$newstatusid = '7';
				$newstatustext = 'Disetujui Admin GA';
			} else if ($statusid == '7') {
				$newstatusid = '9';
				$newstatustext = 'Disetujui Ass.Man GA';
			} else {
				$newstatusid = '11';
				$newstatustext = 'Disetujui Head GA';
			}
		}
		// jika disetujui oleh FA
		elseif (($statusid == '11') || ($statusid == '13')) {
			if ($statusid == '11') {
				$newstatusid = '13';
				$newstatustext = 'Disetujui Admin FA';
			} else {
				$newstatusid = '15';
				$newstatustext = 'Disetujui Head FA';
			}
		}

		$params_budget = array(
			'v_pengajuanid' 	=> $pengajuanid,
			'v_pegawaiid' 		=> $pegawaiid,
			'v_nourut'    		=> $nourut,
			'v_grandtotal'		=> $grandtotal,
			'v_normalisasi' 	=> $normalisasi,
			'v_aid'    			=> $aid,
			'v_tid'    			=> $tid,
			'v_accomodation'	=> $accomodation,
			'v_ticket'      	=> $ticket,
			'v_suma' 			=> $sum_a,
			'v_sumt' 			=> $sum_t,
			'v_kelebihan' 		=> $kelebihan,
			'v_kekurangan' 		=> $kekurangan,
			'v_grandacco'		=> $grandacco,
			'v_grandtiket'		=> $grandtiket,
			'v_periode'			=> $periode,
		);
		// jika admin FA
		$pegawai = $this->session->userdata('userid');
		if ($pegawai == '139') {
			$addbudget = $this->m_perjalanandinas->addBudgetFromSap($params_budget);
			if ($jenisformid == '2' || $jenisformid == '4') {
				$mresult = $this->m_perjalanandinas->uptBudget($params_budget);
			}
		}

		// if ($kelebihan > 0) {
		// 	$newkelebihan = 't'; //kelebihan yang harus dikembalikan
		// } else {
		// 	$newkelebihan = 'f'; //tidak ada kelebihan yang harus dikembalikan
		// }
		// $params = array(
		// 	'v_pengajuanid' => (string) $pengajuanid,
		// 	'v_active' => (string) $newkelebihan,
		// );
		// $mresult = $this->m_perjalanandinas->updStatusKelebihan($params);

		if ($pegawai == '93') {
			for ($x = 0; $x <= 0; $x++) {
				$desc = array(
					'nik' => $this->session->userdata('nik'),
					'nama' => $this->session->userdata('nama'),
					'desctription' => 'Di Setujui Pengajuan Dinas pada tanggal ' . $tglpermohonan . ' ' . $newstatustext,
				);
				if ($x == '0') {
					$penerima = $pegawaiid;
					$atasanemail = $email;
					if ($newstatusid == '15') {
						$keterangan = 'Disetujui Head FA';
					}
				}
				$paramsnotif = array(
					'v_jenisnotif' => $keterangan,
					'v_description' => json_encode($desc),
					'v_penerima' => $penerima,
					'v_useridfrom' => $this->session->userdata('userid'),
					'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
					'v_pengirim' => $this->session->userdata('pegawaiid'),
					'v_modulid' => '3',
					'v_modul' => null,
					// 'v_pengajuanid' => $pengajuanid,
				);
				$this->tp_notification->addNotif($paramsnotif);
				// email approve
				// $this->sendMail($nik, $atasanemail, $nama, $keterangan);
			}
		} else {
			for ($x = 0; $x <= 1; $x++) {
				// Notifikasi saat approve admin GA
				$desc = array(
					'nik' => $this->session->userdata('nik'),
					'nama' => $this->session->userdata('nama'),
					'desctription' => 'Di Setujui Pengajuan Dinas pada tanggal ' . $tglpermohonan,
				);
				if ($x == '0') {
					if ($newstatusid == '7') {
						$penerima = '000000000060'; //Diisi dengan id Ass.Man GA
						$atasanemail = 'hr.adm2@electronic-city.co.id';
						$keterangan = 'Disetujui Admin GA';
					} elseif ($newstatusid == '9') {
						$penerima = '000000000080'; //Diisi dengan id Head FA
						$atasanemail = 'hr.adm2@electronic-city.co.id';
						$keterangan = 'Disetujui Ass.Man GA';
					} elseif ($newstatusid == '11') {
						$penerima = '000000000613'; //Diisi dengan id Admin FA
						$atasanemail = 'hr.adm2@electronic-city.co.id';
						$keterangan = 'Disetujui Head GA';
					} elseif ($newstatusid == '13') {
						$penerima = '000000000205'; //Diisi dengan id Head FA
						$atasanemail = 'hr.adm2@electronic-city.co.id';
						$keterangan = 'Disetujui Admin FA';
					}
				} else if ($x == '1') {
					$penerima = $pegawaiid;
					$atasanemail = $email;
					if ($newstatusid == '7') {
						$keterangan = 'Disetujui Admin GA';
					} elseif ($newstatusid == '9') {
						$keterangan = 'Disetujui Ass.Man GA';
					} elseif ($newstatusid == '11') {
						$keterangan = 'Disetujui Head GA';
					} elseif ($newstatusid == '13') {
						$keterangan = 'Disetujui Admin FA';
					}
				}

				$paramsnotif = array(
					'v_jenisnotif' => $keterangan,
					'v_description' => json_encode($desc),
					'v_penerima' => $penerima,
					'v_useridfrom' => $this->session->userdata('userid'),
					'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
					'v_pengirim' => $this->session->userdata('pegawaiid'),
					'v_modulid' => '3',
					'v_modul' => null,
					// 'v_pengajuanid' => $pengajuanid,
				);

				$this->tp_notification->addNotif($paramsnotif);
				// email approve
				// $this->sendMail($nik, $atasanemail, $nama, $keterangan);
			}
		}
		// END EDITING TAMA

		if ($jenisformid == '1' || $jenisformid == '3') {
			$relasiid = null;
			$pengajuanid = null;
		} else {
			$relasiid = ifunsetempty($_POST, 'relasiid', null);
			$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		}

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_verifikasinotes' => null,
			'v_admin' => $this->session->userdata('pegawaiid'),
			'v_tgladmga' => ifunsetempty($_POST, 'tgladmga', null),
			'v_tglheadga' => ifunsetempty($_POST, 'tglheadga', null),
			'v_tgladmfa' => ifunsetempty($_POST, 'tgladmfa', null),
			'v_tglheadfa' => ifunsetempty($_POST, 'tglheadfa', null),
			'v_pengajuanid' => $relasiid,
			'v_idpertanggungjawaban' => $pengajuanid,
			'v_tglassmanga' => ifunsetempty($_POST, 'tglassmanga', null),
		);
		$mresult = $this->m_perjalanandinas->updStatusCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil diupdate');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diupdate');
		}
		echo json_encode($result);
	}

	function rejectPerjalanan()
	{
		$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		$nik = ifunsetempty($_POST, 'nik', null);
		$nama = ifunsetempty($_POST, 'nama', null);
		$email = ifunsetempty($_POST, 'email', null);
		$tglpermohonan = ifunsetempty($_POST, 'tglpermohonan', null);
		$statusid = ifunsetempty($_POST, 'statusid', null);
		$newstatusid = null;
		$newstatustext = '';
		// budget
		$actual = ifunsetempty($_POST, 'actual', '0');
		$actualinvit = ifunsetempty($_POST, 'actualinvit', '0');
		$actualticket = ifunsetempty($_POST, 'actualticket', '0');
		$aid = ifunsetempty($_POST, 'aid', '0');
		$tid = ifunsetempty($_POST, 'tid', '0');
		$periode = ifunsetempty($_POST, 'periode', '0');
		$jenisperjalananid = ifunsetempty($_POST, 'jenisperjalananid', '0');
		$southeastasia = ifunsetempty($_POST, 'southeastasia', '0');
		$non_southeastasia = ifunsetempty($_POST, 'non_southeastasia', '0');
		$japan = ifunsetempty($_POST, 'japan', '0');
		$eropa_usa_dubai = ifunsetempty($_POST, 'eropa_usa_dubai', '0');
		$no_southeastasia = ifunsetempty($_POST, 'no_southeastasia', '0');
		$no_non_southeastasia = ifunsetempty($_POST, 'no_non_southeastasia', '0');
		$no_japan = ifunsetempty($_POST, 'no_japan', '0');
		$no_eropa_usa_dubai = ifunsetempty($_POST, 'no_eropa_usa_dubai', '0');
		$kotaid = ifunsetempty($_POST, 'kotaid', '0');
		$jenisformid = ifunsetempty($_POST, 'jenisformid', '0');
		$alasan = ifunsetempty($_POST, 'alasan', null);
		$keterangan = '';
		$penerima = '';
		$atasanemail = '';

		// update reject budget

		if ($jenisperjalananid == '1') {
			if (($jenisformid) == '1') {
				$params_budget = array(
					'v_avail' => (string) $actual,
					'v_tavail' => (string) $actualticket,
					'v_aid' => (string) $aid,
					'v_tid' => (string) $tid,
					'v_periode' => (string) $periode,
				);
				$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
			}
		} elseif ($jenisperjalananid == '2') {
			if (($jenisformid) == '1') {
				$params_budget = array(
					'v_avail' => (string) $actualinvit,
					'v_tavail' => (string) $actualticket,
					'v_aid' => (string) $aid,
					'v_tid' => (string) $tid,
					'v_periode' => (string) $periode,
				);
				$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
			}
		}
		// Update Perjalanan Dinas Luar Negeri 02/04/2020
		if ($jenisperjalananid == '1') {
			if ($kotaid == '901') {
				if (($jenisformid) == '3') {
					$params_budget = array(
						'v_avail' => (string) $no_southeastasia,
						'v_tavail' => (string) $actualticket,
						'v_aid' => (string) $aid,
						'v_tid' => (string) $tid,
						'v_periode' => (string) $periode,
					);
					$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
				}
			} elseif ($kotaid == '902') {
				if (($jenisformid) == '3') {
					$params_budget = array(
						'v_avail' => (string) $no_non_southeastasia,
						'v_tavail' => (string) $actualticket,
						'v_aid' => (string) $aid,
						'v_tid' => (string) $tid,
						'v_periode' => (string) $periode,
					);
					$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
				}
			} elseif ($kotaid == '903') {
				if (($jenisformid) == '3') {
					$params_budget = array(
						'v_avail' => (string) $no_japan,
						'v_tavail' => (string) $actualticket,
						'v_aid' => (string) $aid,
						'v_tid' => (string) $tid,
						'v_periode' => (string) $periode,
					);
					$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
				}
			} elseif ($kotaid == '904') {
				if (($jenisformid) == '3') {
					$params_budget = array(
						'v_avail' => (string) $no_eropa_usa_dubai,
						'v_tavail' => (string) $actualticket,
						'v_aid' => (string) $aid,
						'v_tid' => (string) $tid,
						'v_periode' => (string) $periode,
					);
					$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
				}
			}
		} elseif ($jenisperjalananid == '2') {
			if ($kotaid == '901') {
				if (($jenisformid) == '3') {
					$params_budget = array(
						'v_avail' => (string) $southeastasia,
						'v_tavail' => (string) $actualticket,
						'v_aid' => (string) $aid,
						'v_tid' => (string) $tid,
						'v_periode' => (string) $periode,
					);
					$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
				}
			} elseif ($kotaid == '902') {
				if (($jenisformid) == '3') {
					$params_budget = array(
						'v_avail' => (string) $non_southeastasia,
						'v_tavail' => (string) $actualticket,
						'v_aid' => (string) $aid,
						'v_tid' => (string) $tid,
						'v_periode' => (string) $periode,
					);
					$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
				}
			} elseif ($kotaid == '903') {
				if (($jenisformid) == '3') {
					$params_budget = array(
						'v_avail' => (string) $japan,
						'v_tavail' => (string) $actualticket,
						'v_aid' => (string) $aid,
						'v_tid' => (string) $tid,
						'v_periode' => (string) $periode,
					);
					$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
				}
			} elseif ($kotaid == '904') {
				if (($jenisformid) == '3') {
					$params_budget = array(
						'v_avail' => (string) $eropa_usa_dubai,
						'v_tavail' => (string) $actualticket,
						'v_aid' => (string) $aid,
						'v_tid' => (string) $tid,
						'v_periode' => (string) $periode,
					);
					$uptbudget = $this->m_perjalanandinas->uptRejectBudget($params_budget);
				}
			}
		}

		// Update Perjalanan Dinas Luar Negeri 02/04/2020
		// end update reject budget

		// jika ditolak oleh GA
		if (($statusid == '5') || ($statusid == '7') || ($statusid == '9')) {
			if ($statusid == '5') {
				$newstatusid = '8';
				$newstatustext = 'Ditolak Admin GA';
			} else if ($statusid == '7') {
				$newstatusid = '10';
				$newstatustext = 'Ditolak Ass.Man GA';
			} else if ($statusid == '9') {
				$newstatusid = '12';
				$newstatustext = 'Ditolak Head GA';
			}
		}
		// jika ditolak oleh FA
		if (($statusid == '11') || ($statusid == '13')) {
			if ($statusid == '11') {
				$newstatusid = '14';
				$newstatustext = 'Ditolak Admin FA';
			} else {
				$newstatusid = '16';
				$newstatustext = 'Ditolak Head FA';
			}
		}

		if ($pegawaiid == '93') {
			for ($x = 0; $x <= 0; $x++) {
				$desc = array(
					'nik' => $this->session->userdata('nik'),
					'nama' => $this->session->userdata('nama'),
					'desctription' => 'Di Tolak Pengajuan Dinas pada tanggal ' . $tglpermohonan . ' ' . $newstatustext,
				);
				if ($x == '0') {
					$penerima = $pegawaiid;
					$atasanemail = $email;
					if ($newstatusid == '15') {
						$keterangan = 'Ditolak Head FA';
					}
				}
				$paramsnotif = array(
					'v_jenisnotif' => $keterangan,
					'v_description' => json_encode($desc),
					'v_penerima' => $penerima,
					'v_useridfrom' => $this->session->userdata('userid'),
					'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
					'v_pengirim' => $this->session->userdata('pegawaiid'),
					'v_modulid' => '3',
					'v_modul' => null,
					// 'v_pengajuanid' => $pengajuanid,
				);
				$this->tp_notification->addNotif($paramsnotif);
				// email approve
				// $this->sendMail($nik, $atasanemail, $nama, $keterangan);
			}
		} else {

			// Notifikasi saat approve admin GA
			$desc = array(
				'nik' => $this->session->userdata('nik'),
				'nama' => $this->session->userdata('nama'),
				'desctription' => 'Di Tolak Pengajuan Dinas pada tanggal ' . $tglpermohonan,
			);

			$penerima = $pegawaiid;
			$atasanemail = $email;
			if ($newstatusid == '8') {
				$keterangan = 'Ditolak Admin GA';
			} elseif ($newstatusid == '10') {
				$keterangan = 'Ditolak Ass.Man GA';
			} elseif ($newstatusid == '12') {
				$keterangan = 'Ditolak Head GA';
			} elseif ($newstatusid == '14') {
				$keterangan = 'Ditolak Admin FA';
			}

			$paramsnotif = array(
				'v_jenisnotif' => $keterangan,
				'v_description' => json_encode($desc),
				'v_penerima' => $penerima,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
				'v_pengirim' => $this->session->userdata('pegawaiid'),
				'v_modulid' => '3',
				'v_modul' => null,
				// 'v_pengajuanid' => $pengajuanid,
			);

			$this->tp_notification->addNotif($paramsnotif);
			// email approve
			// $this->sendMail($nik, $atasanemail, $nama, $keterangan);

		}

		if ($jenisformid == '1' || $jenisformid == '3') {
			$relasiid = null;
			$pengajuanid = null;
		} else {
			$relasiid = ifunsetempty($_POST, 'relasiid', null);
			$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		}
		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_verifikasinotes' => $alasan,
			'v_admin' => $this->session->userdata('pegawaiid'),
			'v_tgladmga' => ifunsetempty($_POST, 'tgladmga', null),
			'v_tglheadga' => ifunsetempty($_POST, 'tglheadga', null),
			'v_tgladmfa' => ifunsetempty($_POST, 'tgladmfa', null),
			'v_tglheadfa' => ifunsetempty($_POST, 'tglheadfa', null),
			'v_pengajuanid' => $relasiid,
			'v_idpertanggungjawaban' => null,
			'v_tglassmanga' => ifunsetempty($_POST, 'tglassmanga', null),
		);
		$mresult = $this->m_perjalanandinas->updDitolakStatusCuti($params);

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil diupdate');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diupdate');
		}
		echo json_encode($result);
	}
	public function sendMail($nik, $email, $nama, $newstatustext)
	{
		$tglpermohonan = date("d/m/Y");
		$this->load->library('email');	//Load email library
		// SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'mail.electronic-city.co.id',
			'smtp_port' => 25,
			'smtp_user' => 'hris-ec@electronic-city-internal.co.id',
			'smtp_pass' => 'L0nt0n9',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		if ($email == null) {
			// Tidak mengirim email apabila email id kosong
		} else {
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->to('khotama.brata@electronic-city.co.id');
			// $this->email->cc('deny.prabawa@electronic-city.co.id');
			$this->email->from('hris-ec@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Status Notifikasi</th><th>Tanggal Pengajuan</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>' . $newstatustext . '</td><td>' . $tglpermohonan . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Status Perjalanan Dinas HRIS');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}

	public function addPaidHoliday($fingerid, $tglmulai, $tglselesai, $alasancuti, $cutiid)
	{
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');

		$params = array(
			'USERID' => $fingerid,
			'STARTSPECDAY' => $tglmulai,
			'ENDSPECDAY' => $tglselesai,
			'DATEID' => $cutiid,
			'YUANYING' => $alasancuti,
			'DATE' => $tglpermohonan,
		);
		$this->db = $this->load->database('hrd', TRUE);
		$sql = "INSERT INTO USER_SPEDAY(USERID,STARTSPECDAY,ENDSPECDAY,DATEID,YUANYING,DATE) VALUES (?,?,?,?,?,?) ";
		$query = $this->db->query($sql, $params);
	}
	public function download()
	{
		$this->m_pegawai->addlogs();
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('eservices_upload_dok_path');

		if ($filename == '') {
			echo '<h2>Belum upload file</h2>';
		} else if (file_exists($path . $filename)) {
			$data = file_get_contents($path . $filename);
			force_download($filename, $data);
		} else {
			echo '<h2>Maaf, File hilang</h2>';
		}
	}
	public function getPegawaiByNIK()
	{
		$params = array(
			'nik' => ifunsetempty($_POST, 'nik', null),
			'tahun' => ifunsetempty($_POST, 'tahun', null),
		);

		$mresult = $this->m_cuti->getInfoPegawai($params);

		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	public function countLengthCuti()
	{
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$tglmulai = ifunsetempty($_POST, 'tglmulai', null);
		$tglselesai = ifunsetempty($_POST, 'tglselesai', null);

		$mresultHariLibur = $this->m_cuti->getHariLibur();

		$holidays = array();
		foreach ($mresultHariLibur as $key => $value) {
			$holidays[$key] = $value['tgl'];
		}

		$lama_cuti = getJumlahHariKerja($tglmulai, $tglselesai, $holidays);
		$result = array('success' => true, 'data' => $lama_cuti);
		echo json_encode($result);
	}
	public function crudCuti()
	{
		$this->m_pegawai->addlogs();
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$tahun = ifunsetempty($_POST, 'tahun', date("Y"));
		$nik = ifunsetempty($_POST, 'nik', null);
		$nama = ifunsetempty($_POST, 'nama', null);
		$sisacutithnini = ifunsetempty($_POST, 'sisacutithnini', null);
		$sisacutithnlalu = ifunsetempty($_POST, 'sisacutithnlalu', null);
		$tglpermohonan = ifunsetempty($_POST, 'tglpermohonan', null);
		$atasan1 = ifunsetempty($_POST, 'atasanid', null);
		$atasan2 = ifunsetempty($_POST, 'atasan2id', null);
		$pelimpahan = ifunsetempty($_POST, 'pelimpahanid', null);
		$hp = ifunsetempty($_POST, 'hp', null);
		$files = $_FILES['files'];
		$newfilesname = null;
		$file_ext = null;

		$detailallcuti = array();
		$detailallcuti = json_decode(ifunsetempty($_POST, 'detailallcuti', null));

		if ($files['error'] == 0) {
			$filesname_exp = explode('.', $files['name'], 2);
			$newfilesname = $filesname_exp[0] . '_' . time() . '.' . $filesname_exp[1];
		}

		if (is_uploaded_file($files['tmp_name'])) {
			$config['upload_path'] = config_item("eservices_upload_dok_path");
			$config['allowed_types'] = '*';
			$config['not_allowed_types'] = 'php|txt|exe';
			$config['max_size']	= '10000000000';
			$config['overwrite']  = TRUE;
			$config['file_name']  = $newfilesname;

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('files')) {
				$error = array('error' => $this->upload->display_errors());
				$output = array('success' => false, 'message' => $this->upload->display_errors('', ''));
				echo json_encode($output);
				return;
			} else {
				$data = array('upload_data' => $this->upload->data());
				$file_ext = $data['upload_data']['file_ext'];
				$newfilesname = $data['upload_data']['file_name'];
			}
		}

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_periode' => $tahun,
			'v_tglpermohonan' => $tglpermohonan,
			'v_atasan1' => $atasan1,
			'v_atasan2' => $atasan2,
			'v_pelimpahan' => $pelimpahan,
			'v_status' => '7',
			'v_verifikasinotes' => null,
			'v_files' => $newfilesname,
			'v_filestype' => $file_ext,
			'v_hp' => $hp,
		);

		$mpengajuan = $this->m_cuti->addPengajuanCuti($params);
		if (!empty($mpengajuan['pengajuanid'])) {
			$o = 0;
			foreach ($detailallcuti as $r) {
				$sendParams = array(
					'v_pengajuanid' => $mpengajuan['pengajuanid'],
					'v_jeniscutiid' => $r->jeniscutiid,
					'v_detailjeniscutiid' => $r->detailjeniscutiid,
					'v_tglmulai' => $r->tglmulai,
					'v_tglselesai' => $r->tglselesai,
					'v_lama' => $r->lama,
					'v_satuan' => 'HARI KERJA',
					'v_sisacuti' => $r->sisacuti,
					'v_alasan' => $r->alasancuti,
				);
				$mdetailpengajuan = $this->m_cuti->addDetailPengajuanCuti($sendParams);
				if ($mdetailpengajuan) $o++;
			}

			if ($o > 0) {
				$result = array('success' => true, 'message' => 'Data berhasil ditambah');
			} else {
				$result = array('success' => false, 'message' => 'Data gagal ditambah');
			}
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}

		echo json_encode($result);
	}
	function cetakdokumen()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' => ifunsetemptybase64($_GET, 'pengajuanid', null)
		);
		$mresult = $this->m_perjalanandinas->getListCetakForm($params);

		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "form_pertanggungan.docx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "form_pertanggungan.docx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	//cetak dokumen dalam negeri
	function cetakdokumenadvance_dn()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' => ifunsetemptybase64($_GET, 'pengajuanid', null),
			'v_jenisperjalananid' => ifunsetempty($_GET, 'jenisperjalananid', null)
		);
		$mresult = $this->m_perjalanandinas->getListCetakForm($params);
		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "form_advance_dn.docx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "form_advance.docx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	function cetakdokumenlpj_dn()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' => ifunsetemptybase64($_GET, 'pengajuanid', null),
			'v_jenisperjalananid' => ifunsetempty($_GET, 'jenisperjalananid', null)
		);
		$mresult = $this->m_perjalanandinas->getListCetakForm($params);
		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "form_pertanggungan_dn.docx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "form_pertanggungan.docx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	function cetakdokumenlpj_ln()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' => ifunsetemptybase64($_GET, 'pengajuanid', null),
			'v_jenisperjalananid' => ifunsetempty($_GET, 'jenisperjalananid', null)
		);
		$mresult = $this->m_perjalanandinas->getListCetakForm($params);
		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "form_pertanggungan_ln.docx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "form_pertanggungan.docx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	function cetakdokumenbatal()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' => ifunsetemptybase64($_GET, 'pengajuanid', null),
			'v_jenisperjalananid' => ifunsetempty($_GET, 'jenisperjalananid', null)
		);
		$mresult = $this->m_perjalanandinas->getListCetakForm($params);
		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "form_batal.docx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "form_batal.docx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	// function cetakdokumenlpjinvit_dn()
	// {
	// 	$params = array(
	// 		'v_pengajuanid' => ifunsetemptybase64($_GET, 'pengajuanid', null),
	// 		'v_jenisperjalananid' => ifunsetempty($_GET, 'jenisperjalananid', null)
	// 	);
	// 	$mresult = $this->m_perjalanandinas->getListCetakForm($params);
	// 	$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "form_pertanggungan_dn_invit.docx");
	// 	$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
	// 	$TBS->MergeField('header', array());
	// 	$TBS->MergeBlock('rec', $mresult['data']);
	// 	$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "form_pertanggungan_invit.docx");
	// 	$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
	// 	$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	// }
	//end cetak dokumen dalam negeri

	function updStatusCutiKosong()
	{
		$params = array(
			'v_pengajuanid' => ifunsetempty($_POST, 'pengajuanid', null),
			'v_status' 		=> ifunsetempty($_POST, 'statusid', null),
		);
		$mresult = $this->m_cuti->updStatusCutiKosong($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}

	function getBudgetFromSap()
	{
		$grandtotal = ifunsetempty($_POST, 'grandtotal', null);
		#Define Authentication
		$SOAP_AUTH = array(
			'login'    => 'BUDGET',
			'password' => 'budget-eci'
		);

		#Specify WSDL
		$WSDL = "http://10.140.4.25:8001/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zws_get_budget/600/zsi_get_budget/zbi_get_budget?sap-client=600";

		#Create Client Object, download and parse WSDL
		$client = new SoapClient($WSDL, $SOAP_AUTH);

		#Setup input parameters (SAP Likes to Capitalise the parameter names)
		$formid = ifunsetempty($_POST, 'jenisformid', null);
		$accomodation = ifunsetempty($_POST, 'accomodation', null);
		$ticket = ifunsetempty($_POST, 'ticket', null);

		$params = array(
			'PGjahr' => '2019	',
			'POrderGroup' => 'Z001',
			'PValFrom' => $ticket,
			'PValTo' => $accomodation,
			'PKurang' => $grandtotal,
		);

		#Call Operation (Function). Catch and display any errors
		try {
			$result = $client->ZfmGetBudget($params);
		} catch (SoapFault $exception) {
			print "***Caught Exception***\n";
			print_r($exception);
			print "***END Exception***\n";
			die();
		}

		$json = json_encode($result);
		$mresult = substr($json, 20, -2);
		echo $mresult;
	}

	function getBudgetFromLocalDb()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$formid = ifunsetempty($_POST, 'jenisformid', null);
		$params = array(
			'v_pengajuanid' => $pengajuanid,
			'v_formid' => $formid
		);
		$mresult = $this->m_perjalanandinas->getBudgetFromLocalDb($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	// SHARING BUDGET
	function getSharingBudget()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$tglmulai = ifunsetempty($_POST, 'tglmulai', null);
		$tglselesai = ifunsetempty($_POST, 'tglselesai', null);
		$gol = ifunsetempty($_POST, 'gol', null);
		$periode = ifunsetempty($_POST, 'periode', null);

		$params = array(
			'v_pengajuanid' => $pengajuanid,
			'v_tglmulai' => $tglmulai,
			'v_tglselesai' => $tglselesai,
			'v_gol' => $gol,
			'v_periode' => $periode,
		);

		$mresult = $this->m_perjalanandinas->getSharingBudget($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function updSharingBudget()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$periode = ifunsetempty($_POST, 'periode', null);
		$idacco = ifunsetempty($_POST, 'idacco', null);
		$avail = ifunsetempty($_POST, 'avail', '0');
		$hotelprice = ifunsetempty($_POST, 'hotelprice', '0');
		$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
		$pegawaiidsharing = ifunsetempty($_POST, 'pegawaiidsharing', null);
		$pengajuanidsharing = ifunsetempty($_POST, 'pengajuanidsharing', null);
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$totalhistory = ifunsetempty($_POST, 'totalhistory', '0');
		$accomodationhistory = ifunsetempty($_POST, 'accomodationhistory', '0');
		$params = array(
			'v_pengajuanid' => $pengajuanid,
			'v_idacco' => (string) $idacco,
			'v_avail' =>  (string) $avail,
			'v_hotelprice' => (string) $hotelprice,
			'v_periode' => (string) $periode,
			'v_pegawaiidsharing' => (string) $pegawaiidsharing,
			'v_pengajuanidsharing' => $pengajuanidsharing,
			'v_pegawaiid' => (string) $pegawaiid,
			'v_totalhistory' => $totalhistory,
			'v_accomodationhistory' => $accomodationhistory,
		);
		$mresult = $this->m_perjalanandinas->updSharingBudget($params);

		$nik = ifunsetempty($_POST, 'nik', null); // OK
		$nama = ifunsetempty($_POST, 'nama', null);
		$email = ifunsetempty($_POST, 'email', null);
		$niksharing = ifunsetempty($_POST, 'niksharing', null); // OK
		$namasharing = ifunsetempty($_POST, 'namasharing', null);
		$emailsharing = ifunsetempty($_POST, 'emailsharing', null);
		$tglpermohonan = date('Y-m-d H:i:s');
		$newstatusid = null;

		$this->sendMailSharing($nik, $nama, $email, $niksharing, $namasharing, $emailsharing);
	}
	function cancelSharingBudget()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$periode = ifunsetempty($_POST, 'periode', null);
		$aid = ifunsetempty($_POST, 'aid', null);
		$accomodation = ifunsetempty($_POST, 'accomodation', '0');
		$hotelprice = ifunsetempty($_POST, 'hotelprice', '0');
		$totalhistory = ifunsetempty($_POST, 'totalhistory', '0');
		$idsharing = ifunsetempty($_POST, 'idsharing', null);
		$total = $totalhistory + $hotelprice;
		$totalbudget = $accomodation - $hotelprice;

		$params = array(
			'v_pengajuanid' => $pengajuanid,
			'v_idacco' => (string) $aid,
			'v_avail' =>  (string) $accomodation,
			'v_hotelprice' => (string) $hotelprice,
			'v_periode' => (string) $periode,
			'v_totalhistory' => $totalhistory,
			'v_total' => $total,
			'v_idsharing' => $idsharing,
			'v_totalbudget' => (string) $totalbudget,
		);
		$mresult = $this->m_perjalanandinas->cancelSharingBudget($params);

		$nik = ifunsetempty($_POST, 'nik', null); // OK
		$nama = ifunsetempty($_POST, 'nama', null);
		$email = ifunsetempty($_POST, 'email', null);
		$niksharing = ifunsetempty($_POST, 'niksharing', null); // OK
		$namasharing = ifunsetempty($_POST, 'namasharing', null);
		$emailsharing = ifunsetempty($_POST, 'emailsharing', null);
		$tglpermohonan = date('Y-m-d H:i:s');
		$newstatusid = null;

		// $this->sendMailCancelSharing($nik, $nama, $email, $niksharing, $namasharing, $emailsharing);
	}
	public function sendMailSharing($nik, $nama, $email, $niksharing, $namasharing, $emailsharing)
	{
		$tglpermohonan = date("d/m/Y");
		$this->load->library('email');	//Load email library
		// SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'mail.electronic-city.co.id',
			'smtp_port' => 25,
			'smtp_user' => 'hris-ec@electronic-city-internal.co.id',
			'smtp_pass' => 'L0nt0n9',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		if ($email == null) {
			// Tidak mengirim email apabila email id kosong
		} else {
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->to('admin.ga@electronic-city.co.id');
			$this->email->cc('khotama.brata@electronic-city.co.id');
			// $this->email->cc('deny.prabawa@electronic-city.co.id');
			$this->email->from('hris-ec@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Nik Sharing</th><th>Sharing Dengan</th><th>Tanggal Pengajuan</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>' . $niksharing . '</td><td>' . $namasharing . '</td><td>' . $tglpermohonan . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Sharing Perjalanan Dinas HRIS');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}
	// public function sendMailCancelSharing($nik, $nama, $email, $niksharing, $namasharing, $emailsharing)
	// {
	// 	$tglpermohonan = date("d/m/Y");
	// 	$this->load->library('email');	//Load email library
	// 	// SMTP & mail configuration
	// 	$config = array(
	// 		'protocol'  => 'smtp',
	// 		'smtp_host' => 'mail.electronic-city.co.id',
	// 		'smtp_port' => 25,
	// 		'smtp_user' => 'hris-ec@electronic-city-internal.co.id',
	// 		'smtp_pass' => 'L0nt0n9',
	// 		'mailtype'  => 'html',
	// 		'charset'   => 'utf-8'
	// 	);
	// 	if ($email == null) {
	// 		// Tidak mengirim email apabila email id kosong
	// 	} else {
	// 		$this->email->initialize($config);
	// 		$this->email->set_mailtype("html");
	// 		$this->email->set_newline("\r\n");
	// 		$this->email->to('khotama.brata@electronic-city.co.id');
	// 		// $this->email->cc('deny.prabawa@electronic-city.co.id');
	// 		$this->email->from('hris-ec@electronic-city-internal.co.id');
	// 		$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
	// 		$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Nik Sharing</th><th>Cancel Sharing Dengan</th><th>Tanggal Pengajuan</th></tr>';
	// 		$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>' . $niksharing . '</td><td>' . $namasharing . '</td><td>' . $tglpermohonan . '</td></tr>';
	// 		$htmlContent .= '</table>';
	// 		$this->email->subject('Cancel Sharing Perjalanan Dinas HRIS');
	// 		$this->email->message($htmlContent);

	// 		//Send email
	// 		$this->email->send();
	// 	}
	// }
	// END SHARING

	// cetak dokumen luar negeri
	function cetakdokumenadvance_ln()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' => ifunsetemptybase64($_GET, 'pengajuanid', null),
			'v_jenisperjalananid' => ifunsetempty($_GET, 'jenisperjalananid', null)
		);
		$mresult = $this->m_perjalanandinas->getListCetakForm($params);
		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "form_advance_ln.docx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "form_advance_ln.docx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	function cetakdokumenadvanceinvit_ln_901()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' => ifunsetemptybase64($_GET, 'pengajuanid', null),
			'v_jenisperjalananid' => ifunsetempty($_GET, 'jenisperjalananid', null)
		);
		$mresult = $this->m_perjalanandinas->getListCetakForm($params);
		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "form_advance _ln_invitation_901.docx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "form_advance _ln_invitation_901.docx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}
}
