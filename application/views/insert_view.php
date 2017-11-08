


    <!-- Page Content -->
    <div class="container">

      <h3 class="my-4">Insert Tunnel</h3>

      <div class="row">

        <div class="col-md-6">
        <div class="card " align="center" style="margin-bottom: 20px; padding: 10px;"> 
                
            <form action="<?php echo base_url(); ?>index.php/insert" method="post" name="upload_excel" enctype="multipart/form-data">
                
                <input type="file" name="file" id="file" >
                <button type="submit" name="btadd" class="btn btn-primary" style="padding: 5px;margin: 5px;" value="Add Tunnel"><i class="fa fa-plus-circle"></i> Add Tunnel</button>

 
                <?php if($this->session->flashdata('message')=='1'){?>
                        <div align="center" class="alert alert-success" style="padding: 5px; margin-bottom:0px;">      
                            <?php echo "Imported successfully..";?>
                        </div>
                <?php }else if($this->session->flashdata('message')=='0'){?>
                        <div align="center" class="alert alert-danger" style="padding: 5px; margin-bottom:0px;">      
                            <?php echo "Something went wrong..";?>
                        </div>
                <?php } ?>
                <a href="<?php echo base_url(); ?>asset/sample.csv"> Sample csv file </a>
            </form>
        </div>
        </div>



      <div class="col-md-12" style="margin-bottom: 20px;">
        

        <div class="table-responsive">
            <table id="tbTunnel" class="table table-hover" >
            <thead  style="background-color: #0057ae;color: #fff">
                <tr >
                    <th>No.</th>
                    <th>CID</th>
                    <th>Tunnel</th>
                    <th>Host</th>
                    <th>Host name</th>
                    <th>Match</th>
                    <th>command</th>
                </tr>
            </thead>

            <tfoot >
                <tr >
                    <th></th>
                    <th>CID</th>
                    <th>Tunnel</th>
                    <th>Host</th>
                    <th>Host name</th>
                    <th>Match</th>
                    <th></th>
                </tr>
            </tfoot>

            
           
          </table>

        </div>
     
      </div>
      </div><!-- /.row -->

    </div>
    <!-- /.container -->


<script type="text/javascript">

$(document).ready(function() {

    var table = $('#tbTunnel').DataTable( {
        "ajax": '<?=base_url("Ajaxdata")?>',
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 250, 500, -1], [10, 250, 500, "All"]],
        buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ],
        

            initComplete: function () {
                this.api().columns([1,2,3,4,5]).every( function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
    
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
    
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            }
    } );
 
    table.buttons().container()
        .appendTo( $('div.eight.column:eq(0)', table.table().container()) );
} );
</script>

   