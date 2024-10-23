<?php
if(isset($_GET['srid']))
    $srid=$_GET['srid'];
    include("../connection.php");
    $service=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM service WHERE srid='$srid'"));
    $srcr=$service['srcr'];
    $cid=$service['cid'];
    $brid=$service['brid'];
    $srcreator=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$srcr'"));
    $branch=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brid'"));
    $customer=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customer WHERE cid='$cid'"));
?>
<html>
<head>
<!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/file-uploaders/dropzone.min.css">
    <link rel="stylesheet" type="text/css" href="../../'app-assets/vendors/css/tables/datatable/extensions/dataTables.checkboxes.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/toastr.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/app-user.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/file-uploaders/dropzone.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/data-list-view.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/extensions/toastr.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    
    <!-- END: Custom CSS-->
</head>
<body>
<section>
    <div class="row">
        <div class="col-6">        
            <div class="col-12">
                <h4 class="light">Customer Details</h4>
                <hr>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless">
                            <tbody>                            
                                <tr>
                                    <th>NAME</th>
                                    <td><?php echo $customer['cname'];?></td>
                                </tr>
                                <tr>
                                    <th>ADDRESS</th>
                                    <td><?php echo $customer['cads'];?></td>
                                </tr>
                                <tr>
                                    <th>EMAIL</th>
                                    <td><?php echo $customer['cemail'];?></td>
                                </tr>
                                <tr>
                                    <th>PHONE</th>
                                    <td><?php echo $customer['cphone'];?></td>
                                </tr>
                            </tbody>
                </table>
            </div>
            <div class="col-12">
                <h4 class="light">Branch Details</h4>
                <hr>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless">
                            <tbody>                            
                                <tr>
                                    <th>BRANCH NAME</th>
                                    <td><?php echo $branch['fname'] ." " .$branch['lname'];?></td>
                                </tr>
                                <tr>
                                    <th>ASSIST</th>
                                    <td><?php echo $srcreator['fname'] ." " .$srcreator['lname'];?></td>
                                </tr>                                
                            </tbody>
                </table>
            </div>
        </div>
        <div class="col-6">
        <section>
    <div class="col-12">
        <h4 class="light">History</h4>
        <hr>
    </div>
    <table id="selectTable" class="table table-striped dataex-html5-selectors">							
        <thead>
            <tr>
            <th>Sl. No.</th>
            <th>SERVICER</th>
            <th>DETAILS</th>
            <th>INCENTIVE</th>
            <th>COST</th>
            <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
                        <?php
                        $servicedts=mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                        if(mysqli_num_rows($servicedts)>0){
                            foreach($servicedts as $servicedt){
                                $servcierid=$servicedt['svrid'];
                                $servcier=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$servcierid'"));
                                ?>
                                <tr>
                                <td><?php echo $servicedt['srno'];?></td> 
                                <td><?php echo $servcier['fname'] ." " .$servcier['lname'];?></td>
                                <td><?php echo $servicedt['srdetails'];?></td>                                
                                <td><?php echo $servicedt['incentive'];?></td>                                
                                <td><?php echo $servicedt['cost'];?></td>
                                <td>
                                <?php
                                if($servicedt['stat']==0){
                                    ?>
                                    <div class="chip chip-info">
                                        <div class="chip-body">
                                            <div class="chip-text">Servicing</div>
                                        </div>
                                    </div>
                                    <?php
                                }else if($servicedt['stat']==1){
                                    ?>
                                    <div class="chip chip-success">
                                        <div class="chip-body">
                                            <div class="chip-text">Completed</div>
                                        </div>
                                    </div>
                                    <?php
                                }else if($servicedt['stat']==2){
                                    ?>
                                    <div class="chip chip-warning">
                                        <div class="chip-body">
                                            <div class="chip-text">Tranfered</div>
                                        </div>
                                    </div>
                                    <?php
                                }else if($servicedt['stat']==3){
                                    ?>
                                    <div class="chip chip-warning">
                                        <div class="chip-body">
                                            <div class="chip-text">Requested</div>
                                        </div>
                                    </div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="chip chip-danger">
                                        <div class="chip-body">
                                            <div class="chip-text">Denied</div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                </td>
                                </tr>                                
                                <?php
                            }
                        }
                        ?>
        </tbody>
    </table>
</section>
        </div>
    </div>
</section>

    <!-- BEGIN: Page datatable JS-->
    <script src="../../app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/dataTables.select.min.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="../../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <!-- END: Page datatable JS-->
    <script src="../../js/todoback.js"></script>
</body>
</html>