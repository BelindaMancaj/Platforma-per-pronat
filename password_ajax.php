<?php
include("connect.php");
session_start();

if ($_POST['action'] == "update_password") {


    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);


    /**
     * Validimi i Password
     */
    if (empty($password)) {
        echo json_encode(array("status" => 404, "message" => "Password nuk mund te jete bosh"));
        exit;
    }
    if (!preg_match("/^([a-zA-Z0-9]{5,})$/", $password)) {
        echo json_encode(array("status" => 404, "message" => "Password duhet te kete minimumi 5 karaktere qe mund te jene shkronja te medha, te vogla ose numra"));
        exit;
    }

    /**
     * Validimi i konfirmimit
     */
    if (empty($confirm_password)) {
        echo json_encode(array("status" => 404, "message" => "Password nuk mund te jete bosh"));
        exit;
    }
    if ($_POST["password"] !== $_POST["confirm_password"]) {
        echo json_encode(array("status" => 404, "message" => "Fjalekalimi nuk perputhet"));
        exit;
    }

    $sql1 = "SELECT fjalekalimi FROM perdorues WHERE  id = '".$_SESSION['user_id']."'";

    $result = mysqli_query($conn, $sql1);

    if (mysqli_num_rows($result) > 0) {

        while($row = mysqli_fetch_assoc($result)) {
    if($password != $row['fjalekalimi']){

        $sql2 = " UPDATE perdorues SET
                fjalekalimi = '" . $password . "',
                kfjalekalimi = '" . $confirm_password . "' WHERE id = '".$_SESSION['user_id']."' ";

        if (mysqli_query($conn, $sql2)) {
            echo "";
        } else {
            echo "Error" . mysqli_error($conn);
        }

        echo json_encode(array("status" => 200, "message" => "User saved successfully"));
    }
    else{
        echo json_encode(array("status" => 404, "message" => "NO"));

    }

        }
    }}
?>
