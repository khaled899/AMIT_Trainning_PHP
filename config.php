<!-- how to connect to database -->
<?php

$dsn="mysql:host=localhost;dbname=amit";
$username="root";
$password="";

try{
    $con=new PDO($dsn,$username,$password);
    // echo "connect";
}catch(PDOException $e){
echo $e;
}


?>