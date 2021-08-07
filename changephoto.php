    <?php

    include ('connect.php');
    session_start();


    if (isset($_FILES['file']))  {

        $filename = $_FILES['file']['name'];
          $defaultImage = 'fotoprofili.jpg';
         if($filename != ""){
            $image = $filename;

        }
        else{

            $image = $defaultImage;
        }

        $location = "images/" . $filename;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);

        $valid_extensions = array("jpg", "jpeg", "png");

        if (in_array($imageFileType, $valid_extensions)) {

            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {

                $sql = "UPDATE perdorues SET foto ='".$image."' WHERE id ='".$_SESSION['user_id']."'";
                   mysqli_query($conn, $sql);

            }
        }

    }

    ?>
