

<!-- Page Content -->
<div class="container">
    <h3 class="my-4">Template</h3>

    <div class="row">

        <div class="col-md-12">
            <!-- DataTables Card-->
            <div class="card mb-3">

                <div class="card-header" style="background-color: #0057ae;color: #fff;">
                    <div class="row">

                        <div class="col-md-8">
                            <!--<a class="btn btn-success" id="btnew">new templates <i class="fa fa-plus-circle"></i></a>-->
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">new templates <i class="fa fa-plus-circle"></i></button>

                        </div>

                        <div class="col-md-4">

                                <input name="search" id="myInput" type="text" placeholder="Search" onkeyup="mySearch()" class="form-control input-md" >
                        
                        </div>

                    </div><!-- row-->
                </div>

                <div class="card-body">
                <div class="table-responsive">
                <div class="scrollit">
                    <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name template</th>
                            <th>Create template</th>
                            <th>Update fill</th>
                            <th>Command</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if(($Alltemplate=="")||(count($Alltemplate)==0)){
                        ?>
                            <tr><td colspan='6'align='center' >--no data--</td></tr>
                        <?php
                        }else{
        
                            $no=1;
                            foreach($Alltemplate as $template){
                        ?>
                                <tr>
                                    <td> <?php echo $no ?> </td>
                                    <td> <?php echo $template['name_tem'] ?> </td>
                                    <td> <?php echo $template['create_at'] ?> </td>
                                    <td> <?php echo $template['update_at'] ?> </td>

                                    <td> 
                                        <a btviewText="btviewText" id="<?=$template['tem_id']?>" href="javascript:void(0)" > <i class="fa fa-file-text-o"></i></a>&nbsp;&nbsp; 
                                        <a btviewForm="btviewForm" id="<?=$template['tem_id']?>" href="javascript:void(0)" > <i class="fa fa-list-alt"></i></a>&nbsp;&nbsp; 
                                        <a name="btdel" href= "<?=base_url()?>templates/del/<?=$template['tem_id']?>" onclick="javascript:return confirm('Do you want to delete it?');" > <i class="fa fa fa-trash-o fa-lg"></i></a>
                                    </td>
                                <tr>
                        <?php
                                $no++;
                        
                            }
                        }
                        ?>
                    </tbody>

                    </table>
                </div>
                </div>
                </div>

            </div><!-- DataTables Card-->
        </div>

    </div><!-- /.row -->


    
    <?php echo form_open('templates/submitnew');?>
        <!-- Modal new-->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
            
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">New Templates</h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" >Name Templates</label>  
                            <input name="formtem[name_tem]" type="text" 
                            placeholder="name templates" class="form-control input-md" required>
                        </div>

                        <div class="form-group">
                            <label class="control-label" >Form Templates</label>  
                            <textarea rows="12"  name="formtem[template]" type="text" 
                            placeholder="form templates" class="form-control input-md" required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="btsave" class="btn btn-primary" >Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            
            </div>
        </div>
    <?php echo form_close();?>



    <?php echo form_open('templates/submitUpdateTem');?>
        <!-- Modal viewText-->
        <div class="modal fade" id="myModalviewText" role="dialog">
            <div class="modal-dialog">
            
                <!-- Modal content-->
                <div class="modal-content" id="ViewmodalText">

                </div>
            
            </div>
        </div><!-- Modal viewText-->
    <?php echo form_close();?>


    <!-- Modal viewForm-->
    <div class="modal fade" id="myModalviewForm" role="dialog">
        <div class="modal-dialog modal-lg">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Write data to templates</h4>
                </div>

                <div class="modal-body" id="bodyviewFormmodal">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        
        </div>
    </div><!-- Modal viewForm-->


    <!-- Modal success-->
    <div class="modal fade" id="myModalSuccess" role="dialog">
        <div class="modal-dialog modal-sm">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div style="text-align:center;color:#28a745;margin-top:20px;">
                    <i class="fa fa-check-circle-o fa-4x" ></i>
                    <p><strong>Success!</strong></p>
                </div>
            </div>
        
        </div>
    </div><!-- Modal success-->


    <!-- Modal wrong-->
    <div class="modal fade" id="myModalTemno" role="dialog">
        <div class="modal-dialog modal-sm">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div style="text-align:center;color:#dc3545;margin-top:20px;">
                    <i class="fa fa-times-circle-o fa-4x" ></i>
                    <p><strong>Templase not form !!</strong></p>
                </div>
            </div>
        
        </div>
    </div><!-- Modal wrong-->

</div><!-- /.container -->


<script type="text/javascript">

$(document).ready(function(){
    <?php if($this->session->flashdata('message')=='1'){ ?>
        $('#myModalSuccess').modal('show');
        setTimeout(function(){
            $('#myModalSuccess').modal('hide');
        }, 1000);
    <?php }?>
});//document ready


function mySearch() {
  var input, filter, table, tr, td, i, j;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  
  for (i = 0; i < tr.length; i++) {
    tdc = tr[i].getElementsByTagName("td");
    if(tdc!=0){
        for (j = 1; j < tdc.length-1; j++) {
            td = tr[i].getElementsByTagName("td")[j];
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
                break;
            } else {
                tr[i].style.display = "none";
            }
        }
    } 
  }
}//mySearch

function myChecked() {
    document.getElementById("btUpdateTem").style.display = "block";
    document.getElementById("myTextarea").readOnly = false;
}//myChecked

function myUnChecked() {
    document.getElementById("btUpdateTem").style.display = "none";
    document.getElementById("myTextarea").readOnly = true;
}//myUnChecked


$("a[btviewText|='btviewText']").click(function(){
    var idtem=this.id;
    var dataI={"idtem":idtem};
    console.log(dataI);
    $.ajax({
        url : "<?php echo base_url(); ?>templates/viewText",
        type : "POST",
        dataType : "json",
        data : dataI,
        success : function(data) {
            console.log(data);
            var datacreate ='<div class="modal-header">';
                datacreate +='<button type="button" class="close" data-dismiss="modal">&times;</button>';
                datacreate +='<h4 class="modal-title">Templates</h4>';
                datacreate +='</div>';

                datacreate +='<div class="modal-body">';
                datacreate += '<input name="formtem[id_tem]" value="'+idtem+'" type="hidden" class="form-control input-md" >';

                datacreate +='<div class="form-group">';
                datacreate +='<label class="control-label" >Name Templates</label>';
                datacreate +='<input name="formtem[name_tem]" value="'+data['namevari']+'" type="text" class="form-control input-md" readonly >';
                datacreate +='</div>';

                datacreate +='<div class="form-group">';
                datacreate +='<label class="control-label" >Templates</label>';
                if(data['status']=="notuse"){
                    datacreate +='<textarea rows="12" name="formtem[template]" type="text" class="form-control input-md" >'+data['strvari']+'</textarea>';
                }else if(data['status']=="use"){
                    datacreate +='<textarea rows="12" name="formtem[template]" type="text" class="form-control input-md" id="myTextarea" readonly >'+data['strvari']+'</textarea>';
                    datacreate +='<p style="margin: 10px;"><i class="fa fa-exclamation-triangle" style="color:#ff3707;"></i><strong> Can not edit text!</strong> From is use.</p>';
                    datacreate +='<input type="checkbox" onclick="if(this.checked){myChecked()}else{myUnChecked()}"> Confirm to edit and delete usage data.';
                }
                datacreate +='</div>';

                datacreate +='</div>';

                datacreate +='<div class="modal-footer">';
                if(data['status']=="notuse"){
                    datacreate +='<button type="submit" name="btUpdateTem" class="btn btn-primary" >Save</button>';
                    datacreate +='<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                }else if(data['status']=="use"){
                    datacreate +='<button type="submit" name="btUpdateTem" id="btUpdateTem" class="btn btn-primary" style="display:none;">Save</button>';
                    datacreate +='<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                }
                datacreate +='</div>';
              
            $("#ViewmodalText").html(datacreate);
            $('#myModalviewText').modal('show');
        }// success
    });// ajax
    
});// btviewText


$("a[btviewForm|='btviewForm']").click(function(){
    var idtem=this.id;
    var dataI={"idtem":idtem};
    $.ajax({
        url : "<?php echo base_url(); ?>templates/viewform",
        type : "POST",
        dataType : "json",
        data : dataI,
        success : function(data) {
            if(data['arrstrvari'].length != 0){
                var datacreate = '<div class="form-group">';
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

                $("#bodyviewFormmodal").html(datacreate);
                $('#myModalviewForm').modal('show'); 
            } else{
                $('#myModalTemno').modal('show'); 
                setTimeout(function(){
                    $('#myModalTemno').modal('hide');
                }, 2000);
            }// if-else

         }// success
    });// ajax
});// btviewForm

</script>
