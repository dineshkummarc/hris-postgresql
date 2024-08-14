<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class pengajuan extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->absensi();
	}

	public function absensi()
	{
		$data = array();
		$data['vharilibur'] = $this->getHariLibur(); //ambil tanggal merah dari database
		$data['vinfopegawai'] = $this->getInfoPegawai(); // info pegawai
		$data['vinfoatasan'] = $this->infoAtasan(); // info atasan verifikator & approval
		$data['vopendate'] = $this->getOpendate(); // info atasan verifikator & approval
		$data['pages'] = "pengajuan";
		$content = "pengajuan/cuti/v_cuti";
		$this->m_pengajuan->addlogs();
		$this->load->view($content, $data);
	}

	public function getOpendate()
	{
		$mresult = $this->m_pengajuan->getOpendate();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['nik'];
		}
		return json_encode($data);
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);

		$mresult = $this->m_pengajuan->getInfoPegawai($params);
		return $mresult['firstrow'];
	}

	function infoAtasan()
	{
		$atasanid = $this->session->userdata('atasanid');
		$verifikatorid = $this->session->userdata('verifikatorid');
		$ratasan = $this->m_pengajuan->getappver($atasanid);
		$rverify = '';
		if (!empty($verifikatorid)) {
			$rverify = $this->m_pengajuan->getappver($verifikatorid);
		}

		$result = array(
			'verifikatorid' => !empty($rverify[0]['pegawaiid']) ? $rverify[0]['pegawaiid'] : null,
			'verifikatornik' => !empty($rverify[0]['nik']) ? $rverify[0]['nik'] : null,
			'verifikatornama' => !empty($rverify[0]['nama']) ? $rverify[0]['nama'] : null,
			'verifikatorjab' => !empty($rverify[0]['jabatan']) ? $rverify[0]['jabatan'] : null,
			'verifikatoremail' => !empty($rverify[0]['emailkantor']) ? $rverify[0]['emailkantor'] : null,
			'atasanid' => !empty($rverify[0]['pegawaiid']) ? $rverify[0]['pegawaiid'] : $ratasan[0]['pegawaiid'],
			'atasannik' => !empty($rverify[0]['nik']) ? $rverify[0]['nik'] : $ratasan[0]['nik'],
			'atasannama' => !empty($rverify[0]['nama']) ? $rverify[0]['nama'] : $ratasan[0]['nama'],
			'atasanjab' => !empty($rverify[0]['jabatan']) ? $rverify[0]['jabatan'] : $ratasan[0]['jabatan'],
			'atasanemail' => !empty($rverify[0]['emailkantor']) ? $rverify[0]['emailkantor'] : $ratasan[0]['emailkantor'],
		);

		return $result;
	}

	public function getHariLibur()
	{
		$mresult = $this->m_pengajuan->getHariLibur();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['tgl'];
		}
		return json_encode($data);
	}

	public function cekPengajuanKehadiran()
	{
		$act = ifunsetempty($_POST, 'act', null);

		if ($act == 'add') {
			$pegawaiid = $this->session->userdata('pegawaiid');
			$jenisid = ifunsetempty($_POST, 'jenisid', null);
			$nourut = null;
		} else {
			$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
			$jenisid = ifunsetemptybase64($_POST, 'jenisid', null);
			$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		}
		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_jenisid' => $jenisid,
			'v_tglmulai' => ifunsetempty($_POST, 'tglawal', null),
			'v_nourut' => $nourut,
		);

		$mresult = $this->m_pengajuan->cekPengajuanKehadiran($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	public function simpanabsensi()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		$atasanid = ifunsetempty($_POST, 'atasanid', null);
		$atasanemail = ifunsetempty($_POST, 'atasanemail', null);
		$tglpermohonan = date("d/m/Y");
		$files = $_FILES['files'];
		$newfilesname = null;
		$file_ext = null;

		if ($files['error'] == 0) {
			$filesname_exp = explode('.', $files['name'], 2);
			$newfilesname = $filesname_exp[0] . '_' . time() . '.' . $filesname_exp[1];
		}

		if (is_uploaded_file($files['tmp_name'])) {
			$config['upload_path'] = config_item("absensi_upload_dok_path");
			$config['allowed_types'] = 'png|jpg|jpeg|pdf|doc|docx';
			$config['not_allowed_types'] = 'php|txt|exe';
			$config['max_size']	= 0;
			$config['overwrite']  = TRUE;
			$config['file_name']  = $newfilesname;

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('files')) {
				$output = array('success' => false, 'message' => $this->upload->display_errors('', ''));
				echo json_encode($output);
				return;
			} else {
				$data = array('upload_data' => $this->upload->data());
				$file_ext = $data['upload_data']['file_ext'];
				$newfilesname = $data['upload_data']['file_name'];
			}
		}

		$daftarabsensi = array();
		$daftarabsensi = json_decode($this->input->post('daftarcuti'));

		$o = 0;
		foreach ($daftarabsensi as $r) {
			$params = array(
				'v_jenisid' => !empty($r->jeniscutiid) ? $r->jeniscutiid : null,
				'v_waktu' => $r->tglawal,
				'v_jam' => $r->jamawal,
				'v_keterangan' => !empty($r->keterangan) ? $r->keterangan : null,
				'v_status' => '1',
				'v_pegawaiid' => $pegawaiid,
				'v_atasanid' => $atasanid,
				'v_files' => $newfilesname,
				'v_filestype' => $file_ext
			);
			$mdetailpengajuan = $this->m_pengajuan->addPengajuanAbsensi($params);
			if ($mdetailpengajuan) $o++;

			if ($mdetailpengajuan) {
				$desc = array(
					'nik' => $this->session->userdata('nik'),
					'nama' => $this->session->userdata('nama'),
					'desctription' => 'Pengajuan absensi kehadiran pada tanggal ' . $tglpermohonan,
				);
				$params = array(
					'v_jenisnotif' => 'Pengajuan Form Kehadiran',
					'v_description' => json_encode($desc),
					'v_penerima' => $atasanid,
					'v_useridfrom' => $this->session->userdata('userid'),
					'v_usergroupidfrom' => $this->session->userdata('aksesid_absensi'),
					'v_pengirim' => $pegawaiid,
					'v_modulid' => '10',
					'v_modul' => 'Modul datang telat / pulang cepat',
				);
				$this->m_pengajuan->addNotif($params);
				$this->sendMail($atasanemail);
				$this->m_pengajuan->addlogs();

				$result = array('success' => true, 'message' => 'Data berhasil dikirim');
			} else {
				$result = array('success' => false, 'message' => 'Data gagal dikirim');
			}
			echo json_encode($result);
		}
	}

	public function sendMail($atasanemail)
	{
		$nik = $this->session->userdata('nik');
		$nama = $this->session->userdata('nama');
		$tglpermohonan = date("d/m/Y");
		$this->load->library('email');	//Load email library
		$link = 'http://internal.electronic-city.co.id/hris/';
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
		if ($atasanemail == null) {
			// Tidak mengirim email apabila email id kosong
		} else {
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->to($atasanemail); //$atasanemail
			$this->email->from('hris-ec@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Jenis Notifikasi</th><th>Tanggal Pengajuan</th><th>Link</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>Pengajuan Form Kehadiran</td><td>' . $tglpermohonan . '</td><td>' . $link . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Pengajuan Form Kehadiran');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}
}
