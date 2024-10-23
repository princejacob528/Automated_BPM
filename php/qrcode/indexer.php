<?php
include("../connection.php");
$aatid=0;
$check=0;
if(isset($_POST['newcustomer'])){    
    $attname=$_POST['attname'];
    $checker=mysqli_query($con,"SELECT * FROM attkey WHERE attname='$attname'");
    if(!mysqli_num_rows($checker)>0){
        $attnames=mysqli_query($con, "INSERT INTO attkey (attname, attstr) VALUES ('$attname', '')");
        $attnames=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM attkey WHERE attname='$attname'"));
        $aatid=$attnames['aatid'];
    }else{
        $check=1;
    }
}else if(isset($_POST['existingcustomer'])){
    $aatid=$_POST['aatid'];
}
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
    </style>
</head>
<body class="bg">
    <div class="container" id="panel">
        <br><br><br>
        <div class="row">
            <div class="col-md-6 offset-md-3" style="background-color: white; padding: 20px; box-shadow: 10px 10px 5px #888;">
                <div class="panel-heading">
                    <h1>Automated BPM QR Code Gateway</h1>
                </div>
                <hr>
                <form action="#" method="POST" id="machinecre" style="display: none;">
                <section>
                    <div class="row">
                        <div class="col-12">
                        <div class="vs-radio-con">
                            <input type="radio" name="myRadios"  id="myRadios" onclick="radChange1()"  value="1"/>
                            <span class="vs-radio">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                            </span>
                            Existing Machine
                        </div>                                
                        <div class="form-group pl-2" id="extvedorForm" style="display: none">
                            <div class="my-2"></div>
                                <select class="form-control col-12" name="aatid">
                                    <option selected>Select</option>
                                    <?php
                                        $lfe=mysqli_query($con, "SELECT * FROM attkey");
                                        if(mysqli_num_rows($lfe)>0){
                                            foreach($lfe as $lfd){
                                                ?><option value="<?php echo $lfd['aatid'];?>"><?php echo $lfd['attname'];?></option><?php
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
                                New Machine
                        </div>
                        <div class="form-group pl-2" id="newvedorForm" style="display: none">
                            <div class="my-2"></div>
                            <div class="controls">
                                <label>Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control col-12"  placeholder="Name" name="attname" data-validation-required-message="This name field is required">
                                </div>
                            </div>
                            <div class="my-2"></div>                                               
                    </div>
                    <input type="text" name="formselector" id="formselector" style="display: none;"> 
                    <input type="submit" name="machineBtn" id="machineBtn" class="btn btn-md btn-danger btn-block" value="Submit">
                </section>
                </form>
                <script type="text/javascript">
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
                </script>
                <form action="shower.php" method="get" style="display: none;">
                    <input type="text" autocomplete="off" class="form-control" value="<?php echo $aatid; ?>" name="text" style="border-radius: 0px; " placeholder="Text..." value="">
                    <br>
                    <input type="submit" id="submitBtn" class="btn btn-md btn-danger btn-block" value="Generate">
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            if('<?php echo $check;?>'==1){
                swal("Oops !", "Machine already Exist.", "error");
                document.getElementById("machinecre").style.display = "block";
            }else{
                if('<?php echo $aatid;?>'==0){
                    document.getElementById("machinecre").style.display = "block";
                }else{
                    document.getElementById("submitBtn").click();
                } 
            }                       
        });
    </script>
</body>
</html>