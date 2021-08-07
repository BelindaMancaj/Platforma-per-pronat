<?php
include_once("connect.php");
if (empty($_SESSION['user_id'])) {
    header("Location:index.php");
}


if (!empty($_POST['save'])) {
    $emri_prones = mysqli_real_escape_string($conn, $_POST['property_type']);
    $pershkrimi = mysqli_real_escape_string($conn, $_POST['pershkrimi_prones']);

    $sql ="SELECT * FROM prona WHERE lloji_prones='".$emri_prones."' AND pershkrimi='".$pershkrimi."'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "Error ne databaze " . mysqli_error($conn);
    }
    $data_row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $id_prone = $data_row['id_prone'];
    $count = mysqli_num_rows($result);
}


/**
 * Marrim te dhenat e prones
 */
    $query_property_data = "SELECT 
                                lloji_prones,
                                pershkrimi,
                                cmimi,
                                data_blerjes
                             FROM prona
             WHERE  id_pronari = '" . $_SESSION['user_id'] . "' AND id_prone = '".$_SESSION['property_id']."' ";

    $result_property_data = mysqli_query($conn, $query_property_data);

    if (!$result_property_data) {
        echo "Error ne databaze " . mysqli_error($conn);
    }

    $property_data = array();
    $property_data['lloji_prones'] = "";
    $property_data['pershkrimi'] = "";
    $property_data['cmimi'] = "";
    $property_data['data_blerjes'] = "";


    if (mysqli_num_rows($result_property_data) > 0) {
        $row = mysqli_fetch_assoc($result_property_data);

        $property_data['lloji_prones'] = $row['lloji_prones'];
        $property_data['pershkrimi'] = $row['pershkrimi'];
        $property_data['cmimi'] = $row['cmimi'];
        $property_data['data_blerjes'] = $row['data_blerjes'];

    }

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


    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ndrysho te dhenat e pronave</h4>
                </div>

                <form method="post" class="form-horizontal">
                    <div class="modal-body">

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label class="control-label col-sm-5" for="fjalekalim">Prona:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                                    <input type="text" id="property_type" class="form-control" name="property_type" value="<?= $property_data['lloji_prones']?>"
                                           placeholder="Vendos llojin e prones" autocomplete="off">

                                </div>
                                <p id="prona_message" style="margin-left: 215px"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label class="control-label col-sm-5" for="prona2">Pershkrimi:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                                    <input type="text" id="pershkrimi_prones" class="form-control" name="pershkrimi_prones"
                                           value="<?= $property_data['pershkrimi']?>"   autocomplete="off" placeholder="Pershkrimi i prones">

                                </div>
                                <p id="prona_dyte_message" style="margin-left: 215px"></p>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label class="control-label col-sm-5" for="fjalekalim">Data e blerjes:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    <input type="date" id="data_blerjes" class="form-control" name="data_blerjes"  value="<?= $property_data['data_blerjes']?>"
                                           placeholder="Vendos daten e blerjes" autocomplete="off">

                                </div>
                                <p id="data_message" style="margin-left: 215px"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label class="control-label col-sm-5" for="fjalekalim">Cmimi i prones:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-euro"></i></span>
                                    <input type="text" id="cmimi_prones" class="form-control" name="cmimi_prones"  value="<?= $property_data['cmimi']?>"
                                           placeholder="Vendos cmimin" autocomplete="off">

                                </div>
                                <p id="price_message" style="margin-left: 215px"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">

                        <input type="button"  id="property_button" class="btn btn-info " id="save" name="save"
                               value="Save" onclick="edit_property_data();">

                        <button type="button" id="property_button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
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

        function edit_property_data(){

            var prona1 = $('#property_type').val();
            var pershkrimi = $('#pershkrimi_prones').val();
            var cmimi = $('#cmimi_prones').val();
            var data = $('#data_blerjes').val();

            var edited_property = new FormData;

            edited_property.append("action", "edit_property_data");
            edited_property.append("prona1", prona1);
            edited_property.append("pershkrimi", pershkrimi);
            edited_property.append("cmimi", cmimi);
            edited_property.append("data", data);

            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: edited_property,
                cache: false,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log(res);

                    var response = JSON.parse(res);

                    if (response.status == 200) {

                        toastr.success("Prona u ndryshua.", "Sukses!");

                    } else {

                        toastr.error("Prona nuk u ndryshua.", "Kujdes!");

                    }

                }

            });
        }

    </script>

