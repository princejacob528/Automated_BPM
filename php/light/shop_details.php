<?php
$aid=$_POST['userKey'];
include("../connection.php");
$flag=0;
$emailTxt="";
if(isset($_POST['msgBtn'])){    
    $emailTo=$_POST['toemail'];
    $statm = mysqli_query($con, "select aid from account where email = '$emailTo'");
    $emailToid = mysqli_fetch_array($statm);
    $emailid=$emailToid['aid'];    
    $msgTxt=$_POST['messageTxt'];
    $msgTit=$_POST['subjectTxt'];
    if(mysqli_query($con,"INSERT INTO notificat (aid, nsub, ntitle, nstatus, ntype, nsender) VALUES ('$emailid', '$msgTxt', '$msgTit', '0', 'Message','$aid')")){
        $flag=2;
    }
    else{
        $flag=1;
    }
}
$serachbtn="";
if(isset($_POST['searchName']))
    $serachbtn=$_POST['searchName'];
if(isset($_POST['createBtn'])){
    $emailTxt=$_POST['emailTxt'];
    $passTxt=$_POST['passTxt'];
    $fnameTxt=$_POST['fnameTxt'];
    $lnameTxt=$_POST['lnameTxt'];
    $genderTxt  =$_POST['genderTxt'];
    $ads1Txt=$_POST['ads1Txt'];
    $ads2Txt=$_POST['ads2Txt'];
    $pnoTxt=$_POST['pnoTxt'];
    $pincodeTxt=$_POST['pincodeTxt'];
    $idnumTxt=$_POST['idnumTxt'];
    $kmde=mysqli_query($con, "SELECT * FROM account WHERE email='$emailTxt'");
    if(mysqli_num_rows($kmde)<=0){
        if(mysqli_query($con, "INSERT INTO account (email, pass, stat) VALUES ('$emailTxt', '$passTxt', 'approved')")){
            $stmts=mysqli_query($con, "SELECT aid FROM account WHERE email='$emailTxt'");
            $stmte=mysqli_fetch_array($stmts);
            $smter=$stmte['aid'];
            if(mysqli_query($con, "INSERT INTO actdetails (aid, fname, lname, gender, ads1, ads2, pno, pincode, idnum, utype, dest) VALUES ('$smter', '$fnameTxt', '$lnameTxt', '$genderTxt', '$ads1Txt', '$ads2Txt', '$pnoTxt', '$pincodeTxt', '$idnumTxt', 'Branch Manager', 'Company Login')")){
                $flag=6;
                if(mysqli_query($con,"DELETE FROM shop where aid='$smter'")){
                    if(isset($_POST['employList'])){                
                        foreach ($_POST['employList'] as $empNamer){
                            if(mysqli_query($con, "INSERT INTO shop (aid, eid) VALUES ('$smter', '$empNamer')"))
                                $flag=6;             
                        }                        
                    }                
                }
            }else
                $flag=5;
        }else
            $flag=5;
    }else{
        $flag=7;
    }
}
if(isset($_POST['updateBtn'])){    
    $aidTxt=$_POST['aidTxt'];
    $emailTxt=$_POST['emailTxt'];
    $passTxt=$_POST['passTxt'];
    $fnameTxt=$_POST['fnameTxt'];
    $lnameTxt=$_POST['lnameTxt'];
    $genderTxt  =$_POST['genderTxt'];
    $ads1Txt=$_POST['ads1Txt'];
    $ads2Txt=$_POST['ads2Txt'];
    $pnoTxt=$_POST['pnoTxt'];
    $pincodeTxt=$_POST['pincodeTxt'];
    $idnumTxt=$_POST['idnumTxt'];
    if(mysqli_query($con, "UPDATE account SET  email = '$emailTxt', pass = '$passTxt' WHERE aid = '$aidTxt'")){        
        if(mysqli_query($con, "UPDATE actdetails SET fname = '$fnameTxt', lname = '$lnameTxt', gender = '$genderTxt', ads1 = '$ads1Txt', ads2 = '$ads2Txt', pno = '$pnoTxt', pincode = '$pincodeTxt', idnum = '$idnumTxt' WHERE aid = '$aidTxt' ")){
            $flag=4;
            if(mysqli_query($con,"DELETE FROM shop where aid='$aidTxt'")){
                if(isset($_POST['employList'])){                
                    foreach ($_POST['employList'] as $empNamer){
                        if(mysqli_query($con, "INSERT INTO shop (aid, eid) VALUES ('$aidTxt', '$empNamer')"))
                            $flag=4;             
                    }                        
                }                
            }
        }else
            $flag=3;
    }else
        $flag=3;    
}if(isset($_POST['expBtn'])){
    $expenseTxt=$_POST['expenseTxt'];
    $detailsTxt=$_POST['detailsTxt'];
    $dates=date("Y-m-d");
    if(mysqli_query($con, "INSERT INTO shopexp (aid, expense, details, dates) VALUES ('$aid', '$expenseTxt', '$detailsTxt', '$dates')"))
        $flag=8;
    else
        $flag=9;
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

    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/forms/select/select2.min.css">
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
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/app-user.css">

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
                        <form style="display: none;" id="lightForm" action="../dark/shop_details.php" method="POST">
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
                                    toastr.error("Message sended failed..", "Oops !");
                                }else if(x==2){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Message sended successfully..", "Well Done !");
                                }else if(x==3){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error("Updation failed..", "Oops !");
                                }else if(x==4){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Updation successfully..", "Well Done !");
                                }else if(x==5){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error("Account Created failed..", "Oops !");
                                }else if(x==6){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Account Created successfully..", "Well Done !");
                                }else if(x==7){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error("'<?php echo $emailTxt; ?>' is already exist..", "Oops !");
                                }else if(x==8){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.success("Expense added successfully..", "Well done !");
                                }else if(x==9){                                    
                                    toastr.options = {
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right"
                                    };
                                    toastr.error("Expense added failed..", "Oops !");
                                }
                                var y='<?php echo $row['gender']; ?>';
                                if(y=="Female"){
                                    document.getElementById("avatarImg").src="../../img/female_avatar.png";
                                    document.getElementById("avatarPic").src="../../img/female_avatar.png";                                    
                                }
                                else if(y=="Male"){
                                    document.getElementById("avatarPic").src="../../img/male_avatar.png";
                                    document.getElementById("avatarImg").src="../../img/male_avatar.png";
                                }
                                else{
                                    document.getElementById("avatarPic").src="../../img/female_avatar.png";
                                    document.getElementById("avatarImg").src="../../img/male_avatar.png";
                                } 
                                navBar();                                                           
                            }
                            function navBar() {
                                if('<?php echo $row['utype']; ?>'=="Management"){
                                    document.getElementById("aCs").style.display = "none";
                                    document.getElementById("mangerView").style.display = "block";
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
                                    document.getElementById("branchView").style.display = "block";                                    
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
                <li class="active nav-item"><a href="javascript:shopdt()" style="display: none;" id="sDt"><i class="feather icon-briefcase"></i><span class="menu-title" >Shop Details</span></a>
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
                        <h2 class="content-header-title float-left mb-0">Branch</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Shop details</li>
                                </ol>
                            </div>
                         </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrum-right">                        
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
            <div class="content-body" style="display: none;" id="mangerView">
                <section>
                    <div class="row">
                    <div class="col-lg-12 col-12">               
                    <fieldset class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control round" id="filter" value="<?php echo $serachbtn; ?>" placeholder="Search by Email..">
                            <div class="form-control-position">
                                <i class="feather icon-search"></i>
                            </div>
                    </fieldset>
                    </div>
                    <div class="col-lg-12 col-12">
                        <div class="float-right">
                            <button type="sumbit" data-toggle="modal" data-target="#editBox" id="newBranch" class="btn btn-outline-success round">Add Branch</button>
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
                document.getElementById("newBranch").onclick = function() {
                    var x = "New Branch Creator";
                    document.getElementById("msgBoxname").innerHTML = x;
                    document.getElementById("createBtn").style.display = "block";
                    document.getElementById("updateBtn").style.display = "none";
                    document.getElementById("emailTxt").value = "";
                    document.getElementById("passTxt").value = "";
                    document.getElementById("aidTxt").value = "";
                    document.getElementById("fnameTxt").value = "";
                    document.getElementById("lnameTxt").value = "";
                    document.getElementById("ads1Txt").value = "";
                    document.getElementById("ads2Txt").value = "";
                    document.getElementById("pnoTxt").value = "";
                    document.getElementById("pincodeTxt").value = "";
                    document.getElementById("idnumTxt").value = "";
                    document.getElementById("nullTxt").selected = true;
                    var select = document.getElementById("languageselect2");
                    var length = select.options.length;
                    for (i = length-1; i >= 0; i--) {
                    select.options[i] = null;
                    }
                    <?php 
                    $empLister=mysqli_query($con, "SELECT * FROM actdetails WHERE utype = 'Employee'");
                    if(mysqli_num_rows($empLister)>0){
                        foreach($empLister as $empListing){
                            $cheks=mysqli_query($con, "SELECT * FROM shop WHERE eid = '$empchid'");
                            if(mysqli_num_rows($cheks)<=0){?>
                            var x = document.getElementById("languageselect2");
                            var option = document.createElement("option");
                            option.text = "<?php echo $empListing['fname'] ?> <?php echo $empListing['lname'] ?>";
                            option.value = "<?php echo $empListing['aid'] ?>";
                            x.add(option);                                                                        
                            <?php
                            }
                        }
                    }
                    ?>
                    
                    };
            </script>
            <?php
            if(!isset($_POST['searchName'])){
                if($row['utype']=="Management"){
                    $act = mysqli_query($con, "SELECT * FROM actdetails WHERE utype = 'Branch Manager'");                
                    if(mysqli_num_rows($act)>0){?>
                        <section id="defaultView">
                            <br>                                           
                        <div class="row match-height">
                        <?php
                        foreach($act as $item){
                            $aaid=$item['aid'];
                            $items = mysqli_query($con, "select * from account where aid = '$aaid' AND stat = 'approved'");
                            if(mysqli_num_rows($items)>0){
                                $itemz = mysqli_fetch_array($items);                            
                                ?>                            
                                <div class="col-xl-4 col-md-6 col-sm-12 profile-card-1">
                                    <div class="card">
                                        <div class="card-header mx-auto">
                                            <div class="avatar avatar-xl">
                                                <?php
                                                    if($item['gender']=="Male"){
                                                        ?><img class="img-fluid" src="../../img/male_avatar.png" alt="img placeholder"><?php
                                                    }else{
                                                        ?><img class="img-fluid" src="../../img/female_avatar.png" alt="img placeholder"><?php
                                                    }
                                                ?>                                            
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <h4 class="text-center"><?php echo $item['fname']; ?> <?php echo $item['lname']; ?></h4>
                                                <p class="text-center"><?php echo $itemz['email']; ?></p>
                                                <div class="card-btns d-flex justify-content-between">
                                                    <button data-toggle="modal" data-target="#editBox" id="Fns<?php echo $item['id']; ?>" class="btn btn-outline-danger waves-effect waves-light">Edit</button>
                                                    <button data-toggle="modal" data-target="#msgBox" id="Fn<?php echo $item['id']; ?>" class="btn btn-outline-primary waves-effect waves-light">Message</button>
                                                    <input value='<?php echo $itemz['email']; ?>' id="email<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $itemz['pass']; ?>' id="pass<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $itemz['aid']; ?>' id="aid<?php echo $item['id']; ?>" style="display: none;">                                                    
                                                    <input value='<?php echo $item['fname']; ?> <?php echo $item['lname']; ?>' id="name<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['fname']; ?>' id="fname<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['lname']; ?>' id="lname<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['gender']; ?>' id="gender<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['ads1']; ?>' id="ads1<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['ads2']; ?>' id="ads2<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['pno']; ?>' id="pno<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['pincode']; ?>' id="pincode<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['idnum']; ?>' id="idnum<?php echo $item['id']; ?>" style="display: none;">
                                                    <script type="text/javascript">
                                                        document.getElementById("Fn<?php echo $item['id']; ?>").onclick = function() {
                                                            var x=document.getElementById("email<?php echo $item['id']; ?>").value;
                                                            document.getElementById("toemail").value=x;
                                                        };
                                                        document.getElementById("Fns<?php echo $item['id']; ?>").onclick = function() {
                                                            var x = document.getElementById("name<?php echo $item['id']; ?>").value;
                                                            document.getElementById("msgBoxname").innerHTML = x;
                                                            document.getElementById("emailTxt").value = document.getElementById("email<?php echo $item['id']; ?>").value;
                                                            document.getElementById("createBtn").style.display = "none";
                                                            document.getElementById("updateBtn").style.display = "block";
                                                            document.getElementById("passTxt").value = document.getElementById("pass<?php echo $item['id']; ?>").value;
                                                            document.getElementById("aidTxt").value = document.getElementById("aid<?php echo $item['id']; ?>").value;                                                            
                                                            document.getElementById("fnameTxt").value = document.getElementById("fname<?php echo $item['id']; ?>").value;
                                                            document.getElementById("lnameTxt").value = document.getElementById("lname<?php echo $item['id']; ?>").value;
                                                            document.getElementById("ads1Txt").value = document.getElementById("ads1<?php echo $item['id']; ?>").value;
                                                            document.getElementById("ads2Txt").value = document.getElementById("ads2<?php echo $item['id']; ?>").value;
                                                            document.getElementById("pnoTxt").value = document.getElementById("pno<?php echo $item['id']; ?>").value;
                                                            document.getElementById("pincodeTxt").value = document.getElementById("pincode<?php echo $item['id']; ?>").value;
                                                            document.getElementById("idnumTxt").value = document.getElementById("idnum<?php echo $item['id']; ?>").value;                                                            
                                                            var q = document.getElementById("gender<?php echo $item['id']; ?>").value;
                                                            if(q=="Male"){
                                                                document.getElementById("maleTxt").selected = true;
                                                            }else if(q=="Female"){
                                                                document.getElementById("femaleTxt").selected = true;
                                                            }else if(q=="Other"){
                                                                document.getElementById("otherTxt").selected = true;
                                                            }
                                                            var select = document.getElementById("languageselect2");
                                                            var length = select.options.length;
                                                            for (i = length-1; i >= 0; i--) {
                                                            select.options[i] = null;
                                                            }
                                                            <?php
                                                            $empLister=mysqli_query($con, "SELECT * FROM actdetails WHERE utype = 'Employee'");
                                                            if(mysqli_num_rows($empLister)>0){
                                                                foreach($empLister as $empListing){
                                                                    $empchid=$empListing['aid'];
                                                                    $empListCheck=mysqli_query($con, "SELECT * FROM shop WHERE aid='$aaid' AND eid = '$empchid'");
                                                                    if(mysqli_num_rows($empListCheck)>0){?>
                                                                        var x = document.getElementById("languageselect2");
                                                                        var option = document.createElement("option");
                                                                        option.text = "<?php echo $empListing['fname'] ?> <?php echo $empListing['lname'] ?>";
                                                                        option.value = "<?php echo $empListing['aid'] ?>";
                                                                        option.selected = true;
                                                                        x.add(option);
                                                                        <?php
                                                                    }else{
                                                                        $cheks=mysqli_query($con, "SELECT * FROM shop WHERE eid = '$empchid'");
                                                                        if(mysqli_num_rows($cheks)<=0){?>
                                                                        var x = document.getElementById("languageselect2");
                                                                        var option = document.createElement("option");
                                                                        option.text = "<?php echo $empListing['fname'] ?> <?php echo $empListing['lname'] ?>";
                                                                        option.value = "<?php echo $empListing['aid'] ?>";
                                                                        x.add(option);                                                                        
                                                                        <?php
                                                                        }
                                                                    }
                                                                }
                                                            }?>
                                                        };
                                                    </script>
                                                </div>
                                                <hr class="my-1">
                                                <div class="d-flex justify-content-between">
                                                    <div class="float-left">
                                                    <label><label class="text-muted">Address Line 1:</label>  <?php echo $item['ads1']; ?></label><br>
                                                    <label><label class="text-muted">Address Line 1:</label>  <?php echo $item['ads2']; ?></label><br>
                                                    <label><label class="text-muted">Phone Number:</label>  <?php echo $item['pno']; ?></label><br>
                                                    </div>
                                                </div>
                                                <hr class="my-1">
                                                <section id="accordion-with-margin">
                                                    <div class="card collapse-icon accordion-icon-rotate">
                                                        <div class="accordion" id="accordionExample">
                                                            <div class="collapse-margin">
                                                                <div class="card-header" id="headingOne" data-toggle="collapse" role="button" data-target="#collapse<?php echo $item['id']; ?>" aria-expanded="false" aria-controls="collapseOne">
                                                                    <span class="lead collapse-title">
                                                                        <h6 class="text-bold-200 light">Employees</h6>
                                                                    </span>
                                                                </div>
                                                            <div id="collapse<?php echo $item['id']; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                                <div class="card-body"><?php
                                                                $employee = mysqli_query($con, "select * from shop where aid = '$aaid'");
                                                                $empcounter=0;
                                                                if(mysqli_num_rows($employee)>0){
                                                                    foreach($employee as $employed){
                                                                        $empid=$employed['eid'];
                                                                        $employee = mysqli_query($con, "select * from actdetails where aid = '$empid'");
                                                                        $employer = mysqli_fetch_array($employee);
                                                                        $empcounter=1;?>
                                                                        <label class="text-bold-100"><?php echo $employer['fname'];?> <?php echo $employer['lname'];?><label class="text-muted">(ID: <?php echo $employer['idnum'];?>)</label></label><br>
                                                                        <?php
                                                                    }
                                                                }
                                                                if($empcounter==0){?>
                                                                    <label class="text-bold-100">Employees are not added.</label><br>
                                                                <?php
                                                                }
                                                                ?>
                                                                </div>
                                                            </div>
                                                        </div>                                        
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }                       
                        }
                    }?>
                    </div>            
                    </section>
                    <?php                                
                }                
            }                        
            ?>
            <?php                       
            if(isset($_POST['searchName'])){
                ?>
                <section id="searchView">
                <h4 class="warning">Search results</h4>
                <hr>
                <div class="row match-height">
                <?php
                $memil=$_POST['searchName'];
                $esr = mysqli_query($con, "SELECT aid FROM account WHERE email LIKE '%{$memil}%'");
                if(mysqli_num_rows($esr)>0){
                    $mdrk=0;
                    foreach($esr as $eeid){
                        $eeeid=$eeid['aid'];
                        if($row['utype']=="Management"){
                            $act = mysqli_query($con, "SELECT * FROM actdetails WHERE utype = 'Branch Manager' AND aid = '$eeeid'");                
                            if(mysqli_num_rows($act)>0){
                                foreach($act as $item){
                                    $aaid=$item['aid'];
                                    $items = mysqli_query($con, "select * from account where aid = '$aaid' AND stat = 'approved'");
                                    if(mysqli_num_rows($items)>0){
                                        $mdrk=1;
                                        $itemz = mysqli_fetch_array($items);                            
                                        ?>                            
                                        <div class="col-xl-4 col-md-6 col-sm-12 profile-card-1">
                                            <div class="card">
                                                <div class="card-header mx-auto">
                                                    <div class="avatar avatar-xl">
                                                        <?php
                                                            if($item['gender']=="Male"){
                                                                ?><img class="img-fluid" src="../../img/male_avatar.png" alt="img placeholder"><?php
                                                            }else{
                                                                ?><img class="img-fluid" src="../../img/female_avatar.png" alt="img placeholder"><?php
                                                            }
                                                        ?>                                            
                                                    </div>
                                                </div>
                                                <div class="card-content">
                                                    <div class="card-body">
                                                        <h4 class="text-center"><?php echo $item['fname']; ?> <?php echo $item['lname']; ?></h4>
                                                        <p class="text-center"><?php echo $itemz['email']; ?></p>
                                                        <div class="card-btns d-flex justify-content-between">
                                                    <button data-toggle="modal" data-target="#editBox" id="Fns<?php echo $item['id']; ?>" class="btn btn-outline-danger waves-effect waves-light">Edit</button>
                                                    <button data-toggle="modal" data-target="#msgBox" id="Fn<?php echo $item['id']; ?>" class="btn btn-outline-primary waves-effect waves-light">Message</button>
                                                    <input value='<?php echo $itemz['email']; ?>' id="email<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $itemz['pass']; ?>' id="pass<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $itemz['aid']; ?>' id="aid<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['fname']; ?> <?php echo $item['lname']; ?>' id="name<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['fname']; ?>' id="fname<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['lname']; ?>' id="lname<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['gender']; ?>' id="gender<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['ads1']; ?>' id="ads1<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['ads2']; ?>' id="ads2<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['pno']; ?>' id="pno<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['pincode']; ?>' id="pincode<?php echo $item['id']; ?>" style="display: none;">
                                                    <input value='<?php echo $item['idnum']; ?>' id="idnum<?php echo $item['id']; ?>" style="display: none;">
                                                    <script type="text/javascript">
                                                        document.getElementById("Fn<?php echo $item['id']; ?>").onclick = function() {
                                                            var x=document.getElementById("email<?php echo $item['id']; ?>").value;
                                                            document.getElementById("toemail").value=x;
                                                        };
                                                        document.getElementById("Fns<?php echo $item['id']; ?>").onclick = function() {
                                                            var x = document.getElementById("name<?php echo $item['id']; ?>").value;
                                                            document.getElementById("msgBoxname").innerHTML = x;
                                                            document.getElementById("emailTxt").value = document.getElementById("email<?php echo $item['id']; ?>").value;
                                                            document.getElementById("passTxt").value = document.getElementById("pass<?php echo $item['id']; ?>").value;
                                                            document.getElementById("aidTxt").value = document.getElementById("aid<?php echo $item['id']; ?>").value;
                                                            document.getElementById("fnameTxt").value = document.getElementById("fname<?php echo $item['id']; ?>").value;
                                                            document.getElementById("lnameTxt").value = document.getElementById("lname<?php echo $item['id']; ?>").value;
                                                            document.getElementById("ads1Txt").value = document.getElementById("ads1<?php echo $item['id']; ?>").value;
                                                            document.getElementById("ads2Txt").value = document.getElementById("ads2<?php echo $item['id']; ?>").value;
                                                            document.getElementById("pnoTxt").value = document.getElementById("pno<?php echo $item['id']; ?>").value;
                                                            document.getElementById("pincodeTxt").value = document.getElementById("pincode<?php echo $item['id']; ?>").value;
                                                            document.getElementById("idnumTxt").value = document.getElementById("idnum<?php echo $item['id']; ?>").value;                                                            
                                                            var q = document.getElementById("gender<?php echo $item['id']; ?>").value;
                                                            if(q=="Male"){
                                                                document.getElementById("maleTxt").selected = true;
                                                            }else if(q=="Female"){
                                                                document.getElementById("femaleTxt").selected = true;
                                                            }else if(q=="Other"){
                                                                document.getElementById("otherTxt").selected = true;
                                                            }
                                                            var select = document.getElementById("languageselect2");
                                                            var length = select.options.length;
                                                            for (i = length-1; i >= 0; i--) {
                                                            select.options[i] = null;
                                                            }
                                                            <?php
                                                            $empLister=mysqli_query($con, "SELECT * FROM actdetails WHERE utype = 'Employee'");
                                                            if(mysqli_num_rows($empLister)>0){
                                                                foreach($empLister as $empListing){
                                                                    $empchid=$empListing['aid'];
                                                                    $empListCheck=mysqli_query($con, "SELECT * FROM shop WHERE aid='$aaid' AND eid = '$empchid'");                                                                    
                                                                        if(mysqli_num_rows($empListCheck)>0){?>
                                                                            var x = document.getElementById("languageselect2");
                                                                            var option = document.createElement("option");
                                                                            option.text = "<?php echo $empListing['fname'] ?> <?php echo $empListing['lname'] ?>";
                                                                            option.value = "<?php echo $empListing['aid'] ?>";
                                                                            option.selected = true;
                                                                            x.add(option);
                                                                            <?php
                                                                        }else{
                                                                            $cheks=mysqli_query($con, "SELECT * FROM shop WHERE eid = '$empchid'");
                                                                            if(mysqli_num_rows($cheks)<=0){?>
                                                                            var x = document.getElementById("languageselect2");
                                                                            var option = document.createElement("option");
                                                                            option.text = "<?php echo $empListing['fname'] ?> <?php echo $empListing['lname'] ?>";
                                                                            option.value = "<?php echo $empListing['aid'] ?>";
                                                                            x.add(option);                                                                        
                                                                            <?php
                                                                            }
                                                                        }                                                                    
                                                                }
                                                            }?>
                                                        };
                                                    </script>
                                                </div>
                                                        <hr class="my-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="float-left">
                                                            <label><label class="text-muted">Desgination:</label>  <?php echo $item['dest']; ?></label><br>
                                                            <label><label class="text-muted">User Type:</label>  <?php echo $item['utype']; ?></label><br>
                                                            <label><label class="text-muted">Status:</label>  <?php echo $itemz['stat']; ?></label><br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }                       
                                }
                            }                               
                        }
                    }if($mdrk==0){?>
                        <div >
                            <h3 class="text-muted pl-1" center>No results found...</h3>
                        </div>
                        <?php
                    }
                }
                else{?>
                    <div >
                        <h3 class="text-muted pl-1" center>No results found...</h3>
                    </div>
                    <?php
                }?>
                </div> 
                </section>
                <?php 
            }
            ?>
            </div>
            <div class="content-body" style="display: none;" id="branchView">
                <section>
                    <div class="row my-2">                    
                    <div class="col-lg-12 col-12">
                        <div class="float-right">
                            <button type="sumbit" data-toggle="modal" data-target="#expenseBox" id="expenseBtn" class="btn btn-outline-danger round">Add Expense</button>
                        </div>
                    </div>
                </section>    
                <section class="page-users-view">
                    <div class="row">
                        <!-- account start -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Account</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="users-view-image">
                                            <img src="" id="avatarPic" class="users-avatar-shadow w-100 rounded mb-2 pr-2 ml-1" alt="avatar">
                                        </div>
                                        <div class="col-12 col-sm-9 col-md-6 col-lg-5">
                                            <br>
                                            <table>
                                                <tr>
                                                    <td class="font-weight-bold">Username</td>
                                                    <td><?php echo $eml['email']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Name</td>
                                                    <td><?php echo $row['fname']; ?> <?php echo $row['lname']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Employee ID</td>
                                                    <td><?php echo $row['idnum']; ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-5">
                                            <br>
                                            <table class="ml-0 ml-sm-0 ml-lg-0">
                                                <tr>
                                                    <td class="font-weight-bold">Designation</td>
                                                    <td><?php echo $row['dest']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Date of Hire</td>
                                                    <td><?php echo $row['doh']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">User Type</td>
                                                    <td><?php echo $row['utype']; ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>    
                <section class="page-users-view">
                    <div class="row">                        
                        <!-- information start -->
                        <div class="col-md-6 col-12 ">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title mb-2">Information</div>
                                </div>
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <td class="font-weight-bold">First Name</td>
                                            <td><?php echo $row['fname']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Last Name</td>
                                            <td><?php echo $row['lname']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Birth Date </td>
                                            <td><?php echo $row['dob']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Gender</td>
                                            <td><?php echo $row['gender']; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Mobile</td>
                                            <td>+91 <?php echo $row['pno']; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- information start -->
                        <!-- social links end -->
                        <div class="col-md-6 col-12 ">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title mb-2">Location</div>
                                </div>
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <td class="font-weight-bold">Address 1</td>
                                            <td><?php echo $row['ads1']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Address 2</td>
                                            <td><?php echo $row['ads2']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Country</td>
                                            <td><?php echo $row['country']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">State</td>
                                            <td><?php echo $row['states']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Pincode</td>
                                            <td><?php echo $row['pincode']; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- social links end -->
                    </div>
                </section>
                <?php
                    $act = mysqli_query($con, "SELECT * FROM actdetails WHERE utype = 'Employee'");                
                    if(mysqli_num_rows($act)>0){?>
                        <section id="defaultView">
                        <h4 class="light">Empolyees</h4>                                           
                        <hr>
                        <div class="row match-height">
                        <?php
                        $mdrk=0;
                        foreach($act as $item){
                            $aaid=$item['aid'];
                            $eploy=mysqli_query($con, "SELECT * FROM shop WHERE eid = '$aaid' AND aid='$aid'");
                            if(mysqli_num_rows($eploy)>0){
                            $mdrk=1;
                            $items = mysqli_query($con, "select * from account where aid = '$aaid' AND stat = 'approved'");
                            if(mysqli_num_rows($items)>0){
                                $itemz = mysqli_fetch_array($items);                            
                                ?>                            
                                <div class="col-xl-4 col-md-6 col-sm-12 profile-card-1">
                                    <div class="card">
                                        <div class="card-header mx-auto">
                                            <div class="avatar avatar-xl">
                                                <?php
                                                    if($item['gender']=="Male"){
                                                        ?><img class="img-fluid" src="../../img/male_avatar.png" alt="img placeholder"><?php
                                                    }else{
                                                        ?><img class="img-fluid" src="../../img/female_avatar.png" alt="img placeholder"><?php
                                                    }
                                                ?>                                            
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">
                                                <h4 class="text-center"><?php echo $item['fname']; ?> <?php echo $item['lname']; ?></h4>
                                                <p class="text-center"><?php echo $itemz['email']; ?></p>
                                                <hr class="my-1">
                                                <div class="d-flex justify-content-between">
                                                    <div class="float-left">
                                                    <label><label class="text-muted">Desgination:</label>  <?php echo $item['dest']; ?></label><br>
                                                    <label><label class="text-muted">User Type:</label>  <?php echo $item['utype']; ?></label><br>
                                                    <label><label class="text-muted">Status:</label>  <?php echo $itemz['stat']; ?></label><br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            }                       
                        }if($mdrk==0){?>
                            <div >
                                <h3 class="text-muted pl-1" center>No empolyees found...</h3>
                            </div>
                        </div>
                        </section>
                        <?php
                        }
                    }
                ?>
            </div>
        </div>
        </div>
        </div>
    </div>
    <script type="text/javascript">
    document.getElementById("expenseBtn").onclick = function(){
        document.getElementById("expenseTxt").value ="";
        document.getElementById("detailsTxt").value ="";
    };
    </script>
<div class="modal fade text-left" id="editBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="msgBoxname"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
            <div class="modal-body">            
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <div class="controls">
                                <label>Username</label>
                                <input type="email" class="form-control"  placeholder="Username" name="emailTxt" id="emailTxt" required data-validation-required-message="This username field is required">
                            </div>
                        </div>                        
                        <div class="form-group">
                            <div class="controls">
                                <label>Frist Name</label>
                                <input type="text" class="form-control" placeholder="First Name" name="fnameTxt" id="fnameTxt" required data-validation-required-message="This frist name field is required">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls">
                                <label id="statName">Gender</label>
                                <select class="form-control" id="genderTxt" name="genderTxt" required data-validation-required-message="This gender field is required">                                
                                <option id="nullTxt" value="" selected>Choose</option>
                                <option id="maleTxt" value="Male">Male</option>
                                <option id="femaleTxt" value="Female">Female</option>
                                <option id="otherTxt" value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls">
                                <label>Address Line 1</label>
                                <input type="text" class="form-control" placeholder="Address line one.." name="ads1Txt" id="ads1Txt" required data-validation-required-message="This address line 1 field is required">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls">
                                <label>Pincode</label>
                                <input type="text" class="form-control" placeholder="Pincode" name="pincodeTxt" id="pincodeTxt" required data-validation-required-message="This pincode field is required">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">                        
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" class="form-control" placeholder="Password" id="passTxt" name="passTxt" required data-validation-required-message="This password field is required">
                        </div>
                        <div class="form-group">
                            <label>Last name</label>
                            <input type="text" class="form-control" id="lnameTxt" name="lnameTxt" required placeholder="Last Name" data-validation-required-message="This last name field is required">
                        </div>
                        <div class="form-group">
                            <div class="controls">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" placeholder="Phone Number" name="pnoTxt" id="pnoTxt" required data-validation-required-message="This phone number field is required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address Line 2</label>
                            <input type="text" class="form-control" placeholder="Address line two.." name="ads2Txt" id="ads2Txt" required data-validation-required-message="This address line 2 field is required">
                        </div>
                        <div class="form-group">
                            <label>ID Number:</label>
                            <input type="text" class="form-control" placeholder="ID Number.." name="idnumTxt" id="idnumTxt" required data-validation-required-message="This id number field is required">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">                            
                            <select class="form-control select2-hidden-accessible" name="employList[]" id="languageselect2" multiple data-select2-id="languageselect2" tabindex="-1" aria-hidden="true" name="empList[]">
                                
                            </select>                            
                        </div>
                    </div>
                </div>
            </div>
        <div class="modal-footer">
            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
            <input id="aidTxt" name="aidTxt" style="display: none;">
            <button type="sumbit" name="createBtn" style="display: none;" class="btn btn-success" id="createBtn">Submit</button>
            <button type="sumbit" name="updateBtn" style="display: none;" class="btn btn-success" id="updateBtn">Submit</button>
        </div>        
            </form>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="msgBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">SEND Message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
            <div class="modal-body">
                <label>To</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    </div>
                <input type="text" class="form-control" id="toemail" name="toemail">                                                 
                </div> 
                <label>Subject</label>
            <div class="form-group">
                <input type="text" class="form-control" name="subjectTxt" id="subjectTxt" placeholder="Subject" value="" required>
            </div>
            <label>Message</label>
            <div class="form-group">
                <fieldset class="form-group">
                    <textarea class="form-control" name="messageTxt" id="messageTxt" rows="5" placeholder="Message" value="" required></textarea>
                </fieldset>
            </div>
        </div>
        <div class="modal-footer">
        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
        <button type="sumbit" name="msgBtn" class="btn btn-success">Send</button>
        </div>        
            </form>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="expenseBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Add Expense</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
            <div class="modal-body">                 
                <label>Expense</label>
            <div class="form-group">
                <input type="number" class="form-control" name="expenseTxt" id="expenseTxt" placeholder="Expense" value="" required>
            </div>
            <label>Reason</label>
            <div class="form-group">
                <fieldset class="form-group">
                    <textarea class="form-control" name="detailsTxt" id="detailsTxt" rows="5" placeholder="Reason" value="" required></textarea>
                </fieldset>
            </div>
        </div>
        <div class="modal-footer">
        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
        <button type="sumbit" name="expBtn" class="btn btn-danger">Add</button>
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
    <script src="../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="../../app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <script src="../../app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="../../app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="../../app-assets/vendors/js/extensions/dropzone.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page extensions JS-->
    <script src="../../app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- END: Page extensions JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <script src="../../app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../app-assets/js/scripts/pages/account-setting.js"></script>
    <script src="../../app-assets/js/scripts/pages/app-ecommerce-shop.js"></script>    
    <!-- END: Page JS-->



</body>
<!-- END: Body-->

</html>