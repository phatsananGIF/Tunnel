<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TUNNEL</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url()?>asset/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">    
    <!-- Custom fonts for this template-->
    <link href="<?=base_url()?>asset/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="<?=base_url()?>asset/css/sb-admin.css" rel="stylesheet">
    <link href="<?=base_url()?>asset/css/modern-business.css" rel="stylesheet">
    <!-- datatables -->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>asset/vendor/datatables/jquery.dataTables.css" >
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>asset/vendor/datatables/buttons.dataTables.css" >

  </head>

  <body>
      <!-- Bootstrap core JavaScript -->
      <script src="<?=base_url()?>asset/vendor/jquery/jquery.min.js"></script>
      <script src="<?=base_url()?>asset/vendor/popper/popper.min.js"></script>
      <script src="<?=base_url()?>asset/vendor/bootstrap/js/bootstrap.min.js"></script>

       <!-- datatables -->
     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/jquery.dataTables-1.10.16.min.js"></script>
     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/dataTables.buttons.min.js"></script>
     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/buttons.flash.min.js"></script>

     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/jszip.min.js"></script>
     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/pdfmake.min.js"></script>
     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/vfs_fonts.js"></script>
     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/buttons.html5.min.js"></script>
     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/buttons.print.min.js"></script>
     <script type="text/javascript" src="<?=base_url()?>asset/vendor/datatables/buttons.colVis.min.js"></script>




    <!-- Navigation -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="<?=base_url()?>home">TUNNEL</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?=base_url()?>home">Home</a>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBlog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Report
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">
                <a class="dropdown-item" href="<?=base_url()?>report">Report data</a>
                <a class="dropdown-item" href="<?=base_url()?>reportchart">Report chart</a>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="<?=base_url()?>insert">Insert</a>
            </li>

          </ul>
        </div>
      </div>
    </nav>