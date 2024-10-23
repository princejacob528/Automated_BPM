<?php
$aid = $_POST['userKey'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
date_default_timezone_set('Etc/UTC');
include("../connection.php");
$flag = 0;
if (isset($_POST['csaveBtn'])) {
    $cname = $_POST['cname'];
    $cid = $_POST['cid'];
    $cemail = $_POST['cemail'];
    $cphone = $_POST['cphone'];
    if (mysqli_query($con, "UPDATE customer SET cname='$cname', cemail='$cemail', cphone='$cphone' WHERE cid='$cid'"))
        $flag = 1;
    else
        $flag = 2;
}
if (isset($_POST['custcrtBtn'])) {
    $cname = $_POST['cname'];
    $cads = $_POST['cads'];
    $cemail = $_POST['cemail'];
    $cphone = $_POST['cphone'];
    if (mysqli_query($con, "INSERT INTO customer (cname, cads, cemail, cphone) VALUES ('$cname', '$cads', '$cemail', '$cphone') "))
        $flag = 3;
    else
        $flag = 4;
}
if (isset($_POST['debitcreate'])) {
    $vid = $_POST['vid'];
    $debit = $_POST['debit'];
    $dates = date("Y-m-d");
    if (mysqli_query($con, "INSERT INTO custcredit (cid, dates, credit, debit, ref) VALUES ('$vid', '$dates', '0', '$debit', '0')"))
        $flag = 5;
    else
        $flag = 6;
}
if (isset($_POST['assignSr'])) {
    $flag = 10;
    $srid = $_POST['assignsrid'];
    $svraid = $_POST['svraid'];
    if (mysqli_query($con, "UPDATE service SET stat='1' WHERE srid='$srid'")) {
        $delter = mysqli_query($con, "DELETE FROM servicedt WHERE srid='$srid'");
        if (mysqli_query($con, "INSERT INTO servicedt (invdetails, srid, svrid, srno, stat, srdetails, incentive, cost, discount) VALUES ('','$srid', '$svraid', '1', '0', 'Assgined by higher authority', '0', '0', '0')")) {
            $flag = 9;
        }
    }
}
if (isset($_POST['acceptFrom'])) {
    $flag = 10;
    $srid = $_POST['srid'];
    if (mysqli_query($con, "UPDATE service SET stat='1' WHERE srid='$srid'")) {
        if (mysqli_query($con, "INSERT INTO servicedt (invdetails, srid, svrid, srno, stat, srdetails, incentive, cost, discount) VALUES ('', '$srid', '$aid', '1', '0', 'Self assgined', '0', '0', '0')")) {
            $flag = 9;
        }
    }
    $titler = "SR-" . str_pad($srid, 5, 0, STR_PAD_LEFT);
    mysqli_query($con, "DELETE FROM notificat WHERE ntitle='$titler' AND aid!='$aid'");
    mysqli_query($con, "UPDATE notificat SET nstatus='1' WHERE ntitle='$titler' AND aid='$aid'");
}
if (isset($_POST['reqacceptFrom'])) {
    $flag = 10;
    $srid = $_POST['srid'];
    $srno = $_POST['srno'];
    if (mysqli_query($con, "UPDATE service SET stat='$srno' WHERE srid='$srid'")) {
        if (mysqli_query($con, "UPDATE servicedt SET stat='0' WHERE srid='$srid' AND srno='$srno'")) {
            $flag = 9;
        }
    }
    $titler = "SR-" . str_pad($srid, 5, 0, STR_PAD_LEFT);
    mysqli_query($con, "UPDATE notificat SET nstatus='1' WHERE ntitle='$titler' AND aid='$aid'");
}
if (isset($_POST['assignSred'])) {
    $assignsrided = $_POST['assignsrided'];
    $assignsridno = $_POST['assignsridno'];
    $svraid = $_POST['svraid'];
    $method = $_POST['method'];
    if ($method == '2') {
        if (mysqli_query($con, "UPDATE service SET stat='1' WHERE srid='$assignsrided'")) {
            $delter = mysqli_query($con, "DELETE FROM servicedt WHERE srid='$assignsrided'");
            if (mysqli_query($con, "INSERT INTO servicedt (invdetails, srid, svrid, srno, stat, srdetails, incentive, cost, discount) VALUES ('', '$assignsrided', '$svraid', '1', '0', 'Assgined by higher authority', '0', '0', '0')")) {
                $flag = 9;
            }
        }
    } else if ($method == '1') {
        if (mysqli_query($con, "UPDATE servicedt SET svrid='$svraid', srdetails='Assgined by higher authority', incentive='0', cost='0', discount='0', stat='0' WHERE srid='$assignsrided' AND srno='$assignsridno'")) {
            $flag = 9;
        }
    } else if ($method == '0') {
        if (mysqli_query($con, "UPDATE servicedt SET srdetails='Servicer is changed by higher authorities.', incentive='0', cost='0', discount='0', stat='2' WHERE srid='$assignsrided' AND srno='$assignsridno'")) {
            $flag = 9;
        }
        $assignsridno = intval($assignsridno) + 1;
        if (mysqli_query($con, "UPDATE service SET stat='$assignsridno' WHERE srid='$assignsrided'")) {
            if (mysqli_query($con, "INSERT INTO servicedt (invdetails, srid, svrid, srno, stat, srdetails, incentive, cost, discount) VALUES ('', '$assignsrided', '$svraid', '$assignsridno', '0', 'Assgined by higher authority', '0', '0', '0')")) {
                $flag = 9;
            }
        }
    }
}
$query = "select * from actdetails where aid = '$aid'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
if (isset($_POST['pendingTask'])) {
    $flag = 12;
    if (isset($_POST['svraid']))
        $svraid = $_POST['svraid'];
    if (isset($_POST['amount']))
        $amount = $_POST['amount'];
    $viewchagerno = $_POST['viewchagerno'];
    $complaints = $_POST['complaints'];
    $srid = $_POST['srid'];
    $srdid = $_POST['srdid'];
    $stat = $_POST['stat'];
    if ($viewchagerno == 0) {
        if (mysqli_query($con, "UPDATE service SET stat='4' WHERE srid='$srid'")) {
            if (mysqli_query($con, "UPDATE servicedt SET stat='1', srdetails='$complaints', incentive='$amount' WHERE srdid='$srdid'")) {
                $flag = 11;
            }
        }
        $service = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM service WHERE srid='$srid'"));
        $brid = $service['brid'];
        $cid = $service['cid'];
        $cust = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customer WHERE cid='$cid'"));
        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brid'"));
        $brancheml = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM account WHERE aid='$brid'"));
        $titler = "SR-" . str_pad($srid, 5, 0, STR_PAD_LEFT);
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0; // 0 - Disable Debugging, 2 - Responses received from the server
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'princedeveloper24@gmail.com'; // SMTP username
        $mail->Password = 'hrpamlmttmansptj'; // SMTP password
        $mail->SMTPSecure = 'tls'; //PHPMailer::ENCRYPTION_STARTTLS; Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port = 587; // TCP port to connect to

        //Recipients
        $mail->setFrom('princedeveloper24@gmail.com', 'PrinceDev Mailer');
        $mail->addAddress($cust['cemail'], $cust['cname']); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Automated BPM - ' . $titler;
        $mail->Body = "Dear Customer,
        <br><br>Thank you for choosing! We have resolved all your registered complaints of your " . $service['items'] . " with reference ID: " . $titler . " that we have received few days before. You can collect the same from the service center. If you notice the same complaint within next 30 days, you will be having a free service.
        <br><br>If you have any questions or queries, please contact us through ( " . $brancheml['email'] . " / " . $branch['pno'] . " )    
        <br><br>We hope for a long business relationship with you. Thank you!    
        <br><br>Best regards,<br>
        " . $row['fname'] . " " . $row['lname'];

        if ($mail->send()) {
            $flag = 11;
        }
    } else if ($viewchagerno == 1) {
        if (mysqli_query($con, "UPDATE servicedt SET stat='2', srdetails='$complaints', incentive='$amount' WHERE srdid='$srdid'")) {
            $assignsridno = intval($stat) + 1;
            $details = "Requested by " . $row['fname'] . " " . $row['lname'];
            if (mysqli_query($con, "INSERT INTO servicedt (invdetails, srid, svrid, srno, stat, srdetails, incentive, cost, discount) VALUES ('', '$srid', '$svraid', '$assignsridno', '3', '$details', '0', '0', '0')")) {
                $flag = 11;
            }
            $message = $details . "<br>Servicer Details: " . $complaints;
            $titler = "SR-" . str_pad($srid, 5, 0, STR_PAD_LEFT);
            mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$svraid', '$message', '$titler', '0', 'To Do', '$aid')");
        }
    } else if ($viewchagerno == 2) {
        if (mysqli_query($con, "UPDATE servicedt SET stat='4', srdetails='$complaints' WHERE srdid='$srdid'")) {
            $assignsridno = intval($stat) + 1;
            $details = "Requested by " . $row['fname'] . " " . $row['lname'];
            if (mysqli_query($con, "INSERT INTO servicedt (invdetails, srid, svrid, srno, stat, srdetails, incentive, cost, discount) VALUES ('', '$srid', '$svraid', '$assignsridno', '3', '$details', '0', '0', '0')")) {
                $flag = 11;
            }
            $message = $details . "<br>Servicer Details: " . $complaints;
            $titler = "SR-" . str_pad($srid, 5, 0, STR_PAD_LEFT);
            mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$svraid', '$message', '$titler', '0', 'To Do', '$aid')");
        }
    }
}
if (isset($_POST['sevicecrtBtn'])) {
    if (isset($_POST['existingcustomer'])) {
        $cid = $_POST['cid'];
        $cust = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customer WHERE cid='$cid'"));
        $brid = $_POST['brid'];
        $dates = $_POST['dates'];
        $items = $_POST['items'];
        $complaints = $_POST['complaints'];
        if (mysqli_query($con, "INSERT INTO service (srcr, cid, brid, dates, items, complaints, stat) VALUES ('$aid', '$cid', '$brid', '$dates', '$items', '$complaints', '0')"))
            $flag = 7;
        else
            $flag = 8;
        $srid = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM service WHERE srcr='$aid' AND cid='$cid' AND brid='$brid' AND dates='$dates'"));
        $message = "Item: " . $items;
        $message .= "<br>";
        $message .= "Complaints: " . $complaints;
        $titler = "SR-" . str_pad($srid['srid'], 5, 0, STR_PAD_LEFT);
        if ($row['utype'] == "Management") {
            mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$brid', '$message', '$titler', '0', 'To Do', '$aid')");
            $shop = mysqli_query($con, " SELECT * FROM shop WHERE aid='$brid'");
            if (mysqli_num_rows($shop) > 0) {
                foreach ($shop as $shops) {
                    $aaid = $shops['eid'];
                    mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$aaid', '$message', '$titler', '0', 'To Do', '$aid')");
                }
            }
        } else if ($row['utype'] == "Branch Manager") {
            $shop = mysqli_query($con, " SELECT * FROM shop WHERE aid='$brid'");
            if (mysqli_num_rows($shop) > 0) {
                foreach ($shop as $shops) {
                    $aaid = $shops['eid'];
                    mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$aaid', '$message', '$titler', '0', 'To Do', '$aid')");
                }
            }
        } else {
            mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$brid', '$message', '$titler', '0', 'To Do', '$aid')");
            $shop = mysqli_query($con, " SELECT * FROM shop WHERE aid='$brid' AND eid!='$aid'");
            if (mysqli_num_rows($shop) > 0) {
                foreach ($shop as $shops) {
                    $aaid = $shops['eid'];
                    mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$aaid', '$message', '$titler', '0', 'To Do', '$aid')");
                }
            }
        }
    } else if (isset($_POST['newcustomer'])) {
        $cname = $_POST['cname'];
        $cphone = $_POST['cphone'];
        $cemail = $_POST['cemail'];
        $cads = $_POST['cads'];
        if (mysqli_query($con, "INSERT INTO customer (cname, cads, cemail, cphone) VALUES ('$cname', '$cads', '$cemail', '$cphone') ")) {
            $cust = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customer WHERE cname='$cname' AND cads='$cads' AND cphone='$cphone' AND cemail='$cemail'"));
            $cid = $cust['cid'];
        }
        $dates = $_POST['dates'];
        $brid = $_POST['brid'];
        $items = $_POST['items'];
        $complaints = $_POST['complaints'];
        if (mysqli_query($con, " INSERT INTO service (srcr, cid, brid, dates, items, complaints, stat) VALUES ('$aid', '$cid', '$brid', '$dates', '$items', '$complaints', '0')"))
            $flag = 7;
        else
            $flag = 8;
        $srid = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM service WHERE srcr='$aid' AND cid='$cid' AND brid='$brid' AND dates='$dates'"));
        $message = "Item: " . $items;
        $message .= "<br>";
        $message .= "Complaints: " . $complaints;
        $titler = "SR-" . str_pad($srid['srid'], 5, 0, STR_PAD_LEFT);
        if ($row['utype'] == "Management") {
            mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$brid', '$message', '$titler', '0', 'To Do', '$aid')");
            $shop = mysqli_query($con, " SELECT * FROM shop WHERE aid='$brid'");
            if (mysqli_num_rows($shop) > 0) {
                foreach ($shop as $shops) {
                    $aaid = $shops['eid'];
                    mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$aaid', '$message', '$titler', '0', 'To Do', '$aid')");
                }
            }
        } else if ($row['utype'] == "Branch Manager") {
            $shop = mysqli_query($con, " SELECT * FROM shop WHERE aid='$brid'");
            if (mysqli_num_rows($shop) > 0) {
                foreach ($shop as $shops) {
                    $aaid = $shops['eid'];
                    mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$aaid', '$message', '$titler', '0', 'To Do', '$aid')");
                }
            }
        } else {
            mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$brid', '$message', '$titler', '0', 'To Do', '$aid')");
            $shop = mysqli_query($con, " SELECT * FROM shop WHERE aid='$brid' AND eid!='$aid'");
            if (mysqli_num_rows($shop) > 0) {
                foreach ($shop as $shops) {
                    $aaid = $shops['eid'];
                    mysqli_query($con, "insert into notificat (aid, nsub, ntitle, nstatus, ntype, nsender) values ('$aaid', '$message', '$titler', '0', 'To Do', '$aid')");
                }
            }
        }
    }
    $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brid'"));
    $brancheml = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM account WHERE aid='$brid'"));
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0; // 0 - Disable Debugging, 2 - Responses received from the server
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'princedeveloper24@gmail.com'; // SMTP username
    $mail->Password = 'hrpamlmttmansptj'; // SMTP password
    $mail->SMTPSecure = 'tls'; //PHPMailer::ENCRYPTION_STARTTLS; Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port = 587; // TCP port to connect to

    //Recipients
    $mail->setFrom('princedeveloper24@gmail.com', 'PrinceDev Mailer');
    $mail->addAddress($cust['cemail'], $cust['cname']); // Add a recipient

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Automated BPM - ' . $titler;
    $mail->Body = "Dear Customer,
    <br><br>Thank for choosing us! It's a great pleasure to have you here. We have registered a complaint on your " . $items . " by " . $row['fname'] . " " . $row['lname'] . ".  " . $complaints . " is the complaint that you have registered with us . Your reference number is " . $titler . ". Please quote it for the future correspondence. You will receive an email after resolving all the problems that you have registered.
    <br><br>If you have any questions or queries, please contact us through ( " . $brancheml['email'] . " / " . $branch['pno'] . " )    
    <br><br>We hope for a long business relationship with you. Thank you!    
    <br><br>Best regards,<br>
    " . $row['fname'] . " " . $row['lname'];

    if ($mail->send()) {
        $flag = 7;
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
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="javascript:notiFy()"><i class="ficon feather icon-bell"></i><span class="badge badge-pill badge-primary badge-up"><?php echo $count; ?></span></a>
                            <form style="display: none;" id="notiFy" action="notification.php" method="POST">
                                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                            </form>
                            <form style="display: none;" id="lightForm" action="../dark/to_do.php" method="POST">
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
                                    var today = new Date();
                                    var dd = String(today.getDate()).padStart(2, '0');
                                    var mm = String(today.getMonth() + 1).padStart(2, '0');
                                    var yyyy = today.getFullYear();
                                    today = yyyy + '-' + mm + '-' + dd;
                                    dates.value = today;
                                    var x = '<?php echo $flag; ?>';
                                    if (x == 1) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success("Customer updated successfully..", "Well Done !");
                                    } else if (x == 2) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.error('Customer updated failed..', "Oops !");
                                    } else if (x == 3) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success("Customer created successfully..", "Well Done !");
                                    } else if (x == 4) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.error('Customer created failed..', "Oops !");
                                    } else if (x == 5) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success("Debit added successfully..", "Well Done !");
                                    } else if (x == 6) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.error('Debit added failed..', "Oops !");
                                    } else if (x == 7) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success("Service added successfully..", "Well Done !");
                                    } else if (x == 8) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.error('Service added failed..', "Oops !");
                                    } else if (x == 9) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success("Servicer assigned successfully..", "Well Done !");
                                    } else if (x == 10) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.error('Servicer assigned failed..', "Oops !");
                                    } else if (x == 11) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.success("Servicer updated successfully..", "Well Done !");
                                    } else if (x == 12) {
                                        toastr.options = {
                                            "progressBar": true,
                                            "positionClass": "toast-bottom-right"
                                        };
                                        toastr.error('Servicer updated failed..', "Oops !");
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
                                        document.getElementById("creditTab").style.display = "block";
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
                <li class=" nav-item"><a href="javascript:dashBtn()" style="display: none;" id="daS"><i class="feather icon-home"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span></a>
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
                <li class="active nav-item"><a href="javascript:toDo()" style="display: none;" id="tDo"><i class="feather icon-check-square"></i><span class="menu-title">To Do</span></a>
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
                            <h2 class="content-header-title float-left mb-0">To Do</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:homeBtn()">Home</a></li>
                                    <li class="breadcrumb-item active">To Do</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section class="users-edit">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                                            <i class="feather icon-settings mr-25"></i><span class="d-none d-sm-block">Service</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center" id="information-tab" data-toggle="tab" href="#information" aria-controls="information" role="tab" aria-selected="false">
                                            <i class="feather icon-users mr-25"></i><span class="d-none d-sm-block">Customer</span>
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
                                                <button data-toggle="modal" data-target="#addToDo" class="btn btn-outline-success round mr-30">Add To Do</button>
                                            </div>
                                        </section>
                                        <br>
                                        <?php
                                        /*New Service*/

                                        if ($row['utype'] != "Management") {/*New Services*/
                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="info">New Services</h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="CpendingTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $brid = "";
                                                            if ($row['utype'] == "Branch Manager") {
                                                                $brid = $aid;
                                                            } else  if ($row['utype'] == "Employee") {
                                                                $shop = mysqli_query($con, "SELECT * FROM shop WHERE eid='$aid'");
                                                                if (mysqli_num_rows($shop) > 0) {
                                                                    $shop = mysqli_fetch_array($shop);
                                                                    $brid = $shop['aid'];
                                                                }
                                                            }
                                                            if ($brid != "") {
                                                                $service = mysqli_query($con, "SELECT * FROM service WHERE brid='$brid' AND stat='0'");
                                                                foreach ($service as $serv) {
                                                            ?>
                                                                    <tr>
                                                                        <td><?php echo $serv['dates']; ?></td>
                                                                        <td><?php
                                                                            $brids = $serv['srcr'];
                                                                            $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                            echo $branch['fname'] . " " . $branch['lname'];
                                                                            ?></td>
                                                                        <td><?php echo $serv['items']; ?></td>
                                                                        <td><?php echo $serv['complaints']; ?></td>
                                                                        <td>
                                                                            <form action="#" method="POST">
                                                                                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                                                <input value='<?php echo $serv['srid']; ?>' name="srid" style="display: none;">
                                                                                <input type="submit" title="Accept" name="acceptFrom" class="btn btn-outline-success btn-sm round" value="Accept">
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section>
                                        <?php
                                        }
                                        /*Request Service*/

                                        if ($row['utype'] != "Management") {/*New Services*/
                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="primary">Services Transfer Request</h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="rpendingTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $servicedt = mysqli_query($con, "SELECT * FROM servicedt WHERE svrid ='$aid' AND stat='3'");
                                                            if (mysqli_num_rows($servicedt) > 0)
                                                                foreach ($servicedt as $servicedtss) {
                                                                    $srid = $servicedtss['srid'];
                                                                    $service = mysqli_query($con, "SELECT * FROM service WHERE srid='$srid'");
                                                                    foreach ($service as $serv) {
                                                            ?>
                                                                    <tr>
                                                                        <td><?php echo $serv['dates']; ?></td>
                                                                        <td><?php
                                                                            $brids = $serv['srcr'];
                                                                            $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                            echo $branch['fname'] . " " . $branch['lname'];
                                                                            ?></td>
                                                                        <td><?php echo $serv['items']; ?></td>
                                                                        <td><?php echo $serv['complaints']; ?></td>
                                                                        <td>
                                                                            <form action="#" method="POST">
                                                                                <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                                                                <input value='<?php echo $serv['srid']; ?>' name="srid" style="display: none;">
                                                                                <input value='<?php echo $servicedtss['srno']; ?>' name="srno" style="display: none;">
                                                                                <input type="submit" title="Accept" name="reqacceptFrom" class="btn btn-outline-success btn-sm round" value="Accept">
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section>
                                        <?php
                                        }
                                        /*Pending Service*/

                                        if ($row['utype'] != "Management") {/*Pending Services*/
                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="warning">Pending Services</h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="IpendingTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $servicedt = mysqli_query($con, "SELECT * FROM servicedt WHERE svrid ='$aid' AND stat='0'");
                                                            if (mysqli_num_rows($servicedt) > 0)
                                                                foreach ($servicedt as $servicedtss) {
                                                                    $srid = $servicedtss['srid'];
                                                                    $service = mysqli_query($con, "SELECT * FROM service WHERE srid='$srid'");
                                                                    foreach ($service as $serv) {
                                                            ?>
                                                                    <tr>
                                                                        <td><?php echo $serv['dates']; ?></td>
                                                                        <td><?php
                                                                            $brids = $serv['srcr'];
                                                                            $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                            echo $branch['fname'] . " " . $branch['lname'];
                                                                            ?></td>
                                                                        <td><?php echo $serv['items']; ?></td>
                                                                        <td><?php echo $serv['complaints']; ?></td>
                                                                        <td>
                                                                            <?php
                                                                            if ($serv['stat'] == 1) {
                                                                            ?>
                                                                                <div class="chip chip-warning">
                                                                                    <div class="chip-body">
                                                                                        <div class="chip-text">Servicing(1)</div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php
                                                                            } else if ($serv['stat'] == 2) {
                                                                            ?>
                                                                                <div class="chip chip-warning">
                                                                                    <div class="chip-body">
                                                                                        <div class="chip-text">Servicing(2)</div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php
                                                                            } else if ($serv['stat'] == 3) {
                                                                            ?>
                                                                                <div class="chip chip-warning">
                                                                                    <div class="chip-body">
                                                                                        <div class="chip-text">Servicing(3)</div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <a data-toggle="modal" data-target="#transfer" id="change<?php echo $srid; ?>">
                                                                                <div class="chip chip-error">
                                                                                    <div class="chip-body">
                                                                                        <div class="chip-text">Open</div>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                            <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                            <input id="srdid<?php echo $srid; ?>" value="<?php echo $servicedtss['srdid']; ?>" style="display: none;">
                                                                            <input id="stat<?php echo $srid; ?>" value="<?php echo $serv['stat']; ?>" style="display: none;">
                                                                            <script text="javascript">
                                                                                document.getElementById("change<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("srid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("srdid").value = document.getElementById("srdid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("stat").value = document.getElementById("stat<?php echo $srid; ?>").value;
                                                                                };
                                                                            </script>
                                                                        </td>
                                                                    </tr>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section>
                                        <?php
                                        }
                                        /*Service Details*/

                                        if ($row['utype'] == "Management") {/*Services Details Management*/
                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="success">Services Details</h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="ctodoTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>BRANCH</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $service = mysqli_query($con, "SELECT * FROM service WHERE stat!='4' AND stat!='5' AND stat!='6'");
                                                            foreach ($service as $serv) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $serv['dates']; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['brid'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['srcr'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $serv['items']; ?></td>
                                                                    <td><?php echo $serv['complaints']; ?></td>
                                                                    <?php
                                                                    $srid = $serv['srid'];
                                                                    $servdt = mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                                                                    $servct = mysqli_num_rows($servdt);
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 0) {
                                                                        ?>
                                                                            <div class="chip chip-info">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Created</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 1) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(1)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 2) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(2)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 3) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(3)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 0) {
                                                                        ?>
                                                                            <a title="Servicer are not assigned."><i class="fas fa-history light"></i></a>
                                                                            <a title="More Details" style="display: none;" id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <a title="Assign Servicer." id="assign<?php echo $srid; ?>" data-toggle="modal" data-target="#assignTab"><i class="fas fa-user-cog"></i></a>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <a title="More Details." id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <a title="Assign Servicer."><i class="fas fa-user-cog light"></i></a>
                                                                            <a title="Assign Servicer" style="display: none;" id="assign<?php echo $srid; ?>" data-toggle="modal" data-target="#assignTab"><i class="fas fa-user-cog"></i></a>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <script text="javascript">
                                                                            document.getElementById("assign<?php echo $srid; ?>").onclick = function() {
                                                                                document.getElementById("items").innerHTML = document.getElementById("item<?php echo $srid; ?>").value;
                                                                                document.getElementById("complaints").innerHTML = document.getElementById("compt<?php echo $srid; ?>").value;
                                                                                $('#skill').val("");
                                                                                document.getElementById("skillfetched").style.display = "none";
                                                                                document.getElementById("assignsrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                            };
                                                                            document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                $('#skilled').val("");
                                                                                document.getElementById("skilledfetched").style.display = "none";
                                                                                document.getElementById("secondassign").style.display = "none";
                                                                                document.getElementById("footerView").style.display = "block";
                                                                                document.getElementById("assignsrided").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                document.getElementById("assignsridno").value = document.getElementById("count<?php echo $srid; ?>").value;
                                                                                if (document.getElementById("count<?php echo $srid; ?>").value == 3) {
                                                                                    document.getElementById("newservicer").disabled = true;
                                                                                }
                                                                                document.getElementById("historyBtn").click();
                                                                                $.ajax({
                                                                                    type: "GET",
                                                                                    url: "todo_backend.php",
                                                                                    data: {
                                                                                        "srid": srid
                                                                                    },
                                                                                    dataType: "html",
                                                                                    beforeSend: function() {
                                                                                        $('#history_content').html(
                                                                                            '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                        );
                                                                                    },
                                                                                    success: function(data) {
                                                                                        setTimeout(function() {
                                                                                            $('#history_content').html(data);
                                                                                            document.getElementById("secondassign").style.display = "block";
                                                                                        }, 1000);

                                                                                    }
                                                                                });
                                                                            };
                                                                        </script>
                                                                        <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                        <input id="count<?php echo $srid; ?>" value="<?php echo $serv['stat']; ?>" style="display: none;">
                                                                        <input id="item<?php echo $srid; ?>" value="<?php echo $serv['items']; ?>" style="display: none;">
                                                                        <input id="compt<?php echo $srid; ?>" value="<?php echo $serv['complaints']; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section><?php
                                                    } else if ($row['utype'] == "Branch Manager") {/*Services Details Management*/
                                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="success">Services Details</h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="btodoTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $service = mysqli_query($con, "SELECT * FROM service WHERE brid='$aid' AND stat!='4' AND stat!='5' AND stat!='6'");
                                                            foreach ($service as $serv) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $serv['dates']; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['srcr'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $serv['items']; ?></td>
                                                                    <td><?php echo $serv['complaints']; ?></td>
                                                                    <?php
                                                                    $srid = $serv['srid'];
                                                                    $servdt = mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                                                                    $servct = mysqli_num_rows($servdt);
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 0) {
                                                                        ?>
                                                                            <div class="chip chip-info">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Created</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 1) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(1)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 2) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(2)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 3) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(3)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 0) {
                                                                        ?>
                                                                            <a title="Servicer are not assigned."><i class="fas fa-history light"></i></a>
                                                                            <a title="More Details" style="display: none;" id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <a title="Assign Servicer." id="assign<?php echo $srid; ?>" data-toggle="modal" data-target="#assignTab"><i class="fas fa-user-cog"></i></a>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <a title="More Details." id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <a title="Assign Servicer."><i class="fas fa-user-cog light"></i></a>
                                                                            <a title="Assign Servicer" style="display: none;" id="assign<?php echo $srid; ?>" data-toggle="modal" data-target="#assignTab"><i class="fas fa-user-cog"></i></a>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <script text="javascript">
                                                                            document.getElementById("assign<?php echo $srid; ?>").onclick = function() {
                                                                                document.getElementById("items").innerHTML = document.getElementById("item<?php echo $srid; ?>").value;
                                                                                document.getElementById("complaints").innerHTML = document.getElementById("compt<?php echo $srid; ?>").value;
                                                                                $('#skill').val("");
                                                                                document.getElementById("skillfetched").style.display = "none";
                                                                                document.getElementById("assignsrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                            };
                                                                            document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                $('#skilled').val("");
                                                                                document.getElementById("skilledfetched").style.display = "none";
                                                                                document.getElementById("secondassign").style.display = "none";
                                                                                document.getElementById("footerView").style.display = "block";
                                                                                document.getElementById("assignsrided").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                document.getElementById("assignsridno").value = document.getElementById("count<?php echo $srid; ?>").value;
                                                                                if (document.getElementById("count<?php echo $srid; ?>").value == 3) {
                                                                                    document.getElementById("newservicer").disabled = true;
                                                                                }
                                                                                document.getElementById("historyBtn").click();
                                                                                $.ajax({
                                                                                    type: "GET",
                                                                                    url: "todo_backend.php",
                                                                                    data: {
                                                                                        "srid": srid
                                                                                    },
                                                                                    dataType: "html",
                                                                                    beforeSend: function() {
                                                                                        $('#history_content').html(
                                                                                            '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                        );
                                                                                    },
                                                                                    success: function(data) {
                                                                                        setTimeout(function() {
                                                                                            $('#history_content').html(data);
                                                                                            document.getElementById("secondassign").style.display = "block";
                                                                                        }, 1000);

                                                                                    }
                                                                                });
                                                                            };
                                                                        </script>
                                                                        <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                        <input id="count<?php echo $srid; ?>" value="<?php echo $serv['stat']; ?>" style="display: none;">
                                                                        <input id="item<?php echo $srid; ?>" value="<?php echo $serv['items']; ?>" style="display: none;">
                                                                        <input id="compt<?php echo $srid; ?>" value="<?php echo $serv['complaints']; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section><?php
                                                    } else {/*Services Details Management*/
                                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="success">Services Details</h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="etodoTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $service = mysqli_query($con, "SELECT * FROM service WHERE srcr='$aid' AND stat!='4' AND stat!='5' AND stat!='6'");
                                                            foreach ($service as $serv) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $serv['dates']; ?></td>
                                                                    <td><?php echo $serv['items']; ?></td>
                                                                    <td><?php echo $serv['complaints']; ?></td>
                                                                    <?php
                                                                    $srid = $serv['srid'];
                                                                    $servdt = mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                                                                    $servct = mysqli_num_rows($servdt);
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 0) {
                                                                        ?>
                                                                            <div class="chip chip-info">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Created</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 1) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(1)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 2) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(2)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 3) {
                                                                        ?>
                                                                            <div class="chip chip-warning">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing(3)</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 0) {
                                                                        ?>
                                                                            <a title="Servicer are not assigned."><i class="fas fa-history light"></i></a>
                                                                            <a title="More Details" style="display: none;" id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <a title="More Details." id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <script text="javascript">
                                                                            document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                document.getElementById("footerView").style.display = "none";
                                                                                document.getElementById("historyBtn").click();
                                                                                $.ajax({
                                                                                    type: "GET",
                                                                                    url: "todo_backend.php",
                                                                                    data: {
                                                                                        "srid": srid
                                                                                    },
                                                                                    dataType: "html",
                                                                                    beforeSend: function() {
                                                                                        $('#history_content').html(
                                                                                            '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                        );
                                                                                    },
                                                                                    success: function(data) {
                                                                                        setTimeout(function() {
                                                                                            $('#history_content').html(data);
                                                                                        }, 2000);

                                                                                    }
                                                                                });
                                                                            };
                                                                        </script>
                                                                        <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                        <input id="item<?php echo $srid; ?>" value="<?php echo $serv['items']; ?>" style="display: none;">
                                                                        <input id="compt<?php echo $srid; ?>" value="<?php echo $serv['complaints']; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section><?php
                                                    }
                                                    /*Completed Service*/

                                                    if ($row['utype'] == "Management") {/*Services Details Management*/
                                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="warning">Services Details <label class="warning">( <label class="success">Completed</label> )</label></h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="cctodoTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>BRANCH</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $service = mysqli_query($con, "SELECT * FROM service WHERE stat='4' OR stat='5'");
                                                            foreach ($service as $serv) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $serv['dates']; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['brid'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['srcr'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $serv['items']; ?></td>
                                                                    <td><?php echo $serv['complaints']; ?></td>
                                                                    <?php
                                                                    $srid = $serv['srid'];
                                                                    $servdt = mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                                                                    $servct = mysqli_num_rows($servdt);
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 4) {
                                                                        ?>
                                                                            <div class="chip chip-primary">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing Completed</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 5) {
                                                                        ?>
                                                                            <div class="chip chip-success">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Completed</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 4) {
                                                                        ?>
                                                                            <a title="Create Invoice." id="save<?php echo $srid; ?>"><i class="feather icon-save"></i></a>
                                                                            <a title="More Details." id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <script text="javascript">
                                                                                document.getElementById("save<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceaddSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceaddFrom").submit();
                                                                                };
                                                                                document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                    var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("footerView").style.display = "none";
                                                                                    document.getElementById("secondassign").style.display = "none";
                                                                                    document.getElementById("historyBtn").click();
                                                                                    $.ajax({
                                                                                        type: "GET",
                                                                                        url: "todo_backend.php",
                                                                                        data: {
                                                                                            "srid": srid
                                                                                        },
                                                                                        dataType: "html",
                                                                                        beforeSend: function() {
                                                                                            $('#history_content').html(
                                                                                                '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                            );
                                                                                        },
                                                                                        success: function(data) {
                                                                                            setTimeout(function() {
                                                                                                $('#history_content').html(data);
                                                                                            }, 2000);

                                                                                        }
                                                                                    });
                                                                                };
                                                                            </script>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 5) {
                                                                        ?>
                                                                            <a title="Inovice" id="inv<?php echo $srid; ?>"><i class="feather icon-file-text"></i></a>
                                                                            <a title="Edit" id="edit<?php echo $srid; ?>"><i class="feather icon-edit"></i></a>
                                                                            <a title="More Details" id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <script text="javascript">
                                                                                document.getElementById("inv<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceinvSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceinvFrom").submit();
                                                                                };
                                                                                document.getElementById("edit<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceeditSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceeditFrom").submit();
                                                                                };
                                                                                document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                    var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("footerView").style.display = "none";
                                                                                    document.getElementById("secondassign").style.display = "none";
                                                                                    document.getElementById("historyBtn").click();
                                                                                    $.ajax({
                                                                                        type: "GET",
                                                                                        url: "todo_backend.php",
                                                                                        data: {
                                                                                            "srid": srid
                                                                                        },
                                                                                        dataType: "html",
                                                                                        beforeSend: function() {
                                                                                            $('#history_content').html(
                                                                                                '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                            );
                                                                                        },
                                                                                        success: function(data) {
                                                                                            setTimeout(function() {
                                                                                                $('#history_content').html(data);
                                                                                            }, 2000);

                                                                                        }
                                                                                    });
                                                                                };
                                                                            </script>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section><?php
                                                    } else if ($row['utype'] == "Branch Manager") {/*Services Details Management*/
                                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="light">Services Details <label class="light">( <label class="success">Completed</label> )</label></h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="cbtodoTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $service = mysqli_query($con, "SELECT * FROM service WHERE brid='$aid' AND (stat='4' OR stat='5')");
                                                            foreach ($service as $serv) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $serv['dates']; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['srcr'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $serv['items']; ?></td>
                                                                    <td><?php echo $serv['complaints']; ?></td>
                                                                    <?php
                                                                    $srid = $serv['srid'];
                                                                    $servdt = mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                                                                    $servct = mysqli_num_rows($servdt);
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 4) {
                                                                        ?>
                                                                            <div class="chip chip-primary">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing Completed</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 5) {
                                                                        ?>
                                                                            <div class="chip chip-success">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Completed</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 4) {
                                                                        ?>
                                                                            <a title="Create Invoice." id="save<?php echo $srid; ?>"><i class="feather icon-save"></i></a>
                                                                            <a title="More Details." id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <script text="javascript">
                                                                                document.getElementById("save<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceaddSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceaddFrom").submit();
                                                                                };
                                                                                document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                    var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("footerView").style.display = "none";
                                                                                    document.getElementById("secondassign").style.display = "none";
                                                                                    document.getElementById("historyBtn").click();
                                                                                    $.ajax({
                                                                                        type: "GET",
                                                                                        url: "todo_backend.php",
                                                                                        data: {
                                                                                            "srid": srid
                                                                                        },
                                                                                        dataType: "html",
                                                                                        beforeSend: function() {
                                                                                            $('#history_content').html(
                                                                                                '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                            );
                                                                                        },
                                                                                        success: function(data) {
                                                                                            setTimeout(function() {
                                                                                                $('#history_content').html(data);
                                                                                            }, 2000);

                                                                                        }
                                                                                    });
                                                                                };
                                                                            </script>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 5) {
                                                                        ?>
                                                                            <a title="Inovice" id="inv<?php echo $srid; ?>"><i class="feather icon-file-text"></i></a>
                                                                            <a title="Edit" id="edit<?php echo $srid; ?>"><i class="feather icon-edit"></i></a>
                                                                            <a title="More Details" id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <script text="javascript">
                                                                                document.getElementById("inv<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceinvSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceinvFrom").submit();
                                                                                };
                                                                                document.getElementById("edit<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceeditSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceeditFrom").submit();
                                                                                };
                                                                                document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                    var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("footerView").style.display = "none";
                                                                                    document.getElementById("secondassign").style.display = "none";
                                                                                    document.getElementById("historyBtn").click();
                                                                                    $.ajax({
                                                                                        type: "GET",
                                                                                        url: "todo_backend.php",
                                                                                        data: {
                                                                                            "srid": srid
                                                                                        },
                                                                                        dataType: "html",
                                                                                        beforeSend: function() {
                                                                                            $('#history_content').html(
                                                                                                '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                            );
                                                                                        },
                                                                                        success: function(data) {
                                                                                            setTimeout(function() {
                                                                                                $('#history_content').html(data);
                                                                                            }, 2000);

                                                                                        }
                                                                                    });
                                                                                };
                                                                            </script>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section><?php
                                                    } else {/*Services Details Management*/
                                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="light">Services Details <label class="light">( <label class="success">Completed</label> )</label></h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="cbtodoTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $service = mysqli_query($con, "SELECT * FROM service WHERE srcr='$aid' AND (stat='4' OR stat='5')");
                                                            foreach ($service as $serv) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $serv['dates']; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['srcr'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $serv['items']; ?></td>
                                                                    <td><?php echo $serv['complaints']; ?></td>
                                                                    <?php
                                                                    $srid = $serv['srid'];
                                                                    $servdt = mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                                                                    $servct = mysqli_num_rows($servdt);
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 4) {
                                                                        ?>
                                                                            <div class="chip chip-primary">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Servicing Completed</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 5) {
                                                                        ?>
                                                                            <div class="chip chip-success">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Completed</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 4) {
                                                                        ?>
                                                                            <a title="Create Invoice." id="save<?php echo $srid; ?>"><i class="feather icon-save"></i></a>
                                                                            <a title="More Details." id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <script text="javascript">
                                                                                document.getElementById("save<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceaddSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceaddFrom").submit();
                                                                                };
                                                                                document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                    var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("footerView").style.display = "none";
                                                                                    document.getElementById("secondassign").style.display = "none";
                                                                                    document.getElementById("historyBtn").click();
                                                                                    $.ajax({
                                                                                        type: "GET",
                                                                                        url: "todo_backend.php",
                                                                                        data: {
                                                                                            "srid": srid
                                                                                        },
                                                                                        dataType: "html",
                                                                                        beforeSend: function() {
                                                                                            $('#history_content').html(
                                                                                                '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                            );
                                                                                        },
                                                                                        success: function(data) {
                                                                                            setTimeout(function() {
                                                                                                $('#history_content').html(data);
                                                                                            }, 2000);

                                                                                        }
                                                                                    });
                                                                                };
                                                                            </script>
                                                                        <?php
                                                                        } else if ($serv['stat'] == 5) {
                                                                        ?>
                                                                            <a title="Inovice" id="inv<?php echo $srid; ?>"><i class="feather icon-file-text"></i></a>
                                                                            <a title="Edit" id="edit<?php echo $srid; ?>"><i class="feather icon-edit"></i></a>
                                                                            <a title="More Details" id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                            <script text="javascript">
                                                                                document.getElementById("inv<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceinvSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceinvFrom").submit();
                                                                                };
                                                                                document.getElementById("edit<?php echo $srid; ?>").onclick = function() {
                                                                                    document.getElementById("serviceeditSrid").value = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("serviceeditFrom").submit();
                                                                                };
                                                                                document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                    var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                    document.getElementById("footerView").style.display = "none";
                                                                                    document.getElementById("secondassign").style.display = "none";
                                                                                    document.getElementById("historyBtn").click();
                                                                                    $.ajax({
                                                                                        type: "GET",
                                                                                        url: "todo_backend.php",
                                                                                        data: {
                                                                                            "srid": srid
                                                                                        },
                                                                                        dataType: "html",
                                                                                        beforeSend: function() {
                                                                                            $('#history_content').html(
                                                                                                '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                            );
                                                                                        },
                                                                                        success: function(data) {
                                                                                            setTimeout(function() {
                                                                                                $('#history_content').html(data);
                                                                                            }, 2000);

                                                                                        }
                                                                                    });
                                                                                };
                                                                            </script>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section><?php
                                                    }
                                                        ?>
                                        <form action="service_invoice_add.php" method="POST" id="serviceaddFrom">
                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                            <input id="serviceaddSrid" name="srid" style="display: none;">
                                        </form>
                                        <form action="service_invoice_edit.php" method="POST" id="serviceeditFrom">
                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                            <input id="serviceeditSrid" name="srid" style="display: none;">
                                        </form>
                                        <form action="service_invoice.php" method="POST" id="serviceinvFrom">
                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                            <input id="serviceinvSrid" name="serviceId" style="display: none;">
                                        </form>
                                        <?php
                                        /*Denied Service*/

                                        if ($row['utype'] == "Management") {/*Services Details Management*/
                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="light">Services Details <label class="light">( <label class="danger">Denied</label> )</label></h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="dctodoTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>BRANCH</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $service = mysqli_query($con, "SELECT * FROM service WHERE stat='6'");
                                                            foreach ($service as $serv) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $serv['dates']; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['brid'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['srcr'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $serv['items']; ?></td>
                                                                    <td><?php echo $serv['complaints']; ?></td>
                                                                    <?php
                                                                    $srid = $serv['srid'];
                                                                    $servdt = mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                                                                    $servct = mysqli_num_rows($servdt);
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 6) {
                                                                        ?>
                                                                            <div class="chip chip-danger">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Deined</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <a title="More Details." id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                        <script text="javascript">
                                                                            document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                document.getElementById("secondassign").style.display = "none";
                                                                                document.getElementById("footerView").style.display = "none";
                                                                                document.getElementById("historyBtn").click();
                                                                                $.ajax({
                                                                                    type: "GET",
                                                                                    url: "todo_backend.php",
                                                                                    data: {
                                                                                        "srid": srid
                                                                                    },
                                                                                    dataType: "html",
                                                                                    beforeSend: function() {
                                                                                        $('#history_content').html(
                                                                                            '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                        );
                                                                                    },
                                                                                    success: function(data) {
                                                                                        setTimeout(function() {
                                                                                            $('#history_content').html(data);
                                                                                        }, 2000);

                                                                                    }
                                                                                });
                                                                            };
                                                                        </script>
                                                                        <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section><?php
                                                    } else if ($row['utype'] == "Branch Manager") {/*Services Details Management*/
                                                        ?>
                                            <div class="my-2"></div>
                                            <div class="col-12">
                                                <h4 class="light">Services Details <label class="light">( <label class="danger">Denied</label> )</label></h4>
                                                <hr>
                                            </div>
                                            <section class="data-list-view-header">
                                                <div class="table-responsive">
                                                    <table id="dbtodoTab" class="table table-striped dataex-html5-selectors">
                                                        <thead>
                                                            <tr>
                                                                <th>DATE</th>
                                                                <th>ASSIST</th>
                                                                <th>ITEM</th>
                                                                <th>COMPLAINT</th>
                                                                <th>STATUS</th>
                                                                <th>ACTION</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $service = mysqli_query($con, "SELECT * FROM service WHERE brid='$aid' AND stat='6'");
                                                            foreach ($service as $serv) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $serv['dates']; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $brids = $serv['srcr'];
                                                                        $branch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$brids'"));
                                                                        echo $branch['fname'] . " " . $branch['lname'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo $serv['items']; ?></td>
                                                                    <td><?php echo $serv['complaints']; ?></td>
                                                                    <?php
                                                                    $srid = $serv['srid'];
                                                                    $servdt = mysqli_query($con, "SELECT * FROM servicedt WHERE srid='$srid'");
                                                                    $servct = mysqli_num_rows($servdt);
                                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($serv['stat'] == 6) {
                                                                        ?>
                                                                            <div class="chip chip-danger">
                                                                                <div class="chip-body">
                                                                                    <div class="chip-text">Deined</div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <a title="More Details." id="open<?php echo $srid; ?>"><i class="fas fa-history"></i></a>
                                                                        <script text="javascript">
                                                                            document.getElementById("open<?php echo $srid; ?>").onclick = function() {
                                                                                var srid = document.getElementById("srid<?php echo $srid; ?>").value;
                                                                                document.getElementById("secondassign").style.display = "none";
                                                                                document.getElementById("footerView").style.display = "none";
                                                                                document.getElementById("historyBtn").click();
                                                                                $.ajax({
                                                                                    type: "GET",
                                                                                    url: "todo_backend.php",
                                                                                    data: {
                                                                                        "srid": srid
                                                                                    },
                                                                                    dataType: "html",
                                                                                    beforeSend: function() {
                                                                                        $('#history_content').html(
                                                                                            '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                                                        );
                                                                                    },
                                                                                    success: function(data) {
                                                                                        setTimeout(function() {
                                                                                            $('#history_content').html(data);
                                                                                        }, 2000);

                                                                                    }
                                                                                });
                                                                            };
                                                                        </script>
                                                                        <input id="srid<?php echo $srid; ?>" value="<?php echo $srid; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section><?php
                                                    }
                                                        ?>
                                        <button data-toggle="modal" data-target="#historyTab" id="historyBtn" style="display: none;">
                                    </div>
                                    <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
                                        <section>
                                            <div class="float-right">
                                                <button type="sumbit" data-toggle="modal" data-target="#custCrt" class="btn btn-outline-success round mr-30">Create Customer</button>
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
                                                    <tbody><?php
                                                            $mtd = mysqli_query($con, "SELECT * FROM customer");
                                                            if (mysqli_num_rows($mtd) > 0) {
                                                                foreach ($mtd as $dtm) {
                                                            ?>
                                                                <tr>
                                                                    <td><input type="text" class="form-control round" value="<?php echo $dtm['cname']; ?>" id="cname<?php echo $dtm['cid']; ?>">
                                                                        <div style="display: none;"><?php echo $dtm['cname']; ?>" id="cname<?php echo $dtm['cid']; ?></div>
                                                                    </td>
                                                                    <td><input type="text" class="form-control round" value="<?php echo $dtm['cemail']; ?>" id="cemail<?php echo $dtm['cid']; ?>">
                                                                        <div style="display: none;"><?php echo $dtm['cemail']; ?></div>
                                                                    </td>
                                                                    <td><input type="text" class="form-control round" value="<?php echo $dtm['cphone']; ?>" id="cphone<?php echo $dtm['cid']; ?>">
                                                                        <div style="display: none;"><?php echo $dtm['cphone']; ?></div>
                                                                    </td>
                                                                    <td>
                                                                        <a title="Save" onclick="savebtn<?php echo $dtm['cid']; ?>()"><i class="feather icon-save"></i></a>
                                                                        <input type="text" class="form-control round" value="<?php echo $dtm['cid']; ?>" id="cid<?php echo $dtm['cid']; ?>" style="display: none;">
                                                                    </td>
                                                                </tr>
                                                                <script text="javascript">
                                                                    function savebtn<?php echo $dtm['cid']; ?>() {
                                                                        document.getElementById("cname").value = document.getElementById("cname<?php echo $dtm['cid']; ?>").value;
                                                                        document.getElementById("cemail").value = document.getElementById("cemail<?php echo $dtm['cid']; ?>").value;
                                                                        document.getElementById("cphone").value = document.getElementById("cphone<?php echo $dtm['cid']; ?>").value;
                                                                        document.getElementById("cid").value = document.getElementById("cid<?php echo $dtm['cid']; ?>").value;
                                                                        document.getElementById("csaveBtn").click();
                                                                    }
                                                                </script>
                                                        <?php
                                                                }
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </section>
                                        <form action="#" method="POST">
                                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                                            <input id="cid" name="cid" style="display: none;">
                                            <input id="cname" name="cname" style="display: none;">
                                            <input id="cemail" name="cemail" style="display: none;">
                                            <input id="cphone" name="cphone" style="display: none;">
                                            <input type="submit" id="csaveBtn" name="csaveBtn" style="display: none;" value="Submit">
                                        </form>
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
                                                        $ttd = mysqli_query($con, "SELECT * FROM customer");
                                                        if (mysqli_num_rows($ttd) > 0) {
                                                            foreach ($ttd as $ddt) { ?>
                                                                <tr>
                                                                    <td><?php echo $ddt['cname'] ?></td>
                                                                    <?php
                                                                    $cid = $ddt['cid'];
                                                                    $htk = mysqli_query($con, "SELECT * FROM custcredit WHERE cid='$cid'");
                                                                    $credit = 0;
                                                                    $debit = 0;
                                                                    if (mysqli_num_rows($htk) > 0) {
                                                                        foreach ($htk as $kth) {
                                                                            $credit = $credit + intval($kth['credit']);
                                                                            $debit = $debit + intval($kth['debit']);
                                                                        }
                                                                    }
                                                                    $total = $credit - $debit;
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
    <div class="modal fade text-left show" id="addToDo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" style="display: none;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create To Do</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <section>
                                    <div class="col-12">
                                        <h6 class="light">Customer Details</h6>
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="vs-radio-con">
                                                <input type="radio" name="myRadios" id="myRadios" onclick="radChange1()" value="1" />
                                                <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                                Existing Customer
                                            </div>
                                            <div class="form-group pl-2" id="extvedorForm" style="display: none">
                                                <div class="my-2"></div>
                                                <select class="form-control col-12" name="cid" required data-validation-required-message="This field is required">
                                                    <option selected>Select</option>
                                                    <?php
                                                    $lfe = mysqli_query($con, "SELECT * FROM customer");
                                                    if (mysqli_num_rows($lfe)) {
                                                        foreach ($lfe as $lfd) {
                                                    ?><option value="<?php echo $lfd['cid']; ?>"><?php echo $lfd['cname']; ?></option><?php
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                        ?>
                                                </select>
                                            </div>
                                            <div class="my-2"></div>
                                            <div class="vs-radio-con">
                                                <input type="radio" name="myRadios" id="myRadios" onclick="radChange2()" value="2" />
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
                                                        <input type="text" class="form-control col-12" placeholder="Name" name="cname" data-validation-required-message="This name field is required">
                                                    </div>
                                                </div>
                                                <div class="controls">
                                                    <label>Address</label>
                                                    <div class="form-group">
                                                        <fieldset class="form-group">
                                                            <textarea class="form-control col-12" name="cads" rows="1" placeholder="Address..." data-validation-required-message="This address field is required"></textarea>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="controls">
                                                    <label>Email</label>
                                                    <div class="form-group">
                                                        <input type="email" class="form-control col-12" placeholder="Email" name="cemail" data-validation-required-message="This email field is required">
                                                    </div>
                                                </div>
                                                <div class="controls">
                                                    <label>Phone Number</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control col-12" placeholder="Phone Number" name="cphone" data-validation-required-message="This phone number field is required">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="my-2"></div>
                                            <input type="text" name="name" id="formselector" style="display: none;">
                                        </div>
                                </section>
                            </div>
                            <div class="col-6">
                                <section>
                                    <div class="my-2"></div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="col-12">
                                                <h6 class="light">Branch Details</h6>
                                                <hr>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <select class="form-control col-12" name="brid" required data-validation-required-message="This field is required">
                                                            <option value="" selected>Select</option>
                                                            <?php
                                                            if ($row['utype'] == "Management") {
                                                                $dgt = mysqli_query($con, "SELECT * FROM actdetails WHERE utype='Branch Manager'");
                                                                if (mysqli_num_rows($dgt)) {
                                                                    foreach ($dgt as $tgd) {
                                                            ?><option value="<?php echo $tgd['aid']; ?>"><?php echo $tgd['fname'] . " " . $tgd['lname']; ?></option><?php
                                                                                                                                                                }
                                                                                                                                                            }
                                                                                                                                                        } else if ($row['utype'] == "Branch Manager") {
                                                                                                                                                                    ?><option value="<?php echo $row['aid']; ?>"><?php echo $row['fname'] . " " . $row['lname']; ?></option><?php
                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                    $mtd = mysqli_query($con, "SELECT * FROM shop WHERE eid='$aid'");
                                                                                                                                                                                                                                                                    if (mysqli_num_rows($mtd) > 0) {
                                                                                                                                                                                                                                                                        $dtm = mysqli_fetch_array($mtd);
                                                                                                                                                                                                                                                                        $aidds = $dtm['aid'];
                                                                                                                                                                                                                                                                        $dgt = mysqli_query($con, "SELECT * FROM actdetails WHERE aid='$aidds'");
                                                                                                                                                                                                                                                                        if (mysqli_num_rows($dgt)) {
                                                                                                                                                                                                                                                                            foreach ($dgt as $tgd) {
                                                                                                                                                                                                                                                                    ?><option value="<?php echo $tgd['aid']; ?>"><?php echo $tgd['fname'] . " " . $tgd['lname']; ?></option><?php
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
                                </section>
                                <section>
                                    <div class="col-12">
                                        <h6 class="light">Service Details</h6>
                                        <hr>
                                    </div>
                                    <div class="controls">
                                        <label>Date</label>
                                        <div class="form-group">
                                            <input type="date" id="dates" name="dates" class="form-control">
                                        </div>
                                    </div>
                                    <div class="controls">
                                        <label>Item Details</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Item details" name="items" required>
                                        </div>
                                    </div>
                                    <div class="controls">
                                        <label>Complaint Details</label>
                                        <div class="form-group">
                                            <fieldset class="form-group">
                                                <textarea class="form-control" name="complaints" rows="3" placeholder="Complaint details..." required></textarea>
                                            </fieldset>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        <button type="sumbit" name="sevicecrtBtn" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function radChange1() {
            document.getElementById("formselector").name = "existingcustomer";
            document.getElementById("extvedorForm").style.display = "block";
            document.getElementById("newvedorForm").style.display = "none";
        };

        function radChange2() {
            document.getElementById("formselector").name = "newcustomer";
            document.getElementById("extvedorForm").style.display = "none";
            document.getElementById("newvedorForm").style.display = "block";
        };
    </script>
    <div class="modal fade text-left show" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" style="display: none;" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel17">Customer Detailed Report</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <section id="selectorVid">
                        <div class="form-group">
                            <label>Select Customer:</label>
                            <select class="form-control" id="ccid" required data-validation-required-message="This field is required">
                                <option id="" value="" selected>Choose</option>
                                <?php
                                $lfe = mysqli_query($con, "SELECT * FROM customer");
                                if (mysqli_num_rows($lfe)) {
                                    foreach ($lfe as $lfd) { ?>
                                        <option value="<?php echo $lfd['cid']; ?>"><?php echo $lfd['cname']; ?></option>
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
                        var cid = document.getElementById("ccid").value;
                        var startdate = document.getElementById("startDate").value;
                        var enddate = document.getElementById("endDate").value;
                        $.ajax({
                            type: "GET",
                            url: "sales_backend.php",
                            data: {
                                "cid": cid,
                                "startdate": startdate,
                                "enddate": enddate
                            },
                            dataType: "html",
                            beforeSend: function() {
                                $('#table-container').html(
                                    '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                );
                            },
                            success: function(data) {
                                setTimeout(function() {
                                    $('#table-container').html(data);
                                }, 2000);

                            }
                        });
                        document.getElementById("vendorSearchTab").style.display = "block";
                    };
                    document.getElementById("vendorSearchbtn").onclick = function() {
                        document.getElementById("ccid").value = "";
                        document.getElementById("startDate").value = "";
                        document.getElementById("endDate").value = "";
                        document.getElementById("selectorVid").style.display = "block";
                        document.getElementById("searchfooter").style.display = "block";
                        document.getElementById("vendorSearchTab").style.display = "none";
                    };
                </script>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="custCrt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Customer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST">
                    <div class="modal-body">
                        <div class="controls">
                            <label>Name</label>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Name" name="cname" required data-validation-required-message="This name field is required">
                            </div>
                        </div>
                        <div class="controls">
                            <label>Address</label>
                            <div class="form-group">
                                <fieldset class="form-group">
                                    <textarea class="form-control" name="cads" rows="3" placeholder="Address..." required data-validation-required-message="This address field is required"></textarea>
                                </fieldset>
                            </div>
                        </div>
                        <div class="controls">
                            <label>Email</label>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email" name="cemail" required data-validation-required-message="This email field is required">
                            </div>
                        </div>
                        <div class="controls">
                            <label>Phone Number</label>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Phone Number" name="cphone" required data-validation-required-message="This phone number field is required">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        <button type="sumbit" name="custcrtBtn" class="btn btn-success">Submit</button>
                    </div>
                </form>
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
                                    $lfe = mysqli_query($con, "SELECT * FROM customer");
                                    if (mysqli_num_rows($lfe)) {
                                        foreach ($lfe as $lfd) { ?>
                                            <option value="<?php echo $lfd['cid']; ?>"><?php echo $lfd['cname']; ?></option>
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
                    <div class="modal-footer">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        <button type="sumbit" name="debitcreate" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="transfer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Service Manage</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST">
                    <div class="modal-body">
                        <div class="controls">
                            <label>Choose:</label>
                            <div class="form-group">
                                <select class="form-control" onchange="viewchanger()" name="viewchagerno" id="viewchagerno" required>
                                    <option value="" selected>Select</option>
                                    <option value="0">Service Completed.</option>
                                    <option value="1">Add Servicer.</option>
                                    <option value="2">I can not fix it.</option>
                                </select>
                            </div>
                        </div>
                        <div class="controls">
                            <label>Servicing details:</label>
                            <div class="form-group">
                                <fieldset class="form-group">
                                    <textarea class="form-control" name="complaints" rows="3" placeholder="Servicing details..." required></textarea>
                                </fieldset>
                            </div>
                        </div>
                        <section id="paymentTab" style="display: none">
                            <div class="controls">
                                <label>Amount</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="amount" placeholder="Service Charge">
                                </div>
                            </div>
                        </section>
                        <section id="newservicers" style="display: none">
                            <div class="controls">
                                <label>Choose servicer:</label>
                                <div class="form-group">
                                    <select class="form-control" name="svraid" required>
                                        <option selected>Select</option>
                                        <?php
                                        $dgt = mysqli_query($con, "SELECT * FROM actdetails WHERE utype!='Invalid' AND utype!='Not authorized' AND utype!='Management'");
                                        if (mysqli_num_rows($dgt) > 0) {
                                            foreach ($dgt as $tgd) {
                                        ?><option value="<?php echo $tgd['aid']; ?>"><?php echo $tgd['fname'] . " " . $tgd['lname']; ?></option><?php
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                                ?>
                                    </select>
                                </div>
                            </div>
                        </section>
                    </div>
                    <script type="text/javascript">
                        function viewchanger() {
                            if (document.getElementById("viewchagerno").value == 1) {
                                document.getElementById("newservicers").style.display = "block";
                                document.getElementById("paymentTab").style.display = "block";
                            } else if (document.getElementById("viewchagerno").value == 0) {
                                document.getElementById("newservicers").style.display = "none";
                                document.getElementById("paymentTab").style.display = "block";
                            } else if (document.getElementById("viewchagerno").value == 2) {
                                document.getElementById("newservicers").style.display = "block";
                                document.getElementById("paymentTab").style.display = "none";
                            } else {
                                document.getElementById("newservicers").style.display = "none";
                                document.getElementById("paymentTab").style.display = "none";
                            }

                        }
                    </script>
                    <div class="modal-footer">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        <input name="srid" id="srid" style="display: none;">
                        <input name="srdid" id="srdid" style="display: none;">
                        <input name="stat" id="stat" style="display: none;">
                        <button type="sumbit" name="pendingTask" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <form action="#" method="POST">
        <div class="modal fade text-left show" id="historyTab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" style="display: none;" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel17">Service History</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <section style="display: none;" id="secondassign">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="light">Find Servicer</h6>
                                            <hr>
                                        </div>
                                        <div class="col-6">
                                            <div class="controls">
                                                <label>Choose Option:</label>
                                                <div class="form-group">
                                                    <select class="form-control" onchange="fetchskilled()" id="skilled">
                                                        <option value="">Select</option>
                                                        <option value="Hardware_Service_PC">Hardware Service (PC)</option>
                                                        <option value="Hardware_Service_Laptop">Hardware Service (Laptop)</option>
                                                        <option value="Chip_Level_Service">Chip Level Service</option>
                                                        <option value="Advanced_Hardware_Service">Advanced Hardware Service</option>
                                                        <option value="Software_Service">Software Service</option>
                                                        <option value="Advanced_Software_Service">Advanced Software Service</option>
                                                        <option value="Surveillance_System_Management">Surveillance System Management</option>
                                                        <option value="Printer_Service">Printer Service</option>
                                                        <option value="Toner_Service">Toner Service</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div id="skilledfetched" style="display: none;"></div>
                                        </div>
                                        <script type="text/javascript">
                                            function fetchskilled() {
                                                var skill = document.getElementById("skilled").value;
                                                document.getElementById("skilledfetched").style.display = "block";
                                                $.ajax({
                                                    type: "GET",
                                                    url: "todo_backend_skill.php",
                                                    data: {
                                                        "skill": skill
                                                    },
                                                    dataType: "html",
                                                    beforeSend: function() {
                                                        $('#skilledfetched').html(
                                                            '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                        );
                                                    },
                                                    success: function(data) {
                                                        setTimeout(function() {
                                                            $('#skilledfetched').html(data);
                                                        }, 2000);

                                                    }
                                                });
                                            };
                                        </script>
                                    </div>
                                </div>
                                <br>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="light">Assign Servicer</h6>
                                            <hr>
                                        </div>
                                        <div class="col-6">
                                            <div class="controls">
                                                <label>Select Servicer:</label>
                                                <div class="form-group">
                                                    <select class="form-control" name="svraid" required>
                                                        <option value="" selected>Select</option>
                                                        <?php
                                                        if ($row['utype'] == "Management") {
                                                            $dgt = mysqli_query($con, "SELECT * FROM actdetails WHERE utype!='Invalid' AND utype!='Not authorized'");
                                                            if (mysqli_num_rows($dgt) > 0) {
                                                                foreach ($dgt as $tgd) {
                                                        ?><option value="<?php echo $tgd['aid']; ?>"><?php echo $tgd['fname'] . " " . $tgd['lname']; ?></option><?php
                                                                                                                                                            }
                                                                                                                                                        }
                                                                                                                                                    } else if ($row['utype'] == "Branch Manager") {
                                                                                                                                                        $dgt = mysqli_query($con, "SELECT * FROM actdetails WHERE utype!='Invalid' AND utype!='Not authorized'");
                                                                                                                                                        if (mysqli_num_rows($dgt) > 0) {
                                                                                                                                                            foreach ($dgt as $tgd) {
                                                                                                                                                                $eid = $tgd['aid'];
                                                                                                                                                                $lmh = mysqli_query($con, "SELECT * FROM shop WHERE eid='$eid' AND aid='$aid'");
                                                                                                                                                                if (mysqli_num_rows($lmh) > 0) {
                                                                                                                                                                ?><option value="<?php echo $tgd['aid']; ?>"><?php echo $tgd['fname'] . " " . $tgd['lname']; ?></option><?php
                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="controls">
                                                <label>Select Method:</label>
                                                <div class="form-group">
                                                    <select class="form-control" name="method" required>
                                                        <option value="" selected>Select</option>
                                                        <option value="0" id="newservicer">Add New Servicer</option>
                                                        <option value="1">Replace Last Servicer</option>
                                                        <option value="2">Delete All Servicer</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <br>
                        <section>
                            <div id="history_content"></div>
                        </section>
                    </div>
                    <div class="modal-footer" style="display: none;" id="footerView">
                        <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                        <input id="assignsrided" name="assignsrided" style="display: none;">
                        <input id="assignsridno" name="assignsridno" style="display: none;">
                        <button type="sumbit" name="assignSred" class="btn btn-success float-right">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="#" method="POST">
        <div class="modal fade text-left show" id="assignTab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" style="display: none;" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel17">Assign Servicer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>ITEMS</th>
                                    </tr>
                                    <tr>
                                        <td id="items"></td>
                                    </tr>
                                    <tr>
                                        <th>COMPLAINTS</th>
                                    </tr>
                                    <tr>
                                        <td id="complaints"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6">
                                <div class="col-12">
                                    <h6 class="light">Find Servicer</h6>
                                    <hr>
                                </div>
                                <select class="form-control" onchange="fetchskill()" id="skill">
                                    <option value="">Select</option>
                                    <option value="Hardware_Service_PC">Hardware Service (PC)</option>
                                    <option value="Hardware_Service_Laptop">Hardware Service (Laptop)</option>
                                    <option value="Chip_Level_Service">Chip Level Service</option>
                                    <option value="Advanced_Hardware_Service">Advanced Hardware Service</option>
                                    <option value="Software_Service">Software Service</option>
                                    <option value="Advanced_Software_Service">Advanced Software Service</option>
                                    <option value="Surveillance_System_Management">Surveillance System Management</option>
                                    <option value="Printer_Service">Printer Service</option>
                                    <option value="Toner_Service">Toner Service</option>
                                </select>
                                <div id="skillfetched" style="display: none;"></div>
                                <div class="my-2">
                                    <div class="col-12">
                                        <h6 class="light">Assign Servicer</h6>
                                        <hr>
                                    </div>
                                    <select class="form-control" name="svraid" required>
                                        <option selected>Select</option>
                                        <?php
                                        if ($row['utype'] == "Management") {
                                            $dgt = mysqli_query($con, "SELECT * FROM actdetails WHERE utype!='Invalid' AND utype!='Not authorized'");
                                            if (mysqli_num_rows($dgt) > 0) {
                                                foreach ($dgt as $tgd) {
                                        ?><option value="<?php echo $tgd['aid']; ?>"><?php echo $tgd['fname'] . " " . $tgd['lname']; ?></option><?php
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    } else if ($row['utype'] == "Branch Manager") {
                                                                                                                                        $dgt = mysqli_query($con, "SELECT * FROM actdetails WHERE utype!='Invalid' AND utype!='Not authorized'");
                                                                                                                                        if (mysqli_num_rows($dgt) > 0) {
                                                                                                                                            foreach ($dgt as $tgd) {
                                                                                                                                                $eid = $tgd['aid'];
                                                                                                                                                $lmh = mysqli_query($con, "SELECT * FROM shop WHERE eid='$eid' AND aid='$aid'");
                                                                                                                                                if (mysqli_num_rows($lmh) > 0) {
                                                                                                                                                ?><option value="<?php echo $tgd['aid']; ?>"><?php echo $tgd['fname'] . " " . $tgd['lname']; ?></option><?php
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                    ?>
                                    </select>
                                    <script type="text/javascript">
                                        function fetchskill() {
                                            var skill = document.getElementById("skill").value;
                                            document.getElementById("skillfetched").style.display = "block";
                                            $.ajax({
                                                type: "GET",
                                                url: "todo_backend_skill.php",
                                                data: {
                                                    "skill": skill
                                                },
                                                dataType: "html",
                                                beforeSend: function() {
                                                    $('#skillfetched').html(
                                                        '<img src="../../img/Loader.gif" width="25" height="25"/>'
                                                    );
                                                },
                                                success: function(data) {
                                                    setTimeout(function() {
                                                        $('#skillfetched').html(data);
                                                    }, 2000);

                                                }
                                            });
                                        };
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input value='<?php echo $aid; ?>' id="userKey" name="userKey" style="display: none;">
                            <input id="assignsrid" name="assignsrid" style="display: none;">
                            <button type="sumbit" name="assignSr" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
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
    <script src="../../js/to_do.js"></script>
    <!-- END: Page JS-->
</body>
<!-- END: Body-->

</html>