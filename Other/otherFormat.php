<?php

include 'connect.php';

require_once __DIR__ . '/vendor/autoload.php';

require_once 'PHPExcel/Classes/PHPExcel.php';
require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

$mpdf = new \Mpdf\Mpdf();

//? export all data to PDF using mpdf
if (isset($_POST['exportPDF'])) {

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
}

//? exprot all data in excel using PHPExcel
if (isset($_POST['exportExcel'])) {

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

        /* $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['name']);

    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['email']);

    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['phone']); */

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

    /* $filename = "EmployeeData." . date('d.m.Y') . ".xls";
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=\"$filename\""); */

    /* $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('data.xlsx'); */


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
}


//? import data from excel and store into database
if (isset($_POST['uploadExcel'])) {
    
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

                if ($data['name'] != '') {

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
}
