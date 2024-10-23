<?php
if(isset($_GET['cid']))
    $cid=$_GET['cid'];
    $sd=$_GET['startdate'];
    $ed=$_GET['enddate'];
    include("../connection.php");
    $cname=mysqli_fetch_array(mysqli_query($con, "SELECT cname FROM customer WHERE cid='$cid'"));
    $htk=mysqli_query($con, "SELECT * FROM custcredit WHERE cid='$cid'");
    $credit=0;
    $debit=0;
    if(mysqli_num_rows($htk)>0){
        foreach($htk as $kth){
            $credit=$credit + intval($kth['credit']);
            $debit=$debit + intval($kth['debit']);
        }
    }
    $total=$credit-$debit;
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../'app-assets/vendors/css/tables/datatable/extensions/dataTables.checkboxes.css">
<link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/tables/datatable/datatables.min.css">
</head>
<body>
<table id="selectTable" class="table table-striped dataex-html5-selectors">							
    <thead>
    <tr>
        <th><h1><?php echo $cname['cname']; ?><h1></th>
        <th><h5>Start date: </h5><?php echo $sd; ?></th>
        <th><h5>End date: </h5><?php echo $ed; ?></th>
    </tr>
    <tr>
        <th>DATE</th>
        <th>CREDIT</th>
        <th>DEBIT</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $htk=mysqli_query($con, "SELECT * FROM custcredit WHERE cid='$cid'");
    if(mysqli_num_rows($htk)>0){
        foreach($htk as $kth){
            if($kth['dates'] >= $sd && $kth['dates'] <= $ed){?>
            <tr>
                <td><?php echo $kth['dates']?></td>
                <td><?php echo $kth['credit']?></td>
                <td><?php echo $kth['debit']?></td>
            </tr>
            <?php
            }
        }
    }
    ?>
    <tr>
        <td>Total Credit: <?php echo $credit;?></td>
        <td>Total Debit: <?php echo $debit;?></td>
        <td>Closing Amount : <?php echo $total;?></td>
    </tr>
    </tbody>
    </table>
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
    <script src="../../js/purchaseback.js"></script>
</body>
</html>