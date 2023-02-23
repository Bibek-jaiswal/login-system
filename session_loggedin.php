<?php

session_start();
if(isset($_SESSION['loggedin'])){

    header("location: welcome.php");

    exit;


}

?>