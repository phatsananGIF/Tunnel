
<?php echo form_open('insert/add');?>

    <!-- Page Content -->
    <div class="container">

      <h3 class="my-4">Add new tunnel</h3>

      <div class="row">
         

            <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="phone">Tunnel</label>  
                <input  name="addtunnel[tunnel]" value="<?php echo set_value('addtunnel[tunnel]'); ?>" type="text"
                placeholder="tunnel" class="form-control input-md" >
                <?php echo form_error('addtunnel[tunnel]'); ?>
            </div>

            <div class="form-group">
                <label class="control-label" >CID</label>  
                <input name="addtunnel[cid]" value="<?php echo set_value('addtunnel[cid]'); ?>" type="text" 
                placeholder="CID" class="form-control input-md" >
                <?php echo form_error('addtunnel[cid]'); ?>
            </div>
            </div>

            <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="cus">Host</label> 
                <select multiple class="form-control" name="addtunnel[host]" >
                <?php if(count($rsrouter)==0){ ?>
                    <option value="0">ยังไม่มีลูกค้า</option>
                <?php }else{ 
                        foreach($rsrouter as $r){
                            $s="";
                            if(set_value('addtunnel[host]')== $r["host"]){
                                $s="selected";
                            }
                            ?>

                        <option value="<?php echo $r["host"];?>" <?php echo $s ?> > <?php echo $r["host"]." (".$r["hostname"].")";?></option>
                <?php }
                    } ?>
                </select>

                <?php echo form_error('addtunnel[host]'); ?>
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

