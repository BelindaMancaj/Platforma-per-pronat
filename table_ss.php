<?php
error_reporting(0);
include "connect.php";
?>
<!doctype html>
<html lang="en">
<head>
    <!--<meta charset="UTF-8">-->
    <!--<meta name="viewport"-->
    <!--      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">-->
    <!--<meta http-equiv="X-UA-Compatible" content="ie=edge">-->
    <!--<title>Document</title>-->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"-->
    <!--      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">-->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">-->
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"-->
    <!--        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"-->
    <!--        crossorigin="anonymous"></script>-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>-->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>-->
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">-->
    <!--<script type="text/javascript" charset="utf8"-->
    <!--        src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>-->
    <!--<script src=" https://datatables.net/dev/accessibility/DataTables_1_10/media/js/jquery.dataTables.js"/>-->
    <!--<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>-->

    <style>
        td.details-control {
            background: url('images/details_open.png') no-repeat center center;
            cursor: pointer;
        }

        tr.details td.details-control {
            background: url('images/details_close.png') no-repeat center center;
        }
    </style>
</head>
<body>
<!--<div class="row justify-content-md-center">-->
<!--    <div class="col-md-9">-->
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
<?php

?>
<script>

    function format(d) {
        return d.days;
    }

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
                    lloji_prones: "<?=$_POST['prona']?>"


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

                // sInfo: " Visualizzati da START a END su TOTAL record ",
                // sEmptyTable: " Nessun dato disponibile nella tabella  ",
                // sInfoEmpty: " Visualizzati da 0 a 0 su 0 record ",
                // sInfoFiltered: "(filtrati da MAX record)",
                // // sProcessing: "<i class='fa fa-spinner fa-spin fa-3x fa-fw'></i>Processing...",
                // sSearch: " Ricerca ",
                // sSearchPlaceholder: ' Ricerca ',
                // sLoadingRecords: "Caricamento...",
                // sZeroRecords: " Nessuna corrispondenza trovata ",
                //
                // sLengthMenu: 'Show <select>' +
                //     '<option value="10">10</option>' +
                //     '<option value="30">30</option>' +
                //     '<option value="50">50</option>' +
                //     '<option value="-1">All</option>' +
                //     '</select> records',
                //
                // sLengthMenu: ' Mostra  <select>' +
                //     '<option value="10">10</option>' +
                //     '<option value="30">30</option>' +
                //     '<option value="50">50</option>' +
                //     '<option value="-1">Tutti</option>' +
                //     '</select>  recordi ',
                // oPaginate: {
                //     sFirst: " Prima ",
                //     sNext: " Successiva ",
                //     sLast: " Ultima ",
                //     sPrevious: " Precedente "
                // }
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
</html>
