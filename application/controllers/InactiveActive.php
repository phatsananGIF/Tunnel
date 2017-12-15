<?php
class InactiveActive extends CI_Controller{
    function __construct() {      
        parent::__construct();
    }

    public function index(){

        $dataRS=array();
        $dataInactiveActive="";
        $dataheatmap="";
        $countIA="";
        $countIAheatmap="";
        $hostselect="";

        $Ldate = date('Y/m/d');
        $Fdate = date("Y/m/d",strtotime("-6 days",strtotime($Ldate)));
        $Daterange = $Fdate." - ".$Ldate;

        if($this->input->post("btsearch")){
            $Daterange = $this->input->post('reportrange');
            //ดึงหัสภาพ
            //$IDbase64 = $this->input->post('IDbase64');
            //echo $IDbase64;
            
        }

        $sss = strpos($Daterange,"-");
        $start_time = trim(substr($Daterange, 0, $sss));
        $start_time = $start_time." 00:00:00";
        $end_time = trim(substr($Daterange, $sss+1));
        $end_time =$end_time." 23:59:59";


        //--get date--//
        $querydate = (" SELECT DATE_FORMAT(tundown, '%Y-%m-%d') As MyDate, COUNT(tunup) AS InactiveActive 
                FROM tunnel_log 
                WHERE (tunup!='0000-00-00 00:00:00' AND flag != 'SKIP') and (tundown >= '". $start_time."' and tundown <= '". $end_time."') 
                GROUP BY `MyDate`");

        $rsdate = $this->db->query($querydate);
        $rsdate = $rsdate->result_array();


        //--get host--//
        $queryhost = ("SELECT * FROM router ORDER BY hostname");

        $rshost = $this->db->query($queryhost);
        $rshost = $rshost->result_array();
        $allhost = $rshost;
        $hostselect = $rshost;

        if($this->input->post("btsearch")){
            $rshost="";
            if(isset($_POST['check_list'])){
                
                foreach($_POST['check_list'] as $check) {
                    foreach($allhost as $getCodecolor){
                        if($check == $getCodecolor['hostname']){
                            $color = $getCodecolor['code_color'];
                            break;
                        }
                    }
                    $rshost[] = array('hostname'=> $check,'code_color'=>$color );
                    $hostselect = $rshost;
                }
            }else{
                $rshost[] = array('hostname'=> 'not host');
                $hostselect = $rshost;
            }
            
        }

        //print_r($rshost);

        //--- chart column---//
        $nH=0;
        foreach($rshost as $host){
            
           
            $querychar = (" SELECT DATE_FORMAT(tundown, '%Y-%m-%d') As MyDate,hostname, COUNT(tunup) AS InactiveActive
                        FROM tunnel_log
                        LEFT JOIN router on tunnel_log.host = router.host  
                        WHERE (tunup!='0000-00-00 00:00:00' AND flag != 'SKIP') and (tundown >= '". $start_time."' and tundown <= '". $end_time."') and (hostname='".$host['hostname']."')
                        and (DATE_FORMAT(tunup, '%Y-%m-%d') = DATE_FORMAT(tundown, '%Y-%m-%d'))
                        GROUP BY `MyDate` ");
    
            $rschart = $this->db->query($querychar);
            $rschart = $rschart->result_array();

            $nD=0;
            if(count($rsdate)==count($rschart)){

                foreach($rschart as $chart){
                    if($chart['InactiveActive']==''){
                        $chart['InactiveActive'] = 0;
                    }
                    $dataheatmap .= "[".$nH.",".$nD.",".$chart['InactiveActive']."],";
                    $nD++;
                    
                    $dataInactiveActive .= $chart['InactiveActive'].",";
                }
            }elseif(count($rschart)==0){
                foreach($rsdate as $rsD){

                    $countIA= "0";
                    $countIAheatmap= "0";

                    $dataheatmap .= "[".$nH.",".$nD.",".$countIAheatmap."],";
                    $nD++;

                    $dataInactiveActive .= $countIA.",";
                }

            }else{

                foreach($rsdate as $rsD){
                    
                    foreach($rschart as $chart){
                    
                        if($rsD['MyDate']==$chart['MyDate']){
                            $countIA= $chart['InactiveActive'];
                            $countIAheatmap= $chart['InactiveActive'];
                            break;
                        }else{
                            $countIA= "0";
                            $countIAheatmap= "0";
                        }

                    }

                    $dataheatmap .= "[".$nH.",".$nD.",".$countIAheatmap."],";
                    $nD++;

                    $dataInactiveActive .= $countIA.",";
                }
            }// if count data and date
            
            
            $dataRS[] = array('hostname'=> $host['hostname'],'color'=> $host['code_color'],'InactiveActive'=> $dataInactiveActive);
            $dataInactiveActive="";

            $nH++;
        }//for host
        

        //print_r($this->db->last_query());
        //print_r($dataRS);

        $data['dataRS'] = $dataRS;
        $data['dataheatmap'] = $dataheatmap;
        $data['rsdate'] = $rsdate;
        $data['rshost'] = $rshost;
        $data['allhost'] = $allhost;
        $data['hostselect'] = $hostselect;
        $data['reportrange'] = $Daterange;

        $this->load->view('header_view');
        $this->load->view('inactive_active_view',$data);
        $this->load->view('footer_view');

    }//f.index

}//end class