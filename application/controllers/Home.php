<?php
class Home extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        $valuesearchD="";
        $valuesearchUD="";

        if($this->input->post("btsearchD")!=null){
            $valuesearchD=$this->input->post("searchD");

            $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
            DATE_FORMAT(SEC_TO_TIME(Time_to_sec( CURTIME()) - Time_to_sec( tundown)) ,'%Hh %im %ss') as total_time
            FROM tunnel_log  
            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
            LEFT JOIN router on tunnel_log.host = router.host            
            HAVING (cid like '%".$valuesearchD."%' or tunnel_log.tunnel like '%".$valuesearchD."%'
            or tunnel_log.host like '%".$valuesearchD."%' or hostname like '%".$valuesearchD."%'
            or tundown like '%".$valuesearchD."%' or total_time like '%".$valuesearchD."%') 
            and (tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y-%m-%d')=CURDATE()) ");



            $rsdown = $this->db->query($query);
            $rsdownsum = "search ".$rsdown->num_rows();
            $rsdown = $rsdown->result_array();


           // echo $valuesearchD;


        }else{

            $query  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                        DATE_FORMAT(SEC_TO_TIME(Time_to_sec( CURTIME()) - Time_to_sec( tundown)) ,'%Hh %im %ss') as total_time
                        FROM tunnel_log  
                        LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                        LEFT JOIN router on tunnel_log.host = router.host            
                        WHERE tunup = '0000-00-00 00:00:00' and DATE_FORMAT(tundown,'%Y-%m-%d')=CURDATE() ");


            $rsdown = $this->db->query($query);
            $rsdownsum = $rsdown->num_rows();
            $rsdown = $rsdown->result_array();

            

        }


        if($this->input->post("btsearchUD")!=null){

            $valuesearchUD=$this->input->post("searchUD");
            
                $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
                DATE_FORMAT(SEC_TO_TIME(Time_to_sec( tunup) - Time_to_sec( tundown)) ,'%Hh %im %ss') as total_time, flag
                FROM tunnel_log  
                LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
                LEFT JOIN router on tunnel_log.host = router.host            
                HAVING (cid like '%".$valuesearchUD."%' or tunnel_log.tunnel like '%".$valuesearchUD."%'
                or tunnel_log.host like '%".$valuesearchUD."%' or hostname like '%".$valuesearchUD."%'
                or tundown like '%".$valuesearchUD."%' or tunup like '%".$valuesearchUD."%'
                or total_time like '%".$valuesearchUD."%') 
                and ( DATE_FORMAT(tundown,'%Y-%m-%d')=CURDATE() and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ) ");
    
    
    
                $rsUpdown = $this->db->query($query2);
                $rsUpdownsum = "search ".$rsUpdown->num_rows();
                $rsUpdown = $rsUpdown->result_array();
    
    
               // echo $valuesearchUD;

        }else{

            $query2  = (" SELECT tunnel_log.id, cid, tunnel_log.tunnel, tunnel_log.host, hostname, tundown, tunup,
            DATE_FORMAT(SEC_TO_TIME(Time_to_sec(tunup) - Time_to_sec( tundown)) ,'%Hh %im %ss') as total_time
            FROM tunnel_log  
            LEFT JOIN tunnel_list on ( tunnel_log.tunnel = tunnel_list.tunnel and tunnel_log.host = tunnel_list.host)
            LEFT JOIN router on tunnel_log.host = router.host            
            WHERE DATE_FORMAT(tundown,'%Y-%m-%d')=CURDATE() and  tunup!='0000-00-00 00:00:00' and flag != 'SKIP' ");

            $rsUpdown = $this->db->query($query2);
            $rsUpdownsum = $rsUpdown->num_rows();
            $rsUpdown = $rsUpdown->result_array();

        }

         //--- chart ---//
         $querychar = (" SELECT DATE_FORMAT(tundown, '%Y-%m-%d') As MyDate,
                        SUM(IF(tunup='0000-00-00 00:00:00', 1, 0)) AS Down,
                        SUM(IF(tunup!='0000-00-00 00:00:00' AND flag != 'SKIP', 1, 0)) AS UpDown
                        FROM tunnel_log
                        WHERE tundown >= CURDATE()-7
                        GROUP BY `MyDate` ");

         $rschart = $this->db->query($querychar);
         $rschart = $rschart->result_array();


        $data['rsdown'] = $rsdown;
        $data['rsdownsum'] = $rsdownsum;
        $data['valuesearchD'] = $valuesearchD;

        $data['rsUpdown'] = $rsUpdown;
        $data['rsUpdownsum'] = $rsUpdownsum;
        $data['valuesearchUD'] = $valuesearchUD;

        $data['rschart'] = $rschart;

        $this->load->view('header_view');
        $this->load->view('home_view',$data);
        $this->load->view('footer_view');

    }// fn.index


}