<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class report extends SIAP_Controller
{
	private $warna = array('#406a9b', '#9d403e', '#809948', '#6a5085', '#da9f00', '#3c8da3', '#cd7c38');

	function __construct()
	{
		parent::__construct();
		$this->load->model('m_report');
		$this->load->model('m_pegawai');
	}

	// report ulang tahun
	function getReportUlangtahun()
	{
		$this->m_pegawai->addlogs();
		$month = date('m');
		$bulan = ifunsetempty($_POST, 'bulan', 0);
		$hari = ifunsetempty($_POST, 'hari', 0);

		$a = '';
		if ($bulan != '') {
			$a = $bulan;
		} else {
			$a = $month;
		}

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_hari' => ifunsetempty($_POST, 'hari', '01'),
			'v_bulan' => $a,
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->getReportUlangtahun($params);
		echo json_encode($mresult);
	}

	// report acting as
	function getReportActingAs()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
		);
		$mresult = $this->m_report->getReportActingAs($params);
		echo json_encode($mresult);
	}

	function getReportActingAsBySatker()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->getReportActingAsBySatker($params);
		echo json_encode($mresult);
	}

	function getMutasiPromosi()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->getMutasiPromosi($params);
		echo json_encode($mresult);
	}

	function getReportListKaderPegawai()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_levelid' => ifunsetempty($_POST, 'level', null),
			'v_lokasiid' => ifunsetempty($_POST, 'lokasi', null),
			'v_start' => 0,
			'v_limit' => 1000000
		);
		$mresult = $this->m_report->getReportListKader($params);
		echo json_encode($mresult);
	}

	function getReportListKaderGroup()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_levelid' => ifunsetempty($_POST, 'level', null),
			'v_lokasiid' => ifunsetempty($_POST, 'lokasi', null),
			'v_start' => 0,
			'v_limit' => 1000000
		);
		$mresult = $this->m_report->getReportListKaderGroup($params);
		echo json_encode($mresult);
	}

	function getGraphByDivisi()
	{
		$this->m_pegawai->addlogs();
		$satkerid = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_report->statistikDivisi($satkerid);

		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function getReportListDivisi()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListDivisi($params);
		echo json_encode($mresult);
	}

	// Report By Status Pegawai
	function getGraphByStatusPegawai()
	{
		$this->m_pegawai->addlogs();
		$satkerid = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_report->statistikStatusPegawai($satkerid);
		echo json_encode($mresult);
	}

	function getReportListStatusPegawai()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_statuspegawaiid' => ifunsetempty($_POST, 'statuspegawaiid', 'null'),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);

		$mresult = $this->m_report->reportListStatusPegawai($params);
		echo json_encode($mresult);
	}

	function getGraphByJenisKelamin()
	{
		$satkerid = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_report->statistikJenisKelamin($satkerid);
		echo json_encode($mresult);
	}

	function getReportListJenisKelamin()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListJenisKelamin($params);
		echo json_encode($mresult);
	}

	function getReportSDM()
	{
		$this->m_pegawai->addlogs();
		$satkerid = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_report->getReportSDM($satkerid);
		echo json_encode($mresult);
	}

	function getReportListSDM()
	{
		$golongan = ifunsetempty($_POST, 'levelid', null);
		$v_golongan = '';
		if ($golongan == 'bod') {
			$v_golongan = 'bod';
		} else {
			if ($golongan == null) {
				$v_golongan = 'null';
			} else {
				$v_golongan = $golongan;
			}
		}
		$params = array(
			'v_golongan' => $v_golongan,
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListSdm($params);
		echo json_encode($mresult);
	}

	function getGraphByLocation()
	{
		$this->m_pegawai->addlogs();
		$mresult = $this->m_report->getGraphByLocation();
		echo json_encode($mresult);
	}

	function getReportListLocationByID()
	{
		$params = array(
			'v_lokasiid' => ifunsetempty($_POST, 'lokasiid', null),
		);
		$mresult = $this->m_report->getLokasiByID($params);
		echo json_encode($mresult);
	}

	function getReportListLocation()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_lokasiid' => ifunsetempty($_POST, 'lokasiid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListLocation($params);
		echo json_encode($mresult);
	}

	function getGraphByLevel()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
		);

		$mresult = $this->m_report->getGraphByLevel($params);
		echo json_encode($mresult);
	}

	function getReportListLevel()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_levelid' => ifunsetempty($_POST, 'levelid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListLevel($params);
		echo json_encode($mresult);
	}

	function getGraphByKetPegawai()
	{
		$this->m_pegawai->addlogs();
		$bulan = ifunsetempty($_POST, 'bulan', null);
		$bulan = $bulan == '' ? '-' : $bulan;

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_month' => $bulan,
			'v_years' => ifunsetempty($_POST, 'tahun', date("Y")),
		);

		$mresult = $this->m_report->getGraphByKetPegawai($params);
		echo json_encode($mresult);
	}

	function getReportListKetPegawai()
	{
		$bulan = ifunsetempty($_POST, 'bulan', null);
		$bulan = $bulan == '' ? '-' : $bulan;
		$ketstatus = ifunsetempty($_POST, 'ketstatus', null);
		$ketstatus = empty($ketstatus) ? null : ((int)$ketstatus);

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_month' => $bulan,
			'v_years' => ifunsetempty($_POST, 'tahun', date("Y")),
			'v_ketstatus' => $ketstatus,
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);

		$mresult = $this->m_report->getReportListKetPegawai($params);
		echo json_encode($mresult);
	}

	function getReportEndOfContract()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->getReportEndOfContract($params);
		echo json_encode($mresult);
	}

	function getReportListUsiaPegawai()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_labelid' => ifunsetempty($_POST, 'labelid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);

		$mresult = $this->m_report->reportListUsiaPegawai($params);
		echo json_encode($mresult);
	}

	function getGraphByUsiaPegawai()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01')
		);
		$mresult = $this->m_report->statistikUsiaPegawai($params);
		echo json_encode($mresult);
	}

	function getReportBudget()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null)
		);
		$mresult = $this->m_report->getReportBudget($params);
		echo json_encode($mresult);
	}

	function getReportLpj()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null)
		);
		$mresult = $this->m_report->getReportLpj($params);
		echo json_encode($mresult);
	}

	function getReportRealisasi()
	{
		$this->m_pegawai->addlogs();
		$params = array(
			'v_tahun' => date('Y')
		);
		$mresult = $this->m_report->getReportRealisasi($params);
		echo json_encode($mresult);
	}

	function cetak($opt)
	{
		$this->m_pegawai->addlogs();
		if ($opt == 'bydivisi') {
			$satkerid = '01';

			$path = config_item("siap_tpl_path");
			$filetemplate = "LISTREPORT.xlsx";

			$colorBG1 = 'ffffff';
			$colorBG2 = 'd9d9d9';

			$objPHPExcel = $this->cetak_phpexcel->loadTemplate($path . $filetemplate);
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
				->setLastModifiedBy("Maarten Balliauw")
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
				->setKeywords("office 2007 openxml php")
				->setCategory("Test result file");

			$objPHPExcel = new PHPExcel();

			$sharedStyle1 = new PHPExcel_Style();
			$sharedStyle1->applyFromArray(
				array(
					'font' => array(
						'name' => 'Calibri',
						'size' => '11',
						'bold' => true
					),
					'borders' => array(
						'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
				)
			);

			$sharedStyle2 = new PHPExcel_Style();
			$sharedStyle2->applyFromArray(
				array(
					'font' => array(
						'name' => 'Calibri',
						'size' => '11',
					),
					'borders' => array(
						'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
					),
					'alignment' => array(
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					)
				)
			);

			// Set di worksheet 2 untuk grafik
			$startbase = 2;
			$base = $startbase;
			$mresult = $this->m_report->statistikDivisi($satkerid);
			$tot = (sizeof($mresult['data']) + $base) - 1;

			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex(1);

			$objWorksheet = $objPHPExcel->getActiveSheet()->setTitle('Sheet2');
			foreach ($mresult['data'] as $row) {
				$objWorksheet->setCellValue('A' . $base, $row['satker'])
					->setCellValue('B' . $base, $row['jml']);
				$base++;
			}

			$objPHPExcel->setActiveSheetIndex(0);
			$objWorksheet = $objPHPExcel->getActiveSheet()->setTitle('Sheet1');

			$dataseriesLabels1 = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'Sheet2!$B$1', null, 1),
			);

			$xAxisTickValues1 = array(
				new PHPExcel_Chart_DataSeriesValues('String', 'Sheet2!$A$' . $startbase . ':' . '$A$' . $tot, null, 2),
			);

			$dataSeriesValues1 = array(
				new PHPExcel_Chart_DataSeriesValues('Number', 'Sheet2!$B$' . $startbase . ':' . '$B$' . $tot, null, 2),
			);

			$series1 = new PHPExcel_Chart_DataSeries(
				PHPExcel_Chart_DataSeries::TYPE_PIECHART,
				null,
				range(0, count($dataSeriesValues1) - 1),
				$dataseriesLabels1,
				$xAxisTickValues1,
				$dataSeriesValues1
			);

			$layout1 = new PHPExcel_Chart_Layout();
			$layout1->setShowVal(true);
			$layout1->setShowPercent(false);

			$plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
			$legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOPRIGHT, null, false);
			$title1 = new PHPExcel_Chart_Title('Jumlah Divisi');

			// Set di worksheet 1 untuk penempatan raw data

			$base = 1;
			$chart1 = new PHPExcel_Chart(
				'chart1',
				$title1,
				$legend1,
				$plotarea1,
				true,
				0,
				null,
				null
			);
			$chart1->setTopLeftPosition('A' . $base);
			$base = $base + 19;
			$chart1->setBottomRightPosition('H' . $base);

			$objWorksheet->addChart($chart1);

			$colomStart = 1;
			$base = $base + 1;

			$mresult2 = $this->m_report->reportListDivisi($satkerid);

			// echo json_encode($mresult2['data']);
			// exit;

			$objWorksheet->setSharedStyle($sharedStyle1, "A" . $base . ":R" . ($base + 1));
			$objWorksheet->getStyle('A' . $base . ':' . 'R' . ($base + 1))->getAlignment()->setWrapText(true);
			$objWorksheet->getColumnDimension('A')->setWidth(5);
			$objWorksheet->getColumnDimension('B')->setWidth(20);
			$objWorksheet->getColumnDimension('C')->setWidth(30);
			$objWorksheet->getColumnDimension('D')->setWidth(30);
			$objWorksheet->getColumnDimension('E')->setWidth(30);
			$objWorksheet->getColumnDimension('F')->setWidth(30);
			$objWorksheet->getColumnDimension('G')->setWidth(20);
			$objWorksheet->getColumnDimension('H')->setWidth(20);
			$objWorksheet->getColumnDimension('I')->setWidth(20);
			$objWorksheet->getColumnDimension('J')->setWidth(20);
			$objWorksheet->getColumnDimension('K')->setWidth(20);
			$objWorksheet->getColumnDimension('L')->setWidth(15);
			$objWorksheet->getColumnDimension('M')->setWidth(15);
			$objWorksheet->getColumnDimension('N')->setWidth(20);
			$objWorksheet->getColumnDimension('O')->setWidth(20);
			$objWorksheet->getColumnDimension('P')->setWidth(15);
			$objWorksheet->getColumnDimension('Q')->setWidth(20);
			$objWorksheet->getColumnDimension('R')->setWidth(20);

			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "No", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "NIK", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Nama", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Jabatan", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Level", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Title", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 0, $colomStart, 5, "Unit", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart - 5, 1, "Direktorat", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Divisi", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Departemen", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Seksi", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Sub Seksi", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Tanggal Masuk", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Tanggal Keluar", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Alasan Keluar", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Status Pegawai", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Jenis Kelamin", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Email", TRUE, $colorBG1);
			$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Telp", TRUE, $colorBG1);

			$base = $base + 2;
			$tot = (sizeof($mresult2['data']) + $base) - 1;

			$objWorksheet->setSharedStyle($sharedStyle2, "A" . $base . ":" . "R" . $tot);
			$objWorksheet->getStyle('A' . $base . ':' . 'R' . $tot)->getAlignment()->setWrapText(true);

			$no = 1;
			foreach ($mresult2['data'] as $row) {
				$objWorksheet->setCellValue('A' . $base, $no)
					->setCellValueExplicit('B' . $base, $row['nik'], PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('C' . $base, $row['nama'])
					->setCellValue('D' . $base, $row['jabatan'])
					->setCellValue('E' . $base, $row['level'])
					->setCellValue('F' . $base, '')
					->setCellValue('G' . $base, $row['direktorat'])
					->setCellValue('H' . $base, $row['divisi'])
					->setCellValue('I' . $base, $row['departemen'])
					->setCellValue('J' . $base, $row['seksi'])
					->setCellValue('K' . $base, $row['subseksi'])
					->setCellValue('L' . $base, $row['tglmulai'])
					->setCellValue('M' . $base, $row['tglselesai'])
					->setCellValue('N' . $base, $row['keterangan'])
					->setCellValue('O' . $base, $row['statuspegawai'])
					->setCellValue('P' . $base, $row['jeniskelamin'])
					->setCellValue('Q' . $base, $row['email'])
					->setCellValue('R' . $base, $row['telp']);
				$base++;
				$no++;
			}

			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', $filetemplate);
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $file_name . '"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->setIncludeCharts(TRUE);
			$objWriter->save('php://output');
		}
	}

	function cetakdokumen($opt)
	{
		$this->m_pegawai->addlogs();
		if ($opt == 'endofcontract') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportEndOfContract($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "ENDOFCONTRACT.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "ENDOFCONTRACT.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'statuspegawai') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_statuspegawaiid' => ifunsetemptybase64($_GET, 'statuspegawaiid', 'null'),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListStatusPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_STATUS_PEGAWAI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_STATUS_PEGAWAI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'jeniskelamin') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_jeniskelamin' => ifunsetemptybase64($_GET, 'jeniskelamin', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListJenisKelamin($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_GENDER.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_GENDER.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'ketstatus') {
			$bulan = ifunsetemptybase64($_GET, 'bulan', null);
			$bulan = $bulan == '' ? '-' : $bulan;
			$ketstatus = ifunsetemptybase64($_GET, 'ketstatus', null);
			$ketstatus = empty($ketstatus) ? null : ((int)$ketstatus);

			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_month' => $bulan,
				'v_years' => ifunsetemptybase64($_GET, 'tahun', date("Y")),
				'v_ketstatus' => $ketstatus,
				'v_start' => '0',
				'v_limit' => '1000000000'
			);

			$mresult = $this->m_report->getReportListKetPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_KETSTATUS.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_KETSTATUS.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'bylevel') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_levelid' => ifunsetemptybase64($_GET, 'levelid', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListLevel($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_LEVEL.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_LEVEL.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'bysdm') {
			$satkerid = ifunsetemptybase64($_GET, 'satkerid', '');
			$golongan = ifunsetemptybase64($_GET, 'levelid', null);
			$v_golongan = '';
			if ($golongan == 'bod') {
				$v_golongan = 'bod';
			} else {
				if ($golongan == null) {
					$v_golongan = 'null';
				} else {
					$v_golongan = $golongan;
				}
			}
			$params = array(
				'v_golongan' => $v_golongan,
				'v_satkerid' => $satkerid,
				'v_start' => 0,
				'v_limit' => 100000000000000
			);
			$mresult_peg = $this->m_report->reportListSdm($params);
			$mresult = $this->m_report->getReportSDM($satkerid);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_SDM.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$TBS->MergeBlock('rec_peg', $mresult_peg['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_SDM.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'bylocation') {
			$params = array(
				'v_lokasiid' => ifunsetemptybase64($_GET, 'lokasiid', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListLocation($params);
			$mresultid = $this->m_report->getLokasiByID($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_LOCATION.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec_id', $mresultid['data']);
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_LOCATION.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'bydivisi') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListDivisi($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_DIVISI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_DIVISI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'actingas') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);

			$mresult = $this->m_report->getReportActingAsBySatker($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_ACTINGAS.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec_as', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_ACTINGAS.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'mutasipromosi') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);

			$mresult = $this->m_report->getMutasiPromosi($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_MUTASIPROMOSI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec_as', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_MUTASIPROMOSI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportusia') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', '01'),
				'v_labelid' => ifunsetemptybase64($_GET, 'labelid', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListUsiaPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_USIA_PEGAWAI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_USIA_PEGAWAI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportkader') { //reportkader chunz
			$pegawaiid = ifunsetemptybase64($_GET, 'fingerid', null);
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', '01'),
				'v_levelid' => ifunsetemptybase64($_GET, 'level', null),
				'v_lokasiid' => ifunsetemptybase64($_GET, 'lokasi', null),
				'v_pegawaiid' => $pegawaiid,
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportListKader($params);
			$path = config_item('siap_upload_foto_path');
			$objPHPExcel = PHPExcel_IOFactory::load(config_item("siap_tpl_path") . "REPORT_KADER.xlsx"); //Template Asli jangan tiban

			//var_dump($mresult['data']);

			// Tambah Gambar Pegawai
			for ($img = 0; $img < count($mresult['data']); $img++) {
				$cell = "B" . (9 + (5 * $img));
				$foto = $mresult['data'][$img]['foto'];
				$image = '';
				if (!empty($foto)) {
					$image = $path . $foto;
				} else {
					$image = $path . 'no_image.jpg';
				}

				// Tambah Gambar Pegawai
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setPath($image);
				$objDrawing->setCoordinates($cell);
				$objDrawing->setResizeProportional(true);
				$objDrawing->setWidth(165);
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			}

			//var_dump($image);

			$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(config_item("siap_tpl_path") . "kader.xlsx");

			//Cetak Data to Excel
			$tempexcel = config_item("siap_tpl_path") . "kader.xlsx";
			$TBS = new clsTinyButStrong;
			$TBS->Plugin(TBS_INSTALL, 'clsOpenTBS');
			$TBS->LoadTemplate($tempexcel);
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('kader', 'REPORT_KADER_' . date('Y-m-d'), "kader.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportkadergroup') { //reportkadergroup chunz
			$pegawaiid = ifunsetemptybase64($_GET, 'fingerid', null);
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', '01'),
				'v_levelid' => ifunsetemptybase64($_GET, 'level', null),
				'v_lokasiid' => ifunsetemptybase64($_GET, 'lokasi', null),
				'v_pegawaiid' => $pegawaiid,
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportListKaderGroup($params);
			$path = config_item('siap_upload_foto_path');
			$objPHPExcel = PHPExcel_IOFactory::load(config_item("siap_tpl_path") . "REPORT_KADER_GROUP.xlsx"); //Template Asli jangan tiban

			//var_dump($mresult['data']);

			// Tambah Gambar Pegawai
			for ($img = 0; $img < count($mresult['data']); $img++) {
				$cell = "C" . (3 + $img);
				$foto = $mresult['data'][$img]['foto'];
				$image = '';
				if (!empty($foto)) {
					$image = $path . $foto;
				} else {
					$image = $path . 'no_image.jpg';
				}

				// Tambah Gambar Pegawai
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setPath($image);
				$objDrawing->setCoordinates($cell);
				$objDrawing->setResizeProportional(true);
				$objDrawing->setWidth(165);
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			}

			//var_dump($image);

			$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(config_item("siap_tpl_path") . "kadergroup.xlsx");

			//Cetak Data to Excel
			$tempexcel = config_item("siap_tpl_path") . "kadergroup.xlsx";
			$TBS = new clsTinyButStrong;
			$TBS->Plugin(TBS_INSTALL, 'clsOpenTBS');
			$TBS->LoadTemplate($tempexcel);
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('kadergroup', 'REPORT_KADER_' . date('Y-m-d'), "kadergroup.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportkaderold') {
			$pegawaiid = ifunsetemptybase64($_GET, 'fingerid', null);

			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', '01'),
				'v_levelid' => ifunsetemptybase64($_GET, 'level', null),
				'v_lokasiid' => ifunsetemptybase64($_GET, 'lokasi', null),
				'v_pegawaiid' => $pegawaiid,
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportListKader($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_KADER_OLD.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_KADER_OLD.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportrealisasi') {
			$pegawaiid = ifunsetemptybase64($_GET, 'fingerid', null);

			$params = array(
				'v_tahun' => '2021',
			);
			$mresult = $this->m_report->getReportRealisasi($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_PERDIN_REALISASI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_PERDIN_REALISASI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'ulangtahun') {

			$month = date('m');
			$bulan = ifunsetemptybase64($_GET, 'bulan', 0);
			$hari = ifunsetemptybase64($_GET, 'hari', 0);

			$a = '';
			if ($bulan != '') {
				$a = $bulan;
			} else {
				$a = $month;
			}

			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_hari' => ifunsetemptybase64($_GET, 'hari', '01'),
				'v_bulan' => $a,
				'v_start' => ifunsetemptybase64($_GET, 'start', 0),
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportUlangtahun($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_ULANG_TAHUN.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_ULANG_TAHUN.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		}
	}

	function ary()
	{
		$satkerid = ifunsetempty($_POST, 'satkerid', '01');

		$path = config_item("siap_tpl_path");
		$filetemplate = 'STATISTIK_GRUANG.DOCX';

		$ChartNameOrNum = 'chart1';

		$template = basename($path . $filetemplate);
		$TBS = $this->template_cetak->createNew('', $path . $filetemplate);
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$TBS->LoadTemplate($path . $filetemplate);

		$mresult = $this->m_report->statistikDivisi($satkerid);

		$data = array();
		$i = 0;
		$label = array();
		$values = array();
		foreach ($mresult['data'] as $r) {
			$label[] = $r['satker'];
			$values[] = (int)$r['jml'];
			$i++;
		}
		$NewValues = array($label, $values);
		// echo json_encode($NewValues);
		// exit;

		$TBS->PlugIn(OPENTBS_CHART, $ChartNameOrNum, 1, $NewValues);
		$TBS->Show(OPENTBS_DOWNLOAD, $filetemplate);
	}

	function testing()
	{
		$path = config_item("siap_tpl_path");
		$filetemplate = 'STATISTIK_GRUANG.DOCX';

		$ChartNameOrNum = 'chart1';

		$keys = array('I', 'II', 'III', 'IV');
		$satker = array("Kepala BKPM", "Sekretariat Utama", "Inspektorat", "Pusat Pendidikan dan Pelatihan", "Pusat Pengolahan Data dan Informasi", "Pusat Bantuan Hukum", "Deputi Bidang Perencanaan Penanaman Modal", "Deputi Bidang Pengembangan Iklim Penanaman Modal", "Deputi Bidang Promosi Penanaman Modal");
		$values = array(
			"I" => array(0, 1, 0, 0, 0, 0, 0, 0, 0),
			"II" => array(0, 14, 0, 2, 1, 0, 0, 1, 3),
			"III" => array(0, 94, 10, 30, 26, 7, 41, 38, 60),
			"IV" => array(0, 10, 3, 5, 2, 3, 18, 12, 23),
		);

		$template = basename($path . $filetemplate);
		$TBS = $this->template_cetak->createNew('', $path . $filetemplate);
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$TBS->LoadTemplate($path . $filetemplate, OPENTBS_ALREADY_UTF8);

		$NewValues = array(
			array("Sub Business Unit Compliance", "HRGA Finance Accounting", "Special Project Market Development", "Operation", "IT MIS", "Business Development", "Marketing"),
			array(15, 12, 0, 0, 0, 0, 0)
		);

		// echo json_encode($NewValues);
		// exit;

		$TBS->PlugIn(OPENTBS_CHART, $ChartNameOrNum, 1, $NewValues);
		$TBS->Show(OPENTBS_DOWNLOAD, $filetemplate);
	}

	function ary_excel()
	{
		$path = config_item("siap_tpl_path");
		$filetemplate = "cetak_daftar_pegawai.xlsx";

		$colorBG1 = 'ffffff';
		$colorBG2 = 'd9d9d9';

		$objPHPExcel = $this->cetak_phpexcel->loadTemplate($path . $filetemplate);
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
			->setLastModifiedBy("Maarten Balliauw")
			->setTitle("Office 2007 XLSX Test Document")
			->setSubject("Office 2007 XLSX Test Document")
			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			->setKeywords("office 2007 openxml php")
			->setCategory("Test result file");

		$objPHPExcel = new PHPExcel();

		$sharedStyle1 = new PHPExcel_Style();
		$sharedStyle1->applyFromArray(
			array(
				'font' => array(
					'name' => 'Calibri',
					'size' => '11',
					'bold' => true
				),
				'borders' => array(
					'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			)
		);

		$sharedStyle2 = new PHPExcel_Style();
		$sharedStyle2->applyFromArray(
			array(
				'font' => array(
					'name' => 'Calibri',
					'size' => '11',
				),
				'borders' => array(
					'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			)
		);

		// Set di worksheet 2 untuk grafik
		$base = 1;
		$mresult = $this->m_report->testingData2();
		// echo json_encode($mresult);
		// exit;

		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1);

		$objWorksheet = $objPHPExcel->getActiveSheet()->setTitle('Sheet2');
		foreach ($mresult as $row) {
			$objWorksheet->setCellValue('A' . $base, $row['satker'])
				->setCellValue('B' . $base, $row['jml']);
			$base++;
		}

		$tot = (sizeof($mresult) + $base) - 1;

		$objPHPExcel->setActiveSheetIndex(0);
		$objWorksheet = $objPHPExcel->getActiveSheet()->setTitle('Sheet1');

		$dataseriesLabels1 = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Sheet2!$B$1', null, 1),
		);

		$xAxisTickValues1 = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Sheet2!$A$2:$A$' . $tot, null, 2),
		);

		$dataSeriesValues1 = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Sheet2!$B$2:$B$' . $tot, null, 2),
		);

		$series1 = new PHPExcel_Chart_DataSeries(
			PHPExcel_Chart_DataSeries::TYPE_PIECHART,
			null,
			range(0, count($dataSeriesValues1) - 1),
			$dataseriesLabels1,
			$xAxisTickValues1,
			$dataSeriesValues1
		);

		$layout1 = new PHPExcel_Chart_Layout();
		$layout1->setShowVal(true);
		$layout1->setShowPercent(false);

		$plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
		$legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOPRIGHT, null, false);
		$title1 = new PHPExcel_Chart_Title('Jumlah Divisi');

		// Set di worksheet 1 untuk penempatan raw data

		$base = 1;
		$chart1 = new PHPExcel_Chart(
			'chart1',
			$title1,
			$legend1,
			$plotarea1,
			true,
			0,
			null,
			null
		);
		$chart1->setTopLeftPosition('A' . $base);
		$base = $base + 19;
		$chart1->setBottomRightPosition('H' . $base);

		$objWorksheet->addChart($chart1);

		$colomStart = 1;
		$base = $base + 1;

		$mresult2 = $this->m_report->testingData();

		$objWorksheet->setSharedStyle($sharedStyle1, "A" . $base . ":R" . ($base + 1));
		$objWorksheet->getStyle('A' . $base . ':' . 'R' . ($base + 1))->getAlignment()->setWrapText(true);
		$objWorksheet->getColumnDimension('A')->setWidth(5);
		$objWorksheet->getColumnDimension('B')->setWidth(20);
		$objWorksheet->getColumnDimension('C')->setWidth(30);
		$objWorksheet->getColumnDimension('D')->setWidth(30);
		$objWorksheet->getColumnDimension('E')->setWidth(30);
		$objWorksheet->getColumnDimension('F')->setWidth(30);
		$objWorksheet->getColumnDimension('G')->setWidth(20);
		$objWorksheet->getColumnDimension('H')->setWidth(20);
		$objWorksheet->getColumnDimension('I')->setWidth(20);
		$objWorksheet->getColumnDimension('J')->setWidth(20);
		$objWorksheet->getColumnDimension('K')->setWidth(20);
		$objWorksheet->getColumnDimension('L')->setWidth(15);
		$objWorksheet->getColumnDimension('M')->setWidth(15);
		$objWorksheet->getColumnDimension('N')->setWidth(20);
		$objWorksheet->getColumnDimension('O')->setWidth(20);
		$objWorksheet->getColumnDimension('P')->setWidth(15);
		$objWorksheet->getColumnDimension('Q')->setWidth(20);
		$objWorksheet->getColumnDimension('R')->setWidth(20);

		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "No", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "NIK", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Nama", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Jabatan", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Level", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Title", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 0, $colomStart, 5, "Unit", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart - 5, 1, "Direktorat", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Divisi", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Departemen", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Seksi", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Sub Seksi", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Tanggal Masuk", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Tanggal Keluar", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Alasan Keluar", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Status Pegawai", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Jenis Kelamin", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Email", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Telp", TRUE, $colorBG1);

		$base = $base + 2;
		$tot = (sizeof($mresult2) + $base) - 1;

		$objWorksheet->setSharedStyle($sharedStyle2, "A" . $base . ":" . "R" . $tot);
		$objWorksheet->getStyle('A' . $base . ':' . 'R' . $tot)->getAlignment()->setWrapText(true);

		$no = 1;
		foreach ($mresult2 as $row) {
			$objWorksheet->setCellValue('A' . $base, $no)
				->setCellValueExplicit('B' . $base, $row['nik'], PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('C' . $base, $row['nama'])
				->setCellValue('D' . $base, $row['statuspegawai'])
				->setCellValue('E' . $base, $row['jabatan'])
				->setCellValue('F' . $base, $row['satker']);
			$base++;
			$no++;
		}

		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', $filetemplate);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $file_name . '"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
	}

	function ary_excel2()
	{
		$satkerid = '01';

		$path = config_item("siap_tpl_path");
		$filetemplate = "cetak_daftar_pegawai.xlsx";

		$colorBG1 = 'ffffff';
		$colorBG2 = 'd9d9d9';

		$objPHPExcel = $this->cetak_phpexcel->loadTemplate($path . $filetemplate);
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
			->setLastModifiedBy("Maarten Balliauw")
			->setTitle("Office 2007 XLSX Test Document")
			->setSubject("Office 2007 XLSX Test Document")
			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			->setKeywords("office 2007 openxml php")
			->setCategory("Test result file");

		$objPHPExcel = new PHPExcel();

		$sharedStyle1 = new PHPExcel_Style();
		$sharedStyle1->applyFromArray(
			array(
				'font' => array(
					'name' => 'Calibri',
					'size' => '11',
					'bold' => true
				),
				'borders' => array(
					'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			)
		);

		$sharedStyle2 = new PHPExcel_Style();
		$sharedStyle2->applyFromArray(
			array(
				'font' => array(
					'name' => 'Calibri',
					'size' => '11',
				),
				'borders' => array(
					'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
				)
			)
		);

		// Set di worksheet 2 untuk grafik
		$startbase = 2;
		$base = $startbase;
		$mresult = $this->m_report->statistikDivisi($satkerid);
		$tot = (sizeof($mresult['data']) + $base) - 1;

		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1);

		$objWorksheet = $objPHPExcel->getActiveSheet()->setTitle('Sheet2');
		foreach ($mresult['data'] as $row) {
			$objWorksheet->setCellValue('A' . $base, $row['satker'])
				->setCellValue('B' . $base, $row['jml']);
			$base++;
		}

		$objPHPExcel->setActiveSheetIndex(0);
		$objWorksheet = $objPHPExcel->getActiveSheet()->setTitle('Sheet1');

		$dataseriesLabels1 = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Sheet2!$B$1', null, 1),
		);

		$xAxisTickValues1 = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Sheet2!$A$' . $startbase . ':' . '$A$' . $tot, null, 2),
		);

		$dataSeriesValues1 = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Sheet2!$B$' . $startbase . ':' . '$B$' . $tot, null, 2),
		);

		$series1 = new PHPExcel_Chart_DataSeries(
			PHPExcel_Chart_DataSeries::TYPE_PIECHART,
			null,
			range(0, count($dataSeriesValues1) - 1),
			$dataseriesLabels1,
			$xAxisTickValues1,
			$dataSeriesValues1
		);

		$layout1 = new PHPExcel_Chart_Layout();
		$layout1->setShowVal(true);
		$layout1->setShowPercent(false);

		$plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
		$legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOPRIGHT, null, false);
		$title1 = new PHPExcel_Chart_Title('Jumlah Divisi');

		// Set di worksheet 1 untuk penempatan raw data

		$base = 1;
		$chart1 = new PHPExcel_Chart(
			'chart1',
			$title1,
			$legend1,
			$plotarea1,
			true,
			0,
			null,
			null
		);
		$chart1->setTopLeftPosition('A' . $base);
		$base = $base + 19;
		$chart1->setBottomRightPosition('H' . $base);

		$objWorksheet->addChart($chart1);

		$colomStart = 1;
		$base = $base + 1;

		$mresult2 = $this->m_report->reportListDivisi($satkerid);

		// echo json_encode($mresult2['data']);
		// exit;

		$objWorksheet->setSharedStyle($sharedStyle1, "A" . $base . ":R" . ($base + 1));
		$objWorksheet->getStyle('A' . $base . ':' . 'R' . ($base + 1))->getAlignment()->setWrapText(true);
		$objWorksheet->getColumnDimension('A')->setWidth(5);
		$objWorksheet->getColumnDimension('B')->setWidth(20);
		$objWorksheet->getColumnDimension('C')->setWidth(30);
		$objWorksheet->getColumnDimension('D')->setWidth(30);
		$objWorksheet->getColumnDimension('E')->setWidth(30);
		$objWorksheet->getColumnDimension('F')->setWidth(30);
		$objWorksheet->getColumnDimension('G')->setWidth(20);
		$objWorksheet->getColumnDimension('H')->setWidth(20);
		$objWorksheet->getColumnDimension('I')->setWidth(20);
		$objWorksheet->getColumnDimension('J')->setWidth(20);
		$objWorksheet->getColumnDimension('K')->setWidth(20);
		$objWorksheet->getColumnDimension('L')->setWidth(15);
		$objWorksheet->getColumnDimension('M')->setWidth(15);
		$objWorksheet->getColumnDimension('N')->setWidth(20);
		$objWorksheet->getColumnDimension('O')->setWidth(20);
		$objWorksheet->getColumnDimension('P')->setWidth(15);
		$objWorksheet->getColumnDimension('Q')->setWidth(20);
		$objWorksheet->getColumnDimension('R')->setWidth(20);

		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "No", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "NIK", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Nama", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Jabatan", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Level", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Title", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 0, $colomStart, 5, "Unit", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart - 5, 1, "Direktorat", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Divisi", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Departemen", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Seksi", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base + 1, 0, $colomStart, 1, "Sub Seksi", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Tanggal Masuk", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Tanggal Keluar", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Alasan Keluar", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Status Pegawai", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Jenis Kelamin", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Email", TRUE, $colorBG1);
		$colomStart = $this->cetak_phpexcel->createHeader($objWorksheet, $base, 1, $colomStart, 1, "Telp", TRUE, $colorBG1);

		$base = $base + 2;
		$tot = (sizeof($mresult2['data']) + $base) - 1;

		$objWorksheet->setSharedStyle($sharedStyle2, "A" . $base . ":" . "R" . $tot);
		$objWorksheet->getStyle('A' . $base . ':' . 'R' . $tot)->getAlignment()->setWrapText(true);

		$no = 1;
		foreach ($mresult2['data'] as $row) {
			$objWorksheet->setCellValue('A' . $base, $no)
				->setCellValueExplicit('B' . $base, $row['nik'], PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('C' . $base, $row['nama'])
				->setCellValue('D' . $base, $row['jabatan'])
				->setCellValue('E' . $base, $row['level'])
				->setCellValue('F' . $base, '')
				->setCellValue('G' . $base, $row['direktorat'])
				->setCellValue('H' . $base, $row['divisi'])
				->setCellValue('I' . $base, $row['departemen'])
				->setCellValue('J' . $base, $row['seksi'])
				->setCellValue('K' . $base, $row['subseksi'])
				->setCellValue('L' . $base, $row['tglmulai'])
				->setCellValue('M' . $base, $row['tglselesai'])
				->setCellValue('N' . $base, $row['keterangan'])
				->setCellValue('O' . $base, $row['statuspegawai'])
				->setCellValue('P' . $base, $row['jeniskelamin'])
				->setCellValue('Q' . $base, $row['email'])
				->setCellValue('R' . $base, $row['telp']);
			$base++;
			$no++;
		}

		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', $filetemplate);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $file_name . '"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
	}

	function download()
	{
		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "format_budget.xlsx");
		$TBS->MergeField('header', array());
		$file_name = str_replace('.', '.', "format_budget.xlsx");
		$file_name = str_replace('.', '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}
}
