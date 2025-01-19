 <?php
    if ($emp) {

        $iTotalMemer = $_POST['iTotalMemer'];
        // echo $iTotalMember;exit;

        if (is_array($_POST['vMember_' . $iTotalMemer])) {

            for ($i = 1; $i <= $iTotalMemer; $i++) {

                foreach ($_POST['vMember_' . $iTotalMemer] as $vMem) {

                    $data = array();

                    $iEmpId = $emp->mf_dbinsert_id();
                    $data['iEmpId'] = $iEmpId;
                    $data['vMemberName'] = $_REQUEST['vMember_' . $iTotalMemer];

                    $update = $emp->mf_dbupdate("emp_member", $data, "WHERE iEmpId = '$iEmpId'");

                    if ($update) {
                        echo "<script>window.alert('Data Updated')</script>";
                        // echo "<script>window.open('index.php','_self')</script>";
                    }
                }
                exit;
            }
        } else {
            $iTotalMemer = $_POST['iTotalMemer'];
            echo $iTotalMember;
            exit;
            for ($i = 1; $i <= $iTotalMemer; $i++) {

                $iEmpId = $emp->mf_dbinsert_id();

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
                    echo "<script>window.alert('Data Updated.')</script>";
                    echo "<script>window.open('index.php','_self')</script>";
                }
            }
            // $iEmpId = $emp->mf_dbinsert_id();

            // $vMember = $_POST['vMember_' . $i];

            // $data = array();
            // $data['iEmpId'] = $iEmpId;
            // $data['vMemberName'] = $vMember;

            // $fam = $emp->mf_dbinsert('emp_member', $data);

            // if ($fam) {
            //     echo "<script>window.alert('Data Updated')</script>";
            //     // echo "<script>window.open('index.php','_self')</script>";
            // }
        }
    } else {
        echo "<script>window.alert('Error')</script>";
        echo "<script>window.open('update.php','_self')</script>";
    }
