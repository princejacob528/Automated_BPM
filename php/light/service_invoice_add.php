<?php
$aid=$_POST['userKey'];
$srid=$_POST['srid'];
$flag=0;
include("../connection.php");
$service=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM service WHERE srid='$srid'"));
$cid=$service['cid'];
$srcr=$service['srcr'];
$brid=$service['brid'];
$srcreater=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$srcr'"));
$customer=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customer WHERE cid='$cid'"));
$branch=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brid'"));
$branchemail=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM account WHERE aid='$brid'"));
if(isset($_POST['delsrdid'])){ 
    $flag=5;
    $delsrdid=$_POST['delsrdid'];
    if(mysqli_query($con, "DELETE FROM servicedt WHERE srdid='$delsrdid'")){
        $flag=4;
    }
}
if(isset($_POST['saveBtn'])){
    $flag=2;
    $srdid=$_POST['srdid'];
    $invdt=$_POST['invdt'];
    $cost=$_POST['cost'];
    $disc=$_POST['disc'];
    $dates=$_POST['dates'];
    $cashmethod=$_POST['cashmethod'];
    $total=$_POST['total'];
    $incentive=$_POST['incentive'];
    if(isset($_POST['remarks'])){
        $remarks = $_POST['remarks'];
    }else{
        $remarks = "";
    }
    if(mysqli_query($con, "UPDATE service SET stat='5' WHERE srid='$srid'")){
        if(mysqli_query($con, " INSERT INTO serviceinvoice (srid, aid, dates, remarks, incentive, total) VALUES ('$srid', '$aid', '$dates', '$remarks', '$incentive', '$total')")){
            $flag=1;
            foreach($srdid as $key => $value){
                $invdetails=$invdt[$key];
                $dtcost=$cost[$key];
                $discount=$disc[$key];
                if(mysqli_query($con, "UPDATE servicedt SET invdetails='$invdetails', cost='$dtcost', discount='$discount' WHERE srdid='$value'")){
                    $flag=1;
                }
            }
        }
    }
    if($flag==1){
        if($cashmethod == "credit"){
            if(mysqli_query($con, "INSERT INTO custcredit (cid, dates, credit, debit, ref) VALUES ('$cid', '$dates', '$total', '0', '$srid')")){
                $flag=3;
            }
        }
    }
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
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
                        <form style="display: none;" id="lightForm" action="../dark/service_invoice_add.php" method="POST">
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
                                        text: "Sales added successfully.",                                      
                                        showCancelButton: false        
                                    }).then((result) => {
                                        document.getElementById("invoice").submit();
                                    });
                                }else if(x==2){
                                    Swal.fire({
                                        icon: 'error',        
                                        text: "Something went wrong.",                                      
                                        showCancelButton: false        
                                    }).then((result) => {
                                        document.getElementById("toDo").submit();
                                    });
                                }else if(x==3){
                                    Swal.fire({
                                        icon: 'success',        
                                        text: "Sales added successfully and credit is marked.",                                      
                                        showCancelButton: false        
                                    }).then((result) => {
                                        document.getElementById("invoice").submit();
                                    });
                                }else if(x==4){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.warning("Service deleted successfully.", "Well Done !");
                                }else if(x==5){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error("Service deleted failed.", "Oops !");
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
                <li class=" nav-item"><a href="javascript:purchase()" style="display: none;" id="pCe"><i class="fa fa-cart-arrow-down"></i><span class="menu-title">Purchase</span></a>
                <form style="display: none;" id="purchase" action="purchase.php" method="POST">
                   <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                </form>
                <script type="text/javascript">
                   function purchase(){
                       document.getElementById("purchase").submit();
                   }
                </script>
                </li>
                <li class="active nav-item"><a href="javascript:toDo()" style="display: none;" id="tDo"><i class="feather icon-check-square"></i><span class="menu-title">To Do</span></a>
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
    <form style="display: none;" id="invoice" action="service_invoice.php" method="POST">
        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
        <input value='<?php echo $srid; ?>' id="serviceId" name="serviceId" style="display: none;">
        <input value='mail' name="sendmail" style="display: none;">
    </form>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Sales</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:toDo()">To Do</a></li>
                                <li class="breadcrumb-item active">Add Invoice</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
            <form action="#" method="POST" id="delBtn">
                <input value='<?php echo $srid; ?>' id="srid" name="srid" style="display: none;">
                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                <input id="delsrdid" name="delsrdid" style="display: none;">
            </from>
        <section>
            <div class="my-2"></div>
            <div class="row"> 
                <div class="col-6">
                <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <h4 class="light">Customer Details</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="card-body">
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
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <h4 class="light">Branch Details</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                            <tbody>                            
                                <tr>
                                    <th>NAME</th>
                                    <td><?php echo $branch['fname'] ." " .$branch['lname'];?></td>
                                </tr>
                                <tr>
                                    <th>ADDRESS</th>
                                    <td><?php echo $branch['ads1'] .", " .$branch['ads2'].", ".$branch['pincode'];?></td>
                                </tr>
                                <tr>
                                    <th>EMAIL</th>
                                    <td><?php echo $branchemail['email'];?></td>
                                </tr>
                                <tr>
                                    <th>PHONE</th>
                                    <td><?php echo $branch['pno'];?></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="card">
            <div class="row">
                <div class="col-12">
                   <div class="card-header">
                        <div class="col-12">
                            <h4 class="light">Complaint Details</h4>
                            <hr>
                        </div>
                   </div>
                   <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                            <div class="row pl-2">
                                <div class="col-6">
                                    <p><strong>ITEM</strong></p>
                                </div>
                                <div class="col-6">
                                    <p><?php echo $service['items'];?></p>
                                </div>
                            </div>
                            </div>
                            <div class="col-6">
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>COMPLAINT</strong></p>
                                </div>
                                <div class="col-6">
                                    <p><?php echo $service['complaints'];?></p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                            <div class="row pl-2">
                                <div class="col-6">
                                    <p><strong>ASSIST</strong></p>
                                </div>
                                <div class="col-6">
                                    <p><?php echo $srcreater['fname'] ." " .$srcreater['lname'];?></p>
                                </div>
                            </div>
                            </div>
                            <div class="col-6">
                            <div class="row">
                                <div class="col-6">
                                    <p><strong>REGISTERED DATE</strong></p>
                                </div>
                                <div class="col-6">
                                    <p><?php echo $service['dates'];?></p>
                                </div>
                            </div>
                            </div>
                        </div>
                   </div>                    
                </div>
            </div>
            </div>
        </section>
        <form action="#" method="POST">           
        <section>
            <div class="card">
            <div class="row">
                <div class="col-12">
                   <div class="card-header">
                        <div class="col-12">
                            <h4 class="light">Products List</h4>
                            <hr>
                        </div>
                   </div>
                   <div class="card-body">
                    <table id="editTable" class="table table-striped dataex-html5-selectors">
                    <thead>
                        <tr>
                            <th class="text-center">SERVICE DETAILS</th>
                            <th class="text-center">SERVICE DESCRIPTION</th>
                            <th class="text-center">PROFIT RATE</th>
                            <th class="text-center">DISCOUNT RATE</th>
                            <th class="text-center">AMOUNT</th>
                            <th class="text-center">ACTION</th>
                        </tr>
                        </thead>
                    <tbody>
                    <?php
                    $counter=0;
                    $incent=0;
                    $mdk=mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                    if(mysqli_num_rows($mdk)>0){
                        foreach($mdk as $kdm){
                            $counter=$counter+1;
                            $incent=$incent+$kdm['incentive'];
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    $srvid=$kdm['svrid'];
                                    $svrdt=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$srvid'"));
                                    echo '<strong>Servicer: </strong>' .$svrdt['fname'] . " ".$svrdt['lname'] .'<br>';
                                    echo '<strong>Details: </strong>' .$kdm['srdetails'] .'<br>';
                                    echo '<strong>Incentive: </strong>' .$kdm['incentive'];
                                    ?>
                                </td>
                                <td>
                                    <input name="invdt[]" type="text" class="form-control">
                                    <input name="srdid[]" value="<?php echo $kdm['srdid'];?>" id="srdid<?php echo $counter; ?>" type="text" style="display: none;">
                                </td>
                                <td>
                                    <select name="cost[]" id="pfr<?php echo $counter; ?>" onchange="incent()" class="form-control">
                                        <option value="" selected>Select</option>
                                        <option value="100" >100%</option>
                                        <option value="80" >80%</option>
                                        <option value="60" >60%</option>
                                        <option value="40" >40%</option>
                                        <option value="20" >20%</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="disc[]" id="dis<?php echo $counter; ?>" onchange="incent()" class="form-control">
                                        <option value="1" selected>0-Level</option>                                
                                        <option value="1.2">1-Level</option>                                
                                        <option value="1.4">2-Level</option>
                                        <option value="1.6">3-Level</option>                                
                                        <option value="1.8">4-Level</option>                                
                                        <option value="2">5-Level</option>
                                    </select>
                                </td>
                                <td>
                                    <input id="amount<?php echo $counter; ?>" value="0" class="form-control" disabled>
                                    <input id="incent<?php echo $counter; ?>" value="<?php echo $kdm['incentive'];?>" style="display: none;">
                                </td>
                                <td><a title="Remove Service Details" id="del<?php echo $counter; ?>"><i class="feather icon-trash-2 light font-medium-4"></i></a></td>
                            </tr>
                            <script type="text/javascript">
                                document.getElementById("del<?php echo $counter; ?>").onclick = function() {
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
                                            document.getElementById("delsrdid").value = document.getElementById("srdid<?php echo $counter; ?>").value;
                                            document.getElementById("delBtn").submit();
                                        }
                                    });                                    
                                };
                            </script>
                            <?php
                        }
                    }
                    ?>
                    <script type="text/javascript">
                        function incent(){
                            var total=0
                            for(i=1; i<4; i++){
                                var disct=$('#pfr'+i+'').val();
                                if(disct){
                                    var incent = parseInt(document.getElementById("incent"+i+"").value);
                                    var dis = parseFloat(document.getElementById("dis"+i+"").value);
                                    var pfr = parseInt(document.getElementById("pfr"+i+"").value);
                                    var cost = (incent/100)*pfr;
                                    var amount = (cost/dis)+incent;
                                    document.getElementById("amount"+i+"").value = Math.round(amount);                                    
                                }
                                var amount = $('#amount'+i+'').val();
                                if(amount)
                                    total = total + parseInt(amount);
                                document.getElementById("totaler").value = total;
                                document.getElementById("total").value = total;
                            }
                        };
                    </script>
                    </tbody>
                    </table>
                   </div>
                </div>
            </div>
            </div>
        </section>
        <section>
            <div class="card">
                <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-5">
                        <div class="my-2"></div>
                        <fieldset class="form-group pl-1">
                            <textarea class="form-control" name="remarks" rows="6" placeholder="Remarks"></textarea>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-7">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>DATE</th>
                                    <td><input type="text" id="dates" name="dates"  class="form-control round"></td>
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
                                    <td>
                                        <input type="text" disabled  id="total" value="0" class="form-control round">
                                        <input type="text" name="total"  id="totaler" value="0" style="display: none">
                                        <input type="text" name="incentive" value="<?php echo $incent;?>" style="display: none">
                                    </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-footer">
                <div class="float-right">
                    <button type="button" onclick="toDo()" class="btn btn-outline-danger">Cancel</button>
                    <input type="submit" value="Submit" id="saveBtn" name="saveBtn" class="btn btn-outline-success">
                </div>
                </div>
            </div>
        </section>
        <input value='<?php echo $srid; ?>' id="srid" name="srid" style="display: none;">
        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
        </from>            
           </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#editTable').DataTable({
            "searching": false,
            "bPaginate": false,
            "lengthChange": false,
            "bInfo": false,
            "bAutoWidth": true,
            "columnDefs": [
                { "width": "35%", "targets": 0 },
                { "width": "30%", "targets": 1 },
                { "width": "10%", "targets": 2 },
                { "width": "10%", "targets": 3 },
                { "width": "15%", "targets": 4 }
            ]
            });
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
    <!-- END: Page JS-->
</body>
<!-- END: Body-->

</html>