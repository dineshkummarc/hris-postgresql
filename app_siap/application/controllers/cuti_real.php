<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cuti extends SIAP_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('m_cuti');
	}
	function getListCuti(){
		$params = array(
			'v_pegawaiid' => null,
			'v_status' => null,
			'v_mulai' => ifunsetempty($_POST,'tglmulai',null),
			'v_selesai' => ifunsetempty($_POST,'tglselesai',null),
			'v_satkerid' => ifunsetempty($_POST,'satkerid',''),
			'v_keyword' => ifunsetempty($_POST,'keyword',''),
			'v_nstatus' => ifunsetempty($_POST,'statusid',null),
			'v_usergroupid' => $this->session->userdata('aksesid_siap'),
			'v_lokasiid' => $this->session->userdata('lokasiid'),
			'v_start' => ifunsetempty($_POST,'start',0),
			'v_limit' => ifunsetempty($_POST,'limit',config_item('PAGESIZE')),			
		);
		
		$mresult = $this->m_cuti->getListCuti($params);
		echo json_encode($mresult);
	}	
	function getDetailCutiPegawai() {
		$params = array(
			'v_pegawaiid' => ifunsetemptybase64($_POST,'pegawaiid',null),
			'v_nourut' => ifunsetemptybase64($_POST,'nourut',null),
			'v_tahun' => ifunsetemptybase64($_POST,'tahun',null),
		);		
		$mresult = $this->m_cuti->getCutiById($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);		
	}
	function getDetailPengajuanCuti() {
		$pengajuanid = ifunsetempty($_POST,'pengajuanid',null);
		$mresult = $this->m_cuti->getDetailPengajuanCuti($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function approveCuti() {
		$pegawaiid = ifunsetemptybase64($_POST,'pegawaiid',null);
		$nourut = ifunsetemptybase64($_POST,'nourut',null);
		$nik = ifunsetempty($_POST,'nik',null);
		$nama = ifunsetempty($_POST,'nama',null);
		$tglpermohonan = ifunsetempty($_POST,'tglpermohonan',null);
		$email = ifunsetempty($_POST,'email',null);
		$statusid = ifunsetempty($_POST,'statusid',null);
		$newstatusid = null;
		$newstatustext = '';
		
		// Jika disetujui approval maka ubah statusid = 7
		if($statusid == '5') {
			$newstatusid = '7';
			$newstatustext = 'Disetujui HR';
		}
		// Jika Pengajuan pembatalan disetujui approval maka ubah statusid = 14
		else if($statusid == '12') {
			$newstatusid = '14';
			$newstatustext = 'Pengajuan pembatalan disetujui hr';
		}
		
		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_notes' => null,
			'v_hrd' => $this->session->userdata('pegawaiid'),
		);
		
		// echo json_encode($params);
		// exit;
		
		$mresult = $this->m_cuti->updStatusCuti($params);
		
		if($mresult) {
			$desc = array(
				'nik' => $nik,
				'nama' => $nama,
				'desctription' => 'Pengajuan Cuti pada tanggal '.$tglpermohonan.' '.$newstatustext,
			);
			
			$sendParams = array(
				'v_jenisnotif' => $newstatustext,
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_siap'),
				'v_pengirim' => $pegawaiid,
				'v_modulid' => '2',
				'v_modul' => null,
			);	
			
			$this->tp_notification->addNotif($sendParams);
			$this->sendMail($nik,$email,$nama,$newstatustext);	

			$result = array('success'=>true, 'message'=>'Data berhasil diupdate');
		}
		else {
			$result = array('success'=>false, 'message'=>'Data gagal diupdate');
		}
		echo json_encode($result);
	}
	function rejectCuti() {
		$pegawaiid = ifunsetemptybase64($_POST,'pegawaiid',null);
		$nourut = ifunsetemptybase64($_POST,'nourut',null);
		$nik = ifunsetempty($_POST,'nik',null);
		$nama = ifunsetempty($_POST,'nama',null);
		$email = ifunsetempty($_POST,'email',null);
		$tglpermohonan = ifunsetempty($_POST,'tglpermohonan',null);
		$statusid = ifunsetempty($_POST,'statusid',null);		
		$newstatusid = null;
		$newstatustext = '';
		
		// Jika Ditolak Approval maka ubah statusid = 6
		if($statusid == '5') {
			$newstatusid = '8';
			$newstatustext = 'Ditolak HR';
		}
		// Jika Pengajuan pembatalan ditolak approval maka ubah statusid = 15
		else if($statusid == '12') {
			$newstatusid = '15';
			$newstatustext = 'Pengajuan pembatalan ditolak hr';
		}
						
		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_notes' => ifunsetempty($_POST,'alasan',null),
			'v_hrd' => $this->session->userdata('pegawaiid'),
		);	
		
		$mresult = $this->m_cuti->updStatusCuti($params);
		
		if($mresult) {
			$desc = array(
				'nik' => $nik,
				'nama' => $nama,
				'desctription' => 'Pengajuan Cuti pada tanggal '.$tglpermohonan.' '.$newstatustext,
			);
			
			$sendParams = array(
				'v_jenisnotif' => $newstatustext,
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_siap'),
				'v_pengirim' => $pegawaiid,
				'v_modulid' => '2',
				'v_modul' => null,
			);	
			
			$this->tp_notification->addNotif($sendParams);
			$this->sendMail($nik,$email,$nama,$newstatustext);				
			
			$result = array('success'=>true, 'message'=>'Data berhasil diupdate');
		}
		else {
			$result = array('success'=>false, 'message'=>'Data gagal diupdate');
		}
		echo json_encode($result);
	}
	public function sendMail($nik,$email,$nama,$newstatustext) {
		$tglpermohonan = date("d/m/Y");
		$this->load->library('email');	//Load email library
		// SMTP & mail configuration
		$config = array(
		    'protocol'  => 'smtp',
		    'smtp_host' => 'mail.electronic-city.co.id',
		    'smtp_port' => 25,
		    'smtp_user' => 'hris@electronic-city-internal.co.id',
		    'smtp_pass' => 'HREc!2018',
		    'mailtype'  => 'html',
		    'charset'   => 'utf-8'
		);
		if ($email == null) {
			// Tidak mengirim email apabila email id kosong
		} else {
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->to($email);
			$this->email->cc('rahman.gunawan@electronic-city.co.id');
			$this->email->from('hris@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Status Notifikasi</th><th>Tanggal Pengajuan</th></tr>';
			$htmlContent .= '<tr><td>' .$nik.'</td><td>' .$nama.'</td><td>'.$newstatustext.'</td><td>'.$tglpermohonan.'</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Status Cuti HRIS');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}
	public function download() {
		$this->load->helper('download');
		
		$filename = $this->input->get('filename');
		$path = config_item('eservices_upload_dok_path');
		
		if($filename == '') {
			echo '<h2>Belum upload file</h2>';
		}
		else if(file_exists($path . $filename)) {
			$data = file_get_contents($path . $filename);			
			force_download($filename, $data); 
		} 
		else {
			echo '<h2>Maaf, File hilang</h2>';
		}										
	}	
	public function getPegawaiByNIK() {
		$params = array(
			'nik' => ifunsetempty($_POST,'nik',null),
			'tahun' => ifunsetempty($_POST,'tahun',null),		
		);
		
		$mresult = $this->m_cuti->getInfoPegawai($params);
		
		$result = array('success'=>true, 'data'=>$mresult);
		echo json_encode($result);
	}
	public function countLengthCuti() {
		$pegawaiid = ifunsetempty($_POST,'pegawaiid',null);
		$tglmulai = ifunsetempty($_POST,'tglmulai',null);
		$tglselesai = ifunsetempty($_POST,'tglselesai',null);
		
		$mresultHariLibur = $this->m_cuti->getHariLibur();
		
		$holidays = array();
		foreach ($mresultHariLibur as $key => $value) {
			$holidays[$key] = $value['tgl'];
		}
		
		$lama_cuti = getJumlahHariKerja($tglmulai,$tglselesai,$holidays);		
		$result = array('success' => true, 'data' => $lama_cuti);		
		echo json_encode($result);				
	}
	public function crudCuti() {
		$pegawaiid = ifunsetempty($_POST,'pegawaiid',null);
		$tahun = ifunsetempty($_POST,'tahun',date("Y"));
		$nik = ifunsetempty($_POST,'nik',null);
		$nama = ifunsetempty($_POST,'nama',null);
		$sisacutithnini = ifunsetempty($_POST,'sisacutithnini',null);
		$sisacutithnlalu = ifunsetempty($_POST,'sisacutithnlalu',null);
		$tglpermohonan = ifunsetempty($_POST,'tglpermohonan',null);
		$atasan1 = ifunsetempty($_POST,'atasanid',null);
		$atasan2 = ifunsetempty($_POST,'atasan2id',null);
		$pelimpahan = ifunsetempty($_POST,'pelimpahanid',null);
		$hp = ifunsetempty($_POST,'hp',null);
		$files = $_FILES['files'];
		$newfilesname = null;
		$file_ext = null;
				
		$detailallcuti = array();
		$detailallcuti = json_decode(ifunsetempty($_POST,'detailallcuti',null));
		
		if($files['error'] == 0) {
			$filesname_exp = explode('.',$files['name'],2);
			$newfilesname = $filesname_exp[0] . '_' . time() . '.' . $filesname_exp[1];
		}
				
		if(is_uploaded_file($files['tmp_name'])) {
			$config['upload_path'] = config_item("eservices_upload_dok_path");
			$config['allowed_types'] = '*';
			$config['not_allowed_types'] = 'php|txt|exe';
			$config['max_size']	= '1000000';
			$config['overwrite']  = TRUE;
			$config['file_name']  = $newfilesname;
			
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('files')){
				$error = array('error' => $this->upload->display_errors());
				$output = array('success' => false, 'message' => $this->upload->display_errors('',''));
				echo json_encode($output);
				return;
			}
			else{				
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
		if( !empty($mpengajuan['pengajuanid'])) {
			$o=0;
			foreach($detailallcuti as $r) {
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
				if($mdetailpengajuan) $o++;								
			}
			
			if($o > 0) {
				$result = array('success' => true, 'message' => 'Data berhasil ditambah');
			}
			else {
				$result = array('success' => false, 'message' => 'Data gagal ditambah');
			}
		}
		else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		
		echo json_encode($result);
	}
}