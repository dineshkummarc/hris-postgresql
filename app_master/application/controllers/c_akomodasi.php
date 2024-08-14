<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class c_akomodasi extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_akomodasi');
	}
	function getmasterlokasi()
	{
		$node = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_akomodasi->getmasterlokasi($node);

		$data = array();
		foreach ($mresult->result_array() as $r) {
			$temp = array();
			$temp['id'] = $r['id'];
			$temp['text'] = $r['text'];

			$mresult2 = $this->m_lokasi->getmasterlokasi($r['id']);
			$temp['leaf'] = true;
			$data[] = $temp;
		}
		echo json_encode($data);
	}
	function getKursDollar()
	{
		$mresult = $this->m_akomodasi->getKursDollar();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function crudkursdollar()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'dollar' => ifunsetempty($_POST, 'dollar', null),
			'id' => ifunsetempty($_POST, 'id', null),
		);

		if ($flag == '1') {
			unset($params['id']);
			$mresult = $this->m_akomodasi->tambahkursdollar($params);
		} else {
			$mresult = $this->m_akomodasi->ubahkursdollar($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}

		echo json_encode($result);
	}
	function getAkomodasi()
	{
		$mresult = $this->m_akomodasi->getAkomodasi();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function crudAkomodasi()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'hotelprice' => ifunsetempty($_POST, 'hotelprice', null),
			'uangsaku' => ifunsetempty($_POST, 'uangsaku', null),
			'uangmakan' => ifunsetempty($_POST, 'uangmakan', null),
			'transport' => ifunsetempty($_POST, 'transport', null),
			'id' => ifunsetempty($_POST, 'id', null),
		);

		if ($flag == '1') {
			unset($params['id']);
			$mresult = $this->m_akomodasi->tambah($params);
		} else {
			$mresult = $this->m_akomodasi->ubah($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}

		echo json_encode($result);
	}
	function getAkomodasiLn()
	{
		$mresult = $this->m_akomodasi->getAkomodasiLn();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function crudAkomodasiLn()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'southeastasia' => ifunsetempty($_POST, 'southeastasia', null),
			'non_southeastasia' => ifunsetempty($_POST, 'non_southeastasia', null),
			'japan' => ifunsetempty($_POST, 'japan', null),
			'eropa_usa_dubai' => ifunsetempty($_POST, 'eropa_usa_dubai', null),
			'id' => ifunsetempty($_POST, 'id', null),
		);

		if ($flag == '1') {
			unset($params['id']);
			$mresult = $this->m_akomodasi->tambahln($params);
		} else {
			$mresult = $this->m_akomodasi->ubahln($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}

		echo json_encode($result);
	}

	function hapus()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_akomodasi->hapus($r['id']);
			if ($mresult) $o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	function hapusln()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_akomodasi->hapusln($r['id']);
			if ($mresult) $o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
}
