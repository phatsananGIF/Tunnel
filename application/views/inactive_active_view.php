<script src="<?=base_url()?>asset/vendor/Highcharts/code/highcharts.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/code/modules/heatmap.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/code/modules/exporting.js"></script>


<script src="<?=base_url()?>asset/vendor/Highcharts/meget/rgbcolor.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/meget/StackBlur.js"></script>
<script src="<?=base_url()?>asset/vendor/Highcharts/meget/canvg.js"></script>


<?php echo form_open('InactiveActive');?>

<!-- Page Content -->
<div class="container">
    <h3 class="my-4">Chart Inactive-Active</h3>
    <div class="row" >
        
        <div class="col-md-6">
        <div class="input-group">

        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                Select Host
            </button>
            <ul class="dropdown-menu" style="width: 300px; height: 400px; overflow: auto">
                <li>
                    <?php
                    foreach($allhost as $dr){
                        $ch="";
                        if($hostselect != ""){
                            foreach($hostselect as $rh){
                                if($dr['hostname']== $rh['hostname']){
                                    $ch="checked";
                                    break;
                                }
                            }
                        }
                    ?>
                    <div style="margin-left: 10px;">
                        <input type="checkbox" id="<?php echo $dr['hostname'];?>" name="check_list[]" value="<?php echo $dr['hostname'];?>" <?php echo $ch ?>>
                        <label for="<?php echo $dr['hostname'];?>"><?php echo $dr['hostname'];?></label>
                    </div>

                    <?php
                    }//for
                    ?>
                
                </li>
            </ul>
        </div>

            <input type="text" id="reportrange" name="reportrange" class="form-control input-md"
            <?php if($reportrange!=""){ echo "value = '".$reportrange."'";}?> />

            <input type="text" id="IDbase64" name="IDbase64" style="display:none;" class="form-control input-md" />

            <span class="input-group-btn">
                <button type="submit" name="btsearch" class="btn btn-bb" value="ค้นหา"><i class="fa fa-search"></i> Search</button>

            </span>

        </div>
        </div>

    </div>
<?php echo form_close();?>

    <div id="columncharts" style="width: 100%; height: 400px; margin-bottom: 20px; margin-top: 20px;"></div>
    <div id="heatmap" style="width: 100%; height: 
    <?php if(count($rsdate)<=10){
        echo "400px;";
    }else{echo "800px;"; } ?>
    margin-bottom: 20px; margin-top: 20px;">
    </div>


            <!-- canvas tag to convert SVG -->
            <canvas id="canvas" style="display:none;"></canvas>
            
</div><!-- /.container -->


<!-- Include Required Prerequisites -->
<script type="text/javascript" src="<?=base_url()?>asset/vendor/DateRangePicker/moment.min.js"></script>
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>asset/vendor/DateRangePicker/daterangepicker.css" />

<script type="text/javascript">

///////////////// ดึงรูป base64
/*
$(function () {
    var svg = document.getElementById('columncharts').children[0].innerHTML;
    canvg(document.getElementById('canvas'),svg);
    var img = canvas.toDataURL("image/png"); //img is data:image/png;base64
    img = img.replace('data:image/png;base64,', '');
    var data = "bin_data=" + img;
    console.log(data);
    var a = document.getElementById("IDbase64").value = img;

}); */

////////////

$(function() {
    $('#reportrange').daterangepicker({
        locale: {
            format: 'YYYY/MM/DD'
        }
    });

});


Highcharts.chart('columncharts', {
    chart: {
        type: 'column',
        zoomType: 'xy'
    },
    title: {
        text: 'Tunnel Inactive-Active'
    },
    
    subtitle: {
        text: '<?php echo $reportrange;?>'
    },

    xAxis: {
        categories: [
            <?php
            foreach($rsdate as $dr){
                echo "'".$dr['MyDate']."',";
            }//for
            ?>
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total'
        }
    },
    tooltip: {
        /*
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
        footerFormat: '</table>',*/

        headerFormat: '<span><b>{point.key}</b></span> <br/>',
        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:.0f}</b></span> <br/>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [
    <?php
    foreach($dataRS as $dr){

        echo "{name: '".$dr['hostname']."',color: '".$dr['color']."',data: [".$dr['InactiveActive']."]},";
    }//for
    ?>
    ]
});


Highcharts.chart('heatmap', {
    
        chart: {
            type: 'heatmap',
            zoomType: 'xy'
        },
    
        title: {
            text: 'Tunnel Inactive-Active'
        },

        subtitle: {
            text: '<?php echo $reportrange;?>'
        },
    
        xAxis: {
            categories: [
                <?php
                foreach($rshost as $hr){
                    echo "'".$hr['hostname']."',";
                }//for
                ?>
            ]
        },
    
        yAxis: {
            categories: [
                <?php
                foreach($rsdate as $dr){
                    echo "'".$dr['MyDate']."',";
                }//for
                ?>
            ],
            title: null
        },
    
        colors: ['#f01520'],
        colorAxis: {
            min: 0,
            minColor: '#FFFFFF',
            maxColor: '#f01520'
        },
    
        legend: {
            align: 'right',
            layout: 'vertical',
            margin: 0,
            verticalAlign: 'top',
            y: 25,
            symbolHeight: 280
        },
    
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.xAxis.categories[this.point.x] + '</b> <br>Inactive-Active <b>' +
                    this.point.value + '</b> on <br><b>' + this.series.yAxis.categories[this.point.y] + '</b>';
            }
        },
    
        series: [{
            name: 'Tunnel Inactive-Active',
            borderWidth: 1,
            data: [<?php echo $dataheatmap ?>
                   ],
            dataLabels: {
                enabled: true,
                color: '#000000'
            }
        }]
    
    });

    
</script>