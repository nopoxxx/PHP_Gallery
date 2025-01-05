<?php
function connectSQL() {

    $servername = "localhost";
    $username = "root";
    $dbpassword = "nopoxPassword";
    $dbname = "gallery";

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}