<!-- Modal new 1-->
<div class="modal fade" id="myModalnewcon" role="dialog">
<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Write data to gen config</h4>
        </div>

        <div class="modal-body" id="bodyNewcon">
            <div class="form-group">
                <label class="control-label" >Host</label>  
                <input id="hostname" type="text" placeholder="hostname" class="form-control input-md" list="Listhost"  >
                <datalist id="Listhost"></datalist>
            </div>

            <div class="form-group">
                <label class="control-label" >CID</label>  
                <input id="cid" type="text" placeholder="cid" class="form-control input-md" >
            </div>

            <div class="form-group">
                <label class="control-label" >Tunnel</label>
                <input id="tunnel" type="text" placeholder="tunnel" class="form-control input-md" >
            </div>

            <div class="form-group">
                <label class="control-label" >Templates</label>
                <select id="templates" class="form-control" > </select>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" id="btNextconfig" class="btn btn-primary" >Next</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>

</div>
</div><!-- Modal new 1-->


<!-- Modal new 2-->
<?php echo form_open('genConfigTun/submitnew');?>
<div class="modal fade" id="myModalnewcon2" role="dialog">
    <div class="modal-dialog modal-lg">
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Write data to gen config</h4>
            </div>

            <div class="modal-body" id="mybodynewcon2">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btBackconfig" >Back</button>
                <button type="submit" name="btsavedatatem" class="btn btn-primary" >Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    
    </div>
</div>
<?php echo form_close();?><!-- Modal new 2-->

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
                <p><strong id="textwrong">Templase not form !!</strong></p>
            </div>
        </div>
    
    </div>
</div><!-- Modal wrong-->


<!-- Modal viewText-->
<div class="modal fade" id="myModalviewText" role="dialog">
    <div class="modal-dialog">
    
        <!-- Modal content-->
        <div class="modal-content" id="ViewmodalText">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gen config</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" ><b>Host :</b> <?php echo $this->session->flashdata('host'); ?></label>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" ><b>CID :</b> <?php echo $this->session->flashdata('cid'); ?></label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" ><b>Tunnel :</b> <?php echo $this->session->flashdata('tunnel'); ?></label>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" ><b>Name Templates :</b> <?php echo $this->session->flashdata('nametem'); ?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label" ><b>Config</b></label>
                    <textarea rows="12" name="formtem[template]" type="text" class="form-control input-md" readonly ><?php echo $this->session->flashdata('textconfig'); ?></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>
    
    </div>
</div><!-- Modal viewText-->



<!-- Modal viewText from list config -->
<div class="modal fade" id="myViewTextconfig" role="dialog">
    <div class="modal-dialog">
    
        <!-- Modal content-->
        <div class="modal-content" >

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Config</h4>
            </div>


            <div class="modal-body" id="ViewTextconfig">
               
                <label class="control-label" ><b>Name Templates :</b> <?php echo $this->session->flashdata('nametem'); ?></label>

                <div class="form-group">
                    <label class="control-label" ><b>Config</b></label>
                    <textarea rows="12" name="formtem[template]" type="text" class="form-control input-md" readonly ><?php echo $this->session->flashdata('textconfig'); ?></textarea>
                </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    
    </div>
</div><!-- Modal viewText from list config -->


<?php echo form_open('genConfigTun/submiteditDataformlist');?>
    <!-- Modal edit-->
    <div class="modal fade" id="myModalEditDataformlist" role="dialog">
        <div class="modal-dialog modal-lg">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit data to config</h4>
                </div>

                <div class="modal-body" id="bodyEditmodalDataformlist">
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btsavedata" class="btn btn-primary" >Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        
        </div>
    </div><!-- Modal edit-->
<?php echo form_close();?>

