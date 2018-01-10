<?php
 
class Model_template extends CI_Model { // คลาส Model_template สืบทอดคุณสมบัติของ CI_Model

	public function getAllTemplate(){ // สร้าง Method ชื่อว่า getAllTemplate
	
		$query = ("SELECT * FROM form_template WHERE delete_at='0000-00-00 00:00:00' ORDER BY create_at DESC "); 
        $queryFT = $this->db->query($query);
        $queryFT = $queryFT->result_array();
/*
        foreach($queryFT as $tem){
            
            $query = ("SELECT * FROM data_template WHERE id_tem='".$tem['tem_id']."' ORDER BY update_at DESC LIMIT 1 ");
            $queryVT = $this->db->query($query);
            $queryVT = $queryVT->row_array();
            
            if(count($queryVT)>0 ){
                $artem[]=array('tem_id'=>$tem['tem_id'], 'name_tem'=>$tem['name_tem'], 
                                'create_at'=>$tem['create_at'],
                                'update_at'=>$queryVT['update_at'], 
                                'version_datatem'=>$queryVT['version_datatem'], );
            }else{
                $artem[]=array('tem_id'=>$tem['tem_id'], 'name_tem'=>$tem['name_tem'], 
                                'create_at'=>$tem['create_at'],
                                'update_at'=>$queryVT['update_at'], 
                                'version_datatem'=>$queryVT['version_datatem'], );
                
            }
            
        }*/
        return $queryFT;
        
    }//f.getAllTemplate
    
    public function getAllHost(){

        $query = ("SELECT * FROM router ORDER BY hostname ASC "); 
        $queryhost = $this->db->query($query);
        $queryhost = $queryhost->result_array();
        return $queryhost;

    }//f.getAllHost


    public function getAllTunnel(){

        $query = ("SELECT * FROM tunnel_list WHERE tuneldelete='0000-00-00 00:00:00' ORDER BY tunneladd DESC "); 
        $queryAT = $this->db->query($query);
        $queryAT = $queryAT->result_array();
        return $queryAT;

    }//f.getAllTunnel

    
    public function addTemplate($name,$template){

        $query  = (" INSERT INTO form_template (name_tem, template)VALUES ('".$name."', '".$template."') ");
        $this->db->query($query);

    } //f.addTemplate


    public function updateTextform($text_arr){

        $query = (" SELECT * FROM data_template WHERE id_tem = '".$text_arr['id_tem']."' AND delete_at = '0000-00-00 00:00:00' ");
        $querydataTem = $this->db->query($query);
        $querydataTem = $querydataTem->result_array();

        if(count($querydataTem)!=0){
            $query = (" UPDATE data_template SET delete_at = now() WHERE delete_at='0000-00-00 00:00:00' AND id_tem='".$text_arr['id_tem']."' ");
            $this->db->query($query);
        }

        $query  = (" UPDATE form_template SET template='".$text_arr['template']."' WHERE tem_id=".$text_arr['id_tem']." ");
        $this->db->query($query);

    }//f.updateTextform


    public function delTemplate($id){
        
        $query  = (" UPDATE form_template SET delete_at = now() WHERE tem_id = '".$id."' ");
        $this->db->query($query);

        $query = (" UPDATE data_template SET delete_at = now() WHERE delete_at='0000-00-00 00:00:00' AND id_tem='".$id."' ");
        $this->db->query($query);

    } //f.delTemplate


    public function getformTemplate($id){
        $query = ("SELECT * FROM form_template WHERE tem_id = '".$id."' AND delete_at='0000-00-00 00:00:00' "); 
        $querygetformTem = $this->db->query($query);
        return $querygetformTem->row_array();
        
    } //f.getformTemplate

    public function getdataTemplateforviewText($id){
        $query = ("SELECT * FROM data_template WHERE id_tem = '".$id."' AND delete_at='0000-00-00 00:00:00' "); 
        $querygetdataTem = $this->db->query($query);
        return $querygetdataTem->result_array();
        
    } //f.getdataTemplateforviewText

    public function getIPhost($hostname){
        $query = (" SELECT * FROM router WHERE hostname = '".$hostname."' "); 
        $getIPhost = $this->db->query($query);
        return $getIPhost->row_array();

    }//f.getIPhost

    public function getDatatunnel($id_tunnel){
        $query = (" SELECT tunnel_list.id, tunnel_list.host, hostname, cid, tunnel FROM tunnel_list 
                    LEFT JOIN router ON tunnel_list.host = router.host
                    WHERE tunnel_list.id = '".$id_tunnel."' AND tuneldelete='0000-00-00 00:00:00' ");
        
        $getDatatunnel = $this->db->query($query);
        return $getDatatunnel->row_array();

    }//f.getDatatunnel

    public function insertNewtunnel($cid, $tunnel, $IPhost){
        $INSERT  = (" INSERT INTO tunnel_list (cid, tunnel, host, tunneladd)
                VALUES ('".$cid."', 'Tunnel".$tunnel."', '".$IPhost."', now() ) ");
        $this->db->query($INSERT);
        return $this->db->insert_id();

    }//f.insertNewtunnel

    public function updateCIDtunnel($id_tunnel, $cid){
        $query  = (" UPDATE tunnel_list SET cid = '".$cid."', tunelupdate =  now() WHERE id = '".$id_tunnel."' ");
        $this->db->query($query);
    }//f.updateCIDtunnel

    public function getdataTemplate($id_tem, $id_tunnel){
        $query = ("SELECT * FROM data_template WHERE id_tem = '".$id_tem."' AND id_tunnel = '".$id_tunnel."' AND delete_at = '0000-00-00 00:00:00' ORDER BY create_at DESC LIMIT 1 ");
        $querygetdataTem = $this->db->query($query);
        return $querygetdataTem->row_array();

    } //f.getdataTemplate


    public function updateTemplate($data_arr,$id_tunnel){ //บันทึกข้อมูล gen config
        $myJSON = array_slice($data_arr, 7);
        $myJSON = json_encode($myJSON);

        $querygetversionTem = $this->getdataTemplate($data_arr['id_tem'], $id_tunnel);
        
        if(count($querygetversionTem)==0 || $querygetversionTem==""){
            $versionTem = 1;
        }else{
            $versionTem = $querygetversionTem['version_datatem']+1;
        }
        
        $query  = (" INSERT INTO data_template (id_tem, id_tunnel, data_tem, version_datatem)VALUES ('".$data_arr['id_tem']."', '".$id_tunnel."', '".$myJSON."', '".$versionTem."') ");
        $this->db->query($query);
        
        return $this->getdataTemplate($data_arr['id_tem'], $id_tunnel);

    } //f.updateTemplate


    public function insertConfig($data_arr){ //บันทึกข้อมูล gen config จาก upload file

        $myJSON=$data_arr[3];
        $id_tem=$data_arr[5];
        $id_tunnel=$data_arr[6];

        $querygetversionTem = $this->getdataTemplate($id_tem, $id_tunnel);
        
        if(count($querygetversionTem)==0 || $querygetversionTem==""){
            $versionTem = 1;
        }else{
            $versionTem = $querygetversionTem['version_datatem']+1;
        }
        
        $query  = (" INSERT INTO data_template (id_tem, id_tunnel, data_tem, version_datatem)VALUES ('".$id_tem."', '".$id_tunnel."', '".$myJSON."', '".$versionTem."') ");
        $this->db->query($query);

    } //f.insertConfig



    public function getdataAndTemplate($id){

        $query = ("SELECT id_tem, id_tunnel, name_tem, template, data_tem, version_datatem FROM data_template 
                    LEFT JOIN form_template ON data_template.id_tem = form_template.tem_id
                    WHERE id = '".$id."' ");
        $querygetdataTem = $this->db->query($query);
        return $querygetdataTem->row_array();

    } //f.getdataAndTemplate


    
	
}