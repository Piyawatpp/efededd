
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?=$nametitle?></title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="shortcut icon" href="<?=$img?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <link rel="stylesheet"><script src="dist/sweetalert2.min.js"></script><link rel="stylesheet" href="dist/sweetalert2.min.css">
    <script src="dist/sweetalert2.min.js"></script><link rel="stylesheet" href="dist/sweetalert2.min.css">

    <style>
    body {
		background: #101010;
        font-family: 'Kanit', sans-serif;
        font-style: normal;
        font-weight: 300;
        padding-top: 70px;
        overflow-x: hidden;
    }

    div {
        color: #ffffff;
    }

    .card-header  {
        background-color: rgba(20,20,20);
    }
    .shadow {
        background-color: rgba(30,30,30);
    }
    </style>
</head>

<?php
session_start();
$url = ""; // discord webhook
$img = "https://cdn.discordapp.com/attachments/967701434445471787/972905557889527818/unknown.png";
$nametitle = "DonateMe";
if(isset($_POST['topup'])){
    

    function Request($hash){ //ชังชั่นรับซอง
        $phonenumber = "0123456789"; //ใส่เบอร์ตรงนี้
        $curl = curl_init();
        $mygiftlink = str_replace('https://gift.truemoney.com/campaign/?v=','',$hash);
    
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://karuma.servegame.com:4433/?link='.$mygiftlink.'&phone='.$phonenumber,// เบอร์ใส่ตรงนี้
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('Content-Type: application/json',),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        $data = json_decode($response, true);
        return $data;
    }	
    
    $vc = Request($_POST['topup']); 
    if ($vc['status'] == 'VOUCHER_NOT_FOUND') {//เช็คจาก status ถ้าเป็น VOUCHER_NOT_FOUND คือไม่เจอลิ้งซอง
        echo '</body><script> swal("ผิดพลาด","ไม่พบลิ้งอั่งเปาในระบบ!!","error").then(function() {window.location = "";});</script>';
    }elseif ($vc['status'] == "VOUCHER_OUT_OF_STOCK") {//เช็คจาก status ถ้าเป็น VOUCHER_OUT_OF_STOCK คือลิ้งซองมีคนรับไปแล้ว
        echo '</body><script> swal("ผิดพลาด","ลิงค์อังเปาหมดอายุแล้ว!!","error").then(function() {window.location = "";});</script>';
        
    }elseif ($vc['code'] == 200) { //เช็คจาก code ถ้าเป็น 200 คือรับซองได้ปกติ
        $money =  $vc['amount']; //จำนวนเงินที่เติม
            echo '</body><script> swal("เสร็จสิ้น","[ success ] ขอบคุณสำหรับการโดเนทน้าบ จำนวน 100 บาท'.$money.'","success").then(function() {window.location = "";});</script>';
            $headers = [ 'Content-Type: application/json; charset=utf-8' ];
            if ($_POST['discord']) {//เช็ค discord **ไม่รู้ทำไปทำไม
                $content = [ 'username' => 'Testing BOT', 'content' => 'คุณ'.$_POST['discord'].' ได้โดเนทจำนวน '.$money.' บาท \nขอขอบคุณที่โดเนทให้กับทางเรา ><' ];
            }else {
                $content = [ 'username' => 'Testing BOT', 'content' => 'มีคนโดเนทจำนวน '.$money.' บาท \nขอขอบคุณที่โดเนทให้กับทางเรา ><' ];
            }
            $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
                $response   = curl_exec($ch); //api discord webhook ศึกษาได้ที่ https://stackoverflow.com/questions/59219193/how-can-i-send-a-discord-webhook-using-php
            
    }
        
} 
?>

<body>
    <div class="col-sm-4 mx-auto mt-4">
        <div class="card shadow">
	
  <h5 class="card-header"><?=$nametitle?> </h5>
            <div class="card-body text-center mx-auto">
                <img src="<?=$img?>" width="250" class="mt-1" alt="">
                <hr>
                <form method="POST">
                    <div class="form-group">
                    </div>
                    <div class="form-group">
					<div class="text-left mt-1"><i class="fas fa-angle-right"></i>ลิ้งซองของขวัญ**</div>
                        <input type="text" name="topup" class="form-control" placeholder="https://gift.truemoney.com/campaign/?v=..." required>
                    </div>
                   <hr>
                   <div class="text-left mt-1"><i class="fas fa-angle-right"></i>ชื่อของคุณ**</div>
                        <input type="text" name="discord" class="form-control" placeholder="Karuma.." required>
                    </div>
                   <hr>
					<span style="color:red"><b>*** เลือกจำนวนซองของขวัญแค่หนึ่งซอง ***</b></span>
					<hr>
                   <button type="submit" class="btn btn-danger btn-block btn-lg text-light w-100"><i class="fas fa-sign-in-alt"></i> โดเนท</button>
                </form>
            </div>
        </div>
    </div>

    <small class="pb-3 d-block my-auto footer-copyright text-secondary text-center py-4 w-100">©  Copyright 2022 Website By <a href="https://discord.gg/VUCQgzn3xR" target="_blank">Karuma Service</a> Not Sell.</small>

    
</body>
</html>
