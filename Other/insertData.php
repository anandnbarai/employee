<?php

// database connection file
include 'connect.php';

// error_reporting(0);
date_default_timezone_set('Asia/Kolkata');

$action = $_REQUEST['action'];
//add data to database
if ($action == 'insert_emp') {

    extract($_POST);
  
    $image = $data['image'] = $_FILES['image']['name'];
    $temp_img = $_FILES['image']['tmp_name'];

    $sql = $emp->mf_query("SELECT id FROM emp WHERE email = '" . $email . "' AND eStatus = 'y'");

    $row = $emp->mf_fetch_array($sql);

    if (intval($row['id']) == 0) {

        $img_ex = pathinfo($image, PATHINFO_EXTENSION);
        $image = random_int(10, 999999) . "." . $img_ex;
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png", "pdf", "webp");

        if (in_array($img_ex_lc, $allowed_exs)) {

            $image = $emp->uploadFile($temp_img, $image, 'uploads/');

            $data = array();
            $data['name'] = $name;
            $data['email'] = $email;
            $data['phone'] = $phone;
            $data['gender'] = $gender;
            $data['country'] = $country;
            $data['state'] = $state;
            $data['city'] = $city;
            $data['image'] = $image;


            $emp->mf_dbinsert('emp', $data);

            if ($emp) {

                $iTotalMemer = $_POST['iTotalMemer'];
                $iEmpId = $emp->mf_dbinsert_id();

                for ($i = 1; $i <= $iTotalMemer; $i++) {

                    // $iEmpId = $row['id'];

                    // echo $iEmpId;
                    // exit;
                    $vMember = $_POST['vMember_' . $i];

                    // echo $vMember;
                    // exit;

                    $data = array();
                    $data['iEmpId'] = $iEmpId;
                    $data['vMemberName'] = $vMember;

                    $fam = $emp->mf_dbinsert('emp_member', $data);

                    if ($fam) {
                        // echo "<script>window.alert('Data Added')</script>";
                        echo "<script>window.open('index.php','_self')</script>";
                    }
                }
            }
        } else {
            echo "<script>window.alert('You can't upload this type of file')</script>";
            echo "<script>window.open('addData.php','_self')</script>";
        }
    } else {
        echo "<script>window.alert('Use different Email')</script>";
        echo "<script>window.open('addData.php','_self')</script>";
    }
}
