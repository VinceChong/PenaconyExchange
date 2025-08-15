<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "PenaconyExchange";

    $connect = new mysqli($host, $user, $password, $db);

    if ($connect -> connect_error){
        die("Connection failed: ".$connect->connect_error);
    }
?>