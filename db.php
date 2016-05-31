<?php
$mysqli = new mysqli("localhost", "root", "!25Asweetangel", "regula");

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

?>