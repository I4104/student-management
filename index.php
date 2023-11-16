<?php
    include "handler/config.php";

    if (!isset($_SESSION["username"])) {
        header("location: login.php");
    } else {
        header("location: classroom.php");
    }
?>