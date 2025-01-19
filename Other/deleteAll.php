<?php
include('connect.php');

if (isset($_POST['ids'])) {
    $iIds = (array) $_REQUEST['ids'];

    $data = array();
    $data['eStatus'] = 'd';
    $emp->mf_dbupdate("emp", $data, " WHERE id IN(" . implode(',', $iIds) . ")");
    //echo $_SESSION['last_query'];

    echo json_encode(array("status" => 200, "msg" => "Data deleted"));
    exit;
}

// The implode() function takes an array as input 
//and returns a string with the elements of the array separated by the specified separator. In this case, the separator is a comma.
?>