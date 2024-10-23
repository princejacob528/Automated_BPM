<?php
$aid=$_POST['userKey'];
include("../connection.php");
$flag=0;
if(isset($_POST['saveBtn'])){
    if(isset($_POST['existingcustomer'])){
        $flag=2;
        $cid=$_POST['cid'];
        $dates=$_POST['dates'];
        $cashmethod=$_POST['cashmethod'];
        $total=$_POST['total'];
        $incentive=$_POST['incentive'];
        $brid=$_POST['brid'];
        if(isset($_POST['remarks'])){
            $remarks=$_POST['remarks'];
        }else{
            $remarks="";
        }
        if(mysqli_query($con, "INSERT INTO sales (dates, cid, aid, remarks, total, incentive, brid) VALUES ('$dates', '$cid', '$aid', '$remarks', '$total', '$incentive', '$brid')")){
            $ttg=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM sales WHERE dates='$dates' AND cid='$cid' AND aid='$aid' AND remarks='$remarks' AND total='$total' AND incentive='$incentive' "));
            $sid=$ttg['sid'];
            $item=$_POST['item'];
            $disc=$_POST['disc'];
            foreach($item as $key => $value){
                $discount=$disc[$key];
                if(mysqli_query($con, "UPDATE productdts SET discount='$discount', stat='$sid' WHERE ptid='$value'")){
                    $flag=1;
                }
            }
            if($flag==1){
                if($cashmethod == "credit"){
                    if(mysqli_query($con, "INSERT INTO custcredit (cid, dates, credit, debit, ref) VALUES ('$cid', '$dates', '$total', '0', '$sid')")){
                        $flag=3;
                    }
                }
            }
        }
    }
    else if(isset($_POST['newcustomer'])){
        $flag=2;
        $cname=$_POST['cname'];
        $cphone=$_POST['cphone'];
        $cemail=$_POST['cemail'];
        $cads=$_POST['cads'];
        if(mysqli_query($con, "INSERT INTO customer (cname, cads, cemail, cphone) VALUES ('$cname', '$cads', '$cemail', '$cphone') ")){
            $cust=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customer WHERE cname='$cname' AND cads='$cads' AND cphone='$cphone' AND cemail='$cemail'"));
            $cid=$cust['cid'];
        }
        $dates=$_POST['dates'];
        $cashmethod=$_POST['cashmethod'];
        $total=$_POST['total'];
        $incentive=$_POST['incentive'];
        $brid=$_POST['brid'];
        if(isset($_POST['remarks'])){
            $remarks=$_POST['remarks'];
        }else{
            $remarks="";
        }
        if(mysqli_query($con, "INSERT INTO sales (dates, cid, aid, remarks, total, incentive, brid) VALUES ('$dates', '$cid', '$aid', '$remarks', '$total', '$incentive', '$brid')")){
            $ttg=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM sales WHERE dates='$dates' AND cid='$cid' AND aid='$aid' AND remarks='$remarks' AND total='$total' AND incentive='$incentive' "));
            $sid=$ttg['sid'];
            $item=$_POST['item'];
            $disc=$_POST['disc'];
            foreach($item as $key => $value){
                $discount=$disc[$key];
                if(mysqli_query($con, "UPDATE productdts SET discount='$discount', stat='$sid' WHERE ptid='$value'")){
                    $flag=1;
                }
            }
            if($flag==1){
                if($cashmethod == "credit"){
                    if(mysqli_query($con, "INSERT INTO custcredit (cid, dates, credit, debit, ref) VALUES ('$cid', '$dates', '$total', '0', '$sid')")){
                        $flag=3;
                    }
                }
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
    <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
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
                        <form style="display: none;" id="lightForm" action="../dark/sales_add.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        </form>
                        <form style="display: none;" id="invoice" action="sale_invoice.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        <input value='<?php echo $sid; ?>' id="saleId" name="saleId" style="display: none;">
                        <input value='mail' name="sendmail" style="display: none;">
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
                                        document.getElementById("sale").submit();
                                    });
                                }else if(x==3){
                                    Swal.fire({
                                        icon: 'success',        
                                        text: "Sales added successfully and credit is marked.",                                      
                                        showCancelButton: false        
                                    }).then((result) => {
                                        document.getElementById("invoice").submit();
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
                <li class="active nav-item"><a href="javascript:sale()" style="display: none;" id="sAl"><i class="fa fa-cart-plus"></i><span class="menu-title">Sales</span></a>
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
                            <h2 class="content-header-title float-left mb-0">Stock</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:sale()">Sales</a></li>
                                <li class="breadcrumb-item active">Add Sales</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="content-body">
        <form action="#" method="POST">
                <section>
                    <div class="my-2"></div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="col-12">
                                        <h4 class="light">Customer Details</h4>
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
                                            Existing Customer
                                    </div>                                
                                    <div class="form-group pl-2" id="extvedorForm" style="display: none">
                                        <div class="my-2"></div>
                                        <select class="form-control col-9" name="cid" required data-validation-required-message="This field is required">                                
                                            <option selected>Select</option>
                                            <?php
                                            $lfe=mysqli_query($con, "SELECT * FROM customer");
                                            if(mysqli_num_rows($lfe)){
                                                foreach($lfe as $lfd){
                                                    ?><option value="<?php echo $lfd['cid'];?>"><?php echo $lfd['cname'];?></option><?php
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
                                            New Customer
                                    </div>
                                    <div class="form-group pl-2" id="newvedorForm" style="display: none">
                                        <div class="my-2"></div>
                                        <div class="controls">
                                            <label>Name</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control col-9"  placeholder="Name" name="cname" data-validation-required-message="This name field is required">
                                            </div>
                                        </div>
                                        <div class="controls">
                                            <label>Address</label>
                                            <div class="form-group">
                                                <fieldset class="form-group">
                                                    <textarea class="form-control col-9" name="cads" rows="3" placeholder="Address..." data-validation-required-message="This address field is required"></textarea>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="controls">
                                            <label>Email</label>
                                            <div class="form-group">
                                                <input type="email" class="form-control col-9"  placeholder="Email" name="cemail" data-validation-required-message="This email field is required">
                                            </div>
                                        </div>
                                        <div class="controls">
                                            <label>Phone Number</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control col-9"  placeholder="Phone Number" name="cphone" data-validation-required-message="This phone number field is required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-2"></div>
                                    <input type="text" name="name" id="formselector" style="display: none;">
                                </div>                           
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
                                        <select class="form-control col-9" onchange="productFetch()" id="brid" required data-validation-required-message="This field is required">                                
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
                                        <input name="brid" id="bridTxt" class="form-control" style="display: none">
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
                                    <div style="display: none;">
                                        <select id="SelectService" name="item[]" onchange="fetcher()" class="form-control">                                        
                                            <option selected>Select</option>                                                        
                                        </select>
                                    </div>
                                    <table id="vendorTable" class="table table-striped dataex-html5-selectors">
                                        <thead>
                                            <tr>                                    
                                                <th class="text-center">ITEM DESCRIPTION</th>
                                                <th class="text-center">MRP</th>
                                                <th class="text-center">PRICE</th>                                
                                                <th class="text-center">DISCOUNT</th>
                                                <th class="text-center">AMOUNT</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div id="item1">
                                                    <select id="selector1" onchange="fetcher()" class="form-control">                                
                                                        <option selected>Select</option>                                                        
                                                    </select>
                                                    </div>
                                                </td>
                                                <td><input id="mrp1" value="0" class="form-control" disabled></td>
                                                <td><input id="price1" value="0" class="form-control" disabled></td>
                                                <td>
                                                    <select name="disc[]" id="dis1" onchange="incent()" class="form-control">
                                                        <option value="0" selected>0-Level</option>                                
                                                        <option value="1" >1-Level</option>                                
                                                        <option value="2" >2-Level</option>
                                                        <option value="3" >3-Level</option>                                
                                                        <option value="4" >4-Level</option>                                
                                                        <option value="5" >5-Level</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input id="amount1" value="0" class="form-control" disabled>
                                                    <input id="dp1" value="0" class="form-control" style="display: none">
                                                    <input id="incentive1" value="0" class="form-control" style="display: none">
                                                </td>
                                                <td><a title="Add Row" id="add"><i class="feather icon-plus-square success font-medium-4"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
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
                                                            <input type="text"  name="total" id="totaler" style="display: none;" value="0" class="form-control round">
                                                            <input type="text"  name="incentive" id="incentive" style="display: none;" value="0" class="form-control round"></td>
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
                                    <button type="button" onclick="javascript:sale()" class="btn btn-outline-danger">Cancel</button>                                
                                    <input type="submit"  id="saveBtn" name="saveBtn" class="btn btn-outline-success" value="Submit">
                                    </div>
                                </div>
                            </div>                    
                        </div>
                    </div>
                </section>
                <script type="text/javascript">
                    $(document).ready(function() {
                        var x=2;
                        $("#add").click(function(){
                            if(x<16){
                            var html='<tr><td><div id="target"></div></td><td><input id="mrp'+x+'" value="0" class="form-control" disabled></td><td><input id="price'+x+'" value="0" class="form-control" disabled></td><td><select name="disc[]" id="dis'+x+'" onchange="incent()" class="form-control"><option value="0" selected>0-Level</option><option value="1" >1-Level</option><option value="2" >2-Level</option><option value="3" >3-Level</option><option value="4" >4-Level</option><option value="5" >5-Level</option></select></td><td><input id="amount'+x+'" value="0" class="form-control" disabled><input id="dp'+x+'" value="0" class="form-control" style="display: none;"><input id="incentive'+x+'" value="0" class="form-control" style="display: none;"></td><td><a title="Remove Row" id="remove"><i class="feather icon-minus-square danger font-medium-4"></i></a></td></tr>';            
                            $("#vendorTable").append(html);
                            var original = $('select#SelectService:eq(0)');
                            var allSelects = $('select#SelectService');
                            var clone = original.clone();
                            $('option', clone).filter(function(i) {
                                return allSelects.find('option:selected[value="' + $(this).val() + '"]').length;
                            }).remove();
                            $('#target').append(clone).attr('id', "item" + x);
                            x=x+1;
                            }else{
                                toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                };
                                toastr.warning("Items limited on 15 numbers.", "Limit exceeded");
                            }
                            
                        });
                        $("#vendorTable").on('click', '#remove', function(){
                            $(this).closest('tr').remove();
                            x=x-1;
                        });        
                    });
                    function fetcher() {
                        document.getElementById("brid").disabled = true;
                        var fetd = $('#item1 .form-control').val();
                        if(fetd){
                            $('#SelectService').val(fetd);
                        }
                        var ptid = $('#item1 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+1+"").value = dp;
                                document.getElementById("price"+1+"").value = cost;
                                document.getElementById("amount"+1+"").value = cost;
                                document.getElementById("mrp"+1+"").value = mrp;
                                document.getElementById("incentive"+1+"").value = Math.round(((cost-dp)/10));
                                $('#dis1').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item2 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+2+"").value = dp;
                                document.getElementById("price"+2+"").value = cost;
                                document.getElementById("amount"+2+"").value = cost;
                                document.getElementById("mrp"+2+"").value = mrp;
                                document.getElementById("incentive"+2+"").value = Math.round(((cost-dp)/10));
                                $('#dis2').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item3 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+3+"").value = dp;
                                document.getElementById("price"+3+"").value = cost;
                                document.getElementById("amount"+3+"").value = cost;
                                document.getElementById("mrp"+3+"").value = mrp;
                                document.getElementById("incentive"+3+"").value = Math.round(((cost-dp)/10));
                                $('#dis3').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item4 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+4+"").value = dp;
                                document.getElementById("price"+4+"").value = cost;
                                document.getElementById("amount"+4+"").value = cost;
                                document.getElementById("mrp"+4+"").value = mrp;
                                document.getElementById("incentive"+4+"").value = Math.round(((cost-dp)/10));
                                $('#dis4').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item5 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+5+"").value = dp;
                                document.getElementById("price"+5+"").value = cost;
                                document.getElementById("amount"+5+"").value = cost;
                                document.getElementById("mrp"+5+"").value = mrp;
                                document.getElementById("incentive"+5+"").value = Math.round(((cost-dp)/10));
                                $('#dis5').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item6 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+6+"").value = dp;
                                document.getElementById("price"+6+"").value = cost;
                                document.getElementById("amount"+6+"").value = cost;
                                document.getElementById("mrp"+6+"").value = mrp;
                                document.getElementById("incentive"+6+"").value = Math.round(((cost-dp)/10));
                                $('#dis6').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item7 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+7+"").value = dp;
                                document.getElementById("price"+7+"").value = cost;
                                document.getElementById("amount"+7+"").value = cost;
                                document.getElementById("mrp"+7+"").value = mrp;
                                document.getElementById("incentive"+7+"").value = Math.round(((cost-dp)/10));
                                $('#dis7').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item8 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+8+"").value = dp;
                                document.getElementById("price"+8+"").value = cost;
                                document.getElementById("amount"+8+"").value = cost;
                                document.getElementById("mrp"+8+"").value = mrp;
                                document.getElementById("incentive"+8+"").value = Math.round(((cost-dp)/10));
                                $('#dis8').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item9 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+9+"").value = dp;
                                document.getElementById("price"+9+"").value = cost;
                                document.getElementById("amount"+9+"").value = cost;
                                document.getElementById("mrp"+9+"").value = mrp;
                                document.getElementById("incentive"+9+"").value = Math.round(((cost-dp)/10));
                                $('#dis9').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item10 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+10+"").value = dp;
                                document.getElementById("price"+10+"").value = cost;
                                document.getElementById("amount"+10+"").value = cost;
                                document.getElementById("mrp"+10+"").value = mrp;
                                document.getElementById("incentive"+10+"").value = Math.round(((cost-dp)/10));
                                $('#dis10').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item11 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+11+"").value = dp;
                                document.getElementById("price"+11+"").value = cost;
                                document.getElementById("amount"+11+"").value = cost;
                                document.getElementById("mrp"+11+"").value = mrp;
                                document.getElementById("incentive"+11+"").value = Math.round(((cost-dp)/10));
                                $('#dis11').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item12 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+12+"").value = dp;
                                document.getElementById("price"+12+"").value = cost;
                                document.getElementById("amount"+12+"").value = cost;
                                document.getElementById("mrp"+12+"").value = mrp;
                                document.getElementById("incentive"+12+"").value = Math.round(((cost-dp)/10));
                                $('#dis12').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item13 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+13+"").value = dp;
                                document.getElementById("price"+13+"").value = cost;
                                document.getElementById("amount"+13+"").value = cost;
                                document.getElementById("mrp"+13+"").value = mrp;
                                document.getElementById("incentive"+13+"").value = Math.round(((cost-dp)/10));
                                $('#dis13').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item14 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+14+"").value = dp;
                                document.getElementById("price"+14+"").value = cost;
                                document.getElementById("amount"+14+"").value = cost;
                                document.getElementById("mrp"+14+"").value = mrp;
                                document.getElementById("incentive"+14+"").value = Math.round(((cost-dp)/10));
                                $('#dis14').val(0);
                                total();
                                }
                            });
                        }
                        var ptid = $('#item15 .form-control').val();
                        if(ptid){
                        $.ajax({        
                            url: 'sale_add_backend.php',
                            type: 'get',
                            data:{ "ptid": ptid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var dp = response[i].dp;
                                    var cost = response[i].cost;
                                    var mrp = response[i].mrp;
                                }
                                document.getElementById("dp"+15+"").value = dp;
                                document.getElementById("price"+15+"").value = cost;
                                document.getElementById("amount"+15+"").value = cost;
                                document.getElementById("mrp"+15+"").value = mrp;
                                document.getElementById("incentive"+15+"").value = Math.round(((cost-dp)/10));
                                $('#dis15').val(0);
                                total();
                                }
                            });
                        }        
                    };
                    function incent(){
                            for(i=1; i<16; i++){
                                var disct=$('#dis'+i+'').val();
                                if(disct){
                                    if(disct==0){
                                        var price = parseInt(document.getElementById("price"+i+"").value);
                                        var dp = parseInt(document.getElementById("dp"+i+"").value);
                                        document.getElementById("amount"+i+"").value = price;
                                        document.getElementById("incentive"+i+"").value = Math.round(((price-dp)/10));
                                        total();
                                    }else if(disct==1){
                                        var price = parseInt(document.getElementById("price"+i+"").value);
                                        var dp = parseInt(document.getElementById("dp"+i+"").value);
                                        price= Math.round(((price-dp)/1.2)+dp);
                                        document.getElementById("amount"+i+"").value = price;
                                        document.getElementById("incentive"+i+"").value = Math.round(((price-dp)/10));
                                        total();
                                    }else if(disct==2){
                                        var price = parseInt(document.getElementById("price"+i+"").value);
                                        var dp = parseInt(document.getElementById("dp"+i+"").value);
                                        price= Math.round(((price-dp)/1.4)+dp);
                                        document.getElementById("amount"+i+"").value = price;
                                        document.getElementById("incentive"+i+"").value = Math.round(((price-dp)/10));
                                        total();
                                    }else if(disct==3){
                                        var price = parseInt(document.getElementById("price"+i+"").value);
                                        var dp = parseInt(document.getElementById("dp"+i+"").value);
                                        price= Math.round(((price-dp)/1.6)+dp);
                                        document.getElementById("amount"+i+"").value = price;
                                        document.getElementById("incentive"+i+"").value = Math.round(((price-dp)/10));
                                        total();
                                    }else if(disct==4){
                                        var price = parseInt(document.getElementById("price"+i+"").value);
                                        var dp = parseInt(document.getElementById("dp"+i+"").value);
                                        price= Math.round(((price-dp)/1.8)+dp);
                                        document.getElementById("amount"+i+"").value = price;
                                        document.getElementById("incentive"+i+"").value = Math.round(((price-dp)/10));
                                        total();
                                    }else if(disct==5){
                                        var price = parseInt(document.getElementById("price"+i+"").value);
                                        var dp = parseInt(document.getElementById("dp"+i+"").value);
                                        price= Math.round(((price-dp)/2)+dp);
                                        document.getElementById("amount"+i+"").value = price;
                                        document.getElementById("incentive"+i+"").value = Math.round(((price-dp)/10));
                                        total();
                                    }
                                }
                            }
                    };
                    function total(){
                        var total=0;
                        if(document.getElementById("amount1")){total = total + parseInt(document.getElementById("amount1").value);};
                        if(document.getElementById("amount2")){total = total + parseInt(document.getElementById("amount2").value);};
                        if(document.getElementById("amount3")){total = total + parseInt(document.getElementById("amount3").value);};
                        if(document.getElementById("amount4")){total = total + parseInt(document.getElementById("amount4").value);};
                        if(document.getElementById("amount5")){total = total + parseInt(document.getElementById("amount5").value);};
                        if(document.getElementById("amount6")){total = total + parseInt(document.getElementById("amount6").value);};
                        if(document.getElementById("amount7")){total = total + parseInt(document.getElementById("amount7").value);};
                        if(document.getElementById("amount8")){total = total + parseInt(document.getElementById("amount8").value);};
                        if(document.getElementById("amount9")){total = total + parseInt(document.getElementById("amount9").value);};
                        if(document.getElementById("amount10")){total = total + parseInt(document.getElementById("amount10").value);};
                        if(document.getElementById("amount11")){total = total + parseInt(document.getElementById("amount11").value);};
                        if(document.getElementById("amount12")){total = total + parseInt(document.getElementById("amount12").value);};
                        if(document.getElementById("amount13")){total = total + parseInt(document.getElementById("amount13").value);};
                        if(document.getElementById("amount14")){total = total + parseInt(document.getElementById("amount14").value);};
                        if(document.getElementById("amount15")){total = total + parseInt(document.getElementById("amount15").value);};
                        document.getElementById("total").value = total;
                        document.getElementById("totaler").value = total;
                        var incentive=0;
                        if(document.getElementById("incentive1")){incentive = incentive + parseInt(document.getElementById("incentive1").value);};
                        if(document.getElementById("incentive2")){incentive = incentive + parseInt(document.getElementById("incentive2").value);};
                        if(document.getElementById("incentive3")){incentive = incentive + parseInt(document.getElementById("incentive3").value);};
                        if(document.getElementById("incentive4")){incentive = incentive + parseInt(document.getElementById("incentive4").value);};
                        if(document.getElementById("incentive5")){incentive = incentive + parseInt(document.getElementById("incentive5").value);};
                        if(document.getElementById("incentive6")){incentive = incentive + parseInt(document.getElementById("incentive6").value);};
                        if(document.getElementById("incentive7")){incentive = incentive + parseInt(document.getElementById("incentive7").value);};
                        if(document.getElementById("incentive8")){incentive = incentive + parseInt(document.getElementById("incentive8").value);};
                        if(document.getElementById("incentive9")){incentive = incentive + parseInt(document.getElementById("incentive9").value);};
                        if(document.getElementById("incentive10")){incentive = incentive + parseInt(document.getElementById("incentive10").value);};
                        if(document.getElementById("incentive11")){incentive = incentive + parseInt(document.getElementById("incentive11").value);};
                        if(document.getElementById("incentive12")){incentive = incentive + parseInt(document.getElementById("incentive12").value);};
                        if(document.getElementById("incentive13")){incentive = incentive + parseInt(document.getElementById("incentive13").value);};
                        if(document.getElementById("incentive14")){incentive = incentive + parseInt(document.getElementById("incentive14").value);};
                        if(document.getElementById("incentive15")){incentive = incentive + parseInt(document.getElementById("incentive15").value);};
                        document.getElementById("incentive").value = incentive;
                    };
                    function radChange1(){
                        document.getElementById("formselector").name = "existingcustomer";
                        document.getElementById("extvedorForm").style.display = "block";
                        document.getElementById("newvedorForm").style.display = "none";                             
                    };
                    function radChange2(){                               
                        document.getElementById("formselector").name = "newcustomer";
                        document.getElementById("extvedorForm").style.display = "none";
                        document.getElementById("newvedorForm").style.display = "block";                                
                    };
                    function productFetch(){
                        document.getElementById("bridTxt").value = document.getElementById("brid").value;
                        var brid=document.getElementById("brid").value;
                        $.ajax({
                            url: 'sale_product_backend.php',
                            type: 'get',
                            data:{ "brid": brid },
                            dataType: 'JSON',
                            success: function(response){
                                var len = response.length;
                                for(var i=0; i<len; i++){
                                    var ptid = response[i].ptid;
                                    var products = response[i].products;
                                    var x = document.getElementById("SelectService");                                    
                                    var option = document.createElement("option");
                                    option.text = products;
                                    option.value = ptid;
                                    x.add(option);
                                    var y = document.getElementById("selector1");
                                    var options = document.createElement("option");
                                    options.text = products;
                                    options.value = ptid;
                                    y.add(options);
                                }

                            }
                        });
                    };
                </script>
            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
        </form>
            </div>            
        </div>
    </div>   
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
    <script src="../../js/tabler.js"></script>
    <!-- END: Page JS-->
</body>
<!-- END: Body-->

</html>