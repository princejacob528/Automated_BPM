<?php
$email=$_POST['lemail'];
$pass=$_POST['lpass'];
include("connection.php");
$query = "select aid from account where email = '$email'";
$result = mysqli_query($con, $query);
 if($result && mysqli_num_rows($result) > 0){
  $row = mysqli_fetch_array($result);
  $row = $row['aid'];
}
if(isset($_POST['fname'])){
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
  $utype=$_POST['dest'];
  $doh=$_POST['doh'];
  $idnum=$_POST['idnum'];
  if(!isset($_POST['idnum']))
    $idnum="Not_Valued";
  $uid=$_POST['row'];  
  include("connection.php");
  $query = "INSERT INTO actdetails (aid, fname, lname, gender, dob, ads1, ads2, country, states, pincode, pno, dest, doh, idnum, utype, salary, extraduty) VALUES ('$uid', '$fname', '$lname', '$data', '$dob', '$ads1', '$ads2', '$country', '$state', '$pincode', '$pno', '$utype', '$doh', '$idnum', '' , '0', '0')";        
  $quy="UPDATE account SET stat='informed' WHERE aid='$uid'";
  if(mysqli_query($con, $query)){
    if(mysqli_query($con, $quy))
      echo '<script type="text/javascript">checkers();</script>';      
    else
      echo '<script type="text/javascript">failedFn();</script>';
  }
}
function checker(){
  include("connection.php");
  $email=$GLOBALS['email'];
  $pass=$GLOBALS['pass']; 
  $query = "select aid,stat from account where email = '$email' AND pass = '$pass' ";
  $result = mysqli_query($con, $query);
  if($result)
  {
      if($result && mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        $aid=$row['stat'];
        if($row['stat']=="created")        
          return 1;        
        else if($row['stat']=="informed")        
          return 2;        
        else if($row['stat']=="approved")
          return 3;
        else
          return 4;
      }
      else
        return 5;
  }
  else
    return 6;        
}
?>
<html lang="en">
  <head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Automated BPM | Information Interface</title>
    <link rel="icon" href="../img/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">
    <!-- Bootstrap core CSS -->
    <style>
      body{
        background-color: whitesmoke;
      }
    </style>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body{
        background-color: #F8F8F8;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 450px 400px;
        background-position: right bottom;
        
      }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      .social-text {
        padding: 0.7rem 0;
        font-size: 1rem;
        text-align: center;
      }
      .social-text p{
        margin-bottom: 0;
        color: #888;  
        font-size: 14px;
      }
      .social-text a {
        color: #888;
        text-decoration: none;
        font-weight: bold;
          
      }      
    </style>    
    <!-- Custom styles for this template -->
    <link href="../sytle/form-validation.css" rel="stylesheet">
  </head>
  <body class="bg-light">    
<div class="container" style="display: none;" id="validate">
  <main>
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="../img/logo.png" alt="" width="50" height="50">
      <h2>We're Verifying You!</h2>
      <p class="lead">You're just one step away</p>
    </div>
    <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Account Verification</h4>
        <form class="needs-validation" novalidate> 
            <div class="col-12">
              <label class="form-label">We have received your account request. The account will be verified within 48 hours. Then, you will receive a verification mail through your E-mail address.<br><br> <span class="text-muted">We hope to see you soon. </span></label>
              <a class="text-muted" href="../index.html">Back to login</a>             
            </div>
        </form>
    </div>
    <br><br>
  </main>
</div>
<div class="container" style="display: none;" id="container">
  <main>
  <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="../img/logo.png" alt="" width="50" height="50">
      <h2>Create your profile</h2>
      <p class="lead">This information will let us know more about you.</p>
    </div>     
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Profile Details</h4>
        <form class="needs-validation" novalidate action="#" method="POST">         
        <div class="row g-3">            
            <div class="col-sm-6">
              <label for="firstName" class="form-label">First name</label>
              <input type="text" name="fname" class="form-control" id="firstName" placeholder="" value="" required>
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>
            <div class="col-sm-6">
              <label for="lastName" class="form-label">Last name</label>
              <input type="text" name="lname" class="form-control" id="lastName" placeholder="" value="" required>
              <div class="invalid-feedback">
                Valid last name is required.
              </div>
            </div>
            <div class="row g-3">              
            <div class="col-sm-6">
              <br>
              <label for="Gender" class="form-label">Gender</label>
              <br>
                <div class="form-check form-check-inline">            
                  <input name="gender" <?php if (isset($gender) && $gender=="Male") echo "checked";?> value="Male" type="radio" class="form-check-input" checked required>
                  <label class="form-check-label" for="credit">Male</label>
                </div>
                <div class="form-check form-check-inline">
                  <input name="gender" <?php if (isset($gender) && $gender=="Female") echo "checked";?> value="Female" type="radio" class="form-check-input" required>
                  <label class="form-check-label" for="debit">Female</label>
                </div>
                <div class="form-check form-check-inline">
                  <input name="gender" <?php if (isset($gender) && $gender=="Other") echo "checked";?> value="Other" type="radio" class="form-check-input" required>
                  <label class="form-check-label" for="paypal">Other</label>
                </div>
            </div>
            <div class="col-sm-6" style="padding: 20px;">
                <label for="lastName" class="form-label">Date of Birth</label>
                <input type="date" min="1980-01-01" max="2020-12-31" class="form-control" name="dob" placeholder="" value="" required>
                  <div class="invalid-feedback">
                    Valid Date is required.
                  </div>
            </div>
          </div>

            <div class="col-12">
              <label for="address" class="form-label">Address</label>
              <input type="text" name="address1" class="form-control" placeholder="House name or apartment name, number" required>
              <div class="invalid-feedback">
                Please enter your address.
              </div>
            </div>

            <div class="col-12">
              <label for="address2" class="form-label">Address 2 </label>
              <input type="text" name="address2" class="form-control" placeholder="Street or locality" required>
            </div>

            <div class="col-md-5">
              <label for="country" class="form-label">Country</label>
              <select name="country" class="form-select" required>
                <option value="">Choose...</option>
                <option value="India">India</option>
                </select>
              <div class="invalid-feedback">
                Please select a valid country.
              </div>
            </div>

            <div class="col-md-4">
              <label for="state" class="form-label">State</label>
              <select name="state" class="form-select" required>
                <option value="">Choose...</option>
                <option value="Andhra Pradesh">Andhra Pradesh</option>
                <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                <option value="Assam">Assam</option>
                <option value="Bihar">Bihar</option>
                <option value="Chandigarh">Chandigarh</option>
                <option value="Chhattisgarh">Chhattisgarh</option>
                <option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
                <option value="Daman and Diu">Daman and Diu</option>
                <option value="Delhi">Delhi</option>
                <option value="Lakshadweep">Lakshadweep</option>
                <option value="Puducherry">Puducherry</option>
                <option value="Goa">Goa</option>
                <option value="Gujarat">Gujarat</option>
                <option value="Haryana">Haryana</option>
                <option value="Himachal Pradesh">Himachal Pradesh</option>
                <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                <option value="Jharkhand">Jharkhand</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Kerala">Kerala</option>
                <option value="Madhya Pradesh">Madhya Pradesh</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Manipur">Manipur</option>
                <option value="Meghalaya">Meghalaya</option>
                <option value="Mizoram">Mizoram</option>
                <option value="Nagaland">Nagaland</option>
                <option value="Odisha">Odisha</option>
                <option value="Punjab">Punjab</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Sikkim">Sikkim</option>
                <option value="Tamil Nadu">Tamil Nadu</option>
                <option value="Telangana">Telangana</option>
                <option value="Tripura">Tripura</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="Uttarakhand">Uttarakhand</option>
                <option value="West Bengal">West Bengal</option>
              </select>
              <div class="invalid-feedback">
                Please provide a valid state.
              </div>
            </div>

            <div class="col-md-3">
              <label for="zip" class="form-label">Pincode</label>
              <input type="text" name="pincode" class="form-control" placeholder="" required>
              <div class="invalid-feedback">
                Pin code required.
              </div>
            </div>
          </div>
            <br>                    
            <div class="col-sm-6">
                <label for="address" class="form-label">Phone Number</label>
                <div class="input-group has-validation">
                  <span class="input-group-text">+91</span>
                <input type="text" name="pno" class="form-control" placeholder="" required>
                <div class="invalid-feedback">
                  Phone number required.
                </div>
                </div>
            </div>          
          <hr class="my-4">

          <h4 class="mb-3">User Details</h4>
          <div class="row gy-3">
          <div class="col-md-6">
              <label for="username" class="form-label">Email</label>
              <div class="input-group has-validation">
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" placeholder="Email Address" value='<?php echo $email; ?>' disabled>
              <div class="invalid-feedback">
                  Your email address is required.
                </div>
            </div>
          </div>
          <div class="col-md-6">
              <label for="state" class="form-label">Designation</label>
              <input type="text" class="form-control" name="dest" required>
              <div class="invalid-feedback">
                Please provide a valid designation.
              </div>
            </div>
          </div>
          <br>
          <div class="row gy-3">
            <div class="col-md-6">
              <label for="cc-name" class="form-label">Date of hire</label>
              <input type="date" class="form-control" name="doh" placeholder="" required>
              <small class="text-muted">Mentioned in offer letter.</small>              
            </div>

            <div class="col-md-6">
              <label for="cc-number" class="form-label">ID Number<span class="text-muted">(Optional)</span></label>
              <input type="text" class="form-control" name="idnum" placeholder="">              
            </div>            
          </div>         
          <input value='<?php echo $pass; ?>' id="lpass" name="lpass" style="display: none;">
          <input value='<?php echo $email; ?>' id="lemail" name="lemail" style="display: none;">
          <input value='<?php echo $row; ?>' id="row" name="row" style="display: none;">
          <hr class="my-4">
          <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to Proceed</button>
        </form>
      </div>
    </div>
  </main>
  <footer class="my-5 pt-5 text-muted text-center text-small" style="display: none;" id="containers">
    <p class="social-text">            
            <a href="https://automatedbusiness.000webhostapp.com/">Automated Business Process Management System.</a>
            <br>Final Year Project, BTech, CSE.<br>
            <a href="http://jaibharathengg.com/">Jai Bharath College Of Management And Engineering Technology </a>
    </p>    
  </footer>
</div>
<form style="display: none;" id="lightForm" action="light/home.php" method="POST">
  <input value='<?php echo $row; ?>' id="userKey" name="userKey" style="display: none;">
</form>
<form style="display: none;" id="darkForm" action="light/home.php" method="POST">
  <input value='<?php echo $row; ?>' id="userKey" name="userKey" style="display: none;">
</form>
<script type="text/javascript">
$(document).ready(function() {
  loaderFade();    
});
function loaderFade(){  
  const mq = window.matchMedia( "(max-width: 760px)" );
  if (mq.matches) {
    document.body.style.backgroundImage = "url('../img/info_bg.png')";
  } else {
    document.body.style.backgroundImage = "url('../img/info_bg.svg')";
  }
  checkers();
}
function checkers(){
  var y='<?php echo checker(); ?>';
  if(y==1){    
    document.getElementById("container").style.display = "block";
    document.getElementById("containers").style.display = "block";
  }
  else if(y==2){
    document.getElementById("validate").style.display = "block";
    document.getElementById("containers").style.display = "block";
  }
  else if(y==3){
    var today = new Date();
    var time = today.getHours();
    if (time > 6 && time < 18) {
    document.getElementById("lightForm").submit();
    }
    else{
      document.getElementById("darkForm").submit();
    }
  }
  else if(y==4){
    Swal.fire({
        icon: 'error',        
        text: "Your account has been disabled.",                                      
        showCancelButton: false        
      }).then((result) => {
        window.location.href="../index.html", '_blank';
      });
  }
  else if(y==5){
    Swal.fire({
        icon: 'error',        
        title: "Invalid Account or Password",
        text: "Entered email address does not exist, Or the password does not match.",                                      
        showCancelButton: false        
      }).then((result) => {
        window.location.href="../index.html", '_blank';
      });
  }
  else{
    Swal.fire({
        icon: 'warning',        
        text: "Something went wrong.",                                      
        showCancelButton: false        
      }).then((result) => {
        window.location.href="../index.html", '_blank';
      });
  }
}
function failedFn(){
  Swal.fire({
        icon: 'warning',        
        text: "Profile updation incomplete, Try again.",                                      
        showCancelButton: false        
      }).then((result) => {
        window.location.href="../index.html", '_blank';
      });
}
</script>
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/form-validation.js"></script>    
  </body>
</html>
