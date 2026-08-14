<?php
include('db_conn.php');
if(isset($_REQUEST['uin'])){
$sql = "SELECT * FROM admintable WHERE uin='$_REQUEST[uin]'";
$result = mysqli_query($conn, $sql);
$row=mysqli_fetch_array($result);

$phone= $row['phone'];
$weblink = "https://wa.me/234";
$newwhatsapp = $weblink.$phone;


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['fullname']; ?> | ID CARD SLIP</title>
    <style>
        .id-card {
            width: 350px;
            height: 200px;
            position: relative;
            background-image: url('NEWCARD.JPG');
            background-size: cover;
            padding: 20px;
            margin: auto;
            margin-top: 50px;
            font-family: Arial, sans-serif;
            font-size:11px;
            color: #000;
        }
        .id-card h2 {
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .id-card p {
            margin: 5px 0;
            padding: 0;
        }
        .info {
            position: absolute;
            top: 65px; /* Adjust this value based on your background design */
            left: 130px; /* Adjust this value based on your background design */
        }
        .uin {
            position: absolute;
            top: 57px; /* Adjust this value based on your background design */
            left: 6px; /* Adjust this value based on your background design */
            color:white;
            text-shadow: 0px 0px 3px black;
        }
        .regdate {
            position: absolute;
            top: 57px; /* Adjust this value based on your background design */
            left: 275px; /* Adjust this value based on your background design */
            color:white;
            text-shadow: 0px 0px 3px black;
        }
        .info2 {
            position: absolute;
            top: 200px; /* Adjust this value based on your background design */
            left: 315px; /* Adjust this value based on your background design */
        }
        .info3 {
            position: absolute;
            top: 180px; /* Adjust this value based on your background design */
            left: 318px; /* Adjust this value based on your background design */
            text-align: center;

        }
        .infobarcode {
            position: absolute;
            top: 168px; /* Adjust this value based on your background design */
            left: 6px; /* Adjust this value based on your background design */
        }
        .infoqrcode {
            position: absolute;
            top: 78px; /* Adjust this value based on your background design */
            left: 308px; /* Adjust this value based on your background design */
        }
        .infopassport {
            position: absolute;
            top: 70px; /* Adjust this value based on your background design */
            left: 15px; /* Adjust this value based on your background design */
        }
        .infopassport2 {
            position: absolute;
            top: 176px; /* Adjust this value based on your background design */
            left: 248px; /* Adjust this value based on your background design */
        }
        
    </style>
</head>
<body bgcolor="black">
    <div class="id-card">
        <div class="infopassport">
            <p><img src="farmers/" height="85px" style="border-radius:5px 5px 0px 0px"></p>
        </div>
       
       
        <div class="info">
            <p>Fullname: <b><?php echo $row['fullname']; ?></b></p>
            <p>Email: <b><?php echo $row['email']; ?></b></p>
            
            <p>Phone: <b><?php echo $row['phone']; ?></b></p>
            <p>Role: <b><?php echo $row['role']; ?></b></p> 
        </div>
        <div class="infopassport2">
            <p><img src="farmers/" height="50px" style="border-radius:5px 5px 0px 0px"></p>
        </div>

        <div class="info3">
            <p>ISSUED: <br> <b><?php //echo date('Y', strtotime($row['date'])); ?></b><br>
            S/N: <b><?php echo $row['id']; ?></b></p>
        </div>

       

        <div class="infobarcode">
        <p><?php //echo "<center><img height='65px' alt='barcode' src='barcode.php?codetype=Code39&size=50&text=".$row['uin']."&print=true'/></center>" ?></p>
        </div>

        <div class="infoqrcode">
        <p><center><img height='65px' alt='QR Code' src="generate_qr.php?data=<?php echo urlencode($newwhatsapp); ?>" /></center></p>
        </div>
        
    </div>
</body>
</html>
<!-- <script type="text/javascript">
        window.print();
    </script> -->