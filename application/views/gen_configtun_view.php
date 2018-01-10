
<!-- Page Content -->
<div class="container">
    <h3 class="my-4">Gen Config</h3>

    <div class="row">

        <div class="col-md-12">
            <!-- DataTables Card-->
            <div class="card mb-3">

                <div class="card-header" style="background-color: #0057ae;color: #fff;">
                    <div class="row">

                        <div class="col-md-8">
                            <!--<a class="btn btn-success" id="btnew">new templates <i class="fa fa-plus-circle"></i></a>-->
                        </div>

                        <div class="col-md-4" style="text-align: right;">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" style="color:#ffffff;padding:0;" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bars"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">
                                    <a btnewCon="btnewCon" class="dropdown-item" href="javascript:void(0)">new config <i class="fa fa-plus-circle"></i></a>
                                    <a class="dropdown-item" href="<?=base_url()?>genConfigTun/upfile">upload file <i class="fa fa-upload"></i></a>
                                </div>
                            </div>
                        </div>

                    </div><!-- row-->
                </div>

                <div class="card-body" >
                <div class="table-responsive">
                <!--<div class="scrollit">-->
                    <table class="table table-bordered" id="myTable" >
                        <thead>
                            <tr >
                                <th>No.</th>
                                <th>CID</th>
                                <th>Tunnel</th>
                                <th>Host</th>
                                <th>Host name</th>
                                <th>command</th>
                            </tr>
                        </thead>


                    </table>
                <!--</div>-->
                </div>
                </div>

            </div><!-- DataTables Card-->
        </div>

    </div><!-- /.row -->
    
    <?php
        $this->load->view('modal/modal_new_view'); 
    ?>

</div><!-- /.container -->


<script type="text/javascript">

$(document).ready(function(){
    <?php if($this->session->flashdata('message')=='1'){ ?>
            
        $('#myModalSuccess').modal('show');
        setTimeout(function(){
            $('#myModalSuccess').modal('hide');
        }, 1000);
        
    <?php }?>
});

$(function(){
    $('#myTable').DataTable( {
        "processing": true, // แสดงข้อความกำลังดำเนินการ กรณีข้อมูลมีมากๆ จะสังเกตเห็นง่าย
        "serverSide": true,  // ใช้งานในโหมด Server-side processing
        "order": [], // กำหนดให้ไม่ต้องการส่งการเรียงข้อมูลค่าเริ่มต้น จะใช้ค่าเริ่มต้นตามค่าที่กำหนดในไฟล์ php
        "ajax": {
            "url": "<?=base_url("Ajaxdata")?>", // ไฟล์ Server script php
            "type": "POST"  // ส่งข้อมูลแบบ post
        },
    } );
});//end f.DataTable

function myConbycid(id_tunnel) {
    
    var dataI={"id_tunnel":id_tunnel};
    $.ajax({
        url : "<?php echo base_url(); ?>genConfigTun/getdatatunnel",
        type : "POST",
        dataType : "json",
        data : dataI,
        success : function(data) {

            //console.log(data);
            //เอาคำว่า Tunnel ออก
            var res = data['getDatatunnel']['tunnel'].replace(/tunnel/gi, "");

            document.getElementById("hostname").value = data['getDatatunnel']['hostname'];
            document.getElementById("cid").value = data['getDatatunnel']['cid'];
            document.getElementById("tunnel").value = res;

            document.getElementById("hostname").readOnly = true;
            document.getElementById("cid").readOnly = true;
            document.getElementById("tunnel").readOnly = true;

            if(data['getDatatunnel']['cid']==""){
                document.getElementById("cid").readOnly = false;
            }
            if(data['getDatatunnel']['tunnel']==""){
                document.getElementById("tunnel").readOnly = false;
            }

            var ListTemplate ='';
            data['getAllTemplate'].forEach(function(entrytem) {
                ListTemplate +='<option value="'+entrytem['tem_id']+'" >'+entrytem['name_tem']+'</option>';
            });
            $("#templates").html(ListTemplate);

        }// success
    });// ajax
    $('#myModalnewcon').modal('show');

}// f.myFunction

$("a[btnewCon|='btnewCon']").click(function(){

    document.getElementById("hostname").value = '';
    document.getElementById("cid").value = '';
    document.getElementById("tunnel").value = '';
    
    document.getElementById("hostname").readOnly = false;
    document.getElementById("cid").readOnly = false;
    document.getElementById("tunnel").readOnly = false;

    var btnewCon="btnewCon";
    var dataI={"btnewCon":btnewCon};
    $.ajax({
        url : "<?php echo base_url(); ?>genConfigTun/getlisthost",
        type : "POST",
        dataType : "json",
        data : dataI,
        success : function(data) {

            var Listhost ='';
            var ListTemplate ='';
            data['getAllhost'].forEach(function(entryhost) {
                Listhost +='<option value="'+entryhost['hostname']+'" >'+entryhost['host']+'</option>';
            });
            $("#Listhost").html(Listhost);

            data['getAllTemplate'].forEach(function(entrytem) {
                ListTemplate +='<option value="'+entrytem['tem_id']+'" >'+entrytem['name_tem']+'</option>';
            });
            $("#templates").html(ListTemplate);

        }// success
    });// ajax
   
    $('#myModalnewcon').modal('show');

});// btn btnewCon



$("#btNextconfig").click(function(){
    var hostname = document.getElementById("hostname").value;
    var cid = document.getElementById("cid").value;
    var tunnel = document.getElementById("tunnel").value;
    var templates = document.getElementById("templates").value;

    if(hostname!="" && cid!="" && tunnel!="" && templates!=""){
      
        var dataI={"hostname":hostname,"cid":cid,"tunnel":tunnel,"templates":templates};

        $.ajax({
            url : "<?php echo base_url(); ?>genConfigTun/checkcidandtunnel",
            type : "POST",
            dataType : "json",
            data : dataI,
            success : function(data) {
                console.log(data);
                if(data['status']=='error'){
                    var texterror = "Something wrong !! CID or Tunnel";
                    $("#textwrong").html(texterror);

                    $('#myModalTemno').modal('show'); 
                    setTimeout(function(){
                        $('#myModalTemno').modal('hide');
                    }, 2000);
                }else{

                    if(data['arrstrvari'].length != 0){
                        var datacreate = '<div class="scrollit">';
                            datacreate += '<input name="formtem[id_tem]" type="hidden" value="'+templates+'"  class="form-control input-md" >';
                            datacreate += '<input name="formtem[hostname]" type="hidden" value="'+hostname+'" class="form-control input-md" >';
                            datacreate += '<input name="formtem[cid]" type="hidden" value="'+cid+'" class="form-control input-md" >';
                            datacreate += '<input name="formtem[tunnel]" type="hidden" value="'+tunnel+'" class="form-control input-md" >';
                            datacreate += '<input name="formtem[status]" type="hidden" value="'+data['status']+'" class="form-control input-md" >';
                            datacreate += '<input name="formtem[id_check]" type="hidden" value="'+data['id_check']+'" class="form-control input-md" >';

                            datacreate += '<div class="form-group">';
                            datacreate += '<label class="control-label" >Name Templates</label>';
                            datacreate += '<input name="formtem[name_tem]" type="text" value="'+data['namevari']+'" placeholder="name templates" class="form-control input-md" readonly>';
                            datacreate += '</div>';

                            datacreate += '<div class="row">';
                        $.each( data['arrstrvari'], function( value ) {
                            datacreate += '<div class="col-md-6">';

                            if(data['arrstrvari'][value].length == 2){
                                
                                datacreate += '<div class="form-group">';
                                datacreate += '<label class="control-label" >'+data['arrstrvari'][value]['1']+'</label>';
                                datacreate += '<input name="formtem['+data['arrstrvari'][value]['0']+']" type="text"';
                                datacreate += 'class="form-control input-md" required>';
                                datacreate += '</div>';
                                
                            }else if(data['arrstrvari'][value].length == 3){
                                datacreate += '<div class="form-group">';
                                datacreate += '<label class="control-label" >'+data['arrstrvari'][value]['2']+'</label>';
                                datacreate += '<select class="form-control" name="formtem['+data['arrstrvari'][value]['0']+']" required>';
                                
                                $.each( data['arrstrvari'][value]['1'], function( valueselect , textselect ) {
                                    datacreate += '<option value="'+valueselect+'" > '+textselect+'</option>';
                                });//each

                                datacreate += '</select>';
                                datacreate += '</div>';

                            }

                            datacreate += '</div>';

                        });//each

                            datacreate += '</div>';
                            datacreate += '</div>';

                        $("#mybodynewcon2").html(datacreate);
                        $('#myModalnewcon2').modal('show'); 
                        $('#myModalnewcon').modal('hide');
                    } else{
                        var textwrong = "Templase not form !!";
                        $("#textwrong").html(textwrong);

                        $('#myModalTemno').modal('show'); 
                        setTimeout(function(){
                            $('#myModalTemno').modal('hide');
                        }, 2000);
                    }// if-else create tem


                }//end if-else check status

                
            }// success
        });// ajax


    }else{
        var texterror = "Data Deficient!!";
        $("#textwrong").html(texterror);

        $('#myModalTemno').modal('show'); 
        setTimeout(function(){
            $('#myModalTemno').modal('hide');
        }, 2000);
    }

});// btNextconfig


$("#btBackconfig").click(function(){
    $('#myModalnewcon').modal('show');
    $('#myModalnewcon2').modal('hide');

});// btBackconfig

</script>