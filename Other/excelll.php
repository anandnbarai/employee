<?php

include 'connect.php';

$filename = "EmployeeData." . date('d.m.Y') . ".xls";

$fields = array('id','name','email','phone');

$htmlData = implode("\t", array_values($fields)) . "\n";


$sql = $emp->mf_query("SELECT id,name,email,phone FROM emp");

if ($num_rows = $emp->mf_num_rows($sql) > 0) {

    while ($row = $emp->mf_fetch_array($sql)) {

        $data = array($row['id'], $row['name'], $row['email'], $row['phone']);

        $htmlData .= implode("\t", array_values($data)) . "\n";
    }
} else {
    $htmlData .= 'No records found...' . "\n";
}

$filename = "EmployeeData." . date('d.m.Y') . ".xls";

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=\"$filename\"");

echo $htmlData;

exit();
