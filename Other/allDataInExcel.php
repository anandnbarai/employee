<?php

include 'connect.php';

require_once 'PHPExcel/Classes/PHPExcel.php';
// require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

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
