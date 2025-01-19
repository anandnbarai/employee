<?php
class db_class
{

	function mf_query($query) // EXECUTE USER QUERY
	{
		$_SESSION['Last_query'] = $query;
		return mysqli_query($GLOBALS['myCon'], $query);
	}

	function add_security($val) // RETURN VALUE WITH SECURITY
	{
		return mysqli_real_escape_string($GLOBALS['myCon'], $val);
	}

	function mf_fetch_array($result) // RETURN SINGLE ROW IN ARRAY FORM
	{
		if ($result) {
			return mysqli_fetch_array($result, MYSQLI_ASSOC);
		} else {
			return array();
		}
	}

	function mf_getValue($table, $field, $where, $condition) // FUNCTION TO SELECT RECORD IN SPECIFIED TABLE
	{
		$qry = "SELECT $field from $table where $where='$condition' LIMIT 1";

		$result = $this->mf_query($qry);
		if ($this->mf_affected_rows() > 0) {
			$row = $this->mf_fetch_array($result);
			return stripslashes($row[$field]);
		} else {
			return "";
		}
	}

	function mf_affected_rows() // RETURN TOTAL AFFECTED ROW WHILE QUERY EXECUTED
	{
		return mysqli_affected_rows($GLOBALS['myCon']);
	}

	function mf_dbinsert($table, $data) // FUNCTION TO INSERT NEW RECORD IN SPECIFIED TABLE
	{
		$qry = "INSERT INTO " . $table . " set ";
		foreach ($data as $fld => $val) {
			$qry .= $fld . "='" . $this->add_security($val) . "',";
		}

		$qry = substr($qry, 0, -1);
		//echo $qry; exit();
		return $this->mf_query($qry);
	}

	function mf_dbupdate($table, $data, $whare) // FUNCTION TO UPDATE TABLE DATA
	{
		$qry = "UPDATE " . $table . " set ";
		foreach ($data as $fld => $val) {
			$qry .= $fld . "='" . $this->add_security($val) . "',";
		}

		$qry = substr($qry, 0, -1);
		$qry .= " " . $whare;
		// echo $qry;
		// exit();
		return $this->mf_query($qry);
	}
	function mf_dbdelete($table, $fld, $val) // FUNCTION TO DELETE TABLE ROW
	{
		$qry = "DELETE FROM " . $table . " where " . $fld . "='" . $val . "'";
		return $this->mf_query($qry);
	}

	function date2dispnew($date) // CONVERT DATE TO DISPLAY FORMAT (INDIAN FORMAT)
	{
		if ($date == "" || $date == "0000-00-00") {
			return "";
		} else {
			return date("d/m/Y", strtotime($date));
		}
	}

	function mf_createcombo($query, $opt_value, $disp_value, $selected = "", $firstval = "-Select-")
	{
		if ($firstval != "") {
			$cmbtext = "<option value=''>$firstval</option>";
		}
		$result = $this->mf_query($query);
		if ($this->mf_affected_rows() > 0) {
			while ($row = $this->mf_fetch_array($result)) {
				$sel = "";
				if (stripslashes($row[$opt_value]) == stripslashes($selected)) {
					$sel = "selected='selected'";
				}

				$cmbtext .= "<option value='" . $row[$opt_value] . "' $sel>" . stripslashes($row[$disp_value]) . "</option>";
			}
		}
		//echo $cmbtext;
		return $cmbtext;
	}

	function date2savenew($date) // CONVERT DATE TO STORE FORMAT (MYSQL FORMAT)
	{
		if ($date == "") {
			return "";
		} else {
			$dtArr = explode("/", $date);
			$newDt = $dtArr[2] . "-" . $dtArr[1] . "-" . $dtArr[0];
			return $newDt;
		}
	}

	function curDate()
	{
		date_default_timezone_set('Asia/Kolkata');
		return date("Y-m-d");
	}

	public function mf_num_rows($query)
	{
		if ($query) {
			return mysqli_num_rows($query);
		} else {
			return 0;
		}
	}
	function creatDir($fixPath)
	{
		$year = date("y");
		$month = date("m");
		$directory = "$year/$month/";

		if (!is_dir($fixPath . $directory)) {
			mkdir($fixPath . $directory, 755, true);
		}

		return $directory;
	}

	function checkUploadFileExt($ext, $allowedExt)
	{
		$ret = true;
		if ($allowedExt != '') {
			$ret = false;
			$alext = explode('|', $allowedExt);
			foreach ($alext as $val) {
				if (strtolower($val) == strtolower($ext)) {
					$ret = true;
				}
			}
		}
		return $ret;
	}

	function uploadFile($tmpPath, $fileName, $fixPath)
	{
		$year = date("y");
		$month = date("m");
		$directory = "$year/$month/";
		$fullPath = $fixPath . $directory . $fileName;
		if (!is_dir($fixPath . $directory)) {
			mkdir($fixPath . $directory, 0777, true);
		}

		if ($tmpPath != '') {
			if (copy($tmpPath, $fullPath)) {
				return $directory . $fileName;
			}
		}
	}

	function uploadBase64File($image, $fileName, $fixPath)
	{
		$year = date("y");
		$month = date("m");
		$directory = "$year/$month/";
		$fullPath = $fixPath . $directory . $fileName;

		if (!is_dir($fixPath . $directory)) {
			mkdir($fixPath . $directory, 0777, true);
		}
		if (file_put_contents($fullPath, $image)) {
			return $directory . $fileName;
		}
	}
	function delUploadFile($table, $field, $where, $condition, $path = "")
	{
		$qry = "SELECT $field from $table where $where='$condition'";
		$result = $this->mf_query($qry);
		if ($this->mf_affected_rows() > 0) {
			$row = $this->mf_fetch_array($result);
			// echo $row['file'];
			$vid = stripslashes($row[$field]);
			if (is_file($path . $vid)) {
				unlink($path . $vid);
			}
		}
	}

	function mf_dbinsert_id(){
		return mysqli_insert_id($GLOBALS['myCon']);
	}
}

function print_pre($arr)
{
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}
