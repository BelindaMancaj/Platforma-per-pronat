<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        .error {
            border: 1px solid red !important;
        }

        .changecolor {
            color: red;
        }
    </style>
</head>

<body>
<div class="well" style="margin: auto; margin-top:80px;
  width: 50%;">
    <div class="container-fluid">
        <form method="post" class="form-horizontal">
            <div class="form-group row">

                <div class="form-group row">

                    <div class="col-sm-8">
                        <label class="control-label col-sm-5" for="fjalekalim">Fjalekalimi i ri:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" id="new_password" class="form-control" name="new_password">
                        </div>
                        <p id="new_password_message" style="margin-left: 215px"></p>
                    </div>
                </div>
                <div class="form-group row">

                    <div class="col-sm-8">
                        <label class="control-label col-sm-5" for="fjalekalim">Konfirmo fjalekalimin:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" id="confirm_new_password" class="form-control" name="confirm_new_password">

                        </div>
                        <p id="confirm_new_message" style="margin-left: 215px"></p>
                    </div>
                </div>

                <div class="row">
                    <div class="form-groupcol-xs-8">
                        <div class="col-sm-offset-4 col-sm-8">

                            <input type="button" style="margin-left: 15px" class="btn btn-primary " name="ndrysho_password"
                                   value="Ndrysho fjalekalimin" onclick="update_password();" >

                        </div>

                    </div>
                </div>
             <a href = "index.php" style="margin: 150px  330px; ">Shko te login</a>
        </form>

    </div>
</div>

<script>

    function isEmpty(val) {

        if (val === undefined)
            return true;

        if (typeof (val) == 'function' || typeof (val) == 'number' || typeof (val) == 'boolean' || Object.prototype.toString.call(val) === '[object Date]')
            return false;

        if (val == null || val.length === 0)
            return true;

        if (typeof (val) == "object") {
            // empty object

            var r = true;

            for (var f in val)
                r = false;

            return r;
        }

        return false;
    }

    function update_password() {
        var error = 0;
        var password = $("#new_password").val();
        var confirm_password = $("#confirm_new_password").val();


        /**
         * Validimi i passwordit
         */
        var password_regex = /^([a-zA-Z0-9]{5,})$/;

        if (isEmpty(password)) {
            $("#new_password").addClass("error");
            $("#new_password_message").text("Fjalekalimi nuk mund te jete bosh").addClass("changecolor");
            error++;
        } else if (!password.match(password_regex)) {
            $("#new_password").addClass("error");
            $("#new_password_message").text("Password duhet te kete minimumi 5 karaktere qe mund te jene shkronja te medha te vogla ose numra").addClass("changecolor");
            error++;
        } else {
            $("#new_password").removeClass("error");
            $("#new_password_message").text("");

        }


        /**
         * Validimi i konfirmimit
         */
        if (isEmpty(confirm_password)) {
            $("#confirm_new_password").addClass("error");
            $("#confirm_new_message").text("Kjo fushe nuk mund te jete bosh").addClass("changecolor");
            error++;
        } else if (confirm_password != password) {
            $("#confirm_new_password").addClass("error");
            $("#confirm_new_message").text("Password dhe Confirm password nuk perputhen").addClass("changecolor");

            $("#new_password").addClass("error");
            $("#new_password_message").text("Password duhet te kete minimumi 5 karaktere qe mund te jene shkronja te medha te vogla ose numra").addClass("changecolor");
            error++;
        } else {
            $("#confirm_new_password").removeClass("error");
            $("#confirm_new_message").text("");

            $("#new_password").removeClass("error");
            $("#new_password_message").text("");
        }

        var data_password = new FormData;
        data_password.append("action", "update_password");
        data_password.append("password", password);
        data_password.append("confirm_password", confirm_password);

        if (error == 0) {
            $.ajax({
                method: "POST",
                url: "password_ajax.php",
                data: data_password,
                cache: false,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log(res);

                    var response = JSON.parse(res);

                    if (response.status == 200) {
                        // alert("U shtua me sukses!");

                        toastr.success("Fjalekalimi juaj u ndryshua.", "Sukses!");

                    } else {

                        toastr.error("Fjalekalimi juaj nuk mund te jete i meparshmi.", "Kujdes!");

                    }

                }

            });


    }



    }
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

</script>

</body>
</html>
