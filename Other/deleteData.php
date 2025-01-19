<?php

include 'connect.php';

if (isset($_REQUEST['delete'])) {

    $id = $_REQUEST['delete'];
    
    $data = array();
    $data['eStatus'] = 'd';
    $emp->mf_dbupdate("emp", $data, " WHERE id = '$id'");

    echo json_encode(array("status" => 200, "msg" => "Data deleted"));
}
