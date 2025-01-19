<?php

include 'connect.php';

if (isset($_POST['empId'])) {

    $empId = $_POST['empId'];
    // echo $empId;

    $sql = $emp->mf_query("SELECT iMemberId, vMemberName FROM emp_member WHERE iEmpId = '$empId' ORDER BY iMemberId");

    $empQuery = $emp->mf_query("SELECT name FROM emp where id = '$empId'");
    $fetchName = $emp->mf_fetch_array($empQuery);

    $empName = ucfirst($fetchName['name']);
    // echo $empName;exit;

    if ($emp->mf_num_rows($sql) > 0) {

        $memberNames = array();

        while ($row = $emp->mf_fetch_array($sql)) {
            $memberNames[] = $row["vMemberName"];
        }


        $table = '<label class="form-label"><b>Family Details of Employee :&nbsp;</b> </label>' . $empName . ' 
                <table cellpadding="5" class="table">
                        <thead>
                            <tr class="text-center">
                                <th>Sr No</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>';
                        
        $i = 1;
        foreach ($memberNames as $memberName) {

            $table .= '<tr>
                           <td style="text-align: center;">' . $i . '</td>
                           <td style="text-align: center;">' . $memberName . '</td>
                      </tr>';
            $i++;
        }
        $table .= '</tbody></table>';

        echo $table;
    } else {
        echo "No Family Members found for <b>$empName</b>";
    }
}
