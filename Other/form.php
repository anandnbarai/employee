<?php
include 'include/connect.php';

if (isset($_REQUEST['edit'])) {
    $id = $_REQUEST['edit'];
    $sql = $emp->mf_query("select * from emp where id = '$id'");
    $row = $emp->mf_fetch_array($sql);
    extract($row);
}

if (isset($_REQUEST["update"])) {
    $data = array();
    $data['name'] = $_REQUEST["name"];
    $data['email'] = $_REQUEST["email"];
    $data['phone'] = $_REQUEST["phone"];
    $data['country'] = $_REQUEST["country"];
    $data['state'] = $_REQUEST["state"];
    $data['city'] = $_REQUEST["city"];

    $emp->mf_dbupdate("emp", $data, " WHERE id = '$id'");

    if ($emp) {
        echo "<script>window.alert('Data Updated.')</script>";
        echo "<script>window.open('index.php','_self')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.js" charset="utf8" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="include/data.js"></script>
    <script src="include/validation.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid col-md-6">
        <h3 class="text-center mt-2">Update Employee Data</h3>
        <form action="" class="m-3" method="post">
            <div class="form-outline mb-3">
                <label class="form-label">Name :</label>
                <input type="text" name="name" id="name" placeholder="Enter Name" class="form-control" value="<?= $name; ?>">
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">Email :</label>
                <input type="text" name="email" id="email" placeholder="Enter Email" class="form-control" value="<?= $email ?>">
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">Phone :</label>
                <input type="text" name="phone" id="phone" placeholder="Enter Phone" class="form-control" value="<?= $phone ?>">
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">Country :</label>

                <select name="country" id="country" class="form-control" s>
                    <?php /* ?>
                       
                    <?php
                    $sql = $emp->mf_query("select id,name from countries");

                    while ($fetch = $emp->mf_fetch_array($sql)) {
                        $selected = ($fetch['id'] == $country) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $fetch['id']; ?>" <?= $selected ?>>
                            <?php echo $fetch['name']; ?>
                        </option>
                    <?php
                    }
                    ?>
                    <?php */ ?>
                    <?=
                    $fetch_c = $emp->mf_createcombo("select id,name from countries ORDER BY name ASC", "id", "name", $country, "Select Country");
                    ?>
                </select>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">State :</label>
                <select name="state" id="state" class="form-control">
                    <option value="">Select State</option>
                    <?php

                    $sql = $emp->mf_query("select * from states where country_id = '$country'");

                    while ($fetch = $emp->mf_fetch_array($sql)) {
                        $selected = ($fetch['id'] == $state) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $fetch['id']; ?>" <?= $selected ?>>
                            <?php echo $fetch['StateName']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">City :</label>
                <select name="city" id="city" class="form-control">
                    <option value="">Select City</option>
                    <?php

                    $sql = $emp->mf_query("select * from cities where state_id = '$state'");

                    while ($fetch = $emp->mf_fetch_array($sql)) {
                        $selected = ($fetch['id'] == $city) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $fetch['id']; ?>" <?= $selected ?>>
                            <?php echo $fetch['name']; ?>
                        </option>
                    <?php
                    }
                    ?>

                </select>
            </div>
            <div class="form-outline mb-3">
                <?php
                if (isset($_REQUEST['edit'])) {
                ?>
                    <input type="submit" name="update" class="btn btn-dark p-2" value="Update">
                <?php
                } else {
                ?>
                    <input type="submit" id="submit" name="add" class="btn btn-dark p-2">
                <?php
                }
                ?>
            </div>
        </form>
    </div>
</body>

</html>