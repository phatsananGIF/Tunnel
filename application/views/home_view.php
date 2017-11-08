
<script src="<?=base_url()?>asset/vendor/Highcharts/code/highcharts.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/code/modules/exporting.js"></script>

<?php echo form_open('home');?>

    <!-- Page Content -->
    <div class="container">
    <div id="columnstacked" style="width: 100%; height: 400px; margin-bottom: 20px; margin-top: 20px;"></div>

              <!-- Marketing Icons Section -->
      <div class="row">

      
      <div class="col-md-12">
        <!-- DataTables Card-->
        <div class="card mb-3">
            <div class="card-header" style="background-color: #c80000;color: #fff;">
                <div class="row">

                    <div class="col-md-8">
                    Tunnel inactive to day (<?php echo $rsdownsum; ?>)
                    </div>

                    <div class="col-md-4">
                        <div class="input-group">

                            <input name="searchD" type="text" placeholder="Search" class="form-control input-md" 
                            <?php if($valuesearchD!=""){ echo "value = '".$valuesearchD."'";}?>>
                    
                            <span class="input-group-btn">
                                <button type="submit" name="btsearchD" class="btn btn-success" value="Search"><i class="fa fa-search"></i> </button>
                            </span>

                        </div>
                    </div>
                    
                </div><!-- row-->
            </div>

        <div class="card-body">
            <div class="table-responsive">
            <div class="scrollit">
                <table class="table table-bordered" >
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>CID</th>
                        <th>Tunnel</th>
                        <th>Host</th>
                        <th>Hostname</th>
                        <th>Tunnel down</th>
                        <th>Down time</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if(($rsdown=="")||(count($rsdown)==0)){
                    ?>
                        <tr><td colspan='7'align='center' >--no data--</td></tr>
                    <?php
                    }else{
    
                        $no=1;
                        foreach($rsdown as $r){
                    ?>
                            <tr>
                                <td> <?php echo $no ?> </td>
                                <td> <?php echo $r['cid'] ?> </td>
                                <td> <?php echo $r['tunnel'] ?> </td>
                                <td> <?php echo $r['host'] ?> </td>
                                <td> <?php echo $r['hostname'] ?> </td>
                                <td> <?php echo $r['tundown'] ?> </td>
                                <td> <?php echo $r['total_time'] ?> </td>
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



    <div class="col-md-12">
        <!-- DataTables Card-->
        <div class="card mb-3">
            <div class="card-header" style="background-color: #0057ae;color: #fff;">
                <div class="row">

                    <div class="col-md-8">
                    Tunnel inactive-active to day (<?php echo $rsUpdownsum; ?>)
                    </div>

                    <div class="col-md-4">
                        <div class="input-group">

                            <input name="searchUD" type="text" placeholder="Search" class="form-control input-md" 
                            <?php if($valuesearchUD!=""){ echo "value = '".$valuesearchUD."'";}?>>
                    
                            <span class="input-group-btn">
                                <button type="submit" name="btsearchUD" class="btn btn-success" value="Search"><i class="fa fa-search"></i> </button>
                            </span>

                        </div>
                    </div>
                    <?php echo form_close();?>
                </div><!-- row-->
            </div>

            <div class="card-body">
            <div class="table-responsive">
            <div class="scrollit">
                <table class="table table-bordered" >
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>CID</th>
                        <th>Tunnel</th>
                        <th>Host</th>
                        <th>Hostname</th>
                        <th>Tunnel down</th>
                        <th>Tunnel Up</th>
                        <th>Down time</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if(($rsUpdown=="")||(count($rsUpdown)==0)){
                    ?>
                        <tr><td colspan='8'align='center' >--no data--</td></tr>
                    <?php
                    }else{
    
                        $no=1;
                        foreach($rsUpdown as $r){
                    ?>
                            <tr>
                                <td> <?php echo $no ?> </td>
                                <td> <?php echo $r['cid'] ?> </td>
                                <td> <?php echo $r['tunnel'] ?> </td>
                                <td> <?php echo $r['host'] ?> </td>
                                <td> <?php echo $r['hostname'] ?> </td>
                                <td> <?php echo $r['tundown'] ?> </td>
                                <td> <?php echo $r['tunup'] ?> </td>
                                <td> <?php echo $r['total_time'] ?> </td>
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


    </div>
      <!-- /.row -->


    </div>
    <!-- /.container -->



<script type="text/javascript">


Highcharts.chart('columnstacked', {
    chart: {
        type: 'line',
        zoomType: 'xy'
    },

    title: {
        text: 'Tunnel Inactive and Inactive-Active in last 7 day'
    },

    xAxis: {
        categories: [<?php
                        foreach($rschart as $r){
                            echo "'".$r['MyDate']."',";
                        }
                    ?>]
    },

    yAxis: {
        title: {
            text: 'Total'
        }
    },

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },

     series: [{
        name: 'Inactive',
        color:'#c80000',
        data: [<?php
                foreach($rschart as $r){
                    echo $r['Down'].",";
                }
             ?>]
    }, {
        name: 'Inactive-Active',
        color:'#0057ae',
        data: [<?php
                foreach($rschart as $r){
                    echo $r['UpDown'].",";
                }
             ?>]
    }]
});
</script>