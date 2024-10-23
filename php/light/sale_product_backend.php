<?php
$brid=$_GET['brid'];

include "../connection.php";

$return_arr = array();

$purchase = mysqli_query($con,"SELECT * FROM purchase WHERE aid='$brid'");

foreach($purchase as $purch){
    $refp=$purch['pcid'];
    $productdts = mysqli_query($con,"SELECT * FROM productdts WHERE refp='$refp' AND stat='0'");
    foreach($productdts as $pdts){
        $pdtid=$pdts['pdtid'];
        $product=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM product WHERE pdtid='$pdtid'"));
        $ptid = $pdts['ptid'];
        $products = $product['pdtname'] ." | ".$product['pdtdetails'] ." | ".$pdts['ptsn'];

        $return_arr[] = array("ptid" => $ptid,
                        "products" => $products);
    }
}

// Encoding array in JSON format
echo json_encode($return_arr);