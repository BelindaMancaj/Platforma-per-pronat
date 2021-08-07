<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database="regjistrim";
    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn){
        die("Ju nuk mund te lidheni".mysqli_connect_error());
    }
    //mysqli_close($conn);
?>