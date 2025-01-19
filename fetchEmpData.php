<?php

include 'connect.php';

$requestData = $_REQUEST;

//print_pre($requestData);

// Start Filter
$iCountryId = intval($requestData['columns'][0]['search']['value']);

$iStateId = intval($requestData['columns'][1]['search']['value']);

$iCityId = intval($requestData['columns'][2]['search']['value']);

$vWhere = "";

if ($iCountryId > 0) {
	$vWhere = " AND e.country = '" . $iCountryId . "'";
}

if ($iStateId > 0) {
	$vWhere = "AND e.state = '" . $iStateId . "'";
}

if ($iCityId > 0) {
	$vWhere = "AND e.state = '" . $iCityId . "'";
}
// End Filter

//for filter
$columns = array(
	0 => 'id',
	1 => 'name',
	2 => 'email',
	3 => 'phone',
	4 => 'gender',
	5 => 'countryName',
	6 => 'stateName',
	7 => 'cityName',
	8 => 'image',
	9 => 'eStatus'
);

//to display all records from database
$SELECTFIELDS = "
	SELECT
		e.id,
		e.name,
		e.email,
		e.phone,
		e.gender,
		c.name as countryName,
		s.StateName as stateName,
		ci.name as cityName,
		e.image
";

//to count total entries from database
$SINGLEFIELD = "SELECT e.id";

//join table to display data from other tables(country,state,city) in datatable
$sql = " 
	FROM
		emp as e
		LEFT JOIN countries as c ON c.id = e.country
		LEFT JOIN states as s ON s.id = e.state
		LEFT JOIN cities as ci ON ci.id = e.city
	WHERE 
		e.eStatus = 'y' " . $vWhere . "
";

//for search bar, search anything from any column in datatable
if (!empty($requestData['search']['value'])) {
	$strString = $requestData['search']['value'];

	$sql .= " AND (
		e.name LIKE '" . $strString . "%' OR
		e.email LIKE '" . $strString . "%' OR
		e.phone LIKE '" . $strString . "%' OR
		e.gender LIKE '" . $strString . "%' OR
		c.name LIKE '" . $strString . "%' OR
		s.StateName LIKE '" . $strString . "%' OR
		ci.name LIKE '" . $strString . "%'
	)";
}

$query = $emp->mf_query($SINGLEFIELD . $sql);

$totalFiltered = $emp->mf_num_rows($query);

//Below code is use to show limited entries in one page
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";

$query = $emp->mf_query($SELECTFIELDS . $sql);

$data = array();

$i = 1;

while ($row = $emp->mf_fetch_array($query)) {
	$id = $row['id'];

	$btnEdit = '<a class="text-dark bi bi-pencil-square update" style="font-size: 20px;" id=' . $id . ' href="empForm.php?edit=' . $id . '" role="button"></a>';
	$btnDelete = '<a class="text-danger bi bi-trash delete" style="font-size: 20px;" deleteid="' . $id . '" role="button"></a>';

	$img = 'No Image Uploaded.';

	if ($row['image']  != '') {
		$img = '<img src="uploads/' . $row['image'] . '" height="100">';
	}

	$famMember = '<a class="text-dark bi bi-eye-fill memberInfo" style="font-size: 25px;" data-id ="' . $id . '"  data-bs-toggle="modal" data-bs-target="#viewMem"></a>';

	$checkBox = '<input type="checkbox" name="delete[]" value="' . $id . '" class="check_box">';

	$nestedData = array();

	// $nestedData[] = $i;

	//$nestedData['key'] = value
	$nestedData['checkbox'] = $checkBox;
	// $nestedData['id'] = $i;
	$nestedData['id'] = $row["id"];
	$nestedData['name'] = ucfirst($row["name"]);
	$nestedData['email'] = $row["email"];
	$nestedData['phone'] = $row["phone"];
	$nestedData['gender'] = ucfirst($row["gender"]);
	$nestedData['country'] = $row["countryName"];
	$nestedData['state'] = $row["stateName"];
	$nestedData['city'] = $row["cityName"];
	$nestedData['image'] = $img;
	$nestedData['family'] = $famMember;
	$nestedData['action'] = $btnEdit . '&nbsp;&nbsp;' . $btnDelete;

	$data[] = $nestedData;
	// $i++;
}

$json_data = array(
	"draw"            => intval($requestData['draw']),

	//to display total entries in footer part of datatable, The total number of records.
	"recordsTotal"    => intval($totalFiltered),

	//to display filter entries(10 entries from 60 entries) in footer part of datatable, The number of records after filtering.
	"recordsFiltered" => intval($totalFiltered),

	"data"            => $data
);

echo json_encode($json_data);

exit;
