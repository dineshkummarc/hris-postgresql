<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class c_budgetkhusus extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_budgetkhusus');
	}
	function getBudget()
	{
		$years = ifunsetempty($_POST, 'tahun', date("Y"));

		$mresult = $this->m_budgetkhusus->getBudget($years);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function getSatker()
	{
		$years = ifunsetempty($_POST, 'tahun', date("Y"));

		$mresult = $this->m_budgetkhusus->getSatker($years);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function crudBudget()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'id' => ifunsetempty($_POST, 'id', null),
			'nama' => ifunsetempty($_POST, 'nama', null),
			'periode' => ifunsetempty($_POST, 'periode', null),
			'jan' => ifunsetempty($_POST, 'jan', 0),
			'feb' => ifunsetempty($_POST, 'feb', 0),
			'mar' => ifunsetempty($_POST, 'mar', 0),
			'apr' => ifunsetempty($_POST, 'apr', 0),
			'mei' => ifunsetempty($_POST, 'mei', 0),
			'jun' => ifunsetempty($_POST, 'jun', 0),
			'jul' => ifunsetempty($_POST, 'jul', 0),
			'agu' => ifunsetempty($_POST, 'agu', 0),
			'sep' => ifunsetempty($_POST, 'sep', 0),
			'okt' => ifunsetempty($_POST, 'okt', 0),
			'nov' => ifunsetempty($_POST, 'nov', 0),
			'des' => ifunsetempty($_POST, 'des', 0)
		);

		if ($flag == '1') {
			$mresult = $this->m_budgetkhusus->tambah($params);
		} else {
			$mresult = $this->m_budgetkhusus->ubah($params);
		}
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}
	function crudTambahBudget()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$budget = ifunsetempty($_POST, 'budget', 0);
		$bulan = ifunsetempty($_POST, 'bulan', 0);
		if ($bulan == '1') {
			$jan = $budget;
		} else if ($bulan == '2') {
			$feb = $budget;
		} else if ($bulan == '3') {
			$mar = $budget;
		} else if ($bulan == '4') {
			$apr = $budget;
		} else if ($bulan == '5') {
			$mei = $budget;
		} else if ($bulan == '6') {
			$jun = $budget;
		} else if ($bulan == '7') {
			$jul = $budget;
		} else if ($bulan == '8') {
			$agu = $budget;
		} else if ($bulan == '9') {
			$sep = $budget;
		} else if ($bulan == '10') {
			$okt = $budget;
		} else if ($bulan == '11') {
			$nov = $budget;
		} else if ($bulan == '12') {
			$des = $budget;
		}

		$params = array(
			'id' => ifunsetempty($_POST, 'id', null),
			'nama' => ifunsetempty($_POST, 'nama', null),
			'jenisbudget' => ifunsetempty($_POST, 'jenisbudget', null),
			'satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'periode' => ifunsetempty($_POST, 'periode', null),
			'jan' => (int) $jan,
			'feb' => (int) $feb,
			'mar' => (int) $mar,
			'apr' => (int) $apr,
			'mei' => (int) $mei,
			'jun' => (int) $jun,
			'jul' => (int) $jul,
			'agu' => (int) $agu,
			'sep' => (int) $sep,
			'okt' => (int) $okt,
			'nov' => (int) $nov,
			'des' => (int) $des,
		);

		if ($flag == '1') {
			$mresult = $this->m_budgetkhusus->tambah($params);
		} else {
			$mresult = $this->m_budgetkhusus->ubah($params);
		}
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}
	function get_import_file()
	{
		try {
			$template = $_FILES['dokumen']['tmp_name'];

			$objPHPExcel = $this->cetak_phpexcel->loadTemplate($template);
			$objWorksheet = $objPHPExcel->setActiveSheetIndexbyName('Sheet1');

			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = 73;

			$startRow  = 4;
			$startColumn  = 0;

			for ($row = $startRow; $row <= $highestRow; $row++) {
				$codeid = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
				$id = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				$periode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
				$satker = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
				$coa = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
				$description = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
				$jan = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
				$feb = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
				$mar = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
				$apr = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
				$mei = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
				$jun = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
				$jul = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
				$agu = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
				$sep = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
				$okt = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
				$nov = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
				$des = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
				$total = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
				$data[] = array(
					'codeid'  		=> $codeid,
					'id'   			=> $id,
					'periode'   	=> $periode,
					'satker'  		=> $satker,
					'coa'   		=> $coa,
					'nama'   		=> $description,
					'jan'   		=> $jan,
					'feb'   		=> $feb,
					'mar'   		=> $mar,
					'apr'   		=> $apr,
					'mei'   		=> $mei,
					'jun'   		=> $jun,
					'jul'   		=> $jul,
					'agu'   		=> $agu,
					'sep'   		=> $sep,
					'okt'   		=> $okt,
					'nov'   		=> $nov,
					'des'   		=> $des,
					'avail'  		=> $total
				);
			}
			$result = array('success' => true, 'data' => $data, 'message' => $highestColumn);
			echo json_encode($result);
		} catch (Exception $e) {
			$result = array('success' => false, 'data' => '', 'message' => 'File upload tidak sesuai dengan template');
			echo json_encode($result);
		}
	}
	function proses_import_file()
	{
		$daftarperjalanan = array();
		$daftarperjalanan = json_decode($this->input->post('params'));

		$o = 0;
		foreach ($daftarperjalanan as $r) {
			$data[] = array(
				'codeid'  	=> !empty($daftarperjalanan->codeid) ? (string) $daftarperjalanan->codeid : null,
				'id'   		=> !empty($daftarperjalanan->id) ? (string) $daftarperjalanan->id : null,
				'periode'   => !empty($daftarperjalanan->periode) ? (string) $daftarperjalanan->periode : null,
				'satker' => !empty($daftarperjalanan->satker) ? (string) $daftarperjalanan->satker : null,
				'coa'   => !empty($daftarperjalanan->coa) ? (string) $daftarperjalanan->coa : null,
				'nama'  => !empty($daftarperjalanan->nama) ? (string) $daftarperjalanan->nama : null,
				'jan'   => !empty($daftarperjalanan->jan) ? (string) $daftarperjalanan->jan : '0',
				'feb'   => !empty($daftarperjalanan->feb) ? (string) $daftarperjalanan->feb : '0',
				'mar'   => !empty($daftarperjalanan->mar) ? (string) $daftarperjalanan->mar : '0',
				'apr'   => !empty($daftarperjalanan->apr) ? (string) $daftarperjalanan->apr : '0',
				'mei'   => !empty($daftarperjalanan->mei) ? (string) $daftarperjalanan->mei : '0',
				'jun'   => !empty($daftarperjalanan->jun) ? (string) $daftarperjalanan->jun : '0',
				'jul'   => !empty($daftarperjalanan->jul) ? (string) $daftarperjalanan->jul : '0',
				'agu'   => !empty($daftarperjalanan->agu) ? (string) $daftarperjalanan->agu : '0',
				'sep'   => !empty($daftarperjalanan->sep) ? (string) $daftarperjalanan->sep : '0',
				'okt'   => !empty($daftarperjalanan->okt) ? (string) $daftarperjalanan->okt : '0',
				'nov'   => !empty($daftarperjalanan->nov) ? (string) $daftarperjalanan->nov : '0',
				'des'   => !empty($daftarperjalanan->des) ? (string) $daftarperjalanan->des : '0',
				'avail' => !empty($daftarperjalanan->avail) ? (string) $daftarperjalanan->avail : null
			);
			$result = $this->db->get_where('pjdinas.kodesapbudget', array('codeid' => (string) $daftarperjalanan->codeid))->result();
			if (count($result) > 0) {
				//nothing
			} else {
				$mresult = $this->db->insert_batch('pjdinas.kodesapbudget', $data);
				if ($mresult) $o++;
			}
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'data berhasil');
		} else {
			$result = array('success' => false, 'message' => 'data gagal');
		}
		echo json_encode($result);
	}
	function hapus()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_budgetkhusus->hapus($r['id']);
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
