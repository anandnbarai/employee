<?php
include 'connect.php';


if (!empty($_POST["name"])) {

    $name = $_POST['name'];

    $sql = $emp->mf_query("SELECT id FROM emp WHERE name = '" . $name . "' AND eStatus = 'y'");
    $row = $emp->mf_fetch_array($sql);

    if ($row > 0) {
        echo "<span style='color:red'> Sorry Username already exists.</span>";
        echo "<script>$('#addEmp').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'> Username available.</span>";
        echo "<script>$('#addEmp').prop('disabled',false);</script>";
    }
    exit;
}


if (!empty($_POST["email"])) {

    $email = $_POST['email'];

    $sql = $emp->mf_query("SELECT id FROM emp WHERE email = '" . $email . "' AND eStatus = 'y'");
    $row = $emp->mf_fetch_array($sql);

    if ($row > 0) {
        echo "<span style='color:red'> Sorry Email already exists.</span>";
        echo "<script>$('#addEmp').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'> Email available.</span>";
        echo "<script>$('#addEmp').prop('disabled',false);</script>";
    }
    exit;
}


if (!empty($_POST["phone"])) {

    $phone = $_POST['phone'];

    $sql = $emp->mf_query("SELECT id FROM emp WHERE phone = '" . $phone . "' AND eStatus = 'y'");
    $row = $emp->mf_fetch_array($sql);

    if ($row > 0) {
        echo "<span style='color:red'>Sorry Phone number already exists.</span>";
        echo "<script>$('#addEmp').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'>Phone number available.</span>";
        echo "<script>$('#addEmp').prop('disabled',false);</script>";
    }
    exit;
}
