<?php
//CHECK IF LOGGED IN
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ./index.php");
    exit;
}
//PROCESS LOGIN
require_once "../config/database.php";
$username = $password = "";
$badusername = $badpassword = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $badusername = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    if(empty(trim($_POST["password"]))){
        $badpassword = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    if(empty($badusername) && empty($badpassword)){
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){                    
                    $stmt->bind_result($id, $username, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username; 
                            $_SESSION["password"] = $hashed_password; 
                            header("location: ./index.php");
                        } else{
                            $badpassword = "The password you entered was invalid.";
                        }
                    }
                } else{
                    $badusername = "That account does not exist.";
                }
            } else{
                echo "Error, please try again.";
            }
        }
        $stmt->close();
    }
    $mysqli->close();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/bootstrap.css">
</head>
<body class="text-center">
    <div class="wrapper">
    <form action="./login.php" method="post" class="form-small">
        <h1 class="h3 mb-3 font-weight-normal">Transport Canada<br/>Review System Login (DEMO)</h1>
        <p>Please login to leave a review.</p>
        <div class="form-group <?php echo (!empty($badusername)) ? 'has-error' : ''; ?>">
            <label class="sr-only">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $badusername; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($badpassword)) ? 'has-error' : ''; ?>">
            <label class="sr-only">Password</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $badpassword; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
    </form>
    </div>
</body>
</html>