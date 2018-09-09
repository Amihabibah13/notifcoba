<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	

    function __construct()
    {
        parent::__construct();
       
        $this->load->model("Mdatasms");


    }
	public function index()
	{


		$data['sms']=$this->Mdatasms->allsms();
		
        $this->load->library('excel');
       
        $this->excel->setActiveSheetIndex(0);
       
        $this->excel->getActiveSheet()->setTitle('Users list');
 
        
        $this->load->database();
 
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(50);

		// Mergecell, menyatukan beberapa kolom
		$this->excel->getActiveSheet()->mergeCells('A1:E1');
		$this->excel->getActiveSheet()->mergeCells('A2:E2');

		$SI = $this->excel->setActiveSheetIndex(0);
		$SI->setCellValue('A1', 'Data-data siswa'); //Judul laporan
		$SI->setCellValue('A3', 'No'); //Kolom No
		$SI->setCellValue('B3', 'Nama Marketing'); //Kolom Nama
		$SI->setCellValue('C3', 'No PIC'); //Kolom Alamat
		$SI->setCellValue('D3', 'Outbox');
		$SI->setCellValue('E3', 'Inbox'); //Kolom Telp

		$headerStylenya = new PHPExcel_Style();
$bodyStylenya   = new PHPExcel_Style();

$headerStylenya->applyFromArray(
	array('fill' 	=> array(
		  'type'    => PHPExcel_Style_Fill::FILL_SOLID,
		  'color'   => array('argb' => 'FFEEEEEE')),
		  'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
	));
	
$bodyStylenya->applyFromArray(
	array('fill' 	=> array(
		  'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
		  'color'	=> array('argb' => 'FFFFFFFF')),
		  'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
    ));

$users = $this->Mdatasms->allsms();
 		
$baris  = 4; //Ini untuk dimulai baris datanya, karena di baris 3 itu digunakan untuk header tabel
$no     = 0;
$no_tujuan=1;
$jum = 1;
$jum_notujuan=1;
for ($i=0; $i <count($users) ; $i++){


	if($jum <= 1) {

	 		$n=$baris+$users[$i]['jumlah']-1;
	        $this->excel->getActiveSheet()->mergeCells('B'.$baris.':B'.$n);
	        $this->excel->getActiveSheet()->mergeCells('A'.$baris.':A'.$n);
	        $jum = $users[$i]['jumlah'];       
	         $no++;                   
	} else {
	        $jum = $jum - 1;
	}

	if($jum_notujuan <= 1) {

	 		$n_tujuan=$baris+$users[$i]['jumlah_notujuan']-1;
	        $this->excel->getActiveSheet()->mergeCells('C'.$baris.':C'.$n_tujuan);
	        $jum_notujuan = $users[$i]['jumlah_notujuan'];       
	                          
	} else {
	        $jum_notujuan = $jum_notujuan - 1;
	}
    

	
		
	
  $SI->setCellValue("A".$baris,$no);
  $SI->setCellValue("B".$baris,$users[$i]['nik']);
  $SI->setCellValue("C".$baris,$users[$i]['notujuan']); 
  if ($users[$i]['status'] == 'masuk') {
    	$SI->setCellValue("D".$baris,$users[$i]['pesan']); 
    }else{
    	$SI->setCellValue("E".$baris,$users[$i]['pesan']); 
    }
  
  
  $baris++; //looping untuk barisnya
}
//Menggunakan HeaderStylenya
$this->excel->getActiveSheet()->getStyle('D1:D'.$this->excel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
$this->excel->getActiveSheet()->getStyle('E1:E'.$this->excel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
$this->excel->getActiveSheet()->setSharedStyle($headerStylenya, "A3:E3");

		 
 
        $filename='just_some_random_name.xls'; //save our workbook as this file name
 
        header('Content-Type: application/vnd.ms-excel'); //mime type
 
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
 
        header('Cache-Control: max-age=0'); //no cache
                    
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as.XLSX Excel 2007 format
 
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');



		$this->load->view('sms',$data);
	}
}
