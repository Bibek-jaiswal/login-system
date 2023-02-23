<?php


// Data retrieving from database
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


                
?>