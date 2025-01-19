<?php

include 'connect.php';

if (isset($_POST['empId'])) {

    $empId = $_POST['empId'];
    // echo $empId;

    $sql = $emp->mf_query("SELECT iMemberId, vMemberName FROM emp_member WHERE iEmpId = '$empId' ORDER BY iMemberId");

    if ($emp->mf_num_rows($sql) > 0) {

        $memberNames = array();

        while ($row = $emp->mf_fetch_array($sql)) {
            $memberNames[] = $row["vMemberName"];
        }
    }
    echo json_encode(array("status"=>200,"aData"=>$memberNames));
}
