<?php
$aid=$_POST['userKey'];
include("../connection.php");
$flag=0;
if(isset($_POST['existingvendor'])){
    $vid=$_POST['vid'];
    $aidds=$_POST['brid'];
    $dates=$_POST['dates'];
    $cashmethod=$_POST['cashmethod'];
    $total=$_POST['total'];
    if(isset($_POST['remarks'])){
        $remarks=$_POST['remarks'];
    }else{
        $remarks="";
    }    
    if(mysqli_query($con, "INSERT INTO purchase (dates, vid, aid, remarks, total) VALUES ('$dates', '$vid', '$aidds', '$remarks', '$total')")){
        $ttg=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM purchase WHERE dates='$dates' AND vid='$vid' AND aid='$aidds' AND remarks='$remarks' AND total='$total' "));
        $pcid=$ttg['pcid'];
        $product = $_POST['product'];
        $pdtsn = $_POST['pdtsn'];
        $dp = $_POST['dp'];
        $mrp = $_POST['mrp'];
        $cost = $_POST['cost'];
        $i=1;
        while($i<16){
            if($pdtsn[$i]!=""){
                $pdtid=$product[$i];
                $ptsn=$pdtsn[$i];
                $dpa=$dp[$i];
                $mrpa=$mrp[$i];
                $costa=$cost[$i];
                if(mysqli_query($con, "INSERT INTO productdts (pdtid, ptsn, dp, mrp, cost, discount, refp, stat) VALUES ('$pdtid', '$ptsn', '$dpa', '$mrpa', '$costa', '0', '$pcid', '0')")){
                    $flag=1;
                }
            }                
            $i+=1;
        }
        if($flag==1){
            if($cashmethod == "credit"){
                if(mysqli_query($con, "INSERT INTO vendorcredit (vid, dates, credit, debit, ref) VALUES ('$vid', '$dates', '$total', '0', '$pcid')")){
                    $flag=3;
                }
            }
        }        
    }
    if($flag == 0)
        $flag=2;
}
if(isset($_POST['newvendor'])){
    $dates=$_POST['dates'];
    $cashmethod=$_POST['cashmethod'];
    $total=$_POST['total'];
    if(isset($_POST['remarks'])){
        $remarks=$_POST['remarks'];
    }else{
        $remarks="";
    }
    $vname=$_POST['vname'];
    $aidds=$_POST['brid'];
    $vads=$_POST['vads'];
    $vemail=$_POST['vemail'];
    $vphone=$_POST['vphone'];
    if(mysqli_query($con, "INSERT INTO vendor (vname, vads, vemail, vphone) VALUES ('$vname', '$vads', '$vemail', '$vphone')")){
        $yyt=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM vendor WHERE vname='$vname' AND vads='$vads' AND vemail='$vemail' AND vphone='$vphone'"));
        $vid=$yyt['vid'];
        if(mysqli_query($con, "INSERT INTO purchase (dates, vid, aid, remarks, total) VALUES ('$dates', '$vid', '$aidds', '$remarks', '$total')")){
            $ttg=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM purchase WHERE dates='$dates' AND vid='$vid' AND aid='$aidds' AND remarks='$remarks' AND total='$total' "));
            $pcid=$ttg['pcid'];
            $product = $_POST['product'];
            $pdtsn = $_POST['pdtsn'];
            $dp = $_POST['dp'];
            $mrp = $_POST['mrp'];
            $cost = $_POST['cost'];
            $i=1;
            while($i<16){
                if($pdtsn[$i]!=""){
                    $pdtid=$product[$i];
                    $ptsn=$pdtsn[$i];
                    $dpa=$dp[$i];
                    $mrpa=$mrp[$i];
                    $costa=$cost[$i];
                    if(mysqli_query($con, "INSERT INTO productdts (pdtid, ptsn, dp, mrp, cost, discount, refp, stat) VALUES ('$pdtid', '$ptsn', '$dpa', '$mrpa', '$costa', '0', '$pcid', '0')")){
                        $flag=1;
                    }
                }                
                $i+=1;
            }
            if($flag==1){
                if($cashmethod == "credit"){
                    if(mysqli_query($con, "INSERT INTO vendorcredit (vid, dates, credit, debit, ref) VALUES ('$vid', '$dates', '$total', '0', '$pcid')")){
                        $flag=3;
                    }
                }
            }       
        }        
    }
    if($flag == 0)
        $flag=2;
}
$query = "select * from actdetails where aid = '$aid'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
$que = "select email from account where aid = '$aid'";
$ret = mysqli_query($con, $que);
$eml = mysqli_fetch_array($ret);
$qry="select nid from notificat where aid = '$aid' AND nstatus = '0'";
$res = mysqli_query($con, $qry);
if(mysqli_num_rows($res)>0)
    $count=mysqli_num_rows($res);
else
    $count=0;

?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>Automated BPM</title>
    <link rel="icon" href="../../img/logo.png">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" crossorigin="anonymous"></script>

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
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/invoice.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <!-- END: Custom CSS-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" onload="avatarLoad()">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="javascript:lightForm()"><i class="ficon feather icon-moon"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon feather icon-maximize"></i></a></li>
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="javascript:notiFy()" ><i class="ficon feather icon-bell"></i><span class="badge badge-pill badge-primary badge-up" ><?php echo $count;?></span></a>
                        <form style="display: none;" id="notiFy" action="notification.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        </form>
                        <form style="display: none;" id="lightForm" action="../dark/purchase_add.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        </form>
                        <script type="text/javascript">
                            function notiFy(){
                            document.getElementById("notiFy").submit();
                            }
                            function lightForm(){
                            document.getElementById("lightForm").submit();
                            }
                            function avatarLoad(){
                                var today = new Date();
                                var dd = String(today.getDate()).padStart(2, '0');                                
                                var mm = String(today.getMonth() + 1).padStart(2, '0');
                                var yyyy = today.getFullYear();
                                today = yyyy + '-' + mm + '-' + dd;
                                dates.value = today;
                                var x='<?php echo $flag; ?>';
                                if(x==1){
                                    Swal.fire({
                                        icon: 'success',        
                                        text: "Purchase added successfully.",                                      
                                        showCancelButton: false        
                                    }).then((result) => {
                                        document.getElementById("purchase").submit();
                                    });
                                }else if(x==2){
                                    Swal.fire({
                                        icon: 'error',        
                                        text: "Something went wrong.",                                      
                                        showCancelButton: false        
                                    }).then((result) => {
                                        document.getElementById("purchase").submit();
                                    });
                                }else if(x==3){
                                    Swal.fire({
                                        icon: 'success',        
                                        text: "Purchase added successfully and credit is marked.",                                      
                                        showCancelButton: false        
                                    }).then((result) => {
                                        document.getElementById("purchase").submit();
                                    });
                                }
                                var y='<?php echo $row['gender']; ?>';
                                if(y=="Female"){
                                    document.getElementById("avatarImg").src="../../img/female_avatar.png";
                                }
                                else if(y=="Male"){
                                    document.getElementById("avatarImg").src="../../img/male_avatar.png";
                                }
                                else{
                                    document.getElementById("avatarImg").src="../../img/male_avatar.png";
                                }
                                navBar();
                            }
                            function navBar() {
                                if('<?php echo $row['utype']; ?>'=="Management"){
                                    document.getElementById("aCs").style.display = "none";
                                    document.getElementById("editBar").style.display = "block";
                                    document.getElementById("taskBar").style.display = "block";
                                    document.getElementById("daS").style.display = "block";
                                    document.getElementById("sHp").style.display = "block";
                                    document.getElementById("sDt").style.display = "block";
                                    document.getElementById("pDt").style.display = "block";
                                    document.getElementById("eDt").style.display = "block";
                                    document.getElementById("fDt").style.display = "block";
                                    document.getElementById("geN").style.display = "block";
                                    document.getElementById("sAl").style.display = "block";
                                    document.getElementById("pCe").style.display = "block";
                                    document.getElementById("tDo").style.display = "block";
                                    document.getElementById("stkN").style.display = "block";
                                    document.getElementById("stkH").style.display = "block";
                                    document.getElementById("stkR").style.display = "block";
                                    document.getElementById("eMp").style.display = "block";
                                    document.getElementById("eMpD").style.display = "block";
                                    document.getElementById("eMpF").style.display = "block";
                                    document.getElementById("eMpW").style.display = "block";
                                    document.getElementById("eMpA").style.display = "block";
                                    document.getElementById("eMpL").style.display = "block";
                                }else if('<?php echo $row['utype']; ?>'=="Branch Manager"){
                                    document.getElementById("aCs").style.display = "none";
                                    document.getElementById("taskBar").style.display = "block";
                                    document.getElementById("daS").style.display = "block";
                                    document.getElementById("sHp").style.display = "block";
                                    document.getElementById("sDt").style.display = "block";
                                    document.getElementById("pDt").style.display = "block";
                                    document.getElementById("eDt").style.display = "block";
                                    document.getElementById("geN").style.display = "block";
                                    document.getElementById("sAl").style.display = "block";
                                    document.getElementById("pCe").style.display = "block";
                                    document.getElementById("tDo").style.display = "block";
                                    document.getElementById("stkN").style.display = "block";
                                    document.getElementById("stkH").style.display = "block";
                                    document.getElementById("stkR").style.display = "block";
                                }else if('<?php echo $row['utype']; ?>'=="Employee"){
                                    document.getElementById("aCs").style.display = "none";
                                    document.getElementById("editBar").style.display = "block";
                                    document.getElementById("taskBar").style.display = "block";
                                    document.getElementById("daS").style.display = "block";
                                    document.getElementById("eMp").style.display = "block";
                                    document.getElementById("eMpD").style.display = "block";
                                    document.getElementById("eMpF").style.display = "block";
                                    document.getElementById("eMpW").style.display = "block";
                                    document.getElementById("eMpA").style.display = "block";
                                    document.getElementById("eMpL").style.display = "block";
                                    document.getElementById("geN").style.display = "block";
                                    document.getElementById("sAl").style.display = "block";
                                    document.getElementById("tDo").style.display = "block";
                                }else if('<?php echo $row['utype']; ?>'=="Not authorized"){
                                    window.location.href="../../html/page-not-authorized.html", '_blank';
                                }
                            }
                        </script>
                        </li>
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600"><?php echo $row['fname']; ?> <?php echo $row['lname']; ?> </span><span class="user-status"><?php echo $row['dest']; ?></span></div><span><img class="round" src="" id="avatarImg" alt="avatar" height="40" width="40"></span></a>
                            <div class="dropdown-menu dropdown-menu-right"><a style="display: none" id="editBar" class="dropdown-item" href="javascript:usrEdit()"><i class="feather icon-user"></i> Edit Profile</a><a style="display: none" id="taskBar" class="dropdown-item" href="javascript:toDo()"><i class="feather icon-check-square"></i> Task</a><a class="dropdown-item" href="javascript:changePass()"><i class="fa fa-key"></i> Change Password</a>
                            <div class="dropdown-divider"></div><a class="dropdown-item" href="../../index.html"><i class="feather icon-power"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="javascript:homeBtn()">
                    <img src="../../img/favicon.ico">
                    <h2 class="brand-text mb-0">Home</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i></a></li>
            </ul>
        </div>
        <form style="display: none;" id="home" action="home.php" method="POST">
           <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
        </form>
        <script type="text/javascript">
            function homeBtn(){
              document.getElementById("home").submit();
            }
        </script>
        <form style="display: none;" id="usrEdit" action="user_edit.php" method="POST">
            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
        </form>
        <form style="display: none;" id="changePass" action="../otp.php" method="POST">
            <input value='<?php echo $eml['email']; ?>' name="email" style="display: none;">
            <input value='<?php echo $row['fname']; ?>' name="name" style="display: none;">
            <input name="id" style="display: none;" value="2">
        </form>
        <script type="text/javascript">
            function usrEdit(){
                document.getElementById("usrEdit").submit();
            }
            function changePass() {
                document.getElementById("changePass").submit();
            }
        </script>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" nav-item"><a href="javascript:accessCont()" style="display: block;" id="aCs"><i class="feather icon-home"></i><span class="menu-title" data-i18n="Dashboard">Access control</span></a>
                <form style="display: none;" id="accessCont" action="access_control.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function accessCont(){
                       document.getElementById("accessCont").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:dashBtn()" style="display: none;" id="daS"><i class="feather icon-home"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span></a>
                <form style="display: none;" id="dashBtn" action="dashboard.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function dashBtn(){
                       document.getElementById("dashBtn").submit();
                   }
                </script>
                </li>
                <li class=" navigation-header" style="display: none;" id="sHp"><span>SHOP</span>
                </li>
                <li class=" nav-item"><a href="javascript:shopdt()" style="display: none;" id="sDt"><i class="feather icon-briefcase"></i><span class="menu-title" >Shop Details</span></a>
                <form style="display: none;" id="shopdt" action="shop_details.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function shopdt(){
                       document.getElementById("shopdt").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:Proddt()" style="display: none;" id="pDt"><i class="feather icon-shopping-bag"></i><span class="menu-title">Product Details</span></a>
                <form style="display: none;" id="Proddt" action="product_details.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function Proddt(){
                       document.getElementById("Proddt").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:Empsdt()" style="display: none;" id="eDt"><i class="feather icon-users"></i><span class="menu-title">Employee Details</span></a>
                <form style="display: none;" id="Empsdt" action="employees_details.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function Empsdt(){
                       document.getElementById("Empsdt").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:Findt()" style="display: none;" id="fDt"><i class="feather icon-inbox"></i><span class="menu-title">Financial Details</span></a>
                <form style="display: none;" id="Findt" action="financial_details.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function Findt(){
                       document.getElementById("Findt").submit();
                   }
                </script>
                </li>
                <li class=" navigation-header" style="display: none;" id="geN"><span>GENERAL</span>
                </li>
                <li class=" nav-item"><a href="javascript:sale()" style="display: none;" id="sAl"><i class="fa fa-cart-plus"></i><span class="menu-title">Sales</span></a>
                <form style="display: none;" id="sale" action="sales.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function sale(){
                       document.getElementById("sale").submit();
                   }
                </script>
                </li>
                <li class="active nav-item"><a href="javascript:purchase()" style="display: none;" id="pCe"><i class="fa fa-cart-arrow-down"></i><span class="menu-title">Purchase</span></a>
                <form style="display: none;" id="purchase" action="purchase.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function purchase(){
                       document.getElementById("purchase").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:toDo()" style="display: none;" id="tDo"><i class="feather icon-check-square"></i><span class="menu-title">To Do</span></a>
                <form style="display: none;" id="toDo" action="to_do.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function toDo(){
                       document.getElementById("toDo").submit();
                   }
                </script>
                </li>
                <li class=" navigation-header" style="display: none;" id="stkN"><span>STOCK</span>
                </li>
                <li class=" nav-item"><a href="javascript:stockHand()" style="display: none;" id="stkH"><i class="fa fa-shopping-bag"></i><span class="menu-title">Stock on Hand</span></a>
                <form style="display: none;" id="stockHand" action="stock_on_hand.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function stockHand(){
                       document.getElementById("stockHand").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:stockReq()" style="display: none;" id="stkR"><i class="fa fa-shopping-basket"></i><span class="menu-title">Stock Request</span></a>
                <form style="display: none;" id="stockReq" action="stock_request.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function stockReq(){
                       document.getElementById("stockReq").submit();
                   }
                </script>
                </li>
                <li class=" navigation-header" style="display: none;" id="eMp"><span>USER</span>
                </li>
                <li class=" nav-item"><a href="javascript:empDt()" style="display: none;" id="eMpD"><i class="feather icon-user"></i><span class="menu-title">User Details</span></a>
                <form style="display: none;" id="empDt" action="user_details.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function empDt(){
                       document.getElementById("empDt").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:finEdt()"  style="display: none;" id="eMpF"><i class="feather icon-inbox"></i><span class="menu-title">Financial Details</span></a>
                <form style="display: none;" id="finEdt" action="employee_financial_details.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function finEdt(){
                       document.getElementById("finEdt").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:workHis()"  style="display: none;" id="eMpW"><i class="fa fa-history"></i><span class="menu-title">Work History</span></a>
                <form style="display: none;" id="workHis" action="work_history.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function workHis(){
                       document.getElementById("workHis").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:attDt()" style="display: none;" id="eMpA"><i class="feather icon-calendar"></i><span class="menu-title">Attendance Details</span></a>
                <form style="display: none;" id="attDt" action="attendance_details.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function attDt(){
                       document.getElementById("attDt").submit();
                   }
                </script>
                </li>
                <li class=" nav-item"><a href="javascript:leaRe()" style="display: none;" id="eMpL"><i class="fas fa-sign-out-alt"></i><span class="menu-title">Leave Request</span></a>
                <form style="display: none;" id="leaRe" action="leave_request.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function leaRe(){
                       document.getElementById("leaRe").submit();
                   }
                </script>
                </li>
                <li class=" navigation-header"><span>SUPPORT</span>
                </li>
                <li class=" nav-item"><a href="javascript:About()"><i class="feather icon-info"></i><span class="menu-title">About</span></a>
                <form style="display: none;" id="About" action="about.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function About(){
                       document.getElementById("About").submit();
                   }
                </script>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Purchase</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:purchase()">Purchase</a></li>
                                <li class="breadcrumb-item active">Add Purchase</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body"> 
            <section>
                <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="float-right">
                        <button type="sumbit" onclick="javascript:Proddt()"  class="btn btn-outline-warning round">Manage Products</button>
                    </div>
                </div>
            </section>           
            <form action="#" method="POST" id="createFrom">
            <section>
                    <div class="my-2"></div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <h4 class="light">Vendor Details</h4>
                                <hr>
                            </div>
                            </div>
                            <div class="row pl-2">
                                <div class="col-12">
                                <div class="my-2"></div>
                                <div class="vs-radio-con">
                                <input type="radio" name="myRadios"  id="myRadios" onclick="radChange1()"  value="1"/>
                                <span class="vs-radio">
                                <span class="vs-radio--border"></span>
                                <span class="vs-radio--circle"></span>
                                </span>
                                    Existing Vendor
                                </div>                                
                            <div class="form-group pl-2" id="extvedorForm" style="display: none">
                                <div class="my-2"></div>
                                <select class="form-control col-9" name="vid" required data-validation-required-message="This field is required">                                
                                    <option selected>Select</option>
                                    <?php
                                    $lfe=mysqli_query($con, "SELECT * FROM vendor");
                                    if(mysqli_num_rows($lfe)){
                                        foreach($lfe as $lfd){?>
                                            <option value="<?php echo $lfd['vid'];?>"><?php echo $lfd['vname'];?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="my-2"></div>
                                <div class="vs-radio-con">
                                <input type="radio" name="myRadios"  id="myRadios"  onclick="radChange2()" value="2" />
                                <span class="vs-radio">
                                <span class="vs-radio--border"></span>
                                <span class="vs-radio--circle"></span>
                                </span>
                                    New Vendor
                                </div>
                                <div class="form-group pl-2" id="newvedorForm" style="display: none">
                                    <div class="my-2"></div>
                                    <div class="controls">
                                        <label>Name</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control col-9"  placeholder="Name" name="vname" required data-validation-required-message="This name field is required">
                                        </div>
                                    </div>
                                    <div class="controls">
                                        <label>Address</label>
                                        <div class="form-group">
                                            <fieldset class="form-group">
                                                <textarea class="form-control col-9" name="vads" rows="3" placeholder="Address..." required data-validation-required-message="This address field is required"></textarea>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="controls">
                                        <label>Email</label>
                                        <div class="form-group">
                                            <input type="email" class="form-control col-9"  placeholder="Email" name="vemail" required data-validation-required-message="This email field is required">
                                        </div>
                                    </div>
                                    <div class="controls">
                                        <label>Phone Number</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control col-9"  placeholder="Phone Number" name="vphone" required data-validation-required-message="This phone number field is required">
                                        </div>
                                    </div>
                                </div>
                                <div class="my-2"></div>
                            </div>                            
                            <input type="text" name="name" id="formselector" style="display: none;">
                        </div>                                                
                    </div>
                </div>
                </section>
                <section>
                    <div class="my-2"></div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="col-12">
                                        <h4 class="light">Branch Details</h4>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row pl-2">
                                <div class="col-12">
                                    <div class="my-2"></div>                                                                    
                                    <div class="form-group pl-2">
                                        <div class="my-2"></div>
                                        <select class="form-control col-9" name="brid" required data-validation-required-message="This field is required">                                
                                            <option selected>Select</option>
                                            <?php                                            
                                            if($row['utype']=="Management"){
                                                $dgt=mysqli_query($con, "SELECT * FROM actdetails WHERE utype='Branch Manager'");
                                                if(mysqli_num_rows($dgt)){
                                                    foreach($dgt as $tgd){
                                                        ?><option value="<?php echo $tgd['aid'];?>"><?php echo $tgd['fname'] ." " .$tgd['lname'];?></option><?php
                                                    }
                                                }
                                            }else if($row['utype']=="Branch Manager"){
                                                ?><option value="<?php echo $row['aid'];?>"><?php echo $row['fname'] ." " .$row['lname'];?></option><?php
                                            }else{
                                                $mtd=mysqli_query($con, "SELECT * FROM shop WHERE eid='$aid'");
                                                if(mysqli_num_rows($mtd)>0){
                                                    $dtm=mysqli_fetch_array($mtd);
                                                    $aidds=$dtm['aid'];
                                                    $dgt=mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$aidds'");
                                                    if(mysqli_num_rows($dgt)){
                                                    foreach($dgt as $tgd){
                                                        ?><option value="<?php echo $tgd['aid'];?>"><?php echo $tgd['fname'] ." " .$tgd['lname'];?></option><?php
                                                    }
                                                }
                                                }
                                            }                                            
                                            ?>
                                        </select>
                                    </div>
                                </div>                           
                            </div>                                                
                        </div>
                    </div>
                </section>
                <section>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                            <div class="col-12">
                                <h4 class="light">Products List</h4>
                                <hr>
                            </div>
                            </div>
                            <div class="card-body">
                            <div class="col-12">
                            <table id="vendorTable" class="table table-striped dataex-html5-selectors">
                                <thead>
                                    <tr>
                                        <th class="text-center">PRODUCT</th>
                                        <th class="text-center">PRODUCT SERIAL NUMBER</th>
                                        <th class="text-center">DP</th>
                                        <th class="text-center">MRP</th>
                                        <th class="text-center">COST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter1" class="form-control round" name="product[1]" required data-validation-required-message="This field is required">                                
                                        <option >Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[1]" id="pdtsn1" value="">
                                        <a onclick="javascript:generateSerial('1')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp1" name="dp[1]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[1]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[1]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter2" class="form-control round" name="product[2]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[2]" id="pdtsn2" value="">
                                        <a onclick="javascript:generateSerial('2')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp2" name="dp[2]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[2]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[2]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter3" class="form-control round" name="product[3]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[3]" id="pdtsn3" value="">
                                        <a onclick="javascript:generateSerial('3')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp3" name="dp[3]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[3]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[3]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter4" class="form-control round" name="product[4]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[4]" id="pdtsn4" value="">
                                        <a onclick="javascript:generateSerial('4')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp4" name="dp[4]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[4]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[4]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter5" class="form-control round" name="product[5]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[5]" id="pdtsn5" value="">
                                        <a onclick="javascript:generateSerial('5')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp5" name="dp[5]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[5]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[5]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter6" class="form-control round" name="product[6]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[6]" id="pdtsn6" value="">
                                        <a onclick="javascript:generateSerial('6')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp6" name="dp[6]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[6]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[6]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter7" class="form-control round" name="product[7]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[7]" id="pdtsn7" value="">
                                        <a onclick="javascript:generateSerial('7')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp7" name="dp[7]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[7]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[7]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter8" class="form-control round" name="product[8]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[8]" id="pdtsn8" value="">
                                        <a onclick="javascript:generateSerial('8')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp8" name="dp[8]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[8]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[8]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter9" class="form-control round" name="product[9]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[9]" id="pdtsn9" value="">
                                        <a onclick="javascript:generateSerial('9')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp9" name="dp[9]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[9]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[9]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter10" class="form-control round" name="product[10]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[10]" id="pdtsn10" value="">
                                        <a onclick="javascript:generateSerial('10')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp10" name="dp[10]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[10]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[10]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter11" class="form-control round" name="product[11]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[11]" id="pdtsn11" value="">
                                        <a onclick="javascript:generateSerial('11')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp11" name="dp[11]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[11]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[11]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter12" class="form-control round" name="product[12]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[12]" id="pdtsn12" value="">
                                        <a onclick="javascript:generateSerial('12')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp12" name="dp[12]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[12]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[12]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter13" class="form-control round" name="product[13]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[13]" id="pdtsn13" value="">
                                        <a onclick="javascript:generateSerial('13')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp13" name="dp[13]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[13]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[13]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter14" class="form-control round" name="product[14]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[14]" id="pdtsn14" value="">
                                        <a onclick="javascript:generateSerial('14')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp14" name="dp[14]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[14]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[14]"  value="0"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                        <select id="selecter15" class="form-control round" name="product[15]" required data-validation-required-message="This field is required">                                
                                        <option selected>Select</option>
                                        <?php
                                        $kdk=mysqli_query($con, "SELECT * FROM product");
                                        if(mysqli_num_rows($kdk)){
                                            foreach($kdk as $dkd){?>
                                                <option value="<?php echo $dkd['pdtid'];?>"><?php echo $dkd['pdtname'];?> | <?php echo $dkd['pdtdetails'];?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        </td>
                                        <td><input class="form-control round" name="pdtsn[15]" id="pdtsn15" value="">
                                        <a onclick="javascript:generateSerial('15')"><p class="font-small-1 float-right" >auto generate</p></a>
                                        </td>
                                        <td style="vertical-align: top;"><input class="form-control round" onchange="totalCal()" id="dp15" name="dp[15]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="mrp[15]"  value="0"></td>
                                        <td style="vertical-align: top;"><input class="form-control round" name="cost[15]"  value="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-5">
                                    <div class="my-2"></div>
                                <fieldset class="form-group pl-1">
                                    <textarea class="form-control" name="remarks" rows="6" placeholder="Remarks" required></textarea>
                                </fieldset>
                                </div>
                                <div class="col-12 col-sm-7">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th>DATE</th>
                                                    <td><input type="date" id="dates" name="dates" class="form-control round"></td>
                                                </tr>
                                                <tr>
                                                    <th>CASH METHOD</th>
                                                    <td>
                                                        <select name="cashmethod" class="form-control round">
                                                        <option value="cash" selected>BY CASH</option>
                                                        <option value="credit">CREDIT</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>GRAND TOTAL</th>
                                                    <td><input type="text" disabled  id="total" value="0" class="form-control round">
                                                    <input type="text"  name="total" id="totaler" style="display: none;" value="0" class="form-control round"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                <button type="button" id="cancelBtn" class="btn btn-outline-danger">Cancel</button>
                                <button type="submit" id="submitBtn" name="submitBtn" class="btn btn-outline-success">Submit</button>
                                </div>
                            </div>
                        </div>                    
                    </div>
                </div>
            </section>
            </form>
            <script type="text/javascript">
            function totalCal(){
                document.getElementById("total").value = parseInt(document.getElementById("dp1").value) + parseInt(document.getElementById("dp2").value) + parseInt(document.getElementById("dp3").value) + parseInt(document.getElementById("dp4").value) + parseInt(document.getElementById("dp5").value) + parseInt(document.getElementById("dp6").value) + parseInt(document.getElementById("dp7").value) + parseInt(document.getElementById("dp8").value) + parseInt(document.getElementById("dp9").value) + parseInt(document.getElementById("dp10").value) + parseInt(document.getElementById("dp11").value) + parseInt(document.getElementById("dp12").value) + parseInt(document.getElementById("dp13").value) + parseInt(document.getElementById("dp14").value) + parseInt(document.getElementById("dp15").value);
                document.getElementById("totaler").value = parseInt(document.getElementById("dp1").value) + parseInt(document.getElementById("dp2").value) + parseInt(document.getElementById("dp3").value) + parseInt(document.getElementById("dp4").value) + parseInt(document.getElementById("dp5").value) + parseInt(document.getElementById("dp6").value) + parseInt(document.getElementById("dp7").value) + parseInt(document.getElementById("dp8").value) + parseInt(document.getElementById("dp9").value) + parseInt(document.getElementById("dp10").value) + parseInt(document.getElementById("dp11").value) + parseInt(document.getElementById("dp12").value) + parseInt(document.getElementById("dp13").value) + parseInt(document.getElementById("dp14").value) + parseInt(document.getElementById("dp15").value);
            }
            function radChange1(){
                document.getElementById("formselector").name = "existingvendor";
                document.getElementById("extvedorForm").style.display = "block";
                document.getElementById("newvedorForm").style.display = "none";                             
            }
            function radChange2(){                               
                document.getElementById("formselector").name = "newvendor";
                document.getElementById("extvedorForm").style.display = "none";
                document.getElementById("newvedorForm").style.display = "block";                                
            }
            document.getElementById("submitBtn").onclick = function() {
                document.getElementById("createFrom").submit();
            };
            document.getElementById("cancelBtn").onclick = function() {
                document.getElementById("purchase").submit();
            };
            function generateSerial(sub) {
                'use strict';                              
                var chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',                    
                    serialLength = 10,                    
                    randomSerial = "",                    
                    i,                    
                    randomNumber;                
                for (i = 0; i < serialLength; i = i + 1) {                    
                    randomNumber = Math.floor(Math.random() * chars.length);                    
                    randomSerial += chars.substring(randomNumber, randomNumber + 1);                    
                }                
                document.getElementById("pdtsn"+sub).value = randomSerial;               
            }
            </script>
           </div>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#vendorTable').DataTable( {       
            "searching": false,
        "bPaginate": false,
        "lengthChange": false,
        "bInfo": false,
        "bAutoWidth": false,
        "columnDefs": [
            { "width": "25%", "targets": 0 },
            { "width": "25%", "targets": 1 },
            { "width": "15%", "targets": 2 },
            { "width": "15%", "targets": 3 },
            { "width": "15%", "targets": 4 }
          ]
        });
        $('#selecter1').select2();
        $('#selecter2').select2();
        $('#selecter3').select2();
        $('#selecter4').select2();
        $('#selecter5').select2();
        $('#selecter6').select2();
        $('#selecter7').select2();
        $('#selecter8').select2();
        $('#selecter9').select2();
        $('#selecter10').select2();
        $('#selecter11').select2();
        $('#selecter12').select2();
        $('#selecter13').select2();
        $('#selecter14').select2();
        $('#selecter15').select2();
    });
    </script>

    <!-- END: Content-->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2021<a class="text-bold-800 grey darken-2" href="https://automatedbusiness.000webhostapp.com/" target="_blank">Automated BPM,</a>All rights Reserved</span><span class="float-md-right d-none d-md-block">Final Year Project, BTech, CSE.</span></p>
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25"><em>Developed By Prince Jacob, Greshma R, Amrutha Soman.</em></span><span class="float-md-right d-none d-md-block"><a class="text-bold-800 grey darken-2" href="http://jaibharathengg.com/" target="_blank">Jai Bharath College Of Management And Engineering Technology</a></span></p>
    </footer>
    <!-- END: Footer-->
    <!-- BEGIN: Vendor JS-->
    <script src="../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

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

    <!-- BEGIN: Page extensions JS-->
    <script src="../../app-assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="../../app-assets/vendors/js/extensions/dropzone.min.js"></script>
    <!-- END: Page extensions JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <script src="../../app-assets/js/scripts/components.js"></script>
    <script src="../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="../../app-assets/js/scripts/pages/app-user.js"></script>    
    <script src="../../app-assets/js/scripts/navs/navs.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../app-assets/js/scripts/forms/select/form-select2.js"></script>
    <!-- END: Page JS-->
</body>
<!-- END: Body-->

</html>