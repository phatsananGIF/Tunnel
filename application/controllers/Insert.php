<?php
class Insert extends CI_Controller {

    function __construct() {      
        parent::__construct();
    }
    
    public function index(){

        if(($this->input->post("btadd")) && ($_FILES["file"]["tmp_name"]!="")){

            if( ($_FILES["file"]["size"] > 0) && ($_FILES["file"]["type"]=="application/vnd.ms-excel") ){
                $filename=$_FILES["file"]["tmp_name"];
                $file = fopen($filename, "r");
                while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE){
                        $cid = $importdata[0];
                        $tunnel = $importdata[1];
                        $host = $importdata[2];

                        $query  = ("SELECT * FROM tunnel_list where tunnel='".$tunnel."' and host='".$host."' and tuneldelete ='0000-00-00 00:00:00' ");
                        $rsCheck = $this->db->query($query);
                        $rstunnelcheck = $rsCheck->row_array();

                        if ($rsCheck->num_rows() != 0){
                            //update
                            $queryU  = (" UPDATE tunnel_list SET cid='".$cid."', tunnel='".$tunnel."', host='".$host."', tunelupdate ='".date('Y-m-d H:i:s')."' WHERE id = '".$rstunnelcheck['id']."' ");
                            $this->db->query($queryU);

                        }else{
                            //insert
                            $queryA  = (" INSERT INTO tunnel_list (cid, tunnel, host, tunneladd)
                            VALUES ('".$cid."', '".$tunnel."', '".$host."', '".date('Y-m-d H:i:s')."') ");
                            $addtunnel = $this->db->query($queryA);

                        }

                }                    
                fclose($file);
                    $this->session->set_flashdata('message', '1');
                    redirect('insert/index');
            }else{
                $this->session->set_flashdata('message', '0');
                redirect('insert/index');
            }

        }else if($this->input->post("btadd")){
            redirect("insert/add");
        }


        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation','Datatable');

        $query  = (" SELECT tunnel_list.id, cid, tunnel, tunnel_list.host, hostname, router.match
                    FROM tunnel_list
                    left JOIN router ON tunnel_list.host = router.host  WHERE tuneldelete = '0000-00-00 00:00:00' ");

        $rstunnel = $this->db->query($query);
        $rstunnel = $rstunnel->result_array();

        //print_r($this->db->last_query());
        //print_r($rshost);

        $data['rstunnel'] = $rstunnel;
        $data['urledittunnel']=base_url()."insert/edit/";
        $data['urldeltunnel']=base_url()."insert/del/";


        $this->load->view('header_view');
        $this->load->view('insert_view',$data);
        $this->load->view('footer_view');

    }// fn.index



    public function add(){
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->form_validation->set_rules('addtunnel[cid]', 'cid', 'required|trim',array('required' => 'Please enter %s'));
        $this->form_validation->set_rules('addtunnel[host]', 'host', 'required',array('required' => 'Please select %s'));
        $this->form_validation->set_rules(
            'addtunnel[tunnel]', 'tunnel',
            'required|trim|callback_tunnel_check',
            array('required' => 'Please enter %s')
        );
    


        if($this->input->post("btsave")){

            if ($this->form_validation->run() == FALSE){
                //echo "form = FALSE";
            }else{
                
                $Vcid = $this->input->post("addtunnel[cid]");
                $Vtunnel = $this->input->post("addtunnel[tunnel]");
                $Vhost = $this->input->post("addtunnel[host]");


                $query  = (" INSERT INTO tunnel_list (cid, tunnel, host, tunneladd)
                            VALUES ('".$Vcid."', '".$Vtunnel."', '".$Vhost."', '".date('Y-m-d H:i:s')."') ");
                $rsaddtunnel = $this->db->query($query);

                //print_r($this->db->last_query());

                redirect("insert","refresh");
                exit();
            }
        }//if-btsave

        $query  = (" SELECT * FROM router ");

        $rsrouter = $this->db->query($query);
        $rsrouter = $rsrouter->result_array();

        $data['rsrouter'] = $rsrouter;

        $this->load->view('header_view');
        $this->load->view('add_view',$data);
        $this->load->view('footer_view');


    }//end f.add


    public function edit($id){

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');


        $this->form_validation->set_rules('edittunnel[cid]', 'cid', 'required|trim',array('required' => 'Please enter %s'));

        if($this->input->post("btsave")){
            
            if ($this->form_validation->run() == FALSE){
                //echo "form = FALSE";
            }else{
 
                $Vcid = $this->input->post("edittunnel[cid]");
                $Vtunnel = $this->input->post("edittunnel[tunnel]");
                $Vhost = $this->input->post("edittunnel[host]");

                
                $query  = (" UPDATE tunnel_list SET cid='".$Vcid."', tunnel='".$Vtunnel."', host='".$Vhost."',
                             tunelupdate='".date('Y-m-d H:i:s')."' WHERE id='".$id."' ");
                $rsedittunnel = $this->db->query($query);

                //print_r($this->db->last_query());

                redirect("insert","refresh");
                exit();
                
            }
        }//if-btsave
        
        $query  = (" SELECT * FROM tunnel_list WHERE id = '".$id."' ");

        $rstunnel = $this->db->query($query);
        $rstunnel = $rstunnel->row_array();

        //print_r($rstunnel);

        $query2  = (" SELECT * FROM router ");
        
        $rsrouter = $this->db->query($query2);
        $rsrouter = $rsrouter->result_array();

        //print_r($rstunnel);
        //print_r($this->db->last_query());

        $data['rstunnel'] = $rstunnel;
        $data['rsrouter'] = $rsrouter;

        $this->load->view('header_view');
        $this->load->view('edit_view',$data);
        $this->load->view('footer_view');
                        

    }//end f.edit


    public function del($id){


        $query  = (" UPDATE tunnel_list SET tuneldelete = '".date('Y-m-d H:i:s')."' WHERE id = '".$id."' ");
        $this->db->query($query);

        //print_r($this->db->last_query());
        
        redirect("insert","refresh");
        exit();
                        

    }//end f.del
    

    public function tunnel_check($tr){
        
        $query  = (" SELECT * FROM tunnel_list where host ='".$this->input->post("addtunnel[host]")."'
         and tunnel ='".$tr."' and tuneldelete ='0000-00-00 00:00:00'  ");
        $rstunnel = $this->db->query($query);

        if ($rstunnel->num_rows() != 0){
            $this->form_validation->set_message('tunnel_check', 'This host have %s already');
            return FALSE;
        }else{
            return TRUE;
        }

    }//end f.tunnel_check



}