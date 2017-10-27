
<?php echo form_open('insert/edit/'.$rstunnel['id']);?>

    <!-- Page Content -->
    <div class="container">

      <h3 class="my-4">Add new tunnel</h3>

      <div class="row">
         

            <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="phone">Tunnel</label>  
                <input  name="edittunnel[tunnel]" value="<?php echo $rstunnel['tunnel']; ?>"
                type="text" placeholder="tunnel" class="form-control input-md" readonly />
            </div>

            <div class="form-group">
                <label class="control-label" >CID</label>  
                <input name="edittunnel[cid]" value="<?php 
                    if(set_value('edittunnel[cid]') != ""){
                        echo set_value('edittunnel[cid]');
                    }else if($rstunnel['cid'] != ""){
                        echo $rstunnel['cid'];
                    } ?>" 
                type="text" placeholder="CID" class="form-control input-md" >
                <?php echo form_error('edittunnel[cid]'); ?>
            </div>
            </div>

            <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="cus">Host</label> 
                <input  name="edittunnel[host]" value="<?php echo $rstunnel['host']; ?>"
                type="text" placeholder="tunnel" class="form-control input-md" readonly />

            </div>
            </div>

            
            <div class="col-md-12">
            <div class="form-group">
            <label class="control-label" for="save"></label>
                <input type="submit" name="btsave" value="บันทึก" class="btn btn-primary" />&nbsp;
                <?php echo anchor("insert","ยกเลิก");?>              
            </div>
            </div>


      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->
    <?php echo form_close();?>

