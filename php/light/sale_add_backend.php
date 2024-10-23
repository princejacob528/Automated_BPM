<?php
$ptid=$_GET['ptid'];

include "../connection.php";

$return_arr = array();

$query = "SELECT * FROM productdts WHERE ptid='$ptid'";

$result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result)){
    $dp = $row['dp'];
    $cost = $row['cost'];
    $mrp = $row['mrp'];

    $return_arr[] = array("dp" => $dp,
                    "cost" => $cost,
                    "mrp" => $mrp);
}

// Encoding array in JSON format
echo json_encode($return_arr);