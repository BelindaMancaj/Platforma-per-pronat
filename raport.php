<?php
session_start();
error_reporting(0);
include "connect.php";
include "navbar.php";
if (empty($_SESSION['user_id'])) {
    header("Location:index.php");
}
function printArray($arr = array())
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

/**
 * Funksion qe kthen 0 nqs perdoruesi nuk ka prona
 */
function zero($vlera)
{
    if (!$vlera) {
        return 0;
    }
    return $vlera;
}

/**
 * Marrim perdoruesit per filtrat
 */
$query_filter_name = "SELECT emri
                          FROM perdorues
                          GROUP BY emri";
$result_filter_name = mysqli_query($conn, $query_filter_name);
if (!$result_filter_name) {
    echo "Error ne databaze " . mysqli_error($conn);
}

$name['emri'] = array();
while ($namerows = mysqli_fetch_assoc($result_filter_name)) {
    $name['emri'][] = $namerows['emri'];
}

/**
 * Marrim llojet e pronave per filtrat
 */
$query_filter = "SELECT DISTINCT lloji_prones
                     FROM prona
                     GROUP BY lloji_prones";
$result = mysqli_query($conn, $query_filter);
if (!$result) {
    echo "Error ne databaze " . mysqli_error($conn);
}
$lloji['lloji_prones'] = array();
while ($row2 = mysqli_fetch_assoc($result)) {
    $lloji['lloji_prones'][] = $row2['lloji_prones'];
}
/**
 * Llogarit te dhenat per filtrat
 */
$query_property_data = "SELECT perdorues.id,
                               emri,
                               mbiemri,
                               lloji_prones,
                               data_blerjes,
                               cmimi,
                               pershkrimi,
                               id_prone
                         FROM perdorues
                         LEFT JOIN prona
                         ON prona.id_pronari=perdorues.id WHERE 1 = 1 ";

if (isset($_POST['filter'])) {

    /**
     * Filtri me llojin e prones
     */
    if (!empty($_POST['prona'])) {

        $prona_array_escape = array();
        foreach ($_POST['prona'] as $prona_single_escape) {
            $prona_array_escape[] = mysqli_real_escape_string($conn, $prona_single_escape);
        }
        $prona_option = "'" . implode("','", $prona_array_escape) . "'";
        $query_property_data .= " AND lloji_prones IN ($prona_option) ";
    }

    /**
     * Filtri me date
     */
    if (!empty($_POST['date'])) {
        $startdate = explode(" - ", $_POST['date']);
        $query_property_data .= " AND data_blerjes >='" . $startdate[0] . "' AND data_blerjes <='" . $startdate[1] . "' ";
    }
    /**
     * Filtri me cmimin
     */
    if (!empty($_POST['cmimi'])) {
        $cmimi_option = mysqli_real_escape_string($conn, $_POST['cmimi']);
        if (preg_match("/^([0-9']{1,})$/", $cmimi_option)) {
            $query_property_data .= " AND cmimi >='" . $cmimi_option . "'";
        }
    }

    /**
     * Filtri me emer
     */

    if (!empty($_POST['names'])) {

        $name_array_escape = array();
        foreach ($_POST['names'] as $name_single_escape) {
            $name_array_escape[] = mysqli_real_escape_string($conn, $name_single_escape);
        }
        $name_option = "'" . implode("','", $name_array_escape) . "'";
        $query_property_data .= " AND emri IN ($name_option) ";
    }
}

$result_property_data = mysqli_query($conn, $query_property_data);

if (!$result_property_data) {
    echo "Error ne databaze " . mysqli_error($conn);
}

$property_data = array();
while ($row = mysqli_fetch_assoc($result_property_data)) {

    $prone_id = $row['id_prone'];
    $date_key = $row['data_blerjes'];
    $user_key = $row['emri'];
    $user_id = $row['id'];
    $property_key = $row['lloji_prones'];
    $property_data['User'][$user_key]['emri'] = $row['emri'];
    $property_data['User'][$user_key]['id'] = $user_id;
    /**
     * Veprimet me poshte behen vetem kur perdoruesi ka prona
     */
    if ($prone_id) {

        /**
         * Marrim te dhenat e prones
         */
        $property_data['Pronat'][$prone_id]['id'] = $row['id_prone'];
        $property_data['Pronat'][$prone_id]['lloji_prones'] = $row['lloji_prones'];
        $property_data['Pronat'][$prone_id]['pershkrimi'] = $row['pershkrimi'];
        $property_data['Pronat'][$prone_id]['cmimi'] = $row['cmimi'];
        $property_data['Pronat'][$prone_id]['data_blerjes'] = $row['data_blerjes'];
        /**
         * Marrim te dhenat e pronarit
         */
        $property_data['User'][$user_key]['pronatotal']++;
        $property_data['User'][$user_key]['cmimitotal'] += $row['cmimi'];
        $property_data['User']['totali']['pronatotal']++;
        $property_data['User']['totali']['cmimitotal'] += $row['cmimi'];
        /**
         * Marrim numrin e pronave dhe cmimin total per cdo date
         */
        $property_data['Data'][$date_key]['date_pronatotal']++;
        $property_data['Data'][$date_key]['date_cmimitotal'] += $row['cmimi'];
        $property_data['Data']['date_totali']['pronatotal']++;
        $property_data['Data']['date_totali']['cmimitotal'] += $row['cmimi'];
    }
}
$lloji_encoded = json_encode($_POST['prona']);
$emri_encoded = json_encode($_POST['names']);

?>

<?php include_once "header.php"; ?>
<style>
    td.details-control {
        background: url('images/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.details td.details-control {
        background: url('images/details_close.png') no-repeat center center;
    }
</style>
<body>

<div class="container">

    <form action="raport.php" method="post">
        <div class="row" style="margin-top: 60px">
            <div class="form-group">
                <div class="col-xs-3">
                    <label>Lloji i prones:</label>
                    <select class="js-example-basic-multiple form-control" name="prona[]" multiple="multiple">
                        <?php
                        $sel = "selected";
                        $selected_array = $_POST['prona'];

                        foreach ($selected_array as $opt) { ?>
                            <option value="<?= $opt ?>" <?= $sel ?> > <?= $opt ?> </option>
                            <?php
                        }
                        foreach ($lloji['lloji_prones'] as $i => $emer_prone) { ?>
                            <option><?= ucwords($emer_prone) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-xs-3">
                    <label style="margin-left: 10px">Data e blerjes:</label>
                    <div id="reportrange">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <input type="text" id="date" name="date" class="fushadate" value="<?php echo $_POST["date"]; ?>"
                               readonly>
                        <i class="fa fa-caret-down"></i>
                    </div>
                </div>

                <div class="col-xs-3">
                    <label>Cmimi:</label>
                    <input type="number" id="cmimi" name="cmimi" class="fushadate" placeholder="Vendos cmimin"
                           value="<?php echo $_POST["cmimi"]; ?>" autocomplete="off">
                    <p id="msg"></p>
                </div>

                <div class="col-xs-3">
                    <label>Emri i pronarit:</label>
                    <select class="js-example-basic-multiple form-control" name="names[]" multiple="multiple">
                        <?php
                        $sel = "selected";
                        $selected_array = $_POST['names'];
                        foreach ($selected_array as $opt) { ?>
                            <option value="<?= $opt ?>" <?= $sel ?> > <?= $opt ?> </option>
                            <?php
                        }
                        foreach ($name['emri'] as $n => $emer_pronari) { ?>
                            <option><?= ucwords($emer_pronari) ?></option>
                        <?php } ?>
                    </select>

                </div>
            </div>
            <div class="row" style="margin-top: 80px; margin-left: 3px ">
                <div class=" col-xs-4">
                    <input type="submit" style="margin-right: 90px;  position: -webkit-sticky; /* Safari */
  position: sticky;" class="btn btn-info " id="filter" name="filter"
                           value="Filter">
                    <br><br>
                </div>
            </div>
    </form>

</div>
<br>
<hr>
<h3>Tabela per prona</h3>
<table id="example" class="display table cell-border table-bordered dataTable" style="width:100%; text-align: center;">
    <thead>
    <tr style="background-color: #dbcda2">
        <th class="txtb">Lloji i prones</th>
        <th class="txtb">Pershkrimi</th>
        <th class="txtb">Data</th>
        <th class="txtb">Cmimi</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($property_data['Pronat'] as $id => $rowdata) {
        ?>
        <tr>
            <td><?= $rowdata['lloji_prones'] ?></td>
            <td><?= $rowdata['pershkrimi'] ?></td>
            <td><?= $rowdata['data_blerjes'] ?></td>
            <td><?= $rowdata['cmimi'] ?></td>
        </tr>
    <?php } ?>
    </tbody>

</table>
<br><br><br>
<br>
<hr>
<h3>Tabela per pronare</h3>
<table id="example2" class="display table cell-border table-bordered dataTable" style="width:100%; text-align: center;">
    <thead>
    <tr style="background-color: #dbcda2">
        <th class="txtb">Emri</th>
        <th class="txtb">Numri i pronave</th>
        <th class="txtb">Cmimi total</th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($property_data['User'] as $i => $rows) {
        if ($i == 'totali') {
            continue;
        }
        $id = $rows['id'];
        ?>
        <tr>
            <td><a href="profile.php?id=<?= $rows['id']; ?>"><?= $rows['emri'] . " " . $rows['mbiemri'] ?></a></td>
            <td><?= zero($rows['pronatotal']) ?></td>
            <td><?= zero($rows['cmimitotal']) ?></td>
        </tr>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <th>Total:</th>
        <th class="txtb"><?= $property_data['User']['totali']['pronatotal'] ?></th>
        <th class="txtb"><?= $property_data['User']['totali']['cmimitotal'] . " $" ?></th>
    </tr>
    </tfoot>
</table>

<br>
<hr>
<h3>Tabela per javet</h3>

<table id="access_table_grant" name="access_table_grant"
       class="display table cell-border table-bordered dataTable"
       style="width:100%">
    <thead style="background-color: #dbcda2">
    <th></th>
    <th>Data</th>
    <th>Numri i pronave</th>
    <th>Cmimi</th>
    </thead>
</table>

</body>
</html>

<script>

    /**
     * Krijon tabelat
     */
    $(document).ready(function () {
        $('#example').DataTable();
        $('#example2').DataTable();

    });
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

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


    $(document).ready(function () {
        $('.js-example-basic-multiple').select2();
    });

</script>

<script type="text/javascript">

    /**
     * Funksioni per date range picker
     */
    $(function () {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#date').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);

    });

    /**
     * Krijon tabelen te row details
     */
    function format(d) {
        return "<table class='table' style='text-align: center'><thead><tr style='background-color: #ddd'><td>Data</td>" +
            "<td>Numri i pronave</td><td>Cmimi</td></tr></thead>" + d.days + "</table>";
    }

    /**
     * Krijon datatable
     */
    $(document).ready(function () {
        var dt = $('#access_table_grant').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            serverMethod: 'POST',
            ajax: {
                url: "serverside.php",
                data: {
                    action: "show_date_datatable",
                    lloji_prones: <?= $lloji_encoded?>,
                    filter_date: "<?= $_POST['date']?>",
                    emri: <?= $emri_encoded?>,
                    cmimi: "<?= $_POST['cmimi']?>"
                }
            },
            //Heq order nga kolona numri i pronave
            'columnDefs': [{
                "targets": [2],
                "orderable": false
            }],
            columns: [
                {
                    "class": "details-control",
                    "orderable": false,
                    "data": null,
                    "defaultContent": ""
                },
                {data: "data_blerjes"},
                {data: "numri"},
                {data: "cmimi"},

            ],
            "order": [[1, 'asc']],
            dom: '<"html5buttons"B>lTfgitp',
            oLanguage: {
                buttons: {
                    copyTitle: 'Copia to appunti',
                    copySuccess: {
                        _: 'Copiato %d rige to appunti',
                        1: 'Copiato 1 riga'
                    }
                },
            },
        });


        // Array to track the ids of the details displayed rows
        var detailRows = [];

        $('#access_table_grant tbody').on('click', '.details-control', function () {
            var tr = $(this).parents('tr');
            var row = dt.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details bg-light');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details bg-light');
                row.child(format(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });

        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on('draw', function () {
            $.each(detailRows, function (i, id) {
                $('#' + id + ' td:first-child').trigger('click');
            });
        });
    });
</script>