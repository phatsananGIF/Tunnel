<?php
class Reportsummary extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){
        $rs=array();
        $dataRS=array();
        $rsH=array();
        $Daterange="";
        $valueSearch="";
        $status="";
        $rsCOUNT="";




        if( $this->input->post("btsearchRe")!=null){

            $valueSearch=$this->input->post("valueSearch");
            $status = $this->input->post("statusRe");

            $Daterange = $this->input->post('reportrange');
            $sss = strpos($Daterange,"-");
            $start_time = trim(substr($Daterange, 0, $sss));
            $end_time = trim(substr($Daterange, $sss+1));

            if($status == 1){//Inactive
                $status = 'Inactive';
                $query2 =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname
                            ORDER BY sumt DESC");
                            
                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();


                foreach($rsH as $h ){
                    $dataRS[] = array('hostname'=> $h['hostname']." (".$h['sumt'].")");

                    $query =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                                FROM tunnel_log  
                                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                                LEFT JOIN router on tunnel_log.host = router.host            
                                WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                        or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                        and (hostname = '".$h['hostname']."' and tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                        and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                                GROUP BY  hostname, tunnel
                                ORDER BY hostname, sumt DESC ");

                    $rsquery = $this->db->query($query);
                    $rs = $rsquery->result_array();
                    
                    foreach($rs as $r){
                        $dataRS[] = array('cid'=>$r['cid'],'tunnel'=>$r['tunnel'],'sumt'=>$r['sumt']);
                    }
                    
                }//end foreach

                
               
            }else if($status == 2){//Inactive-Active
                $status = 'Inactive-Active';
                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                                    and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and DATE_FORMAT(tunup,'%Y-%m-%d')=DATE_FORMAT(tundown,'%Y-%m-%d') and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname
                            ORDER BY sumt DESC");
    
                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();


                foreach($rsH as $h ){
                    $dataRS[] = array('hostname'=> $h['hostname']." (".$h['sumt'].")");

                    $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                                SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                                FROM tunnel_log  
                                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                                LEFT JOIN router on tunnel_log.host = router.host            
                                WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                        or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%' ) 
                                        and ( hostname = '".$h['hostname']."' and DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                        and DATE_FORMAT(tunup,'%Y-%m-%d')=DATE_FORMAT(tundown,'%Y-%m-%d') and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                        
                                GROUP BY  hostname, tunnel
                                ORDER BY hostname, sumt DESC");

                    $rsquery = $this->db->query($query);
                    $rs = $rsquery->result_array();
                    
                    foreach($rs as $r){
                        $dataRS[] = array('cid'=>$r['cid'],'tunnel'=>$r['tunnel'],'sumt'=>$r['sumt']);
                    }

                }//end foreach

                //print_r($this->db->last_query());

            }//else if




            foreach($rsH as $c){
                $rsCOUNT =  $rsCOUNT + $c['sumt'];
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

            if($status == 1){//Inactive
                $fname = "Inactive";
                $query2 =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname
                            ORDER BY sumt DESC");
                            
                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();

                foreach($rsH as $h ){
                    $dataRS[] = array('hostname'=> $h['hostname']." (".$h['sumt'].")");

                    $query =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                                FROM tunnel_log  
                                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                                LEFT JOIN router on tunnel_log.host = router.host            
                                WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                        or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%' )  
                                        and (hostname = '".$h['hostname']."' and tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                        and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                                GROUP BY  hostname, tunnel
                                ORDER BY hostname, sumt DESC ");

                    $rsquery = $this->db->query($query);
                    $rs = $rsquery->result_array();
                    
                    foreach($rs as $r){
                        $dataRS[] = array('cid'=>$r['cid'],'tunnel'=>$r['tunnel'],'sumt'=>$r['sumt']);
                    }

                }//end foreach

                
                //print_r($this->db->last_query());

            }else if($status == 2){//Inactive-Active
                $fname = "Inactive-Active";
                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                                    and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and DATE_FORMAT(tunup,'%Y-%m-%d')=DATE_FORMAT(tundown,'%Y-%m-%d') and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname
                            ORDER BY sumt DESC");

                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();
               
                foreach($rsH as $h ){
                    $dataRS[] = array('hostname'=> $h['hostname']." (".$h['sumt'].")");

                    $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%' ) 
                                    and (hostname = '".$h['hostname']."' and  DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and DATE_FORMAT(tunup,'%Y-%m-%d')=DATE_FORMAT(tundown,'%Y-%m-%d') and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname, tunnel
                            ORDER BY hostname, sumt DESC ");

                    $rsquery = $this->db->query($query);
                    $rs = $rsquery->result_array();
                    
                    foreach($rs as $r){
                        $dataRS[] = array('cid'=>$r['cid'],'tunnel'=>$r['tunnel'],'sumt'=>$r['sumt']);
                    }

                }//end foreach

            }//end if

            foreach($rsH as $c){
                $rsCOUNT =  $rsCOUNT + $c['sumt'];
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
            
            /*
            //--โหลดภาพใส่ในโฟเดอ
            //$str ="R0lGODlhPQBEAPeoAJosM//AwO/AwHVYZ/z595kzAP/s7P+goOXMv8+fhw/v739/f+8PD98fH/8mJl+fn/9ZWb8/PzWlwv///6wWGbImAPgTEMImIN9gUFCEm/gDALULDN8PAD6atYdCTX9gUNKlj8wZAKUsAOzZz+UMAOsJAP/Z2ccMDA8PD/95eX5NWvsJCOVNQPtfX/8zM8+QePLl38MGBr8JCP+zs9myn/8GBqwpAP/GxgwJCPny78lzYLgjAJ8vAP9fX/+MjMUcAN8zM/9wcM8ZGcATEL+QePdZWf/29uc/P9cmJu9MTDImIN+/r7+/vz8/P8VNQGNugV8AAF9fX8swMNgTAFlDOICAgPNSUnNWSMQ5MBAQEJE3QPIGAM9AQMqGcG9vb6MhJsEdGM8vLx8fH98AANIWAMuQeL8fABkTEPPQ0OM5OSYdGFl5jo+Pj/+pqcsTE78wMFNGQLYmID4dGPvd3UBAQJmTkP+8vH9QUK+vr8ZWSHpzcJMmILdwcLOGcHRQUHxwcK9PT9DQ0O/v70w5MLypoG8wKOuwsP/g4P/Q0IcwKEswKMl8aJ9fX2xjdOtGRs/Pz+Dg4GImIP8gIH0sKEAwKKmTiKZ8aB/f39Wsl+LFt8dgUE9PT5x5aHBwcP+AgP+WltdgYMyZfyywz78AAAAAAAD///8AAP9mZv///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAKgALAAAAAA9AEQAAAj/AFEJHEiwoMGDCBMqXMiwocAbBww4nEhxoYkUpzJGrMixogkfGUNqlNixJEIDB0SqHGmyJSojM1bKZOmyop0gM3Oe2liTISKMOoPy7GnwY9CjIYcSRYm0aVKSLmE6nfq05QycVLPuhDrxBlCtYJUqNAq2bNWEBj6ZXRuyxZyDRtqwnXvkhACDV+euTeJm1Ki7A73qNWtFiF+/gA95Gly2CJLDhwEHMOUAAuOpLYDEgBxZ4GRTlC1fDnpkM+fOqD6DDj1aZpITp0dtGCDhr+fVuCu3zlg49ijaokTZTo27uG7Gjn2P+hI8+PDPERoUB318bWbfAJ5sUNFcuGRTYUqV/3ogfXp1rWlMc6awJjiAAd2fm4ogXjz56aypOoIde4OE5u/F9x199dlXnnGiHZWEYbGpsAEA3QXYnHwEFliKAgswgJ8LPeiUXGwedCAKABACCN+EA1pYIIYaFlcDhytd51sGAJbo3onOpajiihlO92KHGaUXGwWjUBChjSPiWJuOO/LYIm4v1tXfE6J4gCSJEZ7YgRYUNrkji9P55sF/ogxw5ZkSqIDaZBV6aSGYq/lGZplndkckZ98xoICbTcIJGQAZcNmdmUc210hs35nCyJ58fgmIKX5RQGOZowxaZwYA+JaoKQwswGijBV4C6SiTUmpphMspJx9unX4KaimjDv9aaXOEBteBqmuuxgEHoLX6Kqx+yXqqBANsgCtit4FWQAEkrNbpq7HSOmtwag5w57GrmlJBASEU18ADjUYb3ADTinIttsgSB1oJFfA63bduimuqKB1keqwUhoCSK374wbujvOSu4QG6UvxBRydcpKsav++Ca6G8A6Pr1x2kVMyHwsVxUALDq/krnrhPSOzXG1lUTIoffqGR7Goi2MAxbv6O2kEG56I7CSlRsEFKFVyovDJoIRTg7sugNRDGqCJzJgcKE0ywc0ELm6KBCCJo8DIPFeCWNGcyqNFE06ToAfV0HBRgxsvLThHn1oddQMrXj5DyAQgjEHSAJMWZwS3HPxT/QMbabI/iBCliMLEJKX2EEkomBAUCxRi42VDADxyTYDVogV+wSChqmKxEKCDAYFDFj4OmwbY7bDGdBhtrnTQYOigeChUmc1K3QTnAUfEgGFgAWt88hKA6aCRIXhxnQ1yg3BCayK44EWdkUQcBByEQChFXfCB776aQsG0BIlQgQgE8qO26X1h8cEUep8ngRBnOy74E9QgRgEAC8SvOfQkh7FDBDmS43PmGoIiKUUEGkMEC/PJHgxw0xH74yx/3XnaYRJgMB8obxQW6kL9QYEJ0FIFgByfIL7/IQAlvQwEpnAC7DtLNJCKUoO/w45c44GwCXiAFB/OXAATQryUxdN4LfFiwgjCNYg+kYMIEFkCKDs6PKAIJouyGWMS1FSKJOMRB/BoIxYJIUXFUxNwoIkEKPAgCBZSQHQ1A2EWDfDEUVLyADj5AChSIQW6gu10bE/JG2VnCZGfo4R4d0sdQoBAHhPjhIB94v/wRoRKQWGRHgrhGSQJxCS+0pCZbEhAAOw==";
            $str = "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";
            //die($str);
            $imgname=date('YmdHis').".jpg" ;
            file_put_contents(FCPATH.'/tmp/'.$imgname, base64_decode($str));

            //--ใส่ภาพที่ A1
            //$gdImage = imagecreatefromjpeg(FCPATH.'/tmp/'.$imgname);
            $gdImage = imagecreatefromjpeg('tmp/'.$imgname);
            //$gdImage = imagecreatefromjpeg('asset/img/t12.jpg');
            $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
            $objDrawing->setName('Sample image');
            $objDrawing->setDescription('Sample image');
            $objDrawing->setImageResource($gdImage);
            $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
            $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
            $objDrawing->setHeight(450);
            $objDrawing->setCoordinates('A1');
            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
            */


            // กำหนดหัวข้อให้กับแถวแรก
            $objPHPExcel->setActiveSheetIndex(0)  
                        ->setCellValue('A1', $fname." ".$Daterange." The total ".$rsCOUNT)    
                        ->setCellValue('B1', '')  
                        ->setCellValue('C1', '')  
                        ->setCellValue('D1', '');    


            // ดึงข้อมูลเริ่มเพิ่มแถวที่ 2 ของ excel            
            $start_row=2;  
            $result = $dataRS;
            if(count($result)>=0){
                foreach($result as $row){
                    
                    if( isset( $row['hostname'] ) ){
                        $objPHPExcel->setActiveSheetIndex(0)  
                                ->setCellValue('A'.$start_row, $row['hostname']);           
                        
                        $start_row++;

                        $objPHPExcel->setActiveSheetIndex(0)  
                                ->setCellValue('A'.$start_row, '')  
                                ->setCellValue('B'.$start_row, 'CID')  
                                ->setCellValue('C'.$start_row, 'Tunnel')  
                                ->setCellValue('D'.$start_row, 'Amount');

                        $start_row++;

                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)  
                                ->setCellValue('A'.$start_row, '')  
                                ->setCellValue('B'.$start_row, $row['cid'])  
                                ->setCellValue('C'.$start_row, $row['tunnel'])  
                                ->setCellValue('D'.$start_row, $row['sumt']);           
                        
                        $start_row++; 
                    }
                      
                          
                           
                }//end foreach
        
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  // Excel2007 (xlsx) หรือ Excel5 (xls)        
                
                $filename='Tunnel-('.$fname.')'.date("dmYHis").'.xls'; //  กำหนดชือ่ไฟล์ นามสกุล xls หรือ xlsx
                // บังคับให้ทำการดาวน์ดหลดไฟล์
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                ob_end_clean();     
                $objWriter->save('php://output'); // ดาวน์โหลดไฟล์รายงาน
                // หากต้องการบันทึกเป็นไฟล์ไว้ใน server  ใช้คำสั่งนี้ $this->excel->save("/path/".$filename); 
                // แล้วตัด header ดัานบนทั้ง 3 อันออก   
                
            }

            //---delete image
            //unlink('tmp/'.$imgname);
            

        }//bt-Export



        if( $this->input->post("btn_exportCSV")!=null){
            
            $fname = "";
            
            $valueSearch=$this->input->post("valueSearch");
            $status = $this->input->post("statusRe");

            $Daterange = $this->input->post('reportrange');
            $sss = strpos($Daterange,"-");
            $start_time = trim(substr($Daterange, 0, $sss));
            $end_time = trim(substr($Daterange, $sss+1));

            if($status == 1){//Inactive
                $fname = "Inactive";
                $query2 =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname
                            ORDER BY sumt DESC");
                            
                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();

                foreach($rsH as $h ){
                    $dataRS[] = array('hostname'=> $h['hostname']." (".$h['sumt'].")");

                    $query =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                                FROM tunnel_log  
                                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                                LEFT JOIN router on tunnel_log.host = router.host            
                                WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                        or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%' )  
                                        and (hostname = '".$h['hostname']."' and tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                        and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                                GROUP BY  hostname, tunnel
                                ORDER BY hostname, sumt DESC ");

                    $rsquery = $this->db->query($query);
                    $rs = $rsquery->result_array();
                    
                    foreach($rs as $r){
                        $dataRS[] = array('cid'=>$r['cid'],'tunnel'=>$r['tunnel'],'sumt'=>$r['sumt']);
                    }

                }//end foreach

                
                //print_r($this->db->last_query());

            }else if($status == 2){//Inactive-Active
                $fname = "Inactive-Active";
                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                                    and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and DATE_FORMAT(tunup,'%Y-%m-%d')=DATE_FORMAT(tundown,'%Y-%m-%d') and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname
                            ORDER BY sumt DESC");

                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();
               
                foreach($rsH as $h ){
                    $dataRS[] = array('hostname'=> $h['hostname']." (".$h['sumt'].")");

                    $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%'  or hostname like '%".$valueSearch."%') 
                                    and (hostname = '".$h['hostname']."' and  DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and DATE_FORMAT(tunup,'%Y-%m-%d')=DATE_FORMAT(tundown,'%Y-%m-%d') and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname, tunnel
                            ORDER BY hostname, sumt DESC ");

                    $rsquery = $this->db->query($query);
                    $rs = $rsquery->result_array();
                    
                    foreach($rs as $r){
                        $dataRS[] = array('cid'=>$r['cid'],'tunnel'=>$r['tunnel'],'sumt'=>$r['sumt']);
                    }

                }//end foreach

            }//end if

            foreach($rsH as $c){
                $rsCOUNT =  $rsCOUNT + $c['sumt'];
            }


            $data[] = array($fname." ".$Daterange." The total ".$rsCOUNT);

            $hostname="";
            $hostSum="";

            foreach($dataRS as $dr){

                if( isset( $dr['hostname'] ) ){
               
                    $data[] = array($dr['hostname']);
                    $data[] = array('','CID','Tunnel','Amount');
                }else{

                    $data[] = array('',$dr['cid'],$dr['tunnel'],$dr['sumt']);
                }

               
            }

           
            //$data=array_merge($data, $rs);
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

        $data['rsqueryHost'] = $rsqueryHost;

        
        $data['rs'] = $rs;
        $data['dataRS'] = $dataRS;
        $data['rsH'] = $rsH;
        $data['rsCOUNT'] = $rsCOUNT;
        $data['status'] = $status;
        $data['reportrange'] = $Daterange;
        $data['valueSearch'] = $valueSearch;


        $this->load->view('header_view');
        $this->load->view('reportsummary_view',$data);
        $this->load->view('footer_view');

    }// fn.index


}