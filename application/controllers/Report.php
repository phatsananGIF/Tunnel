<?php
class Report extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        $rs=array();
        $Daterange="";
        $valueSearch="";
        $status="";


        if( $this->input->post("btsearchRe")!=null){

            $valueSearch=$this->input->post("valueSearch");
            $status = $this->input->post("statusRe");

            $Daterange = $this->input->post('reportrange');
            $sss = strpos($Daterange,"-");
            $start_time = trim(substr($Daterange, 0, $sss));
            $end_time = trim(substr($Daterange, $sss+1));



            if($status == 1){//Down
                
                
                $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup
                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                HAVING (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."'
                and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."') ");
    

                $rsdown = $this->db->query($query);
                $rs = $rsdown->result_array();

                //print_r($this->db->last_query());

            }else if($status == 2){//Inactive-Active


                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup, flag,
                SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time

                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                HAVING (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) ");
    
                $rsUpdown = $this->db->query($query2);
                $rs = $rsUpdown->result_array();

            }else if($status == 3){//All


                $query = ("  SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup, flag,
                            if(tunup = '0000-00-00 00:00:00', 'Inactive', 'Inactive-Active') AS status,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time

                            FROM tunnel_log
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            HAVING (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                            or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                            and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."'
                            and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."' and flag != 'SKIP' ) 
                        
                        ORDER BY tundown ");
        
                $rsAll = $this->db->query($query);
                $rs = $rsAll->result_array();

                                
            }

        }//bt-Search


        if( $this->input->post("btn_exportRe")!=null){
  
            $fname = "";
            
            $valueSearch=$this->input->post("valueSearch");
            $status = $this->input->post("statusRe");

            $Daterange = $this->input->post('reportrange');
            $sss = strpos($Daterange,"-");
            $start_time = trim(substr($Daterange, 0, $sss));
            $end_time = trim(substr($Daterange, $sss+1));



            if($status == 1){//Down
                $fname = "Inactive";
                
                $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup
                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                HAVING (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."'
                and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."') ");
    

                $rsdown = $this->db->query($query);
                $rs = $rsdown->result_array();

                //print_r($this->db->last_query());

            }else if($status == 2){//Inactive-Active
                $fname = "Inactive-Active";

                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup, flag,
                SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time

                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                HAVING (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) ");
    
                $rsUpdown = $this->db->query($query2);
                $rs = $rsUpdown->result_array();

            }else if($status == 3){//All
                $fname = "All";

                $query = ("  SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup, flag,
                            if(tunup = '0000-00-00 00:00:00', 'Down', 'Up-down') AS status,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time

                            FROM tunnel_log
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            HAVING (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                            or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                            and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."'
                            and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."' and flag != 'SKIP' ) 
                       
                        ORDER BY tundown ");
        
                $rsAll = $this->db->query($query);
                $rs = $rsAll->result_array();

                                
            }

            $this->load->library('excel');    
            $objPHPExcel = new PHPExcel();   
            
            $objPHPExcel->getProperties()->setCreator("embes.com")  
                                        ->setLastModifiedBy("embes.com")  
                                        ->setTitle("Excel Document")  
                                        ->setSubject("Excel Document")  
                                        ->setDescription("Document for Excel")  
                                        ->setKeywords("office PHPExcel php")  
                                        ->setCategory("Result file");      
            $objPHPExcel->getActiveSheet()->setTitle('Tunnel Report');  
            $objPHPExcel->setActiveSheetIndex(0);        
            $objPHPExcel->getDefaultStyle()  
                                    ->getAlignment()  
                                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)  
                                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);   
                                    
            // จัดความกว้างของคอลัมน์
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);     
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);      
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);  
                                                      
            
            // กำหนดหัวข้อให้กับแถวแรก
            if($fname == 'Inactive'){
                $objPHPExcel->setActiveSheetIndex(0)  
                ->setCellValue('A1', 'No.')    
                ->setCellValue('B1', 'CID')  
                ->setCellValue('C1', 'Tunnel')  
                ->setCellValue('D1', 'Host') 
                ->setCellValue('E1', 'Hostname') 
                ->setCellValue('F1', 'Tunnel down');    

            }else if($fname == 'Inactive-Active'){
                $objPHPExcel->setActiveSheetIndex(0)  
                ->setCellValue('A1', 'No.')    
                ->setCellValue('B1', 'CID')  
                ->setCellValue('C1', 'Tunnel')  
                ->setCellValue('D1', 'Host') 
                ->setCellValue('E1', 'Hostname') 
                ->setCellValue('F1', 'Tunnel down') 
                ->setCellValue('G1', 'Tunnel Up') 
                ->setCellValue('H1', 'Total time');    

            }else if($fname == 'All'){
                $objPHPExcel->setActiveSheetIndex(0)  
                ->setCellValue('A1', 'No.')    
                ->setCellValue('B1', 'CID')  
                ->setCellValue('C1', 'Tunnel')  
                ->setCellValue('D1', 'Host') 
                ->setCellValue('E1', 'Hostname') 
                ->setCellValue('F1', 'Tunnel down') 
                ->setCellValue('G1', 'Tunnel Up') 
                ->setCellValue('H1', 'Total time') 
                ->setCellValue('I1', 'Status');      

            }

            // ดึงข้อมูลเริ่มเพิ่มแถวที่ 2 ของ excel            
            $start_row=2;  
            $result = $rs;
            $i_num = 0;
            if(count($result)>=0){
                foreach($result as $row){
                    $i_num++;

                    // เพิ่มข้อมูลลงแต่ละเซลล์    
                    if($fname == 'Inactive'){
                        $objPHPExcel->setActiveSheetIndex(0)  
                        ->setCellValue('A'.$start_row, $i_num)  
                        ->setCellValue('B'.$start_row, $row['cid'])  
                        ->setCellValue('C'.$start_row, $row['tunnel'])  
                        ->setCellValue('D'.$start_row, $row['host']) 
                        ->setCellValue('E'.$start_row, $row['hostname']) 
                        ->setCellValue('F'.$start_row, $row['tundown']);

                    }else if($fname == 'Inactive-Active'){
                        $objPHPExcel->setActiveSheetIndex(0)  
                        ->setCellValue('A'.$start_row, $i_num)  
                        ->setCellValue('B'.$start_row, $row['cid'])  
                        ->setCellValue('C'.$start_row, $row['tunnel'])  
                        ->setCellValue('D'.$start_row, $row['host']) 
                        ->setCellValue('E'.$start_row, $row['hostname']) 
                        ->setCellValue('F'.$start_row, $row['tundown']) 
                        ->setCellValue('G'.$start_row, $row['tunup']) 
                        ->setCellValue('H'.$start_row, $row['total_time']);

                    }else if($fname == 'All'){
                        $objPHPExcel->setActiveSheetIndex(0)  
                        ->setCellValue('A'.$start_row, $i_num)  
                        ->setCellValue('B'.$start_row, $row['cid'])  
                        ->setCellValue('C'.$start_row, $row['tunnel'])  
                        ->setCellValue('D'.$start_row, $row['host']) 
                        ->setCellValue('E'.$start_row, $row['hostname']) 
                        ->setCellValue('F'.$start_row, $row['tundown']) 
                        ->setCellValue('G'.$start_row, $row['tunup']) 
                        ->setCellValue('H'.$start_row, $row['total_time']) 
                        ->setCellValue('I'.$start_row, $row['status']);  

                    }               

                    $start_row++;               
                }
        
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  // Excel2007 (xlsx) หรือ Excel5 (xls)        
                
                $filename='Tunnel('.$fname.')-'.date("dmYHis").'.xlsx'; //  กำหนดชือ่ไฟล์ นามสกุล xls หรือ xlsx
                // บังคับให้ทำการดาวน์ดหลดไฟล์
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                ob_end_clean();     
                $objWriter->save('php://output'); // ดาวน์โหลดไฟล์รายงาน
                // หากต้องการบันทึกเป็นไฟล์ไว้ใน server  ใช้คำสั่งนี้ $this->excel->save("/path/".$filename); 
                // แล้วตัด header ดัานบนทั้ง 3 อันออก   
            }



        }//bt-Export


        if( $this->input->post("btn_exportCSV")!=null){

            $fname = "";
            
            $valueSearch=$this->input->post("valueSearch");
            $status = $this->input->post("statusRe");

            $Daterange = $this->input->post('reportrange');
            $sss = strpos($Daterange,"-");
            $start_time = trim(substr($Daterange, 0, $sss));
            $end_time = trim(substr($Daterange, $sss+1));

            if($status == 1){//Down
                $fname = "Inactive";
                
                $query  = (" SELECT cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown
                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                where (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."'
                and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."') ");
    

                $rsdown = $this->db->query($query);
                $rs = $rsdown->result_array();

                //print_r($this->db->last_query());

            }else if($status == 2){//Inactive-Active
                $fname = "Inactive-Active";

                $query2  = (" SELECT cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time

                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                where (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) ");
    
                $rsUpdown = $this->db->query($query2);
                $rs = $rsUpdown->result_array();

            }else if($status == 3){//All
                $fname = "All";

                $query = ("  SELECT cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,
                            if(tunup = '0000-00-00 00:00:00', 'Down', 'Up-down') AS status
                            FROM tunnel_log
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            where (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                            or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                            and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."'
                            and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."' and flag != 'SKIP' ) 
                       
                        ORDER BY tundown ");
        
                $rsAll = $this->db->query($query);
                $rs = $rsAll->result_array();

                                
            }
            //$data[] = array('CID', 'Tunnel', 'Host', 'Hostname', 'Tunnel down');
            if($fname == "Inactive"){
                $data[] = array('CID', 'Tunnel', 'Host', 'Hostname', 'Tunnel down');

            }else if($fname == "Inactive-Active"){
                $data[] = array('CID', 'Tunnel', 'Host', 'Hostname', 'Tunnel down', 'Tunnel Up', 'Total time');

            }else if($fname == "All"){
                $data[] = array('CID', 'Tunnel', 'Host', 'Hostname', 'Tunnel down', 'Tunnel Up', 'Total time', 'Status');
                
            }

          
            $data=array_merge($data, $rs);
            //print_r($data);


            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Tunnel(".$fname.")-".date('dmYHis').".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            $handle = fopen('php://output', 'w');

            foreach ($data as $data) {
                fputcsv($handle, $data);
            }
                fclose($handle);
            exit;

        }//bt-csv



        $queryHost =(" SELECT * FROM router ");
        $rsqueryHost = $this->db->query($queryHost);
        $rsqueryHost = $rsqueryHost->result_array();

        $queryTunnel =(" SELECT * FROM tunnel_list ");
        $rsqueryTunnel = $this->db->query($queryTunnel);
        $rsqueryTunnel = $rsqueryTunnel->result_array();


        $nors = count($rs);
        $data['rs'] = $rs;
        $data['nors'] = "The total ".$nors;

        $data['rsqueryHost'] = $rsqueryHost;
        $data['rsqueryTunnel'] = $rsqueryTunnel;

        $data['status'] = $status;
        $data['reportrange'] = $Daterange;
        $data['valueSearch'] = $valueSearch;

        $this->load->view('header_view');
        $this->load->view('report_view',$data);
        $this->load->view('footer_view');

    }// fn.index


}