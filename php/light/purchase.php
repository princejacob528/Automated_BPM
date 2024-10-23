<?php
$aid=$_POST['userKey'];
include("../connection.php");
$flag=0;
if(isset($_POST['vendorSave'])){
    $vid=$_POST['vid'];
    $vname=$_POST['vname'];
    $vemail=$_POST['vemail'];
    $vphone=$_POST['vphone'];
    if(mysqli_query($con, "UPDATE vendor SET vname='$vname', vemail='$vemail', vphone='$vphone' WHERE vid='$vid'"))
        $flag=1;
    else
        $flag=2;
}
if(isset($_POST['vendorcreate'])){
    $vname=$_POST['vname'];
    $vads=$_POST['vads'];
    $vemail=$_POST['vemail'];
    $vphone=$_POST['vphone'];
    if(mysqli_query($con, "INSERT INTO vendor (vname, vads, vemail, vphone) VALUES ('$vname', '$vads', '$vemail', '$vphone')"))
        $flag=3;
    else
        $flag=4;
}
if(isset($_POST['debitcreate'])){
    $vid=$_POST['vid'];
    $debit=$_POST['debit'];
    $dates=date("Y-m-d");
    if(mysqli_query($con, "INSERT INTO vendorcredit (vid, dates, credit, debit, ref) VALUES ('$vid', '$dates', '0', '$debit', '0')"))
        $flag=5;
    else
        $flag=6;
}
if(isset($_POST['purchaseDelete'])){
    $pcid=$_POST['pcid'];
    if(mysqli_query($con, "DELETE FROM productdts WHERE refp='$pcid'")){
        if(mysqli_query($con, "DELETE FROM purchase WHERE pcid='$pcid'")){
            $flag=7;
        }
        if(mysqli_query($con, "DELETE FROM vendorcredit WHERE ref='$pcid'")){
            $flag=7;
        }
    }
    if($flag!=7)
        $flag=8;
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
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <!-- END: Custom CSS-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
                        <form style="display: none;" id="lightForm" action="../dark/purchase.php" method="POST">
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
                                var x='<?php echo $flag; ?>';
                                if(x==1){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Vendor updated successfully..", "Well Done !");
                                }
                                else if(x==2){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error('Vendor updated failed..', "Oops !");
                                }else if(x==3){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Vendor created successfully..", "Well Done !");
                                }
                                else if(x==4){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error('Vendor created failed..', "Oops !");
                                }else if(x==5){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Debit added successfully..", "Well Done !");
                                }else if(x==6){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error('Debit added failed..', "Oops !");
                                }else if(x==7){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Purchase removed successfully..", "Well Done !");
                                }else if(x==8){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error('Purchase removed failed..', "Oops !");
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
                                    document.getElementById("creditTab").style.display = "block";
                                    document.getElementById("vendorTab").style.display = "block";
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
                                <li class="breadcrumb-item active">Purchase</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
				<form style="display: none;" id="purchase_add" action="purchase_add.php" method="POST">
					<input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
				</form>
				<script type="text/javascript">
						function purchase_add(){
						document.getElementById("purchase_add").submit();
						}
				</script>
            <section class="users-edit">
                <div class="card">
                    <div class="card-content">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                                            <i class="feather icon-shopping-cart mr-25"></i><span class="d-none d-sm-block">Purchase</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" id="vendorTab" style="display: none;">
                                        <a class="nav-link d-flex align-items-center" id="information-tab" data-toggle="tab" href="#information" aria-controls="information" role="tab" aria-selected="false">
                                            <i class="feather icon-users mr-25"></i><span class="d-none d-sm-block">Vendor</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" id="creditTab" style="display: none;">
                                        <a class="nav-link d-flex align-items-center" id="social-tab" data-toggle="tab" href="#social" aria-controls="social" role="tab" aria-selected="false">
                                            <i class="fas fa-wallet mr-25"></i><span class="d-none d-sm-block">Credit</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                    <section>
                                        <div class="float-right">
                                            <button type="sumbit" onclick="purchase_add()" class="btn btn-outline-success round mr-30">Add Purchase</button>
                                        </div>
                                    </section>
                                    <section class="data-list-view-header">
                                        <div class="table-responsive">
                                        <?php
                                        if($row['utype'] == "Management"){?>
                                            <table id="purchaseTable" class="table table-striped dataex-html5-selectors">
                                                <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>VENDOR</th>
                                                    <th>ADDED BY</th>
                                                    <th>REMARKS</th>
                                                    <th>TOTAL AMOUNT</th>
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $lle=mysqli_query($con, "SELECT * FROM purchase");
                                                if(mysqli_num_rows($lle)>0){
                                                    foreach($lle as $eel){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $eel['dates'];?></td>
                                                            <td><?php
                                                            $vid=$eel['vid'];
                                                            $vi=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM vendor WHERE vid='$vid'"));
                                                            echo $vi['vname'];?>
                                                            </td>
                                                            <td>
                                                            <?php $jthd=$eel['aid'];
                                                            $va=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$jthd'"));
                                                            echo $va['fname'] ." " .$va['lname'];?>
                                                            </td>
                                                            <td><?php echo $eel['remarks'];?></td>
                                                            <td><?php echo $eel['total'];?></td>
                                                            <td>
                                                            <a title="Edit" onclick="save<?php echo $eel['pcid']; ?>()"><i class="feather icon-edit"></i></a> 
                                                            <a title="Delete"onclick="del<?php echo $eel['pcid']; ?>()"><i class="feather icon-trash-2"></i></a>                                                           
                                                            <form action="#" method="POST">
                                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                            <input value='<?php echo $eel['pcid']; ?>' id="pcidDel<?php echo $eel['pcid']; ?>" name="pcid" style="display: none;">                                                            
                                                            <button type="submit" name="purchaseDelete" id="formDel<?php echo $eel['pcid']; ?>" style="display: none;">
                                                            </form>
                                                            <form action="purchase_edit.php" method="POST">
                                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                            <input value='<?php echo $eel['pcid']; ?>' id="pcidSave<?php echo $eel['pcid']; ?>" name="pcid" style="display: none;">
                                                            <button type="submit" id="formSave<?php echo $eel['pcid']; ?>" style="display: none;">
                                                            </form>
                                                            </td>
                                                        </tr>
                                                        <script text="javascript">
                                                            function del<?php echo $eel['pcid']; ?>(){
                                                                Swal.fire({
                                                                title: 'Are you sure?',
                                                                text: "You won't be able to revert this!",
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Yes, delete it!'
                                                                }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    document.getElementById("formDel<?php echo $eel['pcid']; ?>").click();
                                                                }
                                                                });                                                              
                                                            }function save<?php echo $eel['pcid']; ?>(){
                                                                document.getElementById("formSave<?php echo $eel['pcid']; ?>").click();                         
                                                            }                                       
                                                        </script> 
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        <?php
                                        }else if($row['utype'] == "Branch Manager"){?>
                                            <table id="purchaseTable" class="table table-striped dataex-html5-selectors">
                                                <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>VENDOR</th>
                                                    <th>ADDED BY</th>
                                                    <th>REMARKS</th>
                                                    <th>TOTAL AMOUNT</th>
                                                    <th>ACTION</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $lle=mysqli_query($con, "SELECT * FROM purchase WHERE aid='$aid'");
                                                if(mysqli_num_rows($lle)>0){
                                                    foreach($lle as $eel){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $eel['dates'];?></td>
                                                            <td><?php
                                                            $vid=$eel['vid'];
                                                            $vi=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM vendor WHERE vid='$vid'"));
                                                            echo $vi['vname'];?>
                                                            </td>
                                                            <td>
                                                            <?php $jthd=$eel['aid'];
                                                            $va=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$jthd'"));
                                                            echo $va['fname'] ." " .$va['lname'];?>
                                                            </td>
                                                            <td><?php echo $eel['remarks'];?></td>
                                                            <td><?php echo $eel['total'];?></td>
                                                            <td>
                                                            <a title="Edit" onclick="save<?php echo $eel['pcid']; ?>()"><i class="feather icon-edit"></i></a> 
                                                            <a title="Delete"onclick="del<?php echo $eel['pcid']; ?>()"><i class="feather icon-trash-2"></i></a>                                                           
                                                            <form action="#" method="POST">
                                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                            <input value='<?php echo $eel['pcid']; ?>' id="pcidDel<?php echo $eel['pcid']; ?>" name="pcid" style="display: none;">                                                            
                                                            <button type="submit" name="purchaseDelete" id="formDel<?php echo $eel['pcid']; ?>" style="display: none;">
                                                            </form>
                                                            <form action="purchase_edit.php" method="POST">
                                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                            <input value='<?php echo $eel['pcid']; ?>' id="pcidSave<?php echo $eel['pcid']; ?>" name="pcid" style="display: none;">
                                                            <button type="submit" id="formSave<?php echo $eel['pcid']; ?>" style="display: none;">
                                                            </form>
                                                            </td>
                                                        </tr>
                                                        <script text="javascript">
                                                            function del<?php echo $eel['pcid']; ?>(){
                                                                Swal.fire({
                                                                title: 'Are you sure?',
                                                                text: "You won't be able to revert this!",
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Yes, delete it!'
                                                                }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    document.getElementById("formDel<?php echo $eel['pcid']; ?>").click();
                                                                }
                                                                });                                                              
                                                            }function save<?php echo $eel['pcid']; ?>(){
                                                                document.getElementById("formSave<?php echo $eel['pcid']; ?>").click();                         
                                                            }                                       
                                                        </script> 
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        <?php
                                        }
                                        ?>
                                        </div>
                                    </section>
                                    </div>
                                    <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
                                    <section>
                                        <div class="float-right">
                                            <button type="sumbit" data-toggle="modal" data-target="#vendorCte" class="btn btn-outline-success round mr-30">Create Vendor</button>
                                        </div>
                                    </section>
                                    <section class="data-list-view-header">
                                        <div class="table-responsive">
                                                <table id="vendorTable" class="table table-striped dataex-html5-selectors">
                                                    <thead>
                                                        <tr>
                                                            <th>NAME</th>
                                                            <th>EMAIL</th>
                                                            <th>PHONE</th>
                                                            <th>ACTION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $ttd=mysqli_query($con, "SELECT * FROM vendor");
                                                    if(mysqli_num_rows($ttd)>0){
                                                        foreach($ttd as $ddt){?>
                                                            <tr>
                                                            <form action="#" method="POST">
                                                            <td><input type="text" class="form-control round" value="<?php echo $ddt['vname'] ?>" name="vname"></td>
                                                            <td><input type="text" class="form-control round" value="<?php echo $ddt['vemail'] ?>" name="vemail"></td>
                                                            <td><input type="text" class="form-control round" value="<?php echo $ddt['vphone'] ?>" name="vphone"></td>
                                                            <td>
                                                            <a title="Save" onclick="savebtn<?php echo $ddt['vid']; ?>()"><i class="feather icon-save"></i></a>
                                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                            <input value='<?php echo $ddt['vid'] ?>' name="vid" style="display: none;">
                                                            <button type="submit" name="vendorSave" id="save<?php echo $ddt['vid']; ?>" style="display: none;">
                                                            </td>
                                                            <script text="javascript">
                                                                function savebtn<?php echo $ddt['vid']; ?>(){
                                                                    document.getElementById("save<?php echo $ddt['vid']; ?>").click();
                                                                }
                                                            </script>
                                                            </form>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                        </div>
                                    </section>
                                    </div>
                                    <div class="tab-pane" id="social" aria-labelledby="social-tab" role="tabpanel">
                                    <section>
                                        <div class="float-right">
                                            <button type="sumbit" id="vendorSearchbtn" data-toggle="modal" data-target="#large" class="btn btn-outline-warning round mr-30">More</button>
                                            <button type="sumbit" data-toggle="modal" data-target="#debitCrt" class="btn btn-outline-success round mr-30">Add Debit</button>
                                        </div>
                                    </section>
                                    <section class="data-list-view-header">
                                        <div class="table-responsive">
                                                <table id="creditTable" class="table table-striped dataex-html5-selectors">
                                                    <thead>
                                                        <tr>
                                                            <th>NAME</th>
                                                            <th>TOTAL CREDIT</th>
                                                            <th>TOTAL DEBIT</th>
                                                            <th>CLOSING BALANCE</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $ttd=mysqli_query($con, "SELECT * FROM vendor");
                                                    if(mysqli_num_rows($ttd)>0){
                                                        foreach($ttd as $ddt){?>
                                                            <tr>
                                                            <td><?php echo $ddt['vname'] ?></td>
                                                            <?php
                                                            $vid=$ddt['vid'];
                                                            $htk=mysqli_query($con, "SELECT * FROM vendorcredit WHERE vid='$vid'");
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
                                                            <td><?php echo $credit; ?></td>
                                                            <td><?php echo $debit; ?></td>
                                                            <td><?php echo $total; ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                        </div>
                                    </section>
                                    </div>
                                </div>
                            </div>
                        </div>        
                    </div>
                </section>
            </div>
        </div>
    </div>
<div class="modal fade text-left show" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" style="display: none;" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel17">Vendor Detailed Report</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
					<section id="selectorVid">
						<div class="form-group">
						<label>Select Vendor:</label>
						<select class="form-control" id="vid" name="vid" required data-validation-required-message="This field is required">                                
						<option id="" value="" selected>Choose</option>
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
						<label>Select Start Date:</label>
						<div class="form-group">
						<input type="date" id="startDate" class="form-control pickadate" required placeholder="Start date" data-validation-required-message="This field is required">
						</div>
						<label>Select End Date:</label>
						<div class="form-group">
						<input type="date" id="endDate" class="form-control pickadate" required placeholder="End date" data-validation-required-message="This field is required">
						</div>	
					</section>
					<section class="data-list-view-header" id="vendorSearchTab">
                <div class="table-responsive">                  
							<div id="table-container"></div>							
					</div>
					</section>
            </div>
            <div class="modal-footer" id="searchfooter">					
               <button type="button" id="searchBtn" class="btn btn-primary waves-effect waves-light float-right">Submit</button>
            </div>
				<script type="text/javascript">
					document.getElementById("searchBtn").onclick = function() {
						document.getElementById("selectorVid").style.display = "none";						
						document.getElementById("searchfooter").style.display = "none";						
						var vid=document.getElementById("vid").value;
						var startdate=document.getElementById("startDate").value;
						var enddate=document.getElementById("endDate").value;
						$.ajax({    
							type: "GET",
							url: "purchase_backend.php",
							data: { "vid": vid , "startdate" : startdate, "enddate" : enddate},             
							dataType: "html",
							beforeSend: function() {
							$('#table-container').html(
							'<img src="../../img/Loader.gif" width="25" height="25"/>'
							);
							},
							success: function(data){                    
								setTimeout(function() {
									$('#table-container').html(data);
								}, 2000);
								
							}
						});
						document.getElementById("vendorSearchTab").style.display = "block";
					};
					document.getElementById("vendorSearchbtn").onclick = function() {
						document.getElementById("vid").value="";
						document.getElementById("startDate").value="";
						document.getElementById("endDate").value="";
						document.getElementById("selectorVid").style.display = "block";
						document.getElementById("searchfooter").style.display = "block";						
						document.getElementById("vendorSearchTab").style.display = "none";
					};
				</script>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="debitCrt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Debit</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
            <div class="modal-body">
                    <div class="controls">
                        <label>Name</label>
                        <div class="form-group">
                            <select class="form-control" name="vid" required data-validation-required-message="This field is required">                                
                                <option id="" value="" selected>Choose</option>
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
                    </div>                    
                    <div class="controls">
                        <label>Debit Amount</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="debit" placeholder="Debit Amount" required>
                        </div>
                    </div>
            </div>
        <div class="modal-footer" >
            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
            <button type="sumbit" name="debitcreate" class="btn btn-success">Submit</button>
        </div>        
            </form>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="vendorCte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
            <div class="modal-body">
                    <div class="controls">
                        <label>Name</label>
                        <div class="form-group">
                            <input type="text" class="form-control"  placeholder="Name" name="vname" required data-validation-required-message="This name field is required">
                        </div>
                    </div>
                    <div class="controls">
                        <label>Address</label>
                        <div class="form-group">
                            <fieldset class="form-group">
                                <textarea class="form-control" name="vads" rows="3" placeholder="Address..." required data-validation-required-message="This address field is required"></textarea>
                            </fieldset>
                        </div>
                    </div>
                    <div class="controls">
                        <label>Email</label>
                        <div class="form-group">
                            <input type="email" class="form-control"  placeholder="Email" name="vemail" required data-validation-required-message="This email field is required">
                        </div>
                    </div>
                    <div class="controls">
                        <label>Phone Number</label>
                        <div class="form-group">
                            <input type="text" class="form-control"  placeholder="Phone Number" name="vphone" required data-validation-required-message="This phone number field is required">
                        </div>
                    </div>
            </div>
        <div class="modal-footer">
            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
            <button type="sumbit" name="vendorcreate" class="btn btn-success">Submit</button>
        </div>        
            </form>
        </div>
    </div>
</div>
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
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../js/purchase.js"></script>
    <!-- END: Page JS-->
</body>
<!-- END: Body-->

</html>