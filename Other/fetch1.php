<?php

include 'connect.php';

/* if (!empty($_POST['s_id'])) {
    $id = $_POST['s_id'];

    $sql = $mfp->mf_query("select * from cities where state_id = '$id'");

    while ($city = $mfp->mf_fetch_array($sql)) {
?>
        <option value="<?php echo $city['id']; ?>"><?php echo $city['name']; ?></option>
<?php
    }
} */


if (!empty($_POST['s_id'])) {
    $id = $_POST['s_id'];

    $sql = $mfp->mf_query("select * from cities where state_id = '$id'");

    $aReturnArr = array(); $html = '';
    while ($city = $mfp->mf_fetch_array($sql)) {
        $aReturnArr[] = $city['name'];
        $html .= '<option value="'.$city['id'].'">'.$city['name'].'</option>';
    }
}
echo json_encode(array("aData"=>$aReturnArr,'html'=>$html));