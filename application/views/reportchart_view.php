<script src="<?=base_url()?>asset/vendor/Highcharts/code/highcharts.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/code/modules/exporting.js"></script>

<?php echo form_open('reportchart');?>
    <!-- Page Content -->
    <div class="container">
        <h3 class="my-4">Report chart</h3>

        <div class="row" >
            
            <div class="col-md-12">
            <div class="input-group">

                <input id="valueSearch" name="valueSearch" type="text" placeholder="Search data" class="form-control input-md" list="List"
                <?php if($valueSearch!=""){ echo "value = '".$valueSearch."'";}?> />
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

                <span class="input-group-btn">
                    <button type="submit" name="btsearch" class="btn btn-bb" value="ค้นหา"><i class="fa fa-search"></i> Search</button>

                </span>

            </div>
            </div>

        </div>
    <?php echo form_close();?>
    <!-- /.row -->



        <div id="columnstacked" style="width: 100%; height: 400px; margin-bottom: 20px; margin-top: 20px;"></div>



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



Highcharts.chart('columnstacked', {
    chart: {
        type: 'column',
        zoomType: 'xy',
    },
    title: {
        text: 'Tunnel Inactive and Inactive-Active'
    },
    subtitle: {
        text:  ' <?php if($valueSearch !=""){ echo "(".$valueSearch.") ";}
                    echo $reportrange."<br/>";
                    if($total !=""){ echo " Total : ".$total." Inactive : ".$norschart[0]['Inactive']." InactiveActive : ".$norschart[0]['InactiveActive'];}
                ?>'
    },
    xAxis: {
        categories: [<?php 
                        if( ($rschart=="")||(count($rschart)==0) ){
                        }else{
                            foreach($rschart as $r){
                                echo "'".$r['MyDate']."',";
                            }
                        }
                    ?>]
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || '#000'
                
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>Total: {point.stackTotal}',
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || '#000',
                format: '<b>{point.y}</b> ({point.percentage:.0f}%)'
            }
            
        }
    },
    series: [{
        name: 'Inactive',
        color:'#c80000',
        data: [<?php 
                    if( ($rschart=="")||(count($rschart)==0) ){
                    }else{
                        foreach($rschart as $r){
                            echo $r['Inactive'].",";
                        }
                    }
                ?>]
    }, {
        name: 'Inactive-Active',
        color:'#0057ae',
        data: [<?php 
                    if( ($rschart=="")||(count($rschart)==0) ){
                    }else{
                        foreach($rschart as $r){
                            echo $r['InactiveActive'].",";
                        }
                    }
                ?>]
    }]
});


</script>