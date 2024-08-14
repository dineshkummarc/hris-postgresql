<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class notifikasi extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->getAllNotifikasi();
	}

	public function getAllNotifikasi()
	{
		$data = array();
		$content = "notifikasi/v_notifikasi";
		$data['pages'] = "notifikasi";
		$this->load->view($content, $data);
	}

	public function getAllNotification()
	{
		$row = $this->input->get('start');
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->m_pengajuan->getAllNotification($penerimaid, $row);
		$notifCount = $this->m_pengajuan->getCountNotif($penerimaid);
		$result = array('success' => true, 'count' => $notifCount, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getAllNotificationHR()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$row = $this->input->get('start');
		$nik = $this->session->userdata('nik');
		$mnotif = $this->m_pengajuan->getAllNotificationHR($penerimaid, $nik, $row);
		$notifCount = $this->m_pengajuan->getCountNotif($penerimaid);
		$result = array('success' => true, 'count' => $notifCount, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getShortNotification()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->m_pengajuan->getShortNotif($penerimaid);
		$notifUnread = $this->m_pengajuan->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getShortNotificationHR()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$nik = $this->session->userdata('nik');
		$mnotif = $this->m_pengajuan->getShortNotifHR($penerimaid, $nik);
		$notifUnread = $this->m_pengajuan->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function updateReadNotif()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->m_pengajuan->updateNotifRead($penerimaid);
		if ($mnotif) {
			$result = array('success' => true);
		} else {
			$result = array('success' => false);
		}
		echo json_encode($result);
	}
}
