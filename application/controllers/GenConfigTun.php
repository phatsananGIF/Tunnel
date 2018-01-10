<?php
class GenConfigTun extends CI_Controller{

    function __construct() {      
        parent::__construct();
        $this->load->model('model_template');

    }

    public function index(){
        $getAllTunnel = $this->model_template->getAllTunnel();  
        $data['getAllTunnel'] = $getAllTunnel; 
        
        $this->load->view('header_view');
        $this->load->view('gen_configtun_view',$data);
        $this->load->view('footer_view');
    }//f.index

    
    public function upfile(){
        if( $this->input->post("btpreview") ){
            $tem = $this->input->post("tem");
            $getformTemplate = $this->model_template->getformTemplate($tem);

            $tem = $this->formTOarray($getformTemplate["template"]);

            if(count($tem)==0 || $tem==""){
                $fillconfig = array('Templase not fill !!');

            }else{
                $fillconfig = array('CID', 'Tunnel', 'Host');

                foreach($tem as $rs){
                    $countrs = count($rs);
                    array_push($fillconfig,$rs[$countrs-1]);
                }

                
            }
                       
            $fillar[]=array( $getformTemplate["name_tem"], $getformTemplate["tem_id"] );
            $fillar=array_merge($fillar, array($fillconfig) );


            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Config(".$getformTemplate["name_tem"].")-".date('dmYHis').".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            $handle = fopen('php://output', 'w');

            foreach ($fillar as $fillconfig) {
                fputcsv($handle, $fillconfig);
            }
                fclose($handle);
            exit;
/*
            echo '<pre>';
            print_r($fillar);
            echo  '</pre>';
*/
        }// bt-preview

        if( $this->input->post("btupfile") ){
            
            $filename=$_FILES["file"]["tmp_name"];

            
            if( ($_FILES["file"]["size"] > 0) && ($_FILES["file"]["type"]=="application/vnd.ms-excel") ){
                $file = fopen($filename, "r");
                $arfillrow=array();
                while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE){
                    
                    $arrfill = array();
                    for($i=0; $i< count($importdata); $i++){
                        $fill = $importdata[$i];
                        array_push( $arrfill, $fill );
                    }
                    $arfillrow = array_merge($arfillrow, array($arrfill) );
                   
                }             
                fclose($file);

                $getformTemplate = $this->model_template->getformTemplate($arfillrow[0][1]);
                $tem = $this->formTOarray($getformTemplate["template"]);

                $Hfilltem = array();
                foreach($tem as $filltitel){
                    array_push( $Hfilltem, $filltitel[0] );
                }

                if(count($arfillrow)<=2){ //เช็คว่าใส่ค่ามาไหม
                    $this->session->set_flashdata('message', '2');
                }else{

                    for($i=2; $i< count($arfillrow); $i++){// วนเช็ค tunnel และจัด array ใหม่เพื่อสร้าง config

                        $getdata = array_splice($arfillrow[$i], 3);
                        for($j=0; $j< count($getdata); $j++){
                            $mixfill[$Hfilltem[$j]]=$getdata[$j];
                        }  


                        $query = (" SELECT id, host, cid, tunnel, if(cid= '".$arfillrow[$i][0]."',cid,'')as checkcid
                                    FROM tunnel_list 
                                    WHERE tunnel = 'tunnel".$arfillrow[$i][1]."' AND host = '".$arfillrow[$i][2]."' AND tuneldelete = '0000-00-00 00:00:00' "); 
                        $querycheck = $this->db->query($query);
                        $querycheck = $querycheck->result_array();
                        
                        //print_r($this->db->last_query());

                        if(count($querycheck)==0){
                            $status = "new";
                            $idtun  = $this->model_template->insertNewtunnel($arfillrow[$i][0], $arfillrow[$i][1], $arfillrow[$i][2]);
                        }elseif( (count($querycheck)!=0) && ($querycheck[0]['checkcid']=='') && ($querycheck[0]['cid']=='') ){
                            $status = "upcid";
                            $idtun = $querycheck[0]['id'];
                            $this->model_template->updateCIDtunnel($idtun, $arfillrow[$i][0]);
                        }elseif( (count($querycheck)!=0) && ($querycheck[0]['checkcid']=='') && ($querycheck[0]['cid']!='') ){
                            $status = "error";
                            $idtun = "";
                        }elseif( (count($querycheck)!=0) && ($querycheck[0]['checkcid']!='') ){
                            $status = "have";
                            $idtun = $querycheck[0]['id'];
                        }

                        
                        $arfillrow[$i][3]= json_encode($mixfill);
                        $arfillrow[$i][4]= $status;
                        $arfillrow[$i][5]= $arfillrow[0][1];
                        $arfillrow[$i][6]= $idtun;

                    

                    }// end for check

                    $arfillrow = array_splice($arfillrow, 2);

                    foreach($arfillrow as $onerow){ //วน gen config
                        if($onerow[4]!="error"){
                            $this->model_template->insertConfig($onerow);
                        }
                    }// end for gen config


                    //---return file log upload---//  
                    /*      
                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=\"LogUpload(".date('dmYHis').").csv\"");
                    header("Pragma: no-cache");
                    header("Expires: 0");
        
                    $handle = fopen('php://output', 'w');
        
                    foreach ($arfillrow as $dataconfig) {
                        fputcsv($handle, $dataconfig);
                    }
                        fclose($handle);
                    exit;
                    */


                    $this->session->set_flashdata('message', '1');

                }//end check input data
                    
            }else{
                $this->session->set_flashdata('message', '2');  
            }
                 
               
               
               
            
        }// bt-upfile

        $getAllTem = $this->model_template->getAllTemplate();
        $data['getAllTem'] = $getAllTem;
      
        $this->load->view('header_view');
        $this->load->view('upfile_config_view',$data);
        $this->load->view('footer_view');
    }//f.upfile

    public function submitnew(){
        if($this->input->post()){
            $Dtem = $this->input->post("formtem");

            if($Dtem['status']=="new"){
            
                $IPhost = $this->model_template->getIPhost($Dtem['hostname']);
                $insert_idtunnel  = $this->model_template->insertNewtunnel($Dtem['cid'], $Dtem['tunnel'], $IPhost['host']);

                $dataconfig = $this->model_template->updateTemplate($Dtem, $insert_idtunnel);

            }elseif($Dtem['status']=="upcid"){
                
                $this->model_template->updateCIDtunnel($Dtem['id_check'], $Dtem['cid']);
                $dataconfig = $this->model_template->updateTemplate($Dtem, $Dtem['id_check']);

            }elseif($Dtem['status']=="have"){

                $dataconfig = $this->model_template->updateTemplate($Dtem, $Dtem['id_check']);
            }

            $querygetformTem = $this->model_template->getformTemplate($dataconfig['id_tem']);
                
            $strvari = $this->formTOvariable($querygetformTem['template']);

            $arrdataTem = json_decode($dataconfig['data_tem'], true);

            foreach($arrdataTem as $f => $v ){
                //echo $f."=>".$v;
                eval("\$$f=\"\$v\";");
            }
            
            eval("\$strvari = \"$strvari\";");
                
            $this->session->set_flashdata('message', '1');
            $this->session->set_flashdata('host', $Dtem['hostname']);
            $this->session->set_flashdata('cid', $Dtem['cid']);
            $this->session->set_flashdata('tunnel', $Dtem['tunnel']);
            $this->session->set_flashdata('nametem', $querygetformTem['name_tem']);
            $this->session->set_flashdata('textconfig', $strvari);

        }
        
        redirect("genConfigTun","refresh");
        exit();
        
        
    }//f.submitnew

    public function getlisthost(){
        if($this->input->post()){
            
            $getAllhost = $this->model_template->getAllHost();  
            $getAllTemplate = $this->model_template->getAllTemplate();

            $data['getAllhost'] = $getAllhost;
            $data['getAllTemplate'] = $getAllTemplate;

            echo json_encode($data);
        }else{
            redirect("genConfigTun");
            exit();
        }
    }//f.getlisthost

    public function getdatatunnel(){
        
        if($this->input->post()){
            $id_tunnel = $this->input->post("id_tunnel");

            $getDatatunnel = $this->model_template->getDatatunnel($id_tunnel);
            $getAllhost = $this->model_template->getAllHost();
            $getAllTemplate = $this->model_template->getAllTemplate();

            $data['getDatatunnel'] = $getDatatunnel;
            $data['getAllhost'] = $getAllhost;
            $data['getAllTemplate'] = $getAllTemplate;

            echo json_encode($data);
        }else{
            redirect("genConfigTun");
            exit();
        }

    }//f.getdatatunnel

    public function checkcidandtunnel(){
        if($this->input->post()){

            $data = $this->input->post();
            $query = (" SELECT tunnel_list.id, router.hostname, tunnel_list.host, cid, tunnel, if(cid= '".$data['cid']."',cid,'')as checkcid
                        FROM tunnel_list LEFT JOIN router ON tunnel_list.host = router.host
                        WHERE tunnel = 'tunnel".$data['tunnel']."' AND hostname = '".$data['hostname']."' AND tuneldelete = '0000-00-00 00:00:00' "); 
            $querycheck = $this->db->query($query);
            $querycheck = $querycheck->result_array();
            
            //$datars['query'] = $this->db->last_query();

            if(count($querycheck)==0){
                $datars['status'] = "new";
                $datars['id_check'] = "new";
            }elseif( (count($querycheck)!=0) && ($querycheck[0]['checkcid']=='') && ($querycheck[0]['cid']=='') ){
                $datars['status'] = "upcid";
                $datars['id_check'] = $querycheck[0]['id'];
            }elseif( (count($querycheck)!=0) && ($querycheck[0]['checkcid']=='') && ($querycheck[0]['cid']!='') ){
                $datars['status'] = "error";
                $datars['id_check'] = $querycheck[0]['id'];
            }elseif( (count($querycheck)!=0) && ($querycheck[0]['checkcid']!='') ){
                $datars['status'] = "have";
                $datars['id_check'] = $querycheck[0]['id'];
            }

            if($datars['status'] != "error"){
                $id_tem = $data['templates'];
                $querygetformTem = $this->model_template->getformTemplate($id_tem);
                $datars['namevari'] = $querygetformTem['name_tem'];
                $datars['arrstrvari'] = $this->formTOarray($querygetformTem['template']);

            }

            echo json_encode($datars);
            
        }else{
            redirect("genConfigTun");
            exit();
        }
    }//f. checkcidandtunnel


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


    public function formTOvariable($formtem){ //ใส่ตัวแปรแทน {}

        $strvari = $formtem;
        $arrstr = str_split($formtem);
        foreach ($arrstr as $key => $value) {

            if ($value == '{') {
                $start = $key;
            }elseif($value == '}'){
                $end = $key;
            }

            if(isset($start) && isset($end)){

                //หาชื้อตัวแปร
                $startgetname = $start+1;
                $strlength = $end - $startgetname;
                $getname=substr($formtem, $startgetname, $strlength);
                $getname=explode("|",$getname);
                $varName = "\$".$getname[0];


                //หาคำที่จะแทนที่
                $start = $start;
                $strlength = $end - $start+1;
                $cutstr=substr($formtem, $start, $strlength);
                
                //แทนที่คำ
                $strvari=str_replace($cutstr,$varName,$strvari);

                $start = null;
                $end = null;
            }
        }//foreach

        return $strvari;

    }//f.formTOvariable

    public function viewlistConfigbycid($id){
        $query = ("SELECT * FROM tunnel_list 
                    LEFT JOIN router ON tunnel_list.host = router.host
                    WHERE tunnel_list.id='".$id."' AND tuneldelete='0000-00-00 00:00:00'");
        $querygettunnel = $this->db->query($query);
        $querygettunnel = $querygettunnel->row_array();

        $query = ("SELECT data_template.id, id_tem, id_tunnel, data_tem, version_datatem, data_template.create_at, name_tem
                    FROM data_template LEFT JOIN form_template ON data_template.id_tem = form_template.tem_id
                    WHERE id_tunnel = '".$id."' AND data_template.delete_at = '0000-00-00 00:00:00'
                    ORDER BY id_tem DESC, data_template.create_at DESC ");
        $querygetlistconfig = $this->db->query($query);
        $querygetlistconfig = $querygetlistconfig->result_array();

        $data['querygettunnel'] = $querygettunnel; 
        $data['querygetlistconfig'] = $querygetlistconfig; 

        $this->load->view('header_view');
        $this->load->view('viewlistconfig_view',$data);
        $this->load->view('footer_view');

    }//f.viewlistConfigbycid


    public function delconfig($id,$id_tunnel){
        
        $query = (" UPDATE data_template SET delete_at = now() WHERE  id ='".$id."' ");
        $this->db->query($query);

        redirect("genConfigTun/viewlistConfigbycid/".$id_tunnel,"refresh");
        exit();        
        
    }//f.delconfig


    public function listviewgetconfig(){
        if($this->input->post()){

            $id = $this->input->post("id_data");
            $querygetdataTem = $this->model_template->getdataAndTemplate($id);  

            $strvari = $this->formTOvariable($querygetdataTem['template']);

            $arrdataTem = json_decode($querygetdataTem['data_tem'], true);
            foreach($arrdataTem as $f => $v ){
                //echo $f."=>".$v;
                eval("\$$f=\"\$v\";");
            }
            
            eval("\$strvari = \"$strvari\";");
            $querygetdataTem['template'] = $strvari;

            echo json_encode($querygetdataTem);
        }else{
            redirect("genConfigTun");
            exit();
        }
    }//f.listviewgetconfig



    public function listview_editconfig(){
        if($this->input->post()){

            $id = $this->input->post("id_data");
            $querygetdataTem = $this->model_template->getdataAndTemplate($id);
            $querygetdataTem['data_tem'] = json_decode($querygetdataTem['data_tem'], true);

            $querygetdataTem['template'] = $this->formTOarray($querygetdataTem['template']);

            echo json_encode($querygetdataTem);
            
        }else{
            redirect("genConfigTun");
            exit();
        }
    }//f.listview_editconfig


    
    public function submiteditDataformlist(){

        if($this->input->post()){
            $Datacon = $this->input->post("formtem");
            $myJSON = array_slice($Datacon, 3);
            $myJSON = json_encode($myJSON);

            
            $query = ("SELECT * FROM data_template WHERE id_tem = '".$Datacon['id_tem']."' AND id_tunnel = '".$Datacon['id_tunnel']."' AND delete_at = '0000-00-00 00:00:00' ORDER BY create_at DESC LIMIT 1 ");
            $querygetversionConfig = $this->db->query($query);
            $querygetversionConfig = $querygetversionConfig->row_array();
        
            if(count($querygetversionConfig)==0 || $querygetversionConfig==""){
                $versionTem = 1;
            }else{
                $versionTem = $querygetversionConfig['version_datatem']+1;
            }
            
            $query  = (" INSERT INTO data_template (id_tem, id_tunnel, data_tem, version_datatem)VALUES ('".$Datacon['id_tem']."', '".$Datacon['id_tunnel']."', '".$myJSON."', '".$versionTem."') ");
            $this->db->query($query);
                
            $this->session->set_flashdata('message', '1');
            
            redirect("genConfigTun/viewlistConfigbycid/".$Datacon['id_tunnel'],"refresh");
            exit();

        }else{
            redirect("genConfigTun","refresh");
            exit();
        }
        
    }//f.submiteditDataformlist


}//class