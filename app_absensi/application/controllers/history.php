<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class history extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_history');
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->historyabsensi();
	}

	public function historyabsensi()
	{
		$this->m_pengajuan->addlogs();
		$this->m_history->updStatusExp();
		$data = array();
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$content = "history/v_historycutihome";
		$data['pages'] = "history";
		$this->load->view($content, $data);
	}

	public function getListPegawai()
	{
		$nik = null;
		$usergroupid = $this->session->userdata('aksesid_eservices');
		$satkerid = $this->session->userdata('satkerdisp');

		if ($usergroupid == '11') {
			$nik = $this->session->userdata('nik');
			$satkerid = '';
		}

		$params = array(
			'v_satkerid' => $satkerid,
			'v_nik' => $nik,
			'v_nama' => '',
			'v_statuspegawai' => '',
			'v_jeniskelamin' => '',
			'v_tglmulai' => '',
			'v_tglselesai' => '',
			'v_usergroupid' => $usergroupid,
			'v_lokasiid' => $this->session->userdata('lokasiid'),
			'v_keyword' => ifunsetempty($_GET, 'keyword', ''),
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);

		$mresult = $this->m_history->getListPegawai($params);
		echo json_encode($mresult);
	}

	function detaillistkehadiran()
	{
		$this->m_pengajuan->addlogs();
		$data = array();
		$data['vpegawaiid'] = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$data['vstatuscuti'] = $this->m_history->getComboStatusCuti();
		$data['pages'] = "history";
		$content = "history/v_historycuti";
		$this->load->view($content, $data);
	}

	public function getListHistoryKehadiran()
	{
		$pegawaiid = ifunsetempty($_GET, 'pegawaiid', null);
		$satkerid = $this->session->userdata('satkerdisp');

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_status' => ifunsetempty($_GET, 'status', null),
			'v_mulai' => ifunsetempty($_GET, 'tglmulai', null),
			'v_selesai' => ifunsetempty($_GET, 'tglselesai', null),
			'v_satkerid' => $satkerid,
			'v_keyword' => null,
			'v_nstatus' => null,
			'v_usergroupid' => null,
			'v_lokasiid' => '1',
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);
		$mresult = $this->m_history->getListHistoryKehadiran($params);
		echo json_encode($mresult);
	}

	public function deleteCuti()
	{
		$this->m_pengajuan->addlogs();
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$verifikator = ifunsetempty($_POST, 'verifikatorid', null);
		$approval = ifunsetempty($_POST, 'approvalid', null);
		$atasan1email = ifunsetempty($_POST, 'verifikatoremail', null);
		$atasan2email = ifunsetempty($_POST, 'approvallemail', null);

		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_pengajuanid' => ifunsetempty($_POST, 'pengajuanid', null),
		);

		$mresult = $this->m_history->deleteCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);

		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$atasanid = '';
		$atasanemail = '';

		if ($verifikator == '') {
			$atasanid = $approval;
			$atasanemail = $atasan2email;
		} else {
			$atasanid = $verifikator;
			$atasanemail = $atasan1email;
		}
		$keterangan = 'Cancel cuti';
		$desc = array(
			'nik' => $this->session->userdata('nik'),
			'nama' => $this->session->userdata('nama'),
			'desctription' => 'Cancel Cuti pada tanggal ' . $tglpermohonan,
		);

		$params = array(
			'v_jenisnotif' => 'Cancel Cuti',
			'v_description' => json_encode($desc),
			'v_penerima' => $atasanid,
			'v_useridfrom' => $this->session->userdata('userid'),
			'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
			'v_pengirim' => $pegawaiid,
			'v_modulid' => '2',
			'v_modul' => null,
		);
		$this->tp_notification->addNotif($params);
		//$this->sendMail($atasanemail,$keterangan);	
	}

	// Update ke Batal Cuti Parsial
	public function delCuti()
	{
		$this->m_pengajuan->addlogs();
		$params = array(
			'v_detailpengajuanid' => ifunsetempty($_POST, 'detailpengajuanid', null),
			'v_lama' => ifunsetempty($_POST, 'lama', null),
		);

		$mresult = $this->m_history->updBtlCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}

	// cancel Batal Cuti Parsial
	public function cCuti()
	{
		$this->m_pengajuan->addlogs();
		$params = array(
			'v_detailpengajuanid' => ifunsetempty($_POST, 'detailpengajuanid', null),
			'v_lama' => ifunsetempty($_POST, 'lama', null),
		);

		$mresult = $this->m_history->cBtlCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}

	public function deleteDraft()
	{
		$this->m_pengajuan->addlogs();
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_pengajuanid' => ifunsetempty($_POST, 'pengajuanid', null),
		);

		$mresult = $this->m_history->deleteAbsensi($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus yes');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}

	public function download()
	{
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('absensi_upload_dok_path');

		if ($filename == '') {
			echo '<h2>Belum upload file</h2>';
		} else if (file_exists($path . $filename)) {
			$data = file_get_contents($path . $filename);
			force_download($filename, $data);
		} else {
			echo '<h2>Maaf, File hilang</h2>';
		}
	}

	function detail()
	{
		$pegawaiid = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_GET, 'nourut', null);
		$tahun = date("Y");

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_tahun' => $tahun,
		);
		$mGetDetailCuti = $this->m_history->getCutiById($params);

		$pengajuanid = $mGetDetailCuti['pengajuanid'];
		$mGetDetailPengajuanCuti = $this->m_history->getDetailPengajuanCuti($pengajuanid);

		$data = array();
		$content = "history/detail/v_historycuti";

		$data['info'] = $mGetDetailCuti;
		$data['daftarcuti'] = $mGetDetailPengajuanCuti;
		$data['vharilibur'] = $this->getHariLibur();
		$data['vjeniscuti'] = $this->m_history->getComboJenisCuti();
		$data['infopegawai'] = $this->getInfoPegawai();
		$data['vinfoatasan'] = $this->infoAtasan($pegawaiid);

		$this->load->view($content, $data);
	}

	public function getHariLibur()
	{
		$mresult = $this->m_history->getHariLibur();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['tgl'];
		}
		return json_encode($data);
	}

	public function batalCuti()
	{
		$this->m_pengajuan->addlogs();
		$pegawaiid = $this->session->userdata('pegawaiid');
		$batalCuti = ifunsetempty($_POST, 'alasan', null);
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$verifikator = ifunsetempty($_POST, 'verifikatorid', null);
		$approval = ifunsetempty($_POST, 'approvalid', null);
		$atasan1email = ifunsetempty($_POST, 'verifikatoremail', null);
		$atasan2email = ifunsetempty($_POST, 'approvallemail', null);
		$atasanid = '';
		$atasanemail = '';
		$keterangan = 'Pengajuan Batal cuti';

		if ($verifikator == '') {
			$atasanid = $approval;
			$atasanemail = $atasan2email;
		} else {
			$atasanid = $verifikator;
			$atasanemail = $atasan1email;
		}

		$params = array(
			'pengajuanid' => $pengajuanid,
			'status' => "9",
			'atasan1' => $verifikator,
			'atasan2' => $approval,
			'tglpermohonan' => $tglpermohonan,
		);
		$mbatalcuti = $this->m_history->addAlasan($batalCuti, $pengajuanid);
		$mresult = $this->m_history->updStatusCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil diubah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diubah');
		}
		echo json_encode($result);

		$desc = array(
			'nik' => $this->session->userdata('nik'),
			'nama' => $this->session->userdata('nama'),
			'desctription' => 'Pengajuan Batal Cuti pada tanggal ' . $tglpermohonan,
		);

		$params = array(
			'v_jenisnotif' => 'Pengajuan Batal Cuti',
			'v_description' => json_encode($desc),
			'v_penerima' => $atasanid,
			'v_useridfrom' => $this->session->userdata('userid'),
			'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
			'v_pengirim' => $pegawaiid,
			'v_modulid' => '2',
			'v_modul' => null,
		);
		$this->tp_notification->addNotif($params);
		//$this->sendMail($atasanemail,$keterangan);	
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);

		$mresult = $this->m_history->getInfoPegawai($params);
		return $mresult['firstrow'];
	}

	function infoAtasan($pegawaiid)
	{
		$mverifikator = $this->m_history->getVerifikatorCuti($pegawaiid);
		$result = array(
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
			'approvallemail' => '',
		);
		if (!empty($mverifikator['atasanid'])) {
			$mapproval = $this->m_history->getApprovalCuti($mverifikator['atasanid']);
			$result = array(
				'verifikatorid' => $mverifikator['atasanid'],
				'verifikatornik' => $mverifikator['atasannik'],
				'verifikatornama' => $mverifikator['atasannama'],
				'verifikatordivisi' => $mverifikator['atasandivisi'],
				'verifikatorjabatan' => $mverifikator['atasanjabatan'],
				'verifikatorlokasi' => $mverifikator['atasanlokasi'],
				'verifikatoremail' => $mverifikator['atasanemail'],
				'approvalid' => $mapproval['atasanid'],
				'approvalnik' => $mapproval['atasannik'],
				'approvalnama' => $mapproval['atasannama'],
				'approvaldivisi' => $mapproval['atasandivisi'],
				'approvaljabatan' => $mapproval['atasanjabatan'],
				'approvallokasi' => $mapproval['atasanlokasi'],
				'approvallemail' => $mapproval['atasanemail'],
			);
		} else {
			$mapproval = $this->m_history->getApprovalCuti($pegawaiid);
			$result = array(
				'verifikatorid' => '',
				'verifikatornik' => '',
				'verifikatornama' => '',
				'verifikatordivisi' => '',
				'verifikatorjabatan' => '',
				'verifikatorlokasi' => '',
				'verifikatoremail' => '',
				'approvalid' => $mapproval['atasanid'],
				'approvalnik' => $mapproval['atasannik'],
				'approvalnama' => $mapproval['atasannama'],
				'approvaldivisi' => $mapproval['atasandivisi'],
				'approvaljabatan' => $mapproval['atasanjabatan'],
				'approvallokasi' => $mapproval['atasanlokasi'],
				'approvallemail' => $mapproval['atasanemail'],
			);
		}
		return $result;
	}
}
