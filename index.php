<?php
    require 'util/connection.php';

    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = mysqli_query($connect,"SELECT * FROM user WHERE username='$username' AND password='$password'");
        $data =  mysqli_fetch_array($query);

        if($data > 0){
            session_start();
            $_SESSION['username'] = $data['username'];
            $_SESSION['password'] = $data['password'];
            $_SESSION['name'] = $data['name'];
            echo "<script>location.href='dashboard.php'</script>";
        } else{
            echo "<script>alert('Failed, check your username and password!')</script>";
        } 
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/signin.css">
        <title>App</title>
    </head>
    <body class="text-center">
        <form class="form-signin" action="" method="POST">
            <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
            <label for="inputUsername" class="sr-only">Username</label>
            <input id="inputUsername" name="username" class="form-control" placeholder="Username" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign in</button>
        </form>
    </body>
</html>