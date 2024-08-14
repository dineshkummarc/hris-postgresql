<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_harilibur extends Master_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('m_harilibur');
	}	
	function getListHariLibur() {
		$mresult = $this->m_harilibur->getListHariLibur();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function crudHariLibur() {
		$flag = ifunsetempty($_POST,'flag','1');
		$params = array(
			'tgl' => ifunsetempty($_POST,'tgl',null),
			'keterangan' => ifunsetempty($_POST,'keterangan',null),
			'status' => ifunsetempty($_POST,'status',null),
			'jenis' => ifunsetempty($_POST,'jenisid',null),			
			'hariliburid' => ifunsetempty($_POST,'hariliburid',null),			
		);	
		
		if($flag == '1'){
			unset($params['hariliburid']);
			$mresult = $this->m_harilibur->tambah($params);
		}
		else{
			$mresult = $this->m_harilibur->ubah($params);
		}				
		
		if($mresult){
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		}
		else{
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}			
		
		echo json_encode($result);
	}
	function hapus() {
		$params = array();
		$params = json_decode($this->input->post('params'), true);
		
		$o=0;
		foreach($params as $r){
			$mresult = $this->m_harilibur->hapus($r['id']);
			if($mresult) $o++;
		}		
		if($o > 0){
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		}
		else{
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}		
		echo json_encode($result);
	}
}