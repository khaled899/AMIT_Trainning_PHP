<?php session_start() ?>
<?php include 'config.php' ?>
<?php include "includes/header.php" ?>
<?php include "includes/navbar.php" ?>



<?php if (isset($_GET['action'])) {
    $do = $_GET['action'];
} else {
    $do = 'index';
}
?>

<!-- show all users in database -->
<?php if ($do == "index") : ?>
    <?php if (isset($_GET['open']) && $_GET['open'] == 'admin') : ?>
        <h1 class="text-center">All Admin & moderators</h1>
    <?php else : ?>
        <h1 class="text-center">All Members</h1>
    <?php endif ?>
    <?php
    //condition to check the page admin tabed on it
    $role = isset($_GET['open']) && $_GET['open'] == 'admin' ? '!=3' : '2';

    $stmt = $con->prepare("SELECT * FROM `user` WHERE `role` =$role");
    $stmt->execute();
    $users = $stmt->fetchAll();
    // 1-من حيث الاختبار
    // echo '<pre>';
    // print_r($users) ;
    // echo '</pre>';
    ?>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">image</th>

                    <th scope="col">Username</th>

                    <th scope="col">Created_at</th>
                    <th scope="col">Role</th>

                    <th scope="col">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <th scope="row">1</th>
                        <td><img src="uploads/<?= $user['img'] ?>" style="height:10vh"></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td><?= $user['role'] ?></td>

                        <td>
                            <!---make the admin see all buttons and moderators see show button only-->
                            <?php if ($_SESSION['ROLE'] == 3) : ?>
                                <a class="btn btn-warning" href="?action=show&userid=<?= $user["id"] ?>">Show</a>
                            <?php else : ?>
                                <!--------------------------Selected data using id------------------------------------->
                                <a class="btn btn-warning" href="?action=show&userid=<?= $user["id"] ?>">Show</a>
                                <a class="btn btn-info" href="?action=edit&userid=<?= $user["id"] ?>">Edit</a>
                                <a class="btn btn-danger" href="?action=delete&userid=<?= $user["id"] ?>">Delete</a>
                            <?php endif ?>

                        </td>
                    </tr>
                <?php endforeach  ?>
            </tbody>
        </table>
        <a class="btn btn-primary" href="members.php?action=create ">Add User</a>
    </div>
    <!------------------------------------------------------------------------------------->

<?php elseif ($do == "create") : ?>
    <h1 class="text-center">Add User</h1>
    <div class="container">
        <form method="post" action="?action=store" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Username</label>
                <input type="text" class="form-control" name="username">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Phone</label>
                <input type="number" class="form-control" name="phone">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Upload Image</label>
                <input type="file" class="form-control" name="image">
            </div>
            <div class="mb-3 form-check">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <!------------------------------------------------------------------------------------->

    <!-- لانم اتاكد من الريكوست -->
<?php elseif ($do == "store") : ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
       // upload file or image at database
       $imagename=$_FILES['image']['name'];
       $imagetype=$_FILES['image']['type'];
       $imagetmp=$_FILES['image']['tmp_name'];
       $imageAllowedExtension=array("image/jpeg" , "image/png", "image/png");
        if(in_array($imagetype,$imageAllowedExtension)){
            $image=rand(0,1000).$imagename;
            move_uploaded_file($imagetmp , "uploads/".$image);
            $username =  $_POST['username'];
            $email =  $_POST['email'];
            $password = sha1($_POST['password']);
            $phone =  $_POST['phone'];
            $stmt = $con->prepare("INSERT INTO `user`( `username`, `email`, `password`, `role`, `phone`, `img`, `created_at`) VALUES (?,?,?,2,?,?,now())");
            $stmt->execute(array($username, $email, $password, $phone , $image));
            header('location:members.php');
        }
        
        else{
            echo 'check your extension';
        }
        
      
    }
    ?>

    <!------------------------------------------------------------------------------------->

<?php elseif ($do == "edit") : ?>
    <?php
    $userid = $_GET['userid'];
    $stmt = $con->prepare("SELECT * FROM `user` WHERE `id`=? ");
    $stmt->execute(array($userid));
    $user = $stmt->fetch();
    $count = $stmt->rowCount();
    ?>
    <?php if ($count == 1) : ?>
        <h1 class="text-center">Edit User</h1>
        <div class="container">
            <form method="post" action="?action=update">
                <input type="hidden" class="form-control" name="id" value=<?= $user['id'] ?>>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value=<?= $user['username'] ?>>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" value=<?= $user['email'] ?>>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="newpassword">
                    <input type="text" class="form-control" name="oldpassword" value=<?= $user['password'] ?>>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Phone</label>
                    <input type="number" class="form-control" name="phone" value=<?= $user['phone'] ?>>
                </div>
                <div class="mb-3 form-check">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    <?php else : ?>
        <!-- علشان يرجعني للصفحه اللي كنت جاي منها -->
        <script>
            window.history.back();
        </script>
    <?php endif ?>
  
    <!------------------------------------------------------------------------------------------->
<?php elseif ($do == "update") : ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //if condition to check password
        // if(!empty($_POST['newpassword'])){
        //     $password=sha1($_POST['newpassword']) ;

        // }else{
        //     $password= $_POST['oldpassword'];
        // }
        $password = !empty($_POST['newpassword']) ? sha1($_POST['newpassword']) : $_POST['oldpassword'];
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $stmt = $con->prepare("UPDATE `user` SET `username`=?,`email`=?,`password`=?,`phone`=? WHERE `id`=?");
        $stmt->execute(array($username, $email, $password, $phone, $id));
        header("location:members.php");
    }



    ?>

    <!------------------------------------------------------------------------------------------->

<?php elseif ($do == "show") : ?>
    <?php
    $userid =  $_GET['userid'];
    $stmt = $con->prepare("SELECT * FROM `user` WHERE `id` = ?");
    $stmt->execute(array($userid));
    $user = $stmt->fetchAll();
    echo "<pre>";
    print_r($user);
    echo "</pre>";
    ?>
    <!------------------------------------------------------------------------------------------->

<?php elseif ($do == "delete") : ?>
    <?php
    $userid = $_GET['userid'];
    $stmt = $con->prepare("DELETE FROM `user` WHERE `id`=?");
    $stmt->execute(array($userid));
    header('location:members.php');
    ?>
    <!------------------------------------------------------------------------------------------->

<?php else : ?>
    <h1>404 page</h1>

<?php endif ?>