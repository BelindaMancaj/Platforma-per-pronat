<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<div class="well" style="margin: auto; margin-top:80px;
  width: 50%;">
    <div class="container-fluid">
        <form method="post" class="form-horizontal">


            <div class="form-group">
                <div class="col-sm-8">
                    <label class="control-label col-sm-5" for="email" placeholder="email@abc.com">Email:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input type="text" id="email" class="form-control" name="email" autocomplete="off"
                              placeholder="email@dd.com" >
                    </div>
                    <p id="p" style="margin-left: 215px"></p>
                </div>
            </div>

            <div class="form-group row">

                <div class="col-sm-8">
                    <label class="control-label col-sm-5" for="fjalekalim">Fjalekalimi:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" id="password" class="form-control" name="password">
                    </div>
                    <p id="parag" style="margin-left: 215px"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3"></div>

                <div class="col-sm-9">

                    <input type="submit" class="btn btn-primary " name="login" id = "login" value="LogIn">


                    <a href="signup.php" class="btn btn-primary" id = "register" name = "register">Register</a>

                </div>



            </div>

        </form>

    </div>
</div>
</body>
</html>

        <?php
        session_start();
        include("connect.php");

        if (!empty($_POST['login'])) {

            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            $sql = "SELECT*FROM perdorues WHERE email = '$email' and fjalekalimi = '$password'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $id = $row['id'];
            $count = mysqli_num_rows($result);

            if($count == 1){
           // echo "<p>Login successful</p>";
                $_SESSION['user'] = $email;
                $_SESSION['user_password'] = $password;
                $_SESSION['user_id'] = $id;
                header("Location: profile.php");
            }
            else{
                echo "<p> Login failed</p>";

            }
        }
        ?>

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
