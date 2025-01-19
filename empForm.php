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
        $memberMsg = "<h5>No members found for '$name'</h5>";
    }

    $iTotalMember = $emp->mf_num_rows($mem);
}

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
                <input type="text" name="name" id="name" class="form-control" value="<?= $name; ?>" autocomplete="off" onkeypress="return onlyAlpha(event)" oninput="checkName()">
                <input type="hidden" id="validName" value="success">
                <span id="check-name" style='color:red'></span>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="email">Email :</label>
                <input type="text" name="email" id="email" class="form-control" value="<?= $email ?>" autocomplete="off" oninput="checkEmail()">
                <input type="hidden" id="validEmail" value="success">
                <span id="check-email" style='color:red'></span>
            </div>
            <div class="form-outline mb-3">
                <label class="form-label" for="phone">Phone :</label>
                <input type="number" name="phone" id="phone" class="form-control" value="<?= $phone ?>" autocomplete="off" oninput="checkPhone()">
                <input type="hidden" id="validPhone" value="success">
                <span id="check-phone" style='color:red'></span>
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
                <input type="file" name="image" id="image" placeholder="Enter Your Image" class="form-control mt-2" onchange="checkImage()">
                <input type="hidden" id="validImage" value="success">
                <span id="check-image" style='color:red'></span>
            </div>
            <?php
            if ($iTotalMember > 0) {

                /* $sql = $emp->mf_query("SELECT COUNT(iMemberId) FROM emp_member WHERE iEmpId = '$id'");
                // Get the total number of rows from the result set.
                $total_rows = mysqli_fetch_row($sql)[0]; */
            ?>
                <div id="dynamicFami">
                    <div class="form-outline mb-3">
                        <?php
                        $i = 1;
                        foreach ($memberNames as $key => $memberName) {
                            echo '
                                <label class="form-label" for="vMember_' . $i . '">Member ' . $i . ':</label>
                                <input type="text" name="vMember_' . $i . '" id="vMember_' . $i . '" class="form-control" value="' . $memberName . '" autocomplete="off">
                                <i type="button" class="bi bi-trash delete_member p-2" data-id="' . $i . '" value="Delete"></i><br>
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
                    echo $memberMsg;
                    ?>
                    <input type="hidden" id="iTotalMemer" name="iTotalMemer" value="<?= $iTotalMember ?>" />
                </div>
            <?php
            }
            ?>
            <div class="form-outline mb-3" id="dynamicData">
                <input type="button" id="addFMember" name="addFMember" value="Add More Family Members" class="btn btn-dark p-2">
            </div>

            <div class="mt-2 mb-2">
                <span id="validMsg" style='color:red'></span>
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
                    <?php
                    if ($image == '') {
                        echo 'required: true';
                    }
                    ?>
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


    //? Check name exist or not while adding 
    function checkName() {
        var name = $('#name').val();
        var empId = $('#id').val();

        //only check after two alphabets added
        if (name.length >= 2) {
            $.ajax({
                type: "POST",
                url: "allAction.php",
                data: {
                    name: name,
                    empId: empId,
                    'action': 'nameValid'
                },
                dataType: 'json',
                success: function(data) {
                    $("#check-name").html(data.message);
                    $("#validName").val(data.status);

                    // Change the color of the message based on the status
                    if (data.status === 'success') {
                        $("#check-name").css('color', 'green');
                    } else {
                        $("#check-name").css('color', 'red');
                    }
                },
                error: function() {}
            });
        }
    }


    //? Check email exist or not while adding 
    function checkEmail() {
        var email = $('#email').val();
        var empId = $('#id').val();

        $.ajax({
            type: "POST",
            url: "allAction.php",
            data: {
                email: email,
                empId: empId,
                'action': 'emailValid'
            },
            dataType: 'json',
            success: function(data) {
                $("#check-email").html(data.message);
                $("#validEmail").val(data.status);

                // Change the color of the message based on the status
                if (data.status === 'success') {
                    $("#check-email").css('color', 'green');
                } else {
                    $("#check-email").css('color', 'red');
                }
            },
            error: function() {}
        });
    }


    //? Check phone exist or not while adding 
    function checkPhone() {
        var phone = $('#phone').val();
        var empId = $('#id').val();

        if (phone.length >= 10) {
            $.ajax({
                type: "POST",
                url: "allAction.php",
                data: {
                    phone: phone,
                    empId: empId,
                    'action': 'phoneValid'
                },
                dataType: 'json',
                success: function(data) {
                    $("#check-phone").html(data.message);
                    $("#validPhone").val(data.status);

                    // Change the color of the message based on the status
                    if (data.status === 'success') {
                        $("#check-phone").css('color', 'green');
                    } else {
                        $("#check-phone").css('color', 'red');
                    }
                },
                error: function() {}
            });
        }
    }

    function checkImage() {
        var imageFile = $('#image')[0].files[0];

        // Create a new FormData object
        var formData = new FormData();

        // Add the image file to the FormData object
        formData.append('image', imageFile);
        formData.append('action', 'imageValid')
        console.log('Selected file name :', imageFile);

        $.ajax({
            type: "POST",
            url: "allAction.php",
            data: formData,
            dataType: 'json',
            success: function(data) {
                $("#check-image").html(data.message);
                $("#validImage").val(data.status);
            },
            error: function() {},
            cache: false,
            contentType: false,
            processData: false
        });
    }


    //? Add Data
    $(document).on('click', '#addEmp', function() {

        var formData = new FormData($('#form')[0]);
        formData.append('action', 'insertEmp');

        $okName = $('#validName').val();
        $okEmail = $('#validEmail').val();
        $okPhone = $('#validPhone').val();
        $okImage = $('#validImage').val();
        $okForm = $("#form").valid();

        if ($okName == 'success' && $okEmail == 'success' && $okPhone == 'success' && $okImage == 'success' && $okForm) {
            $.ajax({
                type: "POST",
                url: 'allAction.php',
                data: formData,
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
        } else {
            $("#validMsg").html('Please, Enter Valid details in All fields.');
        }

    });


    //? Update Data
    $(document).on('click', '#updateEmp', function(e) {
        e.preventDefault();
        var formData = new FormData($('#form')[0]);
        formData.append('action', 'updateEmp');

        $okName = $('#validName').val();
        $okEmail = $('#validEmail').val();
        $okPhone = $('#validPhone').val();
        $okImage = $('#validImage').val();

        if ($okName == 'success' && $okEmail == 'success' && $okPhone == 'success' && $okImage == 'success') {
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
        
        // if($('.delete_member').length < 2){
        //     alert("You can't delete last input field.");
        //     return false;
        // }

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
                    // $('#mem_'+id).remove();
                    // $("#dynamicFami").load(location.href + "#dynamicFami");
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