<?php
class Templates extends CI_Controller{

    function __construct() {      
        parent::__construct();
        $this->load->model('model_template'); // โหลดโมเดล model_template

    }

    public function index(){
        /*----
        format {ชื้อฟิว|ชื้อ label} กรณีเป็น input
            ตัวอย่าง {TIPADDR|IP Address}
        format {ชื้อฟิว|ค้าที่จะเอามาทำเป็นตัวเลือก|ชื้อ label} กรณีเป็น input
            ตัวอย่าง {TID|1,2,3,4,5=5555 - 3333|Tunnel ID}
        format {ชื้อฟิว} กรณีเป็นฟิวซ้ำ
            ตัวอย่าง {TID}
        ----*/

        $Alltemplate = $this->model_template->getAllTemplate(); // เรียกใช้เมธอด getAllTemplate 
        $data['Alltemplate'] = $Alltemplate;        
        
        $this->load->view('header_view');
        $this->load->view('templates_view',$data);
        $this->load->view('footer_view');
    }//f.index


    public function submitnew(){
        if($this->input->post()){
            $Vtem = $this->input->post("formtem");
            $this->model_template->addTemplate($Vtem['name_tem'],$Vtem['template']);
            $this->session->set_flashdata('message', '1');
        }
        redirect("templates","refresh");
        exit();

    }//end f.submitnew


    public function viewText(){
        if($this->input->post()){
            $id = $this->input->post('idtem');
            $querygetformTem = $this->model_template->getformTemplate($id);
            $querygetdataTem = $this->model_template->getdataTemplateforviewText($id);

            $tem['namevari'] = $querygetformTem['name_tem'];
            $tem['strvari'] = $querygetformTem['template'];
            
            if(count($querygetdataTem)!=0){
                
                $tem['status'] = "use";
                
            }else{
                $tem['status'] = "notuse";
            }

           
            echo json_encode($tem);
        }else{
            redirect("templates");
            exit();
        }
    }//end f.viewText


    public function submitUpdateTem(){
        if($this->input->post()){
            $datatem = $this->input->post("formtem");
            $this->model_template->updateTextform($datatem);
            $this->session->set_flashdata('message', '1');
        }
        redirect("templates","refresh");
        exit();
    }//f.submitUpdateTem


    public function viewform(){
        if($this->input->post()){
            $id = $this->input->post('idtem');
            $querygetformTem = $this->model_template->getformTemplate($id);
            $tem['namevari'] = $querygetformTem['name_tem'];
            $tem['arrstrvari'] = $this->formTOarray($querygetformTem['template']);

            echo json_encode($tem);
        }else{
            redirect("templates");
            exit();
        }
    }//f.viewform

    
    public function del($id){

        $this->model_template->delTemplate($id);
        redirect("templates","refresh");
        exit();

    }//end f.del
    

    public function formTOarray($formtem){ // text to array
        
        $arrstrvari = '';
        $arrstr = str_split($formtem);
        foreach ($arrstr as $key => $value) {

            if ($value == '{') {
                $start = $key;
            }elseif($value == '}'){
                $end = $key;
            }

            if(isset($start) && isset($end)){

                //หา form ใน {}
                $start = $start+1;
                $strlength = $end - $start;
                $cutstr=substr($formtem, $start, $strlength);

                //แบ่ง form
                $cutstr=explode("|",$cutstr);
                if(count($cutstr)==2){
                    $arrstrvari[]=array($cutstr[0],$cutstr[1]);

                }elseif(count($cutstr)==3){
                    $arrvalue=explode(",",$cutstr[1]);
                    foreach($arrvalue as $vari){
                        $valone=explode("=",$vari);
                        if(count($valone)==1){
                            $arrvalue2[$valone[0]] = $valone[0];
                        }elseif(count($valone)==2){
                            $arrvalue2[$valone[0]] = $valone[1];
                        }
                    }
                    $arrstrvari[]=array($cutstr[0],$arrvalue2,$cutstr[2]);
                }

                
                $start = null;
                $end = null;
            }
        }//foreach
        return $arrstrvari;

    }//f.formTOarray
}//class