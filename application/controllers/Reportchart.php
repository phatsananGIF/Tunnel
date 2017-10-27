<?php
class Reportchart extends CI_Controller {

    function __construct() {      
        parent::__construct();
    
    }
    
    public function index(){

        $Daterange="";
        $valueSearch="";
        $rschart="";
        $norschart="";
        $total="";

        if($this->input->post("btsearch")){


            $Daterange = $this->input->post('reportrange');
            $valueSearch = $this->input->post('valueSearch');
            
            $sss = strpos($Daterange,"-");
            $start_time = trim(substr($Daterange, 0, $sss));
            $start_time = $start_time." 00:00:00";
            $end_time = trim(substr($Daterange, $sss+1));
            $end_time =$end_time." 23:59:59";

            $query  = ("  SELECT DATE_FORMAT(tundown, '%Y-%m-%d') As MyDate,
                            SUM(IF(tunup='0000-00-00 00:00:00', 1, 0)) AS Inactive,
                            SUM(IF(tunup!='0000-00-00 00:00:00' AND flag != 'SKIP', 1, 0)) AS InactiveActive
                            FROM tunnel_log
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%' or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                            and(tundown >= '". $start_time."' and tundown <= '". $end_time."')
                            GROUP BY MyDate ");
            $rschart = $this->db->query($query);
            $rschart = $rschart->result_array();

            $query2  = ("  SELECT DATE_FORMAT(tundown, '%Y-%m-%d') As MyDate,
                            SUM(IF(tunup='0000-00-00 00:00:00', 1, 0)) AS Inactive,
                            SUM(IF(tunup!='0000-00-00 00:00:00' AND flag != 'SKIP', 1, 0)) AS InactiveActive
                            FROM tunnel_log
                            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                            LEFT JOIN router on tunnel_log.host = router.host
                            WHERE (cid like '%".$valueSearch."%' or tunnel_log.tunnel like '%".$valueSearch."%' or tunnel_log.host like '%".$valueSearch."%' or hostname like '%".$valueSearch."%') 
                            and(tundown >= '". $start_time."' and tundown <= '". $end_time."')
                            ");
            $norschart = $this->db->query($query2);
            $norschart = $norschart->result_array();


            //print_r($rschart);

            $total= ($norschart[0]['Inactive']) + ($norschart[0]['InactiveActive']);


        }

        
        $data['rschart'] = $rschart;
        $data['norschart'] = $norschart;
        $data['reportrange'] = $Daterange;
        $data['valueSearch'] = $valueSearch;
        $data['total'] = $total;

        $this->load->view('header_view');
        $this->load->view('reportchart_view',$data);
        $this->load->view('footer_view');
   

    }//end f.index

}