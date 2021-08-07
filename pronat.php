<?php

if (empty($_SESSION['user'])) {
    header("Location:index.php");
}
include_once("connect.php");

/**
 * Marrim te dhenat e prones
 */
$query_property_data = "SELECT lloji_prones,
                            pershkrimi,
                            cmimi,
                            data_blerjes
                     FROM prona WHERE id_pronari = '" . $_SESSION['user_id'] . "'";

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

<?php include "header.php";?>


    <style>
        .error {
            border: 1px solid red !important;
        }

        .changecolor {
            color: red;
        }
    </style>
<!--</head>-->

    <body>


        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Informacion mbi pronat</h4>
                    </div>

                    <form method="post" class="form-horizontal">
                    <div class="modal-body">

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label class="control-label col-sm-5" for="fjalekalim">Prona:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                                    <input type="text" id="prona1" class="form-control" name="prona1"
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
                                    <input type="text" id="pershkrimi" class="form-control" name="prona2"
                                         placeholder="Pershkrimi i prones" autocomplete="off">

                                </div>
                                <p id="prona_dyte_message" style="margin-left: 215px"></p>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label class="control-label col-sm-5" for="fjalekalim">Data e blerjes:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    <input type="date" id="data" class="form-control" name="data"
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
                                    <input type="text" id="cmimi" class="form-control" name="cmimi"
                                           placeholder="Vendos cmimin" autocomplete="off">

                                </div>
                                <p id="price_message" style="margin-left: 215px"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">

                        <input type="button" style="margin-left: 130px;" class="btn btn-info " id="save" name="save"
                               value="Save" onclick="add_property_data();">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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

            function add_property_data(){

                var prona1 = $('#prona1').val();
                var pershkrimi = $('#pershkrimi').val();
                var cmimi = $('#cmimi').val();
                var data = $('#data').val();


                var newproperty = new FormData;

                newproperty.append("action", "add_new_property");
                newproperty.append("prona1", prona1);
                newproperty.append("pershkrimi", pershkrimi);
                newproperty.append("cmimi", cmimi);
                newproperty.append("data", data);

                $.ajax({
                    method: "POST",
                    url: "ajax.php",
                    data: newproperty,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        console.log(res);

                        var response = JSON.parse(res);

                        if (response.status == 200) {

                            toastr.success("Prona u shtua.", "Sukses!");

                        } else {

                            toastr.error("Pronat nuk u shtuan.", "Kujdes!");

                        }

                    }


                });

            }
        </script>
<?php
