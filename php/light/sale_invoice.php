<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require'../PHPMailer/Exception.php';
require'../PHPMailer/PHPMailer.php';
require'../PHPMailer/SMTP.php';
date_default_timezone_set('Etc/UTC');
$aid=$_POST['userKey'];
$sid=$_POST['saleId'];
$ssid = "SA-".str_pad($sid, 5, 0, STR_PAD_LEFT);
include("../connection.php");
$flag=0;
$sales=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM sales WHERE sid='$sid'"));
$cust=$sales['cid'];
$query = "select fname,lname,dest,gender,idnum,doh,utype from actdetails where aid = '$aid'";
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
    <title>ACE CARE</title>
    <link rel="icon" href="../../img/logo.png">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/tether-theme-arrows.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/tether.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/shepherd-theme-default.css">
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
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/dashboard-analytics.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/card-analytics.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/tour/tour.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/invoice.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/app-user.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- END: Custom CSS-->

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
                        <form style="display: none;" id="lightForm" action="../dark/sale_invoice.php" method="POST">
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
                            <h2 class="content-header-title float-left mb-0">Sales</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="javascript:sale()">Sales</a>
                                    </li>
                                    <li class="breadcrumb-item active">Invoice</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            <div class="content-body">
                <!-- invoice functionality start -->
                <?php
                $cust=$sales['cid'];
                $custr=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customer WHERE cid='$cust'"));
                ?>
                <form action="#" method="POST">
                <section class="invoice-print mb-1">
                    <div class="row">
                        <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
                            <div class="input-group">
                                <input type="text" class="form-control" name="emailaddress" placeholder="Email" value="<?php echo $custr['cemail']; ?>" aria-describedby="button-addon2">
                                <div class="input-group-append" id="button-addon2">
                                    <button class="btn btn-outline-primary" type="submit">Send Invoice</button>
                                </div>                                
                            </div>
                        </fieldset>
                        <div class="col-12 col-md-7 d-flex flex-column flex-md-row justify-content-end">
                            <button class="btn btn-primary btn-print mb-1 mb-md-0"> <i class="feather icon-file-text"></i> Print</button>
                        </div>
                    </div>
                </section>
                <input value="SET" id="sendmail" name="sendmail" style="display: none;">
                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                <input value='<?php echo $sid; ?>' id="saleId" name="saleId" style="display: none;">
                </form>
                <!-- invoice functionality end -->
                <!-- invoice page -->
                <section class="card invoice-page">
                    <div id="invoice-template" class="card-body">
                        <!-- Invoice Company Details -->
                        <div id="invoice-company-details" class="row">
                            <div class="col-sm-6 col-12 text-left pt-1">
                                <div class="media pt-1">
                                    <img src="../../img/logo_small.png" alt="company logo"/>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12 text-right">
                                <h1>Sale Invoice</h1>
                                <div class="invoice-details mt-2">
                                    <h6>INVOICE NO.</h6>
                                    <p><?php echo $ssid; ?></p>
                                    <h6 class="mt-2">INVOICE DATE</h6>
                                    <p><?php 
                                    $invoicedate=date("d-M-Y", strtotime($sales['dates']));
                                    echo $invoicedate;
                                    ?></p>
                                    <h6 class="mt-2">BILLED BY</h6>
                                    <p><?php
                                    $saler=$sales['aid'];
                                    $saler1=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$saler'"));
                                    echo $saler1['fname'] ." " .$saler1['lname'];
                                    ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/ Invoice Company Details -->

                        <!-- Invoice Recipient Details -->
                        <div id="invoice-customer-details" class="row pt-2">
                            <div class="col-sm-6 col-12 text-left">
                                <h5>Recipient</h5>
                                <div class="recipient-info my-2">
                                    <p><?php                                    
                                    echo $custr['cname'];?></p>
                                    <p><?php echo $custr['cads'];?></p>
                                </div>
                                <div class="recipient-contact pb-2">
                                    <p>
                                        <i class="feather icon-mail"></i>
                                        <?php echo $custr['cemail'];?>
                                    </p>
                                    <p>
                                        <i class="feather icon-phone"></i>
                                        +91 <?php echo $custr['cphone'];?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12 text-right">
                                <h5>ACE IT CARE & COMPUTERS</h5>
                                <div class="company-info my-2">
                                    <p>
                                    <?php
                                    $branch=$sales['brid'];
                                    $branch1=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$branch'"));
                                    echo $branch1['fname'] ." " .$branch1['lname'];
                                    ?>
                                    </p>
                                    <p>
                                    <?php
                                        echo $branch1['ads1'] .", " .$branch1['ads2'];
                                    ?></p>
                                    <p>
                                    <?php
                                        echo $branch1['pincode'];
                                    ?></p>
                                </div>
                                <div class="company-contact">
                                    <p>
                                        <i class="feather icon-mail"></i>
                                        <?php
                                        $email=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM account WHERE aid='$branch'"));
                                        echo $email['email'];
                                        ?>
                                    </p>
                                    <p>
                                        <i class="feather icon-phone"></i>
                                        +91 <?php echo $branch1['pno'];?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--/ Invoice Recipient Details -->

                        <!-- Invoice Items Details -->
                        <div id="invoice-items-details" class="pt-1 invoice-items-table">
                            <div class="row">
                                <div class="table-responsive col-12">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>ITEM NAME</th>
                                                <th>ITEM DESCRIPTION</th>
                                                <th>ITEM SN#</th>
                                                <th>PRICE</th>
                                                <th>DISCOUNT</th>
                                                <th>AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $subtotal=0;
                                        $tdiscount=0;
                                        $total=0;
                                        $jtf=mysqli_query($con, "SELECT * FROM productdts WHERE stat='$sid'");
                                        $tablecontent="";
                                        $productlist="";
                                        foreach($jtf as $ftj){
                                            $pdtid=$ftj['pdtid'];
                                            $pdt=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM product WHERE pdtid='$pdtid'"));
                                            ?><tr>
                                                <td><?php echo $pdt['pdtname'];?></td>
                                                <td><?php echo $pdt['pdtdetails'];?></td>
                                                <td><?php echo $ftj['ptsn'];?></td>
                                                <td><?php echo $ftj['mrp'];?></td>
                                                <td><?php
                                                $mrp=intval($ftj['mrp']);
                                                $dp=intval($ftj['dp']);
                                                $cost=intval($ftj['cost']);
                                                $dst=$ftj['discount'];
                                                if($dst==0){
                                                    $amount=$cost;
                                                    $discount=$mrp-$amount;
                                                }else if($dst==1){
                                                    $amount=(($cost-$dp)/1.2)+$dp;
                                                    $discount=$mrp-$amount;
                                                }else if($dst==2){
                                                    $amount=(($cost-$dp)/1.4)+$dp;
                                                    $discount=$mrp-$amount;
                                                }else if($dst==3){
                                                    $amount=(($cost-$dp)/1.6)+$dp;
                                                    $discount=$mrp-$amount;
                                                }else if($dst==4){
                                                    $amount=(($cost-$dp)/1.8)+$dp;
                                                    $discount=$mrp-$amount;
                                                }else if($dst==5){
                                                    $amount=(($cost-$dp)/2)+$dp;
                                                    $discount=$mrp-$amount;
                                                }
                                                echo round($discount);
                                                ?></td>
                                                <td><?php echo round($amount);?></td>
                                            </tr><?php
                                            $subtotal=$subtotal+$mrp;
                                            $tdiscount=$tdiscount+round($discount);
                                            $total=$total+round($amount);
                                            $tablecontent.='                                            
                                            <tr>
                                                <td style="text-align: left; border: 1px solid #ddd;  border-collapse: collapse; padding: 15px;">'.$pdt['pdtname'].'<br>'.$pdt['pdtdetails'].'<br>SN#: '.$ftj['ptsn'].'</td>
                                                <td style="text-align: center; border: 1px solid #ddd;  border-collapse: collapse; padding: 15px;">'.$ftj['mrp'].'</td>
                                                <td style="text-align: center; border: 1px solid #ddd;  border-collapse: collapse; padding: 15px;">'.round($discount).'</td>
                                                <td style="text-align: center; border: 1px solid #ddd;  border-collapse: collapse; padding: 15px;">'.round($amount).'</td>                                        
                                            </tr>';
                                            $productlist.=$ftj['ptsn'].", ";
                                        }
                                        ?>                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="invoice-total-details" class="invoice-total-table">
                            <div class="row">
                                <div class="col-7 offset-5">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th>SUBTOTAL</th>
                                                    <td><?php echo $subtotal;?> INR</td>
                                                </tr>
                                                <tr>
                                                    <th>DISCOUNT</th>
                                                    <td><?php echo $tdiscount;?> INR</td>
                                                </tr>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <td><?php echo $total;?> INR</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Footer -->
                        <div id="invoice-footer" class="text-center pt-3">
                            <p>For more information, please contact us : <?php echo $email['email'] ." or +91 " .$branch1['pno'] .".";?>
                                <p class="bank-details mb-0">
                                    <span class="mr-4">We hope for a long business relationship with you . <strong>Thank you !</strong></span>
                                </p>
                        </div>
                        <!--/ Invoice Footer -->

                    </div>
                </section>
                <!-- invoice page end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <?php
    if(isset($_POST['sendmail'])){
        $page = file_get_contents('../../html/sale_invoice.html');
        $page = str_replace('{dates}', $invoicedate, $page);
        $page = str_replace('{productlist}', $productlist, $page);
        $page = str_replace('{billedby}', $saler1['fname'] ." " .$saler1['lname'], $page);
        $page = str_replace('{tablecontent}', $tablecontent, $page);
        $page = str_replace('{subtotal}', $subtotal, $page);
        $page = str_replace('{discount}', $tdiscount, $page);
        $page = str_replace('{total}', $total, $page);
        $page = str_replace('{branch}', $email['email'] ." or +91 " .$branch1['pno'].".", $page);
        if(isset($_POST['emailaddress'])){
            $emailAds=$_POST['emailaddress'];
        }else{
            $emailAds=$custr['cemail'];
        }
        $mail = new PHPMailer(true);
            //Server settings
            $mail->SMTPDebug = 0; // 0 - Disable Debugging, 2 - Responses received from the server
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'princedeveloper24@gmail.com'; // SMTP username
            $mail->Password = 'hrpamlmttmansptj'; // SMTP password
            $mail->SMTPSecure = 'tls';//PHPMailer::ENCRYPTION_STARTTLS; Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port = 587; // TCP port to connect to
        
            //Recipients
            $mail->setFrom('princejacob528@gmail.com', 'ACE IT CARE & COMPUTERS');
            $mail->addAddress( $emailAds, $custr['cname']); // Add a recipient
        
            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'ACE IT CARE & COMPUTERS -'.$ssid;
            $mail->Body =$page;
            
        
            /* Attachement 
            $mail->addAttachment('upload/file.pdf');
            $mail->addAttachment('upload/image.png', 'image 1');*/    // Optional name
                    
            if($mail->send()){
                $GLOBALS['flag']=2;
            }
    }
    ?>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">        
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2021<a class="text-bold-800 grey darken-2" href="https://automatedbusiness.000webhostapp.com/" target="_blank">Automated BPM,</a>All rights Reserved</span><span class="float-md-right d-none d-md-block">Final Year Project, BTech, CSE.</span></p>
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25"><em>Developed By Prince Jacob, Greshma R, Amrutha Soman.</em></span><span class="float-md-right d-none d-md-block"><a class="text-bold-800 grey darken-2" href="http://jaibharathengg.com/" target="_blank">Jai Bharath College Of Management And Engineering Technology</a></span></p>
    </footer>
    <!-- END: Footer-->
    <!-- BEGIN: Form-->

    <!-- END: Form-->

    <!-- BEGIN: Vendor JS-->
    <script src="../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../app-assets/vendors/js/charts/apexcharts.min.js"></script>
    <script src="../../app-assets/vendors/js/extensions/tether.min.js"></script>
    <script src="../../app-assets/vendors/js/extensions/shepherd.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <script src="../../app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->
    <script src="../../app-assets/js/scripts/pages/invoice.js"></script>
</body>
<!-- END: Body-->

</html>