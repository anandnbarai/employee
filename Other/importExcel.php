<?php

require_once 'connect.php';
require_once 'PHPExcel/Classes/PHPExcel.php';
require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

if (isset($_POST['upload_excel'])) {

    // print_pre($_FILES); 
    // exit;

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
