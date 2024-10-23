<?php
$aatid=$_GET['text'];
include("../connection.php");
$randomNumber = rand(99999,999999);
$randomNum = rand(99999,999999);
$random= rand(99999,999999);
$output="automated" .$randomNumber ."BPM" .$randomNum ."qrcode" .$random;
$tdt=mysqli_query($con, "UPDATE attkey SET attstr='$output' WHERE aatid='$aatid'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>Automated BPM</title>
    <link rel="icon" href="../../img/logo.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <style>
        body, html {
            height: 100%;
            width: 100%;
        }
        .bg {
            background-image: url("images/bg.jpg");
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .counter{            
            display: inline-block;
        }
    </style>
</head>
<body class="bg">
    <div class="container" id="panel">
        <br><br><br>
        <div class="row">
            <div class="col-md-6 offset-md-3" style="background-color: white; padding: 20px; box-shadow: 10px 10px 5px #888;">
                <div class="panel-heading">
                    <h3 style="text-align: center;">Automated BPM | Attendance System</h3>
                </div>
                <hr>                
                <div id="qrbox" style="text-align: center;">
                <img src="generate.php?text=<?php echo $output;?>" alt="">
                </div>
                <hr>
                <div id="qrbox" style="text-align: center;">
                    <h1 class="counter">00:<h1 id="countdown" class="counter">20</h1>
                </div>                
            </div>
        </div>
    </div>
    <form action="indexer.php" method="POST"  style="display: none;">
        <input type="text" name="aatid" value="<?php echo $aatid;?>" style="display: none;">
        <input type="text" name="existingcustomer" value="existingcustomer" style="display: none;">   
        <input type="submit" name="machineBtn" id="machineBtn" class="btn btn-md btn-danger btn-block" value="Submit">
    </form>
    <script type="text/javascript">
    $(document).ready(function() {
        var seconds = document.getElementById("countdown").textContent;
        var countdown = setInterval(function() {
            seconds--;
            document.getElementById("countdown").textContent = seconds;
            if (seconds <= 0) {
                document.getElementById("machineBtn").click();
            };
        }, 1000);
        });
    </script>
</body>
</html>