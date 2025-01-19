<?php
include 'connect.php';

$requestData= $_REQUEST;

$iCountryId = intval($requestData['columns'][0]['search']['value']);

$iStateId = intval($requestData['columns'][1]['search']['value']);



$vWhere = "";
if ($iCountryId > 0) {
	$vWhere = " AND d.country = '" . $iCountryId . "'";
}

if ($iStateId > 0) {
	$vWhere = "AND d.state = '" . $iStateId . "'";
}

// $iCountryId = intval($requestData['columns'][0]['search']['value']);

// $vWhere = "";
// if($iCountryId > 0){
// 	$vWhere = " AND d.country = '".$iCountryId."'";
// }
$columns = array(
	0 => 'id',
	1 => 'fname',
	2 => 'lname',
	3 => 'dDate',
	4 => 'countryName',
	5 => 'stateName',
	6 => 'cityName',
	7 => 'vsal',
	8 => 'email',
	9 => 'gender',
	10 => 'file',
	11 => 'eStatus'
	
);

$SELECTFIELDS = "
SELECT 
	d.id,
	d.fname,
	d.lname,
	d.dDate,
	c.name as countryName ,
	s.StateName as stateName,
	ci.name as cityName,
	d.vsal,
	d.email,
	d.gender,
	d.file
";

$SINGLEFIELD = "SELECT d.id";
$sql = "
FROM
	data as d
	LEFT JOIN countries as c ON  c.id = d.country 
	LEFT JOIN states as s ON  s.id = d.state
	LEFT JOIN cities as ci ON  ci.id = d.city
WHERE 
	d.eStatus = 'y' ".$vWhere."
";

if(!empty($requestData['search']['value'])){
	$strString = $requestData['search']['value'];
	$sql.=" AND (
		d.fname LIKE '".$strString."%' OR
		d.lname LIKE '".$strString."%' OR
		s.StateName LIKE '".$strString."%'
	)";
}

$query=$mfp->mf_query($SINGLEFIELD.$sql);
$totalFiltered = $mfp->mf_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."";	
$query=$mfp->mf_query($SELECTFIELDS.$sql);

$data = array();
$i=1;
while($row=$mfp->mf_fetch_array($query)){
	$id = $row['id'];
	//$btnEdit = '<a class="btn btn-primary" href="index.php?ed='.$id.'" role="button">EDIT</a>';
	//$btnDelete = '<a class="bi bi-trash3-fill" delete_mem" data-id="'.$id.'" role="button"></a>';
	$btnEdit =' <a class="fa-solid fa-pen-to-square"  href="index.php?ed='.$id.'" value=""></a>';
	$btnDelete =' <a class="bi bi-trash3-fill text-danger delete_mem" data-id="'.$id.'" "value=""></a>';
	$image = "no image uploaded";
	if($row['file'] != "")
	{
		$image= ' <img src="uploads/'.$row['file'].'" height="70">';
	}
	//$family = '<a class="fa-solid fa-eye" href="display.php"></a>';<i class="fa-regular fa-eye" style="color: #02060d;"></i>
	$fam='<button type="button" class="fa-regular fa-eye foram" data-famid="'.$id.'">
  </button>';
	$check= '<input type="checkbox" name="delete[]" value="'.$id.'" class="check_box">';
	$nestedData=array(); 
	$nestedData[] = $check;
	$nestedData[] = $i;	
	$nestedData[] = $row["fname"];	
	$nestedData[] = $row["lname"];
	$nestedData[] = $row["dDate"];
	$nestedData[] = $row["countryName"];
	$nestedData[] = $row["stateName"];
	$nestedData[] = $row["cityName"];	
	$nestedData[] = $row["vsal"];	
	$nestedData[] = $row["email"];	
	$nestedData[] = $row["gender"];	
	$nestedData[] = $image;	
	///$nestedData[] = $family;
	$nestedData[] = $fam;
	$nestedData[] = $btnEdit.''.$btnDelete;
	
	$data[] = $nestedData;
	$i++;
}

$json_data = array(
	"draw"            => intval($requestData['draw']),
	"recordsTotal"    => intval($totalFiltered),
	"recordsFiltered" => intval($totalFiltered),
	"data"            => $data
);

echo json_encode($json_data);

exit;
?>
