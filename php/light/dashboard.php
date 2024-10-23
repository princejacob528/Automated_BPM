<?php
$aid = $_POST['userKey'];
include("../connection.php");
date_default_timezone_set('Asia/Calcutta');
$flag = 0;
$checker = 0;
$today = date("Y-m-d");
if (isset($_POST['checks'])) {
    $flag = 7;
    $attstr = $_POST['attstr'];
    $attkey = mysqli_query($con, "SELECT * FROM attkey WHERE attstr='$attstr'");
    if (mysqli_num_rows($attkey) > 0) {
        $checker = mysqli_fetch_array($attkey);
        $checker = $checker['attname'];
        $gtk = mysqli_query($con, "SELECT * FROM attendance WHERE aid='$aid' AND attdate='$today'");
        if (mysqli_num_rows($gtk) > 0) {
            $ttd = mysqli_fetch_array($gtk);
            if ($ttd['checkin'] == "00:00:00" && $ttd['attstat'] == "Hours leave") {
                $atid = $ttd['atid'];
                $cin = date("H:i:s");
                if (mysqli_query($con, "UPDATE attendance SET checkin='$cin' WHERE atid='$atid'"))
                    $flag = 1;
            } else if ($ttd['checkin'] != "00:00:00" && $ttd['attstat'] == "Hours leave") {
                $out = $ttd['checkout'];
                $in = $ttd['checkin'];
                $atid = $ttd['atid'];
                if ($out == "00:00:00" && $in != "00:00:00") {
                    $checkin = strtotime($in);
                    $checkout = strtotime(date("H:i:s"));
                    $diff = $checkout - $checkin;
                    $h = intval($diff / 3600);
                    $diff = $diff - ($h * 3600);
                    $m = intval($diff / 60);
                    $timer = floatval($h . "." . $m);
                    $timer = $timer + intval($ttd['ahours']);
                    $cout = date("H:i:s");
                    if ($timer > "8" && $timer < "9") {
                        if (mysqli_query($con, "UPDATE attendance SET checkout='$cout', ahours='8' WHERE atid='$atid'"))
                            $flag = 2;
                    } else {
                        if ($timer < "8") {
                            if (mysqli_query($con, "UPDATE attendance SET checkout='$cout', ahours='$timer' WHERE atid='$atid'"))
                                $flag = 2;
                        } else if ($timer > "9") {
                            $timer = $timer - 8;
                            if (mysqli_query($con, "UPDATE attendance SET checkout='$cout', ahours='8', extraduty='$timer' WHERE atid='$atid'"))
                                $flag = 3;
                        }
                    }
                }
            } else if ($ttd['checkin'] != "00:00:00" && $ttd['attstat'] == "Present") {
                $out = $ttd['checkout'];
                $in = $ttd['checkin'];
                $atid = $ttd['atid'];
                if ($out == "00:00:00" && $in != "00:00:00") {
                    $checkin = strtotime($in);
                    $checkout = strtotime(date("H:i:s"));
                    $diff = $checkout - $checkin;
                    $h = intval($diff / 3600);
                    $diff = $diff - ($h * 3600);
                    $m = intval($diff / 60);
                    $timer = floatval($h . "." . $m);
                    $cout = date("H:i:s");
                    if ($timer > "7.30" && $timer < "8.30") {
                        if (mysqli_query($con, "UPDATE attendance SET checkout='$cout', ahours='8' WHERE atid='$atid'"))
                            $flag = 2;
                    } else {
                        if ($timer < "7.30") {
                            if (mysqli_query($con, "UPDATE attendance SET checkout='$cout', ahours='$timer' WHERE atid='$atid'"))
                                $flag = 2;
                        } else if ($timer > "8.30") {
                            $timer = $timer - 8;
                            if (mysqli_query($con, "UPDATE attendance SET checkout='$cout', ahours='8', extraduty='$timer' WHERE atid='$atid'"))
                                $flag = 3;
                        }
                    }
                }
            }
        } else {
            $cin = date("H:i:s");
            if (mysqli_query($con, "INSERT INTO attendance (aid, attdate, checkin, checkout, ahours, extraduty, attstat, astatus) VALUES ('$aid', '$today', '$cin', '00:00:00', '0', '0', 'Present', '0')"))
                $flag = 1;
        }
    } else {
        $checker = 0;
    }
}
if (isset($_POST['feedbackBtn'])) {
    $flag = 6;
    $emailid = $_POST['emailid'];
    $msgTxt = $_POST['msgTxt'];
    $msgTit = $_POST['msgTit'];
    if (mysqli_query($con, "INSERT INTO notificat (aid, nsub, ntitle, nstatus, ntype, nsender) VALUES ('$emailid', '$msgTxt', '$msgTit', '0', 'Feedback','$aid')")) {
        $flag = 5;
    }
}
$query = "select fname,lname,dest,gender,idnum,doh,utype from actdetails where aid = '$aid'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
if ($row['utype'] == "Management") {
    $sertotal = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service"));
    $serinp = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service WHERE stat!='5' AND stat!='4'"));
    $sercom = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service WHERE stat='5' OR stat='4'"));
    if (intval($serinp) != 0)
        $todopercent = round((intval($serinp) * 100) / intval($sertotal));
    else
        $todopercent = 0;
    if ($todopercent == 0) {
        $todopercent = 100;
    } else {
        $todopercent = 100 - $todopercent;
    }
    $stocktotal = mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts"));
    $stockinp = mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts WHERE stat!='0'"));
    $stockcom = mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts WHERE stat='0'"));
    if (intval($stockcom) != 0)
        $stockpercent = round((intval($stockcom) * 100) / intval($stocktotal));
    else
        $stockpercent = 0;
    if ($stockpercent == 0) {
        $stockpercent = 100;
    } else {
        $stockpercent = 100 - $stockpercent;
    }
} else if ($row['utype'] == "Branch Manager") {
    $sertotal = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service WHERE brid='$aid'"));
    $serinp = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service WHERE stat!='5' AND stat!='4' AND brid='$aid'"));
    $sercom = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service WHERE (stat='5' OR stat='4') AND brid='$aid'"));
    if ($sertotal != 0) {
        $todopercent = round((intval($serinp) * 100) / intval($sertotal));
    } else {
        $todopercent = 0;
    }
    if ($todopercent == 0) {
        $todopercent = 100;
    } else {
        $todopercent = 100 - $todopercent;
    }
    $stocktotal = 0;
    $stockinp = 0;
    $stockcom = 0;
    $purchase = mysqli_query($con, "SELECT * FROM purchase WHERE aid='$aid'");
    if (mysqli_num_rows($purchase) > 0) {
        foreach ($purchase as $purchases) {
            $pcid = $purchases['pcid'];
            $stocktotal = $stocktotal + mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts WHERE refp='$pcid'"));
            $stockcom = $stockcom + mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts WHERE refp='$pcid' AND stat!='0'"));
            $stockinp = $stockinp + mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts WHERE refp='$pcid' AND stat='0'"));
        }
    }
    if ($stocktotal != 0) {
        $stockpercent = round((intval($stockcom) * 100) / intval($stocktotal));
    } else {
        $stockpercent = 0;
    }
    if ($stockpercent == 0) {
        $stockpercent = 100;
    } else {
        $stockpercent = 100 - $stockpercent;
    }
} else {
    $sertotal = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service WHERE srcr='$aid'"));
    $serinp = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service WHERE stat!='5' AND stat!='4' AND srcr='$aid'"));
    $sercom = mysqli_num_rows(mysqli_query($con, "SELECT * FROM service WHERE (stat='5' OR stat='4') AND srcr='$aid'"));
    if ($sertotal != 0) {
        $todopercent = round((intval($serinp) * 100) / intval($sertotal));
    } else {
        $todopercent = 0;
    }

    if ($todopercent == 0) {
        $todopercent = 100;
    } else {
        $todopercent = 100 - $todopercent;
    }
    $stocktotal = 0;
    $stockinp = 0;
    $stockcom = 0;
    $shop = mysqli_query($con, "SELECT * FROM shop WHERE eid='$aid'");
    if (mysqli_num_rows($shop) > 0) {
        $shops = mysqli_fetch_array($shop);
        $brid = $shops['aid'];
        $purchase = mysqli_query($con, "SELECT * FROM purchase WHERE aid='$brid'");
        if (mysqli_num_rows($purchase) > 0) {
            foreach ($purchase as $purchases) {
                $pcid = $purchases['pcid'];
                $stocktotal = $stocktotal + mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts WHERE refp='$pcid'"));
                $stockcom = $stockcom + mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts WHERE refp='$pcid' AND stat!='0'"));
                $stockinp = $stockinp + mysqli_num_rows(mysqli_query($con, "SELECT * FROM productdts WHERE refp='$pcid' AND stat='0'"));
            }
        }
    }
    if ($stocktotal != 0) {
        $stockpercent = round((intval($stockcom) * 100) / intval($stocktotal));
    } else {
        $stockpercent = 0;
    }

    if ($stockpercent == 0) {
        $stockpercent = 100;
    } else {
        $stockpercent = 100 - $stockpercent;
    }
}
$que = "select email from account where aid = '$aid'";
$ret = mysqli_query($con, $que);
$eml = mysqli_fetch_array($ret);
$qry = "select nid from notificat where aid = '$aid' AND nstatus = '0'";
$res = mysqli_query($con, $qry);
if (mysqli_num_rows($res) > 0)
    $count = mysqli_num_rows($res);
else
    $count = 0;
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
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/tether-theme-arrows.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/tether.min.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/vendors/css/extensions/shepherd-theme-default.css">
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
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/dashboard-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/dashboard-analytics.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/card-analytics.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/tour/tour.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/pages/app-user.css">
    <link rel="stylesheet" type="text/css" href="../../app-assets/css/plugins/extensions/toastr.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <!-- END: Custom CSS-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="javascript:notiFy()"><i class="ficon feather icon-bell"></i><span class="badge badge-pill badge-primary badge-up"><?php echo $count; ?></span></a>
                            <form style="display: none;" id="notiFy" action="notification.php" method="POST">
                                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                            </form>
                            <form style="display: none;" id="lightForm" action="../dark/dashboard.php" method="POST">
                                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                            </form>
                            <script type="text/javascript">
                                function notiFy() {
                                    document.getElementById("notiFy").submit();
                                }

                                function lightForm() {
                                    document.getElementById("lightForm").submit();
                                }

                                function avatarLoad() {
                                    var x = '<?php echo $flag; ?>';
                                    if (x == 1) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success("Check in at " + '<?php echo $checker; ?>' + " successfully..", "Well Done !");
                                    } else if (x == 2) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success('Check out at ' + '<?php echo $checker; ?>' + '  successfully..', "Well Done !");
                                    } else if (x == 3) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success("Check out at " + '<?php echo $checker; ?>' + "  and extra duty marked successfully..", "Well Done !");
                                    } else if (x == 4) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.error('Something went wrong..', "Oops !");
                                    } else if (x == 5) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success('Feedback send successfully..', "Well Done !");
                                    } else if (x == 6) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.error('Something went wrong..', "Oops !");
                                    } else if (x == 7) {
                                        if ('<?php echo $checker; ?>' == 0) {
                                            swal("Oops !", "QR code is expired.", "warning");
                                        }
                                    }
                                    var y = '<?php echo $row['gender']; ?>';
                                    if (y == "Female") {
                                        document.getElementById("avatarImg").src = "../../img/female_avatar.png";
                                    } else if (y == "Male") {
                                        document.getElementById("avatarImg").src = "../../img/male_avatar.png";
                                    } else {
                                        document.getElementById("avatarImg").src = "../../img/male_avatar.png";
                                    }
                                    navBar();
                                }

                                function navBar() {
                                    if ('<?php echo $row['utype']; ?>' == "Management") {
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
                                    } else if ('<?php echo $row['utype']; ?>' == "Branch Manager") {
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
                                    } else if ('<?php echo $row['utype']; ?>' == "Employee") {
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
                                    } else if ('<?php echo $row['utype']; ?>' == "Not authorized") {
                                        window.location.href = "../../html/page-not-authorized.html", '_blank';
                                    }
                                }
                            </script>
                        </li>
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600"><?php echo $row['fname']; ?> <?php echo $row['lname']; ?> </span><span class="user-status"><?php echo $row['dest']; ?></span></div><span><img class="round" src="" id="avatarImg" alt="avatar" height="40" width="40"></span>
                            </a>
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
            function homeBtn() {
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
            function usrEdit() {
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
                        function accessCont() {
                            document.getElementById("accessCont").submit();
                        }
                    </script>
                </li>
                <li class="active nav-item"><a href="javascript:dashBtn()" style="display: none;" id="daS"><i class="feather icon-home"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span></a>
                    <form style="display: none;" id="dashBtn" action="dashboard.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function dashBtn() {
                            document.getElementById("dashBtn").submit();
                        }
                    </script>
                </li>
                <li class=" navigation-header" style="display: none;" id="sHp"><span>SHOP</span>
                </li>
                <li class=" nav-item"><a href="javascript:shopdt()" style="display: none;" id="sDt"><i class="feather icon-briefcase"></i><span class="menu-title">Shop Details</span></a>
                    <form style="display: none;" id="shopdt" action="shop_details.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function shopdt() {
                            document.getElementById("shopdt").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:Proddt()" style="display: none;" id="pDt"><i class="feather icon-shopping-bag"></i><span class="menu-title">Product Details</span></a>
                    <form style="display: none;" id="Proddt" action="product_details.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function Proddt() {
                            document.getElementById("Proddt").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:Empsdt()" style="display: none;" id="eDt"><i class="feather icon-users"></i><span class="menu-title">Employee Details</span></a>
                    <form style="display: none;" id="Empsdt" action="employees_details.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function Empsdt() {
                            document.getElementById("Empsdt").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:Findt()" style="display: none;" id="fDt"><i class="feather icon-inbox"></i><span class="menu-title">Financial Details</span></a>
                    <form style="display: none;" id="Findt" action="financial_details.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function Findt() {
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
                        function sale() {
                            document.getElementById("sale").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:purchase()" style="display: none;" id="pCe"><i class="fa fa-cart-arrow-down"></i><span class="menu-title">Purchase</span></a>
                    <form style="display: none;" id="purchase" action="purchase.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function purchase() {
                            document.getElementById("purchase").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:toDo()" style="display: none;" id="tDo"><i class="feather icon-check-square"></i><span class="menu-title">To Do</span></a>
                    <form style="display: none;" id="toDo" action="to_do.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function toDo() {
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
                        function stockHand() {
                            document.getElementById("stockHand").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:stockReq()" style="display: none;" id="stkR"><i class="fa fa-shopping-basket"></i><span class="menu-title">Stock Request</span></a>
                    <form style="display: none;" id="stockReq" action="stock_request.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function stockReq() {
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
                        function empDt() {
                            document.getElementById("empDt").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:finEdt()" style="display: none;" id="eMpF"><i class="feather icon-inbox"></i><span class="menu-title">Financial Details</span></a>
                    <form style="display: none;" id="finEdt" action="employee_financial_details.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function finEdt() {
                            document.getElementById("finEdt").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:workHis()" style="display: none;" id="eMpW"><i class="fa fa-history"></i><span class="menu-title">Work History</span></a>
                    <form style="display: none;" id="workHis" action="work_history.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function workHis() {
                            document.getElementById("workHis").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:attDt()" style="display: none;" id="eMpA"><i class="feather icon-calendar"></i><span class="menu-title">Attendance Details</span></a>
                    <form style="display: none;" id="attDt" action="attendance_details.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function attDt() {
                            document.getElementById("attDt").submit();
                        }
                    </script>
                </li>
                <li class=" nav-item"><a href="javascript:leaRe()" style="display: none;" id="eMpL"><i class="fas fa-sign-out-alt"></i><span class="menu-title">Leave Request</span></a>
                    <form style="display: none;" id="leaRe" action="leave_request.php" method="POST">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                    </form>
                    <script type="text/javascript">
                        function leaRe() {
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
                        function About() {
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
                            <h2 class="content-header-title float-left mb-0">Dashboard</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form style="display: none;" id="qrcode" action="../../html/qrcode/qrcode.php" method="POST">
                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
            </form>
            <script type="text/javascript">
                function qrcode() {
                    document.getElementById("qrcode").submit();
                }
            </script>
            <div class="content-body">
                <section id="statistics-card">
                    <div class="row">
                        <?php
                        if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM attendance WHERE aid='$aid' AND attdate='$today'")) > 0) {
                            $time = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM attendance WHERE aid='$aid' AND attdate='$today'"));
                            if ($time['attstat'] == "Present" && $time['checkin'] != "00:00:00" && $time['checkout'] == "00:00:00") { ?>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-start pb-0">
                                            <div>
                                                <h3 class="text-bold-700 mb-0">CHECK OUT</h3>
                                                <p>Currently check in.</p>
                                            </div>
                                            <a href="javascript:qrcode()" title="Check out">
                                                <div class="avatar bg-rgba-danger p-50 m-0">
                                                    <div class="avatar-content">
                                                        <i class="feather icon-log-out text-danger font-medium-5"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else if ($time['attstat'] == "Hours leave" && $time['checkin'] == "00:00:00") { ?>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-start pb-0">
                                            <div>
                                                <h3 class="text-bold-700 mb-0">CHECK IN</h3>
                                                <p>Currently check out.</p>
                                            </div>
                                            <a href="javascript:qrcode()" title="Check in">
                                                <div class="avatar bg-rgba-success p-50 m-0">
                                                    <div class="avatar-content">
                                                        <i class="feather icon-log-in text-success font-medium-5"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else if ($time['attstat'] == "Hours leave" && $time['checkin'] != "00:00:00" && $time['checkout'] == "00:00:00") { ?>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-start pb-0">
                                            <div>
                                                <h3 class="text-bold-700 mb-0">CHECK OUT</h3>
                                                <p>Currently check in.</p>
                                            </div>
                                            <a href="javascript:qrcode()" title="Check out">
                                                <div class="avatar bg-rgba-danger p-50 m-0">
                                                    <div class="avatar-content">
                                                        <i class="feather icon-log-out text-danger font-medium-5"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else if ($time['checkout'] != "00:00:00") { ?>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-start pb-0">
                                            <div>
                                                <h3 class="text-bold-700 mb-0">CHECK IN</h3>
                                                <p>Currently check out.</p>
                                            </div>
                                            <a title="Disabled. already check in & check out marked.">
                                                <div class="avatar bg-rgba-success p-50 m-0">
                                                    <div class="avatar-content">
                                                        <i class="feather icon-log-in text-success font-medium-5"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else { ?>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-start pb-0">
                                            <div>
                                                <h3 class="text-bold-700 mb-0">In Leave</h3>
                                                <p>Currently in <?php echo $time['attstat']; ?>.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else { ?>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-header d-flex align-items-start pb-0">
                                        <div>
                                            <h3 class="text-bold-700 mb-0">CHECK IN</h3>
                                            <p>Currently check out.</p>
                                        </div>
                                        <a href="javascript:qrcode()" title="Check in">
                                            <div class="avatar bg-rgba-success p-50 m-0">
                                                <div class="avatar-content">
                                                    <i class="feather icon-log-in text-success font-medium-5"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        if ($row['utype'] == "Employee") {
                        ?>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-header d-flex align-items-start pb-0">
                                        <div>
                                            <h2 class="text-bold-700 mb-0"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM actdetails WHERE utype='Branch Manager'")); ?></h2>
                                            <p>Active Branches</p>
                                        </div>
                                        <div class="avatar bg-rgba-info p-50 m-0">
                                            <div class="avatar-content">
                                                <i class="feather icon-briefcase text-info font-medium-5"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-header d-flex align-items-start pb-0">
                                        <div>
                                            <h2 class="text-bold-700 mb-0"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM actdetails WHERE utype='Employee'")); ?></h2>
                                            <p>Active Employees</p>
                                        </div>
                                        <div class="avatar bg-rgba-warning p-50 m-0">
                                            <div class="avatar-content">
                                                <i class="feather icon-users text-warning font-medium-5"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-header d-flex align-items-start pb-0">
                                        <div>
                                            <h2 class="text-bold-700 mb-0"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM product")); ?></h2>
                                            <p>Available Products</p>
                                        </div>
                                        <div class="avatar bg-rgba-danger p-50 m-0">
                                            <div class="avatar-content">
                                                <i class="feather icon-shopping-bag text-danger font-medium-5"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-end">
                                    <h4 class="mb-0">Todo Overview</h4>
                                    <a href="javascript:toDo()" title="To Do Details">
                                        <p class="font-medium-5 mb-0"><i class="feather icon-check-square text-PRIMARY cursor-pointer"></i></p>
                                    </a>
                                </div>
                                <div class="card-content">
                                    <div class="card-body px-0 pb-0">
                                        <div id="todo-overview-chart" class="mt-75"></div>
                                        <div class="row text-center mx-0">
                                            <div class="col-4 border-top border-right d-flex align-items-between flex-column py-1">
                                                <p class="font-small-3 mb-50">Total</p>
                                                <p class="font-large-1 text-bold-700"><?php echo $sertotal; ?></p>
                                            </div>
                                            <div class="col-4 border-top border-right d-flex align-items-between flex-column py-1">
                                                <p class="font-small-3 mb-50">Completed</p>
                                                <p class="font-large-1 text-bold-700"><?php echo $sercom; ?></p>
                                            </div>
                                            <div class="col-4 border-top d-flex align-items-between flex-column py-1">
                                                <p class="font-small-3 mb-50">In Progress</p>
                                                <p class="font-large-1 text-bold-700"><?php echo $serinp; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-end">
                                    <h4 class="mb-0">Stock Overview</h4>
                                    <p class="font-medium-5 mb-0"><i class="fa fa-shopping-bag text-primary"></i></p>
                                </div>
                                <div class="card-content">
                                    <div class="card-body px-0 pb-0">
                                        <div id="stock-overview-chart" class="mt-75"></div>
                                        <div class="row text-center mx-0">
                                            <div class="col-4 border-top border-right d-flex align-items-between flex-column py-1">
                                                <p class="font-small-3 mb-50">Total Stock</p>
                                                <p class="font-large-1 text-bold-700"><?php echo $stocktotal; ?></p>
                                            </div>
                                            <div class="col-4 border-top border-right d-flex align-items-between flex-column py-1">
                                                <p class="font-small-3 mb-50">Sold Stock</p>
                                                <p class="font-large-1 text-bold-700"><?php echo $stockcom; ?></p>
                                            </div>
                                            <div class="col-4 border-top d-flex align-items-between flex-column py-1">
                                                <p class="font-small-3 mb-50">In Stock</p>
                                                <p class="font-large-1 text-bold-700"><?php echo $stockinp; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 class="card-title">Feedback Form</h4>
                                    </div>
                                    <div class="card-body">
                                        <form class="form" action="#" method="POST">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label for="feedback2" class="sr-only">Email</label>
                                                    <input type="email" class="form-control" disabled value="<?php $actdetails = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE dest='CEO'"));
                                                                                                                echo $actdetails['fname'] . " " . $actdetails['lname']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="feedback1" class="sr-only">Subject</label>
                                                    <input type="text" class="form-control" name="msgTit" placeholder="Subject">
                                                </div>
                                                <div class="form-group">
                                                    <label for="feedback3" class="sr-only">Suggestion</label>
                                                    <textarea id="feedback3" rows="3" class="form-control" name="msgTxt" placeholder="Suggestion"></textarea>
                                                </div>
                                            </div>
                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                            <input value="<?php echo $actdetails['aid']; ?>" style="display: none;" name="emailid">
                                            <div class="form-actions">
                                                <button type="submit" name="feedbackBtn" class="btn btn-primary mr-1">Submit</button>
                                                <button type="reset" class="btn btn-outline-warning">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                        } else {
                ?>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h2 class="text-bold-700 mb-0"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM actdetails WHERE utype='Branch Manager'")); ?></h2>
                                    <p>Active Branches</p>
                                </div>
                                <a href="javascript:shopdt()" title="Shop Details">
                                    <div class="avatar bg-rgba-info p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="feather icon-briefcase text-info font-medium-5"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h2 class="text-bold-700 mb-0"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM actdetails WHERE utype='Employee'")); ?></h2>
                                    <p>Active Employees</p>
                                </div>
                                <a href="javascript:Empsdt()" title="Employee Details">
                                    <div class="avatar bg-rgba-warning p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="feather icon-users text-warning font-medium-5"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h2 class="text-bold-700 mb-0"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM product")); ?></h2>
                                    <p>Available Products</p>
                                </div>
                                <a href="javascript:Proddt()" title="Product Details">
                                    <div class="avatar bg-rgba-danger p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="feather icon-shopping-bag text-danger font-medium-5"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-end">
                            <h4 class="mb-0">Todo Overview</h4>
                            <a href="javascript:toDo()" title="To Do Details">
                                <p class="font-medium-5 mb-0"><i class="feather icon-check-square text-PRIMARY cursor-pointer"></i></p>
                            </a>
                        </div>
                        <div class="card-content">
                            <div class="card-body px-0 pb-0">
                                <div id="todo-overview-chart" class="mt-75"></div>
                                <div class="row text-center mx-0">
                                    <div class="col-4 border-top border-right d-flex align-items-between flex-column py-1">
                                        <p class="font-small-3 mb-50">Total</p>
                                        <p class="font-large-1 text-bold-700"><?php echo $sertotal; ?></p>
                                    </div>
                                    <div class="col-4 border-top border-right d-flex align-items-between flex-column py-1">
                                        <p class="font-small-3 mb-50">Completed</p>
                                        <p class="font-large-1 text-bold-700"><?php echo $sercom; ?></p>
                                    </div>
                                    <div class="col-4 border-top d-flex align-items-between flex-column py-1">
                                        <p class="font-small-3 mb-50">In Progress</p>
                                        <p class="font-large-1 text-bold-700"><?php echo $serinp; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-end">
                            <h4 class="mb-0">Stock Overview</h4>
                            <a href="javascript:stockHand()" title="Stock on Hand">
                                <p class="font-medium-5 mb-0"><i class="fa fa-shopping-bag text-PRIMARY cursor-pointer"></i></p>
                            </a>
                        </div>
                        <div class="card-content">
                            <div class="card-body px-0 pb-0">
                                <div id="stock-overview-chart" class="mt-75"></div>
                                <div class="row text-center mx-0">
                                    <div class="col-4 border-top border-right d-flex align-items-between flex-column py-1">
                                        <p class="font-small-3 mb-50">Total Stock</p>
                                        <p class="font-large-1 text-bold-700"><?php echo $stocktotal; ?></p>
                                    </div>
                                    <div class="col-4 border-top border-right d-flex align-items-between flex-column py-1">
                                        <p class="font-small-3 mb-50">Sold Stock</p>
                                        <p class="font-large-1 text-bold-700"><?php echo $stockcom; ?></p>
                                    </div>
                                    <div class="col-4 border-top d-flex align-items-between flex-column py-1">
                                        <p class="font-small-3 mb-50">In Stock</p>
                                        <p class="font-large-1 text-bold-700"><?php echo $stockinp; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Feedback Form</h4>
                            </div>
                            <div class="card-body">
                                <form class="form" action="#" method="POST">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="feedback2" class="sr-only">Email</label>
                                            <input type="email" class="form-control" disabled value="<?php $actdetails = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE dest='CEO'"));
                                                                                                        echo $actdetails['fname'] . " " . $actdetails['lname']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="feedback1" class="sr-only">Subject</label>
                                            <input type="text" class="form-control" name="msgTit" placeholder="Subject">
                                        </div>
                                        <div class="form-group">
                                            <label for="feedback3" class="sr-only">Suggestion</label>
                                            <textarea id="feedback3" rows="3" class="form-control" name="msgTxt" placeholder="Suggestion"></textarea>
                                        </div>
                                    </div>
                                    <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                    <input value="<?php echo $actdetails['aid']; ?>" style="display: none;" name="emailid">
                                    <div class="form-actions">
                                        <button type="submit" name="feedbackBtn" class="btn btn-primary mr-1">Submit</button>
                                        <button type="reset" class="btn btn-outline-warning">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
                        }
        ?>
        </section>
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
    <!-- BEGIN: Form-->

    <!-- END: Form-->

    <!-- BEGIN: Vendor JS-->
    <script src="../../app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../app-assets/vendors/js/charts/apexcharts.min.js"></script>
    <script src="../../app-assets/vendors/js/extensions/tether.min.js"></script>
    <script src="../../app-assets/vendors/js/extensions/shepherd.min.js"></script>
    <script src="../../app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../../app-assets/js/core/app-menu.js"></script>
    <script src="../../app-assets/js/core/app.js"></script>
    <script src="../../app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script>
        $(window).on("load", function() {

            var $primary = '#7367F0';
            var $success = '#28C76F';
            var $danger = '#EA5455';
            var $warning = '#FF9F43';
            var $info = '#00cfe8';
            var $primary_light = '#A9A2F6';
            var $danger_light = '#f29292';
            var $success_light = '#55DD92';
            var $warning_light = '#ffc085';
            var $info_light = '#1fcadb';
            var $strok_color = '#b9c3cd';
            var $label_color = '#e7e7e7';
            var $white = '#fff';

            var todoChartoptions = {
                chart: {
                    height: 250,
                    type: 'radialBar',
                    sparkline: {
                        enabled: true,
                    },
                    dropShadow: {
                        enabled: true,
                        blur: 3,
                        left: 1,
                        top: 1,
                        opacity: 0.1
                    },
                },
                colors: [$success],
                plotOptions: {
                    radialBar: {
                        size: 110,
                        startAngle: -150,
                        endAngle: 150,
                        hollow: {
                            size: '77%',
                        },
                        track: {
                            background: $strok_color,
                            strokeWidth: '50%',
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                offsetY: 18,
                                color: '#99a2ac',
                                fontSize: '4rem'
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        gradientToColors: ['#00b5b5'],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    },
                },
                series: [<?php echo $todopercent; ?>],
                stroke: {
                    lineCap: 'round'
                },

            }

            var todoChart = new ApexCharts(
                document.querySelector("#todo-overview-chart"),
                todoChartoptions
            );

            todoChart.render();

            var stockChartoptions = {
                chart: {
                    height: 250,
                    type: 'radialBar',
                    sparkline: {
                        enabled: true,
                    },
                    dropShadow: {
                        enabled: true,
                        blur: 3,
                        left: 1,
                        top: 1,
                        opacity: 0.1
                    },
                },
                colors: [$success],
                plotOptions: {
                    radialBar: {
                        size: 110,
                        startAngle: -150,
                        endAngle: 150,
                        hollow: {
                            size: '77%',
                        },
                        track: {
                            background: $strok_color,
                            strokeWidth: '50%',
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                offsetY: 18,
                                color: '#99a2ac',
                                fontSize: '4rem'
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        gradientToColors: ['#00b5b5'],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    },
                },
                series: [<?php echo $stockpercent; ?>],
                stroke: {
                    lineCap: 'round'
                },

            }

            var stockChart = new ApexCharts(
                document.querySelector("#stock-overview-chart"),
                stockChartoptions
            );

            stockChart.render();
        });
    </script>
    <!-- END: Page JS-->
</body>
<!-- END: Body-->

</html>