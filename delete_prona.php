<?php

if (empty($_SESSION['user'])) {
    header("Location:index.php");
}
include_once("connect.php");

?>
    <!DOCTYPE html>
    <html lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
        .error {
            border: 1px solid red !important;
        }

        .changecolor {
            color: red;
        }

        #property_button
        {
            display:inline;
            border-radius:0px;
            box-shadow:0px 4px 6px 2px rgba(0,0,0,0.2);
            margin-top:-5px;
        }


    </style>
</head>

<body>


<div class="modal fade" id="deletionModal" role="dialog">
    <div class="modal-dialog modal-sm">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Fshi pronen</h4>
            </div>

            <form method="post" class="form-horizontal">
                <div class="modal-body">
                    <label style="margin-left: 65px; margin-bottom: 30px;">Doni te fshini pronen?</label><br>
                    <input type="button" style="margin-left: 80px;" id="" class="btn btn-danger " id="save" name="po"
                           value="PO" onclick="fshi_prona();">

                    <button type="button" style="margin-left: 0px;"  id="" class="btn btn-info" data-dismiss="modal">JO</button>
            </form>
        </div>

    </div>

</div>
</div>
</body>


<script>

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }


    function fshi_prona(){

        var deleted_property = new FormData;

        deleted_property.append("action", "delete_property_data");
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: deleted_property,
                cache: false,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log(res);

                    var response = JSON.parse(res);

                    if (response.status == 200) {

                        toastr.success("Prona u fshi.", "Sukses!");

                    } else {

                        toastr.error("Prona nuk u fshi.", "Kujdes!");

                    }

                }
            });
        }




</script>
<?php
