<?php
error_reporting(0);

include "connect.php";
function printArray($arr = array())
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

if ($_POST["action"] == "show_date_datatable") {
    /**
     * Filtri me llojin e prones
     */
    $lloji_decoded = $_POST['lloji_prones'];
    if (!empty($lloji_decoded)) {
        $prona_array_escape = array();
        foreach ($lloji_decoded as $prona_single_escape) {
            $prona_array_escape[] = mysqli_real_escape_string($conn, $prona_single_escape);
        }
        $prona_option = "'" . implode("','", $prona_array_escape) . "'";
        $query_property_data = " AND lloji_prones IN ($prona_option) ";
    }
    /**
     * Filtri me date
     */
    if (!empty($_POST['filter_date'])) {
        // echo $_POST['date'];
        $startdate = explode(" - ", $_POST['filter_date']);
        $query_property_data .= " AND data_blerjes >='" . $startdate[0] . "' AND data_blerjes <='" . $startdate[1] . "' ";
        // echo $query_property_data;
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
    $emri_decoded = $_POST['emri'];
    if (!empty($emri_decoded)) {
        $name_array_escape = array();
        foreach ($emri_decoded as $name_single_escape) {
            $name_array_escape[] = mysqli_real_escape_string($conn, $name_single_escape);
        }
        $name_option = "'" . implode("','", $name_array_escape) . "'";
        $name_data = " AND emri IN ($name_option) ";
    }
    /*************** Seksioni i pare
     * draw -> sa here eshte manipuluar tabela, apo numri i thirrjeve ajax pa bere refresh faqjen
     * columnIndex -> ruhen numrat e kolonave
     * columnSortOrder -> ruhet lloji i renditjes (DESC apo ASC)
     * searchValue -> vlera qe po behet serach
     */
    $draw = $_POST['draw'];
    $limit_start = $_POST['start'];
    $limit_end = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = mysqli_real_escape_string($conn, $_POST['search']['value']);
    $searchQuery = " ";

    if ($searchValue != '') {
        $searchQuery = " AND data_blerjes LIKE '%" . $searchValue . "%' ";
    }
    // Rasti kur zgjidhet tutti duhet te hiqen te gjitha limitimet ne pagination
    if ($limit_end == "-1") {
        $pagination = "";
    } else {
        $pagination = "LIMIT " . $limit_start . ", " . $limit_end;
    }

    /*** Tregon numrin total te rekordeve  ***/
    $query_without_ftl = "SELECT COUNT(*) AS allcount	
                            FROM prona 	
                            WHERE 1=1 ";
    $sel = mysqli_query($conn, $query_without_ftl);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $query_with_ftl = "SELECT COUNT(*) AS allcount	
                            FROM prona	
                            WHERE 1=1 " . $searchQuery . " " . $query_property_data;

    $sel = mysqli_query($conn, $query_with_ftl);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];

    /*** Fetch records ***/
    $query_prona = "SELECT perdorues.id,
                               emri,
                               mbiemri,
                               lloji_prones,
                               data_blerjes,
                               cmimi,
                               pershkrimi,
                               id_prone
                         FROM perdorues
                         LEFT JOIN prona
                         ON prona.id_pronari=perdorues.id WHERE 1 = 1 " .
        $searchQuery . " " .
        $query_property_data . " " .
        $name_data .
        " ORDER BY " .
        $columnName . " " .
        $columnSortOrder . " " .
        $pagination;

    $result_prona = mysqli_query($conn, $query_prona);

    if (!$result_prona) {
        echo "Error ne databaze " . mysqli_error($conn);
    }

    $data = array();
    $property_data = array();
    while ($row = mysqli_fetch_assoc($result_prona)) {

        $prone_id = $row['id_prone'];
        $date_key = $row['data_blerjes'];
        $week_number = date("W", strtotime($row['data_blerjes']));
        $year = substr($date_key, 0, 4);
        $user_key = $row['emri'];
        $user_id = $row['id'];
        $property_key = $row['lloji_prones'];
        $week_key = $year . "W" . $week_number;
        /**
         * Marrim numrin e pronave dhe cmimin total per cdo date
         */
        if (($prone_id) && ($date_key != "")) {
            $property_data[$week_key]['date_pronatotal']++;
            $property_data[$week_key]['date_cmimitotal'] += $row['cmimi'];
            $property_data[$week_key][$date_key]['date_pronatotal']++;
            $property_data[$week_key][$date_key]['date_cmimitotal'] += $row['cmimi'];
            $property_data['date_totali']['pronatotal']++;
            $property_data['date_totali']['cmimitotal'] += $row['cmimi'];
        }
    }

    /******************* Seksioni 3
     ** Trupi i tabeles qe do te shfaqet ne front-end
     */

    $nr = 1;
    foreach ($property_data as $key => $row) {
        if ($key == "date_totali") {
            continue;
        }
        $id = $limit_start + $nr;
        $nr++;
        $delete_button = 'disabled';
        $date_range = date("Y-m-d", strtotime($key)) . "  =>  " . date("Y-m-d", strtotime($key . " +1 week"));
        $day_string = "";
        foreach ($row as $day => $dayrow) {
            if ($day == 'date_pronatotal' || $day == 'date_cmimitotal') {
                continue;
            }
            $day_string .= "<tr><td>" . $day . "</td> <td> " . $dayrow['date_pronatotal'] . "</td><td> " . $dayrow['date_cmimitotal'] . "</td></tr>";
        }
        $table_data[] = array(
            "data_blerjes" => "<center><span id='id_$key'>Week: " . $date_range . "</span></center>",
            "numri" => "<center><span id='id_$key'>" . $row['date_pronatotal'] . "</span></center>",
            "cmimi" => "<center><span id='id_$key'>" . $row['date_cmimitotal'] . "</span></center>",
            "days" => $day_string
        );
    }
    ## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $table_data
    );
    echo json_encode($response);
}
