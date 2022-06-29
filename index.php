<?php session_start() ?>
<?php include "config.php" ?>
<?php 
if($_SERVER['REQUEST_METHOD'] == "POST"){
 $email= $_POST['adminemail'];
 $password= sha1($_POST['adminpassword']) ;


$stmt=$con->prepare("SELECT * FROM `user` WHERE `email`=? AND `password`=? AND `role` !=2");
$stmt->execute(array($email , $password));
$count=$stmt->rowCount();
if($count == 1){
  $_SESSION['ID']=$user['id'];
  $_SESSION['USERNAME']=$user['username'];
  $_SESSION['EMAIL']=$user['email'];
  $_SESSION['ROLE']=$user['role'];
  header("location:dashboard.php");
}else{
  echo "<h1>sorry</h1>";
}



// $user=$stmt->fetch();

// echo '<pre>';
// print_r($user);
// echo '</pre>';
}

?>



<?php include "includes/header.php" ?>
<div class="container">
    <h1 class="text-center">Admin panal</h1>
    <form method="post" action="">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email"  class="form-control" name="adminemail">
    <div id="emailHelp" class="form-text"></div>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password"  class="form-control" name="adminpassword">
  </div>
 
  <input type="submit" name="" value="Submit">
</form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>