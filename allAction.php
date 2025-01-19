<?php

include 'connect.php';

require_once __DIR__ . '/vendor/autoload.php';

require_once 'PHPExcel/Classes/PHPExcel.php';
require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

$mpdf = new \Mpdf\Mpdf();

$action = $_REQUEST['action'];

//different action, different code execution
if ($action == 'fetchState') {

    //! fetch state 
    $id = $_POST['c_id'];

    $sql = $emp->mf_query("SELECT * FROM states WHERE country_id = '$id'");

    while ($state = $emp->mf_fetch_array($sql)) {
?>
        <option value="<?php echo $state['id']; ?>">
            <?php echo $state['StateName']; ?>
        </option>
    <?php
    }
    exit;
} elseif ($action == 'fetchCity') {

    //! fetch city
    $id = $_POST['s_id'];

    $sql = $emp->mf_query("SELECT * FROM cities WHERE state_id = '$id'");

    while ($city = $emp->mf_fetch_array($sql)) {
    ?>
        <option value="<?php echo $city['id']; ?>">
            <?php echo $city['name']; ?>
        </option>
<?php
    }
    exit;
} elseif ($action == 'deleteData') {

    //! delete data when click on delete button
    $id = $_REQUEST['delete'];

    $data = array();
    $data['eStatus'] = 'd';
    $emp->mf_dbupdate("emp", $data, " WHERE id = '$id'");

    echo json_encode(array("status" => 200, "msg" => "Data deleted"));
    exit;
} elseif ($action == 'childRow') {

    //! fetch family data to display in child row
    $empId = $_POST['empId'];
    // echo $empId;

    $sql = $emp->mf_query("SELECT iMemberId, vMemberName FROM emp_member WHERE iEmpId = '$empId' ORDER BY iMemberId");

    if ($emp->mf_num_rows($sql) > 0) {

        $memberNames = array();

        while ($row = $emp->mf_fetch_array($sql)) {
            $memberNames[] = $row["vMemberName"];
        }
    }
    echo json_encode(array("status" => 200, "aData" => $memberNames));
    exit;
} elseif ($action == 'fetchFamily') {

    //! fetch family data for modal
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
    exit;
} elseif ($action == 'deleteUsingCheckbox') {

    //! delete all data using checkbox
    $iIds = (array) $_REQUEST['ids'];

    $data = array();
    $data['eStatus'] = 'd';
    $emp->mf_dbupdate("emp", $data, " WHERE id IN(" . implode(',', $iIds) . ")");
    //echo $_SESSION['last_query'];

    echo json_encode(array("status" => 200, "msg" => "Data deleted"));
    exit;
} elseif ($action == 'deleteFamily') {

    //! delete family member in update page
    $db_id = $_REQUEST['db_id'];

    $sql = $emp->mf_dbdelete('emp_member', 'iMemberId', $db_id);

    echo json_encode(array("status" => 200, "msg" => "Family Member deleted"));
    exit;
} elseif ($action == 'nameValid') {

    //! username validation
    $name = $_POST['name'];
    $empId = $_POST['empId'];

    $response = array();

    if ($empId > 0) {

        $sql = $emp->mf_query("SELECT id FROM emp WHERE name = '" . $name . "' AND eStatus = 'y' AND id != '" . $empId . "'");
        $row = $emp->mf_fetch_array($sql);

        // $last = $_SESSION['Last_query'];
        // echo $last;
        if ($row > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Username already exists';
        } else {
            $response['status'] = 'success';
            $response['message'] = 'Username available';
        }
    } else {

        $sql = $emp->mf_query("SELECT id FROM emp WHERE name = '" . $name . "' AND eStatus = 'y'");
        // $last = $_SESSION['Last_query'];
        // echo $last;

        $row = $emp->mf_fetch_array($sql);
        if ($row > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Username already exists';
        } else {
            $response['status'] = 'success';
            $response['message'] = 'Username available';
        }
    }
    // Encode the response array to JSON
    $jsonResponse = json_encode($response);

    // Output the JSON response
    echo $jsonResponse;
    exit;
} elseif ($action == 'emailValid') {

    //! email validation
    $email = $_POST['email'];
    $empId = $_POST['empId'];

    $response = array();

    if ($empId > 0) {

        $sql = $emp->mf_query("SELECT id FROM emp WHERE email = '" . $email . "' AND eStatus = 'y' AND id != '" . $empId . "'");
        $row = $emp->mf_fetch_array($sql);

        // $last = $_SESSION['Last_query'];
        // echo $last;

        if ($row > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Email already exists';
        } else {
            $response['status'] = 'success';
            $response['message'] = 'Email available';
        }
    } else {
        $sql = $emp->mf_query("SELECT id FROM emp WHERE email = '" . $email . "' AND eStatus = 'y'");
        // $last = $_SESSION['Last_query'];
        // echo $last;

        $row = $emp->mf_fetch_array($sql);
        if ($row > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Email already exists';
        } else {
            $response['status'] = 'success';
            $response['message'] = 'Email available';
        }
    }
    // Encode the response array to JSON
    $jsonResponse = json_encode($response);

    // Output the JSON response
    echo $jsonResponse;
    exit;
} elseif ($action == 'phoneValid') {

    //! phone validation
    $phone = $_POST['phone'];
    $empId = $_POST['empId'];

    $response = array();

    if ($empId > 0) {

        $sql = $emp->mf_query("SELECT id FROM emp WHERE phone = '" . $phone . "' AND eStatus = 'y' AND id != '" . $empId . "'");
        $row = $emp->mf_fetch_array($sql);

        // $last = $_SESSION['Last_query'];
        // echo $last;

        if ($row > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Phone number already exists';
        } else {
            $response['status'] = 'success';
            $response['message'] = 'Phone number available';
        }
    } else {
        $sql = $emp->mf_query("SELECT id FROM emp WHERE phone = '" . $phone . "' AND eStatus = 'y'");

        // $last = $_SESSION['Last_query'];
        // echo $last;

        $row = $emp->mf_fetch_array($sql);
        if ($row > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Phone number already exists';
        } else {
            $response['status'] = 'success';
            $response['message'] = 'Phone number available';
        }
    }
    // Encode the response array to JSON
    $jsonResponse = json_encode($response);

    // Output the JSON response
    echo $jsonResponse;
    exit;
} elseif ($action == 'imageValid') {
    $response = array();
    // Get the image extension
    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    // Check if the extension is valid
    $validExtensions = array('jpg', 'jpeg', 'png');

    if (!in_array($extension, $validExtensions)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid image extension.';
        // print_pre($response);
    } else {
        $response['status'] = 'success';
        $response['message'] = 'Valid image extension';
        // print_pre($response);exit;
    }
    // Encode the response array to JSON
    $jsonResponse = json_encode($response);

    // Output the JSON response
    echo $jsonResponse;
    exit;
} elseif ($action == 'insertEmp') {

    //! insert employee data
    extract($_POST);

    $image = $data['image'] = $_FILES['image']['name'];
    $temp_img = $_FILES['image']['tmp_name'];

    $img_ex = pathinfo($image, PATHINFO_EXTENSION);
    $image = random_int(10, 999999) . "." . $img_ex;
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
        }
    }
    echo json_encode(array("status" => 200, "msg" => "Employee data inserted."));
    exit;
} elseif ($action == 'updateEmp') {

    //! update employee data
    extract($_REQUEST);
    // print_pre($_REQUEST);exit;
    $data = array();
    $tmp_name = $_FILES['image']['tmp_name'];

    if ($tmp_name != '') {

        $path = "uploads/";
        $image = $_FILES['image']['name'];
        $path = $path . $image;
        // $target_file = $path . basename($image);

        //pathinfo($image, PATHINFO_EXTENSION) : output of this code is to get image extension
        //output of below $image is random number and image extension ex. 23435.jpg

        $image = random_int(10, 999999) . "." . pathinfo($image, PATHINFO_EXTENSION);

        $sql = $emp->mf_query("SELECT image FROM emp WHERE id='$id'");

        if ($row = $emp->mf_fetch_array($sql)) {

            // Get the path of the employee's current image from the database.
            $delete = $row['image'];

            // Delete the old image.
            unlink($path . $delete);
        }

        //function uploadFile($tmpPath, $fileName, $fixPath)
        $uploadImage = $emp->uploadFile($tmp_name, $image, 'uploads/');
        $data['image'] = $uploadImage;
    }

    $data['name'] = $name;
    $data['email'] = $email;
    $data['phone'] = $phone;
    $data['gender'] = $gender;
    $data['country'] = $country;
    $data['state'] = $state;
    $data['city'] = $city;
    // print_pre($data);exit;

    $emp->mf_dbupdate("emp", $data, " WHERE id = '$id'");

    if ($emp->mf_dbupdate("emp", $data, " WHERE id = '$id'")) {
        for ($i = 1; $i <= $_POST['iTotalMemer']; $i++) {

            $iMemberId = intval($_POST['iMemberId_' . $i]);

            if ($iMemberId > 0) {

                $data = array();
                $data['vMemberName'] = $_POST['vMember_' . $i];
                $iMemberId = intval($_POST['iMemberId_' . $i]);
                // echo $iMemberId;exit;
                // print_pre($data);exit;
                $emp->mf_dbupdate("emp_member", $data, " WHERE iMemberId = '$iMemberId'");
            } else {

                $data = array();
                $data['vMemberName'] = $_POST['vMember_' . $i];
                $data['iEmpId'] = $id;
                $iMemberId = intval($_POST['iMemberId_' . $i]);

                $emp->mf_dbinsert("emp_member", $data);
            }
        }
    }
    echo json_encode(array("status" => 200, "msg" => "Employee data updated."));

    exit;
} elseif (isset($_POST['exportPDF'])) {

    //! export all data to PDF using mpdf
    $sql = $emp->mf_query("SELECT id,name,email,phone,gender,image FROM emp WHERE eStatus = 'y'");

    if ($num_rows = $emp->mf_num_rows($sql)) {

        $html = '<table style="text-align:center" border="1" width="100%" cellpading="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>';

        while ($row = $emp->mf_fetch_array($sql)) {

            extract($row);

            $html .= '<tr>
                    <td>' . $id . '</td>
                    <td>' . $name . '</td>
                    <td>' . $email . '</td>
                    <td>' . $phone . '</td>
                    <td>' . $gender . '</td>
                    <td><img src="uploads/' . $image . '" height="50"></td>
                </tr>';
        }

        $html .= '</tbody></table>';
    }

    $mpdf->WriteHTML($html);

    // $file = "Employee Data.pdf";
    $filename = "EmployeeData." . date('d.m.Y') . ".pdf";

    //to direct donwload D is used instead of I
    $mpdf->Output($filename, 'I');
    exit;
} elseif (isset($_POST['exportExcel'])) {

    //! exprot all data in excel using PHPExcel
    // Instantiate a new PHPExcel object
    $objPHPExcel = new PHPExcel();

    //used to create a new worksheet
    $objWorkSheet = $objPHPExcel->createSheet(0);

    // Excelsheet Name
    $objWorkSheet->setTitle("Employee Data");

    //array for header
    $styleHeaderArray = array(
        'font'  => array(
            'bold'  => true,
            'color' => array('rgb' => 'ffffff'),
            'size'  => 12,
            'name'  => 'Verdana',
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '2d4462')
        )
    );

    //array for header
    $headerArray = array(
        'A' => 'id',
        'B' => 'Name',
        'C' => 'Email',
        'D' => 'Phone',
        'E' => 'gender',
        'F' => 'Country',
        'G' => 'State',
        'H' => 'City'
    );


    foreach ($headerArray as $hkey => $resHead) {

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$hkey" . "1", $resHead);
        $objPHPExcel->getActiveSheet()->getStyle("$hkey" . "1")->applyFromArray($styleHeaderArray);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension("$hkey")->setWidth(25);
    }

    // Initialise the Excel row number, 1st column is header
    $rowCount = 2;

    $sql = $emp->mf_query("SELECT 
        e.id
		,e.name
		,e.email
		,e.phone
        ,e.gender
		,c.name as countryName
		,s.StateName as stateName
		,ci.name as cityName
        FROM 
        emp as e
		LEFT JOIN countries as c ON c.id = e.country
		LEFT JOIN states as s ON s.id = e.state
		LEFT JOIN cities as ci ON ci.id = e.city 
        WHERE e.eStatus = 'y'
        ");


    while ($row = $emp->mf_fetch_array($sql)) {

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$rowCount", $row['id']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$rowCount", $row['name']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$rowCount", $row['email']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$rowCount", $row['phone']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$rowCount", $row['gender']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$rowCount", $row['countryName']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G$rowCount", $row['stateName']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H$rowCount", $row['cityName']);


        // Increment the Excel row counter
        $rowCount++;
    }

    $filename = "EmployeeData." . date('d.m.Y') . ".xls";

    $objPHPExcel->setActiveSheetIndex(0);

    // output buffering
    ob_start();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    ob_end_clean();

    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    header('Content-Disposition: attachment; filename=' . $filename);

    //Below code tells the browser to cache the file for a maximum of 1 second, default time is second
    header('Cache-Control: max-age=1');

    header('Cache-Control: cache, must-revalidate');

    //The Pragma: public header tells the browser that the file can be cached by the browser.
    header('Pragma: public');

    $objWriter->save('php://output');
    exit;
} elseif (isset($_POST['uploadExcel'])) {

    //! import data from excel and store into database

    $sql = $emp->mf_query("SELECT email from emp");

    $existingEmail = [];

    while ($row = $emp->mf_fetch_array($sql)) {
        $existingEmail[] = $row['email'];
    }

    $file = $_FILES['excel']['tmp_name'];

    $ext = pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION);

    if ($ext == 'xls' or $ext == 'xlsx') {

        $obj = PHPExcel_IOFactory::load($file);

        foreach ($obj->getWorksheetIterator() as $sheet) {

            //last row, will use in for loop to export all data
            $getHighestRow = $sheet->getHighestRow();

            for ($i = 0; $i <= $getHighestRow; $i++) {

                $data = array();
                $data['name'] = $sheet->getCellByColumnAndRow(1, $i)->getValue();
                $data['email'] = $sheet->getCellByColumnAndRow(2, $i)->getValue();
                $data['phone'] = $sheet->getCellByColumnAndRow(3, $i)->getValue();
                $data['gender'] = $sheet->getCellByColumnAndRow(4, $i)->getValue();

                $data['country'] = $emp->mf_getValue('countries', 'id', 'name', $sheet->getCellByColumnAndRow(5, $i)->getValue());

                $data['state'] = $emp->mf_getValue('states', 'id', 'StateName', $sheet->getCellByColumnAndRow(6, $i)->getValue());

                $data['city'] = $emp->mf_getValue('cities', 'id', 'name', $sheet->getCellByColumnAndRow(7, $i)->getValue());


                if (!in_array($data['email'], $existingEmail)) {
                    
                    $emp->mf_dbinsert('emp', $data);
                    // echo $_SESSION['Last_query'];
                    // exit;
                    echo "<script>window.alert('Data Exported')</script>";
                    echo "<script>window.open('index.php','_self')</script>";
                }
            }
        }
    } else {
        echo "Invalid file format";
    }
    exit;
}

?>