<?php


include 'connect.php';
$sql = array();


$sql = $emp->mf_query("SELECT email from emp");
$existingEmailAddresses = [];
while ($row = $emp->mf_fetch_array($sql)) {
    $existingEmailAddresses[] = $row['email'];
}
print_pre($existingEmailAddresses);
