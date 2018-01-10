<!-- Page Content -->
<div class="container" style="margin-bottom:200px;">
    <h3 class="my-4">Upload file Config</h3>

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
                                <a btpreview="btpreview" class="dropdown-item" href="javascript:void(0)">file preview <i class="fa fa-file-text"></i></a>
                            </div>
                        </div>
                    </div>

                    </div><!-- row-->
                </div>

                <div class="card-body" style="text-align: center;">
                <!--<div class="scrollit">-->

                    <form action="<?php echo base_url(); ?>genConfigTun/upfile" method="post" name="upfile_config" enctype="multipart/form-data" >
                        <input type="file" name="file" id="file" >
                        <button type="submit" name="btupfile" class="btn btn-success" value="Add Tunnel">Upload</button>
                        </br>choose file type .csv
                        <p style="margin-bottom:0;margin-top:10px;">
                        <h6><span class="label label-default"><a style="color: #000;" href="<?=base_url()?>genConfigTun">close</a></span></h6>
                    </form>

                <!--</div>-->
                </div>

            </div><!-- DataTables Card-->
        </div>

    </div><!-- /.row -->
    
    <!-- Modal preview-->
<?php echo form_open('genConfigTun/upfile');?>
<div class="modal fade" id="myModalpreview" role="dialog">
    <div class="modal-dialog modal-lg">
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">File preview</h4>
            </div>

            <div class="modal-body" id="mybodypreview">
                <div class="input-group">
                    <select class="form-control" name="tem" >
                    <?php if(count($getAllTem)==0){ ?>
                        <option value="0">ยังไม่มีลูกค้า</option>
                    <?php }else{ 
                            foreach($getAllTem as $tem){ ?>
                            <option value="<?php echo $tem["tem_id"];?>" > <?php echo $tem["name_tem"];?></option>
                    <?php }
                        } ?>
                    </select>
                    <span class="input-group-btn">
                        <button type="submit" name="btpreview" class="btn btn-default" value="preview">file preview</button>
                    </span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    
    </div>
</div>
<?php echo form_close();?><!-- Modal preview-->

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

        
    <?php }elseif($this->session->flashdata('message')=='2'){ ?>

        var texterror = "Something wrong !!";
        $("#textwrong").html(texterror);

        $('#myModalTemno').modal('show'); 
        setTimeout(function(){
            $('#myModalTemno').modal('hide');
        }, 2000);

    <?php } ?>
});

$("a[btpreview|='btpreview']").click(function(){
    $('#myModalpreview').modal('show');
});// btpreview

</script>