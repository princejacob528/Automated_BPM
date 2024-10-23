<?php
    session_start();
    include("connection.php");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    require'PHPMailer/Exception.php';
    require'PHPMailer/PHPMailer.php';
    require'PHPMailer/SMTP.php';
    date_default_timezone_set('Etc/UTC');    
    $email=$_POST['email'];
    $name=$_POST['name'];
    $id=$_POST['id'];
    $heading="Welcome...";
    if($_POST['id']=="2"){
        $heading="FORGOT YOUR PASSWORD?";
    }
    $randomNumber = rand(99999,999999);   
    $page = file_get_contents('../html/OTP.html');  
    $page = str_replace('{heading}',$heading, $page);    
    $page = str_replace('{fname}', $name, $page);    
    $page = str_replace('{otp}',$randomNumber, $page); 
    $flag=0;
    $query = "select * from account where email = '$email' ";
	$result = mysqli_query($con, $query);
    if($result)
	{
		if($result && mysqli_num_rows($result) > 0)
		{
            $GLOBALS['flag']=3;
        }        
    }
    if ($_POST['id']=="2") {
        if( $GLOBALS['flag']==3){
            $GLOBALS['flag']=1;
            mailer();
        }else
        {
            $GLOBALS['flag']=4; 
        }
    }
    else
    {
        if( $GLOBALS['flag']==3){
            $GLOBALS['flag']=3;
        }
        else{
            $GLOBALS['flag']=1;
            mailer();
        }
    }
    function mailer(){    
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
        $mail->setFrom('princedeveloper24@gmail.com', 'PrinceDev Mailer');
        $mail->addAddress($GLOBALS['email'], $GLOBALS['name']); // Add a recipient
    
        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Automated BPM - OTP Validation';
        $mail->Body =$GLOBALS['page'];
        
    
        /* Attachement 
        $mail->addAttachment('upload/file.pdf');
        $mail->addAttachment('upload/image.png', 'image 1');*/    // Optional name
                
        if($mail->send()){
            $GLOBALS['flag']=2;
        }        
    }   
?>
<html>
<head>
<script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>    
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="icon" href="../img/logo.png">
<link rel="stylesheet" href="../style/loader.css" />
<link rel="stylesheet" href="../style/otp.css" />
<link rel="stylesheet" href="../style/index.css" />
<title>Automated BPM-Password Interface</title>
</head>
<body>
    <div id="loader" class="loader_bg">
      <div id="box"></div>
      <div id="hill"></div>
    </div>
<button action="#" class="button">Click Here</button>
  <div id="my-modal" class="modal">
    <div class="modal-content">      
      <div class="modal-body">
        <form action="pass.php" method="POST" class="sign-in-form" id="pass_form">
            <br>
            <h2 class="title">Set Password</h2> 
            <br>           
            <div class="input-field">
                <i class="fas fa-key"></i>
                <input type="password" placeholder="Password" id="newPassword" name="pass" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters." required>
            </div>
            <div class="input-field">
                <i class="fas fa-key"></i>
                <input type="password" placeholder="Confirm Password" id="conPassword" name="cpass" title="Enter same as password." required>
            </div>
            <table width=100% class="social-text" style="margin-right: 50%;">
                <tr>
                  <td><a><input type="checkbox" onclick="passShower()"> Show Password</a></td>
                </tr>
              </table>
              <table width=100% class="social-text">
                <tr>
                  <td><input type="button" class="btn" value="Cancel" onclick="closeModal()" /></td>
                  <td><input type="button" class="btn" value="Submit" id="submitbtn" /></td>
                </tr>
              </table>          
            
            <p class="social-text">            
            <a href="https://automatedbusiness.000webhostapp.com/">Automated Business Process Management System.</a>
            <br><br>Final Year Project, BTech, CSE.<br>
            <a href="http://jaibharathengg.com/">Jai Bharath College Of Management And Engineering Technology </a>
            </p>
            <input value='<?php echo $name; ?>' id="varname" name="varname" style="display: none;">
            <input value='<?php echo $email; ?>' id="varemail" name="varemail" style="display: none;">
            <input value='<?php echo $id; ?>' id="varid" name="varid" style="display: none;">
          </form>
      </div>      
    </div>
  </div>
<script type="text/javascript">
    
        $(document).ready(function() { 
            $(".loader_bg").fadeOut("slow");
            myFunction();
        });
        var x =document.getElementById("newPassword"),
            y = document.getElementById("conPassword");
        function myFunction(){            
            if('<?php echo $flag ;?>'=="2"){
                delete result;            
                Swal.fire({
                    title: "OTP Generated.",
                    text: "Enter OTP sent to "+'<?php echo $email; ?>'+":",
                    input: 'password',                
                    showCancelButton: true        
                }).then((result) => {
                    if (result.value==<?php echo $randomNumber; ?>){
                        delete result;
                        Swal.fire({
                            icon: 'success',
                            title: 'Well done',
                            text: 'OTP Verified Successfully.',                        
                        }).then((result) => {
                            if (result.value){                                
                                openModal();
                            }
                        });                                                                                                              
                    }
                    else{
                        delete result;
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'OTP Invaild!',                        
                        }).then((result) => {
                            if (result.value){                                
                                closeModal();
                            }
                        });
                    }
                });
            }
            else if('<?php echo $flag ;?>'=="3"){                
                Swal.fire({
                            icon: 'error',
                            title: 'Account Exist',
                            text: 'Your entered email address was already used.',                        
                }).then((result) => {
                    if (result.value){
                        closeModal();
                    }
                });                
            }          
            else if('<?php echo $flag ;?>'=="1"){                
                Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'Mailer is busy, Please contact our technical team.',                        
                        }).then((result) => {
                            if (result.value){                                
                                closeModal();
                            }
                        });                
            }
            else if('<?php echo $flag ;?>'=="4"){
                Swal.fire({
                            icon: 'error',
                            title: 'Invalid Account',
                            text: 'Entered email address does not Exist.',                        
                        }).then((result) => {
                            if (result.value){                                
                                closeModal();
                            }
                        });
            }
            else{                
                Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong..',                                                    
                        }).then((result) => {
                            if (result.value){                                
                                closeModal();
                            }
                        });                
            } 
            
        }        
        document.getElementById("submitbtn").onclick = function(){
                var pas =x.value,
                cpas = y.value;
                var passw=  /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
                if(x.value.match(passw)){                
                    if(pas != cpas){
                        Swal.fire({
                                icon: 'warning',
                                title:'Oops...',
                                text: 'The password and confirmation password do not match.',                                                  
                        });
                    }
                    else{
                        document.getElementById("submitbtn").type="submit";
                        document.getElementById("submitbtn").click();
                    }
                }
                else{
                    Swal.fire({
                                icon: 'warning',
                                title:'Oops...',
                                html: "<h3>Password must contain the following:</h3><br><p>A <b>lowercase</b> letter, A <b>capital (uppercase)</b> letter, A <b>number</b>, Minimum <b>8 characters</b>.</p>",                                                  
                        });
                }
        }
        function passShower(){
            if(x.type =="text"){
                x.type="password";                
            }
            else{
                x.type="text";                
            }
        }
</script>
<script src="../js/otp.js"></script>
</body>
</html>
