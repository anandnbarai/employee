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

// if (isset($_REQUEST["updateEmp"])) {

//     $data = array();
//     $tmp_name = $_FILES['image']['tmp_name'];

//     if ($tmp_name != '') {

//         $path = "uploads/";
//         $image = $_FILES['image']['name'];
//         $path = $path . $image;
//         $target_file = $path . basename($image);
//         $img_ex = pathinfo($image, PATHINFO_EXTENSION);
//         $image = random_int(10, 999999) . "." . $img_ex;

//         $sql = $emp->mf_query("SELECT * FROM emp WHERE id='$id'");
//         if ($row = $emp->mf_fetch_array($sql)) {
//             $delete = $row['image'];
//         }
//         unlink($path . $delete);
//         $path = $emp->uploadFile($tmp_name, $image, 'uploads/');
//         $data['image'] = $path;
//     }

//     $data['name'] = $_REQUEST["name"];
//     $data['email'] = $_REQUEST["email"];
//     $data['phone'] = $_REQUEST["phone"];
//     $data['gender'] = $_REQUEST['gender'];
//     $data['country'] = $_REQUEST["country"];
//     $data['state'] = $_REQUEST["state"];
//     $data['city'] = $_REQUEST["city"];
//     // print_pre($data);exit;

//     $emp->mf_dbupdate("emp", $data, " WHERE id = '$id'");

//     if ($emp) {
//         for ($i = 1; $i <= $_POST['iTotalMemer']; $i++) {

//             $iMemberId = intval($_POST['iMemberId_' . $i]);

//             if ($iMemberId > 0) {

//                 $data = array();
//                 $data['vMemberName'] = $_POST['vMember_' . $i];
//                 $iMemberId = intval($_POST['iMemberId_' . $i]);
//                 // echo $iMemberId;exit;
//                 // print_pre($data);exit;
//                 $emp->mf_dbupdate("emp_member", $data, " WHERE iMemberId = '$iMemberId'");
//             } else {

//                 $data = array();
//                 $data['vMemberName'] = $_POST['vMember_' . $i];
//                 $data['iEmpId'] = $id;
//                 $iMemberId = intval($_POST['iMemberId_' . $i]);

//                 $emp->mf_dbinsert("emp_member", $data);
//             }


//             // print_pre($_POST);exit;
//             // echo $_POST['iTotalMemer'];exit;

//             // Get the existing family member names from the database.
//             /* $alreadyMem = $emp->mf_query("SELECT iMemberId,vMemberName FROM emp_member where iEmpId = '$id'");

//             while ($fetchMember = $emp->mf_fetch_array($alreadyMem)) {
//                 $oldMember[$fetchMember['iMemberId']] = $fetchMember["vMemberName"];
//                 // print_pre($fetchMember);
//                 // exit;
//             } */

//             // foreach ($oldMember as $key => $oldMemberName) {

//             //     // print_pre($oldMember);



//             //     for ($j = $key; $j <= $key; $j++) {
//             //         //echo $j;
//             //         //$data = array();
//             //         // $data['vMemberName'] = $oldMemberName;
//             //         // $data['iEmpId'] = $id;
//             //         // print_pre($data);


//             //         for ($i = 1; $i <= $_POST['iTotalMemer']; $i++) {

//             //             $data = array();
//             //             // $vMember = $_POST['vMember_' . $i];
//             //             // $data['iMemberId'] = $key;
//             //             // $data['vMemberName'] = $vMember;
//             //             $vMember = $_POST['vMember_' . $i];
//             //             $data['vMemberName'] = $vMember;
//             //             print_pre($data);

//             //             $emp->mf_dbupdate("emp_member", $data, "WHERE  iMemberId = '$key'");
//             //         }
//             //     }
//             //     exit;
//             // }

//             // $vMember = $_POST['vMember_' . $i];
//             // $iMemberId = $_POST['iMemberId_' . $i];

//             // if ($iMemberId) {

//             //     $data = array();

//             //     $vMember = $_POST['vMember_' . $i];
//             //     $data['vMemberName'] = $vMember;
//             //     $data['iEmpId'] = $id;

//             //     $emp->mf_dbupdate("emp_member", $data, "WHERE iEmpId = '$id'");
//             // }
//         }
//         echo "<script>window.alert('Data Updated')</script>";
//         echo "<script>window.open('index.php','_self')</script>";
//     } else {
//         echo "<script>window.open('form.php','_self')</script>";
//     }
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Form</title>
    <!-- Dropdown -->
    <script src="js/jquery_3.3.1.js"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- form validation -->
    <script src="js/validation.js"></script>
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

        .error {
            font-weight: bold;
            color: red;
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
        <h3 class="text-center mt-2 formTitle" id="dynamicH3">Employee Data</h3>
        <form class="m-3" method="post" id="form" enctype="multipart/form-data">
            <?php
            if (isset($_REQUEST['edit'])) {
            ?>
                <input type="hidden" id="id" name="id" value="<?= $id ?>">
            <?php
            }
            ?>
            <div class="form-outline mb-3">
                <label class="form-label" for="name">Username :</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= $name; ?>" autocomplete="off" onkeypress="return onlyAlpha(event)" onInput="checkName()">
                <span id="check-name"></span>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="email">Email :</label>
                <input type="text" name="email" id="email" class="form-control" value="<?= $email ?>" autocomplete="off" onInput="checkEmail()">
                <span id="check-email"></span>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="phone">Phone :</label>
                <input type="number" name="phone" id="phone" class="form-control" value="<?= $phone ?>" autocomplete="off" onInput="checkPhone()">
                <span id="check-phone"></span>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="gender">Gender :</label>
                <br>
                <input type="radio" name="gender" id="gender" value="male" required <?php if ($row['gender'] == "male") {
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
                <label for="gender" class="error"></label>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="country">Country :</label>

                <select name="country" id="country" class="form-control">
                    <?=
                    $fetch_c = $emp->mf_createcombo("select id,name from countries ORDER BY name ASC", "id", "name", $country, "Select Country");
                    ?>
                </select>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="state">State :</label>
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
                <label class="form-label" for="city">City :</label>
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
                <label class="form-label" for="image">Image :</label>
                <?php
                if (isset($_REQUEST['edit'])) {
                ?>
                    <img src="uploads/<?php echo $image; ?>" alt="Sorry!No Image Uploaded!!!" height="100">
                <?php
                }
                ?>
                <input type="file" name="image" id="image" placeholder="Enter Your Image" class="form-control mt-2" oninput="validateImage(event)">
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
                                <label class="form-label" for="vMember_' . $i . '">Member ' . $i . ':</label>
                                <input type="text" name="vMember_' . $i . '" id="vMember_' . $i . '" class="form-control" value="' . $memberName . '" autocomplete="off">
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
    <?php
    if (isset($_REQUEST['edit'])) {
    ?>
        <input type="submit" id="updateEmp" name="updateEmp" value="Update" class="btn btn-dark p-2">
    <?php
    } else {
    ?>
        <input type="submit" id="addEmp" name="addEmp" value="Add Data" class="btn btn-dark p-2">
    <?php
    }
    ?>

</div>
</form>
</div>
</body>

</html>

<script>
    //? allow only alphabates in name
    function onlyAlpha(event) {
        var char = event.which;
        if (char > 31 && char != 32 && (char < 65 || char > 90) && (char < 97 || char > 122)) {
            return false;
        }
    }
    //? form vallidation
    $(document).ready(function() {
        $('#form').validate({
            rules: {
                name: {
                    minlength: 2,
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    rangelength: [10, 12],
                    number: true
                },
                gender: {
                    required: true,
                },
                country: {
                    required: true
                },
                state: {
                    required: true
                },
                city: {
                    required: true
                },
                image: {
                    required: true
                },
                vMember_1: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: 'Please enter Name.',
                },
                email: {
                    required: 'Please enter Email Address.',
                    email: 'Please enter a valid Email Address.',
                },
                phone: {
                    required: 'Please enter Contact.',
                    rangelength: 'Phone should be 10 digit number.'
                },
                gender: 'Please select any one Option.',
                country: 'Please select Country.',
                state: 'Please select State.',
                city: 'Please select City.',
                image: 'Please upload image.',
                vMember_1: 'Please add atleast one family member'
            },
            submitHandler: function(form) {
                //form.submit();
            }
        });
    });
    
    //? Check email exist or not while adding 
    var checkEmail = function checkEmail() {
        var email = $('#email').val();
        var empId = $('#id').val();

        $.ajax({
            url: "allAction.php",
            data: {
                email: email,
                empId: empId
            },
            type: "POST",
            success: function(data) {
                $("#check-email").html(data);
            },
            error: function() {}
        });
    }

    //? Check name exist or not while adding 
    var checkName = function checkName() {
        var name = $('#name').val();
        var empId = $('#id').val();

        //only check after two alphabets added
        if (name.length >= 2) {
            $.ajax({
                url: "allAction.php",
                data: {
                    name: name,
                    empId: empId
                },
                type: "POST",
                success: function(data) {
                    $("#check-name").html(data);
                },
                error: function() {}
            });
        }
    }

    //? Check phone exist or not while adding 
    var checkPhone = function checkPhone() {
        var phone = $('#phone').val();
        var empId = $('#id').val();

        if (phone.length >= 10) {
            $.ajax({
                url: "allAction.php",
                data: {
                    phone: phone,
                    empId: empId
                },
                type: "POST",
                success: function(data) {
                    $("#check-phone").html(data);
                },
                error: function() {}
            });
        }
    }


    //? Image validation with javascript
    var x = 'notUpload';

    var validateImage = function validateImage(event) {

        var updateButton = document.getElementById('updateEmp');
        var submitButton = document.getElementById('addEmp');

        var isUpdatePage = window.location.href.indexOf('empForm.php?edit=') > -1;
        var isAddPage = window.location.href.indexOf('empForm.php?insert') > -1;

        document.getElementById('check-image').innerHTML = '';
        if (!isUpdatePage) {
            if (x == 'uploaded') {

                document.getElementById('output').remove();

                x = 'notUpload';

            }
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
                x = 'uploaded';



                // display output image after id = image
                if (!isUpdatePage) {
                    //create dynamic image tag to display valid image uploaded
                    var output = document.createElement('img');

                    //below is to provide id to dynamic created img tag
                    output.id = 'output';

                    //URL.createObjectURL(): This is a built-in JavaScript function provided by the URL object. 
                    //It is used to create a unique and temporary URL for a given object.
                    output.src = URL.createObjectURL(event.target.files[0]);

                    // Set the height of the image to 100 pixels
                    output.style.height = '100px';

                    // Set the margin-bottom of the image to 15 pixels
                    output.style.marginBottom = '15px';

                    image.before(output);
                }

                if (isAddPage) {
                    submitButton.disabled = false;
                }

                if (isUpdatePage) {
                    updateButton.disabled = false;
                }

            } else {

                //output will be display in tag having id check-image
                document.getElementById('check-image').innerHTML = 'Please Select only jpg, jpeg and png File';

                if (isAddPage) {
                    submitButton.disabled = true;
                }

                if (isUpdatePage) {
                    updateButton.disabled = true;
                }
            }
        }
    }


    //? Add Data
    $(document).on('click', '#addEmp', function() {
        var formData = new FormData($('#form')[0]);
        formData.append('action', 'insertEmp');
        $('#dynamicH3').text('Add Employee Data');

        if ($("#form").valid()) {
            $.ajax({
                type: "POST",
                url: 'allAction.php',
                data: formData,
                async: false,
                dataType: 'json',
                success: function(data) {

                    alert(data.msg);
                    window.location.replace('index.php');
                },
                error: function(data) {
                    alert("Fill the details");
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });


    //? Update Data
    $(document).on('click', '#updateEmp', function(e) {
        e.preventDefault();
        var formData = new FormData($('#form')[0]);
        formData.append('action', 'updateEmp');
        $('#dynamicH3').text('Update Employee Data');

        if ($("#form").valid()) {
            $.ajax({
                type: "POST",
                url: 'allAction.php',
                data: formData,
                async: false,
                dataType: 'json',
                success: function(data) {
                    alert(data.msg);
                    window.location.replace('index.php');
                },
                error: function(data) {
                    alert("Fill the details");
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });


    //? fetch dropdown state
    $(document).on('change', '#country', function() {
        $('#state').html('<option value="">Select State</option>');
        var cId = $(this).val();
        $.ajax({
            url: 'allAction.php',
            type: "POST",
            data: {
                'action': 'fetchState',
                c_id: cId
            },
            success: function(data) {
                $('#state').append(data);
            }
        });
    });


    //? fetch dropdown city 
    $(document).on('change', '#state', function() {
        $('#city').html('<option value="">Select City</option>');

        var sId = $(this).val();
        $.ajax({
            url: 'allAction.php',
            type: "POST",
            data: {
                'action': 'fetchCity',
                s_id: sId
            },
            success: function(data) {
                $('#city').append(data);
            }
        });
    });


    //? dynamic add family member input field in addData Page
    $(document).on('click', '#addFMember', function() {
        var iTotalMemer = $('#iTotalMemer').val();
        iTotalMemer = (iTotalMemer != '') ? parseInt(iTotalMemer) : 0;
        iTotalMemer++;
        $('#iTotalMemer').val(iTotalMemer);

        $('#dynamicFami').append(`
        <div class="form-outline mb-3" id="mem_` + iTotalMemer + `">
            <label class="form-label">Member ` + iTotalMemer + ` :</label>
            <input type="text" name="vMember_` + iTotalMemer + `" id="vMember_` + iTotalMemer + `" class="form-control" placeholder="Enter Your Family Member">
            <i class="bi bi-trash delete_member p-2" data-id="` + iTotalMemer + `" value="Delete"></i>
        </div>`)
    });


    //? delete member input field in update page
    $(document).on('click', '.delete_member', function() {

        //fetch id of newly added family member dynamic input field
        var id = $(this).attr("data-id");

        //fetch id of already inserted family member
        var db_id = $('#iMemberId_' + id).val();
        // console.log(db_id);

        if (db_id > 0) {
            $.ajax({
                type: 'POST',
                url: "allAction.php",
                data: {
                    'action': 'deleteFamily',
                    db_id: db_id
                },
                dataType: 'json',
                success: function(data) {
                    alert(data.msg);
                    //reload same page after data update, otherwise empty data will added.
                    window.location.reload();
                }
            });
        }

        //gets the number of input fields in the `dynamicFami` element, excluding the field with the ID `iTotalMemer`

        var numFields = $('#dynamicFami input:not(#iTotalMemer)').length;

        //if more than one input field found with input id iTotalMemer then user can delete input field
        if (numFields > 1) {
            $('#mem_' + id).remove();
        } else {
            alert('You cannot delete the last Input Field.');
        }
    });
</script>