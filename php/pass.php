<?php

$email=$_POST['varemail'];
$name=$_POST['varname'];
$pass=$_POST['pass'];
$cpass=$_POST['cpass'];
$id=$_POST['varid'];
$flag=0;
function checker(){    
        if($GLOBALS['id']=="1")
        {
            include("connection.php");
            $email=$GLOBALS['email'];
            $cpass=$GLOBALS['cpass']; 
            $qry = "INSERT INTO account (email, pass,stat) VALUES ('$email', '$cpass','created')";        
            if(mysqli_query($con, $qry))
                return 1;        
            else
                return 0;        
        }
        else if($GLOBALS['id']=="2"){
            include("connection.php");
            $email=$GLOBALS['email'];
            $cpass=$GLOBALS['cpass'];        
            $query = "UPDATE account SET pass='$cpass' WHERE email='$email'";        
            if(mysqli_query($con, $query))
                return 2;
            else
                return 0;
        }
        else
            return 0;
}
 
?>
<html>
<head>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>   
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
<link rel="icon" href="../img/logo.png">
<link rel="stylesheet" href="../style/loader.css" />
<link rel="stylesheet" href="../style/otp.css" />
<link rel="stylesheet" href="../style/index.css" />
<title>Automated BPM-Password Interface</title>
</head>
<body onload="myFunction()">
<div id="loader" class="loader_bg">
      <div id="box"></div>
      <div id="hill"></div>
    </div>
    <form action="info.php" method="POST" style="display: none;" id="loginFrom">
    <input type="text" value="<?php echo $email; ?>" name="lemail" style="display: none;">
    <input type="text" value="<?php echo $cpass; ?>" name="lpass" style="display: none;">
    </form>
<script type="text/javascript">
    function myFunction(){
        var y='<?php echo checker(); ?>';                                 
                if(y==1)
                Swal.fire({
                    icon: 'success',
                    title: "Well Done!",
                    text: "Account created successfully.",                                      
                    showCancelButton: false        
                }).then((result) => {
                    delete result;
                    Swal.fire({
                    title: 'Continue as:',
                    text: '<?php echo $email; ?>',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {                    
                    if (result.isConfirmed) {
                        document.getElementById("loginFrom").submit();
                    } else{
                        window.location.href="../index.html", '_blank';
                    }
                    });
                });
                else if(y==2)
                Swal.fire({
                    icon: 'success',
                    title: "Well Done!",
                    text: "Password updated successfully.",                                      
                    showCancelButton: false        
                }).then((result) => {
                    delete result;
                    Swal.fire({
                    title: 'Continue as:',
                    text: '<?php echo $email; ?>',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {                    
                    if (result.isConfirmed) {
                        document.getElementById("loginFrom").submit();
                    } else{
                        window.location.href="../index.html", '_blank';
                    }
                    });
                });                
                else
                Swal.fire({
                    icon: 'error',
                    title: "Oops...",
                    text: "Something went wrong.",                                      
                    showCancelButton: false        
                }).then((result) => {
                    window.location.href="../index.html", '_blank';
                });

    }

</script>
</body>
</html>
