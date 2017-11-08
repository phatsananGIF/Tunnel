<?php echo form_open('report');?>

    <!-- Page Content -->
    <div class="container">

      <h3 class="my-4">Report data</h3>

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
                    <?php  foreach($rsqueryTunnel as $r){ ?>
                        <option value="<?php echo $r['tunnel'] ?>" />
                    <?php }?>
                </datalist>



                <input type="text" id="reportrange" name="reportrange" class="form-control input-md"
                <?php if($reportrange!=""){ echo "value = '".$reportrange."'";}?> />

                <select class="form-control" name="statusRe" >
                    <option value="1" <?php if($status=="1"){ echo "selected";}?> >Inactive</option>
                    <option value="2" <?php if($status=="2"){ echo "selected";}?> >Inactive-Active</option>
                    <option value="3" <?php if($status=="3"){ echo "selected";}?> >All</option>
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
        
            
        
            <table id="tbReport" class="table table-hover" style="margin-top: 20px;">
            <thead style="background-color: #0057ae;color: #fff">
                <tr class="info">

                    <th>No.</th>
                    <th>CID</th>
                    <th>Tunnel</th>
                    <th>Host</th>
                    <th>Hostname</th>

                    <?php if($status=="1"){ ?>
                        <th>Tunnel down</th>
                    <?php }?>

                    <?php if($status=="2"){ ?>
                        <th>Tunnel down</th>
                        <th>Tunnel Up</th>
                        <th>Total time</th>
                    <?php }?>

                    <?php if($status=="3"){ ?>
                        <th>Tunnel down</th>
                        <th>Tunnel Up</th>
                        <th>Total time</th>
                        <th>Status</th>
                    <?php }?>
                   
                    
                </tr>
            </thead>

            <tbody>
                <?php
                if(($rs=="")||(count($rs)==0)){
                ?>
                    <tr><td colspan='9' >--no data--</td></tr>
                <?php
                }else{
 
                    $no=1;
                    foreach($rs as $r){
                ?>
                        <tr class="small">
                            <td> <?php echo $no ?> </td>
                            <td> <?php echo $r['cid'] ?> </td>
                            <td> <?php echo $r['tunnel'] ?> </td>
                            <td> <?php echo $r['host'] ?> </td>
                            <td> <?php echo $r['hostname'] ?> </td>


                            <?php if($status=="1"){ ?>
                                <td> <?php echo $r['tundown'] ?> </td>
                            <?php }?>

                            <?php if($status=="2"){ ?>
                                <td> <?php echo $r['tundown'] ?> </td>
                                <td> <?php echo $r['tunup'] ?> </td>
                                <td> <?php echo $r['total_time'] ?> </td>
                            <?php }?>

                            <?php if($status=="3"){ ?>
                                <td> <?php echo $r['tundown'] ?> </td>

                                <td> <?php
                                    if($r['tunup']!= '0000-00-00 00:00:00'){
                                        echo $r['tunup'];
                                    }
                                ?> </td>

                                <td> <?php  
                                    if($r['status'] == 'Inactive-Active'){
                                        echo $r['total_time'];
                                    }
                                ?> </td>

                                <td> <?php echo $r['status'] ?> </td>
                            <?php }?>



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