<?php
session_start();
include("connect.php");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

/**
 * Validimi i fotos se profilit
 */
function saveProfilePicture($data, $id, $frontDocPath)
{
    $extensions = array("jpeg", "jpg", "png", "JPG", "JPEG", "PNG", "doc", "docx", "pdf");
    if ($data["file"]["name"] != "") {
        $temp = explode(".", $data["file"]["name"]);
        $doc = "profile_picture_" . $id . "." . end($temp);
        $file_ext = end($temp);
        if (in_array($file_ext, $extensions)) {
            move_uploaded_file($data['file']['tmp_name'], 'images/' . $doc);
            $file24 = "images/" . $doc;
            return $file24;
        } else {
            echo json_encode(array("status" => 404, "message" => "File extension is incorrect"));
            exit;
        }
    } else {
        if (empty($frontDocPath)) {
            echo json_encode(array("status" => 404, "message" => "Please upload file"));
            exit;
        } else {
            return $frontDocPath;
        }
    }
}


if ($_POST['action'] == "new_user_register") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    /**
     * Validimi i Emrit
     */
    if (empty($name)) {
        echo json_encode(array("status" => 404, "message" => "Name can not be empty"));
        exit;
    }

    if (!preg_match("/^([a-zA-Z']{1,})$/", $name)) {
        echo json_encode(array("status" => 404, "message" => "Emri duhet te permbaje vetem shkronja"));
        exit;
    }


    /**
     * Validimi i mbiemrit
     */
    if (empty($surname)) {
        echo json_encode(array("status" => 404, "message" => "Mbiemri nuk mund te jete bosh"));
        exit;
    }

    if (!preg_match("/^([a-zA-Z']{1,})$/", $surname)) {
        echo json_encode(array("status" => 404, "message" => "Mbiemri duhet te kete vetem karaktere"));
        exit;
    }

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

    /**
     * Validimi i email
     */
    if (empty($email)) {
        echo json_encode(array("status" => 404, "message" => "Email nuk mund te jete bosh"));
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array("status" => 404, "message" => "Email nuk eshte ne formatin e duhur!"));
        exit;
    }

    /**
     * Shtimi i perdoruesit
     */
    $sql = " INSERT INTO perdorues SET
                emri = '" . $name . "',
                mbiemri = '" . $surname . "',
                email = '" . $email . "',
                fjalekalimi = '" . $password . "',
                kfjalekalimi = '" . $confirm_password . "' ;";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(array("status" => 200, "message" => "User saved successfully"));
        exit;
    } else {
        echo json_encode(array("status" => 404, "message" => "Error on database", "Error" => mysqli_error($conn)));
        exit;
    }


    echo json_encode(array("status" => 200, "message" => "User saved successfully"));

} else if ($_POST['action'] == "add_new_property") {

    $lloji_prones = mysqli_real_escape_string($conn, $_POST['prona1']);
    $pershkrimi = mysqli_real_escape_string($conn, $_POST['pershkrimi']);
    $cmimi = mysqli_real_escape_string($conn, $_POST['cmimi']);
    $data = mysqli_real_escape_string($conn, $_POST['data']);
    /**
     * Shtimi i pronave
     */
    $query_insert = "INSERT INTO prona set 
                       id_pronari = '" . $_SESSION['user_id'] . "',
                       lloji_prones = '" . $lloji_prones . "',
                       pershkrimi = '" . $pershkrimi . "',
                       cmimi = '" . $cmimi . "',
                       data_blerjes = '" . $data . "'
                ";


    if (mysqli_query($conn, $query_insert)) {
        echo json_encode(array("status" => 200, "message" => "Property saved successfully"));
        exit;
    } else {
        echo json_encode(array("status" => 404, "message" => "Error on database", "Error" => mysqli_error($conn)));
        exit;
    }


} else if ($_POST['action'] == "update_profile_data") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $tel = mysqli_real_escape_string($conn, $_POST['tel']);
    $ditelindje = mysqli_real_escape_string($conn, $_POST['ditelindje']);
    $location = mysqli_real_escape_string($conn, $_POST['path_file']);
    $date = date('Y-m-d', strtotime($ditelindje));

    /**
     * Validimi i Emrit
     */
    if (empty($name)) {
        echo json_encode(array("status" => 404, "message" => "Name can not be empty"));
        exit;
    }
    if (!preg_match("/^([a-zA-Z']{1,})$/", $name)) {
        echo json_encode(array("status" => 404, "message" => "Emri duhet te permbaje vetem shkronja"));
        exit;
    }

    /**
     * Validimi i mbiemrit
     */
    if (empty($surname)) {
        echo json_encode(array("status" => 404, "message" => "Mbiemri nuk mund te jete bosh"));
        exit;
    }

    if (!preg_match("/^([a-zA-Z']{1,})$/", $surname)) {
        echo json_encode(array("status" => 404, "message" => "Mbiemri duhet te kete vetem karaktere"));
        exit;
    }

    /**
     * Validimi i email
     */
    if (empty($email)) {
        echo json_encode(array("status" => 404, "message" => "Email nuk mund te jete bosh"));
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array("status" => 404, "message" => "Email nuk eshte ne formatin e duhur!"));
        exit;
    }

    /**
     * Validimi i tel
     */
    if (!empty($tel)) {
        if (!preg_match("/^([0-9']{1,})$/", $tel)) {
            echo json_encode(array("status" => 404, "message" => "Tel duhet te kete vetem numra"));
            exit;
        }
    }

    /**
     * Validimi i adreses
     */
    if (!empty($address)) {
        if (!preg_match("/^([a-zA-Z0-9']{1,})$/", $address)) {
            echo json_encode(array("status" => 404, "message" => "Adresa duhet te kete vetem karaktere dhe numra"));
            exit;
        }
    }

    $profile_picture_location = saveProfilePicture($_FILES, $_SESSION['user_id'], $location);

    /**
     * Updatimi i te dhenave te perdoruesit
     */
    $sql = " UPDATE perdorues SET emri='" . $name . "',
                                   mbiemri='" . $surname . "',
                                   email='" . $email . "',
                                   adresa= '" . $address . "',
                                   tel= '" . $tel . "',
                                   ditelindje = '" . $date . "',
                                   foto = '" . $profile_picture_location . "'
                                   WHERE id='" . $_SESSION['user_id'] . "'";


    if (!mysqli_query($conn, $sql)) {
        echo json_encode(array("status" => 404, "message" => "Error on DB" . mysqli_error($conn)) . " " . __LINE__);
        exit;
    }

    echo json_encode(array("status" => 200, "message" => "User saved successfully"));
    exit;

} else if ($_POST['action'] == "edit_property_data") {

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $edited_lloji_prones = mysqli_real_escape_string($conn, $_POST['lloji_prones']);
    $edited_pershkrimi = mysqli_real_escape_string($conn, $_POST['pershkrimi']);
    $edited_data = mysqli_real_escape_string($conn, $_POST['data']);
    $edited_cmimi = mysqli_real_escape_string($conn, $_POST['cmimi']);

    /**
     * Shohim nese ekziston prona me kete ID tek ky pronar
     */
    $query_id = "SELECT id_prone 
                 FROM prona 
                 WHERE id_pronari = '" . $_SESSION['user_id'] . "' 
                 AND id_prone = '" . $id . "'";

    $result = mysqli_query($conn, $query_id);

    if (!$result) {
        echo json_encode(array("status" => 404, "message" => "Error on DB" . mysqli_error($conn)) . " " . __LINE__);
        exit;
    }

    if (mysqli_num_rows($result) == 0) {
        echo json_encode(array("status" => 404, "message" => "The property does not exist in the database"));
        exit;
    }

    /**
     * Validimi i llojit te prones
     */
    if (empty($edited_lloji_prones)) {
        echo json_encode(array("status" => 404, "message" => "Prona nuk mund te jete bosh"));
        exit;
    }
    if (!preg_match("/^[a-zA-Z\s]*$/", $edited_lloji_prones)) {
        echo json_encode(array("status" => 404, "message" => "Prona duhet te permbaje vetem shkronja"));
        exit;
    }

    /**
     * Validimi i cmimit
     */

    if (!preg_match("/^([0-9']{1,})$/", $edited_cmimi)) {
        echo json_encode(array("status" => 404, "message" => "Cmimi duhet te permbaje vetem numra"));
        exit;
    }
    if (empty($edited_cmimi)) {
        echo json_encode(array("status" => 404, "message" => "Cmimi nuk mund te jete bosh"));
        exit;
    }

    /**
     * Validimi i pershkrimit
     */

        if (!preg_match("/^[0-9a-zA-Z\s]*$/", $edited_pershkrimi)) {
            echo json_encode(array("status" => 404, "message" => "Pershkrimi duhet te kete vetem karaktere dhe numra"));
            exit;
        }
    if (empty($edited_pershkrimi)) {
        echo json_encode(array("status" => 404, "message" => "Cmimi nuk mund te jete bosh"));
        exit;
    }

    /**
     * Editimi i pronave
     */

    $query_update = "UPDATE prona SET  
                     lloji_prones = '" . $edited_lloji_prones . "',
                     pershkrimi = '" . $edited_pershkrimi . "',
                     cmimi = '" . $edited_cmimi . "',
                     data_blerjes = '" . $edited_data . "'                   
                     WHERE id_pronari = '" . $_SESSION['user_id'] . "' 
                     AND id_prone= '" . $id . "' ";


    if (!mysqli_query($conn, $query_update)) {
        echo json_encode(array("status" => 404, "message" => "Error on DB" . mysqli_error($conn)) . " " . __LINE__);
        exit;
    }

    echo json_encode(array("status" => 200, "message" => "Property updated successfully"));
    exit;

} else if ($_POST['action'] == "delete_property_data") {


    $id = mysqli_real_escape_string($conn, $_POST['id']);

    /**
     * Shohim nese ekziston prona me kete ID tek ky pronar
     */
    $query_user_property = "SELECT id_prone 
                            FROM prona 
                            WHERE id_pronari = '" . $_SESSION['user_id'] . "' 
                            AND id_prone = '" . $id . "'";

    $result = mysqli_query($conn, $query_user_property);

    if (!$result) {
        echo json_encode(array("status" => 404, "message" => "Error on DB" . mysqli_error($conn)) . " " . __LINE__);
        exit;
    }

    if (mysqli_num_rows($result) == 0) {
        echo json_encode(array("status" => 404, "message" => "The property does not exist in the database"));
        exit;
    }

    /**
     * Fshirja e prones se pronarit
     */
    $query_delete = "DELETE from prona WHERE id_prone='" . $id . "' AND id_pronari = '" . $_SESSION['user_id'] . "'";
    if (!mysqli_query($conn, $query_delete)) {
        echo json_encode(array("status" => 404, "message" => "Error on DB" . mysqli_error($conn)) . " " . __LINE__);
        exit;
    }

    echo json_encode(array("status" => 200, "message" => "Property deleted successfully"));
    exit;

}