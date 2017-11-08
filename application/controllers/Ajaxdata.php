<?Php

class Ajaxdata extends CI_Controller{
    function __construct() {      
        parent::__construct();
    }

    function index(){

        $data = array();
        $query  = (" SELECT tunnel_list.id, cid, tunnel, tunnel_list.host, hostname, router.match
                    FROM tunnel_list
                    left JOIN router ON tunnel_list.host = router.host  WHERE tuneldelete = '0000-00-00 00:00:00' ");
        $query = $this->db->query($query);
        $no=1;
        foreach ($query->result_array() as $row){
            $text = "'Do you want to delete it?'";
            $command = '<a name="btedit" href="'.base_url().'insert/edit/'.$row['id'].'"  > <i class="fa fa-pencil-square-o fa-lg"></i></a>&nbsp;&nbsp; <a name="btdel" href= "'.base_url().'insert/del/'.$row['id'].'" onclick="javascript:return confirm('.$text.');" > <i class="fa fa fa-trash-o fa-lg"></i></a>';
            
            $data[] = array(
                $no,
                $row['cid'],
                $row['tunnel'],
                $row['host'],
                $row['hostname'],
                $row['match'],
                $command
                
            );
            $no++;
        }
        $output = array(
             "data" => $data
        );
        echo json_encode($output);
        exit();

    }//end f.index
 


}//class