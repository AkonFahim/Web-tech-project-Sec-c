<?php
    $con = mysqli_connect('127.0.0.1', 'root', '', 'finance_tracker');

    if(!$con){
        echo "Error connecting to database";
    }
?>