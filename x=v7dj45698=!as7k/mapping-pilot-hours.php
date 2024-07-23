<?php
require_once "../config.php";
/*
$sql        = "SELECT afml_page_no, count(afml_page_no) as total FROM tbl_afml_pilot_hours WHERE user_id = 0 GROUP BY afml_page_no";
$h          = mysqli_query($conn, $sql);
while($row  = mysqli_fetch_assoc($h)) {

    $sql_afml = "SELECT afml_captain_user_id, afml_copilot_user_id FROM tbl_afml WHERE afml_page_no = '".$row['afml_page_no']."'";
    $h_afml   = mysqli_query($conn, $sql_afml);
    $row_afml = mysqli_fetch_assoc($h_afml);
    $limit    = $row['total']/2;

    $sql2 = "UPDATE tbl_afml_pilot_hours SET user_id = '".$row_afml['afml_copilot_user_id']."',crew_status = 1 WHERE afml_page_no = '".$row['afml_page_no']."'";
    echo $sql2.'<br/>';
    mysqli_query($conn, $sql2);
    //echo $row['afml_page_no'].' - '.$row_afml['afml_captain_user_id'].' - '.$row_afml['afml_copilot_user_id'].'<br/>'; 
    //update `tbl_afml_pilot_hours` SET user_id = 0, crew_status = '' WHERE crew_status = 1  
}*/

/* coba diubah ke menit */
function hoursToMinutes($hours) 
{ 
    $minutes = 0; 
    if (strpos($hours, ':') !== false) 
    { 
        // Split hours and minutes. 
        list($hours, $minutes) = explode(':', $hours); 
    } 
    return $hours * 60 + $minutes; 
} 

// Transform minutes like "105" into hours like "1:45". 
function minutesToHours($minutes) 
{ 
    $hours = (int)($minutes / 60); 
    $minutes -= $hours * 60; 
    return sprintf("%02d:%02.0f", $hours, $minutes); 
}

$sql    = "SELECT afml_page_no,b.aircraft_reg_code,SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs, a.afml_route_from, a.afml_route_to, date_format(a.afml_date, '%d/%m/%Y') as afml_date, full_name FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) INNER JOIN tbl_user c ON b.afml_captain_user_id = c.user_id WHERE (b.afml_captain_user_id = '65') AND (date(a.afml_date) BETWEEN '2020-01-01' AND '2020-01-31') AND a.client_id = '1' GROUP BY afml_detail_id";
echo $sql;
$h      = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($h)) {
    echo $row['afml_page_no'].' - '.$row['afml_date'].' - '.$row['full_name'].' - '.minutesToHours($row['total_flt_hrs']).'<br/>';
}
    $sql2 = "SELECT afml_page_no,b.aircraft_reg_code,SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs, a.afml_route_from, a.afml_route_to, date_format(a.afml_date, '%d/%m/%Y') as afml_date, full_name FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) INNER JOIN tbl_user c ON b.afml_copilot_user_id = c.user_id WHERE (b.afml_copilot_user_id = '65') AND (date(a.afml_date) BETWEEN '2020-01-01' AND '2020-01-31') AND a.client_id = '1' GROUP BY afml_detail_id";
    $h2      = mysqli_query($conn, $sql2);
    while($row2 = mysqli_fetch_assoc($h2)) {
    echo 'xx '.$row2['afml_page_no'].' - '.$row2['afml_date'].' - '.$row2['full_name'].' - '.minutesToHours($row2['total_flt_hrs']).'<br/>';
    }
