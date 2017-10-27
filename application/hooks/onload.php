<?php
class Onload{
    private $ci;
    function __construct() {      
        $this->ci =&get_instance();
        
    }

    public function check_login(){
        $controller = $this->ci->router->class;
        $method = $this->ci->router->method;
        $this->ci->load->database();
        $datetoday = date("Y-m-d H:i:s");

        if($this->ci->session->userdata("user_name")==null){
            if($controller != "login"){
                redirect("login","refresh");
                exit();
            }

        }else{

            $page = $controller.'/'.$method;
            $user_name = $this->ci->session->userdata("user_name");

            $this->ci->db->query(" INSERT INTO user_use_page (user_name, page_use, 	time_use) VALUES ('".$user_name."', '".$page."', '".$datetoday."') ");
            

            if($controller == "login"){
                redirect("","refresh");
                exit();
            }else if($this->ci->session->userdata("user_type")!=1 && $controller == "manageuser" ){
                redirect("","refresh");
                exit();

            }
        }
    }

}
?>