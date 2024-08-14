<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_budget extends Master_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('m_budget');
	}
	function getBudget(){
		$years = ifunsetempty($_POST,'tahun',date("Y"));
		
		$mresult = $this->m_budget->getBudget($years);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function crudBudget() {
		$flag = ifunsetempty($_POST,'flag','1');
		$params = array(
			'id' => ifunsetempty($_POST,'id',null),
			'nama' => ifunsetempty($_POST,'nama',null),	
			'periode' => ifunsetempty($_POST,'periode',null),
			'jan' => ifunsetempty($_POST,'jan',0),
			'feb' => ifunsetempty($_POST,'feb',0),
			'mar' => ifunsetempty($_POST,'mar',0),
			'apr' => ifunsetempty($_POST,'apr',0),
			'mei' => ifunsetempty($_POST,'mei',0),
			'jun' => ifunsetempty($_POST,'jun',0),
			'jul' => ifunsetempty($_POST,'jul',0),
			'agu' => ifunsetempty($_POST,'agu',0),
			'sep' => ifunsetempty($_POST,'sep',0),
			'okt' => ifunsetempty($_POST,'okt',0),
			'nov' => ifunsetempty($_POST,'nov',0),
			'des' => ifunsetempty($_POST,'des',0)
		);	
		
		if($flag == '1'){
			$mresult = $this->m_budget->tambah($params);
		}
		else{
			$mresult = $this->m_budget->ubah($params);
		}				
		if($mresult){
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		}
		else{
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}			
		echo json_encode($result);
	}
	function get_import_file(){
		try{
			$template = $_FILES['dokumen']['tmp_name'];
			
			$objPHPExcel = $this->cetak_phpexcel->loadTemplate($template);
			$objWorksheet = $objPHPExcel->setActiveSheetIndexbyName('Sheet1');
			
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = 30;

			$startRow  = 1;
			$startColumn  = 0;
			
			$data = array();			
			$fhead = array();
			
			for($row = $startRow; $row <= $highestRow; ++ $row){				
				$val = array();
				if($row == 1){					
					for($col = $startColumn; $col < $highestColumnIndex; ++ $col){
						$cell = $objWorksheet->getCellByColumnAndRow($col, $row);
						if( !empty($cell->getValue())){
							if( strlen($cell->getValue()) == 4){
								$fhead[] = $cell->getValue();
							}
							else{
								$fhead[] = $cell->getValue();
								//$fhead[] = trim(sprintf("%04s\n", $cell->getValue()));
							}
						}						
					}					
				}			
				else{
					for($col = $startColumn; $col < sizeof($fhead); ++ $col){					
						$cell = $objWorksheet->getCellByColumnAndRow($col, $row);
						$cell = $cell->getValue();				
						
						// Jika tanggal format excel maka convert ke dalam format Y-m-d
						if($col == 0 and $row > 1){
							$celltgl = $objWorksheet->getCellByColumnAndRow(0, $row);
							$cell = $celltgl->getValue();
							if( !empty($cell)){
								if(PHPExcel_Shared_Date::isDateTime($celltgl)) {
									$cell = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cell)); 
								}								
							}
						}					
						
						// Jika kosong maka jangan diinsert kedalam database
						// if( !empty($cell)){
							// $val[$fhead[$col]] = $cell;
						// }
						if(strlen($cell) > 0){
							$val[$fhead[$col]] = $cell;
						}
					}
					if(sizeof($val) > 0){
						$data[] = $val;
					}					
				}
			}			
			$result = array('success' => true, 'data' => $data, 'message' => $highestColumn);
			echo json_encode($result);
		}
		catch(Exception $e){
			$result = array('success' => false, 'data' => '', 'message' => 'File upload tidak sesuai dengan template');
			echo json_encode($result);
		}	
	}
	function proses_import_file(){
		$params = array();
		$params = json_decode($this->input->post('params'), true);
		
		$o=0;
		foreach($params as $key => $val){
			$temp = array();			
			if($key !== 'Code ID'){
				$temp['codeid'] = $params['Code ID'];;
				$temp['id'] = $params['Code Budget'];
				$temp['periode'] = $params['Periode'];
				$temp['satker'] = $params['Satker'];
				$temp['coa'] = $params['COA'];
				$temp['nama'] = $params['Description'];
				$temp['jan'] = $params['JAN'];
				$temp['feb'] = $params['FEB'];
				$temp['mar'] = $params['MAR'];	
				$temp['apr'] = $params['APR'];	
				$temp['mei'] = $params['MAY'];	
				$temp['jun'] = $params['JUN'];
				$temp['jul'] = $params['JUL'];
				$temp['agu'] = $params['AUG'];	
				$temp['sep'] = $params['SEP'];	
				$temp['okt'] = $params['OCT'];	
				$temp['nov'] = $params['NOV'];
				$temp['des'] = $params['DEC'];	
				$temp['avail'] = $params['Subtotal'];					
				$mresult = $this->m_budget->addBudget($temp);
				if($mresult) $o++;
			}
		}		
		if($o > 0){
			$result = array('success' => true, 'message' => 'data berhasil');
		}
		else{
			$result = array('success' => false, 'message' => 'data gagal');
		}
		echo json_encode($result);
	}
}