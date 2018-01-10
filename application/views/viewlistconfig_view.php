    <!-- Page Content -->
    <div class="container">
        <h3 class="my-4">List config</h3>
    <div class="row">

        <div class="col-md-12">

            <!-- DataTables Card-->
            <div class="card mb-3">
                <div class="card-header" style="background-color: #28a745;color: #fff;">
                    <div class="row">

                        <div class="col-md-8">
                            <?php echo "(".$querygettunnel['host']." : ".$querygettunnel['cid']." : ".$querygettunnel['tunnel'].")" ;?>
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
                                <th>Config date</th>
                                <th>version</th>
                                <th>Command</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if(($querygetlistconfig=="")||(count($querygetlistconfig)==0)){
                            ?>
                                <tr><td colspan='6'align='center' >--no data--</td></tr>
                            <?php
                            }else{
            
                                $no=1;
                                foreach($querygetlistconfig as $config){
                            ?>
                                    <tr>
                                        <td> <?php echo $no ?> </td>
                                        <td> <?php echo $config['name_tem'] ?> </td>
                                        <td> <?php echo $config['create_at'] ?> </td>
                                        <td> <?php echo $config['version_datatem'] ?> </td>

                                        <td>
                                            <a btviewConfig="btviewConfig" id="<?=$config['id']?>" href="javascript:void(0)" > <i class="fa fa-file-text-o"></i></a>&nbsp;&nbsp;
                                            <a bteditConfig="bteditConfig" id="<?=$config['id']?>" href="javascript:void(0)" > <i class="fa fa-pencil-square-o fa-lg"></i></a>&nbsp;&nbsp;
                                            <a name="btdel" href= "<?=base_url()?>genConfigTun/delconfig/<?=$config['id']?>/<?=$config['id_tunnel']?>" onclick="javascript:return confirm('Do you want to delete it?');" > <i class="fa fa fa-trash-o fa-lg"></i></a>
                                            
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
            </div>

        </div>


    </div><!-- /.row -->

    <?php
        $this->load->view('modal/modal_new_view'); 
    ?>

    </div><!-- /.container -->

<script type="text/javascript">

$(document).ready(function(){
    <?php if($this->session->flashdata('message')=='1'){ ?>
    
        $('#bodyViewConfig').modal('show');
        
        $('#myModalSuccess').modal('show');
        setTimeout(function(){
            $('#myModalSuccess').modal('hide');
        }, 1000);
        
    <?php }?>
});

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


$("a[btviewConfig|='btviewConfig']").click(function(){
    var id_data=this.id;
    var dataI={"id_data":id_data};
    $.ajax({
        url : "<?php echo base_url(); ?>genConfigTun/listviewgetconfig",
        type : "POST",
        dataType : "json",
        data : dataI,
        success : function(data) {

            var datacreate ='<label class="control-label" ><b>Name Templates :</b> '+data['name_tem']+'</label>';
                datacreate +='<div class="form-group">';
                datacreate +='<label class="control-label" ><b>Config</b></label>';
                datacreate +='<textarea rows="12" name="formtem[template]" type="text" class="form-control input-md" readonly >'+data['template']+'</textarea>';
                datacreate +='</div>';
            
            $("#ViewTextconfig").html(datacreate);
            $('#myViewTextconfig').modal('show');

        }// success
    });// ajax
});// btn btnewCon



$("a[bteditConfig|='bteditConfig']").click(function(){
    var id_data=this.id;
    var dataI={"id_data":id_data};
    $.ajax({
        url : "<?php echo base_url(); ?>genConfigTun/listview_editconfig",
        type : "POST",
        dataType : "json",
        data : dataI,
        success : function(data) {
            console.log(data);

            if(data['template'].length != 0){
                var datacreate = '<input name="formtem[id_tem]" value="'+data['id_tem']+'" type="hidden" class="form-control input-md" >';
                    datacreate += '<input name="formtem[id_tunnel]" value="'+data['id_tunnel']+'" type="hidden" class="form-control input-md" >';

                    datacreate += '<div class="form-group">';
                    datacreate += '<label class="control-label" >Name Templates</label>';
                    datacreate += '<input name="formtem[name_tem]" type="text" value="'+data['name_tem']+'" placeholder="name templates" class="form-control input-md" readonly>';
                    datacreate += '</div>';
                
                    datacreate += '<div class="row">';
                $.each( data['template'], function( value ) {
                    datacreate += '<div class="col-md-6">';

                    if(data['template'][value].length == 2){
                        
                        
                        datacreate += '<div class="form-group">';
                        datacreate += '<label class="control-label" >'+data['template'][value]['1']+'</label>';
                        if (typeof data['data_tem'] !== 'undefined') {
                            datacreate += '<input name="formtem['+data['template'][value]['0']+']" type="text"';
                            datacreate += 'value="'+data['data_tem'][data['template'][value]['0']]+'" class="form-control input-md" required>';
                        }else{
                            datacreate += '<input name="formtem['+data['template'][value]['0']+']" type="text"';
                            datacreate += 'placeholder="'+data['template'][value]['1']+'" class="form-control input-md" required>';
                        }
                        
                        datacreate += '</div>';
                        
                    }else if(data['template'][value].length == 3){
                        datacreate += '<div class="form-group">';
                        datacreate += '<label class="control-label" >'+data['template'][value]['2']+'</label>';
                        datacreate += '<select class="form-control" name="formtem['+data['template'][value]['0']+']" required>';
                        
                        $.each( data['template'][value]['1'], function( valueselect , textselect ) {

                            if (typeof data['data_tem'] !== 'undefined') {
                                if( valueselect == data['data_tem'][data['template'][value]['0']] ){
                                    datacreate += '<option value="'+valueselect+'" selected > '+textselect+'</option>';

                                }else{
                                    datacreate += '<option value="'+valueselect+'" > '+textselect+'</option>';
                                }
                            }else{
                                datacreate += '<option value="'+valueselect+'" > '+textselect+'</option>';
                            }

                        });//each

                        datacreate += '</select>';
                        datacreate += '</div>';

                    }

                    datacreate += '</div>';

                });//each

                    datacreate += '</div>';

                $("#bodyEditmodalDataformlist").html(datacreate);
                $('#myModalEditDataformlist').modal('show'); 
            } else{
                $('#myModalTemno').modal('show'); 
                setTimeout(function(){
                    $('#myModalTemno').modal('hide');
                }, 2000);
            }// if-else

        }// success
    });// ajax
});// btn btnewCon



</script>