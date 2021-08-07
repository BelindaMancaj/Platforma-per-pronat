<?php
session_start();
include "navbar.php";
if (empty($_SESSION['user_id'])) {
    header("Location:index.php");
}
include_once("connect.php");
/**
 * Merr id me GET nqs ka
 * nqs nuk ka e merr nga session
 */
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
} else {
    $id = $_SESSION['user_id'];
}
/**
 * Marrim te dhenat e userit
 */
$query_users_data = "SELECT emri,
                            mbiemri,
                            email,
                            adresa,
                            tel,
                            foto, 
                            ditelindje
                     FROM perdorues 
                     WHERE id = '" . $id . "'";
// echo $query_users_data;
$result_users_data = mysqli_query($conn, $query_users_data);

if (!$result_users_data) {
    echo "Error ne databaze " . mysqli_error($conn);
}

$user_data = array();
$user_data['emri'] = "";
$user_data['mbiemri'] = "";
$user_data['email'] = "";
$user_data['adresa'] = "";
$user_data['tel'] = "";
$user_data['foto'] = "";
$user_data['ditelindje'] = "";

if (mysqli_num_rows($result_users_data) > 0) {
    $row = mysqli_fetch_assoc($result_users_data);

    $user_data['emri'] = $row['emri'];
    $user_data['mbiemri'] = $row['mbiemri'];
    $user_data['email'] = $row['email'];
    $user_data['adresa'] = $row['adresa'];
    $user_data['tel'] = $row['tel'];
    $user_data['foto'] = $row['foto'];
    $user_data['ditelindje'] = $row['ditelindje'];

}
?>

<?php include "header.php"; ?>
<style>
    .error {
        border: 1px solid red !important;
    }

    body {
        padding-right: 0 !important
    }

    .changecolor {
        color: red;
    }


    .imagePreview {
        width: 100%;
        height: 180px;
        background-position: center center;
        background: url("<?=$user_data['foto']?>");
        background-color: #fff;
        background-size: cover;
        background-repeat: no-repeat;
        display: inline-block;
        box-shadow: 0px -3px 6px 2px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        display: block;
        border-radius: 0px;
        box-shadow: 0px 4px 6px 2px rgba(0, 0, 0, 0.2);
        margin-top: -5px;
    }

    .imgUp {
        margin-bottom: 15px;
    }

    .del {
        position: absolute;
        top: 0px;
        right: 15px;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        background-color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }

    .imgAdd {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #4bd7ef;
        color: #fff;
        box-shadow: 0px 0px 2px 1px rgba(0, 0, 0, 0.2);
        text-align: center;
        line-height: 30px;
        margin-top: 0px;
        cursor: pointer;
        font-size: 15px;
    }

    .center_photo {
        margin-top: 50%;
    }

    .upload_button {
        display: block;
        border-radius: 0px;
        box-shadow: 0px 4px 6px 2px rgba(0, 0, 0, 0.2);
        margin-top: -5px;
        text-align: center;
        padding-top: 10px;
        padding-bottom: 5px;
        background-color: #333333;
        color: #ffffff;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;

    }

</style>
<script>

</script>
<!--</head>-->

<body>
<div class="well" style="margin: 145px 200px; background-color: aliceblue;">

    <!--Fotoja e profilit-->

    <div class="media-body">

        <div class="container-fluid">

            <!-- Profili i perdoruesit-->
            <br>
            <?php
            /**
             * Nqs id e perdoruesit eshte e ndryshme nga
             * id e perdoruesit te loguar,
             * ai nuk ben dot ndryshime
             */
            if ($id != $_SESSION['user_id']) {
                $disabled = 'disabled';
                $access = false;
            } else {
                $disabled = '';
                $access = true;
            }
            ?>

            <div class="container">
                <div class="row">
                    <div class="col-sm-3 imgUp" id="img_div">
                        <div class="col-sm-3">

                        </div>
                        <div class="col-sm-9">
                            <div class="center_photo">
                                <div class="imagePreview" id="show_photo"></div>

                                <input type="hidden" id="photo_path" name="photo_path"
                                       value="<?= $user_data['foto'] ?>">

                                <?php
                                if ($access) {
                                    ?>
                                    <label class="upload_button">
                                        Upload<input type="file" class="uploadFile img" <?= $disabled ?> id="file"
                                                     value="<?= $user_data['foto'] ?>"
                                                     style="width: 0px;height: 0px;overflow: hidden;">
                                    </label>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-9">
                        <form method="post" class="form-horizontal">

                            <div class="form-group" style="margin-top: 5px">
                                <div class="col-sm-8">
                                    <label class="control-label col-sm-5" for="emri">Emri:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input type="text" class="form-control" name="name" id="name" autocomplete="off"
                                               value="<?= $user_data['emri'] ?>" <?= $disabled ?>
                                               placeholder="Vendos emrin">
                                    </div>

                                    <span id="name_message" style="margin-left: 215px"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-8">
                                    <label class="control-label col-sm-5" for="mbiemri">Mbiemri:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input type="text" class="form-control" id="surname" name="surname"
                                               autocomplete="off"
                                               value="<?= $user_data['mbiemri'] ?>" <?= $disabled ?>
                                               placeholder="Vendos mbiemrin">
                                    </div>
                                    <span id="check_surname" style="margin-left: 215px;"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-8">
                                    <label class="control-label col-sm-5" for="email">Email:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-envelope"></i></span>
                                        <input type="text" id="email" class="form-control" name="email"
                                               autocomplete="off"
                                               value="<?= $user_data['email'] ?>" <?= $disabled ?>
                                               placeholder="Vendos emailin">
                                    </div>
                                    <p id="email_message" style="margin-left: 215px"></p>
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-sm-8">
                                    <label class="control-label col-sm-5" for="fjalekalim">Tel:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                                        <input type="text" id="tel" class="form-control" name="tel"
                                               value="<?= $user_data['tel'] ?>" <?= $disabled ?>
                                               placeholder="Vendosni telefonin">
                                    </div>
                                    <p id="tel_message" style="margin-left: 215px"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8">
                                    <label class="control-label col-sm-5" for="fjalekalim">Adresa:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                        <input type="text" id="address" class="form-control" name="address"
                                               value="<?= $user_data['adresa'] ?>" <?= $disabled ?>
                                               placeholder="Vendos adresen">

                                    </div>
                                    <p id="address_message" style="margin-left: 215px"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8">
                                    <label class="control-label col-sm-5" for="ditelindje">Ditelindja:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-calendar"></i></span>
                                        <input type="date" id="ditelindje" class="form-control" name="ditelindje"
                                               value="<?= $user_data['ditelindje'] ?>" <?= $disabled ?>
                                               placeholder="Vendos ditelindjen">

                                    </div>
                                    <p id="ditelindje_message" style="margin-left: 215px"></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-groupcol-xs-8">
                                    <div class="col-sm-offset-1 col-sm-6">
                                        <?php
                                        if ($access) {

                                            ?>
                                            <input type="button" style="margin-left: 250px;" class="btn btn-info "
                                                   id="save" name="save"
                                                   value="Save" onclick="update()">
                                            <input type="button" style="" class="btn btn-warning" data-toggle="modal"
                                                   data-target="#myModal"
                                                   value="Shto prona">

                                            <!--  Modal per shtimin e pronave-->
                                            <?php include "pronat.php"; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                </div><!-- row -->


            </div><!-- container -->
            <p id="photo_message" style="margin-left: 35px"></p>
            <p id="extension_message" style="margin-left: 35px"></p>


        </div>
    </div>
</div>


</body>

<script>

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }


    /**
     * Funksion per foton e profilit
     */
    $(".imgAdd").click(function () {
        $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">' +
            'Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label>' +
            '<i class="fa fa-times del"></i></div>');
    });

    $(document).on("click", "i.del", function () {
        $(this).parent().remove();
    });

    $(function () {
        $(document).on("change", ".uploadFile", function () {
            var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function () { // set image data as background of div
                    uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
                }
            }

        });
    });


    /**
     * Kontrollon nqs fushat jane bosh
     */
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


    function update() {
        var error = 0;

        var name = $("#name").val();
        var surname = $("#surname").val();
        var email = $("#email").val();
        var tel = $("#tel").val();
        var address = $("#address").val();
        var ditelindje = $("#ditelindje").val();
        var files = $("#file")[0].files;
        var path_file = $("#photo_path").val();
        var old_path = '<?= $user_data['foto']?>';


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
            $("#check_surname").text("Nuk mund te jete bosh").addClass("changecolor");
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
         *Validimi i email
         */
        email_regex = /^[^\s@]+@[^\s@]+$/;
        if (isEmpty(email)) {
            $("#email").addClass("error");
            $("#email_message").text("Email nuk mund te jete bosh").addClass("changecolor");
            error++;
        } else if (!email.match(email_regex)) {

            $("#email").addClass("error");
            $("#email_message").text("Email nuk eshte ne formatin e duhur").addClass("changecolor");
            error++;
        } else {
            $("#email").removeClass("error");
            $("#email_message").text("");

        }

        /**
         *Validimi i  tel
         */
        var tel_regex = /^([0-9]{1,})$/;
        if (!isEmpty(address)) {
            if (!tel.match(tel_regex)) {
                $("#tel").addClass("error");
                $("#tel_message").text("Tel duhet te jete vetem me numra").addClass("changecolor");
                error++;
            } else {
                $("#tel").removeClass("error");
                $("#tel_message").text("");
            }
        }

        /**
         *Validimi i adreses
         */
        var address_regex = /^([a-zA-Z0-9]{1,})$/;
        if (!isEmpty(address)) {
            if (!address.match(address_regex)) {
                $("#address").addClass("error");
                $("#address_message").text("Adresa duhet te permbaje vetem shkronja dhe numra").addClass("changecolor");
                error++;
            } else {
                $("#address").removeClass("error");
                $("#address_message").text("");
            }

        }


        var newdata = new FormData;

        newdata.append("action", "update_profile_data");
        newdata.append("name", name);
        newdata.append("surname", surname);
        newdata.append("email", email);
        newdata.append("address", address);
        newdata.append("tel", tel);
        newdata.append("ditelindje", ditelindje);

        /**
         *  Validimi i files
         */
        if (files.length > 0) {
            newdata.append('file', files[0]);
            newdata.append('path_file', "");
            $("#img_div").removeClass("error");
            $("#photo_message").html("");


        } else if (!isEmpty(path_file)) {
            // newdata.append('file', files[0]);
            newdata.append('path_file', path_file);
            $("#img_div").removeClass("error");
            $("#photo_message").html("");

        } else if (isEmpty(path_file)) {
            path_file = old_path;
            newdata.append('path_file', path_file);
            $("#img_div").removeClass("error");
            // console.log(old_path);
            $("#photo_message").html("");

        } else {
            $("#img_div").addClass("error");
            $("#photo_message").text("Vendosni foton e profilit!").addClass("changecolor");
            error++;
        }


        var ext = $('#file').val().split('.').pop().toLowerCase();
        if (isEmpty($('#file').val())) {
            $("#extension_message").text("");
        } else if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
            $("#extension_message").text("Foto nuk eshte ne formatin e duhur!").addClass("changecolor");
        } else {
            $("#extension_message").text("");
        }


        if (error == 0) {
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: newdata,
                cache: false,
                processData: false,
                contentType: false,
                success: function (res) {

                    console.log(res);

                    var response = JSON.parse(res);

                    if (response.status == 200) {

                        toastr.success("Te dhenat u ndryshuan.", "Sukses!");

                        setTimeout(function () {
                            location.reload();
                        }, 2000);

                    } else {

                        toastr.error("Te dhenat nuk u ndryshuan.", "Kujdes!");

                    }

                }

            });
        }
    }

</script>