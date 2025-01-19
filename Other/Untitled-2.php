<?php
if ($emp) {

        for ($i = 1; $i <= $_POST['iTotalMemer']; $i++) {

            // print_pre($_POST);
            // echo $_POST['iTotalMemer'];exit;
            
            $vMember = $_POST['vMember_' . $i];
            $iMemberId = $_POST['iMemberId_' . $i];

            if ($iMemberId) {

                $data = array();

                $data['vMemberName'] = $vMember;
                $data['iEmpId'] = $id;

                $emp->mf_dbupdate("emp_member", $data, "WHERE iEmpId = '$id'");
            }
        }

        echo "<script>window.alert('Data Updated')</script>";
        echo "<script>window.open('index.php','_self')</script>";
    } else {
        echo "<script>window.open('updateData.php','_self')</script>";
    }