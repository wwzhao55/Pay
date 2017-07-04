<?php 
require 'medoo.php';
require 'send.php';
require 'upload.php';
class api{
	protected $config;	
	protected $message;
	protected $upload;
	protected $db;
	protected $excel;

	public function __construct(){
		$this->config = require 'config.php';	
	}

	public function get_page_config(){
		return $this->config['PAGE'];
	}

	public function get_db(){
		$this->db = new medoo([
		    'database_type' => 'mysql',
		    'database_name' => $this->config['DB_NAME'],
		    'server' => $this->config['DB_HOST'],
		    'username' => $this->config['DB_USER'],
		    'password' => $this->config['DB_PASSWORD'],
		    'charset' => 'utf8'
		]); 

		return $this->db;
		try{
			$this->db = new medoo([
			// 必须配置项
		    'database_type' => 'mysql',
		    'database_name' => $db_name,
		    'server' => DB_HOST,
		    'username' => DB_USER,
		    'password' => DB_PASSWORD,
		    'charset' => 'utf8',
			]);
		}catch(Exception $e) {
			$this->db = null;
		}
	}

	public function get_message(){
		$this->message = new send();
		return $this->message;
	}

	public function get_upload(){
		$this->upload = new FileUpload($this->config['UPLOAD']);
		return $this->upload;
	}

	public function get_excel($title,$data){


        require_once 'phpExcel/PHPExcel.php';	  
	    // Create new PHPExcel object    
	    $objPHPExcel = new PHPExcel();  
	    // Set properties    
	    $objPHPExcel->getProperties()->setCreator("ctos")  
	            ->setLastModifiedBy("ctos")  
	            ->setTitle("Office 2007 XLSX Test Document")  
	            ->setSubject("Office 2007 XLSX Test Document")  
	            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
	            ->setKeywords("office 2007 openxml php")  
	            ->setCategory("Test result file");  
	  
	    // set width    
	    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);  
	    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);  
	    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);  
	    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); 
	    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10); 
	  
	    // 设置行高度    
	    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
	  
	    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
	  
	    // 字体和样式  
	    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
	    $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);  
	    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
	  
	    $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
	  
	    // 设置水平居中    
	    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
	    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	  
	    //  合并  
	    $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');  
	  
	    // 表头  
	    $objPHPExcel->setActiveSheetIndex(0)  
	            ->setCellValue('A1', $title)  
	            ->setCellValue('A2', '序号')  
	            ->setCellValue('B2', '姓名') 
	            ->setCellValue('C2', '时间')
	            ->setCellValue('D2', '手机号') 
	            ->setCellValue('E2', '身份证正面图片')
	            ->setCellValue('F2', '身份证背面图片')  
	            ->setCellValue('G2', '所选BD')  
	            ->setCellValue('H2', '优先级');  
	              
	  
	    // 内容  
	    for ($i = 0, $len = count($data); $i < $len; $i++) {  
	        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $i+1);  
	        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $data[$i]['name']); 
	        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), date('Y-m-d H:i:s',$data[$i]['created_at'])); 
	        $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $data[$i]['mobile']);  
	        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), $data[$i]['ID_card_front']); 
	        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), $data[$i]['ID_card_back']);  
	        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 3), $data[$i]['BD']);  
	        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 3), $data[$i]['level']);  
	        

	        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':H' . ($i + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
	        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':H' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
	        $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);  
	    }  
	  
	    // Rename sheet    
	    $objPHPExcel->getActiveSheet()->setTitle($title);  
	  
	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
	    $objPHPExcel->setActiveSheetIndex(0);  
	  
	    // 输出  
	    header('Content-Type: application/vnd.ms-excel');  
	    header('Content-Disposition: attachment;filename="' . $title . '.xls"');  
	    header('Cache-Control: max-age=0');  
	  
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
	    $objWriter->save('php://output'); 
	}
}