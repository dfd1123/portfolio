<?php
include("../api/tlc/tlcAPI.php");



echo "<h1> test wallet </h1> <br><br>";

$test_user = "test";

echo "<br><br> 주소만들기 <br>";
$new_address = Create_tlc_address($test_user);

echo "new tellus address : ".$new_address;



echo "<br><br> 잔액조회 <br>";
$balance = get_tlc_balance($new_address);

echo "balance : ".$balance;



echo "<br><br> 보내기는 send_tlc 함수 사용 <br>";



?>