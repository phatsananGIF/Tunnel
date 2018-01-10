<?Php

class Ajaxdata extends CI_Controller {
    private $table = "tunnel_list"; // กำหนดชื่อตารางข้อมูล
    // กำหนดฟิลด์ข้อมูลที่สามารถให้ค้นหาข้อมูลได้
    private $column_search = array("cid", "tunnel", "tunnel_list.host", "hostname");
    // กำหนดฟิลด์ข้อมูลที่สามารถให้เรียงข้อมูลได้
    private $column_order = array(NULL, "cid", "tunnel", "tunnel_list.host", "hostname", NULL);
    // กำหนดฟิลด์ข้อมูลที่่ต้องการเรียงข้อมูลเริ่มต้น และรูปแบบการเรียงข้อมูล
    private $order = array("tunnel_list.id"=>"DESC");


    public function __construct(){
        parent::__construct();
    }

    public function index(){
         
        $data = array();
        $_draw = $this->input->post('draw'); // ครั้งที่การดึงข้อมูล ค่าของ dataTable ส่งมาอัตโนมัติ
        $_p = $this->input->post('search'); // ตัวแปรคำค้นหาถ้ามี
        $_earchValue = $_p['value']; // ค่าคำค้นหา
        $_order = $this->input->post('order'); // ตัวแปรคอลัมน์ที่ต้องการเรียงข้อมูล
        $_length = $this->input->post('length'); // ตัวแปรจำนวนรายการที่จะแสดงแต่ละหน้า
        $_start = $this->input->post('start'); // เริ่มต้นที่รายการ
        $this->db->select('tunnel_list.id, cid, tunnel, tunnel_list.host, hostname');
        $query = $this->db->from($this->table);  // ดึงข้อมูลจากตารางที่กำหนด
        $this->db->join('router', 'tunnel_list.host = router.host', 'left');
        $this->db->where('tuneldelete', '0000-00-00 00:00:00');
        $total_rows_all = $this->db->count_all_results(null,FALSE); // เก็บค่าจำนวนรายการทั้งหมด      
        $i = 0;    
        // วนลูปฟิลด์ที่ต้องการค้นหา กรณีมีการส่งคำค้น เข้ามา
        foreach ($this->column_search as $item){
            if($_earchValue){ // ถ้ามีค่าคำค้น
                // จัดรูปแแบคำสั่ง sql การใช้งาน OR กับ LIKE
                if($i===0){ // ถ้าเป็นค่าเริ่มเต้นให้เปิดวงเล็บ (
                    $this->db->group_start(); 
                    $this->db->like($item, $_earchValue);                 
                }else{
                    $this->db->or_like($item, $_earchValue);
                }
                if(count($this->column_search) - 1 == $i){ // ถ้าเป็นต้วสุดท้ายให้ปิดวงเล็บ )
                    $this->db->group_end();
                }
            }
            $i++;
            // ส่วนของการวนลูปนี้จะได้รูปแบบ เช่น ( fileld1 LIKE 'a' OR field2 LIKE 'a' )  เป็นต้น
        }  
        // ถ้ามีการส่งฟิลด์ที่ต้องการเรียงข้อมูลเข้ามา เช่น กรณีกดที่หัวข้อในตาราง dataTable
        if(isset($_order) && $_order!=NULL){
            // จัดรูปแบบการจัดเรียงข้อมูลจากค่าที่ส่งมา
            $_orderColumn = $_order['0']['column'];
            $_orderSort = $_order['0']['dir'];
            $this->db->order_by($this->column_order[$_orderColumn], $_orderSort);
        }else{ // กรณีไม่ได้ส่งค่าในตอนต้น ให้ใช้ค่าตามที่กำหนด
            // จัดรูปแบบการจัดเรียง  ตามที่กำหนดด้ายตัวแปร $order ด้านบน
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);            
        }
        $total_rows_filter = $this->db->count_all_results(null,FALSE); // กำหนดค่าจำนวนข้อมูลหลังมีเงื่อนไขต่างๆ          
        if($_length != -1){ // กรณีมีการกำหนดว่าต้องการแสดงข้อมูลหน้าละกี่รายการ
            $this->db->limit($_length, $_start); // จัดรูปแบบการแสดง ผลที่ได้เช่น LIMIT 10,10
        }   
        $query = $this->db->get(); // คิวรี่ข้อมูลตาเงื่อนไข

        // วนลูปนำฟิลด์รายการที่ต้องการและสอดคล้องกันมาไว้ในตัวแปร array ที่ชื่อ $data
        $no=1;
        foreach ($query->result_array() as $row){
            $command = '<a href="'.base_url().'genConfigTun/viewlistConfigbycid/'.$row['id'].'" > <i class="fa fa-list-alt fa-lg"></i></a>&nbsp;&nbsp; <a href= "javascript:void(0)" onclick="myConbycid('.$row['id'].')" > <i class="fa fa-pencil-square-o fa-lg"></i></a>';
            
            $data[] = array(
                $no,
                $row['cid'],
                $row['tunnel'],
                $row['host'],
                $row['hostname'],
                $command
            );
            $no++;
        }
        // กำหนดรูปแบบ array ของข้อมูลที่ต้องการสร้าง JSON data ตามรูปแบบที่ DataTable กำหนด
        $output = array(
            "draw" => $_draw, // ครั้งที่เข้ามาดึงข้อมูล
            "recordsTotal" => $total_rows_all, // ข้อมูลทั้งหมดที่มี
            "recordsFiltered" => $total_rows_filter, // ข้อมูลเฉพาะที่เข้าเงื่อนไข เช่น ค้นหา แล้ว       
            "data" => $data // รายการ array ข้อมูลที่จะใช้งาน
        );
        echo json_encode($output);
        exit();         
    }

}//class