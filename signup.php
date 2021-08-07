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
            <div class="form-group">
                <div class="col-sm-8">
                    <label class="control-label col-sm-5" for="emri">Emri:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" class="form-control" name="name" id="name" autocomplete="off"
                               placeholder="Emri">

                    </div>

                    <span id="name_message" style="margin-left: 215px"></span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-8">
                    <label class="control-label col-sm-5" for="mbiemri">Mbiemri:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" class="form-control" id="surname" name="surname" autocomplete="off"
                               placeholder="Mbiemri">
                    </div>
                    <span id="check_surname" style="margin-left: 215px;"></span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-8">
                    <label class="control-label col-sm-5" for="email">Email:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input type="text" id="email" class="form-control" name="email" autocomplete="off"
                               placeholder="email@abc.com">
                    </div>
                    <p id="email_message" style="margin-left: 215px"></p>
                </div>
            </div>

            <div class="form-group row">

                <div class="col-sm-8">
                    <label class="control-label col-sm-5" for="fjalekalim">Fjalekalimi:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" id="password" class="form-control" name="password">
                    </div>
                    <p id="password_message" style="margin-left: 215px"></p>
                </div>
            </div>
            <div class="form-group row">

                <div class="col-sm-8">
                    <label class="control-label col-sm-5" for="fjalekalim">Konfirmo fjalekalimin:</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" id="confirm_password" class="form-control" name="confirm_password">

                    </div>
                    <p id="confirm_message" style="margin-left: 215px"></p>
                </div>
            </div>

            <div class="row">
                <div class="form-groupcol-xs-8">
                    <div class="col-sm-offset-4 col-sm-8">

                        <input type="button" style="margin-left: 45px" class="btn btn-primary " name="regjistrohu"
                               value="Regjistrohu" onclick="save_data();">

                    </div>

                </div>
            </div>

            <p style="margin-left: 295px; margin-top: 30px;">Jeni regjistruar?<a href="index.php"> Hyr</a></p>
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


    function initializeToast() {
        return toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    }

    function save_data() {
        var error = 0;
        var name = $("#name").val();
        var surname = $("#surname").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();


        /**
         * Validimi i emrit
         */
        var name_regex = /^([a-zA-Z]{1,})$/;
        if (isEmpty(name)) {
            $("#name").addClass("error");
            $("#name_message").text("Emri nuk mund te jete bosh").addClass("changecolor");
            error++;
        } else if (!name.match(name_regex)) {
            $("#name").addClass("error");
            $("#name_message").text("Emri duhet te jete vetem me shkronja").addClass("changecolor");
            error++;
        } else {
            $("#name").removeClass("error");
            $("#name_message").text("");
        }


        /**
         * Validimi i mbiemrit
         */
        var surname_regex = /^([a-zA-Z]{1,})$/;
        if (isEmpty(surname)) {
            $("#surname").addClass("error");
            $("#check_surname").text("Mbiemri nuk mund te jete bosh").addClass("changecolor");
            error++;
        } else if (!surname.match(surname_regex)) {
            $("#surname").addClass("error");
            $("#check_surname").text("Mbiemri duhet te jete vetem me shkronja").addClass("changecolor");
            error++;
        } else {
            $("#surname").removeClass("error");
            $("#check_surname").text("");
        }


        /**
         * Validimi i passwordit
         */
        var password_regex = /^([a-zA-Z0-9]{5,})$/;

        if (isEmpty(password)) {
            $("#password").addClass("error");
            $("#password_message").text("Fjalekalimi nuk mund te jete bosh").addClass("changecolor");
            error++;
        } else if (!password.match(password_regex)) {
            $("#password").addClass("error");
            $("#password_message").text("Password duhet te kete minimumi 5 karaktere qe mund te jene shkronja te medha te vogla ose numra").addClass("changecolor");
            error++;
        } else {
            $("#password").removeClass("error");
            $("#password_message").text("");
        }

        /**
         * Validimi i konfirmimit
         */
        if (isEmpty(confirm_password)) {
            $("#confirm_password").addClass("error");
            $("#confirm_message").text("Kjo fushe nuk mund te jete bosh").addClass("changecolor");
            error++;
        } else if (confirm_password != password) {
            $("#confirm_password").addClass("error");
            $("#confirm_message").text("Password dhe Confirm password nuk perputhen").addClass("changecolor");

            $("#password").addClass("error");
            $("#password_message").text("Password duhet te kete minimumi 5 karaktere qe mund te jene shkronja te medha te vogla ose numra").addClass("changecolor");
            error++;
        } else {
            $("#confirm_password").removeClass("error");
            $("#confirm_message").text("");

            $("#password").removeClass("error");
            $("#password_message").text("");
        }

        /**
         *Validimi i email
         */
        email_regex = /^[^\s@]+@[^\s@]+$/;
        if (isEmpty(email)) {
            $("#email").addClass("error");
            $("#email_message").text("Email nuk mund te jete bosh").addClass("changecolor");
            error++;
        }
        else if(!email.match(email_regex)){

            $("#email").addClass("error");
            $("#email_message").text("Email nuk eshte ne formatin e duhur").addClass("changecolor");
            error++;
        }else{
            $("#email").removeClass("error");
            $("#email_message").text("");

        }


        var data = new FormData;
        data.append("action", "new_user_register");
        data.append("name", name);
        data.append("surname", surname);
        data.append("email", email);
        data.append("password", password);
        data.append("confirm_password", confirm_password);

        if (error == 0) {
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log(res);
                   // var toastr = initializeToast();

                    var response = JSON.parse(res);

                    if (response.status == 200) {
                        // alert("U shtua me sukses!");

                        toastr.success("User-i u shtua", "Sukses!");


                    } else {
                        // console.log(response.message);
                        toastr.error("User-i nuk u shtua", "Kujdes!");

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

