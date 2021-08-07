<?php
session_start();
include "connect.php";
include "navbar.php";
if (empty($_SESSION['user_id'])) {
    header("Location:index.php");
}

/**
 * Marrim te dhenat e prones
 */
$query_property_data = "SELECT  id_prone,
                                lloji_prones,
                                pershkrimi,
                                cmimi,
                                data_blerjes
                         FROM prona WHERE id_pronari = '" . $_SESSION['user_id'] . "'";

$result_property_data = mysqli_query($conn, $query_property_data);

if (!$result_property_data) {
    echo "Error ne databaze " . mysqli_error($conn);
}

$property_data = array();
while ($row = mysqli_fetch_assoc($result_property_data)) {
    $property_data[$row['id_prone']]['id'] = $row['id_prone'];
    $property_data[$row['id_prone']]['lloji_prones'] = $row['lloji_prones'];
    $property_data[$row['id_prone']]['pershkrimi'] = $row['pershkrimi'];
    $property_data[$row['id_prone']]['cmimi'] = $row['cmimi'];
    $property_data[$row['id_prone']]['data_blerjes'] = $row['data_blerjes'];
}

?>

<?php include "header.php"; ?>
<style>

    .container {
        padding: 2rem 0rem;
    }

    h4 {
        margin: 2rem 0rem 1rem;
    }

    .table-image {

    td, th {
        vertical-align: middle;
    }

    }
    table tr {
        counter-increment: row-num;
    }

    table tr td:first-child::before {
        content: counter(row-num) "";
    }

    .error {
        border: 1px solid red !important;
    }

    .changecolor {
        color: red;
    }

</style>
<body>

<div class="container">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                <tr style="background-color: #dbcda2">
                    <th scope="col">Nr</th>
                    <th scope="col">Lloji i prones</th>
                    <th scope="col">Pershkrimi</th>
                    <th scope="col">Data e blerjes</th>
                    <th scope="col">Cmimi</th>
                    <th scope="col">Ndrysho</th>


                </tr>
                </thead>


        </div>
    </div>
</div>
<?php foreach ($property_data as $id => $data) { ?>
    <tr>
        <td></td>
        <td><span id="lloji_prones_<?= $id ?>"><?= $data['lloji_prones']; ?></span></td>
        <td><span id="pershkrimi_<?= $id ?>"><?= $data['pershkrimi']; ?></span></td>
        <td><span id="data_blerjes_<?= $id ?>"><?= $data['data_blerjes']; ?></span></td>
        <td><span id="cmimi_<?= $id ?>"><?= $data['cmimi']; ?></span></td>
        <td>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" value=""
                        onclick="fillModalEdit('<?= $id ?>')">
                    <i class="glyphicon glyphicon-pencil" name="edit_property"></i></button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal"><i
                            class="glyphicon glyphicon-trash" onclick="fillModalDelete('<?= $id ?>')"></i></button>
            </div>

        </td>

    </tr>

<?php } ?>
</table>


<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" name="prone_id" id="prone_id_delete">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label style="margin-left: 65px; margin-bottom: 30px;">Doni te fshini pronen?</label><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteProna()">Delete</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" name="id_prona_edit_modal" id="id_prona_edit_modal">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edito pronat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-8">
                        <label class="control-label col-sm-5" for="fjalekalim">Prona:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                            <input type="text" id="lloji_prones_edit_modal_<?= $id ?>" class="form-control"
                                   name="lloji_prones_edit_modal"
                                   value="<?= $data['lloji_prones']; ?>" placeholder="Vendos llojin e prones"
                                   autocomplete="off">

                        </div>
                        <p id="edit_prona_message" style="margin-left: 160px"></p>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-8">
                        <label class="control-label col-sm-5">Pershkrimi:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                            <input type="text" id="pershkrimi_edit_modal_<?= $id ?>" class="form-control"
                                   name="pershkrimi_edit_modal"
                                   value="<?= $data['pershkrimi']; ?>" placeholder="Pershkrimi i prones"
                                   autocomplete="off">

                        </div>
                        <p id="edit_pershkrimi_message" style="margin-left: 160px;"></p>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-8">
                        <label class="control-label col-sm-5">Data e blerjes:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="date" id="data_edit_modal_<?= $id ?>" class="form-control"
                                   name="data_edit_modal"
                                   value="<?= $data['data_blerjes']; ?>" placeholder="Vendos daten e blerjes"
                                   autocomplete="off">

                        </div>
                        <p id="edit_data_message" style="margin-left: 215px"></p>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-8">
                        <label class="control-label col-sm-5">Cmimi i prones:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-euro"></i></span>
                            <input type="text" id="cmimi_edit_modal_<?= $id ?>" class="form-control"
                                   name="cmimi_edit_modal"
                                   value="<?= $data['cmimi']; ?>" placeholder="Vendos cmimin" autocomplete="off">

                        </div>
                        <p id="edit_price_message" style="margin-left: 160px; "></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="editProna()">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


</body>

<script>
    function isEmpty(val) {

        if (val === undefined)
            return true;

        if (typeof (val) == 'function' || typeof (val) == 'number' || typeof (val) == 'boolean' || Object.prototype.toString.call(val) === '[object Date]')
            return false;

        if (val == null || val.length === 0)
            return true;

        if (typeof (val) == "object") {

            var r = true;

            for (var f in val)
                r = false;

            return r;
        }

        return false;
    }

    function fillModalDelete(id) {
        $("#prone_id_delete").val(id);

    }

    function deleteProna() {
        var id = $("#prone_id_delete").val();


        var deleted_property = new FormData;

        deleted_property.append("action", "delete_property_data");
        deleted_property.append("id", id);

        $.ajax({
            method: "POST",
            url: "ajax.php",
            data: deleted_property,
            cache: false,
            processData: false,
            contentType: false,
            success: function (res) {
                console.log(res);
                console.log(id);

                var response = JSON.parse(res);

                if (response.status == 200) {

                    toastr.success("Prona u fshi.", "Sukses!");
                    setTimeout(function () {
                        location.reload();
                    }, 1000);

                } else {

                    toastr.error("Prona nuk u fshi.", "Kujdes!");

                }

            }

        });

    }

    function fillModalEdit(id) {

        $("#id_prona_edit_modal").val(id);

    }

    function editProna() {
        var error = 0;
        var id = $("#id_prona_edit_modal").val();
        var lloji_prones = $("#lloji_prones_edit_modal_" + id).val();
        var pershkrimi = $("#pershkrimi_edit_modal_" + id).val();
        var data = $("#data_edit_modal_" + id).val();
        var cmimi = $("#cmimi_edit_modal_" + id).val();

        // console.log(lloji_prones);
        /**
         * Validimi i llojit te prones
         */
        var property_regex = /^[a-zA-Z\s]*$/;
        if (isEmpty(lloji_prones)) {
            $("#lloji_prones_edit_modal").addClass("error");
            $("#edit_prona_message").text("Prona nuk mund te jete bosh").addClass("changecolor");
            error++;
        } else if (!lloji_prones.match(property_regex)) {
            $("#lloji_prones_edit_modal").addClass("error");
            $("#edit_prona_message").text("Prona duhet te permbaje vetem shkronja").addClass("changecolor");
            error++;
        } else {
            $("#lloji_prones_edit_modal").removeClass("error");
            $("#edit_prona_message").text("");
        }

        /**
         *  Validimi i pershkrimit
         */
        var des_regex = /^([a-zA-Z0-9\s]{1,})*$/;
        if (!isEmpty(pershkrimi)) {
            if (!pershkrimi.match(des_regex)) {
                $("#pershkrimi_edit_modal").addClass("error");
                $("#edit_pershkrimi_message").text("Pershkrimi duhet te permbaje vetem shkronja dhe numra").addClass("changecolor");
                error++;
            } else {
                $("#pershkrimi_edit_modal").removeClass("error");
                $("#edit_pershkrimi_message").text("");
            }
        } else {
            $("#pershkrimi_edit_modal").addClass("error");
            $("#edit_pershkrimi_message").text("Pershkrimi nuk mund te jete bosh").addClass("changecolor");
            error++;
        }

        /**
         *Validimi i  cmimit
         */
        var cmimi_regex = /^([0-9]{1,})$/;
        if (!isEmpty(cmimi)) {
            if (!cmimi.match(cmimi_regex)) {
                $("#cmimi_edit_modal").addClass("error");
                $("#edit_price_message").text("Cmimi duhet te permbaje vetem numra").addClass("changecolor");
                error++;
            } else {
                $("#cmimi_edit_modal").removeClass("error");
                $("#edit_price_message").text("");
            }
        } else {
            $("#cmimi_edit_modal").addClass("error");
            $("#edit_price_message").text("Cmimi nuk mund te jete bosh").addClass("changecolor");
            error++;
        }


        var edited_property = new FormData;
        edited_property.append("action", "edit_property_data");
        edited_property.append("id", id);
        edited_property.append("lloji_prones", lloji_prones);
        edited_property.append("pershkrimi", pershkrimi);
        edited_property.append("data", data);
        edited_property.append("cmimi", cmimi);


        if (error == 0) {
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
                        toastr.success("Te dhenat u ndryshuan.", "Sukses!");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);

                    } else {
                        toastr.error("Te dhenat nuk u ndryshuan.", "Kujdes!");

                    }

                }

            });
        }

    }

</script>
</html>
