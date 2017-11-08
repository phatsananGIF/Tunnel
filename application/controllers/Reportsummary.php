<?php
class Reportsummary extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){
        $rs=array();
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
                $query =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname, tunnel
                            ORDER BY hostname ");

                $rsquery = $this->db->query($query);
                $rs = $rsquery->result_array();


                $query2 =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname
                            ORDER BY hostname");
                            
                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();

                
               
            }else if($status == 2){//Inactive-Active
                $status = 'Inactive-Active';
                $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                                    and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname, tunnel
                            ORDER BY hostname");

                $rsquery = $this->db->query($query);
                $rs = $rsquery->result_array();
                
                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                                    and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname
                            ORDER BY hostname");
    
                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();

                

                //print_r($this->db->last_query());

            }

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
                
                $query =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname, tunnel
                            ORDER BY hostname ");

                $rsquery = $this->db->query($query);
                $rs = $rsquery->result_array();

                $query2 =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname
                            ORDER BY hostname");
                            
                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();

                
                //print_r($this->db->last_query());

            }else if($status == 2){//Inactive-Active
                $fname = "Inactive-Active";

                $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                        or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                        and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                        and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                        
                GROUP BY  hostname, tunnel
                ORDER BY hostname");

                $rsquery = $this->db->query($query);
                $rs = $rsquery->result_array();
                

                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                                    and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname
                            ORDER BY hostname");

                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();
               

            }

            foreach($rsH as $c){
                $rsCOUNT =  $rsCOUNT + $c['sumt'];
            }


            $data[] = array($fname." ".$Daterange." The total ".$rsCOUNT);

            $hostname="";
            $hostSum="";

            foreach($rs as $r){

                if($hostname !=  $r['hostname']){
                    $hostname=$r['hostname'];


                    foreach($rsH as $h){

                        if($h['hostname'] == $hostname){
                            
                            $data[] = array($hostname." (".$h['sumt'].")");
                            break;
                        }
                    }

                    $data[] = array('','CID','Tunnel','Amount');
                }

                $data[] = array('',$r['cid'],$r['tunnel'],$r['sumt']);
 

            }

           

            
            //$data=array_merge($data, $rs);
            //print_r($data);


            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Tunnel(".$fname.")-".date('dmYHis').".xlsx\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            $handle = fopen('php://output', 'w');

            foreach ($data as $data) {
                fputcsv($handle, $data);
            }
                fclose($handle);
            exit;

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
                
                $query =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname, tunnel
                            ORDER BY hostname ");

                $rsquery = $this->db->query($query);
                $rs = $rsquery->result_array();

                $query2 =("SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%')  
                                    and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y/%m/%d')>='".$start_time."'
                                    and DATE_FORMAT(tundown,'%Y/%m/%d')<='".$end_time."')

                            GROUP BY  hostname
                            ORDER BY hostname");
                            
                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();

                
                //print_r($this->db->last_query());

            }else if($status == 2){//Inactive-Active
                $fname = "Inactive-Active";

                $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                        or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                        and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                        and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                        
                GROUP BY  hostname, tunnel
                ORDER BY hostname");

                $rsquery = $this->db->query($query);
                $rs = $rsquery->result_array();
                

                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                            SEC_TO_TIME(UNIX_TIMESTAMP(tunup) - UNIX_TIMESTAMP(tundown))as total_time,COUNT(*) as sumt
                            FROM tunnel_log  
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host            
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%'
                                    or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                                    and ( DATE_FORMAT(tundown,'%Y/%m/%d')>='". $start_time."' and DATE_FORMAT(tundown,'%Y/%m/%d')<='". $end_time."'
                                    and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) 
                                    
                            GROUP BY  hostname
                            ORDER BY hostname");

                $rsquery2 = $this->db->query($query2);
                $rsH = $rsquery2->result_array();
               

            }

            foreach($rsH as $c){
                $rsCOUNT =  $rsCOUNT + $c['sumt'];
            }


            $data[] = array($fname." ".$Daterange." The total ".$rsCOUNT);

            $hostname="";
            $hostSum="";

            foreach($rs as $r){

                if($hostname !=  $r['hostname']){
                    $hostname=$r['hostname'];


                    foreach($rsH as $h){

                        if($h['hostname'] == $hostname){
                            
                            $data[] = array($hostname." (".$h['sumt'].")");
                            break;
                        }
                    }

                    $data[] = array('','CID','Tunnel','Amount');
                }

                $data[] = array('',$r['cid'],$r['tunnel'],$r['sumt']);
 

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