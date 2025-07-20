<?php
    session_start();

    session_unset();
    session_destroy();
    header("Location: /PenaconyExchange/index.php");
    exit;
?>
