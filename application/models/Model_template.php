<?php
 
class Model_template extends CI_Model { // คลาส Model_template สืบทอดคุณสมบัติของ CI_Model
	
	public function getAllTemplate() // สร้าง Method ชื่อว่า getAllTemplate
	{ 
		$query = ("SELECT * FROM form_template WHERE delete_at='0000-00-00 00:00:00' ORDER BY create_at DESC "); 
        $queryFT = $this->db->query($query);
        $queryFT = $queryFT->result_array();

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
            
        }
        return $artem;
        
    }//f.getAllTemplate
    
    public function addTemplate($name,$template){

        $query  = (" INSERT INTO form_template (name_tem, template)VALUES ('".$name."', '".$template."') ");
        $this->db->query($query);

    } //f.addTemplate

    public function delTemplate($id){
        
        $query  = (" UPDATE form_template SET delete_at = '".date('Y-m-d H:i:s')."' WHERE tem_id = '".$id."' ");
        $this->db->query($query);

    } //f.delTemplate

    public function getformTemplate($id){
        $query = ("SELECT * FROM form_template WHERE tem_id = '".$id."' AND delete_at='0000-00-00 00:00:00' "); 
        $querygetformTem = $this->db->query($query);
        return $querygetformTem->row_array();
        
    } //f.getformTemplate

    public function getdataTemplate($id){
        $query = ("SELECT * FROM data_template WHERE id_tem = '".$id."' ORDER BY update_at DESC LIMIT 1 ");
        $querygetdataTem = $this->db->query($query);
        return $querygetdataTem->row_array();

    } //f.getdataTemplate

    public function updateTemplate($data_arr){
        $myJSON = array_slice($data_arr, 2);
        $myJSON = json_encode($myJSON);

        $querygetversionTem = $this->getdataTemplate($data_arr['id_tem']);
        
        if(count($querygetversionTem)==0 || $querygetversionTem==""){
            $versionTem = 1;
        }else{
            $versionTem = $querygetversionTem['version_datatem']+1;
        }
        

        $query  = (" INSERT INTO data_template (id_tem, data_tem, version_datatem)VALUES ('".$data_arr['id_tem']."', '".$myJSON."', '".$versionTem."') ");
        $this->db->query($query);


    } //f.updateTemplate
	
}