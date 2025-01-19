<?php

include 'connect.php';

if (isset($_REQUEST['edit'])) {
    $id = $_REQUEST['edit'];
    $sql = $emp->mf_query("SELECT * FROM emp WHERE id = '$id'");
    $row = $emp->mf_fetch_array($sql);
    extract($row);

    $mem = $emp->mf_query("SELECT iMemberId,vMemberName FROM emp_member WHERE iEmpId = '$id'");

    if ($emp->mf_num_rows($mem) > 0) {
        while ($fetchMem = $emp->mf_fetch_array($mem)) {
            $memberNames[$fetchMem['iMemberId']] = $fetchMem["vMemberName"];
        }
    } else {
        $msg = "<h5>No members found for '$name'</h5>";
    }

    $iTotalMember = $emp->mf_num_rows($mem);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Dropdown -->
    <script src="js/jquery_3.3.1.js"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[readonly] {
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            color: #999;
            cursor: not-allowed;
        }

        input[readonly]:hover {
            background-color: #ddd;
            border: 1px solid #bbb;
            color: #888;
        }

        a {
            text-decoration: none !important;
            color: white;
        }
    </style>

</head>

<body>
    <div class="container-fluid mt-3">
        <button type="submit" name="view" class="btn btn-dark"><a href="index.php">View Data</a></button>
    </div>
    <div class="container-fluid col-md-6">
        <h3 class="text-center mt-2">Update Employee Data</h3>
        <form action="" class="m-3" id="form" method="post" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="<?= $id ?>">
            <div class="form-outline mb-3">
                <label class="form-label">Name :</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= $name; ?>" onkeypress="return onlyAlpha(event)">
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">Email :</label>
                <input type="text" name="email" id="email" class="form-control" value="<?= $email ?>" readonly title="You can't change Email, Contact Admin">
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">Phone :</label>
                <input type="number" name="phone" id="phone" class="form-control" value="<?= $phone ?>">
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">Gender :</label>
                <br>
                <input type="radio" name="gender" value="male" required <?php if ($row['gender'] == "male") {
                                                                            echo "checked";
                                                                        } ?>> Male
                <input type="radio" name="gender" value="female" <?php if ($row['gender'] == "female") {
                                                                        echo "checked";
                                                                    } ?>> Female
                <input type="radio" name="gender" value="transgender" <?php if ($row['gender'] == "transgender") {
                                                                            echo "checked";
                                                                        } ?>> Transgender
                <input type="radio" name="gender" value="other" <?php if ($row['gender'] == "other") {
                                                                    echo "checked";
                                                                } ?>> Other
            </div>
            <div class="form-outline mb-3">
                <label class="form-label">Country :</label>

                <select name="country" id="country" class="form-control">
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
                <label class="form-label">Image :</label>
                <img src="uploads/<?php echo $image; ?>" alt="Sorry!No Image Uploaded!!!" height="100">
                <input type="file" name="image" id="image" placeholder="Enter Your Image" class="form-control mt-2" onchange="validateImage(event)">
                <span id="check-image" style='color:red'></span>
            </div>
            <?php
            if ($iTotalMember > 0) {

                /* $sql = $emp->mf_query("SELECT COUNT(iMemberId) FROM emp_member WHERE iEmpId = '$id'");
                // Get the total number of rows from the result set.
                $total_rows = mysqli_fetch_row($sql)[0]; */
            ?>
                <div id="dynamicFami">

                    <?php
                    $i = 1;
                    foreach ($memberNames as $key => $memberName) {
                        echo '
                            <div class="form-outline mb-3">
                                <label class="form-label">Member ' . $i . ':</label>
                                <input type="text" name="vMember_' . $i . '" id="vMember_' . $i . '" class="form-control" value="' . $memberName . '">
                                <i type="button" class="bi bi-trash delete_member p-2" data-id="' . $i . '" value="Delete"></i>
                                <input type="hidden" name="iMemberId_' . $i . '" id="iMemberId_' . $i . '" value="' . $key . '" />
                                ';
                        $i++;
                    }
                    ?>
                    <!-- <label class="form-label">Member 1 :</label>
                        <input type="text" name="vMember_1" id="vMember_1" class="form-control" placeholder="Enter Your Family Member">
                         -->
                </div>
                <input type="hidden" id="iTotalMemer" name="iTotalMemer" value="<?= $iTotalMember ?>" />
    </div>
<?php
            } else {
?><div id="dynamicFami">
        <?php
                echo $msg;
        ?>
        <input type="hidden" id="iTotalMemer" name="iTotalMemer" value="<?= $iTotalMember ?>" />
    </div>
<?php
            }
?>
<div class="form-outline mb-3" id="dynamicData">
    <input type="button" id="addFMember" name="addFMember" value="Add More Family Members" class="btn btn-dark p-2">
</div>

<div class="form-outline mb-3">
    <input type="submit" name="updateEmp" id="updateEmp" class="btn btn-dark p-2" value="Update">
</div>
</form>
</div>
</body>

</html>

<script src="js/ajax.js"></script>

<script>
    function onlyAlpha(event) {
        var char = event.which;
        if (char > 31 && char != 32 && (char < 65 || char > 90) && (char < 97 || char > 122)) {
            return false;
        }
    }

    var x = 'notUpload';

    function validateImage(event) {

        var submitButton = document.getElementById('updateEmp');

        document.getElementById('check-image').innerHTML = '';

        if (x == 'uploaded') {

            document.getElementById('output').remove();
            x = 'notUpload';

        }

        var image = document.getElementById('image');

        //this will store image in temp path
        var filename = image.value;
        console.log('Selected file name :', filename);

        if (filename != '') {

            //below variabl gives us the position of . in filename
            var extDotPos = filename.lastIndexOf(".") + 1;
            console.log('Position of DOT in image name :', extDotPos);

            //this will give image file extension and convert in into lower case
            var ext = filename.substr(extDotPos, filename.length).toLowerCase();
            console.log('Image file extension :', ext);

            if (ext == "jpg" || ext == "png" || ext == "jpeg") {
                submitButton.disabled = false;
            } else {
                document.getElementById('check-image').innerHTML = 'Please Select only jpg, jpeg and png File';
                submitButton.disabled = true;
            }
        }
    }
</script>