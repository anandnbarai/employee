<?php

include 'connect.php';

if (!empty($_POST['s_id'])) {
    $id = $_POST['s_id'];

    $sql = $emp->mf_query("SELECT * FROM cities WHERE state_id = '$id'");

    while ($city = $emp->mf_fetch_array($sql)) {
?>
        <option value="<?php echo $city['id']; ?>">
            <?php echo $city['name']; ?>
        </option>
<?php
    }
}
