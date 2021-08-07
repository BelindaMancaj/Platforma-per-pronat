
<?php
session_start();
include "connect.php";
$id = $_GET['id_prone'];

$qry = mysqli_query($conn,"select * from prona where id_prone='$id'");

$data = mysqli_fetch_array($qry);

if(isset($_POST['update']))
{
    $lloji_prones = $_POST['Lloji_prones'];
    $pershkrimi = $_POST['peshkrimi'];
    $cmimi = $_POST['cmimi'];
    $data = $_POST['data_blerjes'];

    $edit_property = "UPDATE prona SET lloji_prones = '$lloji_prones',
                                        pershkrimi = '$pershkrimi',
                                        cmimi = '$cmimi',
                                        data_blerjes = '$data' WHERE id_prone ='$id'";

    $edit = mysqli_query($conn, $edit_property);
    if($edit)
    {
        mysqli_close($conn);

        exit;
    }
    else
    {
        echo mysqli_error($conn);
    }
}
?>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ndrysho pronat</h4>
            </div>

            <form method="post" class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="control-label col-sm-5" for="fjalekalim">Prona:</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                                <input type="text" id="prona1" class="form-control" name="prona1"
                                       placeholder="Vendos llojin e prones">

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
                                       placeholder="Pershkrimi i prones">

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
                                       placeholder="Vendos daten e blerjes">

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
                                       placeholder="Vendos cmimin">

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
<script>


    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

