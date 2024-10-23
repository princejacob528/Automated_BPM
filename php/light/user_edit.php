<?php
$aid=$_POST['userKey'];
$flag=0;
include("../connection.php");
if(isset($_POST['infoSubmit'])){
    $flag=1;
    $data=$_POST['gender'];
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $fname=$_POST['fname'];
    $lname=$_POST['lname'];
    $dob=$_POST['dob'];
    $ads1=$_POST['address1'];
    $ads2=$_POST['address2'];
    $country=$_POST['country'];
    $state=$_POST['state'];
    $pincode=$_POST['pincode'];
    $pno=$_POST['pno']; 
    $quy="UPDATE actdetails SET fname='$fname', lname='$lname', dob='$dob', pno='$pno', gender='$data', ads1='$ads1', ads2='$ads2', country='$country', states='$state', pincode='$pincode'  WHERE aid='$aid'";
    if(mysqli_query($con, $quy)){
        $flag=2;
    }      
}
if(isset($_POST['skillSubmit'])){
    $flag=1;
    if(mysqli_query($con,"DELETE FROM skills where aid='$aid'")){
        $flag=3;
    if(isset($_POST['Skills'])){
    foreach ($_POST['Skills'] as $subject){
        $stat=mysqli_query($con,"select skid from skills where aid='$aid'");
        if(mysqli_num_rows($stat)>0){
            $sid=mysqli_fetch_array($stat);
            $made=$sid['skid'];
            if(mysqli_query($con,"UPDATE skills SET $subject='1' WHERE skid=$made")){
                $flag=4;                
            }            
        }
        else{
            if(mysqli_query($con,"INSERT INTO skills (aid,$subject) VALUES ('$aid','1')"))
                $flag=4;            
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
$skillCt=0;
$stmt=mysqli_query($con,"select * from skills where aid='$aid'");
if(mysqli_num_rows($stmt)>0){
    $skill = mysqli_fetch_array($stmt);
}
else{
    $skillCt=1;
}

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
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/toastr.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Vendor CSS-->    
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/pickers/pickadate/pickadate.css">    
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
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/app-user.css">    
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/extensions/toastr.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <!-- END: Custom CSS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

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
                        <form style="display: none;" id="lightForm" action="../dark/user_edit.php" method="POST">
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
                                navBar();
                                var x='<?php echo $flag; ?>';
                                if(x==2){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Information updated successfully..", "Well Done !");
                                }
                                else if(x==1){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error('Something went wrong..', "Oops !");                                    
                                }
                                else if(x==3){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.info('Skills deleted successfully...','Well Done  !');                                    
                                }
                                else if(x==4){
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success('Skills updated successfully...','Well Done  !');                                    
                                }  
                                var y='<?php echo $row['gender']; ?>';
                                if(y=="Female"){
                                    document.getElementById("avatarImg").src="../../img/female_avatar.png";
                                    document.getElementById("avatarPic").src="../../img/female_avatar.png";
                                    document.getElementById("femaleRad").checked = true;                                
                                }
                                else if(y=="Male"){
                                    document.getElementById("avatarImg").src="../../img/male_avatar.png";
                                    document.getElementById("avatarPic").src="../../img/male_avatar.png";
                                    document.getElementById("maleRad").checked = true; 
                                }
                                else{
                                    document.getElementById("avatarImg").src="../../img/male_avatar.png";
                                    document.getElementById("avatarPic").src="../../img/female_avatar.png";
                                    document.getElementById("otherRad").checked = true; 
                                }                                                                                                                          
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
                <li class="active nav-item"><a href="javascript:empDt()" style="display: none;" id="eMpD"><i class="feather icon-user"></i><span class="menu-title">User Details</span></a>
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
                <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">User</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="javascript:empDt()">User Details</a></li>
                                    <li class="breadcrumb-item active">Edit</li>
                                </ol>
                            </div>
                    </div>
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                                            <i class="feather icon-user mr-25"></i><span class="d-none d-sm-block">Account</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" id="information-tab" data-toggle="tab" href="#information" aria-controls="information" role="tab" aria-selected="false">
                                            <i class="feather icon-info mr-25"></i><span class="d-none d-sm-block">Information</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" id="social-tab" data-toggle="tab" href="#social" aria-controls="social" role="tab" aria-selected="false">
                                            <i class="feather icon-award mr-25"></i><span class="d-none d-sm-block">Skill</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                        <!-- users edit media object start -->
                                        <div class="media mb-2">
                                            <a class="mr-2 my-25" href="#">
                                                <img id="avatarPic" alt="users avatar" class="users-avatar-shadow rounded" height="90" width="90">
                                            </a>
                                            <div class="media-body mt-50">
                                                <h4 class="media-heading"><?php echo $row['fname']; ?> <?php echo $row['lname']; ?></h4>
                                                <div class="col-12 d-flex mt-1 px-0">
                                                    <a href="#" class="btn btn-primary d-none d-sm-block mr-75 disabled">Change</a>
                                                    <a href="#" class="btn btn-primary d-block d-sm-none mr-75 disabled"><i class="feather icon-edit-1"></i></a>
                                                    <a href="#" class="btn btn-outline-danger d-none d-sm-block disabled">Remove</a>
                                                    <a href="#" class="btn btn-outline-danger d-block d-sm-none disabled"><i class="feather icon-trash-2"></i></a>
                                                </div>
                                                <label>Since the website is free hosted, you cannot update the profile picture.</label>
                                            </div>
                                        </div>
                                        <!-- users edit media object ends -->
                                        <!-- users edit account form start -->
                                        <form novalidate>
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Username</label>
                                                            <input type="text" class="form-control" placeholder="Username" value="<?php echo $eml['email']; ?>" disabled data-validation-required-message="This username field is required">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control" placeholder="Name" value="<?php echo $row['fname']; ?> <?php echo $row['lname']; ?>" disabled data-validation-required-message="This name field is required">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Designation</label>
                                                            <input type="email" class="form-control" placeholder="Email" value="<?php echo $row['dest']; ?>" disabled data-validation-required-message="This email field is required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">

                                                    <div class="form-group">
                                                        <label>User Type</label>
                                                        <input type="text" class="form-control" placeholder="User Type" value="<?php echo $row['utype']; ?>" disabled data-validation-required-message="This email field is required">
                                                        </div>
                                                    <div class="form-group">
                                                        <label>Employee ID</label>
                                                        <input type="text" class="form-control" placeholder="Employee ID" value="<?php echo $row['idnum']; ?>" disabled data-validation-required-message="This email field is required">
                                                        </div>
                                                    <div class="form-group">
                                                        <label>Date of Hire</label>
                                                        <input type="text" class="form-control" value="<?php echo $row['doh']; ?>" disabled placeholder="Date of Hire">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="table-responsive border rounded px-1 ">
                                                        <label class="border-bottom py-1 mx-1 mb-0 font-medium-1"><i class="feather icon-lock mr-50 "></i>Your account details are disabled. As per the Policy of the Company, you can only edit the information details.</label>
                                                        
                                                    </div>
                                                </div>                                                
                                            </div>
                                        </form>
                                        <!-- users edit account form ends -->
                                    </div>
                                    <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
                                        <!-- users edit Info form start -->                                        
                                        <form action="#" method="POST" novalidate>
                                            <div class="row mt-1">
                                                <div class="col-12 col-sm-6">
                                                    <h5 class="mb-1"><i class="feather icon-user mr-25"></i>Personal Information</h5>
                                                    <div class="row">
                                                        <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>First Name</label>
                                                                <input type="text" name="fname" class="form-control" value="<?php echo $row['fname']; ?>" placeholder="" data-validation-required-message="Valid first name is required">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Last Name</label>
                                                                <input type="text" name="lname" class="form-control" value="<?php echo $row['lname']; ?>" placeholder="" data-validation-required-message=" Valid last name is required">
                                                            </div>
                                                        </div>
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label>Birth date</label>
                                                                    <input type="date" name="dob" class="form-control pickadate" max="2020-12-31" min="1980-01-01" required placeholder="Birth date" data-validation-required-message="This birthdate field is required" value="<?php echo $row['dob']; ?>">                                                                    
                                                                 </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Mobile</label>
                                                            <input type="text" name="pno" class="form-control" value="<?php echo $row['pno']; ?>" placeholder="Mobile number here..." data-validation-required-message="This mobile number is required">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">
                                                                        <input type="radio" name="gender" id="maleRad" <?php if (isset($gender) && $gender=="Male") echo "checked";?> value="Male" required>
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Male
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">
                                                                        <input type="radio" name="gender" id="femaleRad" <?php if (isset($gender) && $gender=="Female") echo "checked";?> value="Female" required>
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Female
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2">
                                                                <fieldset>
                                                                    <div class="vs-radio-con">
                                                                        <input type="radio" name="gender" id="otherRad" <?php if (isset($gender) && $gender=="Other") echo "checked";?> value="Other" required>
                                                                        <span class="vs-radio">
                                                                            <span class="vs-radio--border"></span>
                                                                            <span class="vs-radio--circle"></span>
                                                                        </span>
                                                                        Other
                                                                    </div>
                                                                </fieldset>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <h5 class="mb-1 mt-2 mt-sm-0"><i class="feather icon-map-pin mr-25"></i>Address</h5>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Address Line 1</label>
                                                            <input type="text" name="address1" class="form-control" value="<?php echo $row['ads1']; ?>" required placeholder="Address Line 1" data-validation-required-message="This Address field is required">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Address Line 2</label>
                                                            <input type="text" name="address2" class="form-control" value="<?php echo $row['ads2']; ?>" required placeholder="Address Line 2" data-validation-required-message="This Address field is required">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Country</label>
                                                            <input type="text" name="country" class="form-control" required value="<?php echo $row['country']; ?>" data-validation-required-message="This Time Zone field is required">
                                                        </div>
                                                    </div>                                                  
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>State</label>
                                                            <input type="text" name="state" class="form-control" required value="<?php echo $row['states']; ?>" data-validation-required-message="This Time Zone field is required">
                                                        </div>
                                                    </div>                                                    
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Pincode</label>
                                                            <input type="text" name="pincode" class="form-control" required placeholder="postcode" value="<?php echo $row['pincode']; ?>" data-validation-required-message="This Postcode field is required">
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                    <button name="infoSubmit" type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save
                                                        Changes</button>
                                                    <button type="reset" class="btn btn-outline-warning">Reset</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- users edit Info form ends -->
                                    </div>
                                    <div class="tab-pane" id="social" aria-labelledby="social-tab" role="tabpanel">
                                        <!-- users edit socail form start -->
                                        <form action="#" method="POST" novalidate>
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Skills</label>
                                                        <select class="form-control" id="users-language-select2" multiple="multiple" name="Skills[]">
                                                            <?php if($skillCt!=1){
                                                                if($skill['Hardware_Service_PC']==1){
                                                                ?><option value="Hardware_Service_PC" selected>Hardware Service (PC)</option><?php
                                                                }else{
                                                                ?><option value="Hardware_Service_PC">Hardware Service (PC)</option><?php
                                                                }if($skill['Hardware_Service_Laptop']==1){
                                                                ?><option value="Hardware_Service_Laptop" selected>Hardware Service (Laptop)</option><?php
                                                                }else{
                                                                ?><option value="Hardware_Service_Laptop">Hardware Service (Laptop)</option><?php
                                                                }if($skill['Chip_Level_Service']==1){
                                                                ?><option value="Chip_Level_Service" selected>Chip Level Service</option><?php
                                                                }else{
                                                                ?><option value="Chip_Level_Service">Chip Level Service</option><?php                                                                
                                                                }if($skill['Advanced_Hardware_Service']==1){
                                                                ?><option value="Advanced_Hardware_Service" selected>Advanced Hardware Service</option><?php
                                                                }else{
                                                                ?><option value="Advanced_Hardware_Service">Advanced Hardware Service</option><?php
                                                                }if($skill['Software_Service']==1){
                                                                ?><option value="Software_Service" selected>Software Service</option><?php
                                                                }else{
                                                                ?><option value="Software_Service">Software Service</option><?php
                                                                }if($skill['Advanced_Software_Service']==1){
                                                                ?><option value="Advanced_Software_Service" selected>Advanced Software Service</option><?php
                                                                }else{
                                                                ?><option value="Advanced_Software_Service">Advanced Software Service</option><?php
                                                                }if($skill['Surveillance_System_Management']==1){
                                                                ?><option value="Surveillance_System_Management" selected>Surveillance System Management</option><?php
                                                                }else{
                                                                ?><option value="Surveillance_System_Management">Surveillance System Management</option><?php
                                                                }if($skill['Printer_Service']==1){
                                                                ?><option value="Printer_Service" selected>Printer Service</option><?php
                                                                }else{
                                                                ?><option value="Printer_Service">Printer Service</option><?php
                                                                }if($skill['Toner_Service']==1){
                                                                ?><option value="Toner_Service" selected>Toner Service</option><?php
                                                                }else{
                                                                ?><option value="Toner_Service">Toner Service</option><?php
                                                                }
                                                            }else{
                                                                ?>
                                                                <option value="Hardware_Service_PC">Hardware Service (PC)</option>
                                                                <option value="Hardware_Service_Laptop">Hardware Service (Laptop)</option>
                                                                <option value="Chip_Level_Service">Chip Level Service</option>
                                                                <option value="Advanced_Hardware_Service">Advanced Hardware Service</option>
                                                                <option value="Software_Service">Software Service</option>
                                                                <option value="Advanced_Software_Service">Advanced Software Service</option>
                                                                <option value="Surveillance_System_Management">Surveillance System Management</option>
                                                                <option value="Printer_Service">Printer Service</option>
                                                                <option value="Toner_Service">Toner Service</option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="default-collapse collapse-bordered">
                                                <div class="card collapse-header">
                                                    <div id="headingCollapse1" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                                        <span class="lead collapse-title">
                                                            Instructions
                                                        </span>
                                                    </div>
                                                    <div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1" class="collapse">
                                                        <div class="card-content">                                                        
                                                        <div class="divider divider-primary">
                                                            <div class="divider-text">Hardware Service (PC)</div>
                                                        </div>
                                                            <div class="card-body">
                                                                Servicing Product: Computer, Computer Related Products, etc.<hr>
                                                                Computer(PC) hardware servicing refers to doing repairs and maintenance on the physical components of a computer and its peripherals, including fans, hard drives, keyboards and printers.
                                                            </div>
                                                            <div class="divider divider-primary">
                                                            <div class="divider-text">Hardware Service (Laptop)</div>
                                                        </div>
                                                            <div class="card-body">
                                                            Servicing Product: Laptop, Laptop Related Products, etc.<hr>
                                                            Laptop hardware servicing refers to doing repairs and maintenance on the physical components of a laptop and its peripherals, including fans, hard drives, keyboards and printers.
                                                            </div>
                                                            <div class="divider divider-primary">
                                                            <div class="divider-text">Chip Level Service</div>
                                                        </div>
                                                            <div class="card-body">
                                                            Servicing Product: Computer Motherboard, Laptop Motherboard, other IC, Chip related Products, etc.<hr>
                                                            Chip Level Service Training (CCST) is the training program in which an individual is trained over the assembling and dismantling laptops as well as desktops. This training provides the knowledge of the hardware maintenance on chip level of the computers.  
                                                            </div>
                                                            <div class="divider divider-primary">
                                                            <div class="divider-text">Advanced Hardware Service</div>
                                                        </div>
                                                            <div class="card-body">
                                                                Servicing Product: Electrical & Electronics Related Products, etc.<hr>
                                                                Advanced hardware servicing refers to doing repairs and maintenance on the physical components of a Electrical & Electronics Products.
                                                            </div>
                                                            <div class="divider divider-primary">
                                                            <div class="divider-text">Software Service</div>
                                                        </div>
                                                            <div class="card-body">
                                                            Servicing Product: Operating system, Basic softwares, etc.<hr>
                                                            Software servicing, a service is software that performs automated tasks, responds to hardware events, or listens for data requests from other software. In a user's operating system, these services are often loaded automatically at startup, and run in the background, without user interaction. For example, in Microsoft Windows, many services are loaded to accomplish different functions. They respond to user keyboard shortcuts, index and optimize the file system, and communicate with other devices on the local network. An example of a Windows service is Messenger, which allows users to send messages to other Windows users.
                                                            </div>
                                                            <div class="divider divider-primary">
                                                            <div class="divider-text">Advanced Software Service</div>
                                                        </div>
                                                            <div class="card-body">
                                                            Servicing Product: Advanced(additional) softwares like AutoCAD, Adobe products, etc.<hr>
                                                            Same as above, Like software servicing.
                                                            </div>
                                                            <div class="divider divider-primary">
                                                            <div class="divider-text">Surveillance System Management</div>
                                                        </div>
                                                            <div class="card-body">
                                                            Servicing Product: CCTV, Security surveillance related products, etc.<hr>    
                                                            Surveillance is the monitoring of behavior, activities, or information for the purpose of information gathering, influencing, managing or directing. This can include observation from a distance by means of electronic equipment, such as closed-circuit television (CCTV), or interception of electronically transmitted information, such as Internet traffic. It can also include simple technical methods, such as human intelligence gathering and postal interception.
                                                            </div>
                                                            <div class="divider divider-primary">
                                                            <div class="divider-text">Printer Service</div>
                                                        </div>
                                                            <div class="card-body">
                                                            Servicing Product: Printers,Printers related products, etc.<hr>
                                                            The Printer Technician is a professional who installs new printers, configure printers on network, maintain and ensures the proper function of standalone printers, scanners or Network Printers. The Printer Technician takes care of the regular and routine repairs including replacements such as new ink cartridges. The Printer Technician must be comfortable copying, faxing, producing images and other printer-related issues. As a professional, working in coordination with information technology staff, you will assist in resolving underlying problems with the network.
                                                            </div>
                                                            <div class="divider divider-primary">
                                                            <div class="divider-text">Toner Service</div>
                                                        </div>
                                                            <div class="card-body">
                                                            Servicing Product: Laser Toner, Ink cartridge, Ribbon cartridge etc.<hr>
                                                            Toner refilling is the practice of refilling empty laser printer toner cartridges with new toner powder. This enables the cartridge to be reused, saving the cost of a complete new cartridge and the impact of the waste and disposal of the old one. While toner cartridges are commonly refilled with results reported to be very good, in at least some cases refilling without full remanufacturing may never leave waste toner from each print and paper debris in the cartridge, potentially causing backgrounding problems and producing contamination in the refilled cartridge.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                    <button name="skillSubmit" type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save
                                                        Changes</button>
                                                    <button type="reset" class="btn btn-outline-warning">Reset</button>
                                                </div>
                                            
                                        </form>
                                        <!-- users edit socail form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->

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

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="../../app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <script src="../../app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="../../app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="../../app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <script src="../../app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../app-assets/js/scripts/pages/app-user.js"></script>
    <script src="../../app-assets/js/scripts/navs/navs.js"></script>
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>