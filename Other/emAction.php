<?php
include('connect.php');

$action = $_REQUEST['action'];
if ($action == 'delete_multiple_emp') {
    $iIds = (array) $_REQUEST['ids'];

    $aUpd = array();
    $aUpd['eStatus'] = 'd';
    $mfp->mf_dbupdate("data", $aUpd, " WHERE id IN(" . implode(',', $iIds) . ")");
    //echo $_SESSION['last_query'];

    echo json_encode(array("status" => 200, "msg" => "Record has been deleted successfully."));
    exit;
} else if ($action == 'FetchFamliy') {
    //    echo "hiii";
    if (isset($_POST['famid'])) {
        $idfam = $_POST['famid'];

        $sql = $mfp->mf_query("SELECT iEmpid,vName FROM family WHERE iEmpid='$idfam'");


        if ($mfp->mf_num_rows($sql) > 0) {

            $memberNames = array();

            while ($row = $mfp->mf_fetch_array($sql)) {

                $memberNames[] = $row["vName"];
            }
?>
            <table id="fam" class="table" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Member Name</th>
                </tr>
            </thead>     
            <tbody>
            <?php
            $i = 1;
            foreach ($memberNames as $memberName) {

                echo '<tr>
                <td>' . $i . '</td>
                <td>' . $memberName . '</td>
              </tr>';
              $i++;
            }
            ?>
            </tbody>
        </table>
            <?php
        } else {
            echo "No members found for $idfam";
        }
    }

} else if ($action == 'Fam') {
    if (isset($_POST['famid'])) {
        $idfam = $_POST['famid'];

        $sql = $mfp->mf_query("SELECT iEmpid,vName FROM family WHERE iEmpid='$idfam'");


        if ($mfp->mf_num_rows($sql) > 0) {

            $memberNames = array();

            while ($row = $mfp->mf_fetch_array($sql)) {

                // $memberNames[] = $row["iEmpid"];
                $memberNames[] = $row["vName"];
            }
        }
    }
    
echo json_decode($json_data);
echo json_encode(array("fData"=>$memberNames));
} else if ($action == 'insert_emp') {
    extract($_POST);
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $tmp_name = $_FILES['file']['tmp_name'];


    if ($file_size > 1000000) {
        echo "Sorry, your file is too large.";
    } else {

        $img_ex = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = random_int(100000, 999999) . "." . $img_ex;
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png", "pdf");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $dbPath = $mfp->uploadFile($tmp_name, $file_name, 'uploads/');
            $aIns = array();
            $aIns['fname'] = $fname;
            $aIns['lname'] = $lname;
            $aIns['dDate'] = $date;
            $aIns['country'] = $country;
            $aIns['state'] = $state;
            $aIns['city'] = $city;
            $aIns['vsal'] = $sal;
            $aIns['email'] = $email;
            $aIns['gender'] = $gender;
            $aIns['file'] = $dbPath;

            $sql = $mfp->mf_query("SELECT id FROM data");
            $row = $mfp->mf_fetch_array($sql);

            $mfp->mf_dbinsert('data', $aIns);

            if ($mfp) {

                $total = $_POST['iTotalMemer'];
                $id = $mfp->mf_dbinsert_id();

                for ($i = 1; $i <= $total; $i++) {

                    //last inserted id of above memebers added
                    $vMember = $_POST['vMember_' . $i];
                    $aIns = array();
                    $aIns['iEmpid'] = $id;
                    $aIns['vName'] = $vMember;

                    // $sql = $mfp->mf_query("SELECT * FROM family");
                    // $row = $mfp->mf_fetch_array($sql);
                    if($vMember > 0){
                        // update goes here...
                    }else{
                        $details = $mfp->mf_dbinsert('family', $aIns);
                    }
                    
                    /* if ($details) {
                        echo "<script>window.open('display.php','_self')</script>";
                    } */

                }
            }


        } else {
            echo "You can't upload files of this type";
        }
    }
}

?>