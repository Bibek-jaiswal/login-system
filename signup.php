
<?php


require("./portions/session_loggedin.php");


$showalert = false;
$showerror = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("./partials/_dbconnect.php");
    $fname = $_POST["fname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    

    // Username exists or not check
    $existsql_1 = "SELECT * FROM users WHERE fullname = '$fname'";
    $result_fname = mysqli_query($conn, $existsql_1);
    $numexistrows_fname = mysqli_num_rows($result_fname);
    

    // Email exists or not check
    $existsql_2 = "SELECT * FROM users WHERE email = '$email'";
    $result_email = mysqli_query($conn, $existsql_2);
    $numexistrows_email = mysqli_num_rows($result_email);
    
    
    if ($numexistrows_fname > 0) {
        $showerror = "Username already exists.";
    } 
    elseif ($numexistrows_email > 0) {
        $showerror = "Email already exists.";
    }  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    
        $showerror = "Email not valid.";
    
    }

    
    else {

        // Password and confirm password should be same and other criteria of password check and inserting the data into the database named = "users".
        if (($password == ($cpassword)    &&  (preg_match("#[a-zA-Z]+#", $password)) && (preg_match("#[0-9]+#",$password)) &&  (preg_match("#[^&$*{}()!<>?]+#", $password))  && strlen($password) >= 8 )) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users`.`users` ( `fullname`, `email`, `password`, `dt`) VALUES ( '$fname', '$email', '$hash', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            
            
            
            if ($result) {
                
                
                $sql = "Select * from users where email='$email'";
                $result = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($result);
                if ($num == 1) {
                    while ($row = mysqli_fetch_assoc($result)) {
        // Redirecting the user to dashboard if email and password is correct/ account exists in database

                        if (password_verify($password, $row['password'])) {
            
                            $login = true;
                            session_start();
                            $_SESSION['loggedin'] = true;
                            $_SESSION['fname'] = $row['fullname'];
                            $_SESSION['email'] = $email;
                            header("location: welcome.php");
                        } 

                    }

                }
                $showalert = true;
            }


        } else {
            $showerror = "Passwords do not match or criteria is not met.";
        }
        
    }
}


?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Sign Up</title>

</head>

<body>
    <?php require("partials/_nav.php"); ?>

    <?php

// In action if input is not accepted or criteria of the input is not met.
    if ($showerror) {
        echo
        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> ' . $showerror . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
    }


    ?>


    <div class="container my-4">
        <h1 class="text-center">Sign Up to Our Website</h1>


        <form action="/signup.php" method="post">


            <div class="form-group ">
                <label>Full Name</label>
                <input type="text" name="fname" maxlength="50" class="form-control" required>
            </div>



            <div class="form-group ">
                <label>Email</label>
                <input type="text" name="email" maxlength="50" class="form-control" >
            </div>



            <div class="form-group ">
                <label>Password</label>
                <input type="password" class="form-control" maxlength="50" name="password" required>
                <small id="emailHelp" class="form-text text-muted">Make sure to include atleast a special character(except $,%,^), an uppercase, a lowercase, a number and must be atleast 8 characters.</small>

            </div>



            <div class="form-group ">
                <label>Confirm Password</label>
                <input type="password" class="form-control" maxlength="50" name="cpassword" required>
                <small id="emailHelp" class="form-text text-muted">Make sure to type the same password.</small>
            </div>


            <button type="submit" class="btn btn-primary" name="submit">Sign Up</button>
        </form>
       



    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>



</body>

</html>


