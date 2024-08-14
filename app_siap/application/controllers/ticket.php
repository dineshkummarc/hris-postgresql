<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ticket extends SIAP_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('m_ticket');
		$this->load->model('m_pegawai');
	}
	function getListTicket(){
		$this->m_pegawai->addlogs();
		$params = array(
			'v_id' => ifunsetempty($_POST,'id',''),
			'v_mulai' => ifunsetempty($_POST,'tglmulai',''),
			'v_selesai' => ifunsetempty($_POST,'tglselesai',''),
			'v_satkerid' => ifunsetempty($_POST,'satkerid',''),
			'v_start' => ifunsetempty($_POST,'start',0),
			'v_limit' => ifunsetempty($_POST,'limit',config_item('PAGESIZE')),			
		);
		
		$mresult = $this->m_ticket->getListTicket($params);
		echo json_encode($mresult);
	}
	function getDetailTicket() {
		$this->m_pegawai->addlogs();
		$params = array(
			'v_id' => ifunsetemptybase64($_POST,'id',null),
		);		
		$mresult = $this->m_ticket->getTicketById($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);		
	}
	function get_import_file(){
		$this->m_pegawai->addlogs();
		try{
			$template = $_FILES['dokumen']['tmp_name'];
			
			$objPHPExcel = $this->cetak_phpexcel->loadTemplate($template);
			$objWorksheet = $objPHPExcel->setActiveSheetIndexbyName('Sheet1');
			
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = 73;

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
								$fhead[] = trim(sprintf("%04s\n", $cell->getValue()));
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
		$this->m_pegawai->addlogs();
		$params = array();
		$params = json_decode($this->input->post('params'), true);
		
		$o=0;
		foreach($params as $key => $val){
			$temp = array();			
			if($key !== 'Number'){
				$temp['id'] = $params['Number'];;
				$temp['travel'] = $params['Travel'];
				$temp['noinvoice'] = $params['Transaction Order'];
				$temp['jenis'] = $params['Tiket / Hotel'];
				$temp['namadepan'] = $params['Nama'];
				$temp['departemen'] = $params['Departemen'];
				$temp['tgldinas'] = $params['Tanggal Keberangkatan'];
				$temp['tglkembali'] = $params['Tanggal Kembali'];	
				$temp['tujuan'] = $params['Tujuan'];	
				$temp['jml'] = $params['Jumlah'];	
				$temp['budgetcode'] = $params['Code Budget'];						
				$mresult = $this->m_ticket->addPengajuanTicket($temp);
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
	function cetakdokumen(){
		$this->m_pegawai->addlogs();
		$params = array(
			'v_pengajuanid' => ifunsetemptybase64($_GET,'pengajuanid',null)
		);
		$mresult = $this->m_perjalanandinas->getListCetakForm($params);
			
		$TBS = $this->template_cetak->createNew('xlsx',config_item("siap_tpl_path")."ADVANCE.docx");		
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix'])!=='') && ($_SERVER['SERVER_NAME']=='localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header',array());
		$TBS->MergeBlock('rec', $mresult['data']);			
		$file_name = str_replace('.','_'.date('Y-m-d').'.',"ADVANCE.docx");
		$file_name = str_replace('.','_'.$suffix.'.',$file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);				
	}
	function approveTicket() {
		$this->m_pegawai->addlogs();
		$id = ifunsetemptybase64($_POST,'id',null);
		$avail = ifunsetempty($_POST,'actual',null);
		$codeid = ifunsetempty($_POST,'codeid',null);

		$timezone = "Asia/Jakarta";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');

		$params = array(
			'v_id' => $id,
			'v_avail' => $avail,
			'v_codeid' => $codeid
		);
		$mresult = $this->m_ticket->updTicket($params);
		
		if($mresult) {
			$result = array('success'=>true, 'message'=>'Data berhasil diupdate');
		}
		else {
			$result = array('success'=>false, 'message'=>'Data gagal diupdate');
		}
		echo json_encode($result);
	}
}