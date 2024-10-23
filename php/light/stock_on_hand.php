<?php
$aid=$_POST['userKey'];
include("../connection.php");
$serachbtn="";
if(isset($_POST['searchName']))
    $serachbtn=$_POST['searchName'];
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
    <title>Automated BPM</title>
    <link rel="icon" href="../../img/logo.png">

    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/vendors.min.css">
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
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="../../style/products.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns ecommerce-application navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" onload="avatarLoad()">

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
                        <form style="display: none;" id="lightForm" action="../dark/stock_on_hand.php" method="POST">
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
                <li class="active nav-item"><a href="javascript:stockHand()" style="display: none;" id="stkH"><i class="fa fa-shopping-bag"></i><span class="menu-title">Stock on Hand</span></a>
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
                                    <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Stock on hand</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form style="display: none;" action="#" method="POST" id="searchBtn">
                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                <input id="searchName" name="searchName" style="display: none;">
            </form>
            <form style="display: none;" action="#" method="POST" id="defaultBtn">
                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
            </form>
            <div class="content-body">
                <section>
                    <div class="row">
                    <div class="col-lg-12 col-12">               
                    <fieldset class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control round" id="filter" value="<?php echo $serachbtn; ?>" placeholder="Search by product name..">
                            <div class="form-control-position">
                                <i class="feather icon-search"></i>
                            </div>
                    </fieldset>
                    </div>
                    <div class="col-lg-12 col-12">
                        <div class="float-right">
                            <button type="button" id="pdtadder"  class="btn btn-outline-warning round">Manage Purchase</button>
                        </div>
                    </div>
                </section>
            <script type="text/javascript">
                $("#filter").keyup(function(event) {
                    if (event.keyCode === 13) {
                        filterCheck();                    
                    }
                });
                function filterCheck() {
                    var x=document.getElementById('filter').value;
                    if(x==""){
                        document.getElementById("defaultBtn").submit();                        
                    }
                    else{
                        document.getElementById("searchName").value = document.getElementById("filter").value;
                        document.getElementById("searchBtn").submit();
                    }
                    
                }
                document.getElementById("pdtadder").onclick = function() {
                    purchase();         
                };
            </script>
            <?php
            if(!isset($_POST['searchName'])){
                if($row['utype'] == "Management"){
                    $pdt=mysqli_query($con, "SELECT * FROM product");
                    ?><section id="searchView"><?php
                    if(mysqli_num_rows($pdt)>0){
                        ?><section id="wishlist" class="grid-view wishlist-items"><?php
                        foreach($pdt as $pdts){
                            $pdtid=$pdts['pdtid'];
                            $pdtdt=mysqli_query($con, "SELECT * FROM productdts WHERE pdtid='$pdtid' AND stat='0'");
                            if(mysqli_num_rows($pdtdt)>0){?>
                                    <div class="card ecommerce-card">
                                        <div class="card-content">                            
                                            <div class="card-body">                                
                                                <div class="item-img text-center">
                                                    <img src="../../img/products.png" class="img-fluid" alt="img-placeholder">                                       
                                                </div>
                                                <div class="item-name">                                        
                                                        <?php echo $pdts['pdtname']; ?>                                      
                                                </div>
                                                <div>
                                                    <p class="item-description">
                                                    <?php echo $pdts['pdtdetails']; ?>
                                                    </p>
                                                </div>
                                            </div>                                
                                            <section id="accordion-with-margin">
                                                <div class="card collapse-icon accordion-icon-rotate">
                                                    <div class="accordion" id="accordionExample">
                                                        <div class="collapse-margin">
                                                            <div class="card-header" id="headingOne" data-toggle="collapse" role="button" data-target="#collapse<?php echo $pdtid;?>" aria-expanded="false" aria-controls="collapseOne">
                                                                <span class="lead collapse-title">
                                                                    <h6 class="text-bold-200 light">Product SN#</h6>
                                                                </span>
                                                            </div>
                                                            <div id="collapse<?php echo $pdtid;?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <?php 
                                                                    foreach($pdtdt as $pdtdts){
                                                                        $refp=$pdtdts['refp'];
                                                                        $aaids=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM purchase WHERE pcid='$refp'"));
                                                                        $aaid=$aaids['aid'];
                                                                        $act=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$aaid'"));
                                                                        ?><label class="text-bold-100"><?php echo $pdtdts['ptsn'] ."( ".$act['fname']." ".$act['lname']." )"; ?></label><br><?php
                                                                    }
                                                                    ?>                                                         
                                                                </div>
                                                            </div>
                                                        </div>                                        
                                                    </div>
                                            </section>                                                              
                                        </div>
                                    </div>                                
                                <?php
                            }
                        }
                        ?></section><?php
                    ?></section><?php
                    }
                    else{
                        ?>
                        <div>
                        <h3 class="text-muted pl-1" center>No Products found...</h3>
                        </div>
                        </section>
                        <?php
                    }
                }
                else{
                    $pdt=mysqli_query($con, "SELECT * FROM product");
                    $counts=0;
                    ?><section id="searchView"><?php
                    if(mysqli_num_rows($pdt)>0){
                        ?><section id="wishlist" class="grid-view wishlist-items"><?php
                        foreach($pdt as $pdts){
                            $pdtid=$pdts['pdtid'];
                            $pdtdt=mysqli_query($con, "SELECT * FROM productdts WHERE pdtid='$pdtid' AND stat='0'");
                            $counter=0;
                            foreach($pdtdt as $pdtdtss){
                                $refps=$pdtdtss['refp'];
                                $aaidss=mysqli_query($con, "SELECT * FROM purchase WHERE pcid='$refps' AND aid='$aid'");
                                if(mysqli_num_rows($aaidss)>0){
                                    $counter=1;
                                }                                    
                            }
                            if($counter==1){
                                    $counts=1;?>
                                    <div class="card ecommerce-card">
                                        <div class="card-content">                            
                                            <div class="card-body">                                
                                                <div class="item-img text-center">
                                                    <img src="../../img/products.png" class="img-fluid" alt="img-placeholder">                                       
                                                </div>
                                                <div class="item-name">                                        
                                                        <?php echo $pdts['pdtname']; ?>                                      
                                                </div>
                                                <div>
                                                    <p class="item-description">
                                                    <?php echo $pdts['pdtdetails']; ?>
                                                    </p>
                                                </div>
                                            </div>                                
                                            <section id="accordion-with-margin">
                                                <div class="card collapse-icon accordion-icon-rotate">
                                                    <div class="accordion" id="accordionExample">
                                                        <div class="collapse-margin">
                                                            <div class="card-header" id="headingOne" data-toggle="collapse" role="button" data-target="#collapse<?php echo $pdtid;?>" aria-expanded="false" aria-controls="collapseOne">
                                                                <span class="lead collapse-title">
                                                                    <h6 class="text-bold-200 light">Product SN#</h6>
                                                                </span>
                                                            </div>
                                                            <div id="collapse<?php echo $pdtid;?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <?php 
                                                                    foreach($pdtdt as $pdtdts){
                                                                        $refp=$pdtdts['refp'];
                                                                        $aaids=mysqli_query($con, "SELECT * FROM purchase WHERE pcid='$refp' AND aid='$aid'");
                                                                        if(mysqli_num_rows($aaids)>0){
                                                                            ?><label class="text-bold-100"><?php echo $pdtdts['ptsn']; ?></label><br><?php
                                                                        }                                                                        
                                                                    }
                                                                    ?>                                                         
                                                                </div>
                                                            </div>
                                                        </div>                                        
                                                    </div>
                                            </section>                                                              
                                        </div>
                                    </div>                                
                                <?php
                            }
                        }
                        ?></section><?php
                    ?></section><?php
                    }
                    if($counts==0){
                        ?>
                        <div>
                        <h3 class="text-muted pl-1" center>No Products found...</h3>
                        </div>
                        </section>
                        <?php
                    }
                }
            }
            else if(isset($_POST['searchName'])){
                $memil=$_POST['searchName'];
                if($row['utype'] == "Management"){
                    $pdt=mysqli_query($con, "SELECT * FROM product WHERE pdtname LIKE '%{$memil}%' OR pdtdetails LIKE '%{$memil}%'");
                    ?><section id="searchView">
                    <h4 class="warning">Search results</h4>
                    <hr>
                    <?php
                    if(mysqli_num_rows($pdt)>0){
                        ?><section id="wishlist" class="grid-view wishlist-items"><?php
                        foreach($pdt as $pdts){
                            $pdtid=$pdts['pdtid'];
                            $pdtdt=mysqli_query($con, "SELECT * FROM productdts WHERE pdtid='$pdtid' AND stat='0'");
                            if(mysqli_num_rows($pdtdt)>0){?>
                                    <div class="card ecommerce-card">
                                        <div class="card-content">                            
                                            <div class="card-body">                                
                                                <div class="item-img text-center">
                                                    <img src="../../img/products.png" class="img-fluid" alt="img-placeholder">                                       
                                                </div>
                                                <div class="item-name">                                        
                                                        <?php echo $pdts['pdtname']; ?>                                      
                                                </div>
                                                <div>
                                                    <p class="item-description">
                                                    <?php echo $pdts['pdtdetails']; ?>
                                                    </p>
                                                </div>
                                            </div>                                
                                            <section id="accordion-with-margin">
                                                <div class="card collapse-icon accordion-icon-rotate">
                                                    <div class="accordion" id="accordionExample">
                                                        <div class="collapse-margin">
                                                            <div class="card-header" id="headingOne" data-toggle="collapse" role="button" data-target="#collapse<?php echo $pdtid;?>" aria-expanded="false" aria-controls="collapseOne">
                                                                <span class="lead collapse-title">
                                                                    <h6 class="text-bold-200 light">Product SN#</h6>
                                                                </span>
                                                            </div>
                                                            <div id="collapse<?php echo $pdtid;?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <?php 
                                                                    foreach($pdtdt as $pdtdts){
                                                                        $refp=$pdtdts['refp'];
                                                                        $aaids=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM purchase WHERE pcid='$refp'"));
                                                                        $aaid=$aaids['aid'];
                                                                        $act=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$aaid'"));
                                                                        ?><label class="text-bold-100"><?php echo $pdtdts['ptsn'] ."( ".$act['fname']." ".$act['lname']." )"; ?></label><br><?php
                                                                    }
                                                                    ?>                                                         
                                                                </div>
                                                            </div>
                                                        </div>                                        
                                                    </div>
                                            </section>                                                              
                                        </div>
                                    </div>                                
                                <?php
                            }
                        }
                        ?></section><?php
                    ?></section><?php
                    }
                    else{
                        ?>
                        <div>
                        <h3 class="text-muted pl-1" center>No result found...</h3>
                        </div>
                        </section>
                        <?php
                    }
                }
                else{
                    $pdt=mysqli_query($con, "SELECT * FROM product WHERE pdtname LIKE '%{$memil}%' OR pdtdetails LIKE '%{$memil}%'");
                    $counts=0;
                    ?><section id="searchView"><?php
                    if(mysqli_num_rows($pdt)>0){
                        ?><section id="wishlist" class="grid-view wishlist-items"><?php
                        foreach($pdt as $pdts){
                            $pdtid=$pdts['pdtid'];
                            $pdtdt=mysqli_query($con, "SELECT * FROM productdts WHERE pdtid='$pdtid' AND stat='0'");
                            $counter=0;
                            foreach($pdtdt as $pdtdtss){
                                $refps=$pdtdtss['refp'];
                                $aaidss=mysqli_query($con, "SELECT * FROM purchase WHERE pcid='$refps' AND aid='$aid'");
                                if(mysqli_num_rows($aaidss)>0){
                                    $counter=1;
                                }                                    
                            }
                            if($counter==1){?>
                                    <div class="card ecommerce-card">
                                        <div class="card-content">                            
                                            <div class="card-body">                                
                                                <div class="item-img text-center">
                                                    <img src="../../img/products.png" class="img-fluid" alt="img-placeholder">                                       
                                                </div>
                                                <div class="item-name">                                        
                                                        <?php echo $pdts['pdtname']; ?>                                      
                                                </div>
                                                <div>
                                                    <p class="item-description">
                                                    <?php echo $pdts['pdtdetails']; ?>
                                                    </p>
                                                </div>
                                            </div>                                
                                            <section id="accordion-with-margin">
                                                <div class="card collapse-icon accordion-icon-rotate">
                                                    <div class="accordion" id="accordionExample">
                                                        <div class="collapse-margin">
                                                            <div class="card-header" id="headingOne" data-toggle="collapse" role="button" data-target="#collapse<?php echo $pdtid;?>" aria-expanded="false" aria-controls="collapseOne">
                                                                <span class="lead collapse-title">
                                                                    <h6 class="text-bold-200 light">Product SN#</h6>
                                                                </span>
                                                            </div>
                                                            <div id="collapse<?php echo $pdtid;?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <?php 
                                                                    foreach($pdtdt as $pdtdts){
                                                                        $refp=$pdtdts['refp'];
                                                                        $aaids=mysqli_query($con, "SELECT * FROM purchase WHERE pcid='$refp' AND aid='$aid'");
                                                                        if(mysqli_num_rows($aaids)>0){
                                                                            $counts=1;
                                                                            ?><label class="text-bold-100"><?php echo $pdtdts['ptsn']; ?></label><br><?php
                                                                        }                                                                        
                                                                    }
                                                                    ?>                                                         
                                                                </div>
                                                            </div>
                                                        </div>                                        
                                                    </div>
                                            </section>                                                              
                                        </div>
                                    </div>                                
                                <?php
                            }
                        }
                        ?></section><?php
                    ?></section><?php
                    }
                    if($counts==0){
                        ?>
                        <div>
                        <h3 class="text-muted pl-1" center>No result found...</h3>
                        </div>
                        </section>
                        <?php
                    }
                }
            }
            ?>
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

    <!-- BEGIN: Page Extensions JS-->
    <script src="../../app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- END: Page Extensions JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <script src="../../app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>