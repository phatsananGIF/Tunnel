<?php echo form_open('reportsummary');?>

    <!-- Page Content -->
    <div class="container">

      <h3 class="my-4">Report summary</h3>

      <!-- Marketing Icons Section -->
      <div class="row" >

      <div class="col-md-12">
            <div class="input-group">

                <input id="valueSearch" name="valueSearch" type="text" placeholder="Search data" class="form-control input-md" list="List"
                <?php if($valueSearch!=""){ echo "value = '".$valueSearch."'";}?>  />
                <datalist id="List">
                    <?php  foreach($rsqueryHost as $r){ ?>
                        <option value="<?php echo $r['hostname'] ?>" />
                    <?php }?>
                </datalist>



                <input type="text" id="reportrange" name="reportrange" class="form-control input-md"
                <?php if($reportrange!=""){ echo "value = '".$reportrange."'";}?> />

                <select class="form-control" name="statusRe" >
                    <option value="2" <?php if($status=="Inactive-Active"){ echo "selected";}?> >Inactive-Active</option>
                    <option value="1" <?php if($status=="Inactive"){ echo "selected";}?> >Inactive</option>

                </select>
        
                <span class="input-group-btn">
                    <button type="submit" name="btsearchRe" class="btn btn-bb" value="ค้นหา"><i class="fa fa-search"></i> Search</button>
                    <button type="submit" name="btn_exportRe" class="btn btn-bb" value="Export"><i class="fa fa-external-link"></i> Excel</button>
                    <button type="submit" name="btn_exportCSV" class="btn btn-bb" value="Export"><i class="fa fa-external-link"></i> CSV</button>

                </span>

            </div>
        </div>

      </div>
      <?php echo form_close();?>
      <!-- /.row -->

      <div class="row">
      <div class="col-md-12">
      <div class="table-responsive">


            <table class="table table-hover" style="margin-top: 20px;">
                <thead style="background-color: #0057ae;color: #fff">
                    <tr style="height: 50px;">
                        <th colspan='3'><?php echo $status." ".$reportrange." The total ".$rsCOUNT ?></th>
                        <th></th>
                    </tr>
                </thead>
            
                <tbody class="table table-bordered">
                    <?php
                    if(($dataRS=="")||(count($dataRS)==0)){
                    ?>
                        <tr><td colspan='4' >--no data--</td></tr>
                    <?php
                    }else{
    
                        $no=0;
                        foreach($dataRS as $dr){

                            if( isset( $dr['hostname'] )){

                                $no=0;
                                ?>
                                    <tr style="background-color: #4b4b4b;color: #ffffff">
                                        <th colspan='4'><?php echo $dr['hostname']; ?></th>
                                    </tr>
                                    <tr>
                                        <th>NO.</th>
                                        <th>CID</th>
                                        <th>Tunnel</th>
                                        <th>Amount</th>
                                    </tr>
                                <?php

                            }else{
                                $no++;
                                ?>
                                    <tr class="small">
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $dr['cid'] ?></td>
                                        <td><?php echo $dr['tunnel'] ?></td>
                                        <td><?php echo $dr['sumt'] ?></td>
                                    </tr>
                                <?php
                                
                            }//if-else

                        }//for
                        
                    }//if-else
                    ?>
                </tbody>
            </table>

      </div>
      </div>
      </div><!-- /.row -->

    </div>
    <!-- /.container -->

 

<!-- Include Required Prerequisites -->
<script type="text/javascript" src="<?=base_url()?>asset/vendor/DateRangePicker/moment.min.js"></script>
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.css" />


<script type="text/javascript">
$(function() {
    $('#reportrange').daterangepicker({
        locale: {
            format: 'YYYY/MM/DD'
        }
    });

});

</script>