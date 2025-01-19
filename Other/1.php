<?php

include 'connect.php';


$sql = $emp->mf_query("SELECT iMemberId,vMemberName FROM emp_member where iEmpId = 124");

$row = $emp->mf_fetch_array($sql);

print_pre($row);
?>